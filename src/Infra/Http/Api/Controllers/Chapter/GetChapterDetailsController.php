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
    public function show(string $chapter)
    {
        $chapter = Cache::remember('chapter::'.$chapter, 60, function () use ($chapter) {
            $current = Chapter::query()
                ->with(['pages', 'manga',])
                ->withCount(['pages'])
                ->whereId($chapter)
                ->orWhere('slug', $chapter)
                ->firstOrFail();

            $prev = $current->prev();
            $next = $current->next();

            return [
                'chapter' => $current,
                'prev' => $prev,
                'next' => $next
            ];
        });

        return response()->json($chapter);
    }
}
