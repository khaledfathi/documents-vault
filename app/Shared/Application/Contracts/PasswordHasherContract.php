<?php
declare (strict_types= 1);

namespace  App\Shared\Application\Contracts;

interface PasswordHasherContract {
    public function check (string $password, string $hashedPassword):bool;
}