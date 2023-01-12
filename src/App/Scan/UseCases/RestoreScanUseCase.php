<?php

namespace App\Scan\UseCases;

use App\Scan\Repositories\IScanRepository;
use Support\Http\HttpResponse;
use Exception;
use Illuminate\Http\JsonResponse;

class RestoreScanUseCase
{

    public function __construct(private readonly IScanRepository $scanRepository)
    {
    }

    public function execute(string $scan): JsonResponse
    {
        try {
            $this->scanRepository->restore($scan);

            return HttpResponse::ok([
                'status' => 'success',
                'message' => 'Restored scan successfully!'
            ]);
        } catch (Exception $e) {
            return HttpResponse::fail($e->getMessage());
        }
    }
}
