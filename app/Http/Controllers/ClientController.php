<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventService;
use App\Models\Service;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    /**
     * Display the client dashboard with pending events.
     */
    public function dashboard(Request $request)
    {
        $user = Auth::user();

        // Obtener eventos del cliente
        $events = Event::forUser($user->id)
                      ->with(['eventServices.service', 'eventServices.supplier'])
                      ->orderBy('event_date', 'asc')
                      ->get();

        // Determinar el evento seleccionado
        $selectedEvent = null;
        if ($request->has('event')) {
            $selectedEvent = $events->firstWhere('id', $request->event);
        }

        if (!$selectedEvent && $events->isNotEmpty()) {
            $selectedEvent = $events->first();
        }

        // Estadísticas rápidas
        $stats = [
            'total_events' => $events->count(),
            'upcoming_events' => $events->where('event_date', '>=', now()->toDateString())->count(),
            'confirmed_services' => EventService::whereHas('event', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('status', 'confirmed')->count(),
            'pending_quotes' => EventService::whereHas('event', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('status', 'quoted')->count(),
        ];

        $filteredServices = collect();
        if ($selectedEvent) {
            $query = $selectedEvent->eventServices()->with(['service', 'supplier.user']);

            if ($request->has('status') && $request->status !== 'all') {
                $query->where('status', $request->status);
            }

            $filteredServices = $query->latest()->get();
        }

        return view('client.dashboard', compact('events', 'stats', 'selectedEvent', 'filteredServices'));
    }

    /**
     * Show the form for creating a new event.
     */
    public function createEvent()
    {
        $categories = Category::all();
        return view('client.events.create', compact('categories'));
    }

    /**
     * Store a newly created event.
     */
    public function storeEvent(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'event_date' => 'required|date|after:today',
            'location' => 'required|string|max:255',
            'budget' => 'nullable|numeric|min:0',
            'guests_count' => 'nullable|integer|min:1',
        ]);

        $event = Event::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'description' => $request->description,
            'event_date' => $request->event_date,
            'location' => $request->location,
            'budget' => $request->budget,
            'guests_count' => $request->guests_count,
            'status' => 'planning',
        ]);

        return redirect()->route('client.dashboard')
                        ->with('success', 'Evento creado exitosamente.');
    }

    /**
     * Request a service for an event.
     */
    public function requestService(Request $request)
    {
        try {
            $request->validate([
                'event_id' => 'required|exists:events,id',
                'service_id' => 'required|exists:services,id',
                'urgent' => 'boolean',
                'notes' => 'nullable|string|max:500',
            ]);

            $service = Service::findOrFail($request->service_id);

            // Verificar que el evento pertenece al usuario autenticado
            $event = Event::where('id', $request->event_id)
                         ->where('user_id', Auth::id())
                         ->firstOrFail();

            // Crear la solicitud de servicio
            EventService::create([
                'event_id' => $request->event_id,
                'service_id' => $request->service_id,
                'supplier_id' => $service->supplier_id,
                'status' => 'requested',
                'urgent' => $request->input('urgent', false),
                'notes' => $request->notes,
                'chat_link' => $service->supplier->user_id ? route('chat.start', $service->supplier->user_id) : null,
            ]);

            // Si es una petición AJAX, devolver JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Servicio solicitado exitosamente.'
                ]);
            }

            return redirect()->back()
                            ->with('success', 'Servicio solicitado exitosamente.');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al solicitar el servicio: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                            ->with('error', 'Error al solicitar el servicio: ' . $e->getMessage());
        }
    }

    /**
     * Confirm delivery of a service.
     */
    public function confirmDelivery(EventService $eventService)
    {
        // Verificar que el servicio pertenece a un evento del usuario
        if ($eventService->event->user_id !== Auth::id()) {
            abort(403, 'No tienes permisos para realizar esta acción.');
        }

        // Solo se puede confirmar si el proveedor ya marcó como entregado
        if ($eventService->status !== 'delivered') {
            return redirect()->back()
                           ->with('error', 'El proveedor debe confirmar la entrega primero.');
        }

        // Actualizar estado a completado
        $eventService->update(['status' => 'completed']);

        // Aquí podrías agregar lógica adicional como calificaciones

        return redirect()->back()
                        ->with('success', 'Entrega confirmada exitosamente.')
                        ->with('review_supplier_id', $eventService->supplier_id);
    }

    /**
     * Cancel a service request.
     */
    public function cancelService(EventService $eventService)
    {
        // Verificar que el servicio pertenece a un evento del usuario
        if ($eventService->event->user_id !== Auth::id()) {
            abort(403, 'No tienes permisos para realizar esta acción.');
        }

        // Solo se puede cancelar si no está entregado
        if ($eventService->status === 'delivered') {
            return redirect()->back()
                           ->with('error', 'No se puede cancelar un servicio ya entregado.');
        }

        $eventService->update(['status' => 'cancelled']);

        return redirect()->back()
                        ->with('success', 'Servicio cancelado exitosamente.');
    }

    /**
     * Show available services to add to an event.
     */
    public function showServices(Event $event)
    {
        // Verificar que el evento pertenece al usuario
        if ($event->user_id !== Auth::id()) {
            abort(403, 'No tienes permisos para ver este evento.');
        }

        $categories = Category::all();

        return view('client.events.services', compact('event', 'categories'));
    }

    /**
     * Search services by categories for an event.
     */
    public function searchServices(Request $request, Event $event)
    {
        $request->validate([
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
        ]);

        // Verificar que el evento pertenece al usuario
        if ($event->user_id !== Auth::id()) {
            abort(403, 'No tienes permisos para ver este evento.');
        }

        // Buscar proveedores aprobados en las categorías seleccionadas con sus servicios activos
        $suppliers = \App\Models\Supplier::with(['user', 'category', 'services' => function($query) {
                $query->where('active', true);
            }])
            ->where('status', 'approved')
            ->whereIn('category_id', $request->categories)
            ->whereHas('services', function($query) {
                $query->where('active', true);
            })
            ->get()
            ->map(function($supplier) {
                return $this->formatSupplier($supplier, true);
            });

        return response()->json(['suppliers' => $suppliers]);
    }

    /**
     * Get supplier details.
     */
    public function getSupplier(\App\Models\Supplier $supplier)
    {
        return response()->json(['supplier' => $this->formatSupplier($supplier)]);
    }

    /**
     * Format supplier data.
     */
    private function formatSupplier($supplier, $forSearch = false)
    {
        $reviews = $supplier->reviews()->with('user')->latest()->get();
        $ratingCounts = [
            5 => $reviews->where('rating', 5)->count(),
            4 => $reviews->where('rating', 4)->count(),
            3 => $reviews->where('rating', 3)->count(),
            2 => $reviews->where('rating', 2)->count(),
            1 => $reviews->where('rating', 1)->count(),
        ];

        // Check if user can review (has delivered service)
        $canReview = \App\Models\EventService::where('supplier_id', $supplier->id)
            ->whereHas('event', function($q) {
                $q->where('user_id', Auth::id());
            })
            ->whereIn('status', ['delivered', 'completed'])
            ->exists();

        return [
            'id' => $supplier->id,
            'user' => [
                'id' => $supplier->user->id,
                'name' => $supplier->user->name,
                'phone' => $supplier->user->phone,
            ],
            'category' => $supplier->category->name,
            'location' => $supplier->location,
            'price_range' => $supplier->price_range,
            'description' => $supplier->description,
            'average_rating' => $supplier->rating_avg,
            'reviews_count' => $reviews->count(),
            'rating_counts' => $ratingCounts,
            'reviews' => $reviews->map(function($review) {
                return [
                    'id' => $review->id,
                    'user_name' => $review->user->name,
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'date_posted' => $review->date_posted->format('d M, Y'),
                    'time_ago' => $review->created_at->diffForHumans(),
                ];
            }),
            'can_review' => $forSearch ? false : $canReview,
            'services' => $supplier->services->map(function($service) use ($supplier) {
                return [
                    'id' => $service->id,
                    'name' => $service->name,
                    'description' => $service->description,
                    'base_price' => $service->base_price,
                    'urgent_available' => $service->urgent_available,
                    'urgent_price_extra' => $service->urgent_price_extra,
                    'main_image' => $service->main_image,
                    'main_image_url' => $service->getMainImageUrl(),
                ];
            })
        ];

    }
}

