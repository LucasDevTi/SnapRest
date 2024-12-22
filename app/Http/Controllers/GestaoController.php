<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Table;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GestaoController extends Controller
{

    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        $tables = Table::with('openOrder')->get();
        $tables->each(function ($table) {
            $table->totalPrice = $this->getTotalPriceByOrder($table);
        });
        return view('gestao', compact('tables'));
    }

    private function getTotalPriceByOrder(Table $table): float
    {
        return $this->orderService->calculateTotalPriceByTable($table);
    }
}
