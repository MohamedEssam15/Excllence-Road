<?php

namespace App\Utilities\CourseFilters;

use App\Utilities\FilterContract;
use App\Utilities\QueryFilter;

class Teacher extends QueryFilter implements FilterContract
{
    public function handle($value): void
    {
        $this->query->whereIn('teacher_id', $value);
    }
}
