<?php

declare(strict_types=1);

namespace App\Shared\Presentation\API\Traits;

use App\Shared\Infrastructure\Constants\Messages;
use Closure;
use Illuminate\Http\Response;

trait PresenterTrait
{
    protected Closure $response;

    public function onUnauthorized(): void
    {
        $this->response = fn() => response()->json([
            "success" => false,
            "message" => Messages::UNAUTHORIZED,
        ], Response::HTTP_FORBIDDEN);
    }
    public function onFailure(string $error): void
    {
        $data = [
            "success" => false,
            "message" => Messages::SERVER_ERROR,
        ];
        $this->onDebug($data, $error);
        $this->response = fn() => response()->json($data, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    /**
     * append the exception message to [$data]
     * @param array $data
     * @param string $error
     * @return void
     */
    public final function onDebug(array &$data, string $error): void
    {
        if (getenv('APP_DEBUG')) {
            $data['error'] = $error;
        }
    }
    public final function notFoundResponse(string $message = "Record is not found")
    {
        return response()->json([
            "success" => false,
            "message" => $message,
        ], Response::HTTP_NOT_FOUND);
    }
}
