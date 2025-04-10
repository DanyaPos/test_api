<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'dummy_id',
        'title',
        'description',
        'category',
        'price',
        'discount_percentage',
        'rating',
        'stock',
        'tags',
        'brand',
        'sku',
        'weight',
        'dimensions',
        'warranty_information',
        'shipping_information',
        'availability_status',
        'reviews',
        'return_policy',
        'minimum_order_quantity',
        'meta',
        'images',
        'thumbnail',
    ];

    protected $casts = [
        'tags' => 'array',
        'dimensions' => 'array',
        'reviews' => 'array',
        'meta' => 'array',
        'images' => 'array',
    ];


}
