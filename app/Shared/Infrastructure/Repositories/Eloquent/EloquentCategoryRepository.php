<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Repositories\Eloquent;

use App\Shared\Domain\Entities\Document\CategoryEntity;
use App\Shared\Domain\Repositories\CategoryRepository;
use App\Shared\Domain\ValuObjects\EntitiesWithPagination;
use App\Shared\Infrastructure\Models\Category;
use App\Shared\Infrastructure\Repositories\Eloquent\Traits\PaginatorTrait;

final class EloquentCategoryRepository implements CategoryRepository
{
    use PaginatorTrait;
    public function paginate(int $perPage = 10): EntitiesWithPagination
    {
        $records = Category::paginate($perPage);
        //entities
        $categoryEntities = [];
        foreach ($records as $record) {
            $categoryEntities[] = new CategoryEntity(
                id: $record->id,
                name: $record->name,
                description: $record->description,
            );
        }
        //pagination
        $pagination = $this->mapPaginator($records, $perPage);
        //
        return new EntitiesWithPagination($pagination, $categoryEntities);
    }
    public function show(int $categoryId): ?CategoryEntity
    {
        $record = Category::find($categoryId);
        if (! $record) return null;
        return new CategoryEntity(
            id: $record->id,
            name: $record->name,
            description: $record->description,
        );
    }
    public function store(CategoryEntity $categoryEntity): CategoryEntity
    {
        $record = Category::create([
            'name' => $categoryEntity->name,
            'description' => $categoryEntity->description,
        ]);
        $categoryEntity->id = $record->id;
        return $categoryEntity;
    }
    public function update(CategoryEntity $categoryEntity): bool
    {
        $record = Category::find($categoryEntity->id);
        if (! $record) return false;
        $record->update([
            'name' => $categoryEntity->name,
            'description' => $categoryEntity->description,
        ]);
        return true;
    }
    public function destroy(int $categoryId): bool
    {
        $record = Category::find($categoryId);
        if (!$record) return false;
        $record->delete();
        return true;
    }
}
