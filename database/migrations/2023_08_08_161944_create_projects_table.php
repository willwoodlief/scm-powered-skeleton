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



        if (Schema::hasTable('projects')) {

            DB::statement("ALTER TABLE projects ENGINE=InnoDB");
            DB::statement("ALTER TABLE projects MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;");
            DB::statement("ALTER TABLE projects MODIFY contractor BIGINT UNSIGNED DEFAULT NULL AFTER id;");
            DB::statement("ALTER TABLE projects MODIFY pm BIGINT UNSIGNED DEFAULT NULL AFTER contractor;");


            DB::statement("
                UPDATE projects p
                LEFT JOIN contractors c ON c.id = p.contractor
                SET p.contractor = NULL
                WHERE c.id IS NULL;
            ");

            DB::statement("
                UPDATE projects p
                LEFT JOIN employees e ON e.id = p.pm
                SET p.pm = NULL
                WHERE e.id IS NULL;
            ");



            Schema::table('projects', function (Blueprint $table) {

                $table->string('foreman',50)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();
                $table->string('project_name',255)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();
                $table->string('address',250)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();
                $table->string('city',100)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();
                $table->string('state',2)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();
                $table->string('zip',5)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();
                $table->double('latitude')->nullable(false)->default(0)->change();
                $table->double('longitude')->nullable(false)->default(0)->change();

                $table->string('budget',50)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();
                $table->string('start_date',50)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();
                $table->string('end_date',50)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();
                $table->string('super_name',20)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();
                $table->string('super_phone',15)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();
                $table->string('pm_name',20)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();
                $table->string('pm_phone',15)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();


                $table->integer('status')->nullable(false)->change();

                $table->timestamps();

                $table->foreign('contractor')
                    ->references('id')
                    ->on('contractors')
                    ->nullOnDelete();

                $table->foreign('pm')
                    ->references('id')
                    ->on('employees')
                    ->nullOnDelete();


            });

        } else {
            Schema::create('projects', function (Blueprint $table) {
                $table->id();

                $table->foreignId('contractor')->nullable(true)
                    ->index()
                    ->constrained()
                    ->references('id')
                    ->on('contractors')
                    ->nullOnDelete();

                $table->foreignId('pm')->nullable(true)
                    ->index()
                    ->constrained()
                    ->references('id')
                    ->on('employees')
                    ->nullOnDelete();


                $table->string('project_name',255)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);
                $table->string('foreman',50)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);
                $table->string('address',250)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);
                $table->string('city',100)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);
                $table->string('state',2)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);
                $table->string('zip',5)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);
                $table->double('latitude')->nullable(false)->default(0);
                $table->double('longitude')->nullable(false)->default(0);

                $table->string('budget',50)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);
                $table->string('start_date',50)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);
                $table->string('end_date',50)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);
                $table->string('super_name',20)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);
                $table->string('super_phone',15)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);
                $table->string('pm_name',20)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);
                $table->string('pm_phone',15)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);

                $table->integer('status')->nullable(false);

                $table->timestamps();




            });
        }

        DB::statement("ALTER TABLE projects
                              MODIFY COLUMN created_at
                              TIMESTAMP
                              NULL
                              DEFAULT current_timestamp()
                              ;
                    ");

        DB::statement("ALTER TABLE projects
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
        Schema::dropIfExists('projects');
    }
};


