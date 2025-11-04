<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'description',
        'image',
        'urgent_available',
    ];

    protected $casts = [
        'urgent_available' => 'boolean',
        'price' => 'decimal:2',
    ];

    /**
     * Get the suppliers for this category.
     */
    public function suppliers()
    {
        return $this->hasMany(Supplier::class);
    }
}
