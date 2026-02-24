<?php

declare(strict_types=1);

namespace  App\Shared\Infrastructure\Utilities;

use App\Shared\Domain\Contracts\DateProviderContract;
use Carbon\Carbon;
use Exception;

final class CarbonDateUtility implements DateProviderContract
{
    private int $day;
    private int $month;
    private int $year;
    private  $carbonDate;
    private function __construct(
        private ?string $date = null,
    ) {
        try {
            $this->carbonDate = Carbon::createFromFormat('Y-m-d', $date);
            $this->day = $this->carbonDate->day;
            $this->month = $this->carbonDate->month;
            $this->year = $this->carbonDate->year;
        } catch (Exception) {
            throw new Exception('Invalid Date format , it should be in format of yyyy-mm-dd ');
        }
    }
    public static function from(string $date): DateProviderContract
    {
        return new self($date);
    }
    public static function genereateDate (int $day , int $month , int $year):DateProviderContract{
        return new self(Carbon::create($year, $month , $day)->toDateString());
    }
    public static function now(): DateProviderContract
    {
        return new self(Carbon::now()->toDateString());
    }
    public function toDateString(): string
    {
        return $this->date;
    }
    public function year(): int
    {
        return $this->year;
    }
    public function month(): int
    {
        return $this->month;
    }
    public function day(): int
    {
        return $this->day;
    }
    public function isPast(): bool
    {
        return $this->carbonDate->isAfter(Carbon::now());
    }
    public function monthsUnitlNow(): int
    {
        return (int)ceil(
            $this->carbonDate->diffInMonths(Carbon::now()->toDateString())
        );
    }
    public function monthsUnitl(DateProviderContract $date): int
    {
        return (int)($this->carbonDate->diffInMonths(self::from($date->toDateString())->toDateString()));
    }
    public function yearsUntilNow(): int
    {
        return (int) $this->carbonDate->diffInYears(Carbon::now()->toDateString()) ;
    }
    public function yearsUntil(DateProviderContract $date): int
    {

        return (int)($this->carbonDate->diffInYears(self::from($date->toDateString())->toDateString()));
    }
}