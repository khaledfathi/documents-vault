<?php

declare(strict_types=1);

namespace App\Features\AppInfos\Presentation\API\Presenter;

use App\Features\AppInfos\Application\Ouptputs\ShowAllAppInfoOutput;
use App\Shared\Presentation\API\Traits\PresenterTrait;
use Symfony\Component\HttpFoundation\Response;

final class ShowAllAppInfoPresenter implements ShowAllAppInfoOutput
{

    use PresenterTrait;
    public function onSuccess(array $appInfoEntities): void
    {
        $this->response = fn() => response()->json([
            "success" => true,
            "message" => "Ok",
            "data" => array_map(fn($entity) => $entity->toArray(), $appInfoEntities),
        ], Response::HTTP_OK);
    }
    public function handle()
    {
        return ($this->response)();
    }
}
