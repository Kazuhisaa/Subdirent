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
        Schema::create('applications', function (Blueprint $table) {
        $table->id();
        $table->string('title'); 
        $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
        $table->decimal('price', 12, 2);
        $table->string('surname');
        $table->string('first_name');
        $table->string('middle_name')->nullable();
        $table->string('email');
        $table->string('contact_number');
        $table->string('id_file')->nullable();
        $table->decimal('salary', 12, 2)->nullable();
        $table->date('lease_start')->nullable();
        $table->integer('lease_duration')->nullable(); 
        $table->string('status')->default('pending');
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
