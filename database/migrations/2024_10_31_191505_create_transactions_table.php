<?php

use App\Enums\TransactionStatusEnum;
use App\Enums\TransactionTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', [TransactionTypeEnum::INCOME->value, TransactionTypeEnum::EXPENSE->value])->comment('Tipo de trasação: income:Receita| expense:Despesa');
            $table->string('method')->comment('Metodo da trasação [account:Conta|credit_card:Crédito|debit_card:Debito|pix:Pix|cash:Dinheiro]');
            $table->decimal('amount', 15, 2);
            $table->text('description')->nullable()->comment('Descrção da trasação');
            $table->string('source_type')->nullable()->comment('account or credit_card');
            $table->integer('installment')->default(1)->comment('Parcela');

//            $table->foreignId('source_id')->comment('Related ID of account or credit_card');

            $table->string('frequency')->nullable()->comment('Frequencia: Mensal, Semanal, Anual');
            $table->integer('interval')->nullable()->comment('Intervalo de pagamento: A cada 2 (meses), 1 (mes)');
            $table->date('transaction_date')->comment('Data de pagamento da transação');
//            $table->date('start_date')->nullable()->comment('Data de inicio da trasação');
//            $table->date('end_date')->nullable()->comment('Data de termino da trasação');

            $table->enum('status', [
                TransactionStatusEnum::PENDING->value,
                TransactionStatusEnum::SCHEDULED->value,
                TransactionStatusEnum::PAID->value,
                TransactionStatusEnum::CANCELED->value,
                TransactionStatusEnum::OVERDUE->value,
            ])->default(TransactionStatusEnum::PENDING->value)->comment('Status da trasação');

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
