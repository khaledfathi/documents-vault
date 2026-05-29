<?php

declare(strict_types=1);

namespace App\Shared\Domain\Entities\AppInfo;

final class AppInfoEntity
{
    public function __construct(
        public ?string $key = null,
        public ?string $value = null,
    ) {}
    public function toArray()
    {
        return [
            'key' => $this->key,
            'value' => $this->value
        ];
    }
}
