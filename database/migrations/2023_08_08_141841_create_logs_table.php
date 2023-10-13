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



        if (Schema::hasTable('logs')) {
           DB::statement("UPDATE logs SET timestamp = '2000-01-01 00:00:00' WHERE CAST(timestamp AS CHAR(20)) = '0000-00-00 00:00:00'");

            DB::statement("ALTER TABLE logs ENGINE=InnoDB");
            DB::statement("ALTER TABLE logs MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;");
            DB::statement("ALTER TABLE logs RENAME COLUMN user to user_id;"); //todo after code altered by David change this in code
            DB::statement("ALTER TABLE logs RENAME COLUMN timestamp to created_at;");
            DB::statement("ALTER TABLE logs MODIFY user_id BIGINT UNSIGNED NOT NULL AFTER id;");
            DB::statement("ALTER TABLE logs MODIFY action TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL;");


            DB::statement("
            DELETE del_log.* FROM logs as del_log
            INNER JOIN (
                SELECT v.id from logs v
                    LEFT JOIN users u ON u.id = v.user_id
                WHERE
                    (v.user_id IS NOT NULL AND u.id IS NULL)
                 )
               unwanted ON unwanted.id = del_log.id
            WHERE 1;
        ");

            Schema::table('logs', function (Blueprint $table) {


                $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->cascadeOnDelete();

                $table->timestamp('updated_at')->nullable()->default(null);
            });

        } else {
            Schema::create('logs', function (Blueprint $table) {
                $table->id();

                $table->foreignId('user_id')->nullable(true)
                    ->index()
                    ->comment("the associated user_id")
                    ->constrained()
                    ->onDelete('cascade')
                ;
                $table->text('action');

                $table->timestamps();
            });
        }

        DB::statement("ALTER TABLE logs
                              MODIFY COLUMN created_at
                              TIMESTAMP
                              NULL
                              DEFAULT current_timestamp()
                              ;
                    ");

        DB::statement("ALTER TABLE logs
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
        Schema::dropIfExists('logs');
    }
};


