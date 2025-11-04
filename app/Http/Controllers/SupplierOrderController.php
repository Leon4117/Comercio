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
    public function dashboard()
    {
        $user = Auth::user();
        $supplier = Supplier::where('user_id', $user->id)->first();

        if (!$supplier) {
            return redirect()->route('supplier.registration-form');
        }

        // Obtener estadísticas
        $pendingOrders = Order::where('supplier_id', $supplier->id)
            ->whereIn('status', ['quoting', 'confirmed'])
            ->with('user')
            ->orderBy('event_date', 'asc')
            ->get();

        $completedOrders = Order::where('supplier_id', $supplier->id)
            ->where('status', 'completed')
            ->with('user')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        $totalEarnings = Order::where('supplier_id', $supplier->id)
            ->where('status', 'completed')
            ->sum('quote_price_final');

        $stats = [
            'pending_orders' => $pendingOrders->count(),
            'completed_orders' => Order::where('supplier_id', $supplier->id)->where('status', 'completed')->count(),
            'total_earnings' => $totalEarnings,
            'average_rating' => $supplier->rating_avg ?? 0,
        ];

        return view('supplier.dashboard', compact('pendingOrders', 'completedOrders', 'stats', 'supplier'));
    }

    /**
     * Confirm order delivery.
     */
    public function confirmDelivery(Order $order)
    {
        $user = Auth::user();
        $supplier = Supplier::where('user_id', $user->id)->first();

        // Verificar que el pedido pertenece al proveedor
        if ($order->supplier_id !== $supplier->id) {
            abort(403, 'No tienes permisos para modificar este pedido.');
        }

        // Solo se puede confirmar entrega si está confirmado
        if ($order->status !== 'confirmed') {
            return redirect()->back()->with('error', 'Solo se pueden marcar como entregados los pedidos confirmados.');
        }

        $order->update(['status' => 'completed']);

        return redirect()->back()->with('success', 'Pedido marcado como entregado exitosamente.');
    }

    /**
     * Cancel order.
     */
    public function cancelOrder(Order $order)
    {
        $user = Auth::user();
        $supplier = Supplier::where('user_id', $user->id)->first();

        // Verificar que el pedido pertenece al proveedor
        if ($order->supplier_id !== $supplier->id) {
            abort(403, 'No tienes permisos para modificar este pedido.');
        }

        // Solo se pueden cancelar pedidos en cotización o confirmados
        if (!in_array($order->status, ['quoting', 'confirmed'])) {
            return redirect()->back()->with('error', 'No se puede cancelar este pedido en su estado actual.');
        }

        $order->update(['status' => 'cancelled']);

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

        $completedOrders = Order::where('supplier_id', $supplier->id)
            ->where('status', 'completed')
            ->with('user')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        $totalEarnings = Order::where('supplier_id', $supplier->id)
            ->where('status', 'completed')
            ->sum('quote_price_final');

        return view('supplier.completed-orders', compact('completedOrders', 'totalEarnings', 'supplier'));
    }

    /**
     * Update quote price for an order.
     */
    public function updateQuote(Request $request, Order $order)
    {
        $request->validate([
            'quote_price_final' => 'required|numeric|min:0',
        ]);

        $user = Auth::user();
        $supplier = Supplier::where('user_id', $user->id)->first();

        // Verificar que el pedido pertenece al proveedor
        if ($order->supplier_id !== $supplier->id) {
            abort(403, 'No tienes permisos para modificar este pedido.');
        }

        // Solo se puede actualizar cotización si está en estado 'quoting'
        if ($order->status !== 'quoting') {
            return redirect()->back()->with('error', 'Solo se puede actualizar la cotización de pedidos en proceso de cotización.');
        }

        $order->update([
            'quote_price_final' => $request->quote_price_final,
            'status' => 'confirmed'
        ]);

        return redirect()->back()->with('success', 'Cotización enviada y pedido confirmado exitosamente.');
    }
}
