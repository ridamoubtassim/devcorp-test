<?php

namespace App\Http\Requests;

use App\Models\Task;
use App\Models\User;

class UserTaskRequest extends ApiFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $taskTable = Task::getTableName();
        $userTable = User::getTableName();
        return [
            'task_id' => ['required', "exists:$taskTable,id"],
            'user_id' => ['required', "exists:$userTable,id"],
        ];
    }
}
