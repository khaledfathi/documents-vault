<?php
declare (strict_types=1);
namespace App\Features\Users\Application\Contracts;

use App\Features\Users\Application\Outputs\ResetAdminUserOutput;

interface ResetAdminUserContract {
    public function execute (ResetAdminUserOutput $presenter);
}
