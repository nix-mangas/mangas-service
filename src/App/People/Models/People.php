<?php

namespace App\People\Models;

use App\Manga\Models\Manga;
use App\People\Enums\PeopleGender;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Contracts\Auditable;

class People extends Model implements Auditable {
    use HasFactory;
    use HasUuids;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    use Searchable;

    protected $table = 'peoples';

    protected $fillable = [
        'name',
        'slug',
        'photo',
        'bio',
        'alternative_names',
        'gender'
    ];

    protected $casts = [
        'gender' => PeopleGender::class
    ];

    public function mangas(): BelongsToMany
    {
        return $this->belongsToMany(
            Manga::class,
            'manga_staff',
            'people_id',
            'manga_id'
        );
    }

    protected function slug(): Attribute
    {
        return Attribute::set(fn(string $value) => str($value)->slug('-'));
    }

    protected function alternativeNames(): Attribute
    {
        return Attribute::set(fn(array $value) => implode(',', $value));
    }

    protected function photo(): Attribute
    {
        return Attribute::get(fn($value) => Storage::url($value ?? env('DEFAULT_AUTHOR_IMG')));
    }
}
