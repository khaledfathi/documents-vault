<?php

declare(strict_types=1);

namespace App\Features\Documents\Application\DTOs;

use App\Shared\Application\Contracts\Storage\FileContract;

class UpdatedFileDTO
{
    /**
     * @param array<FileContract>
     * @param array<int>
     */
    public function __construct(
        public ?array $files = null,
        public ?array $deletedFileIds = null,
    ) {}
}
