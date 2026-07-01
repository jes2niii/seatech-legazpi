<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('civil_status')->nullable()->after('gender');
            $table->string('place_of_birth')->nullable()->after('address');
            $table->string('rank')->nullable()->after('seaman_book_number');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['civil_status', 'place_of_birth', 'rank']);
        });
    }
};
