<?php

declare(strict_types=1);

namespace App\Features\Users\Application\Contracts;

use App\Features\Users\Application\Outputs\DestroyTokenOutput;

interface DestroyTokenContract
{
    public function execute(DestroyTokenOutput $presenter): void;
}
