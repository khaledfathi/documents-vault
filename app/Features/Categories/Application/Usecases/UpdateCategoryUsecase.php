<?php

declare(strict_types=1);

namespace App\Features\Categories\Application\Usecases;

use App\Features\Categories\Application\Contracts\UpdateCategoryContract;
use App\Features\Categories\Application\Outputs\UpdateCategoryOutput;
use App\Shared\Application\Contracts\Security\CurrentUserContract;
use App\Shared\Domain\Entities\Document\CategoryEntity;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\CategoryRepository;
use Exception;

final class UpdateCategoryUsecase implements UpdateCategoryContract
{
    public function __construct(
        private readonly PermissionGateway $permissionGateway,
        private readonly CurrentUserContract $currentUser,
        private readonly CategoryRepository $categoryRepository,
    ) {}
    public function execute(CategoryEntity $categoryEntity, UpdateCategoryOutput $presenter): void
    {
        try {
            if (! $this->permissionGateway->can($this->currentUser->id(), PermissionType::EDIT_CATEGORY)) {
                $presenter->onUnauthorized();
                return;
            }
            $status = $this->categoryRepository->update($categoryEntity);
            $status ? $presenter->onSuccess($status) : $presenter->onNotFound();
        } catch (Exception $e) {
            $presenter->onFailure($e->getMessage());
        }
    }
}
