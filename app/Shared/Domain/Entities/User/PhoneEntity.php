<?php
declare (strict_types=1);
namespace App\Shared\Domain\Entities\User;

class PhoneEntity {
    public function __construct(
        public ?int $id,
        public ?string $phone,
    ) { }
}