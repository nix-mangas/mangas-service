<?php

namespace App\Scan\Models;

use App\Chapter\Models\Chapter;
use App\Manga\Models\Manga;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use OwenIt\Auditing\Contracts\Auditable;

class Scan extends Model implements Auditable {
    use HasFactory;
    use HasUuids;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'name',
        'email',
        'slug',
        'logo',
        'description',
        'website',
        'discord',
        'facebook',
    ];

    public function mangas(): BelongsToMany
    {
        return $this->belongsToMany(
            Manga::class,
            'scan_manga',
            'scan_id',
            'manga_id'
        );
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class);
    }

    protected function slug(): Attribute
    {
        return Attribute::set(fn(string $value) => str($value)->slug('-'));
    }

    protected function logo(): Attribute
    {
        return Attribute::get(fn($value) => Storage::url($value ?? env('DEFAULT_SCAN_IMG')));
    }

}
