<?php

namespace Tests\Unit\Controllers;

use App\Helpers\AccountTypes;
use App\Models\User;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    /**
     * @const string
     */
    const API = '/api/users/';

    /**
     * A basic unit test example.
     *
     * @dataProvider providerIndex
     * @return void
     */
    public function testIndex($params, $dataClosure, $compareClosure)
    {
        $response = $this->get(self::API, [], $params);
        $response->assertStatus(200);

        // get result
        $content = $response->getContent();
        $this->assertJson($content);
        $result = json_decode($content, true);

        // compare $result with $data()
        $compareClosure($result, $dataClosure());
    }

    /**
     * Data provider for index test
     *
     * @return array
     */
    public function providerIndex(): array
    {
        return [
            // example 0
            [
                // params
                [
                    // sort by default by id
                    // there is no sort_by
                    // paginate by default is false
                ],
                // data
                function () {
                    return User::query()->get()->toArray();
                },
                // compare
                function ($result, $data) {
                    $this->assertArrayHasKey('data', $result);
                    $this->assertEquals($result['data'], $data);
                }
            ],
            // example 1
            [
                // params
                [
                    'sort_by' => 'name',
                    // order by default asc
                    // paginate by default is false
                ],
                // data
                function () {
                    return User::query()->orderBy('name')->get()->toArray();
                },
                // compare
                function ($result, $data) {
                    $this->assertArrayHasKey('data', $result);
                    $this->assertEquals($result['data'], $data);
                }
            ],
            // example 2
            [
                // params
                [
                    'sort_by' => 'created_at',
                    'order' => 'desc',
                    'paginate' => true,
                    //'page' => 2
                ],
                // data
                function () {
                    return User::query()->orderBy('created_at', 'desc')->paginate()->toArray();
                },
                // compare
                function ($result, $data) {
                    // compare data
                    $this->assertArrayHasKey('data', $result);
                    $this->assertEquals($result['data'], $data['data']);
                    // compare links
                    $this->assertArrayHasKey('meta', $result);
                    $this->assertArrayHasKey('links', $result['meta']);
                    $this->assertEquals($result['meta']['links'], $data['links']);
                }
            ]
        ];
    }

    /**
     * Test show
     * @return void
     */
    public function testShow()
    {
        /** @var User $user */
        $user = User::query()->first();
        $response = $this->get(self::API . $user->id);
        $response->assertStatus(200);

        // get result
        $content = $response->getContent();
        $this->assertJson($content);
        $result = json_decode($content, true);

        // compare
        $this->assertArrayHasKey('data', $result);
        $result = $result['data'];
        $this->assertEquals($result, $user->toArray());
    }

    /**
     * Test store
     * @return void
     */
    public function testStore()
    {
        $params = [
            'name' => 'name example',
            'email' => 'example@devcorp.com',
            'account_type' => AccountTypes::TYPE_TEAM,
            'password' => 'password',
        ];
        $response = $this->post(self::API, $params);
        $response->assertStatus(201);

        // get result
        $content = $response->getContent();
        $this->assertJson($content);
        $result = json_decode($content, true);

        // compare
        $this->assertArrayHasKey('data', $result);
        $result = $result['data'];
        unset($params['password']);
        foreach ($params as $k => $v) {
            $this->assertEquals($result[$k], $v);
        }
    }

    /**
     * Test update
     * @dataProvider providerUpdate
     * @return void
     */
    public function testUpdate($params)
    {

        /** @var User $user */
        $user = User::query()->first();
        $response = $this->put(self::API . $user->id, $params);
        $response->assertStatus(200);

        // get result
        $content = $response->getContent();
        $this->assertJson($content);
        $result = json_decode($content, true);

        // compare
        $this->assertArrayHasKey('data', $result);
        $result = $result['data'];
        unset($params['password']);
        foreach ($params as $k => $v) {
            $this->assertEquals($result[$k], $v);
        }
    }

    /**
     * Data provider for index test
     *
     * @return array
     */
    public function providerUpdate(): array
    {
        return [
            // example 0 : update only name
            [
                // params
                ['name' => 'name test',],
            ],
            // example 1 : update only email
            [
                // params
                ['email' => 'test2@devcorp.com',],
            ],
            // example 2 : update only account_type
            [
                // params
                ['account_type' => AccountTypes::TYPE_TEAM,],
            ],
            // example 3 : update only password
            [
                // params
                ['password' => 'password2',],
            ],
            // example 4 : update
            [
                // params
                [
                    'name' => 'name test',
                    'email' => 'test2@devcorp.com',
                    'account_type' => AccountTypes::TYPE_TEAM,
                    'password' => 'password2',
                ],
            ],

        ];
    }

    /**
     * Test destroy
     * @return void
     */
    public function testDestroy()
    {
        /** @var User $user */
        $user = User::query()->first();

        // delete
        $response = $this->delete(self::API . $user->id);
        $response->assertStatus(200);

        // check
        $this->assertNull(User::query()->find($user->id));
    }

}
