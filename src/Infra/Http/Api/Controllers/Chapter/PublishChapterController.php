<?php

namespace Infra\Http\Api\Controllers\Chapter;

use App\Chapter\UseCases\PublishChapterUseCase;
use Illuminate\Http\JsonResponse;
use Infra\Http\Api\Requests\PublishChapterRequest;
use Infra\Http\App\Controllers\Controller;

class PublishChapterController extends Controller {
    public function __construct(private readonly PublishChapterUseCase $useCase)
    {
    }

    /**
     * Handle the incoming request.
     *
     * @param PublishChapterRequest $request
     * @return JsonResponse
     */
    public function __invoke(PublishChapterRequest $request): JsonResponse
    {
        $request->validated();
        return $this->useCase->execute(
            array_merge(
                $request->all(),
                $request->file('pages'),
                [ 'cover' => $request->file('cover') ]
            )
        );
    }
}
