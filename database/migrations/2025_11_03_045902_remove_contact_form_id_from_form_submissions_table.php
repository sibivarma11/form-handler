<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('form_submissions', function (Blueprint $table) {
            $table->dropForeign(['contact_form_id']);
            $table->dropColumn('contact_form_id');
        });
    }

    public function down()
    {
        Schema::table('form_submissions', function (Blueprint $table) {
            $table->foreignId('contact_form_id')->constrained()->cascadeOnDelete();
        });
    }
};