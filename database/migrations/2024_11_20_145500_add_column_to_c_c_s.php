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
        Schema::table('c_c_s', function (Blueprint $table) {


            $table->longText('audit_type')->nullable();
            $table->longText('title')->nullable();    
            $table->Text('doc_no')->nullable();
            $table->longText('Existing_Stage')->nullable();
            $table->longText('Proposed_changes')->nullable();
            $table->longText('justification_changes')->nullable();
            $table->longText('review_initiating')->nullable();
            $table->Text('identification_cross_funct')->nullable();
            $table->longText('evaluation')->nullable();
            $table->longText('outcome_risk')->nullable();
            $table->string('proposal_change')->nullable();
            $table->string('change_category')->nullable();
            $table->longText('reason_categorization')->nullable();
            $table->string('intimation')->nullable();
            $table->longText('acknowledgement')->nullable();
            $table->longText('justification_extension')->nullable();
            $table->longText('closure_remark')->nullable();
            $table->string('effectiveness')->nullable();
            $table->longText('remark')->nullable();
            $table->longText('closure_conclusion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('c_c_s', function (Blueprint $table) {
            //
        });
    }
};
