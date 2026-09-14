<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable()
                ->after('id')
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('shift_id')
                ->nullable()
                ->after('department_id')
                ->constrained('shifts')
                ->nullOnDelete();

            $table->string('designation')->nullable()->after('department_id');

            $table->enum('status', ['active', 'inactive'])
                ->default('active')
                ->after('joining_date');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['shift_id']);
            $table->dropColumn(['user_id', 'shift_id', 'designation', 'status']);
        });
    }
};
