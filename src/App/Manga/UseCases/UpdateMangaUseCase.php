<?php

namespace App\Manga\UseCases;

use App\Manga\Repositories\IMangaRepository;
use Support\Http\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class UpdateMangaUseCase {
    public function __construct(private readonly IMangaRepository $mangaRepository)
    {
    }

    public function execute(string $id, array $input): JsonResponse
    {
        try {
            $manga = $this->mangaRepository->findById($id);

            $coversFolder = 'mangas/'.$manga->slug.'/covers/';

            if ($input['cover']) {
                $filename = time().'-cover.'.$input['cover']->getClientOriginalExtension();
                $filepath = $coversFolder.$filename;

                Storage::disk('s3')->put($filepath, file_get_contents($input['cover']));

                $input['cover'] = $filepath;
            }


            if ($input['thumbnail']) {
                $filename = time().'-thumbnail.'.$input['thumbnail']->getClientOriginalExtension();
                $filepath = $coversFolder.$filename;

                Storage::disk('s3')->put($filepath, file_get_contents($input['thumbnail']));

                $input['thumbnail'] = $filepath;
            }

            $this->mangaRepository->save(id: $id, data: $input);

            return HttpResponse::created('Manga successfully updated!');
        } catch (\Exception $e) {
            return HttpResponse::fail($e->getMessage());
        }

    }
}