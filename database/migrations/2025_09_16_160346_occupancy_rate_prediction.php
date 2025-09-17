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
        Schema::create('occupancy_prediction',function(Blueprint $table){
        $table->date('Date');
         $table->bigInteger('Year');
         $table->bigInteger('Month');
         $table->bigInteger('Active Tenants');
         $table->bigInteger('Vacant Units');
         $table->bigInteger('Occupancy rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
          Schema::dropIfExists('occupancy_prediction');
    }
};
