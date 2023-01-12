<?php

namespace Infra\Http\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePeopleRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
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
            'name'              => 'nullable|string',
            'photo'             => 'nullable|string',
            'bio'               => 'nullable|string',
            'alternative_names' => 'nullable|array',
        ];
    }
}