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
                $table->string('method')->nullable()->after('status');
            }
            if (!Schema::hasColumn('payments', 'reference')) {
                $table->string('reference')->nullable()->after('method');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['method', 'reference']);
        });
    }
};
