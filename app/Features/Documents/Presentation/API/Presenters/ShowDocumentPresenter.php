<?php

declare(strict_types=1);

namespace App\Features\Documents\Presentation\API\Presenters;

use App\Features\Documents\Application\Outputs\ShowDocumentOutput;
use App\Shared\Domain\Entities\Document\DocumentEntity;
use App\Shared\Presentation\API\Traits\PresenterTrait;
use Illuminate\Http\Response;

final class ShowDocumentPresenter implements ShowDocumentOutput
{
    use PresenterTrait;
    public function onSuccess(DocumentEntity $documentEntitiy): void
    {
        $data = $documentEntitiy->toArray();
        //handle file url (view/download links);
        $data['files'] = array_map(fn($file) => $this->presentFileLinks($file), $data['files']);
        //
        $this->response = fn() => response()->json([
            "success" => true,
            "message" => "Ok",
            'data' => $data,
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
    private function presentFileLinks(array $file): array
    {
        return array_merge($file, [
            'links' => [
                'view' => route('documents.files.view', ['document' => $file['documentId'], 'file' => $file['id']]),
                'download' => route('documents.files.download', ['document' => $file['documentId'], 'file' => $file['id']]),
            ]
        ]);
    }
}
