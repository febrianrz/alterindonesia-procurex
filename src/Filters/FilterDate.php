<?php

namespace Alterindonesia\Procurex\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Spatie\QueryBuilder\Filters\Filter;

class FilterDate implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        if (! is_array($value)) {
            return;
        }

        if (count($value) < 2) {
            return;
        }

        $query->where(fn (Builder $query) => $query
            ->where('created_at', '>=', Carbon::parse($value[0])->startOfDay())
            ->where('created_at', '<=', Carbon::parse($value[1])->endOfDay())
        );
    }
}
