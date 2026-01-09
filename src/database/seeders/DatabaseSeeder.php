<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Profile;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()
            ->has(Profile::factory())
            ->count(5)
            ->create();

        $this->call(CategoriesTableSeeder::class);
        $this->call(ItemsTableSeeder::class);
    }
}
