<?php
declare(strict_types=1);

namespace App\Features\Users\Application\Usecases;

use App\Features\Users\Application\Contracts\DestroyTokenContract;
use App\Features\Users\Application\Outputs\DestroyTokenOutput;
use App\Shared\Application\Contracts\Utilities\TokenGeneratorContract;
use Exception;

final class DestroyTokenUsecase implements DestroyTokenContract
{

    public function __construct(
        private TokenGeneratorContract $tokenGenerator
    ) {
    }
    public function execute(DestroyTokenOutput $presenter): void
    {
        try {
           $this->tokenGenerator->destroyCurrentToken();
           $presenter->onSuccess();
        } catch (Exception $e) {
           $presenter->onFailure($e->getMessage());
        }
    }
}
