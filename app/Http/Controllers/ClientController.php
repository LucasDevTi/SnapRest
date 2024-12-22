<?php

namespace App\Http\Controllers;

use App\Services\ClientService;
use App\Services\TableService; 
use Illuminate\Http\Request;

class ClientController extends Controller
{

    private ClientService $clientService;
    private TableService $tableService;

    public function __construct(ClientService $clientService, TableService $tableService)
    {
        $this->clientService = $clientService;
        $this->tableService = $tableService;
    }

    public function findByCel(Request $request)
    {
        $validated = $this->validateRequest($request);

        $cliente = $this->clientService->findClientByPhone($validated['telefone']);
        $tables = $this->tableService->findAvailableTables();

        return $this->buildResponse($cliente, $tables);
    }

    private function validateRequest(Request $request)
    {
        return $request->validate([
            'telefone' => 'required|min:10|max:15',
        ]);
    }

    private function buildResponse($cliente, $tables)
    {
        $message = empty($cliente) ? 'Cliente não encontrado' : '';

        return response()->json([
            'code' => 200,
            'success' => true,
            'message' => $message,
            'data' => $cliente,
            'tables' => $tables,
        ], 200);
    }
}
