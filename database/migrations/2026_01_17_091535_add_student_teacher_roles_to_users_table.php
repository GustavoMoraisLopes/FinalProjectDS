<?php

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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('user_type', ['student', 'teacher'])->default('student')->after('role')->comment('Tipo de utilizador: student (aluno) ou teacher (professor)');
            $table->string('school')->nullable()->after('user_type')->comment('Institution: istec, ipta, outro');
            $table->string('course_type')->nullable()->after('school')->comment('Course type: CTeSP, Licenciatura, etc');
            $table->string('course_name')->nullable()->after('course_type')->comment('Specific course name');
            $table->string('class_year')->nullable()->after('course_name')->comment('Class/Year: 1ºDS, 2º EI, etc.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['user_type', 'school', 'course_type', 'course_name', 'class_year']);
        });
    }
};
