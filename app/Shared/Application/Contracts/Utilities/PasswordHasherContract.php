<?php

declare(strict_types=1);

namespace App\Shared\Application\Contracts\Utilities;

interface PasswordHasherContract
{
    public function check(string $password, string $hashedPassword): bool;
    public function hash($password): string;
}
