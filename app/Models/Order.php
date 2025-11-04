<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'user_id',
        'event_date',
        'quote_price',
        'status',
        'quote_price_final',
        'urgent',
        'urgent_price',
        'chat_link',
    ];

    protected $casts = [
        'event_date' => 'date',
        'quote_price' => 'decimal:2',
        'quote_price_final' => 'decimal:2',
        'urgent_price' => 'decimal:2',
        'urgent' => 'boolean',
    ];

    /**
     * Get the supplier that owns the order.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the user that owns the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include urgent orders.
     */
    public function scopeUrgent($query)
    {
        return $query->where('urgent', true);
    }

    /**
     * Scope a query to only include orders by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
