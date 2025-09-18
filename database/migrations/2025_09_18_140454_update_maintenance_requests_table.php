<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('maintenance_requests', function (Blueprint $table) {
            // idagdag ang mga missing columns
            if (!Schema::hasColumn('maintenance_requests', 'tenant_id')) {
                $table->foreignId('tenant_id')->nullable()->constrained()->nullOnDelete()->after('id');
            }

            if (!Schema::hasColumn('maintenance_requests', 'house')) {
                $table->string('house')->after('tenant_id');
            }

            if (!Schema::hasColumn('maintenance_requests', 'location')) {
                $table->string('location')->after('house');
            }

            if (!Schema::hasColumn('maintenance_requests', 'contact')) {
                $table->string('contact')->after('location');
            }

            if (!Schema::hasColumn('maintenance_requests', 'image')) {
                $table->string('image')->nullable()->after('contact');
            }

            if (!Schema::hasColumn('maintenance_requests', 'request')) {
                $table->text('request')->after('image');
            }

            if (!Schema::hasColumn('maintenance_requests', 'status')) {
                $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending')->after('request');
            }
        });
    }

    public function down(): void
    {
        Schema::table('maintenance_requests', function (Blueprint $table) {
            // optional: tanggalin kung gusto mo i-rollback
            $columns = ['tenant_id', 'house', 'location', 'contact', 'image', 'request', 'status'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('maintenance_requests', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
