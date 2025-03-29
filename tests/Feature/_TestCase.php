<?php

namespace Tests\Feature;

use App\Data\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;


class _TestCase extends TestCase
{
    use RefreshDatabase;

    protected array $connectionsToTransact = ['mysql'];

    protected User $user_test;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        App::setLocale("en");
        //        $this->artisan('migrate', ['--step' => 9]);
    }

    protected function tearDown(): void
    {
        $this->beforeApplicationDestroyed(function () {
            foreach (DB::getConnections() as $connection) {
                $connection->disconnect();
            }
        });

        parent::tearDown();
    }


    protected function createUserTest(): void
    {
        if (User::where('email','user_test@is.empty')->doesntExist()) {
           User::factory()->createOne(
                [
                    'email' => 'user_test@is.empty',

                ]
            );
        }
        $this->user_test= User::where('email','user_test@is.empty')->first();
    }
}
