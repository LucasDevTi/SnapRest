<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductService
{
    /**
     * Cria um novo produto
     *
     * @param string $name
     * @param string|null $ingredients
     * @param float $price
     * @return bool
     * @throws \Exception
     */
    public function createProduct(string $name, ?string $ingredients, float $price): bool
    {
        DB::beginTransaction();

        try {
            $product = new Product();
            $product->name = $name;
            $product->ingredients = $ingredients;
            $product->price = $price;

            if ($product->save()) {
                DB::commit();
                return true;
            }

            DB::rollBack();
            return false;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Retorna todos os produtos cadastrados
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllProducts()
    {
        return Product::all();
    }


    public function updateProduct(int $id, string $name, ?string $ingredients, float $price): bool
    {
        DB::beginTransaction();

        try {
            $product = Product::findOrFail($id);

            $product->name = $name;
            $product->ingredients = $ingredients;
            $product->price = $price;

            if ($product->save()) {
                DB::commit();
                return true;
            }

            DB::rollBack();
            return false;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteProduct(int $id): bool
    {
        try {
            $product = Product::findOrFail($id);
            return $product->delete();
        } catch (\Exception $e) {
            throw $e;
        }
    }

}
