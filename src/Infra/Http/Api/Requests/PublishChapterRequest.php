<?php

namespace Infra\Http\Api\Requests;

use App\Account\Domain\Enums\Role;
use Illuminate\Foundation\Http\FormRequest;

class PublishChapterRequest extends FormRequest {
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
            'number'         => 'required|string',
            'scan_id'        => 'nullable|exists:scans,id',
            'manga_id'       => 'required|exists:mangas,id',
            'scans_supports' => 'nullable|array',
            'cover'          => 'nullable|image|mimes:jpg,jpeg,png,gif,webp',
            'pages'          => 'required|array|max:500',
            'pages.*'        => 'image|mimes:jpg,jpeg,png,gif,webp'
        ];
    }
}