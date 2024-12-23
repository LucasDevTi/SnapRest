<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comission extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'id',
        'order_item',
        'user_id',
        'quantity'
    ];
}
