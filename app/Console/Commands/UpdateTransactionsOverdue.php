<?php

namespace App\Console\Commands;

use App\Enums\TransactionStatusEnum;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateTransactionsOverdue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updateTransactionsOverdue:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualiza as transactions pendentes com 1 ou mais dias de atraso';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        info("Cron Job running at ". now());

        DB::table('transactions')
            ->where('status', TransactionStatusEnum::PENDING->value)
            ->whereDate('transaction_date', '<',  Carbon::today())
            ->update([
                'status' => TransactionStatusEnum::OVERDUE->value,
                'updated_at' => now(),
                'description' => DB::raw("CONCAT(IFNULL(description, ''), '\n\nPendente -> Vencido automaticamente')")
            ]);
    }
}
