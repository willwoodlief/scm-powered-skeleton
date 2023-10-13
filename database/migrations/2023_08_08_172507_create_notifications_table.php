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



        if (Schema::hasTable('notifications')) {


            DB::statement('SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"');
            DB::statement("UPDATE notifications SET created_at = '2000-01-01 00:00:00' WHERE CAST(created_at AS CHAR(20)) = '0000-00-00 00:00:00'");
            DB::statement("UPDATE notifications SET updated_at = '2000-01-01 00:00:00' WHERE CAST(updated_at AS CHAR(20)) = '0000-00-00 00:00:00'");


            DB::statement("ALTER TABLE notifications MODIFY updated_at varchar(100) default NULL;");
            DB::statement("ALTER TABLE notifications MODIFY created_at varchar(100) default NULL;");



            DB::statement("ALTER TABLE notifications ENGINE=InnoDB");
            DB::statement("ALTER TABLE notifications MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;");



            Schema::table('notifications', function (Blueprint $table) {



                $table->string('employee_id',100)
                    ->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')
                    ->nullable(false)->comment('can be a single id, comma delimited or keyword')
                    ->change();

                $table->string('title',100)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();
                $table->longText('description')->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();

                $table->integer('timestamp')->nullable(false)->change();

                $table->json('server_response_json')->nullable();
                if (Schema::hasColumn('notifications', 'created_at')) {
                    $table->dropColumn('created_at');
                }

                if (Schema::hasColumn('notifications', 'updated_at')) {
                    $table->dropColumn('updated_at');
                }


            });

        } else {
            Schema::create('notifications', function (Blueprint $table) {
                $table->id();

                $table->string('employee_id',100)
                    ->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')
                    ->nullable(false)->comment('can be a single id, comma delimited or keyword');


                $table->string('title',100)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);
                $table->longText('description')->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);

                $table->integer('timestamp')->nullable(false);

                $table->json('server_response_json')->nullable(true);

            });
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};


