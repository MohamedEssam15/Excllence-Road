<?php

namespace App\Utilities\CourseFilters;

use App\Utilities\FilterContract;
use App\Utilities\QueryFilter;

class Search extends QueryFilter implements FilterContract
{
    public function handle($value): void
    {
        $this->query->whereHas('status', function ($query) {
            $query->where('name', 'active');
        })->where(function ($query) use ($value) {
            $query->WhereHas('translations', function ($query) use ($value) {
                $query->where('name', 'LIKE', '%' . $value . '%');
            });
        });
    }
}
