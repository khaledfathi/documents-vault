<?php

declare(strict_types=1);

namespace App\Features\Users\Application\Contracts;

use App\Features\Users\Application\Outputs\GenerateTokenOutput;

interface GenerateTokenContract
{
    public function execute(
        string $email,
        string $password,
        GenerateTokenOutput $presenter,
        string $tokenName = "token"
    ): void;
}
