<?php

declare(strict_types=1);

namespace App\Features\Categories\Application\Usecases;

use App\Features\Categories\Application\Contracts\DestroyCategoryContract;
use App\Features\Categories\Application\Outputs\DestroyCategoryOutput;
use App\Shared\Application\Contracts\Security\CurrentUserContract;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\CategoryRepository;
use Exception;

final readonly class DestroyCategoryUsecase implements DestroyCategoryContract
{
    public function __construct(
        private PermissionGateway $permissionGateway,
        private CurrentUserContract $currentUser,
        private CategoryRepository $categoryRepository,
    ) {}
    public function execute(int $categoryId, DestroyCategoryOutput $presenter): void
    {
        try {
            if (! $this->permissionGateway->can($this->currentUser->id(), PermissionType::DELETE_CATEGORY)) {
                $presenter->onUnauthorized();
                return;
            }
            $record = $this->categoryRepository->show($categoryId);
            if (! $record) {
                $presenter->onNotFound();
                return;
            }
            if ($record->isDefaultGroup) {
                $presenter->onDefaultGroup();
                return;
            }
            $this->moveDocumentsToDefaultCategory($categoryId);
            $this->categoryRepository->destroy($categoryId);
            $presenter->onSuccess();
        } catch (Exception $e) {
            $presenter->onFailure($e->getMessage());
        }
    }
    /**
     * move documents releated this [$categoryId] to
     * @param int $categoryId
     */
    private function moveDocumentsToDefaultCategory(int $categoryId): void
    {
        $x= $this->categoryRepository->updateDocumentWithCategoryIdToDefaultCategory($categoryId);
    }
}
