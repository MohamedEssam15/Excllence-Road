<?php

namespace App\Utilities\QuestionFilters;

use App\Utilities\FilterContract;
use App\Utilities\QueryFilter;

class Teacher extends QueryFilter implements FilterContract
{
    public function handle($value): void
    {
        $this->query->whereIn('user_id', $value);
    }
}
