<?php

declare(strict_types=1);

namespace App\Features\Categories\Application\Usecases;

use App\Features\Categories\Application\Contracts\PaginateCategoryContract;
use App\Features\Categories\Application\Outputs\PaginateCategoryOutput;
use App\Shared\Application\Contracts\Security\CurrentUserContract;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\CategoryRepository;
use Exception;

final readonly class PaginateCategoryUsecase implements PaginateCategoryContract
{
    public function __construct(
        private PermissionGateway $permissionGateway,
        private CurrentUserContract $currentUser,
        private CategoryRepository $categoryRepository,
    ) {}
    public function execute(PaginateCategoryOutput $presenter, int $perPage = 10): void
    {
        try {
            if (! $this->permissionGateway->can($this->currentUser->id(), PermissionType::VIEW_CATEGORY)) {
                $presenter->onUnauthorized();
                return;
            }
            $pagination = $this->categoryRepository->paginate($perPage);
            $presenter->onSuccess($pagination);
        } catch (Exception $e) {
            $presenter->onFailure($e->getMessage());
        }
    }
}
