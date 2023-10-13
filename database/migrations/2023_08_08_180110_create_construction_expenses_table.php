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



        if (Schema::hasTable('construction_expenses')) {

            DB::statement("ALTER TABLE construction_expenses ENGINE=InnoDB");
            DB::statement("ALTER TABLE construction_expenses MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;");
            DB::statement("ALTER TABLE construction_expenses MODIFY project_id BIGINT UNSIGNED NOT NULL AFTER id;");


            DB::statement("
            DELETE del_asg.* FROM construction_expenses as del_asg
            INNER JOIN (
                SELECT v.id from construction_expenses v
                    LEFT JOIN projects p ON p.id = v.project_id
                WHERE
                    (v.project_id IS NOT NULL AND p.id IS NULL)

                 )
               unwanted ON unwanted.id = del_asg.id
            WHERE 1;
        ");

            Schema::table('construction_expenses', function (Blueprint $table) {



                $table->foreign('project_id')
                    ->references('id')
                    ->on('projects')
                    ->cascadeOnDelete();

                $table->date('date')->nullable(false)->change();
                $table->string('transaction_type',255)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();
                $table->string('business',255)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();
                $table->string('description',255)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();
                $table->string('employee_id',50)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();
                $table->float('amount')->nullable(false)->change();


                $table->timestamps();
            });

        } else {
            Schema::create('construction_expenses', function (Blueprint $table) {
                $table->id();

                $table->foreignId('project_id')
                    ->nullable(false)
                    ->index()
                    ->constrained()
                    ->references('id')
                    ->on('projects')
                    ->cascadeOnDelete();


                $table->date('date')->nullable(false);
                $table->string('transaction_type',255)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);
                $table->string('business',255)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);
                $table->string('description',255)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);
                $table->string('employee_id',50)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);
                $table->float('amount')->nullable(false);

                $table->timestamps();




            });
        }

        DB::statement("ALTER TABLE construction_expenses
                              MODIFY COLUMN created_at
                              TIMESTAMP
                              NULL
                              DEFAULT current_timestamp()
                              ;
                    ");

        DB::statement("ALTER TABLE construction_expenses
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
        Schema::dropIfExists('construction_expenses');
    }
};


