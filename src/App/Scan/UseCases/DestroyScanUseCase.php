<?php

namespace App\Scan\UseCases;

use App\Scan\Repositories\IScanRepository;
use Support\Http\HttpResponse;
use Illuminate\Http\JsonResponse;

class DestroyScanUseCase
{

    public function __construct(private readonly IScanRepository $scanRepository)
    {
    }

    public function execute(string $scan): JsonResponse
    {
        try {
            $this->scanRepository->destroy($scan);

            return HttpResponse::ok([
                'status' => 'success',
                'message' => 'Destroyed scan successfully!'
            ]);
        } catch (\Exception $e) {
            return HttpResponse::fail($e->getMessage());
        }
    }
}
