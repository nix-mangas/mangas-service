<?php

namespace Infra\Http\Api\Controllers\Chapter;

use Intervention\Image\Facades\Image as Image;
use App\Chapter\Models\Chapter;
use App\Chapter\Models\ChapterPage;
use App\Chapter\UseCases\PublishChapterUseCase;
use App\Manga\Models\Manga;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
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
    public function __invoke(PublishChapterRequest $request, string $manga): JsonResponse
    {
        try {
            $manga = Manga::query()
                ->where('id', $manga)
                ->orWhere('slug', $manga)
                ->first();

            if (!$manga) {
                return response()->json(['message' => 'Manga not found'], 404);
            }

            $lastChapterNumber = (int) $manga->lastChapter?->number ?? 0;
            $chapter = new Chapter();

            $number = $request->get('number') ?? $lastChapterNumber + 1;
            $title = "Capítulo #{$number} - {$manga->title}";

            $chapter->is_published = false;
            $chapter->manga_id = $manga->id;
            $chapter->number = $number;
            $chapter->title = $title;
            $chapter->slug = Str::slug($title);

            $chapter->save();

            if ($request->hasFile('pages')) {
                $pages = [];

                foreach ($request->file('pages') as $key => $file) {
                    $pageNumber= (int) $key + 1;
                    $filename = 'mangas/'.$manga->id.'/'.$chapter->id.'/'.time().'-'.$pageNumber.'.webp';

                    $image = Image::make($file)->encode('webp', 75);

                    Storage::disk('s3')->put($filename, $image);

                    $page = new ChapterPage();

                    $page->page_number = $pageNumber;
                    $page->page_url = $filename;

                    $pages[] = $page;
                }

                $chapter->pages()->saveMany($pages);
            }

            $chapter->is_published = true;
            $chapter->published_at = now();

            $chapter->save();

            $manga->last_published_at = now();
            $manga->save();

            if(strlen($manga->title) > 60) {
                $title = substr($manga->title, 0, 60) . "...";
            } else {
                $title = $manga->title;
            }

            if(strlen($manga->synopses) > 200) {
                $synopsis = substr($manga->synopses, 0, 200) . "...";
            } else {
                $synopsis = $manga->synopses;
            }

            Http::post(config('services.discord.web_hook'), [
                "content"=> "<@&1109508894360867017>",
                "embeds"=> [
                    [
                        "title"=> "Capítulo #{$chapter->number} - {$title}",
                        "url"=> "https://www.nixmangas.com/ler/".$chapter->id,
                        "color"=> 10755066,
                        "fields"=> [
                            [
                                "name"=> "Sinopse",
                                "value"=> $synopsis
                            ]
                        ],
                        "author"=> [
                            "name"=> "Nix Mangas",
                            "icon_url"=> "https://cdn.nixmangas.com/logo-nix.png"
                        ],
                        "footer"=> [
                            "text"=> "Publicado por Nix Mangas"
                        ],
                        "timestamp"=> now(),
                        "image"=> [
                            "url"=> "https://cdn.nixmangas.com/banner.png"
                        ],
                        "thumbnail"=> [
                            "url"=> Storage::disk('s3')->url($manga->cover)
                        ]
                    ]
                ],
                "attachments"=> []
            ]);

            return response()->json([
                'data' => $chapter,
                'status' => 'published',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'error',
            ]);
        }
    }
}
