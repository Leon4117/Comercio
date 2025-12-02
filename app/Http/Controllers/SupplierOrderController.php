<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierOrderController extends Controller
{
    /**
     * Display the supplier dashboard with orders.
     */
    /**
     * Display the supplier dashboard with orders.
     */
    public function dashboard()
    {
        $user = Auth::user();
        $supplier = Supplier::where('user_id', $user->id)->first();

        if (!$supplier) {
            return redirect()->route('supplier.registration-form');
        }

        // Obtener estadísticas
        $pendingOrders = \App\Models\EventService::where('supplier_id', $supplier->id)
            ->whereIn('status', ['requested', 'quoted', 'confirmed'])
            ->with(['event.user', 'service'])
            ->get()
            ->sortBy(function ($order) {
                return $order->event->event_date;
            })
            ->take(5); // Limit to 5 for dashboard

        $completedOrders = \App\Models\EventService::where('supplier_id', $supplier->id)
            ->whereIn('status', ['delivered', 'completed'])
            ->with(['event.user', 'service'])
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        $totalEarnings = \App\Models\EventService::where('supplier_id', $supplier->id)
            ->whereIn('status', ['delivered', 'completed'])
            ->sum('final_price');

        $stats = [
            'pending_orders' => \App\Models\EventService::where('supplier_id', $supplier->id)->whereIn('status', ['requested', 'quoted', 'confirmed'])->count(),
            'completed_orders' => \App\Models\EventService::where('supplier_id', $supplier->id)->whereIn('status', ['delivered', 'completed'])->count(),
            'total_earnings' => $totalEarnings,
            'average_rating' => $supplier->rating_avg ?? 0,
        ];

        // --- Chart Data Generation ---
        $startOfMonth = \Carbon\Carbon::now()->startOfMonth();
        $endOfMonth = \Carbon\Carbon::now()->endOfMonth();

        // 1. Pie Chart: Status of last orders of the month
        $statusStats = \App\Models\EventService::where('supplier_id', $supplier->id)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->select('status', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        $pieChartData = [
            'labels' => [],
            'data' => [],
            'colors' => []
        ];

        $statusLabels = [
            'requested' => 'Solicitado',
            'quoted' => 'Cotizando',
            'confirmed' => 'Confirmado',
            'delivered' => 'Entregado',
            'cancelled' => 'Cancelado',
            'completed' => 'Completado',
        ];

        $statusColors = [
            'requested' => '#3B82F6', // Blue
            'quoted' => '#F59E0B',    // Yellow
            'confirmed' => '#10B981', // Green
            'delivered' => '#8B5CF6', // Purple
            'cancelled' => '#EF4444', // Red
            'completed' => '#059669', // Dark Green
        ];

        foreach ($statusStats as $stat) {
            $pieChartData['labels'][] = $statusLabels[$stat->status] ?? ucfirst($stat->status);
            $pieChartData['data'][] = $stat->total;
            $pieChartData['colors'][] = $statusColors[$stat->status] ?? '#9CA3AF';
        }

        // 2. Earnings Chart
        $earningsStats = \App\Models\EventService::where('supplier_id', $supplier->id)
            ->whereIn('status', ['delivered', 'completed'])
            ->whereBetween('updated_at', [$startOfMonth, $endOfMonth])
            ->select(\Illuminate\Support\Facades\DB::raw('DATE(updated_at) as date'), \Illuminate\Support\Facades\DB::raw('SUM(final_price) as total'))
            ->groupBy(\Illuminate\Support\Facades\DB::raw('DATE(updated_at)'))
            ->orderBy(\Illuminate\Support\Facades\DB::raw('DATE(updated_at)'))
            ->get();

        $barChartData = [
            'labels' => [],
            'data' => []
        ];

        foreach ($earningsStats as $stat) {
            $barChartData['labels'][] = \Carbon\Carbon::parse($stat->date)->format('d M');
            $barChartData['data'][] = $stat->total;
        }

        return view('supplier.dashboard', compact('pendingOrders', 'completedOrders', 'stats', 'supplier', 'pieChartData', 'barChartData'));
    }

    /**
     * Display all pending orders.
     */
    public function pendingOrders()
    {
        $user = Auth::user();
        $supplier = Supplier::where('user_id', $user->id)->first();

        if (!$supplier) {
            return redirect()->route('supplier.registration-form');
        }

        $pendingOrders = \App\Models\EventService::where('supplier_id', $supplier->id)
            ->whereIn('status', ['requested', 'quoted', 'confirmed'])
            ->with(['event.user', 'service'])
            ->get()
            ->sortBy(function ($order) {
                return $order->event->event_date;
            });
            // Pagination can be added if needed, but sortBy on collection breaks simple pagination.
            // For now, returning all as per request "donde se vean todos".

        return view('supplier.pending-orders', compact('pendingOrders', 'supplier'));
    }

    /**
     * Confirm order delivery.
     */
    public function confirmDelivery(\App\Models\EventService $eventService)
    {
        $user = Auth::user();
        $supplier = Supplier::where('user_id', $user->id)->first();

        // Verificar que el pedido pertenece al proveedor
        if ($eventService->supplier_id !== $supplier->id) {
            abort(403, 'No tienes permisos para modificar este pedido.');
        }

        // Solo se puede confirmar entrega si está confirmado
        if ($eventService->status !== 'confirmed') {
            return redirect()->back()->with('error', 'Solo se pueden marcar como entregados los pedidos confirmados.');
        }

        $eventService->update(['status' => 'delivered']);

        return redirect()->back()->with('success', 'Pedido marcado como entregado exitosamente.');
    }

    /**
     * Cancel order.
     */
    public function cancelOrder(\App\Models\EventService $eventService)
    {
        $user = Auth::user();
        $supplier = Supplier::where('user_id', $user->id)->first();

        // Verificar que el pedido pertenece al proveedor
        if ($eventService->supplier_id !== $supplier->id) {
            abort(403, 'No tienes permisos para modificar este pedido.');
        }

        // Solo se pueden cancelar pedidos en solicitud, cotización o confirmados
        if (!in_array($eventService->status, ['requested', 'quoted', 'confirmed'])) {
            return redirect()->back()->with('error', 'No se puede cancelar este pedido en su estado actual.');
        }

        $eventService->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Pedido cancelado exitosamente.');
    }

    /**
     * Show completed orders.
     */
    public function completedOrders()
    {
        $user = Auth::user();
        $supplier = Supplier::where('user_id', $user->id)->first();

        if (!$supplier) {
            return redirect()->route('supplier.registration-form');
        }

        $completedOrders = \App\Models\EventService::where('supplier_id', $supplier->id)
            ->whereIn('status', ['delivered', 'completed'])
            ->with(['event.user', 'service'])
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        $totalEarnings = \App\Models\EventService::where('supplier_id', $supplier->id)
            ->whereIn('status', ['delivered', 'completed'])
            ->sum('final_price');

        return view('supplier.completed-orders', compact('completedOrders', 'totalEarnings', 'supplier'));
    }

    /**
     * Confirm the service request.
     */
    public function confirmRequest(\App\Models\EventService $eventService)
    {
        $user = Auth::user();
        $supplier = Supplier::where('user_id', $user->id)->first();

        // Verificar que el pedido pertenece al proveedor
        if ($eventService->supplier_id !== $supplier->id) {
            abort(403, 'No tienes permisos para modificar este pedido.');
        }

        // Solo se puede confirmar si está en estado 'requested' o 'quoted'
        if (!in_array($eventService->status, ['requested', 'quoted'])) {
             return redirect()->back()->with('error', 'Solo se pueden confirmar pedidos pendientes.');
        }

        // Calcular precio final basado en el servicio
        $service = $eventService->service;
        $finalPrice = $service->base_price;

        if ($eventService->urgent && $service->urgent_available) {
            $finalPrice += $service->urgent_price_extra ?? 0;
        }

        $eventService->update([
            'final_price' => $finalPrice,
            'status' => 'confirmed'
        ]);

        return redirect()->back()->with('success', 'Pedido confirmado exitosamente.');
    }
}
