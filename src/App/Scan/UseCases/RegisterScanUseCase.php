<?php

namespace App\Scan\UseCases;

use App\Scan\Models\Scan;
use App\Scan\Repositories\IScanRepository;
use Support\Http\HttpResponse;
use Illuminate\Http\JsonResponse;

class RegisterScanUseCase {

    public function __construct(private readonly IScanRepository $scanRepository)
    {
    }

    public function execute(array $data): JsonResponse
    {
        try {
            $scan = new Scan();
            $scan->fill($data);
            $scan['slug'] = $scan['name'];

            $scanAlreadyExists = $this->scanRepository->exists($scan['slug']);
            if ($scanAlreadyExists) {
                return HttpResponse::conflict('Scan "'.$data['name'].'" already exists.');
            }

            $data['slug'] = $data['name'];

            $this->scanRepository->create($data);

            return HttpResponse::created('Saved scan successfully!');
        } catch (\Exception $e) {
            return HttpResponse::fail($e->getMessage());
        }
    }
}
