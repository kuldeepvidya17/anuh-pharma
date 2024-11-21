<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('capas', function (Blueprint $table) {
            $table->longText('source_of_capa')->nullable();
            $table->longText('others')->nullable();
            $table->longText('source_document_name')->nullable();
            $table->longText('comments_cloasure')->nullable();
            $table->longText('head_quality')->nullable();
            $table->longText('justification')->nullable();
            $table->longText('effectiveness_verification_capa')->nullable();
            $table->longText('effectivenessRemark')->nullable();
            $table->longText('problem_description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('capas', function (Blueprint $table) {
            //
        });
    }
};
