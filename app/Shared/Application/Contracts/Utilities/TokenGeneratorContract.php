<?php
declare(strict_types=1);
namespace  App\Shared\Application\Contracts\Utilities;

interface TokenGeneratorContract
{
    public function generate(int $userId): string|null;
    public function destroyCurrentToken(): void;
}
