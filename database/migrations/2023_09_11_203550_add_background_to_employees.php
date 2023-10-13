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
        Schema::table('employees', function (Blueprint $table) {

            if ((!Schema::hasColumn('employees', 'profile_bg'))) {
                $table->string('profile_bg',30)
                    ->collation('utf8mb4_0900_ai_ci')->charset('utf8mb4')
                    ->nullable(false)
                    ->default('profile_bg.jpeg')
                    ->after('profile');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('profile_bg');
        });
    }
};
