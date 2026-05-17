<?php

declare(strict_types=1);

namespace App\Features\Documents\Application\Outputs;

use App\Shared\Domain\Entities\Document\FileEntity;

interface ShowDocumentFileOutput
{
    public function onSuccess(FileEntity $fileEntity): void;
    public function onNotFound(): void;
    public function onUnauthorized(): void;
    public function onForbidden(): void;
    public function onFailure(string $error): void;
}
