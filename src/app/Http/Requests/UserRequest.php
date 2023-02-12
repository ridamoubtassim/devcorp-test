<?php

namespace App\Http\Requests;

use App\Helpers\AccountTypes;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;

class UserRequest extends ApiFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $isUpdate = $this->route()->getName() == 'users.update';
        $required = $isUpdate ? null : 'required';
        $userTable = User::getTableName();
        return [
            'name' => [$required],
            'password' => [$required, 'min:8'],
            'account_type' => [Rule::in(AccountTypes::getAccountTypeKeys())],
            'email' => [
                $required,
                'email',
                Rule::unique($userTable, 'email')
                    // When request is update
                    ->where(function ($query) use ($isUpdate) {
                        /** @var Builder $query */
                        if ($isUpdate) {
                            /** @var User $user */
                            $user = $this->route('user');
                            return $query->where('id', '!=', $user->id);
                        }
                        return $query;
                    })
            ],
        ];
    }
}
