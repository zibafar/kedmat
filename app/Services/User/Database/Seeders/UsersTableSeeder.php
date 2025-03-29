<?php

namespace App\Services\User\Database\Seeders;

use App\Data\Models\User;
use Illuminate\Database\Seeder;


//pa
class UsersTableSeeder extends Seeder
{
    public const USER1 = '1';
    /**
     * Run the database seeds.
     */
    public function run()
    {
        //make Guest
        if(!User::query()->where('id',self::USER1)->exists()) {
            User::factory()->create([
                'id' => self::USER1,
                'name' => 'user1',
                'email' => 'user1@is.empty',
                'password' => '123456',
            ]);
        }


    }
}
