<?php

namespace App\Services\Qms;

use App\Models\OOS;
use App\Models\Oosgrids;
use App\Models\OosAuditTrial;
use App\Models\RoleGroup;
use App\Models\RecordNumber;
use Helpers;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class OOSService
{
    public $oos;

    public function __construct(OOS $oos) {
        $this->oos = $oos;
    }

    public static function create_oss(Request $request)
    {
        
        $res = Helpers::getDefaultResponse();

        try {

            $input = $request->all();
            $input['form_type'] = "OOS";
            $input['status'] = 'Opened';
            $input['stage'] = 1;
            $input['record_number'] = ((RecordNumber::first()->value('counter')) + 1);

            $file_input_names = [
                // 'initial_attachment_gi',
                // 'file_attachments_pli',
                // 'supporting_attachment_plic',
                // 'supporting_attachments_plir',
                // 'attachments_piiqcr',
                // 'additional_testing_attachment_atp',
                // 'file_attachments_if_any_ooscattach',
                // 'conclusion_attachment_ocr',
                // 'cq_attachment_ocqr',
                // 'disposition_attachment_bd',
                // 'reopen_attachment_ro',
                // 'addendum_attachment_uaa',
                // 'addendum_attachments_uae',
                // 'required_attachment_uar',
                // 'verification_attachment_uar',
                // 'hod_attachment1',
                // 'hod_attachment2',
                // 'hod_attachment3',
                // 'hod_attachment4',
                // 'hod_attachment5',
                // 'QA_Head_attachment1',
                // 'QA_Head_attachment2',
                // 'QA_Head_attachment3',
                // 'QA_Head_attachment4',
                // 'QA_Head_attachment5',
                // 'QA_Head_primary_attachment1',
                // 'QA_Head_primary_attachment2',
                // 'QA_Head_primary_attachment3',
                // 'QA_Head_primary_attachment4',
                // 'QA_Head_primary_attachment5',
                // 'provide_attachment1',
                // 'provide_attachment2',
                // 'provide_attachment3',
                // 'provide_attachment4',
                // 'provide_attachment5',
            ];

            foreach ($file_input_names as $file_input_name)
            {
                $input[$file_input_name] = FileService::uploadMultipleFiles($request, $file_input_name);
            }

            $oos = OOS::create($input);
            $record = RecordNumber::first();
            $record->counter = ((RecordNumber::first()->value('counter')) + 1);
            $record->update();

            $grid_inputs = [
                'info_product_material',
                'details_stability',
                'oos_detail',
                'checklist_lab_inv',
                'checklist_IB_inv',
                'phase_iii_result_i',
                // 'oos_capa',
                // 'phase_two_inv',
                // 'phase_two_inv1',
                // 'ph_meter',
                // 'Viscometer',
                // 'Melting_Point',
                // 'Dis_solution',
                // 'HPLC_GC',
                // 'General_Checklist',
                // 'kF_Potentionmeter',
                // 'RM_PM',
                // 'analyst_training_procedure',
                // 'sample_receiving_var',
                // 'method_used_during_analysis',
                // 'instrument_equipment_detailss',
                // 'result_and_calculation',
                // 'Training_records_Analyst_Involved1',
                // 'sample_intactness_before_analysis1',
                // 'test_methods_Procedure1',
                // 'Review_of_Media_Buffer_Standards_prep1',
                // 'Checklist_for_Revi_of_Media_Buffer_Stand_prep1',
                // 'ccheck_for_disinfectant_detail1',
                // 'Checklist_for_Review_of_instrument_equip1',
                // 'Checklist_for_Review_of_Training_records_Analyst1',
                // 'Checklist_for_Review_of_sampling_and_Transport1',
                // 'Checklist_Review_of_Test_Method_proceds1',
                // 'Checklist_for_Review_Media_prepara_RTU_medias1',
                // 'Checklist_Review_Environment_condition_in_tests1',
                // 'review_of_instrument_bioburden_and_waters1',
                // 'disinfectant_details_of_bioburden_and_water_tests1',
                // 'training_records_analyst_involvedIn_testing_microbial_asssays1',
                // 'sample_intactness_before_analysis22',
                // 'checklist_for_review_of_test_method_IMA1',
                // 'cr_of_media_buffer_st_IMA1',
                // 'CR_of_microbial_cultures_inoculation_IMA1',
                // 'CR_of_Environmental_condition_in_testing_IMA1',
                // 'CR_of_instru_equipment_IMA1',
                // 'disinfectant_details_IMA1',
                // 'CR_of_training_rec_anaylst_in_monitoring_CIEM1',
                // 'Check_for_Sample_details_CIEM1',
                // 'Check_for_comparision_of_results_CIEM1',
                // 'checklist_for_media_dehydrated_CIEM1',
                // 'checklist_for_media_prepara_sterilization_CIEM1',
                // 'CR_of_En_condition_in_testing_CIEM1',
                // 'check_for_disinfectant_CIEM1',
                // 'checklist_for_fogging_CIEM1',
                // 'CR_of_test_method_CIEM1',
                // 'CR_microbial_isolates_contamination_CIEM1',
                // 'CR_of_instru_equip_CIEM1',
                // 'Ch_Trend_analysis_CIEM1',
                // 'checklist_for_analyst_training_CIMT2',
                // 'checklist_for_comp_results_CIMT2',
                // 'checklist_for_Culture_verification_CIMT2',
                // 'sterilize_accessories_CIMT2',
                // 'checklist_for_intrument_equip_last_CIMT2',
                // 'disinfectant_details_last_CIMT2',
                // 'checklist_for_result_calculation_CIMT2',
                // 'oos_conclusion',
                // 'oos_conclusion_review',
                'phase_iii_result',
                // 'products_details',
                'instrument_detail',
            ];

            foreach ($grid_inputs as $grid_input)
            {
                self::store_grid($oos, $request, $grid_input);
            }

            // TODO: Audit Trail
                 if(!empty($request->description_gi)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Short Description';
                    $history->current = $request->description_gi;
                    $history->save();
                }
                if (!empty($request->initiator_Group)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'initiator Group';
                    $history->current = $request->initiator_Group;
                    $history->save();
                }
                if (!empty($request->initiator_group_code)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Initiator Group Code';
                    $history->current = $request->initiator_group_code;
                    $history->save();
                }
                if (!empty($request->if_others_gi)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'If Others';
                    $history->current = $request->if_others_gi;
                    $history->save();
                }
                if (!empty($request->is_repeat_gi)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Is Repeat';
                    $history->current = $request->is_repeat_gi;
                    $history->save();
                }
                if (!empty($request->repeat_nature_gi)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Nature Of Change';
                    $history->current = $request->nature_of_change_gi;
                    $history->save();
                }
                if (!empty($request->deviation_occured_on_gi)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Deviation Occured On';
                    $history->current = $request->deviation_occured_on_gi;
                    $history->save();
                }
                if (!empty($request->source_document_type_gi)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Source Document Type';
                    $history->current = $request->source_document_type_gi;
                    $history->save();
                }
                if (!empty($request->reference_system_document_gi)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Reference System Document';
                    $history->current = $request->reference_system_document_gi;
                    $history->save();
                }
                if (!empty($request->reference_document)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Reference Document';
                    $history->current = $request->reference_document;
                    $history->save();
                }
                if (!empty($request->sample_type_gi)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Sample Type';
                    $history->current = $request->sample_type_gi;
                    $history->save();
                }
                if (!empty($request->product_material_name_gi)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Product / Material Name';
                    $history->current = $request->product_material_name_gi;
                    $history->save();
                }
                if (!empty($request->market_gi)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Market';
                    $history->current = $request->market_gi;
                    $history->save();
                }
                if (!empty($request->customer_gi)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Customer';
                    $history->current = $oos->customer_gi;
                    $history->save();
                }
                // TapII
                if (!empty($request->Comments_plidata)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Comments Plidata';
                    $history->current = $oos->Comments_plidata;
                    $history->save();
                }
                if (!empty($request->justify_if_no_field_alert_pli)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Justify If No Field Alert Pli';
                    $history->current = $oos->justify_if_no_field_alert_pli;
                    $history->save();
                }
                if (!empty($request->justify_if_no_analyst_int_pli)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Justify if no Analyst Int';
                    $history->current = $request->justify_if_no_analyst_int_pli;
                    $history->save();
                }
                if (!empty($request->phase_i_investigation_pli)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Phase I Investigation';
                    $history->current = $request->phase_i_investigation_pli;
                    $history->save();
                }
                if (!empty($request->phase_i_investigation_ref_pli)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Phase I Investigation Ref';
                    $history->current = $request->phase_i_investigation_ref_pli;
                    $history->save();
                }
                // TapIV
                if (!empty($request->summary_of_prelim_investiga_plic)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Summary of Preliminary Investigation';
                    $history->current = $request->summary_of_prelim_investiga_plic;
                    $history->save();
                }
                if (!empty($request->root_cause_identified_plic)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Root Cause Identified';
                    $history->current = $request->root_cause_identified_plic;
                    $history->save();
                }
                if (!empty($request->oos_category_root_cause_ident_plic)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'OOS Category-Root Cause Ident';
                    $history->current = $request->oos_category_root_cause_ident_plic;
                    $history->save();
                }
                if (!empty($request->root_cause_details_plic)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'OOS Category Others';
                    $history->current = $request->root_cause_details_plic;
                    $history->save();
                }
                if (!empty($request->oos_category_others_plic)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Root Cause Details';
                    $history->current = $request->oos_category_others_plic;
                    $history->save();
                }
                if (!empty($request->oos_category_others_plic)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'OOS Category-Root Cause Ident';
                    $history->current = $request->oos_category_others_plic;
                    $history->save();
                }
                if (!empty($request->capa_required_plic)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'CAPA Required';
                    $history->current = $request->capa_required_plic;
                    $history->save();
                }
                if (!empty($request->reference_capa_no_plic)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Reference CAPA No';
                    $history->current = $request->reference_capa_no_plic;
                    $history->save();
                }
                if (!empty($request->delay_justification_for_pi_plic)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Delay Justification for Preliminary Investigation';
                    $history->current = $request->delay_justification_for_pi_plic;
                    $history->save();
                }
                // TapV5
                if (!empty($request->review_comments_plir)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Review Comments';
                    $history->current = $request->review_comments_plir;
                    $history->save();
                }
                if (!empty($request->phase_ii_inv_required_plir)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Phase II Inv. Required';
                    $history->current = $request->phase_ii_inv_required_plir;
                    $history->save();
                }
                // TapVI6
                if (!empty($request->qa_approver_comments_piii)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'QA Approver Comments';
                    $history->current = $request->qa_approver_comments_piii;
                    $history->save();
                }
                if (!empty($request->qa_approver_comments_piii)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Manufact. Invest. Required?';
                    $history->current = $request->qa_approver_comments_piii;
                }
                if (!empty($request->manufact_invest_required_piii)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = ' Manufacturing Invest. Type';
                    $history->current = $request->manufact_invest_required_piii;
                    $history->save();
                }
                if (!empty($request->manufacturing_invest_type_piii)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'manufacturing_invest_type_piii';
                    $history->current = $request->manufacturing_invest_type_piii;
                } 
                if (!empty($request->audit_comments_piii)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Audit Comments';
                    $history->current = $request->audit_comments_piii;
                    $history->save();
                }
                if (!empty($request->hypo_exp_required_piii)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Hypo/Exp. Required';
                    $history->current = $request->hypo_exp_required_piii;
                    $history->save();
                }
                if (!empty($request->hypo_exp_reference_piii)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Hypo/Exp. Reference';
                    $history->current = $request->hypo_exp_reference_piii;
                    $history->save();
                }
                // TapVIII8
                if (!empty($request->summary_of_exp_hyp_piiqcr)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Summary of Exp./Hyp.';
                    $history->current = $request->summary_of_exp_hyp_piiqcr;
                    $history->save();
                }
                if (!empty($request->summary_mfg_investigation_piiqcr)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Summary Mfg. Investigation';
                    $history->current = $request->summary_mfg_investigation_piiqcr;
                    $history->save();
                }
                if (!empty($request->root_casue_identified_piiqcr)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Root Casue Identified';
                    $history->current = $request->root_casue_identified_piiqcr;
                    $history->save();
                }
                if (!empty($request->oos_category_reason_identified_piiqcr)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'OOS Category-Reason identified';
                    $history->current = $request->oos_category_reason_identified_piiqcr;
                    $history->save();
                }
                
                if (!empty($request->others_oos_category_piiqcr)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Others (OOS category)';
                    $history->current = $request->others_oos_category_piiqcr;
                    $history->save();
                }
                if (!empty($request->details_of_root_cause_piiqcr)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Details of Root Cause';
                    $history->current = $request->details_of_root_cause_piiqcr;
                    $history->save();
                }
                if (!empty($request->impact_assessment_piiqcr)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Impact Assessment.';
                    $history->current = $request->impact_assessment_piiqcr;
                    $history->save();
                }
                // ======= Additional Testing Proposal ============
                if (!empty($request->review_comment_atp)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Review Comment Atp.';
                    $history->current = $request->review_comment_atp;
                    $history->save();
                }
                if (!empty($request->additional_test_proposal_atp)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Additional Test Proposal Atp.';
                    $history->current = $request->additional_test_proposal_atp;
                    $history->save();
                }
                if (!empty($request->additional_test_reference_atp)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'additional Test Reference Atp.';
                    $history->current = $request->additional_test_reference_atp;
                    $history->save();
                }
                if (!empty($request->any_other_actions_required_atp)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Any Other Actions Required Atp.';
                    $history->current = $request->any_other_actions_required_atp;
                    $history->save();
                }
                // =============== OOS Conclusion  =====================
                if (!empty($request->conclusion_comments_oosc)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Conclusion Comments.';
                    $history->current = $request->conclusion_comments_oosc;
                    $history->save();
                }
                if (!empty($request->specification_limit_oosc)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Specification Limit.';
                    $history->current = $request->specification_limit_oosc;
                    $history->save();
                }
                if (!empty($request->results_to_be_reported_oosc)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Results to be Reported.';
                    $history->current = $request->results_to_be_reported_oosc;
                    $history->save();
                }
                if (!empty($request->final_reportable_results_oosc)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Final Reportable Results.';
                    $history->current = $request->final_reportable_results_oosc;
                    $history->save();
                } 
                if (!empty($request->justifi_for_averaging_results_oosc)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Justifi. for Averaging Results.';
                    $history->current = $request->justifi_for_averaging_results_oosc;
                    $history->save();
                } 
                if (!empty($request->oos_stands_oosc)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'OOS Stands.';
                    $history->current = $request->oos_stands_oosc;
                    $history->save();
                }
                
                if (!empty($request->reference_record)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'CAPA Ref No.';
                    $history->current = $request->reference_record;
                    $history->save();
                }
                if (!empty($request->justify_if_capa_not_required_oosc)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Justify if CAPA not required.';
                    $history->current = $request->justify_if_capa_not_required_oosc;
                    $history->save();
                } 
                if (!empty($request->action_plan_req_oosc)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Action Item Req.';
                    $history->current = $request->action_plan_req_oosc;
                    $history->save();
                }
                if (!empty($request->justification_for_delay_oosc)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = ' Justification for Delay.';
                    $history->current = $request->justification_for_delay_oosc;
                    $history->save();
                }
                // ========= OOS Conclusion Review ==============
                if (!empty($request->conclusion_review_comments_ocr)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = ' Conclusion Review Comments.';
                    $history->current = $request->conclusion_review_comments_ocr;
                    $history->save();
                }
                if (!empty($request->action_taken_on_affec_batch_ocr)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = ' Action Taken on Affec.batch.';
                    $history->current = $request->action_taken_on_affec_batch_ocr;
                    $history->save();
                }
                if (!empty($request->capa_req_ocr)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = ' CAPA Req.';
                    $history->current = $request->capa_req_ocr;
                    $history->save();
                }
                if (!empty($request->justify_if_no_risk_assessment_ocr)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Justify if No Risk Assessment';
                    $history->current = $request->justify_if_no_risk_assessment_ocr;
                    $history->save();
                }
                if (!empty($request->cq_approver)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'CQ Approver';
                    $history->current = $request->cq_approver;
                    $history->save();
                }
                // =========== CQ Review Comments ==========
                if (!empty($request->cq_review_comments_ocqr)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'CQ Review comments';
                    $history->current = $request->cq_review_comments_ocqr;
                    $history->save();
                }
                //==========  Batch Disposition =============
                if (!empty($request->oos_category_bd)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'OOS Category';
                    $history->current = $request->oos_category_bd;
                    $history->save();
                }
                if (!empty($request->others_bd)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Other';
                    $history->current = $request->others_bd;
                    $history->save();
                    
                }
                if (!empty($request->material_batch_release_bd)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Material batch release bd';
                    $history->current = $request->material_batch_release_bd;
                    $history->save();
                }
                if (!empty($request->other_action_bd)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Other Action bd';
                    $history->current = $request->other_action_bd;
                    $history->save();
                }
                if (!empty($request->other_parameters_results_bd)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Other Parameters Results';
                    $history->current = $request->other_parameters_results_bd;
                    $history->save();
                }
                if (!empty($request->trend_of_previous_batches_bd)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Trend of Previous Batches';
                    $history->current = $request->trend_of_previous_batches_bd;
                    $history->save();
                }
                if (!empty($request->stability_data_bd)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Stability Data';
                    $history->current = $request->stability_data_bd;
                    $history->save();
                }
                if (!empty($request->process_validation_data_bd)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Process Validation Data';
                    $history->current = $request->process_validation_data_bd;
                    $history->save();
                }
                if (!empty($request->method_validation_bd)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Method Validation';
                    $history->current = $request->method_validation_bd;
                    $history->save();
                }
                if (!empty($request->any_market_complaints_bd)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Any Market Complaints';
                    $history->current = $request->any_market_complaints_bd;
                    $history->save();
                }
                
                if (!empty($request->statistical_evaluation_bd)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Statistical Evaluation Bd';
                    $history->current = $request->statistical_evaluation_bd;
                    $history->save();
                }
                
                if (!empty($request->risk_analysis_disposition_bd)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Risk Analysis Disposition_bd';
                    $history->current = $request->risk_analysis_disposition_bd;
                    $history->save();
                }
                
                if (!empty($request->conclusion_bd)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Conclusion bd';
                    $history->current = $request->conclusion_bd;
                    $history->save();
                }
                if (!empty($request->justify_for_delay_in_activity_bd)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Justify for delay in activity';
                    $history->current = $request->justify_for_delay_in_activity_bd;
                    $history->save();
                }
                // =============== QA Head/Designee Approval ==========
                if (!empty($request->reopen_approval_comments_uaa)){
                    $history = new OosAuditTrial();
                    $history->oos_id = $oos->id;
                    $history->previous = "Null";
                    $history->comment = "Not Applicable";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $oos->status;
                    $history->stage = $oos->stage;
                    $history->change_to =   "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = 'Create';
                    $history->activity_type = 'Approval Comments ';
                    $history->current = $request->reopen_approval_comments_uaa;
                    $history->save();
                }
               
            $res['body'] = $oos;

        } catch (\Exception $e) {
            $res['status'] = 'error';
            $res['message'] = $e->getMessage();
        }

        return $res;
        
    }

    public static function store_grid(OOS $oos, Request $request, $identifier)
    {
        $res = Helpers::getDefaultResponse();
        
        try {

            $oos_grid = Oosgrids::where([ 'identifier' => $identifier, 'oos_id' => $oos->id  ])->firstOrNew();
            $oos_grid->oos_id = $oos->id;
            $oos_grid->identifier = $identifier;
            $oos_grid->data = $request->$identifier;
            $oos_grid->save();
            
        } catch (\Exception $e) {
            $res['status'] = 'error';
            $res['message'] = $e->getMessage();
            info('Error in OOSService@store_grid', [
                'message' => $e->getMessage()
            ]);
        }

        return $res;
    }
    
    public static function update_oss(Request $request, $id)
    {
        $res = Helpers::getDefaultResponse();

        try {

            $input = $request->all();

             // TODO: Audit Trail
            // $lastOosRecod = OOS::find($id);
            $lastOosRecod = OOS::where('id', $id)->first();
            
            if ($lastOosRecod->description_gi != $request->description_gi){
                // dd($lastOosRecod->description_gi);
                $history = new OosAuditTrial;
                $history->oos_id = $lastOosRecod->id;
                $history->activity_type = 'Short Description';
                $history->previous = $lastOosRecod->description_gi;
                $history->current = $request->description_gi;
                $history->comment = "Null";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->change_to =   "Not Applicable";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = "Update";
                $history->save();
            }
            
            if ($lastOosRecod->initiator_Group != $request->initiator_Group){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->initiator_Group;
                $history->activity_type = 'initiator Group';
                $history->current = $request->initiator_Group;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'update';
                $history->save();
            }
            if ($lastOosRecod->initiator_group_code != $request->initiator_group_code){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->initiator_group_code;
                $history->activity_type = 'Initiator Group Code';
                $history->current = $request->initiator_group_code;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->if_others_gi != $request->if_others_gi){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->if_others_gi;
                $history->activity_type = 'If Others';
                $history->current = $request->if_others_gi;
                $history->save();
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'update';
            }
            if ($lastOosRecod->is_repeat_gi != $request->is_repeat_gi){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->is_repeat_gi;
                $history->activity_type = 'Is Repeat';
                $history->current = $request->is_repeat_gi;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->nature_of_change_gi != $request->nature_of_change_gi){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->nature_of_change_gi;
                $history->activity_type = 'Nature Of Change';
                $history->current = $request->nature_of_change_gi;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->deviation_occured_on_gi != $request->deviation_occured_on_gi){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->deviation_occured_on_gi;
                $history->activity_type = 'Deviation Occured On';
                $history->current = $request->deviation_occured_on_gi;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->source_document_type_gi != $request->source_document_type_gi){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->source_document_type_gi;
                $history->activity_type = 'Source Document Type';
                $history->current = $request->source_document_type_gi;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->sample_type_gi != $request->sample_type_gi){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->sample_type_gi;
                $history->activity_type = 'Sample Type';
                $history->current = $request->sample_type_gi;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->product_material_name_gi != $request->product_material_name_gi){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->product_material_name_gi;
                $history->activity_type = 'Product / Material Name';
                $history->current = $request->product_material_name_gi;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->market_gi != $request->market_gi){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->market_gi;
                $history->activity_type = 'Market';
                $history->current = $request->market_gi;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->customer_gi != $request->customer_gi){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->customer_gi;
                $history->activity_type = 'Customer';
                $history->current = $lastOosRecod->customer_gi;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            // TapII
            if ($lastOosRecod->Comments_plidata != $request->Comments_plidata){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->Comments_plidata;
                $history->activity_type = 'Comments Plidata';
                $history->current = $lastOosRecod->Comments_plidata;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->justify_if_no_field_alert_pli != $request->justify_if_no_field_alert_pli){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->justify_if_no_field_alert_pli;
                $history->activity_type = 'Justify If No Field Alert Pli';
                $history->current = $lastOosRecod->justify_if_no_field_alert_pli;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->justify_if_no_analyst_int_pli != $request->justify_if_no_analyst_int_pli){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->justify_if_no_analyst_int_pli;
                $history->activity_type = 'Justify if no Analyst Int';
                $history->current = $request->justify_if_no_analyst_int_pli;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->phase_i_investigation_pli != $request->phase_i_investigation_pli){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->phase_i_investigation_pli;
                $history->activity_type = 'Phase I Investigation';
                $history->current = $request->phase_i_investigation_pli;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->phase_i_investigation_ref_pli != $request->phase_i_investigation_ref_pli){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->phase_i_investigation_ref_pli;
                $history->activity_type = 'Phase I Investigation Ref';
                $history->current = $request->phase_i_investigation_ref_pli;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            // TapIV
            if ($lastOosRecod->summary_of_prelim_investiga_plic != $request->summary_of_prelim_investiga_plic){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->summary_of_prelim_investiga_plic;
                $history->activity_type = 'Summary of Preliminary Investigation';
                $history->current = $request->summary_of_prelim_investiga_plic;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->root_cause_identified_plic != $request->root_cause_identified_plic){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->root_cause_identified_plic;
                $history->activity_type = 'Root Cause Identified';
                $history->current = $request->root_cause_identified_plic;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->oos_category_root_cause_ident_plic != $request->oos_category_root_cause_ident_plic){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->oos_category_root_cause_ident_plic;
                $history->activity_type = 'OOS Category-Root Cause Ident';
                $history->current = $request->oos_category_root_cause_ident_plic;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->root_cause_details_plic != $request->root_cause_details_plic){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->root_cause_details_plic;
                $history->activity_type = 'OOS Category Others';
                $history->current = $request->root_cause_details_plic;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->oos_category_others_plic != $request->oos_category_others_plic){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->oos_category_others_plic;
                $history->activity_type = 'Root Cause Details';
                $history->current = $request->oos_category_others_plic;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->oos_category_others_plic != $request->oos_category_others_plic){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->oos_category_others_plic;
                $history->activity_type = 'OOS Category-Root Cause Ident';
                $history->current = $request->oos_category_others_plic;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->capa_required_plic != $request->capa_required_plic){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->capa_required_plic;
                $history->activity_type = 'CAPA Required';
                $history->current = $request->capa_required_plic;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->reference_capa_no_plic != $request->reference_capa_no_plic){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->reference_capa_no_plic;
                $history->activity_type = 'Reference CAPA No';
                $history->current = $request->reference_capa_no_plic;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->delay_justification_for_pi_plic != $request->delay_justification_for_pi_plic){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->delay_justification_for_pi_plic;
                $history->activity_type = 'Delay Justification for Preliminary Investigation';
                $history->current = $request->delay_justification_for_pi_plic;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            // TapV5
            if ($lastOosRecod->review_comments_plir != $request->review_comments_plir){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->review_comments_plir;                
                $history->activity_type = 'Review Comments';
                $history->current = $request->review_comments_plir;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->phase_ii_inv_required_plir != $request->phase_ii_inv_required_plir){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->phase_ii_inv_required_plir;
                $history->activity_type = 'Phase II Inv. Required';
                $history->current = $request->phase_ii_inv_required_plir;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            // TapVI6
            if ($lastOosRecod->qa_approver_comments_piii != $request->qa_approver_comments_piii){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->qa_approver_comments_piii;
                $history->activity_type = 'QA Approver Comments';
                $history->current = $request->qa_approver_comments_piii;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->qa_approver_comments_piii != $request->qa_approver_comments_piii){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->qa_approver_comments_piii;
                $history->activity_type = 'Manufact. Invest. Required?';
                $history->current = $request->qa_approver_comments_piii;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->manufact_invest_required_piii != $request->manufact_invest_required_piii){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->manufact_invest_required_piii;
                $history->activity_type = ' Manufacturing Invest. Type';
                $history->current = $request->manufact_invest_required_piii;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->manufacturing_invest_type_piii != $request->manufacturing_invest_type_piii){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->manufacturing_invest_type_piii;
                $history->activity_type = 'manufacturing_invest_type_piii';
                $history->current = $request->manufacturing_invest_type_piii;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
            }
            if ($lastOosRecod->audit_comments_piii != $request->audit_comments_piii){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->audit_comments_piii;
                $history->activity_type = 'Audit Comments';
                $history->current = $request->audit_comments_piii;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = "Initiator";
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->hypo_exp_required_piii != $request->hypo_exp_required_piii){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->hypo_exp_required_piii;
                $history->activity_type = 'Hypo/Exp. Required';
                $history->current = $request->hypo_exp_required_piii;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->hypo_exp_reference_piii != $request->hypo_exp_reference_piii){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->hypo_exp_reference_piii;
                $history->activity_type = 'Hypo/Exp. Reference';
                $history->current = $request->hypo_exp_reference_piii;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            // TapVIII8
            if ($lastOosRecod->summary_of_exp_hyp_piiqcr != $request->summary_of_exp_hyp_piiqcr){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->summary_of_exp_hyp_piiqcr;
                $history->activity_type = 'Summary of Exp./Hyp.';
                $history->current = $request->summary_of_exp_hyp_piiqcr;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->summary_mfg_investigation_piiqcr != $request->summary_mfg_investigation_piiqcr){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->summary_mfg_investigation_piiqcr;
                $history->activity_type = 'Summary Mfg. Investigation';
                $history->current = $request->summary_mfg_investigation_piiqcr;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->root_casue_identified_piiqcr != $request->root_casue_identified_piiqcr){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->root_casue_identified_piiqcr;
                $history->activity_type = 'Root Casue Identified';
                $history->current = $request->root_casue_identified_piiqcr;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->oos_category_reason_identified_piiqcr != $request->oos_category_reason_identified_piiqcr){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->oos_category_reason_identified_piiqcr;
                $history->activity_type = 'OOS Category-Reason identified';
                $history->current = $request->oos_category_reason_identified_piiqcr;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            
            if ($lastOosRecod->others_oos_category_piiqcr != $request->others_oos_category_piiqcr){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->others_oos_category_piiqcr;
                $history->activity_type = 'Others (OOS category)';
                $history->current = $request->others_oos_category_piiqcr;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->nature_of_change_gi != $request->nature_of_change_gi){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->nature_of_change_gi;
                $history->activity_type = 'Details of Root Cause';
                $history->current = $request->details_of_root_cause_piiqcr;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->impact_assessment_piiqcr != $request->impact_assessment_piiqcr){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->impact_assessment_piiqcr;
                $history->activity_type = 'Impact Assessment.';
                $history->current = $request->impact_assessment_piiqcr;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }

            // ======= Additional Testing Proposal ============
            if ($lastOosRecod->review_comment_atp != $request->review_comment_atp){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->review_comment_atp;
                $history->activity_type = 'Review Comment Atp.';
                $history->current = $request->review_comment_atp;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->additional_test_proposal_atp != $request->additional_test_proposal_atp){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->additional_test_proposal_atp;
                $history->activity_type = 'Additional Test Proposal Atp.';
                $history->current = $request->additional_test_proposal_atp;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->additional_test_reference_atp != $request->additional_test_reference_atp){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->additional_test_reference_atp;
                $history->activity_type = 'additional Test Reference Atp.';
                $history->current = $request->additional_test_reference_atp;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->nature_of_change_gi != $request->nature_of_change_gi){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->nature_of_change_gi;
                $history->activity_type = 'Any Other Actions Required Atp.';
                $history->current = $request->any_other_actions_required_atp;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            // =============== OOS Conclusion  =====================
            if ($lastOosRecod->conclusion_comments_oosc != $request->conclusion_comments_oosc){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->conclusion_comments_oosc;
                $history->activity_type = 'Conclusion Comments.';
                $history->current = $request->conclusion_comments_oosc;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->specification_limit_oosc != $request->specification_limit_oosc){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->specification_limit_oosc;
                $history->activity_type = 'Specification Limit.';
                $history->current = $request->specification_limit_oosc;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->results_to_be_reported_oosc != $request->results_to_be_reported_oosc){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->results_to_be_reported_oosc;
                $history->activity_type = 'Results to be Reported.';
                $history->current = $request->results_to_be_reported_oosc;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->final_reportable_results_oosc != $request->final_reportable_results_oosc){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->final_reportable_results_oosc;
                $history->activity_type = 'Final Reportable Results.';
                $history->current = $request->final_reportable_results_oosc;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            } 
            if ($lastOosRecod->justifi_for_averaging_results_oosc != $request->justifi_for_averaging_results_oosc){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->justifi_for_averaging_results_oosc;
                $history->activity_type = 'Justifi. for Averaging Results.';
                $history->current = $request->justifi_for_averaging_results_oosc;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            } 
            if ($lastOosRecod->oos_stands_oosc != $request->oos_stands_oosc){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->oos_stands_oosc;
                $history->activity_type = 'OOS Stands.';
                $history->current = $request->oos_stands_oosc;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            
            if ($lastOosRecod->reference_record != $request->reference_record){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->reference_record;
                $history->activity_type = 'CAPA Ref No.';
                $history->current = $request->reference_record;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->justify_if_capa_not_required_oosc != $request->justify_if_capa_not_required_oosc){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->justify_if_capa_not_required_oosc;
                $history->activity_type = 'Justify if CAPA not required.';
                $history->current = $request->justify_if_capa_not_required_oosc;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Updade';
                $history->save();
            } 
            if ($lastOosRecod->action_plan_req_oosc != $request->action_plan_req_oosc){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->action_plan_req_oosc;
                $history->activity_type = 'Action Item Req.';
                $history->current = $request->action_plan_req_oosc;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->justification_for_delay_oosc != $request->justification_for_delay_oosc){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->justification_for_delay_oosc;
                $history->activity_type = ' Justification for Delay.';
                $history->current = $request->justification_for_delay_oosc;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            // ========= OOS Conclusion Review ==============
            if ($lastOosRecod->conclusion_review_comments_ocr != $request->conclusion_review_comments_ocr){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->conclusion_review_comments_ocr;                
                $history->activity_type = ' Conclusion Review Comments.';
                $history->current = $request->conclusion_review_comments_ocr;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->action_taken_on_affec_batch_ocr != $request->action_taken_on_affec_batch_ocr){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->action_taken_on_affec_batch_ocr;
                $history->activity_type = 'Action Taken on Affec.batch.';
                $history->current = $request->action_taken_on_affec_batch_ocr;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->capa_req_ocr != $request->capa_req_ocr){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->capa_req_ocr;
                $history->activity_type = 'CAPA Req.';
                $history->current = $request->capa_req_ocr;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->justify_if_no_risk_assessment_ocr != $request->justify_if_no_risk_assessment_ocr){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->justify_if_no_risk_assessment_ocr;
                $history->activity_type = 'Justify if No Risk Assessment';
                $history->current = $request->justify_if_no_risk_assessment_ocr;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->cq_approver != $request->cq_approver){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->cq_approver;
                $history->activity_type = 'CQ Approver';
                $history->current = $request->cq_approver;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            // =========== CQ Review Comments ==========
            if ($lastOosRecod->cq_review_comments_ocqr != $request->cq_review_comments_ocqr){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->cq_review_comments_ocqr;
                $history->activity_type = 'CQ Review comments';
                $history->current = $request->cq_review_comments_ocqr;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = "Initiator";
                $history->action_name = 'Update';
                $history->save();
            }
            //==========  Batch Disposition =============
            if ($lastOosRecod->oos_category_bd != $request->oos_category_bd){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->oos_category_bd;
                $history->activity_type = 'OOS Category';
                $history->current = $request->oos_category_bd;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->others_bd != $request->others_bd){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->others_bd;
                $history->activity_type = 'Other';
                $history->current = $request->others_bd;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
                
            }
            if ($lastOosRecod->material_batch_release_bd != $request->material_batch_release_bd){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->material_batch_release_bd;
                $history->activity_type = 'Material batch release bd';
                $history->current = $request->material_batch_release_bd;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->other_action_bd != $request->other_action_bd){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->other_action_bd;
                $history->activity_type = 'Other Action bd';
                $history->current = $request->other_action_bd;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->other_parameters_results_bd != $request->other_parameters_results_bd){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->other_parameters_results_bd;
                $history->activity_type = 'Other Parameters Results';
                $history->current = $request->other_parameters_results_bd;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->trend_of_previous_batches_bd != $request->trend_of_previous_batches_bd){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->trend_of_previous_batches_bd;
                $history->activity_type = 'Trend of Previous Batches';
                $history->current = $request->trend_of_previous_batches_bd;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->stability_data_bd != $request->stability_data_bd){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->stability_data_bd;
                $history->activity_type = 'Stability Data';
                $history->current = $request->stability_data_bd;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->process_validation_data_bd != $request->process_validation_data_bd){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->process_validation_data_bd;
                $history->activity_type = 'Process Validation Data';
                $history->current = $request->process_validation_data_bd;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->method_validation_bd != $request->method_validation_bd){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->method_validation_bd;
                $history->activity_type = 'Method Validation';
                $history->current = $request->method_validation_bd;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->any_market_complaints_bd != $request->any_market_complaints_bd){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->any_market_complaints_bd;
                $history->activity_type = 'Any Market Complaints';
                $history->current = $request->any_market_complaints_bd;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            
            if ($lastOosRecod->statistical_evaluation_bd != $request->statistical_evaluation_bd){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->statistical_evaluation_bd;
                $history->activity_type = 'Statistical Evaluation Bd';
                $history->current = $request->statistical_evaluation_bd;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            
            if ($lastOosRecod->risk_analysis_disposition_bd != $request->risk_analysis_disposition_bd){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->risk_analysis_disposition_bd;
                $history->activity_type = 'Risk Analysis Disposition_bd';
                $history->current = $request->risk_analysis_disposition_bd;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            
            if ($lastOosRecod->conclusion_bd != $request->conclusion_bd){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->conclusion_bd;
                $history->activity_type = 'Conclusion bd';
                $history->current = $request->conclusion_bd;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            if ($lastOosRecod->justify_for_delay_in_activity_bd != $request->justify_for_delay_in_activity_bd){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->justify_for_delay_in_activity_bd;
                $history->action_name = 'Update';
                $history->activity_type = 'Justify for delay in activity';
                $history->current = $request->justify_for_delay_in_activity_bd;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->save();
            }
            // =============== QA Head/Designee Approval ==========
            if ($lastOosRecod->reopen_approval_comments_uaa != $request->reopen_approval_comments_uaa){
                $history = new OosAuditTrial();
                $history->oos_id = $lastOosRecod->id;
                $history->previous = $lastOosRecod->reopen_approval_comments_uaa;
                $history->activity_type = 'Approval Comments ';
                $history->current = $request->reopen_approval_comments_uaa;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastOosRecod->status;
                $history->stage = $lastOosRecod->stage;
                $history->change_to =   "Opened";
                $history->change_from = $lastOosRecod->status;
                $history->action_name = 'Update';
                $history->save();
            }
            
        
// ============ audit trail update close =================
            $file_input_names = [
                'initial_attachment_gi',
                'file_attachments_pli',
                'file_attachments_pII',
                'supporting_attachment_plic',
                'supporting_attachments_plir',
                'attachments_piiqcr',
                'additional_testing_attachment_atp',
                'file_attachments_if_any_ooscattach',
                'conclusion_attachment_ocr',
                'cq_attachment_ocqr',
                'disposition_attachment_bd',
                'reopen_attachment_ro',
                'addendum_attachment_uaa',
                'addendum_attachments_uae',
                'required_attachment_uar',
                'verification_attachment_uar',
                'hod_attachment1',
                'hod_attachment2',
                'hod_attachment3',
                'hod_attachment4',
                'hod_attachment5',
                'QA_Head_attachment1',
                'QA_Head_attachment2',
                'QA_Head_attachment3',
                'QA_Head_attachment4',
                'QA_Head_attachment5',
                'QA_Head_primary_attachment1',
                'QA_Head_primary_attachment2',
                'QA_Head_primary_attachment3',
                'QA_Head_primary_attachment4',
                'QA_Head_primary_attachment5',
                'provide_attachment1',
                'provide_attachment2',
                'provide_attachment3',
                'provide_attachment4',
                'provide_attachment5',
            ];
            $oos = OOS::findOrFail($id);
            foreach ($file_input_names as $file_input_name)
            {
                // dd($input[$file_input_name]);
                if (empty($request->file($file_input_name)) && !empty($oos[$file_input_name])) {
                    // If the request does not contain file data but existing data is present, retain the existing data
                    $input[$file_input_name] = $oos[$file_input_name];
                } else {
                    // If the request contains file data or existing data is not present, upload new files
                    $input[$file_input_name] = FileService::uploadMultipleFiles($request, $file_input_name);
                }
            
            }

             // Find the OOS record by ID

            $oos->update($input);

            $grid_inputs = [
                'info_product_material',
                'details_stability',
                'oos_detail',
                'checklist_lab_inv',
                'checklist_IB_inv',
                'phase_iii_result_i',
                'phase_iii_result',
                'oos_capa',
                'phase_two_inv',
                'phase_two_inv1',
                'ph_meter',
                'Viscometer',
                'Melting_Point',
                'Dis_solution',
                'HPLC_GC',
                'General_Checklist',
                'kF_Potentionmeter',
                'RM_PM',
                'analyst_training_procedure',
                'sample_receiving_var',
                'method_used_during_analysis',
                'instrument_equipment_detailss',
                'result_and_calculation',
                'Training_records_Analyst_Involved1',
                'sample_intactness_before_analysis1',
                'test_methods_Procedure1',
                'Review_of_Media_Buffer_Standards_prep1',
                'Checklist_for_Revi_of_Media_Buffer_Stand_prep1',
                'ccheck_for_disinfectant_detail1',
                'Checklist_for_Review_of_instrument_equip1',
                'Checklist_for_Review_of_Training_records_Analyst1',
                'Checklist_for_Review_of_sampling_and_Transport1',
                'Checklist_Review_of_Test_Method_proceds1',
                'Checklist_for_Review_Media_prepara_RTU_medias1',
                'Checklist_Review_Environment_condition_in_tests1',
                'review_of_instrument_bioburden_and_waters1',
                'disinfectant_details_of_bioburden_and_water_tests1',
                'training_records_analyst_involvedIn_testing_microbial_asssays1',
                'sample_intactness_before_analysis22',
                'checklist_for_review_of_test_method_IMA1',
                'cr_of_media_buffer_st_IMA1',
                'CR_of_microbial_cultures_inoculation_IMA1',
                'CR_of_Environmental_condition_in_testing_IMA1',
                'CR_of_instru_equipment_IMA1',
                'disinfectant_details_IMA1',
                'CR_of_training_rec_anaylst_in_monitoring_CIEM1',
                'Check_for_Sample_details_CIEM1',
                'Check_for_comparision_of_results_CIEM1',
                'checklist_for_media_dehydrated_CIEM1',
                'checklist_for_media_prepara_sterilization_CIEM1',
                'CR_of_En_condition_in_testing_CIEM1',
                'check_for_disinfectant_CIEM1',
                'checklist_for_fogging_CIEM1',
                'CR_of_test_method_CIEM1',
                'CR_microbial_isolates_contamination_CIEM1',
                'CR_of_instru_equip_CIEM1',
                'Ch_Trend_analysis_CIEM1',
                'checklist_for_analyst_training_CIMT2',
                'checklist_for_comp_results_CIMT2',
                'checklist_for_Culture_verification_CIMT2',
                'sterilize_accessories_CIMT2',
                'checklist_for_intrument_equip_last_CIMT2',
                'disinfectant_details_last_CIMT2',
                'checklist_for_result_calculation_CIMT2',
                'oos_conclusion',
                'oos_conclusion_review',
                'products_details',
                'instrument_detail',
            ];

            foreach ($grid_inputs as $grid_input)
            {
                self::update_grid($oos, $request, $grid_input);
            }

           
           
        //    update audit trail
            $res['body'] = $oos;

        } catch (\Exception $e) {
            $res['status'] = 'error';
            $res['message'] = $e->getMessage();
        }

        return $res;
        
    }

    public static function update_grid(OOS $oos, Request $request, $identifier)
    {
        $res = Helpers::getDefaultResponse();
        
        try {

            $oos_grid = Oosgrids::where([ 'identifier' => $identifier, 'oos_id' => $oos->id  ])->firstOrNew();
            $oos_grid->oos_id = $oos->id;
            $oos_grid->identifier = $identifier;
            $oos_grid->data = $request->$identifier;
            $oos_grid->update();
            
        } catch (\Exception $e) {
            $res['status'] = 'error';
            $res['message'] = $e->getMessage();
            info('Error in OOSService@update_grid', [
                'message' => $e->getMessage()
            ]);
        }

        return $res;
    }

}