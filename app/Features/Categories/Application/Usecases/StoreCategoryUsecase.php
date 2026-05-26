<?php

declare(strict_types=1);

namespace App\Features\Categories\Application\Usecases;

use App\Features\Categories\Application\Contracts\StoreCategoryContract;
use App\Features\Categories\Application\Outputs\StoreCategoryOutput;
use App\Shared\Application\Contracts\Security\CurrentUserContract;
use App\Shared\Domain\Entities\Document\CategoryEntity;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\CategoryRepository;
use Exception;

final readonly class StoreCategoryUsecase implements StoreCategoryContract
{
    public function __construct(
        private PermissionGateway $permissionGateway,
        private CurrentUserContract $currentUser,
        private CategoryRepository $categoryRepository,
    ) {}
    public function execute(CategoryEntity $categoryEntity, StoreCategoryOutput $presenter): void
    {
        try {
            if (! $this->permissionGateway->can($this->currentUser->id(), PermissionType::CREATE_CATEGORY)) {
                $presenter->onUnauthorized();
                return;
            }
            $categoryEntity = $this->categoryRepository->store($categoryEntity);
            $presenter->onSuccess($categoryEntity);
        } catch (Exception $e) {
            $presenter->onFailure($e->getMessage());
        }
    }
}
