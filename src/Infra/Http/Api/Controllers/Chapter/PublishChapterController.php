<?php

namespace Infra\Http\Api\Controllers\Chapter;

use Intervention\Image\Facades\Image as Image;
use App\Chapter\Models\Chapter;
use App\Chapter\Models\ChapterPage;
use App\Chapter\UseCases\PublishChapterUseCase;
use App\Manga\Models\Manga;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Infra\Http\Api\Requests\PublishChapterRequest;
use Infra\Http\App\Controllers\Controller;

class PublishChapterController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param PublishChapterRequest $request
     */
    public function __invoke(PublishChapterRequest $request, string $manga)
    {
        $manga = Manga::query()->where('id', $manga)->orWhere('slug', $manga)->firstOrFail();

        $lastChapterNumber = intval($manga->lastChapter?->number) ?? 0;

        $chapter = new Chapter();

        $chapter->is_published = false;
        $chapter->manga_id = $manga->id;
        $chapter->number = $request?->number ?? $lastChapterNumber + 1;
        $chapter->title = $manga->title.' #'.$chapter->number;
        $chapter->slug = Str::slug($manga->title.' #'.$chapter->number);


        $chapter->save();

        $pages = [];

        if ($request->hasFile('pages')) {
            foreach ($request->file('pages') as $key => $file) {
                $pageNumber= (int) $key + 1;
                $filename = 'mangas/'.$manga->id.'/'.$chapter->id.'/'.time().'-'.$pageNumber.'.webp';

                $image = Image::make($file)->encode('webp', 75);

                Storage::disk('s3')->put($filename, $image);

                $page = new ChapterPage();

                $page->page_number = $pageNumber;
                $page->page_url = $filename;

                array_push($pages, $page);
            }
        }

        $chapter->pages()->saveMany($pages);

        $chapter->update([
            'is_published' => true,
            'published_at' => now(),
        ]);

        $manga->update([
            'last_published_at' => now(),
        ]);

        return response()->json([
            'data' => $chapter,
            'status' => 'published',
        ], 201);
    }
}
