<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Supplier;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SupplierServiceController extends Controller
{
    /**
     * Display a listing of the supplier's services.
     */
    public function index()
    {
        $user = Auth::user();
        $supplier = Supplier::where('user_id', $user->id)->first();

        if (!$supplier) {
            return redirect()->route('supplier.registration-form');
        }

        $services = Service::where('supplier_id', $supplier->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('supplier.services.index', compact('services', 'supplier'));
    }

    /**
     * Show the form for creating a new service.
     */
    public function create()
    {
        $user = Auth::user();
        $supplier = Supplier::where('user_id', $user->id)->first();

        if (!$supplier) {
            return redirect()->route('supplier.registration-form');
        }

        $categories = Category::all();
        
        return view('supplier.services.create', compact('supplier', 'categories'));
    }

    /**
     * Store a newly created service in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'base_price' => 'required|numeric|min:0',
            'description' => 'required|string|max:2000',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB
            'portfolio_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'urgent_available' => 'boolean',
            'urgent_price_extra' => 'nullable|numeric|min:0',
        ]);

        $user = Auth::user();
        $supplier = Supplier::where('user_id', $user->id)->first();

        if (!$supplier) {
            return redirect()->route('supplier.registration-form');
        }

        // Handle main image upload
        $mainImagePath = null;
        if ($request->hasFile('main_image')) {
            $mainImagePath = $request->file('main_image')->store('service-images', 'public');
        }

        // Handle portfolio images upload
        $portfolioImages = [];
        if ($request->hasFile('portfolio_images')) {
            foreach ($request->file('portfolio_images') as $image) {
                $portfolioImages[] = $image->store('service-portfolio', 'public');
            }
        }

        Service::create([
            'supplier_id' => $supplier->id,
            'name' => $request->name,
            'base_price' => $request->base_price,
            'description' => $request->description,
            'main_image' => $mainImagePath,
            'portfolio_images' => $portfolioImages,
            'urgent_available' => $request->has('urgent_available'),
            'urgent_price_extra' => $request->urgent_available ? $request->urgent_price_extra : null,
            'active' => true,
        ]);

        return redirect()->route('supplier.services.index')
            ->with('success', 'Servicio publicado exitosamente.');
    }

    /**
     * Display the specified service.
     */
    public function show(Service $service)
    {
        $user = Auth::user();
        $supplier = Supplier::where('user_id', $user->id)->first();

        // Verificar que el servicio pertenece al proveedor
        if ($service->supplier_id !== $supplier->id) {
            abort(403, 'No tienes permisos para ver este servicio.');
        }

        return view('supplier.services.show', compact('service', 'supplier'));
    }

    /**
     * Show the form for editing the specified service.
     */
    public function edit(Service $service)
    {
        $user = Auth::user();
        $supplier = Supplier::where('user_id', $user->id)->first();

        // Verificar que el servicio pertenece al proveedor
        if ($service->supplier_id !== $supplier->id) {
            abort(403, 'No tienes permisos para editar este servicio.');
        }

        $categories = Category::all();

        return view('supplier.services.edit', compact('service', 'supplier', 'categories'));
    }

    /**
     * Update the specified service in storage.
     */
    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'base_price' => 'required|numeric|min:0',
            'description' => 'required|string|max:2000',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'portfolio_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'urgent_available' => 'boolean',
            'urgent_price_extra' => 'nullable|numeric|min:0',
        ]);

        $user = Auth::user();
        $supplier = Supplier::where('user_id', $user->id)->first();

        // Verificar que el servicio pertenece al proveedor
        if ($service->supplier_id !== $supplier->id) {
            abort(403, 'No tienes permisos para editar este servicio.');
        }

        $updateData = [
            'name' => $request->name,
            'base_price' => $request->base_price,
            'description' => $request->description,
            'urgent_available' => $request->has('urgent_available'),
            'urgent_price_extra' => $request->urgent_available ? $request->urgent_price_extra : null,
        ];

        // Handle main image upload
        if ($request->hasFile('main_image')) {
            // Delete old image
            if ($service->main_image) {
                Storage::disk('public')->delete($service->main_image);
            }
            $updateData['main_image'] = $request->file('main_image')->store('service-images', 'public');
        }

        // Handle portfolio images upload
        if ($request->hasFile('portfolio_images')) {
            // Delete old images
            if ($service->portfolio_images) {
                foreach ($service->portfolio_images as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }
            
            $portfolioImages = [];
            foreach ($request->file('portfolio_images') as $image) {
                $portfolioImages[] = $image->store('service-portfolio', 'public');
            }
            $updateData['portfolio_images'] = $portfolioImages;
        }

        $service->update($updateData);

        return redirect()->route('supplier.services.index')
            ->with('success', 'Servicio actualizado exitosamente.');
    }

    /**
     * Toggle the active status of the service.
     */
    public function toggleActive(Service $service)
    {
        $user = Auth::user();
        $supplier = Supplier::where('user_id', $user->id)->first();

        // Verificar que el servicio pertenece al proveedor
        if ($service->supplier_id !== $supplier->id) {
            abort(403, 'No tienes permisos para modificar este servicio.');
        }

        $service->update(['active' => !$service->active]);

        $status = $service->active ? 'activado' : 'desactivado';
        
        return redirect()->back()
            ->with('success', "Servicio {$status} exitosamente.");
    }

    /**
     * Remove the specified service from storage.
     */
    public function destroy(Service $service)
    {
        $user = Auth::user();
        $supplier = Supplier::where('user_id', $user->id)->first();

        // Verificar que el servicio pertenece al proveedor
        if ($service->supplier_id !== $supplier->id) {
            abort(403, 'No tienes permisos para eliminar este servicio.');
        }

        // Delete associated images
        if ($service->main_image) {
            Storage::disk('public')->delete($service->main_image);
        }
        
        if ($service->portfolio_images) {
            foreach ($service->portfolio_images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $service->delete();

        return redirect()->route('supplier.services.index')
            ->with('success', 'Servicio eliminado exitosamente.');
    }
}
