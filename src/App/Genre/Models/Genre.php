<?php

namespace App\Genre\Models;

use App\Manga\Models\Manga;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Contracts\Auditable;

class Genre extends Model implements Auditable {
    use HasFactory;
    use HasUuids;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    use Searchable;

    protected $fillable = [
        'name',
        'slug',
        'icon',
    ];

    public function mangas(): BelongsToMany
    {
        return $this->belongsToMany(
            Manga::class,
            'manga_genre',
            'genre_id',
            'manga_id'
        );
    }

    protected function slug(): Attribute
    {
        return Attribute::set(fn(string $value) => str($value)->slug('-'));
    }

    protected function icon(): Attribute
    {
        return Attribute::get(fn($value) => Storage::url($value ?? env('DEFAULT_ICON_IMG')));
    }
}
