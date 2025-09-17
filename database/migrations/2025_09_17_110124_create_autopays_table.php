<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('autopays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->boolean('enabled')->default(false);
            $table->enum('payment_method', ['gcash', 'card'])->nullable();
            $table->string('gcash_number')->nullable();
            $table->string('payment_token')->nullable(); // For card token from payment provider
            $table->tinyInteger('autopay_day')->nullable(); // day of month to charge (1-28)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('autopays');
    }
};
