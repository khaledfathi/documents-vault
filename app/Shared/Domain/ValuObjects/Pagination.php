<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValuObjects;

use Throwable;

final class Pagination
{
    private string $queires = "";
    public readonly ?int $perPage;
    public function __construct(
        ?int $perPage = null,
        public readonly ?int $currentPage = null,
        public readonly ?string $path = null,
        public readonly ?string $pageName = null,
        public readonly ?int $total = null,
    ) {
        //prevent zero and negtaive value
        $this->perPage = $perPage <= 0 ? $this->perPage = 10 : $perPage;
    }

    private function generatePageURL(int $pageQueryNumber): mixed
    {
        return "$this->path?$this->pageName=$pageQueryNumber";
    }
    public function getPageCounts(): int
    {
        return (int) ceil($this->total / $this->perPage);
    }
    public function getLinks(): array
    {
        $linksArray = [];
        for ($i = 0; $i < $this->getPageCounts(); $i++) {
            $linksArray[] = $this->generatePageURL($i + 1) . $this->queires;
        }
        return $linksArray;
    }
    public function getCurrentPageURL(): string
    {
        return $this->generatePageURL($this->currentPage) . $this->queires;
    }
    public function getNextPageURL(): ?string
    {
        $pageCounts = $this->getPageCounts();
        $pageNumber = $this->currentPage < $pageCounts ? $this->currentPage + 1 : $pageCounts;
        return $this->generatePageURL($pageNumber) . $this->queires;
    }
    public function getPreviousPageURL(): ?string
    {
        $pageNumber = $this->currentPage > 1 ? $this->currentPage - 1 : $this->currentPage;
        return $this->generatePageURL($pageNumber) . $this->queires;
    }
}
