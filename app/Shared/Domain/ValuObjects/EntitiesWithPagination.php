<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValuObjects;

/**
 * @template T
 */
final class EntitiesWithPagination
{
    /**
     *
     * @param ?Pagination $pagination
     * @param ?array<T> $entities
     */
    public function __construct(
        public ?Pagination $pagination = null,
        public ?array $entities = null
    ) {}
}
