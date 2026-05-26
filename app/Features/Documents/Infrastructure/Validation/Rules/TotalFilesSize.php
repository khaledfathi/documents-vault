<?php

namespace App\Features\Documents\Infrastructure\Validation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;
use Illuminate\Translation\PotentiallyTranslatedString;

final readonly class TotalFilesSize implements ValidationRule
{
    /**
     * @param int $maxSize total files size in megabyte
     */
    public function __construct(
        private readonly int $totalMaxSize,
    ) {}
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Sum up the size of all uploaded files (in bytes)
        $totalSize = collect($value)->reduce(function ($carry, $file) {
            return $carry + ($file instanceof UploadedFile ? $file->getSize() : 0);
        }, 0);

        if ($totalSize > $this->totalMaxSize * 1024 * 1024) {
            $fail('The total size of all files must not exceed 150MB.');
        }
    }
}
