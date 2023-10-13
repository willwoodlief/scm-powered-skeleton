<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(true)
                ->unique()
                ->after('id')
                ->nullable(true)
                ->comment("the associated user_id for an employee that can log in")
                ->constrained()
                ->nullOnDelete()

            ;
        });

        Schema::table('users', function (Blueprint $table) {
            $table->boolean('can_log_in')->nullable(false)->default(User::USER_STATUS_NORMAL)->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('can_log_in');
        });
    }
};
