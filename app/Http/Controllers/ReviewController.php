<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reviews = Review::with(['supplier.user', 'user'])->get();
        return view('reviews.index', compact('reviews'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::with('user')->get();
        $users = User::where('role', 'client')->get();
        return view('reviews.create', compact('suppliers', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'user_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'date_posted' => 'required|date',
            'comment' => 'nullable|string',
        ]);

        $review = Review::create($validated);

        // Update supplier rating average
        $this->updateSupplierRating($review->supplier_id);

        return redirect()->route('reviews.index')
            ->with('success', 'Reseña creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        $review->load(['supplier.user', 'user']);
        return view('reviews.show', compact('review'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Review $review)
    {
        $suppliers = Supplier::with('user')->get();
        $users = User::where('role', 'client')->get();
        return view('reviews.edit', compact('review', 'suppliers', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Review $review)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'user_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'date_posted' => 'required|date',
            'comment' => 'nullable|string',
        ]);

        $review->update($validated);

        // Update supplier rating average
        $this->updateSupplierRating($review->supplier_id);

        return redirect()->route('reviews.index')
            ->with('success', 'Reseña actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        $supplierId = $review->supplier_id;
        $review->delete();

        // Update supplier rating average
        $this->updateSupplierRating($supplierId);

        return redirect()->route('reviews.index')
            ->with('success', 'Reseña eliminada exitosamente.');
    }

    /**
     * Update supplier rating average.
     */
    private function updateSupplierRating($supplierId)
    {
        $supplier = Supplier::find($supplierId);
        if ($supplier) {
            $avgRating = $supplier->reviews()->avg('rating') ?? 0;
            $supplier->update(['rating_avg' => round($avgRating, 2)]);
        }
    }
}
