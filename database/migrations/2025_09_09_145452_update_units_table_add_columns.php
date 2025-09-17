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
                if (!Schema::hasColumn('units', 'description')) {
                    $table->text('description')->nullable();
                }
                if (!Schema::hasColumn('units', 'bedrooms')) {
                    $table->integer('bedrooms')->nullable();
                }
                if (!Schema::hasColumn('units', 'bathrooms')) {
                    $table->integer('bathrooms')->nullable();
                }
                if (!Schema::hasColumn('units', 'floor_area')) {
                    $table->integer('floor_area')->nullable();
                }
                if (!Schema::hasColumn('units', 'location')) {
                    $table->string('location')->nullable();
                }
                if (!Schema::hasColumn('units', 'price')) {
                    $table->decimal('price', 10, 2)->nullable();
                }
                if (!Schema::hasColumn('units', 'is_rented')) {
                    $table->boolean('is_rented')->default(false);
                }
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::table('units', function (Blueprint $table) {
                $table->dropColumn(['description', 'bedrooms', 'bathrooms', 'floor_area', 'location', 'price', 'is_rented']);
            });
        }
    };
