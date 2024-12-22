<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'client_id',
        'table_id',
        'status',
        'data_hora_chegada'
    ];
}
