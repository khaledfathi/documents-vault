<?php

declare(strict_types=1);

namespace App\Features\Users\Presentation\API\Presenters;

use App\Features\Users\Application\Outputs\DestroyTokenOutput;
use App\Shared\Infrastructure\Constants\Messages;
use App\Shared\Presentation\API\Traits\PresenterTrait;
use Illuminate\Http\Response;

final class DestroyTokenPresenter implements DestroyTokenOutput
{
    use PresenterTrait;

    public function __construct(
        private readonly string $userName,
    ) {}
    public function onSuccess(): void
    {
        $this->response = fn() => response()->json([
            'success' => true,
            'message' => "User '$this->userName' is loged out",
        ], Response::HTTP_OK);
    }
    public function onFailure(string $error): void
    {
        $data = ['success' => false, 'message' => Messages::SERVER_ERROR];
        $this->onDebug($data, $error);
        $this->response = fn() => response()->json($data, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    public function handle()
    {
        return ($this->response)();
    }
}
