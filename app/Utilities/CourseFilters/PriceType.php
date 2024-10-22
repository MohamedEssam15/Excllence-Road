<?php

namespace App\Utilities\CourseFilters;

use App\Utilities\FilterContract;
use App\Utilities\QueryFilter;

class PriceType extends QueryFilter implements FilterContract
{
    public function handle($value): void
    {
        if ($value == 'free') {
            $this->query->where('price', 0);
        } else {
            $this->query->where('price', '>=', 1);
        }
    }
}
