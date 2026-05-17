<?php

declare(strict_types=1);

namespace App\Features\Documents\Presentation\API\Presenters;

use App\Features\Documents\Application\Outputs\UpdateDocumentOutput;
use App\Shared\Presentation\API\Traits\PresenterTrait;
use Illuminate\Http\Response;

final class UpdateDocumentPresenter implements UpdateDocumentOutput
{
    use PresenterTrait;
    public function onSuccess(): void
    {
        dd('success');
        $this->response = fn() => response()->json([
            "success" => true,
            "message" => "Document Updated Successfuly",
        ], Response::HTTP_OK);
    }
    public function onNotFound(): void
    {
        dd('not found');
        $this->response = fn() => $this->notFoundResponse("Document is not found");
    }
    public function handle()
    {
        return ($this->response)();
    }
}
