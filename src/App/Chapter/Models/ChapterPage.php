<?php

namespace App\Chapter\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use OwenIt\Auditing\Contracts\Auditable;

class ChapterPage extends Model implements Auditable {
    use HasFactory;
    use HasUuids;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'page_number',
        'page_url',
        'chapter_id'
    ];

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    protected function pageUrl(): Attribute
    {
        return Attribute::get(fn($value) => Storage::url($value));
    }
}
