<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'location',
        'price_range',
        'description',
        'documents',
        'identification_photo',
        'status',
        'rating_avg',
        'rejection_reason',
        'deactivation_reason',
    ];

    protected $casts = [
        'rating_avg' => 'decimal:2',
        'documents' => 'array',
    ];

    /**
     * Get the user that owns the supplier.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category that owns the supplier.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the orders for the supplier.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the reviews for the supplier.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the available dates for the supplier.
     */
    public function dates()
    {
        return $this->hasMany(Date::class);
    }

    /**
     * Get the services for the supplier.
     */
    public function services()
    {
        return $this->hasMany(Service::class);
    }
}
