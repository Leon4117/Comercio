<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with(['supplier.user', 'user'])->get();
        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::with('user')->get();
        $users = User::where('role', 'client')->get();
        return view('orders.create', compact('suppliers', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'user_id' => 'required|exists:users,id',
            'event_date' => 'required|date|after:today',
            'quote_price' => 'nullable|numeric|min:0',
            'status' => 'required|in:quoting,confirmed,completed,cancelled',
            'quote_price_final' => 'nullable|numeric|min:0',
            'urgent' => 'boolean',
            'urgent_price' => 'nullable|numeric|min:0',
            'chat_link' => 'nullable|string',
        ]);

        Order::create($validated);

        return redirect()->route('orders.index')
            ->with('success', 'Pedido creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load(['supplier.user', 'user']);
        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $suppliers = Supplier::with('user')->get();
        $users = User::where('role', 'client')->get();
        return view('orders.edit', compact('order', 'suppliers', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'user_id' => 'required|exists:users,id',
            'event_date' => 'required|date',
            'quote_price' => 'nullable|numeric|min:0',
            'status' => 'required|in:quoting,confirmed,completed,cancelled',
            'quote_price_final' => 'nullable|numeric|min:0',
            'urgent' => 'boolean',
            'urgent_price' => 'nullable|numeric|min:0',
            'chat_link' => 'nullable|string',
        ]);

        $order->update($validated);

        return redirect()->route('orders.index')
            ->with('success', 'Pedido actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('orders.index')
            ->with('success', 'Pedido eliminado exitosamente.');
    }

    /**
     * Update order status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:quoting,confirmed,completed,cancelled',
            'quote_price_final' => 'nullable|numeric|min:0',
        ]);

        $order->update($validated);

        return redirect()->back()
            ->with('success', 'Estado del pedido actualizado exitosamente.');
    }
}
