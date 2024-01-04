<?php

namespace Alterindonesia\Procurex\Support;

use InvalidArgumentException;

class MediaNumber
{
    public static function generate(
        string|int $number,
        string $companyCode,
        string $moduleCode,
        string $categoryCode,
        string $subCategoryCode,
        ?string $year = null,
        ?string $tz = 'Asia/Jakarta',
    ): string {
        if (trim($companyCode) === '' || ! preg_match('/^[A-Za-z]/', $companyCode)) {
            throw new InvalidArgumentException('Company code must not be empty and start with a single alphabetic character.');
        }

        if (trim($moduleCode) === '') {
            throw new InvalidArgumentException('Module code must not be empty.');
        }

        if (trim($categoryCode) === '') {
            throw new InvalidArgumentException('Category code must not be empty.');
        }

        if (trim($subCategoryCode) === '') {
            throw new InvalidArgumentException('Sub-category code must not be empty.');
        }

        $companyCode = $companyCode[0];
        $year = $year === null || trim($year) === ''
            ? now($tz)->format('Y')
            : $year;

        return "$number/PR/$companyCode/$moduleCode/$categoryCode/$subCategoryCode/$year";
    }
}