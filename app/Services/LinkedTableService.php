<?php

namespace App\Services;

use App\Models\Table;
use Illuminate\Support\Facades\DB;

class LinkedTableService
{
    public function updateLinkedTables($tableId, $newStatus)
    {
        $mesa = Table::find($tableId);

        if ($newStatus == 2) {
            $linkedId = $mesa->linked_table_id ? $mesa->linked_table_id : $tableId;
            $linkedTables = Table::where('linked_table_id', $linkedId)->get();

            foreach ($linkedTables as $linkedTable) {
                $linkedTable->status = 2;
                $linkedTable->description_status = 'Fechada';
                $linkedTable->save();

                if ($mesa->linked_table_id) {
                    $mesaPai = Table::find($linkedId);
                    if ($mesaPai) {
                        $mesaPai->status = 2;
                        $mesaPai->description_status = 'Fechada';
                        $mesaPai->save();
                    }
                }
            }
        }
    }

    public function linkTables($principalTableId, $linkedTableIds)
    {
        DB::beginTransaction();

        $success = true;
        try {
            foreach ($linkedTableIds as $tableId) {
                $table = Table::find($tableId);

                if ($tableId != $principalTableId) {
                    $table->linked_table_id = $principalTableId;
                }

                $table->status = 1;
                $table->description_status = 'Aberta';

                if (!$table->save()) {
                    $success = false;
                    break;
                }
            }

            if ($success) {
                DB::commit();
                return true;
            } else {
                DB::rollBack();
                return false;
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }
}
