<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();

    // Si es administrador, redirigir al dashboard de admin
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    // Si es proveedor, verificar su estado
    if ($user->role === 'supplier') {
        $supplier = \App\Models\Supplier::where('user_id', $user->id)->first();

        // Si no tiene perfil de proveedor, redirigir al formulario
        if (!$supplier) {
            return redirect()->route('supplier.registration-form');
        }

        // Si está pendiente de aprobación
        if ($supplier->status === 'pending') {
            return redirect()->route('auth.pending-approval');
        }

        // Si fue rechazado
        if ($supplier->status === 'rejected') {
            return redirect()->route('auth.supplier-rejected');
        }

        // Si está aprobado, redirigir al dashboard de proveedor
        if ($supplier->status === 'approved') {
            return redirect()->route('supplier.dashboard');
        }
    }

    // Si es cliente, redirigir al dashboard de cliente
    if ($user->role === 'client') {
        return redirect()->route('client.dashboard');
    }

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Rutas de proveedor
Route::middleware(['auth', 'verified'])->prefix('supplier')->name('supplier.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\SupplierOrderController::class, 'dashboard'])->name('dashboard');
    Route::get('/completed-orders', [App\Http\Controllers\SupplierOrderController::class, 'completedOrders'])->name('completed-orders');
    Route::get('/pending-orders', [App\Http\Controllers\SupplierOrderController::class, 'pendingOrders'])->name('pending-orders');
    Route::patch('/orders/{eventService}/confirm-delivery', [App\Http\Controllers\SupplierOrderController::class, 'confirmDelivery'])->name('orders.confirm-delivery');
    Route::patch('/orders/{eventService}/cancel', [App\Http\Controllers\SupplierOrderController::class, 'cancelOrder'])->name('orders.cancel');
    Route::patch('/orders/{eventService}/confirm-request', [App\Http\Controllers\SupplierOrderController::class, 'confirmRequest'])->name('orders.confirm-request');

    // Rutas de servicios
    Route::resource('services', App\Http\Controllers\SupplierServiceController::class);
    Route::patch('/services/{service}/toggle-active', [App\Http\Controllers\SupplierServiceController::class, 'toggleActive'])->name('services.toggle-active');

    // Rutas de reseñas
    Route::get('/reviews', [App\Http\Controllers\ReviewController::class, 'supplierIndex'])->name('reviews.index');

    // Rutas de reportes
    Route::get('/reports', [App\Http\Controllers\SupplierReportController::class, 'index'])->name('reports.index');

    // Rutas de soporte
    Route::get('/support/contact', [App\Http\Controllers\SupportController::class, 'contactAdmin'])->name('support.contact');
});

// Rutas de cliente
Route::middleware(['auth', 'verified'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\ClientController::class, 'dashboard'])->name('dashboard');
    Route::get('/events/create', [App\Http\Controllers\ClientController::class, 'createEvent'])->name('events.create');
    Route::post('/events', [App\Http\Controllers\ClientController::class, 'storeEvent'])->name('events.store');
    Route::get('/events/{event}/services', [App\Http\Controllers\ClientController::class, 'showServices'])->name('events.services');
    Route::post('/events/{event}/search-services', [App\Http\Controllers\ClientController::class, 'searchServices'])->name('events.search-services');
    Route::post('/request-service', [App\Http\Controllers\ClientController::class, 'requestService'])->name('request-service');
    Route::patch('/event-services/{eventService}/confirm-delivery', [App\Http\Controllers\ClientController::class, 'confirmDelivery'])->name('confirm-delivery');
    Route::patch('/event-services/{eventService}/cancel', [App\Http\Controllers\ClientController::class, 'cancelService'])->name('cancel-service');
    Route::post('/reviews', [App\Http\Controllers\ReviewController::class, 'storeClientReview'])->name('reviews.store');
    Route::get('/suppliers/{supplier}', [App\Http\Controllers\ClientController::class, 'getSupplier'])->name('suppliers.show');

    // Rutas de soporte
    Route::get('/support/contact', [App\Http\Controllers\SupportController::class, 'contactAdmin'])->name('support.contact');
});

// Rutas de administrador
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard de administrador
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');

    // Gestión de proveedores
    Route::prefix('suppliers')->name('suppliers.')->group(function () {
        Route::get('/', [App\Http\Controllers\AdminController::class, 'suppliers'])->name('index');
        Route::get('/{supplier}', [App\Http\Controllers\AdminController::class, 'showSupplier'])->name('show');
        Route::patch('/{supplier}/approve', [App\Http\Controllers\AdminController::class, 'approveSupplier'])->name('approve');
        Route::patch('/{supplier}/reject', [App\Http\Controllers\AdminController::class, 'rejectSupplier'])->name('reject');
        Route::patch('/{supplier}/deactivate', [App\Http\Controllers\AdminController::class, 'deactivateSupplier'])->name('deactivate');
        Route::patch('/{supplier}/reactivate', [App\Http\Controllers\AdminController::class, 'reactivateSupplier'])->name('reactivate');
    });
});
