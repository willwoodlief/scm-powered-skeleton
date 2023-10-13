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



        if (Schema::hasTable('project_assign')) {

            DB::statement("ALTER TABLE project_assign ENGINE=InnoDB");
            DB::statement("ALTER TABLE project_assign MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;");
            DB::statement("ALTER TABLE project_assign MODIFY project_id BIGINT UNSIGNED NOT NULL AFTER id;");
            DB::statement("ALTER TABLE project_assign MODIFY employee_id BIGINT UNSIGNED NOT NULL AFTER project_id;");

            DB::statement("
            DELETE del_asg.* FROM project_assign as del_asg
            INNER JOIN (
                SELECT v.id from project_assign v
                    LEFT JOIN projects p ON p.id = v.project_id
                    LEFT JOIN employees e ON e.id = v.employee_id
                WHERE
                    (v.project_id IS NOT NULL AND p.id IS NULL) OR
                    (v.employee_id IS NOT NULL AND e.id IS NULL)
                 )
               unwanted ON unwanted.id = del_asg.id
            WHERE 1;
        ");

            Schema::table('project_assign', function (Blueprint $table) {


                $table->foreign('project_id')
                    ->references('id')
                    ->on('projects')
                    ->cascadeOnDelete();

                $table->foreign('employee_id')
                    ->references('id')
                    ->on('employees')
                    ->cascadeOnDelete();

                $table->integer('status')->default(0)->nullable(false)->change();

                $table->timestamp('updated_at')->nullable()->default(null);


            });

        } else {
            Schema::create('project_assign', function (Blueprint $table) {
                $table->id();

                $table->foreign('project_id')
                    ->references('id')
                    ->on('projects')
                    ->cascadeOnDelete();

                $table->foreign('employee_id')
                    ->references('id')
                    ->on('employees')
                    ->cascadeOnDelete();

                $table->integer('status')->default(0)->nullable(false);

                $table->timestamps();




            });
        }

        DB::statement("ALTER TABLE project_assign
                              MODIFY COLUMN created_at
                              TIMESTAMP
                              NULL
                              DEFAULT current_timestamp()
                              ;
                    ");

        DB::statement("ALTER TABLE project_assign
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
        Schema::dropIfExists('project_assign');
    }
};


