<?php

namespace App\Utilities\QuestionFilters;

use App\Utilities\FilterContract;
use App\Utilities\QueryFilter;

class Category extends QueryFilter implements FilterContract
{
    public function handle($value): void
    {
        $this->query->where('category_id', (int) $value);
    }
}
