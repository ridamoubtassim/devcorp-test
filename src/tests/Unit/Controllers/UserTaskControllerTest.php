<?php

namespace Tests\Unit\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\UserTask;
use Tests\TestCase;

class UserTaskControllerTest extends TestCase
{
    /**
     * @const string
     */
    const API = '/api/user-tasks/';

    /**
     * Test store
     * @dataProvider providerStore
     * @return void
     */
    public function testStore($paramsClosure, int $code)
    {
        $response = $this->post(self::API, $paramsClosure());
        $response->assertStatus($code);

        // get result
        $content = $response->getContent();
        $this->assertJson($content);
        $result = json_decode($content, true);

        // compare
        $this->assertArrayHasKey('data', $result);
        $result = $result['data'];
        $this->assertEquals(1, UserTask::query()->where('id', $result['id'])->count());
    }

    /**
     * Data provider for store
     * @return array
     */
    public function providerStore(): array
    {
        return [
            // example 1 : user isn't assigned to the task
            [
                // params
                function () {
                    /** @var Task $task */
                    $task = Task::query()->first();
                    /** @var User $user */
                    $user = User::factory()->create([
                        'email' => 'not-assigned-user@devcorp.com'
                    ]);
                    return [
                        'task_id' => $task->id,
                        'user_id' => $user->id,
                    ];
                },
                // code
                201
            ],
            // example 2 : user already assigned to the task
            [
                // params
                function () {
                    /** @var UserTask $userTask */
                    $userTask = UserTask::query()->first();
                    return [
                        'task_id' => $userTask->task_id,
                        'user_id' => $userTask->user_id,
                    ];
                },
                // code
                200
            ]
        ];
    }

    /**
     * Test destroy
     * @return void
     */
    public function testDestroy()
    {
        /** @var UserTask $userTask */
        $userTask = UserTask::query()->first();

        // delete
        $response = $this->delete(self::API . $userTask->id);
        $response->assertStatus(200);

        // check
        $this->assertNull(UserTask::query()->find($userTask->id));
    }
}
