<?php

namespace Infra\Http\Api\Controllers\Chapter;

use App\Chapter\Models\Chapter;
use App\Chapter\UseCases\GetChapterDetailsUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Infra\Http\App\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class GetChapterDetailsController extends Controller
{
    public function __construct(private readonly GetChapterDetailsUseCase $useCase)
    {
    }

    /**
     * @param Request $request
     * @param string $slug
     * @param string $number
     * @return JsonResponse
     */
    public function byNumber(Request $request, string $slug, string $number): JsonResponse
    {


        $key = $slug . '-' . $number;

        if (Cache::has($key)) {
            return Cache::get($key);
        }

        Cache::put($key, $this->useCase->execute(manga: $slug, chapter: $number), 60);

        return Cache::get($key);
    }

    public function show(string $chapter)
    {
        $chapter = Cache::remember('chapter::'.$chapter, 60, function () use ($chapter) {
            return Chapter::query()
                ->with(['pages', 'manga'])
                ->withCount(['pages'])
                ->whereId($chapter)
                ->orWhere('slug', $chapter)
                ->firstOrFail();
        });

        return response()->json($chapter);
    }
}
