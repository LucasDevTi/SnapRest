<?php

namespace App\Services;

use App\Models\Table;
use Illuminate\Support\Facades\DB;

class TableService
{
    public function updateTableStatus($table)
    {
        return $table->save();
    }

    public function findAvailableTables()
    {
        $tables = Table::where('status', 0)->get();

        return $tables->isEmpty() ? [] : $tables;
    }

    public function closeTableAndLinked($tableId)
    {
        DB::beginTransaction();
        $success = true;
        try {
            $table = Table::find($tableId);

            if ($table->linked_table_id) {
                $table = Table::find($table->linked_table_id);
                $table->status = 2;
                $table->description_status = "Fechada";

                $linked_tables = Table::where('linked_table_id', $table->id)->get();

                foreach ($linked_tables as $linked) {
                    $table_parent = Table::find($linked->id);
                    $table_parent->status = 2;
                    $table_parent->description_status = "Fechada";

                    if (!$table_parent->save()) {
                        $success = false;
                        break;
                    }
                }
            } else {
                $linked_tables = Table::where('linked_table_id', $tableId)->get();

                if ($linked_tables->isNotEmpty()) {
                    foreach ($linked_tables as $linked) {
                        $table_parent = Table::find($linked->id);
                        $table_parent->status = 2;
                        $table_parent->description_status = "Fechada";

                        if (!$table_parent->save()) {
                            $success = false;
                            break;
                        }
                    }
                }

                $table->status = 2;
                $table->description_status = "Fechada";
            }

            if (!$table->save()) {
                $success = false;
            }

            if ($success) {
                DB::commit();
                return $table;
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
