<?php

namespace App\Console\Commands;

use App\Enums\TransactionStatusEnum;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateStatusTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updateTransactionsStatus:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualiza as transactions agendadas para o dia';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        info("Cron Job running at ". now());

        DB::table('transactions')
            ->where('status', TransactionStatusEnum::SCHEDULED->value)
            ->whereDate('transaction_date', Carbon::today())
            ->update([
                'status' => TransactionStatusEnum::PAID->value,
                'updated_at' => now(),
                'description' => DB::raw("CONCAT(IFNULL(description, ''), '\n\nAgendado -> Pago automaticamente')")
            ]);
    }
}
