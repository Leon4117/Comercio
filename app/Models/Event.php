<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'event_date',
        'location',
        'budget',
        'guests_count',
        'status',
    ];

    protected $casts = [
        'event_date' => 'date',
        'budget' => 'decimal:2',
    ];

    /**
     * Get the user that owns the event.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the services for this event.
     */
    public function services()
    {
        return $this->belongsToMany(Service::class, 'event_services')
                    ->withPivot(['quoted_price', 'final_price', 'status', 'urgent', 'notes', 'chat_link'])
                    ->withTimestamps();
    }

    /**
     * Get the event services (pivot records).
     */
    public function eventServices()
    {
        return $this->hasMany(EventService::class);
    }

    /**
     * Scope a query to only include events for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include upcoming events.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', now()->toDateString());
    }

    /**
     * Scope a query to only include events by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Get the event status in Spanish.
     */
    public function getStatusInSpanish()
    {
        $statuses = [
            'planning' => 'Planificando',
            'confirmed' => 'Confirmado',
            'completed' => 'Completado',
            'cancelled' => 'Cancelado',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    /**
     * Get the days until the event.
     */
    public function getDaysUntilEvent()
    {
        return (int) now()->endOfDay()->diffInDays($this->event_date->startOfDay(), false);
    }

    /**
     * Check if the event is upcoming.
     */
    public function isUpcoming()
    {
        return $this->event_date >= now()->toDateString();
    }

    /**
     * Get the total budget for confirmed services.
     */
    public function getTotalConfirmedBudget()
    {
        return $this->eventServices()
                    ->where('status', 'confirmed')
                    ->sum('final_price');
    }

    /**
     * Get the count of services by status.
     */
    public function getServicesCountByStatus($status)
    {
        return $this->eventServices()
                    ->where('status', $status)
                    ->count();
    }
}
