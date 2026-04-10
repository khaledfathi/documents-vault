<?php

declare(strict_types=1);

namespace App\Features\Groups\Presentation\API\Presenters;

use App\Features\Groups\Application\Outputs\DestroyGroupOutput;
use App\Shared\Presentation\API\Traits\PresenterTrait;

final class DestroyGroupPresenter implements DestroyGroupOutput
{
    use PresenterTrait;

    public function onSuccess(bool $status): void {}
    public function handle()
    {
        return __CLASS__ . "::" . __FUNCTION__;
    }
}
