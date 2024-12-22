<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'cpf_cnpj',
        'phone_1',
        'cellphone',
        'birth_date',
        'obs'
    ];

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function addressPrimary()
    {
        return $this->hasOne(Address::class)->where('is_primary', true)->first();
    }
}
