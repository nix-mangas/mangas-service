<?php

namespace App\Chapter\Models;

use App\Manga\Models\Manga;
use App\Scan\Models\Scan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Contracts\Auditable;

class Chapter extends Model implements Auditable
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    use Searchable;

    protected $fillable = [
        'title',
        'number',
        'slug',
        'cover',
        'scans_supports',
        'manga_id',
        'scan_id',
        'is_published',
        'published_at',
        'published_plus_at',
    ];

    protected $casts = [
        'is_published'      => 'boolean',
        'published_at'      => 'datetime',
        'published_plus_at' => 'datetime',
    ];

    public function publish(): void
    {
        $this->attributes['published_plus_at'] = Carbon::now();
        $this->attributes['published_at']      = Carbon::now();
        $this->attributes['is_published']      = true;
        $this->save();
    }

    public function manga(): BelongsTo
    {
        return $this->belongsTo(Manga::class);
    }

    public function scan(): BelongsTo
    {
        return $this->belongsTo(Scan::class);
    }

    public function pages(): HasMany
    {
        return $this->hasMany(ChapterPage::class);
    }

    protected function cover(): Attribute
    {
        return Attribute::get(fn ($value) => Storage::url($value ?? env('DEFAULT_COVER_IMG')));
    }

    protected function scansSupports(): Attribute
    {
        return Attribute::set(fn (array $value) => implode(',', $value));
    }

    public function next()
    {
        return $this
            ->query()
            ->where('manga_id', $this->manga->id)
            ->where('number', '>', $this->number)
            ->orderBy('number', 'asc')
            ->first();
    }

    public function prev()
    {
        return $this
            ->query()
            ->where('manga_id', $this->manga->id)
            ->where('number', '<', $this->number)
            ->orderBy('number', 'desc')
            ->first();
    }
}
