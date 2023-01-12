<?php

namespace App\Scan\UseCases;

use App\Scan\Repositories\IScanRepository;
use Support\Http\HttpResponse;
use Illuminate\Http\JsonResponse;

class UpdateScanUseCase {
    public function __construct(private readonly IScanRepository $scanRepository)
    {
    }

    public function execute(string $id, array $data): JsonResponse
    {
        try {
            $this->scanRepository->save($id, $data);

            return HttpResponse::created('Scan successfully updated!');
        } catch (\Exception $e) {
            return HttpResponse::fail($e->getMessage());
        }
    }
}