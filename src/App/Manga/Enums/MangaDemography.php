<?php

namespace App\Manga\Enums;

enum MangaDemography: string {
    case SHOUNEN = 'SHOUNEN';
    case SHOUJO = 'SHOUJO';
    case SEINEN = 'SEINEN';
    case JOSEI = 'JOSEI';
}
