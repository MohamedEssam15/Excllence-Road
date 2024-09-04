<?php

namespace App\Utilities\CourseFilters;

use App\Utilities\FilterContract;
use App\Utilities\QueryFilter;

class MinPrice extends QueryFilter implements FilterContract
{
    public function handle($value): void
    {
        $this->query->where('price','>=',$value);
    }
}
