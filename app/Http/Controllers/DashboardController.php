<?php

namespace App\Http\Controllers;

use App\Filters\DashboardFilter;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Illuminate\Http\Response;

class DashboardController extends Controller
{
    public function indicators(DashboardFilter $filters)
    {
        $transactions = Transaction::filter($filters)->get();

        $groupedTransactions = $transactions->groupBy('status');

        $response = [
                'paid' => [
                    'amount' => $groupedTransactions->get('paid', collect())->sum('amount'),
                    'total' => $groupedTransactions->get('paid', collect())->count(),
                ],
                'scheduled' => [
                    'amount' => $groupedTransactions->get('scheduled', collect())->sum('amount'),
                    'total' => $groupedTransactions->get('scheduled', collect())->count(),
                ],
                'pending' => [
                    'amount' => $groupedTransactions->get('pending', collect())->sum('amount'),
                    'total' => $groupedTransactions->get('pending', collect())->count(),
                ],
                'overdue' => [
                    'amount' => $groupedTransactions->get('overdue', collect())->sum('amount'),
                    'total' => $groupedTransactions->get('overdue', collect())->count(),
                ]
            ];

        return response($response)->setStatusCode(Response::HTTP_OK);
    }

    public function perCategory(DashboardFilter $filters)
    {
        $response = [];
        $transactions = Transaction::with('category')
            ->whereHas('category')
            ->filter($filters)
            ->get();

        if($transactions) {

            $totalAmount = $transactions->sum('amount');

            $groupedTransactions = $transactions->groupBy('category_id');

            $response = $groupedTransactions->map(function ($group) use ($totalAmount) {
                $categoryName = $group->first()->category->name ?? 'Unknown';
                $totalAmountPerCategory = $group->sum('amount');
                $percentage = $totalAmount > 0
                    ? round(($totalAmountPerCategory / $totalAmount) * 100, 2)
                    : 0;

                return [
                    'name' => $categoryName,
                    'amount' => $totalAmountPerCategory,
                    'percentage' => $percentage,
                ];
            })->values(); // Garantir que o resultado seja um array indexado.
        }

        return response()->json($response, Response::HTTP_OK);
    }

    public function perCrediCards(DashboardFilter $filters)
    {
        $response = [];
        $transactions = Transaction::with(['sourceCreditCard', 'sourceCreditCard.issuer'])
            ->whereHas('sourceCreditCard')
            ->filter($filters)
            ->get();

        if($transactions) {

            $totalAmount = $transactions->sum('amount');

            $groupedTransactions = $transactions->groupBy('source_id');

            $response = $groupedTransactions->map(function ($group) use ($totalAmount) {
                $carediCardName = $group->first()->sourceCreditCard->name ? $group->first()->sourceCreditCard->name . ' - ' . $group->first()->sourceCreditCard->issuer->name : 'Unknown';
                $totalAmountPerCreditCard = $group->sum('amount');
                $percentage = $totalAmount > 0
                    ? round(($totalAmountPerCreditCard / $totalAmount) * 100, 2)
                    : 0;

                return [
                    'name' => $carediCardName,
                    'amount' => $totalAmountPerCreditCard,
                    'percentage' => $percentage,
                ];
            })->values(); // Garantir que o resultado seja um array indexado.
        }

        return response()->json($response, Response::HTTP_OK);
    }
}
