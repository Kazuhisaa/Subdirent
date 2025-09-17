<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'method')) {
                $table->string('method')->nullable();
            }
            if (!Schema::hasColumn('payments', 'reference')) {
                $table->string('reference')->nullable();
            }
            if (!Schema::hasColumn('payments', 'for_month')) {
                $table->string('for_month')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'method')) {
                $table->dropColumn('method');
            }
            if (Schema::hasColumn('payments', 'reference')) {
                $table->dropColumn('reference');
            }
            if (Schema::hasColumn('payments', 'for_month')) {
                $table->dropColumn('for_month');
            }
        });
    }
};
