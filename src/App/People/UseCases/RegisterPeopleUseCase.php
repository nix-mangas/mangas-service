<?php

namespace App\People\UseCases;

use App\People\Repositories\IPeopleRepository;
use Support\Http\HttpResponse;
use Illuminate\Http\JsonResponse;

class RegisterPeopleUseCase {

    public function __construct(private readonly IPeopleRepository $peopleRepository)
    {
    }

    public function execute(array $input): JsonResponse
    {
        try {
            $input['slug'] = $input['name'];

            $this->peopleRepository->create($input);

            return HttpResponse::ok([
                'status'  => 'success',
                'message' => 'People successfully saved!',
            ]);
        } catch (\Exception $e) {
            return HttpResponse::fail($e->getMessage());
        }
    }
}
