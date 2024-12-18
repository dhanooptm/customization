<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductInquiry extends Model
{
    use HasFactory;

    protected $casts = [
        'product_id'  => 'integer',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
        'is_dealer' =>  'boolean',
        'similar_info' => 'boolean',
        'price' => 'double',
        'quantity' =>'integer',
        'status' => 'boolean',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
