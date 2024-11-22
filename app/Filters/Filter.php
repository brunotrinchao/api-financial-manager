<?php


namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class Filter
{
    protected $request;
    protected $builder;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $builder)
    {
        $this->builder = $builder;

        foreach ($this->filters() as $filter => $value) {
            if (method_exists($this, $filter) && $value !== null) {
                $this->$filter($value);
            }
        }

        return $this->builder;
    }

    protected function filters()
    {
        return $this->request->all();
    }
}
