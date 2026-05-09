<?php

declare(strict_types=1);

namespace App\Features\Documents\Presentation\API\Presenters;

use App\Features\Documents\Application\Outputs\StoreDocumentOutput;
use App\Shared\Domain\Entities\Document\DocumentEntity;
use App\Shared\Presentation\API\Traits\PresenterTrait;
use Illuminate\Http\Response;

final class StoreDocumentPresenter implements StoreDocumentOutput
{
    use PresenterTrait;
    public function onSuccess(DocumentEntity $documentEntity): void
    {
        $this->response = fn() => response()->json([
            'success' => true,
            'message' => 'document has been created successfully',
            'data' => $documentEntity->toArray(),
        ], Response::HTTP_CREATED);
    }
    public function handle()
    {
        return ($this->response)();
    }
}
