<?php
declare (strict_types=1);

namespace App\Shared\Domain\Contracts;

interface  DateProviderContract {
    public static function from (string $date):DateProviderContract;
    public function toDateString(): string;
    public function year():int;
    public function month():int;
    public function day():int;
    public function isPast():bool;
    public static function now(): DateProviderContract;
    public function monthsUnitlNow ():int;
    public function monthsUnitl (DateProviderContract $date):int;
    public function yearsUntilNow():int;
    public function yearsUntil(DateProviderContract $date):int;
    public static function genereateDate (int $day , int $month , int $year):DateProviderContract;
}