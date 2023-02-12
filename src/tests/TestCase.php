<?php

namespace Tests;

use App\Helpers\AccountTypes;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Testing\TestResponse;
use Tymon\JWTAuth\Facades\JWTAuth;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        // Set auth for APIs
        $testingUser = User::factory()->make([
            'email' => 'user-test@devcorp.com',
            'account_type' => AccountTypes::TYPE_ADMIN,
        ]);
        $testingUser->save();
        $token = JWTAuth::fromUser($testingUser);
        $this->withHeader('Authorization', "Bearer $token");
    }

    /**
     * Override get method (we need params)
     */
    public function get($uri, $headers = [], $params = []): TestResponse
    {
        return $this->call('GET', $uri, $params, [], [], $this->transformHeadersToServerVars($headers));
    }

}
