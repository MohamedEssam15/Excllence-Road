<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use stdClass;

class PaginatedCollection extends ResourceCollection
{
    private $resourceClass;
    private $filters;

    public function __construct($resource, $resourceClass, $filters = new stdClass())
    {
        parent::__construct($resource);

        $this->resource = $this->collectResource($resource);
        $this->resourceClass = $resourceClass;
        $this->filters = $filters;
    }

    public function toArray($request)
    {
        if (! empty($request->all())) {
            $currentUrl = request()->url().'?'.http_build_query($request->except('page')).'&page='
                .$this->resource->currentPage();
            $nextUrl = request()->url().'?'.http_build_query($request->except('page')).'&page='
                .$this->resource->currentPage() + 1;
        } else {
            $currentUrl = request()->url().'?page='
                .$this->resource->currentPage();
            $nextUrl = request()->url().'?page='
                .$this->resource->currentPage() + 1;
        }

        return [
            'data' => $this->resourceClass::collection($this->collection),
            'perPage' => $this->resource->perPage(),
            'total' => $this->resource->total(),
            'currentUrl' => $currentUrl,
            'nextUrl' => $this->resource->currentPage() == $this->resource->lastPage() ? '' : $nextUrl,
            'lastPage' => $this->resource->lastPage(),
            'currentPage' => $this->resource->currentPage(),
            'filters' => $this->filters,
        ];
    }
}
