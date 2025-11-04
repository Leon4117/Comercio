<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Datos de ejemplo para el dashboard
        $stats = [
            'pedidos_nuevos' => 4,
            'pendientes_cotizar' => 2,
            'ingresos_confirmados' => 12500,
            'calificacion' => 4.8
        ];

        $pedidos = [
            [
                'id' => 1,
                'cliente' => 'Ana Pérez',
                'servicio' => 'Pastel Básico (30p)',
                'fecha_evento' => '2025-11-18',
                'estado' => 'NUEVO',
                'estado_color' => 'blue'
            ],
            [
                'id' => 2,
                'cliente' => 'Jorge Ramos',
                'servicio' => 'Mesa Dulce Premium',
                'fecha_evento' => '2026-01-01',
                'estado' => 'COTIZANDO',
                'estado_color' => 'yellow'
            ],
            [
                'id' => 3,
                'cliente' => 'Sofía M. (URGENCIA)',
                'servicio' => 'Pastel Básico (20p)',
                'fecha_evento' => '2025-10-09',
                'estado' => '¡URGENTE!',
                'estado_color' => 'red'
            ]
        ];

        return view('dashboard', compact('stats', 'pedidos'));
    }
}
