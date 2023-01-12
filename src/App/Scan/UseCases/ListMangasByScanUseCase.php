<?php

namespace App\Scan\UseCases;

use App\Scan\Repositories\IScanRepository;
use Support\Http\HttpResponse;
use Illuminate\Http\JsonResponse;

class ListMangasByScanUseCase
{

    public function __construct(private readonly IScanRepository $scanRepository)
    {
    }

    public function execute(string $scan): JsonResponse
    {
        try {
            $mangas = $this->scanRepository->getAllMangasByScan($scan);

            if (!$mangas) {
                return HttpResponse::notFound('Scan not found');
            }

            return HttpResponse::ok($mangas);
        } catch (\Exception $e) {
            return HttpResponse::fail($e->getMessage());
        }
    }
}
