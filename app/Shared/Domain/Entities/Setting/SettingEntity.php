<?php
declare (strict_types=1);
namespace App\Shared\Domain\Entities\Setting;

class SettingEntity {
    public function __construct(
        public ?int $id = null ,
        public ?int $userId = null ,
        public ?string $key = null ,
        public ?string $value = null,
    ) { }
}