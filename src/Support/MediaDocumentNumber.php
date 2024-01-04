<?php

namespace Alterindonesia\Procurex\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use RuntimeException;

class MediaDocumentNumber
{
    /**
     * @param  Builder|string|class-string<Model::class> $table
     */
    public static function generate(
        Builder|string $table,
        string $column,
        string $companyCode,
        string $moduleCode,
        string $categoryCode,
        string $subCategoryCode,
        ?string $year = null,
        ?string $tz = 'Asia/Jakarta',
        int $maxAttempts = 1000,
    ): string {
        $ending = static::format('', $companyCode, $moduleCode, $categoryCode, $subCategoryCode, $year, $tz);
        $query = $table instanceof Builder ? $table : DB::table(static::resolveTableName($table));
        $maxNumber = $query->where($column, 'like', "%$ending%")->max($column);
        $maxNumber = $maxNumber === null ? 0 : (int) substr($maxNumber, 0, -strlen($ending));

        for ($i = 0; $i < $maxAttempts; $i++) {
            $number = ($maxNumber + $i).$ending;

            if ($query->where($column, $number)->doesntExist()) {
                return $number;
            }
        }

        throw new RuntimeException("Failed to generate unique document number after $maxAttempts attempts");
    }

    public static function format(
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


    /**
     * Resolves the name of the table from the given string.
     *
     * @param  string  $table
     * @return string
     *
     * @see \Illuminate\Validation\Rules\DatabaseRule
     */
    protected static function resolveTableName($table)
    {
        if (! str_contains($table, '\\') || ! class_exists($table)) {
            return $table;
        }

        if (is_subclass_of($table, Model::class)) {
            $model = new $table;

            if (str_contains($model->getTable(), '.')) {
                return $table;
            }

            return implode('.', array_map(function (string $part) {
                return trim($part, '.');
            }, array_filter([$model->getConnectionName(), $model->getTable()])));
        }

        return $table;
    }
}