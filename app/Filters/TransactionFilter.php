<?php


namespace App\Filters;

class TransactionFilter extends Filter
{
    public function type($value)
    {
        $this->builder->where('type', $value);
    }

    public function category($value)
    {
        $this->builder->where('category_id', $value);
    }


    public function method($value)
    {
        $this->builder->where('method', $value);
    }

    public function minAmount($value)
    {
        $this->builder->where('amount', '>=', $value);
    }

    public function maxAmount($value)
    {
        $this->builder->where('amount', '<=', $value);
    }

    public function date($value)
    {
        $startDate = $value['startDate'] ?? null;
        $endDate = $value['endDate'] ?? null;

        if ($startDate && $endDate) {
            $this->builder->whereBetween('transaction_date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $this->builder->whereDate('transaction_date', '>=', $startDate);
        } elseif ($endDate) {
            $this->builder->whereDate('transaction_date', '<=', $endDate);
        }
    }

    public function status($value)
    {
        $this->builder->where('status', $value);
    }
}
