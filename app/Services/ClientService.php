<?php

namespace App\Services;

use App\Models\Client;

class ClientService
{
    public function findClientByPhone(string $telefone)
    {
        $cliente = Client::with(['addresses' => function ($query) {
            $query->where('is_primary', true);
        }])->where('cellphone', '=', $telefone)->first();

        if (!$cliente) {
            $cliente = Client::with(['addresses' => function ($query) {
                $query->where('is_primary', true);
            }])->where('phone_1', '=', $telefone)->first();
        }

        return $cliente ?: [];
    }

    public function createClient($data)
    {
        $client = new Client();
        $client->name = $data['name'];
        $client->email = $data['client_email'];
        $client->cpf_cnpj = $data['cpf_cnpj'];
        $client->cellphone = $data['client_cellphone'];
        $client->obs = $data['client_obs'];

        if ($client->save()) {
            return $client;
        }

        return null;
    }
}
