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
        Schema::table('market_complaints', function (Blueprint $table) {
            $table->longText('capa_qa_comments2')->nullable();
            $table->longText('qa_review')->nullable();
            $table->longText('head_qulitiy_comment')->nullable();
            $table->longText('due_date_extension')->nullable();
            $table->longText('re_categoruzation_of_complaint')->nullable();
            $table->longText('reson_for_re_cate')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('market_complaints', function (Blueprint $table) {
            //
        });
    }
};
