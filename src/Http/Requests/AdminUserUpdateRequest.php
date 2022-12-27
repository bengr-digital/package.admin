<?php

namespace Bengr\Admin\Http\Requests;

use Bengr\Admin\Facades\Admin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminUserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'first_name' => ['string', 'max:100'],
            'last_name' => ['string', 'max:100'],
            'username' => ['string', 'max:100', Rule::unique(Admin::authUserModel())->ignore($this->user(Admin::getGuardName()) ? $this->user(Admin::getGuardName())->username : null, 'username')],
            'email' => ['email', 'max:100', Rule::unique(Admin::authUserModel())->ignore($this->user(Admin::getGuardName()) ? $this->user(Admin::getGuardName())->email : null, 'email')],
            'old_password' => ['nullable', Rule::validOldPassword(Admin::getGuardName())],
            'new_password' => ['required_with:old_password', 'min:8', 'same:new_password_confirm'],
            'new_password_confirm' => ['required_with:old_password'],
        ];
    }
}
