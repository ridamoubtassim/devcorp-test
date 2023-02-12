<?php

namespace App\Http\Controllers;

use App\Helpers\ListingTrait;
use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Models\UserTask;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    use ListingTrait;

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * -> with       : array['user', 'tasks']
     * -> search     : string (search by project title)
     * -> sort_by    : string
     * -> order      : string [ask|desk]
     * -> paginate   : boolean
     *
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        return $this->listing(
            Project::class,
            ProjectResource::class,
            'title',
            $request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProjectRequest $request
     * @return ProjectResource
     */
    public function store(ProjectRequest $request): ProjectResource
    {
        // create project
        $validatedData = $request->validated();
        $validatedData['created_by'] = Auth::id();
        $project = Project::query()->create($validatedData);

        // return
        return new ProjectResource($project);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param Project $project
     * -> with : array['user', 'tasks']
     *
     * @return ProjectResource
     */
    public function show(Request $request, Project $project): ProjectResource
    {
        $project->loadMissing($request->get('with', []));
        return new ProjectResource($project);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProjectRequest $request
     * @param Project $project
     * @return ProjectResource
     */
    public function update(ProjectRequest $request, Project $project): ProjectResource
    {
        $project->update($request->validated());
        return new ProjectResource($project);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Project $project
     * @return JsonResponse
     */
    public function destroy(Project $project): JsonResponse
    {
        // delete user tasks
        $tasks = $project->tasks();
        UserTask::query()->whereIn('task_id', $tasks->pluck('id'))->delete();

        // delete tasks
        $tasks->delete();

        // delete project
        $project->delete();

        // return
        return response()->json(['message' => 'Successfully deleted']);
    }
}
