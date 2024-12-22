<?php

namespace App\Services;

use App\Models\Table;

class TableStatusService
{
    public function updateStatus($tableId, $newStatus)
    {
        $mesa = Table::find($tableId);

        if (!$mesa) {
            return null;
        }

        switch ($newStatus) {
            case 0:
                $mesa->description_status = "Liberada";
                break;
            case 1:
                $mesa->description_status = "Aberta";
                break;
            case 2:
                $mesa->description_status = "Fechada";
                break;
            case 3:
                $mesa->description_status = "Reservada";
                break;
            case 4:
                $mesa->description_status = "Inativa";
                break;
            default:
                return null;
        }

        $mesa->status = $newStatus;

        if ($mesa->save()) {
            return $mesa;
        }

        return null;
    }
}
