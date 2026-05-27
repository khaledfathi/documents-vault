<?php

namespace App\Features\AppInfos\Application\Contracts;

use App\Features\AppInfos\Application\Ouptputs\ShowAllAppInfoOutput;

interface ShowAllAppInfoContract
{
    public function execute(ShowAllAppInfoOutput  $presenter): void;
}
