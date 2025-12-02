<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventService extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'service_id',
        'supplier_id',
        'quoted_price',
        'final_price',
        'status',
        'urgent',
        'notes',
        'chat_link',
    ];

    protected $casts = [
        'quoted_price' => 'decimal:2',
        'final_price' => 'decimal:2',
        'urgent' => 'boolean',
    ];

    /**
     * Get the event that owns the service.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the service.
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the supplier.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the status in Spanish.
     */
    public function getStatusInSpanish()
    {
        $statuses = [
            'requested' => 'Solicitado',
            'quoted' => 'Cotizando',
            'confirmed' => 'Confirmado',
            'delivered' => 'Entregado',
            'cancelled' => 'Cancelado',
            'completed' => 'Completado',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    /**
     * Get the status color for badges.
     */
    public function getStatusColor()
    {
        $colors = [
            'requested' => 'blue',
            'quoted' => 'yellow',
            'confirmed' => 'green',
            'delivered' => 'purple',
            'cancelled' => 'red',
            'completed' => 'green',
        ];

        return $colors[$this->status] ?? 'gray';
    }

    /**
     * Scope a query to only include services by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include urgent services.
     */
    public function scopeUrgent($query)
    {
        return $query->where('urgent', true);
    }
}
