<?php

namespace App\Manga\UseCases;


use App\Manga\Enums\MangaDemography;
use App\Manga\Enums\MangaFormat;
use App\Manga\Enums\MangaStatus;
use App\Manga\Repositories\IMangaRepository;
use Illuminate\Support\Facades\Config;
use Support\Http\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class RegisterMangaUseCase {

    public function __construct(private readonly IMangaRepository $mangaRepository)
    {
    }

    public function execute(array $input): JsonResponse
    {
        try {
            $mangaSlug = str($input['title'])->slug('-');

            $input['slug']       = $input['title'];
            $input['format']     = MangaFormat::from($input['format']);
            $input['demography'] = MangaDemography::from($input['demography']);
            $input['status']     = MangaStatus::ACTIVE;
            $input['is_adult']   = $input['is_adult'] ?? false;

            $mangaAlreadyExists = $this->mangaRepository->exists($mangaSlug);
            if ($mangaAlreadyExists) {
                return HttpResponse::conflict('Manga already exists!');
            }

            $coversFolder = 'mangas/'.$mangaSlug.'/covers/';

            if ($input['cover']) {
                $filename = time().'-cover.'.$input['cover']->getClientOriginalExtension();
                $filepath = $coversFolder.$filename;

                $s3_path = Storage::disk('s3')->put($filepath, file_get_contents($input['cover']));
//                dd(Config::all());
//
                $input['cover'] = $filepath;
            }


            if ($input['thumbnail']) {
                $filename = time().'-thumbnail.'.$input['thumbnail']->getClientOriginalExtension();
                $filepath = $coversFolder.$filename;

                Storage::disk('s3')->put($filepath, file_get_contents($input['thumbnail']));

                $input['thumbnail'] = $filepath;
            } else {
                $input['thumbnail'] = $input['cover'] ?? null;
            }

            $this->mangaRepository->create($input);

            return HttpResponse::created('Manga successfully saved!');
        } catch (\Exception $e) {
            return HttpResponse::fail($e->getMessage());
        }
    }
}
