<?php

namespace App\Http\Requests;

class ProjectRequest extends ApiFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $isUpdate = $this->route()->getName() == 'projects.update';
        return [
            'title' => [$isUpdate ? null : 'required'],
            'description' => ['nullable'],
        ];
    }
}
