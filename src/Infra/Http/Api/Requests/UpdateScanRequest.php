<?php

namespace Infra\Http\Api\Requests;

class UpdateScanRequest extends \Illuminate\Foundation\Http\FormRequest {
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
            'logo'        => 'nullable|string',
            'description' => 'nullable|string',
            'website'     => 'nullable|string',
            'discord'     => 'nullable|string',
            'facebook'    => 'nullable|string',
        ];
    }
}