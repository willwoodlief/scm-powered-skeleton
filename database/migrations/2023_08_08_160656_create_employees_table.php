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



        if (Schema::hasTable('employees')) {

            DB::statement("ALTER TABLE employees ENGINE=InnoDB");
            DB::statement("ALTER TABLE employees MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;");


            Schema::table('employees', function (Blueprint $table) {

                $table->string('first_name',20)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();
                $table->string('last_name',20)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();
                $table->string('dob',20)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();
                $table->string('address',50)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();
                $table->double('latitude')->nullable(false)->default(0)->change();
                $table->double('longitude')->nullable(false)->default(0)->change();
                $table->string('city',20)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();
                $table->string('state',2)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();
                $table->integer('zip')->nullable(false)->change();
                $table->string('phone',11)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();
                $table->string('email',40)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();
                $table->string('password',100)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();
                $table->string('hire_date',20)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();
                $table->integer('role')->nullable(false)->change();

                if (Schema::hasColumn('employees', 'department')) {
                    $table->dropColumn('department');
                }
                $table->string('profile',50)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();
                $table->integer('status')->nullable(false)->change();
                $table->timestamps();

                $table->string('token')->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable()->change();
            });

        } else {
            Schema::create('employees', function (Blueprint $table) {
                $table->id();

                $table->string('first_name',20)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);
                $table->string('last_name',20)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);
                $table->string('dob',20)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);
                $table->string('address',50)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);
                $table->double('latitude')->nullable(false)->default(0);
                $table->double('longitude')->nullable(false)->default(0);
                $table->string('city',20)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);
                $table->string('state',2)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);
                $table->integer('zip')->nullable(false);
                $table->string('phone',11)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);
                $table->string('email',40)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);
                $table->string('password',100)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);
                $table->string('hire_date',20)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);
                $table->integer('role')->nullable(false);


                if (Schema::hasColumn('employees', 'department')) {
                    $table->dropColumn('department');
                }
                $table->string('profile',50)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);
                $table->integer('status')->nullable(false);
                $table->timestamps();

                $table->string('token')->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable();
            });
        }

        DB::statement("ALTER TABLE employees
                              MODIFY COLUMN created_at
                              TIMESTAMP
                              NULL
                              DEFAULT current_timestamp()
                              ;
                    ");

        DB::statement("ALTER TABLE employees
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
        Schema::dropIfExists('employees');
    }
};


