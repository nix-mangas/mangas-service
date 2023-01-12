<?php

namespace App\Scan\UseCases;

use App\Scan\Repositories\IScanRepository;
use Support\Http\HttpResponse;
use Illuminate\Http\JsonResponse;

class ListMembersByScanUseCase
{

    public function __construct(private readonly IScanRepository $scanRepository)
    {
    }

    public function execute(string $scan): JsonResponse
    {
        try {
            $members = $this->scanRepository->getAllMembersByScan($scan);

            if (!$members) {
                return HttpResponse::notFound('Scan not found');
            }

            return HttpResponse::ok($members);
        } catch (\Exception $e) {
            return HttpResponse::fail($e->getMessage());
        }
    }
}
