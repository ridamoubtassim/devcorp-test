<?php

namespace Tests\Unit\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\UserTask;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    /**
     * @const string
     */
    const API = '/api/tasks/';

    /**
     * Test show
     * @return void
     */
    public function testShow()
    {
        /** @var Task $task */
        $task = Task::query()->with('users')->first();
        $response = $this->get(self::API . $task->id);
        $response->assertStatus(200);

        // get result
        $content = $response->getContent();
        $this->assertJson($content);
        $result = json_decode($content, true);

        // compare
        $this->assertArrayHasKey('data', $result);
        $result = $result['data'];
        $this->assertEquals($result, $task->toArray());
    }

    /**
     * Test store
     * @return void
     */
    public function testStore()
    {
        /** @var Project $project */
        $project = Project::query()->first(['id']);
        $params = [
            'title' => 'title example',
            'description' => 'description example',
            'status' => Task::REVIEW_STATUS, // optional
            'project_id' => $project->id,
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

        // TODO: compare created_by
    }

    /**
     * Test update
     * @dataProvider providerUpdate
     * @return void
     */
    public function testUpdate($params)
    {
        /** @var Task $task */
        $task = Task::query()->first();
        $response = $this->put(self::API . $task->id, $params);
        $response->assertStatus(200);

        // get result
        $content = $response->getContent();
        $this->assertJson($content);
        $result = json_decode($content, true);

        // compare
        $this->assertArrayHasKey('data', $result);
        $result = $result['data'];
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
            // example 0 : update only title
            [
                // params
                ['title' => 'name example 2',],
            ],
            // example 2 : update only description
            [
                // params
                ['description' => 'description example 2',],
            ],
            // example 2 : update only status
            [
                // params
                ['description' => Task::DONE_STATUS,],
            ],
            // example 2 : update all
            [
                // params
                [
                    'title' => 'name example 2',
                    'description' => 'description example 2',
                    'status' => Task::PENDING_STATUS,
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
        /** @var Task $task */
        $task = Task::query()->first();

        // delete
        $response = $this->delete(self::API . $task->id);
        $response->assertStatus(200);

        // check
        $this->assertNull(Task::query()->find($task->id));
        $this->assertEmpty(UserTask::query()->where('task_id', $task->id)->get(['id']));
    }
}
