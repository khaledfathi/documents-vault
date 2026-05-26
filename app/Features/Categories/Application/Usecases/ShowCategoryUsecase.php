<?php

declare(strict_types=1);

namespace App\Features\Categories\Application\Usecases;

use App\Features\Categories\Application\Contracts\ShowCategoryContract;
use App\Features\Categories\Application\Outputs\ShowCategoryOutput;
use App\Shared\Application\Contracts\Security\CurrentUserContract;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\CategoryRepository;
use Exception;

final class ShowCategoryUsecase implements ShowCategoryContract
{
    public function __construct(
        private PermissionGateway $permissionGateway,
        private CurrentUserContract $currentUser,
        private CategoryRepository $categoryRepository,
    ) {}
    public function execute(int $categoryId, ShowCategoryOutput $presenter): void
    {
        try {
            if (! $this->permissionGateway->can($this->currentUser->id(), PermissionType::VIEW_CATEGORY)) {
                $presenter->onUnauthorized();
                return;
            }
            $categoryEntity = $this->categoryRepository->show($categoryId);
            if (! $categoryEntity) {
                $presenter->onNotFound();
                return;
            }
            $presenter->onSuccess($categoryEntity);
        } catch (Exception $e) {
            $presenter->onFailure($e->getMessage());
        }
    }
}
