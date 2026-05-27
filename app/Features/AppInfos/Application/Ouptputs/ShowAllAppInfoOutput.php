<?php

namespace App\Features\AppInfos\Application\Ouptputs;

use App\Shared\Domain\Entities\AppInfo\AppInfoEntity;

interface ShowAllAppInfoOutput
{
    public function onSuccess(array $appInfoEntities): void;
    public function onFailure(string $error): void;
}
