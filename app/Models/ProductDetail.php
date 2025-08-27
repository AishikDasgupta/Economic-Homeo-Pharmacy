<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'benefits',
        'usage',
        'ingredients',
        'dosage',
        'side_effects',
        'precautions',
        'storage_info',
        'additional_info',
    ];

    /**
     * Get the product that owns the details.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}