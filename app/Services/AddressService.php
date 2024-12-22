<?php 
namespace App\Services;

use App\Models\Address;

class AddressService
{
    public function createAddress($data, $clientId)
    {
        $address = new Address();
        $address->client_id = $clientId;
        $address->street = $data['rua'];
        $address->neighborhood = $data['bairro'];
        $address->number = $data['numero'];
        $address->complement = $data['complemento'];
        $address->is_primary = false;

        if ($address->save()) {
            return $address;
        }

        return null;
    }
}
