<?php
declare (strict_types=1);
namespace App\Shared\Domain\Entities\Setting;

class SettingEntity {
    public function __construct(
        public ?int $id ,
        public ?string $key,
        public ?string $value,
    ) { }
}