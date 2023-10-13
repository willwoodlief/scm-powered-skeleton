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



        if (Schema::hasTable('invoices')) {

            DB::statement("ALTER TABLE invoices ENGINE=InnoDB");
            DB::statement("ALTER TABLE invoices MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;");
            DB::statement("ALTER TABLE invoices MODIFY project_id BIGINT UNSIGNED NOT NULL AFTER id;");


            DB::statement("
            DELETE del_asg.* FROM invoices as del_asg
            INNER JOIN (
                SELECT v.id from invoices v
                    LEFT JOIN projects p ON p.id = v.project_id
                WHERE
                    (v.project_id IS NOT NULL AND p.id IS NULL)
                 )
               unwanted ON unwanted.id = del_asg.id
            WHERE 1;
        ");

            Schema::table('invoices', function (Blueprint $table) {


                $table->foreign('project_id')
                    ->references('id')
                    ->on('projects')
                    ->cascadeOnDelete();


                $table->integer('number')->nullable(false)->change();
                $table->integer('amount')->nullable(false)->change();
                $table->integer('payment')->nullable(false)->change();
                $table->integer('status')->nullable(false)->change();
                $table->date('date')->nullable(false)->change();

                $table->timestamps();

                $table->string('file',50)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false)->change();


            });

        } else {
            Schema::create('invoices', function (Blueprint $table) {
                $table->id();


                $table->foreignId('project_id')
                    ->nullable(false)
                    ->index()
                    ->constrained()
                    ->references('id')
                    ->on('projects')
                    ->cascadeOnDelete();


                $table->integer('number')->nullable(false);
                $table->integer('amount')->nullable(false);
                $table->integer('payment')->nullable(false);
                $table->integer('status')->nullable(false);
                $table->date('date')->nullable(false);

                $table->timestamps();

                $table->string('file',50)->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable(false);



            });
        }

        DB::statement("ALTER TABLE invoices
                              MODIFY COLUMN created_at
                              TIMESTAMP
                              NULL
                              DEFAULT current_timestamp()
                              ;
                    ");

        DB::statement("ALTER TABLE invoices
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
        Schema::dropIfExists('invoices');
    }
};


