<?php

namespace App\Services;

use App\Models\Comission;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
use App\Models\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function handleOrder($tableId, $products)
    {
        DB::beginTransaction();

        try {
            $order = Order::firstOrCreate(
                ['table_id' => $tableId],
                ['status_payment' => 1],
                ['description_status' => 'Aberto']
            );

            foreach ($products as $product) {
                $OrderItemId = $this->addItemToOrder($order, $product, $tableId);
                // $this->addItemToComission($OrderItemId, Auth::id());
            }

            $order->table_id = $tableId;
            $order->total_value = OrderItems::where('order_id', $order->id)->sum('sub_total');
            $order->save();

            DB::commit();
            return $order;
        } catch (\Exception $e) {

            DB::rollBack();
            throw $e;
        }
    }

    private function addItemToOrder(Order $order, $product, $tableId)
    {
        $orderItem = OrderItems::firstOrNew([
            'order_id' => $order->id,
            'product_id' => $product['id']
        ]);

        $productName = Product::find($product['id'])->name;

        $orderItem->product_name = $productName;
        $orderItem->quantity += $product['quantity'];
        $orderItem->sub_total = $orderItem->quantity * $product['price'];
        $orderItem->table_id = $tableId;

        $orderItem->save();

        return $orderItem->id;
    }

    private function addItemToComission($orderItemId, $userId)
    {

        $orderItem = OrderItems::find($orderItemId);

        if ($orderItem) {

            $comission = Comission::firstOrNew([
                'order_item' => $orderItem->id,
                'product_id' => $userId
            ]);

            $comission->quantity = $orderItem->quantity;
            $comission->save();
        }
    }

    public function getConsolidatedProductsByTable($table)
    {
        $orders = Order::where('table_id', $table->id)
            ->whereIn('status_payment', [0, 1])
            ->pluck('id');

        $orderItems = OrderItems::whereIn('order_id', $orders)->get();

        return $orderItems->groupBy('product_id')->map(function ($items) {
            return [
                'product_id' => $items->first()->product_id,
                'product_name' => $items->first()->product_name,
                'quantity' => $items->sum('quantity'),
                'sub_total' => $items->sum('sub_total'),
            ];
        })->values();
    }

    public function getAvailableTables($table, $tableId)
    {
        if ($table->linked_table_id) {
            $table_principal_id = $table->linked_table_id;
            return Table::whereIn('status', [0, 1])
                ->where('id', '!=', $tableId)
                ->where('id', '!=', $table_principal_id)
                ->where(function ($query) use ($table_principal_id) {
                    $query->where('linked_table_id', '!=', $table_principal_id)
                        ->orWhereNull('linked_table_id');
                })
                ->get();
        }

        return Table::whereIn('status', [0, 1])
            ->where('id', '!=', $tableId)
            ->where(function ($query) use ($tableId) {
                $query->where('linked_table_id', '!=', $tableId)
                    ->orWhereNull('linked_table_id');
            })
            ->get();
    }

    public function getOrderItem($orderId, $productId)
    {
        return OrderItems::where('order_id', $orderId)->where('product_id', $productId)->first();
    }

    public function transferProduct($orderItem, $product, $tableToTransferred)
    {
        $orderTransferred = Order::where('table_id', $tableToTransferred)->where('status_payment', 1)->first();
        if ($orderTransferred) {
            return $this->handleProductTransfer($orderItem, $product, $orderTransferred, $tableToTransferred);
        } else {
            return $this->createOrderForTransfer($product, $tableToTransferred, $orderItem);
        }
    }

    private function handleProductTransfer($orderItem, $product, $orderTransferred, $tableToTransferred)
    {
        $orderItemTransf = OrderItems::where('order_id', $orderTransferred->id)->where('product_id', $product['id'])->first();
        if ($orderItemTransf) {
            return $this->updateOrderItemTransfer($orderItem, $orderItemTransf, $product);
        } else {
            return $this->createNewOrderItem($orderTransferred->id, $product, $tableToTransferred, $orderItem);
        }
    }

    private function updateOrderItemTransfer($orderItem, $orderItemTransf, $product)
    {
        if ($orderItem->quantity <= $product['quantity']) {
            $orderItemTransf->quantity += $product['quantity'];
            $orderItemTransf->sub_total = $orderItemTransf->price * $orderItemTransf->quantity;
            if (!$orderItemTransf->save()) {
                return false;
            }

            $orderItem->quantity -= $product['quantity'];
            $orderItem->sub_total = $orderItem->price * $orderItem->quantity;
            if (!$orderItem->save()) {
                return false;
            }

            if ($orderItem->quantity == 0) {
                $orderItem->delete();
                $this->updateOrderTotal($orderItem->order_id);
            }
            return true;
        }
        return false;
    }

    private function createNewOrderItem($orderId, $product, $tableToTransferred, $orderItem)
    {
        $newOrderItem = new OrderItems();
        $productModel = Product::find($product['id']);
        $newOrderItem->order_id = $orderId;
        $newOrderItem->product_id = $product['id'];
        $newOrderItem->product_name = $productModel ? $productModel->name : null;
        $newOrderItem->quantity = $product['quantity'];
        $newOrderItem->price = $productModel ? $productModel->price : null;
        $newOrderItem->sub_total = $productModel->price * $product['quantity'];
        $newOrderItem->table_id = $tableToTransferred;

        if ($newOrderItem->save()) {
            $orderItem->quantity -= $product['quantity'];
            $orderItem->sub_total = $orderItem->price * $orderItem->quantity;
            $orderItem->save();
            $this->updateOrderTotal($orderItem->order_id);
            return true;
        }
        return false;
    }

    private function createOrderForTransfer($product, $tableToTransferred, $orderItem)
    {
        $newOrder = new Order();
        $newOrder->table_id = $tableToTransferred;
        $newOrder->status_payment = 1;
        $newOrder->description_status = "Aberto";
        if ($newOrder->save()) {
            $this->createNewOrderItem($newOrder->id, $product, $tableToTransferred, $orderItem);
            $orderItem->quantity -= $product['quantity'];
            $orderItem->sub_total = $orderItem->price * $orderItem->quantity;
            $orderItem->save();
            $this->updateOrderTotal($orderItem->order_id);
            return true;
        }
        return false;
    }

    private function updateOrderTotal($orderId)
    {
        $order = Order::find($orderId);
        if ($order) {
            $totalSubPrice = OrderItems::where('order_id', $orderId)->sum('sub_total');
            $order->total_value = $totalSubPrice;
            $order->save();
        }
    }

    /**
     * Calcula o preço total dos pedidos associados a uma mesa e suas mesas vinculadas.
     *
     * @param Table $table
     * @return float
     */
    public function calculateTotalPriceByTable(Table $table): float
    {
        $totalPrice = 0.00;

        $order = Order::where('table_id', $table->id)
            ->where('status_payment', '!=', 3)
            ->first();

        if ($order) {
            $totalPrice += $order->total_value;

            if (!$table->linked_table_id) {
                $linkedTables = Table::where('linked_table_id', $table->id)->get();

                foreach ($linkedTables as $linkedTable) {
                    $linkedOrder = Order::where('table_id', $linkedTable->id)
                        ->where('status_payment', '!=', 3)
                        ->first();
                    if ($linkedOrder) {
                        $totalPrice += $linkedOrder->total_value;
                    }
                }
            }
        }

        return $totalPrice;
    }
}
