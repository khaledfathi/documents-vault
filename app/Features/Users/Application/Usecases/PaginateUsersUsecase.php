<?php

declare(strict_types=1);

namespace App\Features\Users\Application\Usecases;

use App\Features\Users\Application\Contracts\PaginateUsersContract;
use App\Features\Users\Application\Outputs\PaginateUsersOutput;
use App\Shared\Application\Contracts\Security\CurrentUserContract;
use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Domain\Gateways\PermissionGateway;
use App\Shared\Domain\Repositories\UserRepository;
use Exception;

final class PaginateUsersUsecase implements PaginateUsersContract
{

    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly PermissionGateway $permissionGateway,
        private readonly CurrentUserContract $currentUser,
    ) {}
    public function execute( PaginateUsersOutput $presenter, int $perPage = 10)
    {
        try {
            if (! $this->permissionGateway->can($this->currentUser->id(), PermissionType::VIEW_USER)) {
                return ;
            }
            $pagination = $this->userRepository->paginate($perPage);
            $presenter->onSuccess($pagination);
        } catch (Exception $e) {
            $presenter->onFailure($e->getMessage());
        }
    }
}

