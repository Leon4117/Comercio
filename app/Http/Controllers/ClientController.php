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
    public function dashboard()
    {
        $user = Auth::user();

        // Obtener eventos del cliente
        $events = Event::forUser($user->id)
                      ->with(['eventServices.service', 'eventServices.supplier'])
                      ->orderBy('event_date', 'asc')
                      ->get();

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

        return view('client.dashboard', compact('events', 'stats'));
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
            'urgent' => $request->has('urgent'),
            'notes' => $request->notes,
            'chat_link' => 'https://wa.me/' . str_replace(['+', ' ', '-'], '', $service->supplier->user->phone),
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

        // Aquí podrías agregar lógica adicional como calificaciones
        
        return redirect()->back()
                        ->with('success', 'Entrega confirmada exitosamente.');
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

        // Buscar servicios activos de proveedores aprobados en las categorías seleccionadas
        $services = Service::with(['supplier.user'])
            ->whereHas('supplier', function($query) use ($request) {
                $query->where('status', 'approved')
                      ->whereIn('category_id', $request->categories);
            })
            ->where('active', true)
            ->get()
            ->map(function($service) {
                return [
                    'id' => $service->id,
                    'name' => $service->name,
                    'description' => $service->description,
                    'base_price' => $service->base_price,
                    'urgent_available' => $service->urgent_available,
                    'urgent_price_extra' => $service->urgent_price_extra,
                    'main_image' => $service->main_image,
                    'main_image_url' => $service->getMainImageUrl(),
                    'supplier' => [
                        'id' => $service->supplier->id,
                        'user' => [
                            'name' => $service->supplier->user->name,
                            'phone' => $service->supplier->user->phone,
                        ]
                    ]
                ];
            });

        return response()->json([
            'success' => true,
            'services' => $services
        ]);
    }
}
