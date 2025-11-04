<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Mostrar el dashboard de administrador
     */
    public function dashboard()
    {
        // Estadísticas generales
        $stats = [
            'total_suppliers' => Supplier::count(),
            'pending_suppliers' => Supplier::where('status', 'pending')->count(),
            'approved_suppliers' => Supplier::where('status', 'approved')->count(),
            'rejected_suppliers' => Supplier::where('status', 'rejected')->count(),
            'total_clients' => User::where('role', 'client')->count(),
            'total_revenue' => 0, // Implementar cuando tengamos sistema de pagos
        ];

        return view('admin.dashboard', compact('stats'));
    }

    /**
     * Mostrar lista de proveedores registrados
     */
    public function suppliers(Request $request)
    {
        $query = Supplier::with(['user', 'category']);

        // Filtro por estado
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filtro por búsqueda
        if ($request->has('search') && $request->search) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $suppliers = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.suppliers.index', compact('suppliers'));
    }

    /**
     * Mostrar detalles de un proveedor específico
     */
    public function showSupplier(Supplier $supplier)
    {
        $supplier->load(['user', 'category']);
        return view('admin.suppliers.show', compact('supplier'));
    }

    /**
     * Aprobar un proveedor
     */
    public function approveSupplier(Supplier $supplier)
    {
        try {
            DB::beginTransaction();

            $supplier->update(['status' => 'approved']);
            
            // También actualizar el usuario
            $supplier->user->update(['approved' => true]);

            DB::commit();

            return redirect()->back()->with('success', 'Proveedor aprobado exitosamente.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Error al aprobar el proveedor: ' . $e->getMessage());
        }
    }

    /**
     * Rechazar un proveedor
     */
    public function rejectSupplier(Request $request, Supplier $supplier)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $supplier->update([
                'status' => 'rejected',
                'rejection_reason' => $request->reason
            ]);

            // También actualizar el usuario
            $supplier->user->update(['approved' => false]);

            DB::commit();

            return redirect()->back()->with('success', 'Proveedor rechazado exitosamente.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Error al rechazar el proveedor: ' . $e->getMessage());
        }
    }

    /**
     * Dar de baja un proveedor
     */
    public function deactivateSupplier(Request $request, Supplier $supplier)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $supplier->update([
                'status' => 'inactive',
                'deactivation_reason' => $request->reason
            ]);

            // También actualizar el usuario
            $supplier->user->update(['approved' => false]);

            DB::commit();

            return redirect()->back()->with('success', 'Proveedor dado de baja exitosamente.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Error al dar de baja el proveedor: ' . $e->getMessage());
        }
    }

    /**
     * Reactivar un proveedor
     */
    public function reactivateSupplier(Supplier $supplier)
    {
        try {
            DB::beginTransaction();

            $supplier->update([
                'status' => 'approved',
                'deactivation_reason' => null
            ]);

            // También actualizar el usuario
            $supplier->user->update(['approved' => true]);

            DB::commit();

            return redirect()->back()->with('success', 'Proveedor reactivado exitosamente.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Error al reactivar el proveedor: ' . $e->getMessage());
        }
    }
}
