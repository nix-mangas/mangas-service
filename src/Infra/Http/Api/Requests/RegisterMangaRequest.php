<?php

namespace Infra\Http\Api\Requests;

use App\Manga\Enums\MangaDemography;
use App\Manga\Enums\MangaFormat;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class RegisterMangaRequest extends FormRequest {
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
            'title'              => 'required|string',
            'is_adult'           => 'nullable|boolean',
            'synopses'           => 'nullable|string',
            'thumbnail'          => 'nullable|image|mimes:jpg,jpeg,png,gif,webp',
            'alternative_titles' => 'nullable|array',
            'cover'              => 'nullable|image|mimes:jpg,jpeg,png,gif,webp',
            'genres'             => 'nullable|array',
            'genres.*'           => 'exists:genres,id',
            'staff'              => 'nullable|array',
            'staff.*'            => 'exists:peoples,id',
            'format'             => [ new Enum(MangaFormat::class) ],
            'demography'         => [ new Enum(MangaDemography::class) ],
        ];
    }
}
