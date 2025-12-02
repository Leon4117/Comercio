<?php

namespace App\Http\Controllers;

use App\Models\EventService;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SupplierReportController extends Controller
{
    /**
     * Display the supplier reports.
     */
    public function index()
    {
        $user = Auth::user();
        $supplier = Supplier::where('user_id', $user->id)->first();

        if (!$supplier) {
            abort(403, 'Acceso denegado. No eres un proveedor.');
        }

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // 1. Pie Chart: Status of last orders of the month
        $statusStats = EventService::where('supplier_id', $supplier->id)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->select('status', DB::raw('count(*) as total'))
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

        // 2. Bar Chart: Earnings of last orders of the month
        // Grouping by day
        $earningsStats = EventService::where('supplier_id', $supplier->id)
            ->whereIn('status', ['confirmed', 'delivered', 'completed']) // Include confirmed as booked earnings
            ->whereBetween('updated_at', [$startOfMonth, $endOfMonth]) // Use updated_at for completion/confirmation date
            ->select(DB::raw('DATE(updated_at) as date'), DB::raw('SUM(final_price) as total'))
            ->groupBy(DB::raw('DATE(updated_at)'))
            ->orderBy(DB::raw('DATE(updated_at)'))
            ->get();

        $barChartData = [
            'labels' => [],
            'data' => []
        ];

        // Fill in missing days if needed, or just show days with earnings
        // For simplicity, let's just show days with activity
        foreach ($earningsStats as $stat) {
            $barChartData['labels'][] = Carbon::parse($stat->date)->format('d M');
            $barChartData['data'][] = $stat->total;
        }

        return view('supplier.reports.index', compact('pieChartData', 'barChartData'));
    }
}
