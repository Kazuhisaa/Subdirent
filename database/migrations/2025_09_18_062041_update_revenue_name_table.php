<?php

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
        //
       
       Schema::table('revenue_predictions',function(Blueprint $table){
            
           $table->renameColumn('Year', 'year');
           $table->renameColumn('Month', 'month');
           $table->renameColumn('Date', 'date');
           $table->renameColumn('Historical Revenue', 'historical_revenue');
        });
   
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
