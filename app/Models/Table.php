<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    protected $fillable = [
        'table_number',
        'linked_table_id ',
        'status',
        'description_status'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function openOrder()
    {
        return $this->hasOne(Order::class)->where('status_payment', 1);
    }

}
