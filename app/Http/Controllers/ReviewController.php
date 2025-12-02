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

    /**
     * Store a review from the client side (AJAX).
     */
    public function storeClientReview(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $user = \Illuminate\Support\Facades\Auth::user();
        $supplierId = $request->supplier_id;

        // Verify if the user can review this supplier
        $canReview = \App\Models\EventService::where('supplier_id', $supplierId)
            ->whereHas('event', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->whereIn('status', ['delivered', 'completed'])
            ->exists();

        if (!$canReview) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes dejar una reseña para este proveedor hasta que tengas un servicio entregado.'
            ], 403);
        }

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $review = Review::create([
                'supplier_id' => $supplierId,
                'user_id' => $user->id,
                'rating' => $request->rating,
                'comment' => $request->comment,
                'date_posted' => now(),
            ]);

            $this->updateSupplierRating($supplierId);

            \Illuminate\Support\Facades\DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Reseña enviada exitosamente.',
                'review' => [
                    'id' => $review->id,
                    'user_name' => $user->name,
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'date_posted' => $review->date_posted->format('d M, Y'),
                    'time_ago' => $review->created_at->diffForHumans(),
                ]
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar la reseña: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * Display a listing of the resource for the logged-in supplier.
     */
    public function supplierIndex()
    {
        $supplier = \Illuminate\Support\Facades\Auth::user()->supplier;

        if (!$supplier) {
            abort(403, 'Acceso denegado. No eres un proveedor.');
        }

        $reviews = $supplier->reviews()->with('user')->latest()->paginate(10);

        // Calculate stats
        $ratingCounts = [
            5 => $supplier->reviews()->where('rating', 5)->count(),
            4 => $supplier->reviews()->where('rating', 4)->count(),
            3 => $supplier->reviews()->where('rating', 3)->count(),
            2 => $supplier->reviews()->where('rating', 2)->count(),
            1 => $supplier->reviews()->where('rating', 1)->count(),
        ];

        $averageRating = $supplier->rating_avg;
        $totalReviews = $supplier->reviews()->count();

        return view('supplier.reviews.index', compact('reviews', 'ratingCounts', 'averageRating', 'totalReviews'));
    }
}
