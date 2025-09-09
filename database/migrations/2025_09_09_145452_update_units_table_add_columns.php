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
    Schema::table('units', function (Blueprint $table) {
        $table->string('title')->nullable()->after('id');
        $table->text('description')->nullable()->after('title');
        $table->integer('bedrooms')->nullable()->after('description');
        $table->integer('bathrooms')->nullable()->after('bedrooms');
        $table->integer('floor_area')->nullable()->after('bathrooms');
        $table->string('location')->nullable()->after('floor_area');
        $table->decimal('price', 10, 2)->nullable()->after('location');
        $table->boolean('is_rented')->default(false)->after('price');
    });
}

public function down(): void
{
    Schema::table('units', function (Blueprint $table) {
        $table->dropColumn([
            'title',
            'description',
            'bedrooms',
            'bathrooms',
            'floor_area',
            'location',
            'price',
            'is_rented',
        ]);
    });
}

};
