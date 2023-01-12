<?php

namespace App\Scan\UseCases;

use App\Scan\Repositories\IScanRepository;
use Support\Http\HttpResponse;
use Illuminate\Http\JsonResponse;

class DeleteScanUseCase
{

    public function __construct(private readonly IScanRepository $scanRepository)
    {
    }

    public function execute(string $scan): JsonResponse
    {
        try {
            $this->scanRepository->delete($scan);

            return HttpResponse::ok([
                'status' => 'success',
                'message' => 'Deleted scan successfully!'
            ]);
        } catch (\Exception $e) {
            return HttpResponse::fail($e->getMessage());
        }
    }
}
