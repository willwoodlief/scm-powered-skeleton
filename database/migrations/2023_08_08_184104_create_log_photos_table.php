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



        if (Schema::hasTable('log_photos')) {

            DB::statement("ALTER TABLE log_photos ENGINE=InnoDB");
            DB::statement("ALTER TABLE log_photos MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;");
            DB::statement("ALTER TABLE log_photos MODIFY daily_log_id BIGINT UNSIGNED NOT NULL AFTER id;");


            DB::statement("
            DELETE del_asg.* FROM log_photos as del_asg
            INNER JOIN (
                SELECT v.id from log_photos v
                    LEFT JOIN daily_logs p ON p.id = v.daily_log_id
                WHERE
                    (v.daily_log_id IS NOT NULL AND p.id IS NULL)

                 )
               unwanted ON unwanted.id = del_asg.id
            WHERE 1;
        ");

            Schema::table('log_photos', function (Blueprint $table) {



                $table->foreign('daily_log_id')
                    ->references('id')
                    ->on('daily_logs')
                    ->cascadeOnDelete();

                $table->longText('file_name')->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();
                $table->string('thumbnail_name',200)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();
                $table->timestamps();
            });

        } else {
            Schema::create('log_photos', function (Blueprint $table) {
                $table->id();

                $table->foreignId('daily_log_id')
                    ->nullable(false)
                    ->index()
                    ->constrained()
                    ->references('id')
                    ->on('daily_logs')
                    ->cascadeOnDelete();


                $table->longText('file_name')->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);
                $table->string('thumbnail_name',200)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);


                $table->timestamps();




            });
        }

        DB::statement("ALTER TABLE log_photos
                              MODIFY COLUMN created_at
                              TIMESTAMP
                              NULL
                              DEFAULT current_timestamp()
                              ;
                    ");

        DB::statement("ALTER TABLE log_photos
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
        Schema::dropIfExists('log_photos');
    }
};


