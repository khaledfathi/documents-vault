<?php

declare(strict_types=1);

namespace  App\Shared\Infrastructure\Repositories\Eloquent;

use App\Shared\Domain\Entities\AppInfo\AppInfoEntity;
use App\Shared\Domain\Repositories\AppInfoRepository;
use App\Shared\Infrastructure\Models\AppInfo;

final class EloquentAppInfoRepository implements AppInfoRepository
{
    /**
     * @inheritdoc
     */
    public function index(): array
    {
        $entities = [];
        $records = AppInfo::all();
        foreach ($records as $record) {
            $entities[] = new AppInfoEntity(
                key: $record->key,
                value: $record->value,
            );
        }
        return $entities;
    }
}
