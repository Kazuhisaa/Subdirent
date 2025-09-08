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
        Schema::create('bookings', function (Blueprint $table) {
          $table->id();
            $table->unsignedBigInteger('property_id');
            $table->date('booking_date');
            $table->time('booking_time');
            $table->string('full_name');
            $table->string('email');
            $table->string('phone_number');
            $table->string('visit_type')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['property_id', 'booking_date', 'booking_time']);
            $table->foreign('property_id')->references('id')->on('units')->onDelete('cascade');
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
