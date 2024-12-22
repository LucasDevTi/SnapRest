<?php
namespace App\Services;

use App\Models\Reservation;
use App\Models\Table;

class ReservationService
{
    public function createReservation($data)
    {
        $reservation = new Reservation();
        $reservation->client_id = $data['client_id'];
        $reservation->table_id = $data['mesas_disponiveis'];
        $reservation->status = 1;

        if ($reservation->save()) {
            $this->updateTableStatus($data['mesas_disponiveis']);
            return $reservation;
        }

        return null;
    }

    private function updateTableStatus($tableId)
    {
        $table = Table::find($tableId);
        if ($table) {
            $table->status = 3;
            $table->description_status = 'Reservada';
            $table->save();
        }
    }
}
