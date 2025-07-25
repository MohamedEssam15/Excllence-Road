<?php

namespace App\Utilities\CourseFilters;

use App\Utilities\FilterContract;
use App\Utilities\QueryFilter;

class Category extends QueryFilter implements FilterContract
{
    public function handle($value): void
    {
        $this->query->whereIn('category_id', $value);
    }
}
