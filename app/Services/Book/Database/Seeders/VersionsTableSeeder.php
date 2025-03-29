<?php

namespace App\Services\Book\Database\Seeders;

use App\Data\Models\BookVersion;
use Illuminate\Database\Seeder;


//pa db:seed --class="App\Services\Book\Database\Seeders\VersionsTableSeeder"
class VersionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        BookVersion::factory()->count(10)->create();

    }
}