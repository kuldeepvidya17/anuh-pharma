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
        Schema::create('market_complaints', function (Blueprint $table) {
            $table->id();
            $table->integer('initiator_id')->nullable();
            $table->integer('record')->nullable();
            $table->tinyText('division_id')->nullable();
            $table->tinyText('initiator_group_code')->nullable();
            $table->date('intiation_date')->nullable();
            $table->string('form_type')->nullable();
              
            $table->longText('record_number')->nullable();
            $table->longText('division_code')->nullable();
            $table->longText('initiator')->nullable();
            $table->longText('initiation_date')->nullable();
            $table->longText('assign_to')->nullable();
            $table->longText('due_date')->nullable();
            $table->longText('initiator_group')->nullable();
            $table->longText('short_description')->nullable();
            $table->longText('sample_recd')->nullable();
            $table->longText('test_results_recd')->nullable();
            $table->longText('severity_level_form')->nullable();
            $table->longText('acknowledgment_sent')->nullable();
            $table->longText('analysis_physical_examination')->nullable();

            $table->longText('identification_cross_functional')->nullable();
            $table->longText('preliminary_investigation_report')->nullable();
            $table->date('further_response_received')->nullable();
            $table->longText('details_of_response')->nullable();
            $table->longText('further_investigation_additional_testing')->nullable();
            $table->longText('method_tools_to_be_used_for')->nullable();
            
            $table->longText('stage')->nullable();
            $table->longText('type')->nullable();
            $table->longText('status')->nullable();
            $table->longText('plan_proposed_by')->nullable();
            $table->longText('plan_proposed_on')->nullable();
            $table->longText('plan_approved_by')->nullable();
            $table->longText('plan_approved_on')->nullable();

            $table->json('attachments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('market_complaints');
    }
};
