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
        Schema::create('lab_incidents', function (Blueprint $table) {
            $table->id();
            $table->integer('record')->nullable();
            $table->string('form_type')->nullable();
            $table->string('division_id')->nullable();
            // $table->integer('initiator_id')->nullable();
            $table->integer('initiator_id')->nullable();
            $table->string('division_code')->nullable();
            $table->string('intiation_date')->nullable();
            $table->string('due_date')->nullable();
            $table->integer('parent_id')->nullable();
            $table->string('parent_type')->nullable();
            $table->string('short_desc')->nullable();
            $table->integer('assign_to')->nullable();
            $table->string('status')->nullable();
            $table->integer('stage')->nullable();

            $table->string('name_of_product_material')->nullable();
            $table->text('batch_number')->nullable();
            $table->text('stage_number')->nullable();
            $table->text('reference_specification_number')->nullable();
            $table->longText('Document_Details')->nullable();
            $table->longText('immediate_action_taken')->nullable();
            $table->longText('investigation_details')->nullable();
            $table->longText('investigation_concern_department')->nullable();
            $table->longText('root_cause')->nullable();
            $table->string('is_repeat_gi')->nullable();
            $table->longText('miscellaneous_others')->nullable();
            $table->longText('current_control_measure')->nullable();
            $table->longText('Currective_Action')->nullable();
            $table->longText('Preventive_Action')->nullable();
            $table->longText('evaluation_from_qa')->nullable();
            $table->longText('conclusion_by_head_quality')->nullable();
            $table->longText('justification_delay_closing')->nullable();
            $table->string('target_close_date')->nullable();
            $table->string('tentavie_extended_closure_date')->nullable();
            $table->longText('justification')->nullable();
            $table->longText('closure_verification')->nullable();


            $table->string('submitted_by')->nullable();
            $table->string('incident_review_completed_by')->nullable();
            $table->string('investigation_completed_by')->nullable();
            $table->string('inv_andCAPA_review_comp_by')->nullable();
            $table->string('qA_review_completed_by')->nullable();
            $table->string('qA_head_approval_completed_by')->nullable();
            $table->string('cancelled_by')->nullable();

            //----------------------
            $table->string('severity_level2')->nullable();
            $table->string('Initiator_Group')->nullable();
            $table->longtext('Incident_Category_others')->nullable();

            $table->string('initiator_group_code')->nullable();
            $table->string('Other_Ref')->nullable();
            $table->date('effect_check_date')->nullable();
            $table->date('occurance_date')->nullable();
            $table->string('due_date_extension')->nullable();
            $table->string('Incident_Category')->nullable();
            $table->string('Invocation_Type')->nullable();
            $table->text('Initial_Attachment')->nullable();
            $table->longtext('Incident_Details')->nullable();
            $table->longtext('Document_Details_test')->nullable();
            $table->longtext('Instrument_Details')->nullable();
            $table->longtext('Involved_Personnel')->nullable();
            $table->longtext('Product_Details')->nullable();
            $table->longtext('Supervisor_Review_Comments')->nullable();
            $table->text('Attachments')->nullable();
            $table->text('Cancelation_Remarks')->nullable();
            $table->text('Inv_Attachment')->nullable();
            $table->text('Investigation_Details_sec')->nullable();
            $table->text('Action_Taken')->nullable();
            $table->text('Root_Cause_sec')->nullable();
            $table->text('Currective_Action_sec')->nullable();
            $table->text('Preventive_Action_sec')->nullable();
            $table->text('Corrective_Preventive_Action')->nullable();
            $table->text('CAPA_Attachment')->nullable();
            $table->text('QA_Review_Comments')->nullable();
            $table->text('QA_Head_Attachment')->nullable();
            $table->longtext('QA_Head')->nullable();
            $table->string('Effectiveness_Check')->nullable();
            $table->date('effectivess_check_creation_date')->nullable();
            $table->string('Incident_Type')->nullable();
            $table->longtext('Conclusion')->nullable();
            //----------------------

            $table->string('submitted_on')->nullable();
            $table->string('incident_review_completed_on')->nullable();
            $table->string('investigation_completed_on')->nullable();
            $table->string('inv_andCAPA_review_comp_on')->nullable();
            $table->string('qA_review_completed_on')->nullable();
            $table->string('qA_head_approval_completed_on')->nullable();
            $table->string('review_completed_by')->nullable();
            $table->string('review_completed_on')->nullable();
            $table->string('all_activities_completed_by')->nullable();
            $table->string('all_activities_completed_on')->nullable();
            $table->string('cancelled_on')->nullable();
            

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
        Schema::dropIfExists('lab_incidents');
    }
};
