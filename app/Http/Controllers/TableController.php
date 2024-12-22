<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Table;
use App\Services\LinkedTableService;
use App\Services\TableService;
use App\Services\TableStatusService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TableController extends Controller
{

    private $tableStatusService;
    private $linkedTableService;
    private $tableService;

    public function __construct(TableStatusService $tableStatusService, LinkedTableService $linkedTableService, TableService $tableService)
    {
        $this->tableStatusService = $tableStatusService;
        $this->linkedTableService = $linkedTableService;
        $this->tableService = $tableService;
    }

    public function atualizarStatusMesa(Request $request)
    {
        $request->validate([
            'mesa_id' => 'required|exists:tables,id',
            'novo_status' => 'required|integer',
        ]);

        $mesa = $this->tableStatusService->updateStatus($request->mesa_id, $request->novo_status);

        if ($mesa) {
            $this->linkedTableService->updateLinkedTables($request->mesa_id, $request->novo_status);

            return response()->json([
                'message' => 'Status atualizado com sucesso!',
                'success' => true,
                'status' => $mesa->status
            ], 200);
        }

        return response()->json([
            'message' => 'Houve um erro ao mudar o status da mesa',
            'success' => false,
            'status' => ''
        ], 404);
    }

    public function linkedTables(Request $request)
    {
        $request->validate([
            'arrayTablesSelects' => 'required|array',
            'arrayTablesSelects.*' => 'exists:tables,id',
            'PrincipalTable' => 'required|exists:tables,id',
        ]);

        $principalTableId = $request->PrincipalTable;
        $linkedTableIds = $request->arrayTablesSelects;

        $success = $this->linkedTableService->linkTables($principalTableId, $linkedTableIds);

        if ($success) {
            return response()->json([
                'message' => 'Mesas juntadas com sucesso!',
                'success' => true,
                'status' => $principalTableId,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Ocorreu um erro ao juntar as mesas.',
                'success' => false,
            ], 500);
        }
    }

    public function closeTables(Request $request)
    {
        $request->validate([
            'table_id' => 'exists:tables,id',
        ]);

        $tableId = $request->table_id;
        $table = $this->tableService->closeTableAndLinked($tableId);

        if ($table) {
            return response()->json([
                'message' => 'Mesa fechada com sucesso!',
                'success' => true,
                'status' => $table->status,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Ocorreu um erro ao fechar as mesas.',
                'success' => false,
            ], 500);
        }
    }

    public function releaseTable($table_id)
    {
        $order = Order::whereIn('status_payment', [1, 2])
            ->where('table_id', $table_id)
            ->first();

        if (!$order) {

            $table = Table::find($table_id);

            if ($table) {
                $table->status = 0;
                $table->description_status = "Liberada";
                if ($table->save()) {
                    return true;
                }
            }
        }
        return false;
    }

    public function updateToOpen($table_id)
    {
        $table = Table::find($table_id);

        if ($table) {
            $table->status = 1;
            $table->description_status = "Aberta";
            if ($table->save()) {
                return true;
            }
        }
    }

    public function getStatusMesa(Request $request): JsonResponse
    {

        $validated = $request->validate([
            'table_id' => ['required', 'integer', 'exists:tables,id']
        ]);

        if (!Auth::check()) {
            return response()->json([
                'status' => -1,
                'message' => 'Você precisa estar logado para acessar essa funcionalidade.'
            ], 403);
        }


        if (!Gate::allows('view-status-table', Auth::user())) {
            return response()->json([
                'status' => -1,
                'message' => 'Você não tem permissão para acessar essa funcionalidade.'
            ], 403);
        }

        $table = Table::find($validated['table_id']);

        try {

            $table = Table::findOrFail($validated['table_id']);

            $permissions = [
                'can_open_table' => Gate::allows('open-table-option'),
                'can_add_item' => Gate::allows('add-item-table-option'),
                'can_close_table' => Gate::allows('closed-table-option'),
                'can_transferred_table' => Gate::allows('transferred-table-option'),
                'can_pay_table' => Gate::allows('payment-table-option'),
                'can_disabled_table' => Gate::allows('disabled-tables-option')
            ];

            return response()->json([
                'status' => $table->status,
                'permissions' => $permissions,
                'message' => 'Status da mesa recuperado com sucesso'
            ], 200);
        } catch (ModelNotFoundException  $e) {

            return response()->json([
                'status' => -1,
                'message' => 'Mesa não encontrada'
            ], 404);
        }
    }
}
