<?php

namespace App\Utilities;

class FilterBuilder
{
    protected $query;

    protected $filters;

    protected $namespace;

    public function __construct($query, $filters, $namespace)
    {
        $this->query = $query;
        $this->filters = $filters;
        $this->namespace = $namespace;
    }

    public function apply()
    {

        foreach ($this->filters as $name => $value) {
            $normalizedName = ucfirst($name);
            $class = $this->namespace."\\{$normalizedName}";

            if (! class_exists($class)) {
                continue;
            }

            if (isset($value)) {
                (new $class($this->query))->handle($value);
            } else {
                (new $class($this->query))->handle();
            }
        }

        return $this->query;
    }
}
