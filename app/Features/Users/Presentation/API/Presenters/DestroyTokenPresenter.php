<?php
declare(strict_types=1);

namespace App\Features\Users\Presentation\API\Presenters;

use App\Features\Users\Application\Outputs\DestroyTokenOutput;
use App\Shared\Application\Enums\Messages;
use App\Shared\Application\Traits\PresenterTrait;
use Closure;

final class DestroyTokenPresenter implements DestroyTokenOutput
{
    use PresenterTrait;
    private Closure $response;
    public function __construct(
        private readonly string $userName,
    ) {}
    public function onSuccess(): void
    {
        $this->response = fn() => response()->json([
            'success' => true,
            'message' => "User '$this->userName' is loged out"
        ], 200);
    }
    public function onFailure(string $error): void
    {
        $data = ['success' => false, 'message' => Messages::SERVER_ERROR];
        $this->onDebug($data, $error);
        $this->response = fn() => response()->json($data, 500);
    }
    public function handle()
    {
        return ($this->response)();
    }
}

