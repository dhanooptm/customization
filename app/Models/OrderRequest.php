<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderRequest extends Model
{
    use HasFactory;
    protected $casts = [
        'product_id'  => 'integer',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
        'is_dealer' =>  'boolean',
        'similar_info' => 'boolean',
        'order_amount' => 'double',
        'quantity' =>'integer',
        'discount' => 'double',
        'price_range' => 'double',
        'tax' => 'double',
        'order_status' => 'string',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }
    public function customer()
    {
        return $this->belongsTo(User::class,'customer_id');
    }
}
