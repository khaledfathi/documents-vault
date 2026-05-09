<?php

declare(strict_types=1);

namespace App\Features\Documents\Presentation\API\Presenters;

use App\Features\Documents\Application\Outputs\ShowDocumentFileOutput;
use App\Shared\Application\Contracts\Storage\StorageContract;
use App\Shared\Application\Contracts\Storage\StorageDirContract;
use App\Shared\Domain\Entities\Document\FileEntity;
use App\Shared\Presentation\API\Traits\PresenterTrait;
use Illuminate\Http\Response;

final class ViewDocumentFilePresenter implements ShowDocumentFileOutput
{
    public function __construct(
        private readonly StorageDirContract $storageDir,
        private readonly StorageContract $storage,
    ) {}
    use PresenterTrait;
    public function onSuccess(FileEntity $fileEntity): void
    {
        $this->response = fn ()=> response()->file($this->storageDir->privatePath()->documents($fileEntity->documentEntity->id).$fileEntity->file);
    }
    public function onForbidden(): void
    {
        $this->response = fn() => response()->json([
            "success" => false,
            "message" => "unauthorized your not owned this file",
        ], Response::HTTP_FORBIDDEN);
    }
    public function onNotFound(): void
    {
        $this->notFoundResponse("file is not found");
    }
    public function handle()
    {
        return ($this->response)();
    }
}
