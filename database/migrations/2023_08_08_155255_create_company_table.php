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



        if (Schema::hasTable('company')) {

            DB::statement("ALTER TABLE company ENGINE=InnoDB");
            DB::statement("ALTER TABLE company MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;");


            Schema::table('company', function (Blueprint $table) {

                $table->string('company_name')->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();
                $table->string('logo',50)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();
                $table->string('database_name')->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();
                $table->string('password')->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();
                $table->string('username')->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();


                $table->timestamp('updated_at')->nullable()->default(null);
            });

        } else {
            Schema::create('company', function (Blueprint $table) {
                $table->id();

                $table->string('company_name')->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);
                $table->string('logo',50)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);
                $table->string('database_name')->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);
                $table->string('password')->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);
                $table->string('username')->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);

                $table->timestamps();
            });
        }

        DB::statement("ALTER TABLE company
                              MODIFY COLUMN created_at
                              TIMESTAMP
                              NULL
                              DEFAULT current_timestamp()
                              ;
                    ");

        DB::statement("ALTER TABLE company
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
        Schema::dropIfExists('company');
    }
};


