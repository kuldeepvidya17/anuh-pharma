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
        Schema::table('deviations', function (Blueprint $table) {

            $table->longtext('name_product')->nullable();
            $table->longtext('deviation_stage')->nullable();
            $table->longtext('deviation_shift')->nullable();
            $table->longtext('existing_procedure')->nullable();
            $table->text('identification_cross_funct')->nullable();
            $table->text('investigation_tools')->nullable();
            $table->text('Impact_other')->nullable();
            $table->text('batch_no')->nullable();
            $table->text('deviation_type')->nullable();
            $table->text('risk_assessment')->nullable();
            $table->longtext('Corrective_Action')->nullable();
            $table->text('deviation_approval')->nullable();
            $table->longtext('comments')->nullable();
            $table->text('notification')->nullable();
            $table->longtext('closure_verification')->nullable();
            $table->longtext('Extension_justification')->nullable();
            $table->text('feedback')->nullable();
            $table->longtext('qa_comments')->nullable();
            $table->text('closure_evidences')->nullable();
            $table->text('closure_enclosed')->nullable();
           
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('deviations', function (Blueprint $table) {
            //
        });
    }
};
