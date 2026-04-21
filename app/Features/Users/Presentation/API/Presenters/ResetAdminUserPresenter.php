<?php

declare(strict_types=1);

namespace App\Features\Users\Presentation\API\Presenters;

use App\Features\Users\Application\Outputs\ResetAdminUserOutput;

final class ResetAdminUserPresenter implements ResetAdminUserOutput
{
    private string $message;
    public function onSuccess(): void
    {
        $this->message =
        "admin user has been reset successfuly\n[user: admin , email: admin@mail.com , password: admin]";
    }
    public function onFailure(string $error): void
    {
        $this->message = $error;
    }
    public function handle()
    {
        return $this->message;
    }
}
