<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            ['name' => 'Hambúrguer Clássico', 'price' => 25.90],
            ['name' => 'Pizza Margherita', 'price' => 49.90],
            ['name' => 'Salada Caesar', 'price' => 22.50],
            ['name' => 'Espaguete à Bolonhesa', 'price' => 32.80],
            ['name' => 'Frango Grelhado', 'price' => 27.40],
            ['name' => 'Costela com Barbecue', 'price' => 59.90],
            ['name' => 'Sopa de Cebola', 'price' => 18.20],
            ['name' => 'Risoto de Camarão', 'price' => 45.60],
            ['name' => 'Tábua de Frios', 'price' => 72.00],
            ['name' => 'Tacos Mexicanos', 'price' => 28.90],
            ['name' => 'Batata Frita', 'price' => 14.90],
            ['name' => 'Churrasco Misto', 'price' => 85.50],
            ['name' => 'Filé à Parmegiana', 'price' => 37.90],
            ['name' => 'Ceviche de Peixe', 'price' => 33.70],
            ['name' => 'Carpaccio de Carne', 'price' => 29.90],
            ['name' => 'Pão de Alho', 'price' => 12.50],
            ['name' => 'Cerveja Artesanal', 'price' => 15.00],
            ['name' => 'Refrigerante Lata', 'price' => 6.00],
            ['name' => 'Suco Natural', 'price' => 10.50],
            ['name' => 'Sobremesa Brownie', 'price' => 19.90],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
