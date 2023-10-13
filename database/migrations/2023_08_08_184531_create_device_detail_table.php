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



        if (Schema::hasTable('device_detail')) {

            DB::statement('SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"');
            DB::statement("UPDATE device_detail SET created_at = '2000-01-01 00:00:00' WHERE CAST(created_at AS CHAR(20)) = '0000-00-00 00:00:00'");
            DB::statement("UPDATE device_detail SET updated_at = '2000-01-01 00:00:00' WHERE CAST(updated_at AS CHAR(20)) = '0000-00-00 00:00:00'");


            DB::statement("ALTER TABLE device_detail MODIFY updated_at timestamp default NULL;");
            DB::statement("ALTER TABLE device_detail MODIFY created_at timestamp default NULL;");


            DB::statement("UPDATE device_detail SET created_at = null WHERE created_at = '2000-01-01 00:00:00'");
            DB::statement("UPDATE device_detail SET updated_at = null WHERE updated_at = '2000-01-01 00:00:00'");

            DB::statement("ALTER TABLE device_detail ENGINE=InnoDB");
            DB::statement("ALTER TABLE device_detail MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;");
            DB::statement("ALTER TABLE device_detail MODIFY employee_id BIGINT UNSIGNED NOT NULL AFTER id;");


            DB::statement("
            DELETE del_asg.* FROM device_detail as del_asg
            INNER JOIN (
                SELECT v.id from device_detail v
                    LEFT JOIN employees e ON e.id = v.employee_id
                WHERE
                    (v.employee_id IS NOT NULL AND e.id IS NULL)

                 )
               unwanted ON unwanted.id = del_asg.id
            WHERE 1;
        ");

            Schema::table('device_detail', function (Blueprint $table) {



                $table->foreign('employee_id')
                    ->references('id')
                    ->on('employees')
                    ->cascadeOnDelete();

                $table->string('device_type',150)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();
                $table->string('device_token',255)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();
            });

        } else {
            Schema::create('device_detail', function (Blueprint $table) {
                $table->id();

                $table->foreignId('employee_id')
                    ->nullable(false)
                    ->index()
                    ->constrained()
                    ->references('id')
                    ->on('employees')
                    ->cascadeOnDelete();


                $table->string('device_type',150)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);
                $table->string('device_token',255)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);


                $table->timestamps();




            });
        }

        DB::statement("ALTER TABLE device_detail
                              MODIFY COLUMN created_at
                              TIMESTAMP
                              NULL
                              DEFAULT current_timestamp()
                              ;
                    ");

        DB::statement("ALTER TABLE device_detail
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
        Schema::dropIfExists('device_detail');
    }
};


