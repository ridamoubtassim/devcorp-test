<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserTaskRequest;
use App\Http\Resources\UserTaskResource;
use App\Models\UserTask;
use Illuminate\Http\JsonResponse;

class UserTaskController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param UserTaskRequest $request
     * @return UserTaskResource
     */
    public function store(UserTaskRequest $request): UserTaskResource
    {
        $user = UserTask::query()->updateOrCreate($request->validated());
        return new UserTaskResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param UserTask $userTask
     * @return JsonResponse
     */
    public function destroy(UserTask $userTask): JsonResponse
    {
        $userTask->delete();
        return response()->json(['message' => 'Successfully deleted']);
    }
}
