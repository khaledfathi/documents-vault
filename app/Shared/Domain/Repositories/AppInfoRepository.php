<?php

declare(strict_types=1);

namespace App\Shared\Domain\Repositories;

use App\Shared\Domain\Entities\AppInfo\AppInfoEntity;

interface AppInfoRepository
{
    /**
     * @return array<AppInfoEntity>
     */
    public function index(): array;
}
