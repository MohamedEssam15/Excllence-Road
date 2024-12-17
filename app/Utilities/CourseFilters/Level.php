<?php

namespace App\Utilities\CourseFilters;

use App\Utilities\FilterContract;
use App\Utilities\QueryFilter;

class Level extends QueryFilter implements FilterContract
{
    public function handle($value): void
    {
        $this->query->whereIn('level_id', $value);
    }
}
