<?php

namespace App\Chapter\UseCases;

use App\Chapter\Repositories\IChapterRepository;
use App\Manga\Repositories\IMangaRepository;
use Carbon\Carbon;
use Support\Http\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class PublishChapterUseCase {
    public function __construct(
        private readonly IChapterRepository $chapterRepository,
        private readonly IMangaRepository   $mangaRepository,
    )
    {
    }

    public function execute(array $input): JsonResponse
    {
        try {
            $manga = $this->mangaRepository->findById($input['manga_id']);
            if (!$manga) {
                return HttpResponse::notFound('Manga not found');
            }

            $input['is_published'] = false;

            if (!empty($input['number'])) {
                $chapterAlreadyExits = $this->chapterRepository->exists(
                    manga : $manga->id,
                    number: $input['number']
                );
                if ($chapterAlreadyExits) {
                    return HttpResponse::conflict(
                        'Chapter with number "'.$input['number'].'" in "'.$manga['title'].'" already exists!'
                    );
                }

                $input['title']        = $manga['title'].' #'.$input['number'];
                $input['slug']         = $manga['slug'].'/'.str($input['number'])->slug('.');
                $chapterFolder = 'mangas/'.$manga['slug'].'/'.$input['number'].'/';
            } else {
                $latestChapterNumber  = $this->chapterRepository->getLatestChapterByManga($manga['slug']);
                $nextChapter = $latestChapterNumber  + 1;
                $input['number'] = $nextChapter;
                $input['title'] = $manga['title'].' #'.$nextChapter;
                $input['slug'] = $manga['slug'].'/'.str($nextChapter)->slug('.');
                $chapterFolder = 'mangas/'.$manga['slug'].'/'.$nextChapter.'/';
            }

            if (!$input['pages']) {
                return HttpResponse::clientError('pages is not provided!');
            }

            if ($input['cover']) {
                $filename = time().'-cover.'.$input['cover']->getClientOriginalExtension();
                $filepath = $chapterFolder.$filename;

                Storage::disk('s3')->put($filepath, file_get_contents($input['cover']));

                $input['cover'] = $filepath;
            }

            $chapter = $this->chapterRepository->create($input);

            $pages = [];

            foreach ($input['pages'] as $key => $page) {
                $filename = time().$key+1.$page->getClientOriginalExtension();
                $filepath = $chapterFolder.$filename;

                Storage::disk('s3')->put($filepath, file_get_contents($page));

                $pages[$key] = [
                    'page_number' => $key + 1,
                    'page_url'    => $filepath,
                ];
            }

            $this->chapterRepository->savePages($chapter['id'], $pages);

            $chapter->publish();

            $manga['last_published_at'] = Carbon::now();
            $manga->save();

            return HttpResponse::created('Publish chapter successfully!');
        } catch (\Exception $e) {
            return HttpResponse::fail($e->getMessage());
        }
    }
}
