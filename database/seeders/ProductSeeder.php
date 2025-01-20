<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insert test products into the products table
        Product::create([
            'name' => 'Product A',
            'price' => 25.50,
            'barcode_id' => 'A123456789',
            'stock' => 100,
        ]);

        Product::create([
            'name' => 'Product B',
            'price' => 15.75,
            'barcode_id' => 'B987654321',
            'stock' => 50,
        ]);

        Product::create([
            'name' => 'Product C',
            'price' => 30.00,
            'barcode_id' => 'C192837465',
            'stock' => 200,
        ]);

        Product::create([
            'name' => 'Product D',
            'price' => 10.00,
            'barcode_id' => 'D102938475',
            'stock' => 150,
        ]);

        Product::create([
            'name' => 'Product E',
            'price' => 50.00,
            'barcode_id' => 'E564738291',
            'stock' => 75,
        ]);
    }
}
