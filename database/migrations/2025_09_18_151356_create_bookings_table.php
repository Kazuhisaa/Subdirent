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
        Schema::create('bookings', function (Blueprint $table) {
        $table->id();
        $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
        $table->string('title');
        $table->decimal('price', 12, 2);
        $table->string('full_name');
        $table->string('email');
        $table->string('contact_number');
        $table->date('date');
        $table->string('time_slot');
        $table->text('notes')->nullable();
        $table->string('status')->default('pending');
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
