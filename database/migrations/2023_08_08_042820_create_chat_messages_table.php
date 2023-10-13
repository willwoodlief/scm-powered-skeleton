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



        if (Schema::hasTable('chat_messages')) {
            DB::statement("ALTER TABLE chat_messages ENGINE=InnoDB");
            DB::statement("ALTER TABLE chat_messages MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;");
            DB::statement("ALTER TABLE chat_messages MODIFY user_id BIGINT UNSIGNED NOT NULL AFTER id;");
            DB::statement("ALTER TABLE chat_messages MODIFY message TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL;");

            DB::statement("
            DELETE del_chat.* FROM chat_messages as del_chat
            INNER JOIN (
                SELECT v.id from chat_messages v
                    LEFT JOIN users u ON u.id = v.user_id
                WHERE
                    (v.user_id IS NOT NULL AND u.id IS NULL)
                 )
               unwanted ON unwanted.id = del_chat.id
            WHERE 1;
        ");

            Schema::table('chat_messages', function (Blueprint $table) {


                $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->cascadeOnDelete();

                $table->timestamp('updated_at')->nullable()->default(null);
            });

        } else {
            Schema::create('chat_messages', function (Blueprint $table) {
                $table->id();

                $table->foreignId('user_id')->nullable(true)
                    ->index()
                    ->comment("the associated user_id")
                    ->constrained()
                    ->onDelete('cascade')
                ;
                $table->text('message')->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4');

                $table->timestamps();
            });
        }


        DB::statement("ALTER TABLE chat_messages
                              MODIFY COLUMN created_at
                              TIMESTAMP
                              NULL
                              DEFAULT current_timestamp()
                              ;
                    ");

        DB::statement("ALTER TABLE chat_messages
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
        Schema::dropIfExists('chat_messages');
    }
};
