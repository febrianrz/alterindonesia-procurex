<?php

namespace Alterindonesia\Procurex\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Spatie\QueryBuilder\Filters\Filter;

class FilterDate implements Filter
{
    public function __invoke(Builder $query, $value, string $property): void
    {
        if (! is_array($value) && ! is_string($value)) {
            return;
        }

        if (is_array($value) && count($value) < 2) {
            return;
        }

        if (is_string($value)) {
            $startDate = $value;
            $endDate = $value;
        } else {
            [$startDate, $endDate] = $value;
        }

        Log::info('Filter Date by [' . $startDate . '] - [' . $endDate.']');
        $startDate = rescue(static fn () => Carbon::parse($startDate)->startOfDay(), report: false);
        $endDate = rescue(static fn () => Carbon::parse($endDate)->endOfDay(), report: false);

        if (! $startDate || ! $endDate) {
            return;
        }

        $query->where(fn (Builder $query) => $query
            ->where($property, '>=', $startDate)
            ->where($property, '<=', $endDate)
        );
    }
}
