<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'due_date')) {
                $table->date('due_date')->nullable()->after('amount');
            }
            if (!Schema::hasColumn('payments', 'paid_at')) {
                $table->date('paid_at')->nullable()->after('due_date');
            }
            if (!Schema::hasColumn('payments', 'status')) {
                $table->enum('status', ['Pending', 'Paid', 'Overdue'])->default('Pending')->after('paid_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'due_date')) {
                $table->dropColumn('due_date');
            }
            if (Schema::hasColumn('payments', 'paid_at')) {
                $table->dropColumn('paid_at');
            }
            if (Schema::hasColumn('payments', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
