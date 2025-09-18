<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('autopays', function (Blueprint $table) {
            if (!Schema::hasColumn('autopays', 'method')) {
                $table->string('method')->nullable(); // gcash, card, etc.
            }
            if (!Schema::hasColumn('autopays', 'day_of_month')) {
                $table->integer('day_of_month')->nullable(); // 1-28
            }
            if (!Schema::hasColumn('autopays', 'active')) {
                $table->boolean('active')->default(true);
            }
        });
    }

    public function down()
    {
        Schema::table('autopays', function (Blueprint $table) {
            $table->dropColumn(['method', 'day_of_month', 'active']);
        });
    }
};
