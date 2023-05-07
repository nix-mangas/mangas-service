<?php

namespace App\Infra\Http\Api\Controllers\Manga;

use App\Manga\Models\Manga as ModelsManga;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Infra\Http\App\Controllers\Controller;

class LatestChaptersController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function latest(Request $request)
    {
        $showNotAdultContent = !$request->boolean('show_adult_content', false);
        $format = $request->query('format');

        $key = 'mangas_latest::show_not_adult::'.$showNotAdultContent.'::format::'.$format;

        $mangas = Cache::remember(
            $key,
            60,
            function () use ($showNotAdultContent, $format) {
                return ModelsManga::query()
                    ->latestChapters()
                    ->when($showNotAdultContent, function (Builder $query) {
                        $query->whereIsAdult(false);
                    })
                    ->when(!!$format, function (Builder $query) use ($format) {
                        $query->whereFormat($format);
                    })
                    ->paginate();
            }
        );

        return response()->json($mangas);
    }
}
