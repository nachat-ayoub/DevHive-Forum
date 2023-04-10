<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up() {
        Schema::table('votes', function (Blueprint $table) {
            $table->foreignId('question_id')->nullable()->after('answer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() {
        Schema::table('votes', function (Blueprint $table) {
            $table->dropColumn('question_id');
        });
    }

};
