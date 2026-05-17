<?php

declare(strict_types=1);

namespace App\Shared\Domain\Entities\User;

final class PhoneEntity
{
    public function __construct(
        public ?int $id = null,
        public ?int $userId = null,
        public ?string $phone = null,
    ) {}
}
