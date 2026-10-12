<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Vehicle category (e.g. Sedan, SUV, Luxury, Van).
 */
class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'category_id');
    }
}
