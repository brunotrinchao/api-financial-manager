<?php


namespace App\Filters;

class DashboardFilter extends Filter
{
    public function date($value)
    {
        $startDate = $value['startDate'] ?? null;
        $endDate = $value['endDate'] ?? null;

        if ($startDate && $endDate) {
            if($startDate === $endDate) {
                $this->builder->whereDate('transaction_date', '=', $startDate);
            }else {
                $this->builder->whereBetween('transaction_date', [$startDate, $endDate]);
            }
        } elseif ($startDate) {
            $this->builder->whereDate('transaction_date', '>=', $startDate);
        } elseif ($endDate) {
            $this->builder->whereDate('transaction_date', '<=', $endDate);
        }
    }

    public function orderBy($value)
    {
        $field = $value['field'] ?? 'id';
        $order = $value['order'] ?? 'ASC';
        $this->builder->orderBy($field, $order);
    }

}
