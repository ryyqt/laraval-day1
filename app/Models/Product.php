<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'price',
        'quantity',
        'description',
        'image',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function invoices()
    {
        return $this->belongsToMany(Invoice::class, 'invoice_items')
                    ->withPivot(['quantity', 'unit_price', 'subtotal'])
                    ->withTimestamps();
    }
}
