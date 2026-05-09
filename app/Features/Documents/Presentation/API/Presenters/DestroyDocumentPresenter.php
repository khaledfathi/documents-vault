<?php

declare(strict_types=1);

namespace App\Features\Documents\Presentation\API\Presenters;

use App\Features\Documents\Application\Outputs\DestroyDocumentOutput;
use App\Shared\Presentation\API\Traits\PresenterTrait;
use Illuminate\Http\Response;

final class DestroyDocumentPresenter implements DestroyDocumentOutput
{
    use PresenterTrait;
    public function onSuccess(): void
    {
        $this->response = fn() => response()->json([
            "success" => true,
            "message" => "Document Destroyed Successfuly",
        ], Response::HTTP_OK);
    }
    public function onNotFound(): void
    {
        $this->response = fn() => $this->notFoundResponse("Document is not found");
    }
    public function handle()
    {
        return ($this->response)();
    }
}
