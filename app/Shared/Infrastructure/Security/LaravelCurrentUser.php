<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Security;

use App\Shared\Application\Contracts\Security\CurrentUserContract;
use App\Shared\Domain\Entities\User\UserEntity;
use App\Shared\Domain\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

final readonly class LaravelCurrentUser implements CurrentUserContract
{
    public function __construct(
        private UserRepository $userRepository,
    ) {}
    public function id(): int
    {
        return  Auth::user()?->id ?? 0;
    }
    public function entity(): ?UserEntity
    {
        return $this->userRepository->show($this->id());
    }
}
