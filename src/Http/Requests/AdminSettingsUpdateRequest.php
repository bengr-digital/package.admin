<?php

namespace Bengr\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminSettingsUpdateRequest extends FormRequest
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
            'phone' => ['string', 'max:100'],
            'email' => ['email', 'max:100'],
            'socials.*' => ['url'],
            'billing.name' => ['string', 'max:100'],
            'billing.cin' => ['string', 'max:100'],
            'billing.tin' => ['string', 'max:100'],
            'billing.country' => [Rule::validCountryCode()],
            'billing.city' => ['string', 'max:100'],
            'billing.zipcode' => ['string', 'max:100'],
            'billing.street' => ['string', 'max:100'],
        ];
    }
}
