<?php

declare(strict_types=1);

namespace App\Features\Permissions\Application\Contracts;

use App\Features\Permissions\Application\Outputs\ListPermissionsOutput;

interface ListPermissionsContract
{
    public function execute(ListPermissionsOutput $presenter);
}
