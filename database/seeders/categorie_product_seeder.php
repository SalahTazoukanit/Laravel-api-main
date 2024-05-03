<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categorie;
use App\Models\Product;

class categorie_product_seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Categorie::factory(5)->create();

            Product::factory()->create()->each(function($products) use ($categories) {
                $products->categories()->attach($categories->random());
        });
    }
}
