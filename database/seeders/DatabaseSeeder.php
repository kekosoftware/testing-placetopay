<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Database\Seeders\ProductSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $this->call([
            ProductSeeder::class,
        ]);

        User::factory()->create([
		    'name' => 'Gustavo Ariel',
            'email' => 'ariel@gmail.com',
            'mobile' => '123456789',
            'email_verified_at' => now(),
            'password' => bcrypt('Gus@123'), // password
            'remember_token' => Str::random(10),
		]);
    }
}
