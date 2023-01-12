<?php

namespace App\Scan\UseCases;

use App\Scan\Repositories\IScanRepository;
use Support\Http\HttpResponse;
use Illuminate\Http\JsonResponse;

class GetScanBySlugUseCase
{

    public function __construct(private readonly IScanRepository $scanRepository)
    {
    }

    public function execute(string $slug): JsonResponse
    {
        try {
            $scan = $this->scanRepository->findBySlug($slug);

            if ($scan === null) {
                return HttpResponse::notFound('Scan not found!');
            }

            return HttpResponse::ok([
                'status' => 'success',
                'scan' => $scan
            ]);
        } catch (\Exception $e) {
            return HttpResponse::fail($e->getMessage());
        }
    }
}
