<?php

namespace Tests\Unit\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\UserTask;
use Tests\TestCase;

class ProjectControllerTest extends TestCase
{
    /**
     * @const string
     */
    const API = '/api/projects/';

    /**
     * A basic unit test example.
     *
     * @dataProvider providerIndex
     * @return void
     */
    public function testIndex($params, $data, $compare)
    {
        $response = $this->get(self::API, [], $params);
        $response->assertStatus(200);

        // get result
        $content = $response->getContent();
        $this->assertJson($content);
        $result = json_decode($content, true);

        // compare $result with $data()
        $compare($result, $data());
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
                    // search : search by project title
                    // with : with tasks or user who created this project
                    // sort : by default by id
                    // order : there is no sort_by
                    // paginate : by default is false
                ],
                // data
                function () {
                    return Project::query()->get()->toArray();
                },
                // compare
                function ($result, $data) {
                    $this->assertArrayHasKey('data', $result);
                    $this->assertEquals($result['data'], $data);
                }
            ],
            // TODO: test other cases
        ];
    }

    /**
     * Test show
     * @dataProvider providerShow
     * @return void
     */
    public function testShow($with)
    {
        /** @var Project $project */
        $project = Project::query()->with($with)->first();
        $response = $this->get(self::API . $project->id, [], ['with' => $with]);
        $response->assertStatus(200);

        // get result
        $content = $response->getContent();
        $this->assertJson($content);
        $result = json_decode($content, true);

        // compare
        $this->assertArrayHasKey('data', $result);
        $result = $result['data'];
        $this->assertEquals($result, $project->toArray());
    }

    /**
     * Data provider for show
     * @return void
     */
    public function providerShow(): array
    {
        return [
            // example 0
            ['with' => []],
            // example 1
            ['with' => ['user']],
            // example 2
            ['with' => ['tasks']],
            // example 3
            ['with' => ['user', 'tasks']],
        ];
    }

    /**
     * Test store
     * @return void
     */
    public function testStore()
    {
        $params = [
            'title' => 'name example',
            'description' => 'description example',
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

        /** @var Project $project */
        $project = Project::query()->first();
        $response = $this->put(self::API . $project->id, $params);
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
            // example 2 : update all
            [
                // params
                [
                    'title' => 'name example 2',
                    'description' => 'description example 2',
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
        /** @var Project $project */
        $project = Project::query()->first();
        $tasks = $project->tasks()->pluck('id'); // for best performance

        // delete
        $response = $this->delete(self::API . $project->id);
        $response->assertStatus(200);

        // check
        $this->assertNull(Project::query()->find($project->id));
        $this->assertEmpty(Task::query()->where('project_id', $project->id)->get(['id']));
        $this->assertEmpty(UserTask::query()->whereIn('task_id', $tasks)->get(['id']));
    }
}
