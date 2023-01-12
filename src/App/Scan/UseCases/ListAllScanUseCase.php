<?php

namespace App\Scan\UseCases;

use App\Scan\Repositories\IScanRepository;
use Support\Http\HttpResponse;
use Illuminate\Http\JsonResponse;

class ListAllScanUseCase
{

  public function __construct(private readonly IScanRepository $scanRepository)
  {
  }

  public function execute(array $filters): JsonResponse
  {
    try {
      $scans = $this->scanRepository->list($filters);

      return HttpResponse::ok($scans);
    } catch (\Exception $e) {
      return HttpResponse::fail($e->getMessage());
    }
  }
}
