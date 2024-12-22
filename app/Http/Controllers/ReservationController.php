<?php
namespace App\Http\Controllers;

use App\Services\ClientService;
use App\Services\AddressService;
use App\Services\ReservationService;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    private $clientService;
    private $addressService;
    private $reservationService;

    public function __construct(ClientService $clientService, AddressService $addressService, ReservationService $reservationService)
    {
        $this->clientService = $clientService;
        $this->addressService = $addressService;
        $this->reservationService = $reservationService;
    }

    public function setReserva(Request $request)
    {
        $data = $request->only([
            'name', 'cpf_cnpj', 'rua', 'bairro', 'numero', 'complemento', 
            'client_cellphone', 'client_email', 'client_obs', 'mesas_disponiveis'
        ]);

        $client = $this->clientService->createClient($data);
        if (!$client) {
            return redirect()->back()->with('error', 'Erro ao criar cliente');
        }

        if (!empty($data['rua']) && !empty($data['bairro'])) {
            $this->addressService->createAddress($data, $client->id);
        }

        $reservationData = [
            'client_id' => $client->id,
            'mesas_disponiveis' => $data['mesas_disponiveis'],
        ];
        $reservation = $this->reservationService->createReservation($reservationData);

        if ($reservation) {
            return redirect()->route('gestao')->with('success', 'Reserva criada com sucesso!');
        }

        return redirect()->back()->with('error', 'Erro ao criar reserva');
    }
}
