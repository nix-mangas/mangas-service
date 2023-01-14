<?php

namespace Infra\Http\Api\Requests;

use App\Manga\Enums\MangaDemography;
use App\Manga\Enums\MangaFormat;
use Illuminate\Validation\Rules\Enum;

class UpdateMangaRequest extends \Illuminate\Foundation\Http\FormRequest {
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
            'title'              => 'nullable|string',
            'is_adult'           => 'nullable',
            'synopses'           => 'nullable|string',
            'thumbnail'          => 'nullable|image|mimes:jpg,jpeg,png,gif,webp',
            'alternative_titles' => 'nullable|array',
            'cover'              => 'nullable|image|mimes:jpg,jpeg,png,gif,webp',
            'format'             => [ 'nullable', new Enum(MangaFormat::class) ],
            'demography'         => [ 'nullable', new Enum(MangaDemography::class) ],
        ];
    }
}
