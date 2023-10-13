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


        if (Schema::hasTable('users')) {

            DB::statement("ALTER TABLE users ENGINE=InnoDB");
            DB::statement("ALTER TABLE users MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;");
            DB::statement("ALTER TABLE users MODIFY fname VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL;");
            DB::statement("ALTER TABLE users MODIFY lname VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL;");
            DB::statement("ALTER TABLE users MODIFY title VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL;");
            DB::statement("ALTER TABLE users MODIFY email VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL;");

            Schema::table('users', function (Blueprint $table) {

                if (!Schema::hasColumn('users', 'name')) {
                    $table->string('name');
                }


                if (!Schema::hasColumn('users', 'email_verified_at')) {
                    $table->timestamp('email_verified_at')->nullable();
                }


                if (!Schema::hasColumn('users', 'remember_token')) {
                    $table->rememberToken();
                }

                if ((!Schema::hasColumn('users', 'created_at')) && (!Schema::hasColumn('users', 'updated_at'))) {
                    $table->timestamps();
                }

                $index_exists = collect(DB::select("SHOW INDEXES FROM users"))->pluck('Key_name')->contains('users_email_unique');
                if (!$index_exists) {
                    $table->string('email')->unique()->change();
                }



            });
        } else {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('username',50)->unique();
                $table->string('name')->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable();
                $table->string('email')->unique()->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->string('fname',50)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable();
                $table->string('lname',50)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable();
                $table->string('title',20)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable();
                $table->string('profile',100);
                $table->string('api_key',50);
                $table->rememberToken();
                $table->timestamps();
            });
        }

        DB::statement("ALTER TABLE users
                              MODIFY COLUMN created_at
                              TIMESTAMP
                              NOT NULL
                              DEFAULT current_timestamp()
                              ;
                    ");

        DB::statement("ALTER TABLE users
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
        Schema::dropIfExists('users');
    }
};
