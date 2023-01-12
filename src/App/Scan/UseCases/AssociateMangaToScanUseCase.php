<?php

namespace App\Scan\UseCases;

use App\Scan\Repositories\IScanRepository;
use Support\Http\HttpResponse;
use Illuminate\Http\JsonResponse;

class AssociateMangaToScanUseCase
{

    public function __construct(private readonly IScanRepository $scanRepository)
    {
    }

    public function execute(string $slug, array $mangas): JsonResponse
    {
        try {
            $scan = $this->scanRepository->findBySlug($slug);
            if (!$scan) {
                return HttpResponse::notFound('Scan not found!');
            }

            $scan->mangas()->sync($mangas);

            return HttpResponse::created('Associate manga to scan successfully!');
        } catch (\Exception $e) {
            return HttpResponse::fail($e->getMessage());
        }
    }
}
