<?php
declare(strict_types=1);
namespace App\Shared\Infrastructure\Repositories\Eloquent\Traits;

use App\Shared\Domain\ValuObjects\Pagination;
use Illuminate\Pagination\LengthAwarePaginator;

trait PaginatorTrait{
    /**
    * map the laravel [LengthAwarePaginator] paginator to [Pagination] Object
    */
    public final function mapPaginator (LengthAwarePaginator $record , int $perPage):Pagination{
        return new Pagination(
            perPage  : $perPage,
            currentPage : $record->currentPage(),
            path : $record->path(),
            pageName : $record->getPageName(),
            total : $record->total(),
        );
    }
}
