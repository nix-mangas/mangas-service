<?php

namespace App\Manga\Enums;

enum MangaStatus: string {
    case COMPLETED = 'COMPLETED';
    case HIATO = 'HIATO';
    case ACTIVE = 'ACTIVE';
    case CANCELLED = 'CANCELLED';
    case DMCA = 'DMCA';
}
