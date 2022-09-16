<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::factory()->create([
		    'name' => 'Computer I9',
		    'price' => '1680'
		]);

		Product::factory()->create([
		    'name' => 'Keyboard gamer',
		    'price' => '520'
		]);

		Product::factory()->create([
		    'name' => 'Monitor 27"',
		    'price' => '710'
		]);

        Product::factory(5)->create();
    }
}
