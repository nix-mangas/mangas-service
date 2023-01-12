<?php

namespace Infra\Http\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReadChapterRequest extends FormRequest {
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
            'chapter_read' => 'required',
            'last_page_read' => 'required'
        ];
    }
}