<?php

declare(strict_types=1);

namespace app\Features\Documents\Presentation\API\Controllers;

use App\Features\Documents\Application\Contracts\DestroyDocumentContract;
use App\Features\Documents\Application\Contracts\ShowDocumentContract;
use App\Features\Documents\Application\Contracts\ShowDocumentFileContract;
use App\Features\Documents\Application\Contracts\StoreDocumentContract;
use App\Features\Documents\Presentation\API\Presenters\DestroyDocumentPresenter;
use App\Features\Documents\Presentation\API\Presenters\DownloadDocumnetFilePresenter;
use App\Features\Documents\Presentation\API\Presenters\ShowDocumentPresenter;
use App\Features\Documents\Presentation\API\Presenters\StoreDocumentPresenter;
use App\Features\Documents\Presentation\API\Presenters\ViewDocumentFilePresenter;
use App\Shared\Application\Contracts\Storage\StorageContract;
use App\Shared\Application\Contracts\Storage\StorageDirContract;
use App\Shared\Domain\Entities\Document\CategoryEntity;
use App\Shared\Domain\Entities\Document\DocumentEntity;
use App\Shared\Domain\Enums\Document\DocumentVisibilityType;
use App\Shared\Infrastructure\Storage\LaravelFile;
use App\Shared\Infrastructure\Utilities\CarbonDateUtility;
use App\Shared\Presentation\HTTP\Controller;
use Illuminate\Http\Request;

class DocumentContoller extends Controller
{
    public function __construct(
        private readonly StorageDirContract $storageDir,
        private readonly StorageContract $storage,
        private readonly StoreDocumentContract $storeDocumentUsecase,
        private readonly DestroyDocumentContract $destroyDocumentUsecase,
        private readonly ShowDocumentContract $showDocumentUsecase,
        private readonly ShowDocumentFileContract $showDocumentFileUsecase,
    ) {}
    public function index()
    {
        return __CLASS__ . "::" . __FUNCTION__;
    }
    public function show(string $documentId)
    {
        $presenter = new ShowDocumentPresenter();
        $this->showDocumentUsecase->execute((int)$documentId, $presenter);
        return $presenter->handle();
    }
    public function store(Request $request)
    {
        $documentEntitiy = $this->requestToDocumentEntity($request);
        $files = $this->requestToLaravelFile($request);
        $presenter = new StoreDocumentPresenter();
        $this->storeDocumentUsecase->execute($documentEntitiy, $files,  $presenter);
        return $presenter->handle();
    }
    public function update()
    {
        return __CLASS__ . "::" . __FUNCTION__;
    }
    public function destroy(string $documentId)
    {
        $presenter = new DestroyDocumentPresenter();
        $this->destroyDocumentUsecase->execute((int)$documentId, $presenter);
        return $presenter->handle();
    }
    public function viewFile(string $documentId, string $fileId)
    {
        $presenter = new ViewDocumentFilePresenter($this->storageDir, $this->storage,);
        $this->showDocumentFileUsecase->execute((int)$documentId, (int)$fileId, $presenter);
        return $presenter->handle();
    }
    public function downloadFile(string $documentId, string $fileId)
    {
        $presenter = new DownloadDocumnetFilePresenter($this->storageDir, $this->storage,);
        $this->showDocumentFileUsecase->execute((int)$documentId, (int)$fileId, $presenter);
        return $presenter->handle();
    }
    private function requestToDocumentEntity(Request $request): DocumentEntity
    {
        $categories = [];
        foreach ($request->categories as $categoryId) {
            $categories[] = new CategoryEntity(
                id: (int)$categoryId,
            );
        }
        $entitiy = new DocumentEntity(
            id: null,
            userId: (int)$request->user_id,
            name: $request->name,
            docNumber: $request->doc_number,
            docDate: CarbonDateUtility::from($request->doc_date),
            docExpireDate: CarbonDateUtility::from($request->doc_expire_date),
            visibility: DocumentVisibilityType::from($request->visibility),
            description: $request->description,
            categories: $categories,
        );
        return $entitiy;
    }
    private function requestToLaravelFile(Request $request, string $requestFileName = 'files'): array
    {
        $files = [];
        $requestFiles = $request->file($requestFileName);
        if ($requestFiles) {
            foreach ($requestFiles as $file) {
                $files[] = new LaravelFile(
                    originalName: $file->getClientOriginalName(),
                    originalExtension: $file->getClientOriginalExtension(),
                    mimeType: $file->getMimeType(),
                    tempPath: $file->path(),
                    content: $file->getContent(),
                );
            }
        };
        return $files;
    }
}
