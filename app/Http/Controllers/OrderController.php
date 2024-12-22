<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
use App\Models\Table;
use App\Services\OrderService;
use App\Services\TableService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{
    private $orderService;
    private $tableService;

    public function __construct(OrderService $orderService, TableService $tableService)
    {
        $this->orderService = $orderService;
        $this->tableService = $tableService;
    }

    public function setOrder(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/')->with('error', 'Você precisa estar logado para acessar essa página.');
        }

        $request->validate([
            'table_id' => 'required|exists:tables,id',
            'productsData' => 'required|json'
        ]);


        $products = json_decode($request->input('productsData'), true);

        $table = Table::find($request->table_id);

        if (Gate::allows('view-tables')) {
            try {

                $order = $this->orderService->handleOrder($request->table_id, $products);

                $table = Table::find($request->table_id);
                if ($table->status === 0) {
                    $table->status = 1;
                    $table->description_status = "Aberta";
                    $table->save();
                }

                return response()->json(['success' => true, 'message' => 'Pedido realizado com sucesso!'], 200);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'error' => 'Erro ao processar o pedido.'], 500);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Você não tem autorização para editar essa mesa.',
            'success' => false
        ], 401);
    }

    public function getProductsByTable(Request $request, OrderService $orderService)
    {
        $request->validate([
            'table_id' => 'required|exists:tables,id'
        ]);

        $table = Table::find($request->table_id);
        $products = $orderService->getConsolidatedProductsByTable($table);
        $tables = $orderService->getAvailableTables($table, $request->table_id);

        if ($products->isNotEmpty() && $tables->isNotEmpty()) {
            return response()->json([
                'message' => 'Itens do pedido encontrados com sucesso!',
                'success' => true,
                'status' => 'success',
                'orderItems' => $products,
                'tables' => $tables
            ], 200);
        }

        return response()->json([
            'message' => 'Nenhum item encontrado',
            'success' => true,
            'status' => 'success',
            'orderItems' => [],
            'tables' => []
        ], 400);
    }


    public function changeTable(Request $request, TableService $tableService, OrderService $orderService)
    {
        $request->validate([
            'table_id' => 'required|exists:tables,id',
            'tableToTransferred' => 'required|exists:tables,id'
        ]);

        $products = json_decode($request->input('productsData'), true);
        $order = Order::where('table_id', $request->table_id)->where('status_payment', 1)->first();
        $tableParent = Table::find($request->table_id);

        if (!empty($products)) {
            DB::beginTransaction();
            $success = true;

            try {
                if ($order) {
                    foreach ($products as $product) {
                        $orderItem = $orderService->getOrderItem($order->id, $product['id']);
                        if ($orderItem) {
                            $tableToTransf = Table::find($request->tableToTransferred);
                            if ($tableToTransf && ($tableToTransf->status == 0 || $tableToTransf->status == 1)) {
                                $success = $orderService->transferProduct($orderItem, $product, $request->tableToTransferred);
                                if (!$success) break;
                            }
                        } else {
                            return response()->json([
                                'message' => 'Nenhum item de pedido encontrado',
                                'success' => false,
                            ], 404);
                        }
                    }

                    if ($success) {
                        $tableToTransf->status = 1;
                        $tableToTransf->description_status = "Aberta";
                        $tableService->updateTableStatus($tableToTransf);
                    }

                    if ($success) {
                        DB::commit();
                        return response()->json([
                            'message' => 'Itens transferidos com sucesso!',
                            'success' => true,
                        ], 200);
                    }
                } else {
                    return response()->json([
                        'message' => 'Nenhum Pedido encontrado',
                        'success' => false,
                    ], 404);
                }
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['error' => 'Erro interno ao tentar realizar o pedido.'], 500);
            }
        }

        return response()->json([
            'message' => 'Nenhum produto foi encontrado.',
            'success' => false
        ], 404);
    }
}
