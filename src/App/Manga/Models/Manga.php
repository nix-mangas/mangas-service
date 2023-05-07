<?php

namespace App\Manga\Models;

use App\Chapter\Models\Chapter;
use App\Manga\Enums\MangaDemography;
use App\Manga\Enums\MangaFormat;
use App\Manga\Enums\MangaStatus;
use App\People\Models\People;
use App\Scan\Models\Scan;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Builder;
use OwenIt\Auditing\Contracts\Auditable;

class Manga extends Model implements Auditable
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;
    use Searchable;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'title',
        'slug',
        'synopses',
        'thumbnail',
        'cover',
        'alternative_titles',
        'status',
        'format',
        'demography',
        'is_adult',
        'last_published_at'
    ];

    protected $casts = [
        'status'            => MangaStatus::class,
        'format'            => MangaFormat::class,
        'demography'        => MangaDemography::class,
        'is_adult'          => 'boolean',
        'last_published_at' => 'datetime'
    ];

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(
            \App\Genre\Models\Genre::class,
            'manga_genre',
            'manga_id',
            'genre_id'
        );
    }

    public function staff(): BelongsToMany
    {
        return $this->belongsToMany(
            People::class,
            'manga_staff',
            'manga_id',
            'people_id'
        );
    }

    public function scans(): BelongsToMany
    {
        return $this->belongsToMany(
            Scan::class,
            'scan_manga',
            'manga_id',
            'scan_id'
        );
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }

    public function oldestChapter()
    {
        return $this->hasOne(Chapter::class)->oldestOfMany();
    }

    public function latestChapter()
    {
        return $this->hasOne(Chapter::class)->latestOfMany();
    }

    protected function slug(): Attribute
    {
        return Attribute::set(fn (string $value) => str($value)->slug('-'));
    }

    protected function alternativeTitles(): Attribute
    {
        return Attribute::set(fn (array $value) => implode(',', $value));
    }

    protected function cover(): Attribute
    {
        return Attribute::get(fn ($value) => Storage::url($value ?? env('DEFAULT_COVER_IMG')));
    }

    protected function thumbnail(): Attribute
    {
        return Attribute::get(fn ($value) =>  Storage::url($value ?? env('DEFAULT_THUMBNAIL_IMG')));
    }

    public function scopeLatestChapters(Builder $query): Builder
    {
        return $query
            ->whereHas('chapters', function ($query) {
                $query
                    ->where('published_at', '>=', now()->startOfWeek())
                    ->orderBy('published_at', 'desc');
            })
            ->with(['chapters' => function ($query) {
                $query
                    ->withCount('pages')
                    ->where('published_at', '>=', now()->startOfWeek())
                    ->orderBy('published_at', 'desc')
                    ->get();
            }]);
    }
}
