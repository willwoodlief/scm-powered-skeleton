<?php

use App\Models\User;
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
        Schema::create('user_role', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable(true)
                ->index()
                ->nullable(false)
                ->comment("the user that has this role")
                ->constrained()
                ->onDelete('cascade');

            $table->string('role_name',20);

            $table->timestamps();
        });

        DB::statement("ALTER TABLE user_role
                              MODIFY COLUMN created_at
                              TIMESTAMP
                              NULL
                              DEFAULT current_timestamp()
                              ;
                    ");

        DB::statement("ALTER TABLE user_role
                              MODIFY COLUMN updated_at
                              TIMESTAMP
                              NULL -- the opposite is NOT NULL, which is implicitly set on timestamp columns
                              DEFAULT NULL -- no default value for newly-inserted rows
                              ON UPDATE CURRENT_TIMESTAMP;
                    ");

        //get the admin users and add the admin role to them
        $admins = User::where('permissions', User::USER_PERMISSION_ADMIN)->get();
        foreach ($admins as $an_admin) {
            $an_admin->save_roles([\App\Models\UserRole::USER_ROLE_ADMIN]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_role');
    }
};
