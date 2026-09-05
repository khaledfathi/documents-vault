<?php

declare(strict_types=1);

namespace App\Features\Users\Application\Outputs;

use App\Shared\Domain\Entities\User\UserEntity;

interface GenerateTokenOutput
{
    public function onSuccess(string $token , UserEntity $user): void;
    public function onMissingInput(string $message): void;
    public function onCredentialFailed(): void;
    public function onFailure(string $error): void;
}
