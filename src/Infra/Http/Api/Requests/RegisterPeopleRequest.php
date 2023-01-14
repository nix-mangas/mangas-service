<?php

namespace Infra\Http\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterPeopleRequest extends FormRequest {
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
            'name'              => 'required|string',
            'bio'               => 'nullable|string',
            'photo'             => 'nullable|image|mimes:jpg,jpeg,png,gif,webp',
            'alternative_names' => 'nullable|array',
            'gender'            => 'nullable|string',
        ];
    }
}
