<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        if (!Auth::check()) {
            return redirect('/')->with('error', 'Você precisa estar logado para acessar essa página.');
        }

        $products = Product::all();

        $data = [
            'products' => $products
        ];
        return view('produtos', $data);
    }

    public function setProduct(Request $request)
    {
        $validated = $this->validateRequest($request);

        try {
            $this->productService->createProduct(
                $validated['product_name'],
                $validated['ingredientes'] ?? null,
                $validated['product_price']
            );

            return redirect()->route('produtos')->with('success', 'Produto criado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('produtos')->with('error', 'Erro ao criar o produto!');
        }
    }

    public function editProduct(Request $request)
    {
        $validated = $this->validateEditRequest($request);

        try {
            $this->productService->updateProduct(
                $validated['id_produto'],
                $validated['product_name'],
                $validated['ingredientes'] ?? null,
                $validated['product_price']
            );

            return redirect()->route('produtos')->with('success', 'Produto alterado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('produtos')->with('error', 'Erro ao alterar o produto!');
        }
    }

    public function deleteProduct(Request $request)
    {
        $validated = $this->validateDeleteRequest($request);

        try {
            $deleted = $this->productService->deleteProduct($validated['id']);

            if ($deleted) {
                return response()->json([
                    'message' => 'Produto deletado com sucesso!',
                    'success' => true,
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Falha ao deletar o produto.',
                    'success' => false,
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao deletar o produto!',
                'success' => false,
            ], 500);
        }
    }

    public function getAllProducts(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/')->with('error', 'Você precisa estar logado para acessar essa página.');
        }

        $products = Product::all();

        if ($products) {
            return response()->json([
                'message' => '',
                'success' => true,
                'data' => $products
            ], 200);
        }

        return response()->json([
            'message' => 'Nemhum produto encontrado!',
            'success' => false,
            'data' => ''
        ], 400);
    }

    private function validateRequest(Request $request): array
    {
        $request->merge([
            'product_price' => str_replace(['R$', '.', ','], ['', '', '.'], $request->product_price)
        ]);

        return $request->validate([
            'product_name' => 'required|string',
            'product_price' => 'required|numeric',
            'ingredientes' => 'nullable|string',
        ]);
    }

    private function validateEditRequest(Request $request): array
    {
        $request->merge([
            'product_price' => str_replace(['R$', '.', ','], ['', '', '.'], $request->product_price)
        ]);

        return $request->validate([
            'id_produto' => 'required|exists:products,id',
            'product_name' => 'required|string',
            'product_price' => 'required|numeric',
            'ingredientes' => 'nullable|string',
        ]);
    }

    private function validateDeleteRequest(Request $request): array
    {
        return $request->validate([
            'id' => 'required|exists:products,id',
        ]);
    }
}
