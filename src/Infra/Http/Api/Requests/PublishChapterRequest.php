<?php

namespace Infra\Http\Api\Requests;

use App\Account\Domain\Enums\Role;
use Illuminate\Foundation\Http\FormRequest;

class PublishChapterRequest extends FormRequest
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
            'number'         => 'nullable',
            'pages'          => 'nullable|array|max:500',
            'pages.*'        => 'image|mimes:jpg,jpeg,png,gif,webp',
            'content'        => 'nullable|string|min:35',
            'type'           => 'nullable|in:pages,text'
        ];
    }
}
