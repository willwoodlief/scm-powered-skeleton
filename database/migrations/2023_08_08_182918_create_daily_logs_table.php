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



        if (Schema::hasTable('daily_logs')) {


            DB::statement('SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"');
            DB::statement("UPDATE daily_logs SET date = '2000-01-01 00:00:00' WHERE CAST(date AS CHAR(20)) = '0000-00-00 00:00:00'");
            DB::statement("ALTER TABLE daily_logs MODIFY date varchar(100) default NULL;"); //deleted below



            DB::statement("ALTER TABLE daily_logs ENGINE=InnoDB");
            DB::statement("ALTER TABLE daily_logs MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;");
            DB::statement("ALTER TABLE daily_logs MODIFY project_id BIGINT UNSIGNED NOT NULL AFTER id;");
            DB::statement("ALTER TABLE daily_logs MODIFY user_id BIGINT UNSIGNED NOT NULL AFTER id;");

            DB::statement("
            DELETE del_note.* FROM daily_logs as del_note
            INNER JOIN (
                SELECT v.id from daily_logs v

                    LEFT JOIN projects p ON p.id = v.project_id
                WHERE

                    (v.project_id IS NOT NULL AND v.id IS NULL)
                 )
               unwanted ON unwanted.id = del_note.id
            WHERE 1;
        ");

            Schema::table('daily_logs', function (Blueprint $table) {


                $table->foreign('project_id')
                    ->references('id')
                    ->on('projects')
                    ->cascadeOnDelete();


                $table->string('user_type',255)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();
                $table->string('content',255)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();
                $table->string('timestampss',25)->nullable(false)->change();


                if (Schema::hasColumn('daily_logs', 'date')) {
                    $table->dropColumn('date');
                }

                $table->timestamps();

            });

        } else {
            Schema::create('daily_logs', function (Blueprint $table) {
                $table->id();

                $table->foreignId('project_id')
                    ->nullable(false)
                    ->index()
                    ->constrained()
                    ->references('id')
                    ->on('projects')
                    ->cascadeOnDelete();

                $table->foreignId('user_id')
                    ->nullable(false)
                    ->index()
                    ;


                $table->string('user_type',255)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);
                $table->string('content',255)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);
                $table->string('timestampss',25)->nullable(false);


                $table->timestamps();

            });
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_logs');
    }
};


