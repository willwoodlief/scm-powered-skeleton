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



        if (Schema::hasTable('todo')) {
            DB::statement("ALTER TABLE todo ENGINE=InnoDB");
            DB::statement("ALTER TABLE todo MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;");
            DB::statement("ALTER TABLE todo MODIFY item TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL;");
            Schema::table('todo', function (Blueprint $table) {

                $table->foreignId('user_id')->after('id')->change();

                $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->cascadeOnDelete();

                $table->timestamps();
            });

        } else {
            Schema::create('todo', function (Blueprint $table) {
                $table->id();

                $table->foreignId('user_id')->nullable(true)
                    ->index()
                    ->comment("the associated user_id")
                    ->constrained()
                    ->onDelete('cascade')
                ;
                $table->text('item')->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')->nullable();
                $table->date('date');
                $table->timestamps();
            });
        }

        DB::statement("ALTER TABLE todo
                              MODIFY COLUMN created_at
                              TIMESTAMP
                              NULL
                              DEFAULT current_timestamp()
                              ;
                    ");

        DB::statement("ALTER TABLE todo
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
        Schema::dropIfExists('todo');
    }
};
