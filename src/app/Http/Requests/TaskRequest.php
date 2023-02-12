<?php

namespace App\Http\Requests;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Validation\Rule;

class TaskRequest extends ApiFormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $isUpdate = $this->route()->getName() == 'tasks.update';
        $rules = [
            'title' => [$isUpdate ? 'min:1' : 'required'],
            'description' => ['nullable'],
            'status' => [Rule::in(array_keys(Task::STATUSES))],
        ];
        if (!$isUpdate) {
            $projectTable = Project::getTableName();
            $rules['project_id'] = ['required', "exists:$projectTable,id"];
        }
        return $rules;
    }
}
