<?php

declare(strict_types=1);

namespace App\Features\Documents\Presentation\API\Presenters;

use App\Features\Documents\Application\Outputs\PaginateDocumentOutput;
use App\Shared\Domain\ValuObjects\EntitiesWithPagination;
use App\Shared\Presentation\API\Traits\PresenterTrait;
use Illuminate\Http\Response;

final class PaginateDocumentPresenter implements PaginateDocumentOutput
{
    use PresenterTrait;
    /**
     * @inheritdoc
     */
    public function onSuccess(EntitiesWithPagination $entitiesWithPagination): void
    {
        $data = [
            'success' => true,
            'data' => [
                'pagination' => $entitiesWithPagination->pagination,
                'paginationControl' => [
                    'pageCount' => $entitiesWithPagination->pagination->getPageCounts(),
                    'currentPageURL' => $entitiesWithPagination->pagination->getCurrentPageURL(),
                    'nextPageURL' => $entitiesWithPagination->pagination->getNextPageURL(),
                    'previousePageURL' => $entitiesWithPagination->pagination->getPreviousPageURL(),
                    'links' => $entitiesWithPagination->pagination->getLinks(),
                ],
            ],
        ];
        $data['document'] = array_map(fn($entity) => $entity->toArray(), $entitiesWithPagination->entities);
        $this->presentFileLinks($data['document']);
        //
        $this->response = fn() => response()->json($data, Response::HTTP_OK);
    }
    public function handle()
    {
        return ($this->response)();
    }
    private function presentFileLinks(array &$documents): void
    {
        foreach ($documents as &$document) {
            foreach ($document['files'] as &$file) {
                $file =  array_merge($file, [
                    'links' => [
                        'view' => route('documents.files.view', ['document' => $file['documentId'], 'file' => $file['id']]),
                        'download' => route('documents.files.download', ['document' => $file['documentId'], 'file' => $file['id']]),
                    ]
                ]);
                unset($file);
            }
            unset($documents);
        }
    }
}
