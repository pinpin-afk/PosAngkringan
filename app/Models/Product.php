<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'category_id',
        'image',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            // Check if image is base64 data URI
            if (str_starts_with($this->image, 'data:')) {
                return $this->image;
            }
            // Check if image path starts with 'storage/' or 'products/'
            elseif (str_starts_with($this->image, 'storage/')) {
                return asset($this->image);
            } else {
                return asset('storage/' . $this->image);
            }
        }
        return null;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
