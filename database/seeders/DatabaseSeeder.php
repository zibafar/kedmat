<?php

namespace Database\Seeders;

use App\Data\Models\User;
use App\Services\User\Database\Seeders\UsersTableSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([

            UsersTableSeeder::class,

            

        ]);
    }
}
