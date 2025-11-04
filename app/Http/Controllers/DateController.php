<?php

namespace App\Http\Controllers;

use App\Models\Date;
use App\Models\Supplier;
use App\Models\Order;
use Illuminate\Http\Request;

class DateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dates = Date::with(['supplier.user', 'order'])->get();
        return view('dates.index', compact('dates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::with('user')->get();
        $orders = Order::with(['supplier.user', 'user'])->get();
        return view('dates.create', compact('suppliers', 'orders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_id' => 'nullable|exists:orders,id',
            'date' => 'required|date',
            'status' => 'required|in:booked,urgent',
        ]);

        Date::create($validated);

        return redirect()->route('dates.index')
            ->with('success', 'Fecha creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Date $date)
    {
        $date->load(['supplier.user', 'order']);
        return view('dates.show', compact('date'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Date $date)
    {
        $suppliers = Supplier::with('user')->get();
        $orders = Order::with(['supplier.user', 'user'])->get();
        return view('dates.edit', compact('date', 'suppliers', 'orders'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Date $date)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_id' => 'nullable|exists:orders,id',
            'date' => 'required|date',
            'status' => 'required|in:booked,urgent',
        ]);

        $date->update($validated);

        return redirect()->route('dates.index')
            ->with('success', 'Fecha actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Date $date)
    {
        $date->delete();

        return redirect()->route('dates.index')
            ->with('success', 'Fecha eliminada exitosamente.');
    }

    /**
     * Get available dates for a supplier.
     */
    public function getAvailableDates(Request $request, Supplier $supplier)
    {
        $bookedDates = $supplier->dates()->pluck('date')->toArray();
        
        return response()->json([
            'booked_dates' => $bookedDates
        ]);
    }
}
