<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    protected $fillable = [
        'id',
        'order_id',
        'product_id',
        'quantity',
        'price',
        'sub_total',
        'table_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id'); // 'product_id' é a chave estrangeira
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
