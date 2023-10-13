<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {



        if (Schema::hasTable('contractors')) {

            DB::statement("ALTER TABLE contractors ENGINE=InnoDB");
            DB::statement("ALTER TABLE contractors MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;");


            Schema::table('contractors', function (Blueprint $table) {

                $table->string('name',50)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();
                $table->string('address',100)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();
                $table->string('city',25)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();
                $table->string('state',2)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();
                $table->string('phone',11)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();
                $table->string('logo',50)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();

                $table->timestamps();
            });

        } else {
            Schema::create('contractors', function (Blueprint $table) {
                $table->id();

                $table->string('name',50)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);
                $table->string('address',100)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);
                $table->string('city',25)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);
                $table->string('state',2)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);
                $table->integer('zip')->nullable(false)->change();
                $table->string('phone',11)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);
                $table->string('logo',50)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);

                $table->timestamps();
            });
        }

        DB::statement("ALTER TABLE contractors
                              MODIFY COLUMN created_at
                              TIMESTAMP
                              NULL
                              DEFAULT current_timestamp()
                              ;
                    ");

        DB::statement("ALTER TABLE contractors
                              MODIFY COLUMN updated_at
                              TIMESTAMP
                              NULL -- the opposite is NOT NULL, which is implicitly set on timestamp columns
                              DEFAULT NULL -- no default value for newly-inserted rows
                              ON UPDATE CURRENT_TIMESTAMP;
                    ");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contractors');
    }
};


