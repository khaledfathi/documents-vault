<?php
declare(strict_types=1);
namespace App\Shared\Domain\Gateways; 

use App\Shared\Domain\Enums\User\PermissionType;

interface PermissionGateway {
    public function can (int $userId , PermissionType $ability):bool;
}