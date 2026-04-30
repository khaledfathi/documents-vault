<?php

declare(strict_types=1);

namespace app\Features\Documents\Presentation\API\Controllers;

use App\Features\Documents\Application\Contracts\StoreDocumentContract;
use App\Features\Documents\Presentation\API\Presenters\StoreDocumentPresenter;
use App\Shared\Domain\Entities\Document\DocumentEntity;
use App\Shared\Domain\Entities\Document\FileEntity;
use App\Shared\Domain\Enums\Document\DocumentVisibilityType;
use App\Shared\Infrastructure\Storage\LaravelFile;
use App\Shared\Infrastructure\Utilities\CarbonDateUtility;
use App\Shared\Presentation\HTTP\Controller;
use Illuminate\Http\Request;

class DocumentContoller extends Controller
{
    public function __construct(
        private readonly StoreDocumentContract $storeDocumentUsecase,
    ) {}
    public function index()
    {
        return __CLASS__ . "::" . __FUNCTION__;
    }
    public function show()
    {
        return __CLASS__ . "::" . __FUNCTION__;
    }
    public function store(Request $request)
    {
        //test
        $documentEntitiy = $this->requestToDocumentEntity($request);
        $files = $this->requestToLaravelFile($request);
        //----
        $presenter = new StoreDocumentPresenter();
        $this->storeDocumentUsecase->execute($documentEntitiy, $files,  $presenter);
        return $presenter->handle();
    }
    public function update()
    {
        return __CLASS__ . "::" . __FUNCTION__;
    }
    public function destroy()
    {
        return __CLASS__ . "::" . __FUNCTION__;
    }
    private function requestToDocumentEntity(Request $request): DocumentEntity
    {
        $entitiy = new DocumentEntity(
            id: null,
            userId: (int)$request->user_id,
            name: $request->name,
            docNumber: $request->doc_number,
            docDate: CarbonDateUtility::from($request->doc_date),
            docExpireDate: CarbonDateUtility::from($request->doc_expire_date),
            visibility: DocumentVisibilityType::from($request->visibility),
            description: $request->description,
            categories: $request->categories,
        );
        return $entitiy;
    }
    private function requestToLaravelFile(Request $request, string $requestFileName = 'files'): array
    {
        $files = [];
        if ($requestFiles = $request->file($requestFileName)) {
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
