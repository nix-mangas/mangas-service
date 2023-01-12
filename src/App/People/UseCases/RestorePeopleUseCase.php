<?php

namespace App\People\UseCases;

use App\People\Repositories\IPeopleRepository;
use Support\Http\HttpResponse;
use Illuminate\Http\JsonResponse;

class RestorePeopleUseCase {

    public function __construct(private readonly IPeopleRepository $peopleRepository)
    {
    }

    public function execute(string $people): JsonResponse
    {
        try {
            $this->peopleRepository->restore($people);

            return HttpResponse::ok([
                'status'  => 'success',
                'message' => 'People restored successfully!'
            ]);
        } catch (\Exception $e) {
            return HttpResponse::fail($e->getMessage());
        }
    }
}
