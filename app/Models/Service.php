<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'name',
        'base_price',
        'description',
        'main_image',
        'portfolio_images',
        'urgent_available',
        'urgent_price_extra',
        'active',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'urgent_price_extra' => 'decimal:2',
        'urgent_available' => 'boolean',
        'active' => 'boolean',
        'portfolio_images' => 'array',
    ];

    /**
     * Get the supplier that owns the service.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Scope a query to only include active services.
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope a query to only include services that accept urgent orders.
     */
    public function scopeUrgentAvailable($query)
    {
        return $query->where('urgent_available', true);
    }

    /**
     * Get the total price including urgent fee if applicable.
     */
    public function getTotalPrice($isUrgent = false)
    {
        $price = $this->base_price;
        
        if ($isUrgent && $this->urgent_available && $this->urgent_price_extra) {
            $price += $this->urgent_price_extra;
        }
        
        return $price;
    }

    /**
     * Get the main image URL or a default placeholder.
     */
    public function getMainImageUrl()
    {
        if ($this->main_image) {
            return asset('storage/' . $this->main_image);
        }
        
        return asset('images/service-placeholder.jpg');
    }

    /**
     * Get portfolio images URLs.
     */
    public function getPortfolioImagesUrls()
    {
        if (!$this->portfolio_images) {
            return [];
        }
        
        return array_map(function ($image) {
            return asset('storage/' . $image);
        }, $this->portfolio_images);
    }
}
