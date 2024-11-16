<?php

namespace App\Http\Controllers\rcms;

use App\Http\Controllers\Controller;
use App\Models\Capa;
use App\Models\CapaAuditTrial;
use App\Models\CapaGrid;
use App\Models\CapaHistory;
use App\Models\CC;
use App\Models\Deviation;
use App\Models\Extension;
use App\Models\OpenStage;
use App\Models\RecordNumber;
use App\Models\RoleGroup;
use App\Models\User;
use Carbon\Carbon;
use Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use PDF;

class CapaController extends Controller
{

    public function capa()
    {
        $cft = [];
        $old_record = Capa::select('id', 'division_id', 'record')->get();
        $record_number = ((RecordNumber::first()->value('counter')) + 1);
        $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->addDays(30);
        $due_date = $formattedDate->format('Y-m-d');
        $changeControl = OpenStage::find(1);
        if (!empty($changeControl->cft)) $cft = explode(',', $changeControl->cft);
        return view("frontend.forms.capa", compact('due_date', 'record_number', 'old_record', 'cft'));
    }

    public function capastore(Request $request)
    {
        // return $request;

        if (!$request->short_description) {
            toastr()->error("Short description is required");
            return redirect()->back();
        }
        $capa = new Capa();
        $capa->form_type = "CAPA";
        $capa->record = ((RecordNumber::first()->value('counter')) + 1);
        $capa->initiator_id = Auth::user()->id;
        $capa->division_id = $request->division_id;
        $capa->parent_id = $request->parent_id;
        $capa->parent_type = $request->parent_type;
        $capa->division_code = $request->division_code;
        $capa->intiation_date = $request->intiation_date;
        $capa->general_initiator_group = $request->initiator_group;
        $capa->due_date = $request->due_date;
        $capa->short_description = $request->short_description;
        $capa->source_of_capa = $request->source_of_capa;
        $capa->others = $request->others;
        $capa->source_document_name = $request->source_document_name;
        $capa->comments_cloasure = $request->comments_cloasure;
        $capa->head_quality = $request->head_quality;
        $capa->justification = $request->justification;
        $capa->effectiveness_verification_capa = $request->effectiveness_verification_capa;
        $capa->effectivenessRemark = $request->effectivenessRemark;
        $capa->problem_description = $request->problem_description;
        $capa->problem_description = $request->problem_description;
        $capa->problem_description = $request->problem_description;


        $capa->status = 'Opened';
        $capa->stage = 1;
        $capa->save();



        $record = RecordNumber::first();
        $record->counter = ((RecordNumber::first()->value('counter')) + 1);
        $record->update();





        // Define the fields and their respective activity types
        $fields = [
            'record_number' => 'CAPA No',
            'division_id' => 'Site/Location Code',
            'division_code' => 'Initiator',
            'intiation_date' => 'Date of Initiation',
            'short_description' => 'Short Description',
            'initiator_group' => 'Department',
            'initiator_group_code' => 'Department Group Code',
            'source_of_capa' => 'Source of CAPA',
            'others' => 'Others',
            'source_document_name' => 'Source Document Name / No',
            'comments_cloasure' => 'Closure of the CAPA',
            'head_quality' => 'Head Quality / Designee',
            'justification' => 'Justification',
            'effectiveness_verification_capa' => 'Effectiveness Verification Capa',
            'effectivenessRemark' => 'Remark',
        ];

        // Loop through each field and create an audit trail entry if the field is not empty
        foreach ($fields as $field => $activity_type) {
            // Check if the field is not empty
            if (!empty($capa->$field)) {
                $history = new CapaAuditTrial();
                $history->capa_id = $capa->id;
                $history->activity_type = $activity_type;
                $history->previous = "Null";
                $history->current = $capa->$field;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $capa->status;
                $history->change_to = "Opened";
                $history->change_from = "Initiation";
                $history->action_name = "Create";
                $history->save();
            }
        }







        // ======================Gird data store=============
        $griddata = $capa->id;
        // Store data for Proposed Corrective Action grid
        $proposedCorrectiveActionData = CapaGrid::where(['capa_id' => $griddata, 'identifer' => 'ProposedCorrectiveAction'])->firstOrNew();
        $proposedCorrectiveActionData->capa_id = $griddata;
        $proposedCorrectiveActionData->identifer = 'ProposedCorrectiveAction';
        $proposedCorrectiveActionData->data = $request->corrective_action_details;
        $proposedCorrectiveActionData->save();
        // dd($proposedCorrectiveActionData);
        // Store data for Proposed Preventive Action grid (new code)
        $proposedPreventiveActionData = CapaGrid::where(['capa_id' => $griddata, 'identifer' => 'ProposedPreventiveAction'])->firstOrNew();
        $proposedPreventiveActionData->capa_id = $griddata;
        $proposedPreventiveActionData->identifer = 'ProposedPreventiveAction';
        $proposedPreventiveActionData->data = $request->preventive_action_details;
        $proposedPreventiveActionData->save();

        // Store data for Implementation of Corrective Action grid
        $implementationCorrectiveActionData = CapaGrid::where(['capa_id' => $griddata, 'identifer' => 'ImplementationCorrectiveAction'])->firstOrNew();
        $implementationCorrectiveActionData->capa_id = $griddata;
        $implementationCorrectiveActionData->identifer = 'ImplementationCorrectiveAction';
        $implementationCorrectiveActionData->data = $request->implementation_corrective_action_details;
        $implementationCorrectiveActionData->save();

        // dd($proposedPreventiveActionData);



        toastr()->success("Record is created Successfully");
        return redirect(url('rcms/qms-dashboard'));
    }
    public function capaUpdate(Request $request, $id)
    {
        if (!$request->short_description) {
            toastr()->error("Short description is required");
            return redirect()->back();
        }
        $lastDocument = Capa::find($id);
        $capa = Capa::find($id);
        $capa->parent_id = $request->parent_id;
        $capa->parent_type = $request->parent_type;
        $capa->division_code = $request->division_code;
        $capa->intiation_date = $request->intiation_date;
        $capa->general_initiator_group = $request->initiator_group;
        $capa->due_date = $request->due_date;
        $capa->short_description = $request->short_description;
        // $capa->problem_description = $request->problem_description;
        $capa->source_of_capa = $request->source_of_capa;
        $capa->others = $request->others;
        $capa->source_document_name = $request->source_document_name;
        $capa->comments_cloasure = $request->comments_cloasure;
        $capa->head_quality = $request->head_quality;
        $capa->justification = $request->justification;
        $capa->effectiveness_verification_capa = $request->effectiveness_verification_capa;
        $capa->effectivenessRemark = $request->effectivenessRemark;



        $capa->update();
        // dd($capa->due_date);



        // -------audit trrail show start update -----------
        $fields = [
           
            'short_description' => 'Short Description',
            'initiator_group' => 'Department',
            'initiator_group_code' => 'Department Group Code',
            'source_of_capa' => 'Source of CAPA',
            'others' => 'Others',
            'source_document_name' => 'Source Document Name / No',
            'comments_cloasure' => 'Closure of the CAPA',
            'head_quality' => 'Head Quality / Designee',
            'justification' => 'Justification',
            'effectiveness_verification_capa' => 'Effectiveness Verification of CAPA',
            'effectivenessRemark' => 'Remark',
        


        ];
        foreach ($fields as $field => $activity_type) {
            $previous = $lastDocument->$field ?? '';
            $current = $capa->$field ?? '';
        
            if (trim($previous) !== trim($current) || !empty($request->comment)) {
                $history = new CapaAuditTrial;
                $history->capa_id = $id;
                $history->activity_type = $activity_type;
                $history->previous = $previous;
                $history->current = $current;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->change_to = "Not Applicable";
                $history->change_from = $lastDocument->status;
                $history->action_name = 'Update';
                $history->save();
            }
        }
        


        // =============================Capa grid update==============

        //             $correctiveActionData = CapaGrid::where(['capa_id' => $griddata, 'identifer' => 'ProposedCorrectiveAction'])->firstOrNew();
        //             $correctiveActionData->capa_id = $griddata;
        //             $correctiveActionData->identifer = 'ProposedCorrectiveAction';
        //             $correctiveActionData->data = $request->ProposedCorrectiveAction;
        // // dd($correctiveActionData->data);
        //    $correctiveActionData->save();
        $griddata = $capa->id;

        // Store data for Proposed Corrective Action grid
        $proposedCorrectiveActionData = CapaGrid::where(['capa_id' => $griddata, 'identifer' => 'ProposedCorrectiveAction'])->firstOrNew();
        $proposedCorrectiveActionData->capa_id = $griddata;
        $proposedCorrectiveActionData->identifer = 'ProposedCorrectiveAction';
        $proposedCorrectiveActionData->data = $request->corrective_action_details;
        $proposedCorrectiveActionData->update();

        // Store data for Proposed Preventive Action grid
        $proposedPreventiveActionData = CapaGrid::where(['capa_id' => $griddata, 'identifer' => 'ProposedPreventiveAction'])->firstOrNew();
        $proposedPreventiveActionData->capa_id = $griddata;
        $proposedPreventiveActionData->identifer = 'ProposedPreventiveAction';
        $proposedPreventiveActionData->data = $request->preventive_action_details;
        $proposedPreventiveActionData->update();

        $implementationCorrectiveActionData = CapaGrid::where(['capa_id' => $griddata, 'identifer' => 'ImplementationCorrectiveAction'])->firstOrNew();
        $implementationCorrectiveActionData->capa_id = $griddata;
        $implementationCorrectiveActionData->identifer = 'ImplementationCorrectiveAction';
        $implementationCorrectiveActionData->data = $request->implementation_corrective_action_details;
        $implementationCorrectiveActionData->update();


        toastr()->success("Record is updated Successfully");
        return back();
    }

    public function capashow($id)
    {
        $cft = [];
        $revised_date = "";
        $data = Capa::find($id);
    //    dd($data->due_date);

        $old_record = Capa::select('id', 'division_id', 'record')->get();
        $revised_date = Extension::where('parent_id', $id)->where('parent_type', "Capa")->value('revised_date');
        $data->record = str_pad($data->record, 4, '0', STR_PAD_LEFT);
        $data->assign_to_name = User::where('id', $data->assign_id)->value('name');
        $data->initiator_name = User::where('id', $data->initiator_id)->value('name');
        $changeControl = OpenStage::find(1);
        if (!empty($changeControl->cft)) $cft = explode(',', $changeControl->cft);

        // Retrieve data for Proposed Corrective Action grid
        $proposedCorrectiveActionData = CapaGrid::where('capa_id', $id)
            ->where('identifer', 'ProposedCorrectiveAction')
            ->first();
        $proposedPreventiveActionData = CapaGrid::where('capa_id', $id)
            ->where('identifer', 'ProposedPreventiveAction')
            ->first();
        $implementationCorrectiveActionData = CapaGrid::where('capa_id', $id)
            ->where('identifer', 'ImplementationCorrectiveAction')
            ->first();
        // dd($implementationCorrectiveActionData);


        return view('frontend.capa.capaView', compact('data', 'proposedCorrectiveActionData', 'proposedPreventiveActionData', 'implementationCorrectiveActionData', 'old_record', 'revised_date', 'cft'));
    }


    public function capa_send_stage(Request $request, $id)
    {


        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $capa = Capa::find($id);
            $lastDocument = Capa::find($id);
            if ($capa->stage == 1) {
                $capa->stage = "2";
                $capa->status = "Pending CAPA Plan";
                $capa->plan_proposed_by = Auth::user()->name;
                $capa->plan_proposed_on = Carbon::now()->format('d-M-Y');

                $history = new CapaAuditTrial();
                $history->capa_id = $id;
                $history->activity_type = 'Activity Log';
                $history->previous = "";
                $history->current = $capa->plan_proposed_by;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = 'Plan Proposed';
                $history->save();

                $list = Helpers::getHodUserList();
                foreach ($list as $u) {
                    if ($u->q_m_s_divisions_id == $capa->division_id) {
                        $email = Helpers::getInitiatorEmail($u->user_id);
                        if ($email !== null) {
                            try {
                                Mail::send(
                                    'mail.view-mail',
                                    ['data' => $capa],
                                    function ($message) use ($email) {
                                        $message->to($email)
                                            ->subject("Document is Submitted By " . Auth::user()->name);
                                    }
                                );
                            } catch (\Exception $e) {
                                // 
                            }
                        }
                    }
                }

                $capa->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($capa->stage == 2) {
                $capa->stage = "3";
                $capa->status = "CAPA In Progress";
                $capa->plan_approved_by = Auth::user()->name;
                $capa->plan_approved_on = Carbon::now()->format('d-M-Y');

                $history = new CapaAuditTrial();
                $history->capa_id = $id;
                $history->activity_type = 'Activity Log';
                $history->previous = "";
                $history->current = $capa->plan_approved_by;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = 'Plan Approved';
                $history->save();

                $list = Helpers::getQAUserList();
                foreach ($list as $u) {
                    if ($u->q_m_s_divisions_id == $capa->division_id) {
                        $email = Helpers::getInitiatorEmail($u->user_id);
                        if ($email !== null) {
                            try {
                                Mail::send(
                                    'mail.view-mail',
                                    ['data' => $capa],
                                    function ($message) use ($email) {
                                        $message->to($email)
                                            ->subject("Plan Approved By " . Auth::user()->name);
                                    }
                                );
                            } catch (\Exception $e) {
                                // 
                            }
                        }
                    }
                }

                $capa->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($capa->stage == 3) {
                $capa->stage = "4";
                $capa->status = "QA Review";
                $capa->completed_by = Auth::user()->name;
                $capa->completed_on = Carbon::now()->format('d-M-Y');
                $history = new CapaAuditTrial();
                $history->capa_id = $id;
                $history->activity_type = 'Activity Log';
                $history->previous = "";
                $history->current = $capa->completed_by;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = 'Completed';
                $history->save();
                $capa->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($capa->stage == 4) {
                $capa->stage = "5";
                $capa->status = "Pending Actions Completion";
                $capa->approved_by = Auth::user()->name;
                $capa->approved_on = Carbon::now()->format('d-M-Y');
                $history = new CapaAuditTrial();
                $history->capa_id = $id;
                $history->activity_type = 'Activity Log';
                $history->previous = "";
                $history->current = $capa->approved_by;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = 'Approved';
                $history->save();
                $capa->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($capa->stage == 5) {
                $capa->stage = "6";
                $capa->status = "Pending Actions Completion";
                $capa->approved_by = Auth::user()->name;
                $capa->approved_on = Carbon::now()->format('d-M-Y');
                $history = new CapaAuditTrial();
                $history->capa_id = $id;
                $history->activity_type = 'Activity Log';
                $history->previous = "";
                $history->current = $capa->approved_by;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = 'Approved';
                $history->save();
                $capa->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($capa->stage == 6) {
                $capa->stage = "7";
                $capa->status = "Pending Actions Completion";
                $capa->approved_by = Auth::user()->name;
                $capa->approved_on = Carbon::now()->format('d-M-Y');
                $history = new CapaAuditTrial();
                $history->capa_id = $id;
                $history->activity_type = 'Activity Log';
                $history->previous = "";
                $history->current = $capa->approved_by;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = 'Approved';
                $history->save();
                $capa->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($capa->stage == 7) {
                $capa->stage = "8";
                $capa->status = "Pending Actions Completion";
                $capa->approved_by = Auth::user()->name;
                $capa->approved_on = Carbon::now()->format('d-M-Y');
                $history = new CapaAuditTrial();
                $history->capa_id = $id;
                $history->activity_type = 'Activity Log';
                $history->previous = "";
                $history->current = $capa->approved_by;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = 'Approved';
                $history->save();
                $capa->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($capa->stage == 8) {
                $capa->stage = "9";
                $capa->status = "Pending Actions Completion";
                $capa->approved_by = Auth::user()->name;
                $capa->approved_on = Carbon::now()->format('d-M-Y');
                $history = new CapaAuditTrial();
                $history->capa_id = $id;
                $history->activity_type = 'Activity Log';
                $history->previous = "";
                $history->current = $capa->approved_by;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = 'Approved';
                $history->save();
                $capa->update();
                toastr()->success('Document Sent');
                return back();
            }

            if ($capa->stage == 9) {
                $capa->stage = "10";
                $capa->status = "Closed - Done";
                $capa->completed_by = Auth::user()->name;
                $capa->completed_on = Carbon::now()->format('d-M-Y');
                $history = new CapaAuditTrial();
                $history->capa_id = $id;
                $history->activity_type = 'Activity Log';
                $history->previous = "";
                $history->current = $capa->completed_by;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = 'Completed';
                $history->save();
                $capa->update();
                toastr()->success('Document Sent');
                return back();
            }
        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }


    public function capaCancel(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $capa = Capa::find($id);
            $lastDocument = Capa::find($id);


            $capa->stage = "0";
            $capa->status = "Closed-Cancelled";
            $capa->cancelled_by = Auth::user()->name;
            $capa->cancelled_on = Carbon::now()->format('d-M-Y');
            $history = new CapaAuditTrial();
            $history->capa_id = $id;
            $history->activity_type = 'Activity Log';
            $history->previous = "";
            $history->current = $capa->cancelled_by;
            $history->comment = $request->comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state =  $capa->status;
            $history->stage = 'Cancelled';
            $history->save();
            $capa->update();
            $history = new CapaHistory();
            $history->type = "Capa";
            $history->doc_id = $id;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->stage_id = $capa->stage;
            $history->status = $capa->status;
            $history->save();

            $list = Helpers::getInitiatorUserList();
            foreach ($list as $u) {
                if ($u->q_m_s_divisions_id == $capa->division_id) {
                    $email = Helpers::getInitiatorEmail($u->user_id);
                    if ($email !== null) {
                        try {
                            Mail::send(
                                'mail.view-mail',
                                ['data' => $capa],
                                function ($message) use ($email) {
                                    $message->to($email)
                                        ->subject("Cancelled By " . Auth::user()->name);
                                }
                            );
                        } catch (\Exception $e) {
                            // 
                        }
                    }
                }
            }

            toastr()->success('Document Sent');
            return back();
        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }

    public function capa_qa_more_info(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $capa = Capa::find($id);
            $lastDocument = Capa::find($id);


            if ($capa->stage == 3) {
                $capa->stage = "2";
                $capa->status = "Pending CAPA Plan";
                $capa->qa_more_info_required_by = Auth::user()->name;
                $capa->qa_more_info_required_on = Carbon::now()->format('d-M-Y');
                $history = new CapaAuditTrial();
                $history->capa_id = $id;
                $history->activity_type = 'Activity Log';
                $history->previous = "";
                $history->current = $capa->qa_more_info_required_by;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = 'Qa More Info Required';
                $history->save();
                $capa->update();
                $history = new CapaHistory();
                $history->type = "Capa";
                $history->doc_id = $id;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->stage_id = $capa->stage;
                $history->status = $capa->status;
                $history->save();
                $list = Helpers::getHodUserList();
                foreach ($list as $u) {
                    if ($u->q_m_s_divisions_id == $capa->division_id) {
                        $email = Helpers::getInitiatorEmail($u->user_id);
                        if ($email !== null) {
                            try {
                                Mail::send(
                                    'mail.view-mail',
                                    ['data' => $capa],
                                    function ($message) use ($email) {
                                        $message->to($email)
                                            ->subject("Document is Send By " . Auth::user()->name);
                                    }
                                );
                            } catch (\Exception $e) {
                                // 
                            }
                        }
                    }
                }
                toastr()->success('Document Sent');
                return back();
            }

            if ($capa->stage == 4) {
                $capa->stage = "3";
                $capa->status = "CAPA In Progress";
                $capa->rejected_by = Auth::user()->name;
                $capa->rejected_on = Carbon::now()->format('d-M-Y');
                $history = new CapaAuditTrial();
                $history->capa_id = $id;
                $history->activity_type = 'Activity Log';
                $history->previous = "";
                $history->current = $capa->rejected_by;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = 'Rejected';
                $history->save();
                $capa->update();
                $history = new CapaHistory();
                $history->type = "Capa";
                $history->doc_id = $id;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->stage_id = $capa->stage;
                $history->status = $capa->status;
                $history->save();
                toastr()->success('Document Sent');
                return back();
            }
        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }

    public function capa_reject(Request $request, $id)
    {

        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $capa = Capa::find($id);
            $lastDocument = Capa::find($id);
            if ($capa->stage == 2) {
                $capa->stage = "1";
                $capa->status = "Opened";
                // $capa->rejected_by = Auth::user()->name;
                // $capa->rejected_on = Carbon::now()->format('d-M-Y');
                $capa->update();
                $history = new CapaHistory();
                $history->type = "Capa";
                $history->doc_id = $id;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->stage_id = $capa->stage;
                $history->status = "Opened";
                $list = Helpers::getInitiatorUserList();
                foreach ($list as $u) {
                    if ($u->q_m_s_divisions_id == $capa->division_id) {
                        $email = Helpers::getInitiatorEmail($u->user_id);
                        if ($email !== null) {
                            try {
                                Mail::send(
                                    'mail.view-mail',
                                    ['data' => $capa],
                                    function ($message) use ($email) {
                                        $message->to($email)
                                            ->subject("More Info Required " . Auth::user()->name);
                                    }
                                );
                            } catch (\Exception $e) {
                                // 
                            }
                        }
                    }
                }
                $history->save();

                toastr()->success('Document Sent');
                return back();
            }
            if ($capa->stage == 3) {
                $capa->stage = "2";
                $capa->status = "Pending CAPA Plan";
                $capa->qa_more_info_required_by = Auth::user()->name;
                $capa->qa_more_info_required_on = Carbon::now()->format('d-M-Y');
                $history = new CapaAuditTrial();
                $history->capa_id = $id;
                $history->activity_type = 'Activity Log';
                $history->previous = "";
                $history->current = $capa->qa_more_info_required_by;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = 'Qa More Info Required';
                $history->save();
                $capa->update();
                $history = new CapaHistory();
                $history->type = "Capa";
                $history->doc_id = $id;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->stage_id = $capa->stage;
                $history->status = "Pending CAPA Plan<";
                $history->save();
                toastr()->success('Document Sent');
                return back();
            }
        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }



    // public function CapaAuditTrial($id)
    // {
    //     $audit = CapaAuditTrial::where('capa_id', $id)->orderByDESC('id')->get()->unique('activity_type');
    //     $today = Carbon::now()->format('d-m-y');
    //     $document = Capa::where('id', $id)->first();
    //     $document->initiator = User::where('id', $document->initiator_id)->value('name');


    //     // return $audit;

    //     return view('frontend.capa.audit-trial', compact('audit', 'document', 'today'));
    // }
    public function CapaAuditTrial($id)
    {
        // dd("test");
        $audit = CapaAuditTrial::where('capa_id', $id)
            ->orderByDesc('id')
            ->paginate(10) // Change to paginate
        ;

        $today = Carbon::now()->format('d-m-y');
        $document = Capa::where('id', $id)->first();
        $document->initiator = User::where('id', $document->initiator_id)->value('name');
        $users = User::all();
        // dd($document );

        return view('frontend.capa.audit-trial', compact('audit', 'document', 'today', 'users'));
    }

    public function auditDetailsCapa($id)
    {

        $detail = CapaAuditTrial::find($id);

        $detail_data = CapaAuditTrial::where('activity_type', $detail->activity_type)->where('capa_id', $detail->capa_id)->latest()->get();

        $doc = Capa::where('id', $detail->capa_id)->first();

        $doc->origiator_name = User::find($doc->initiator_id);
        return view('frontend.capa.audit-trial-inner', compact('detail', 'doc', 'detail_data'));
    }

    public function child_change_control(Request $request, $id)
    {
        $cft = [];
        $parent_id = $id;
        $parent_record = $request->parent_record;

        $parent_type = "Audit_Program";
        $record_number = ((RecordNumber::first()->value('counter')) + 1);
        $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->addDays(30);
        $due_date = $formattedDate->format('d-M-Y');
        $parent_record = Capa::where('id', $id)->value('record');
        $parent_record = str_pad($parent_record, 4, '0', STR_PAD_LEFT);
        $parent_division_id = Capa::where('id', $id)->value('division_id');
        $parent_initiator_id = Capa::where('id', $id)->value('initiator_id');
        $parent_intiation_date = Capa::where('id', $id)->value('intiation_date');
        $parent_short_description = Capa::where('id', $id)->value('short_description');
        $hod = User::where('role', 4)->get();
        $pre = CC::all();
        $changeControl = OpenStage::find(1);
        if (!empty($changeControl->cft)) $cft = explode(',', $changeControl->cft);
        // return $capa_data;
        if ($request->child_type == "Change_control") {
            $record_number = ((RecordNumber::first()->value('counter')) + 1);
            $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
            $parent_name = "CAPA";
            $Changecontrolchild = Deviation::find($id);
            $Changecontrolchild->Changecontrolchild = $record_number;
            $parent_id = $id;

            $Changecontrolchild->save();

            return view('frontend.change-control.new-change-control', compact('cft', 'parent_record', 'parent_record', 'pre', 'hod', 'parent_short_description', 'parent_initiator_id', 'parent_intiation_date', 'parent_division_id', 'parent_record', 'record_number', 'due_date', 'parent_id', 'parent_type'));
        }
        if ($request->child_type == "extension") {
            $parent_due_date = "";
            $parent_id = $id;
            $parent_name = $request->parent_name;
            if ($request->due_date) {
                $parent_due_date = $request->due_date;
            }
            $parent_id = $id;

            $record_number = ((RecordNumber::first()->value('counter')) + 1);
            $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
            return view('frontend.forms.extension', compact('parent_id', 'parent_record', 'parent_name', 'record_number', 'parent_due_date'));
        }
        $old_record = Capa::select('id', 'division_id', 'record')->get();
        if ($request->child_type == "Action_Item") {
            $record_number = ((RecordNumber::first()->value('counter')) + 1);
            $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
            $parent_name = "CAPA";
            $actionchild = Deviation::find($id);
            $actionchild->actionchild = $record_number;
            $parent_id = $id;

            $actionchild->save();
            return view('frontend.forms.action-item', compact('old_record', 'parent_short_description', 'parent_initiator_id', 'parent_intiation_date', 'parent_name', 'parent_division_id', 'parent_record', 'record_number', 'due_date', 'parent_id', 'parent_type'));
        } else {
            $record_number = ((RecordNumber::first()->value('counter')) + 1);
            $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
            $parent_name = "CAPA";
            $effectivenesschild = Deviation::find($id);
            $effectivenesschild->effectivenesschild = $record_number;
            $parent_id = $id;
            $effectivenesschild->save();
            //  dd($effectivenesschild);
            return view('frontend.forms.effectiveness-check', compact('old_record', 'parent_short_description', 'parent_initiator_id', 'parent_intiation_date', 'parent_division_id', 'parent_record', 'record_number', 'due_date', 'parent_id', 'parent_type'));
        }
    }

    public function effectiveness_check(Request $request, $id)
    {
        $record_number = ((RecordNumber::first()->value('counter')) + 1);
        $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
        $currentDate = Carbon::now();
        dd($currentDate);
        $formattedDate = $currentDate->addDays(30);
        $due_date = $formattedDate->format('Y-m-d');
        return view("frontend.forms.effectiveness-check", compact('due_date', 'record_number'));
    }


    public static function singleReport($id)
    {
        $data = Capa::find($id);
        if (!empty($data)) {
            $data->originator = User::where('id', $data->initiator_id)->value('name');

            // Get the proposed corrective action data
            $proposedCorrectiveActionData = CapaGrid::where([
                'capa_id' => $id,
                'identifer' => 'ProposedCorrectiveAction'
            ])->first();
            $proposedPreventiveActionData = CapaGrid::where([
                'capa_id' => $id,
                'identifer' => 'ProposedPreventiveAction'
            ])->first();
            // $implementationCorrectiveActionData = CapaGrid::where([
            //     'capa_id' => $id,
            //     'identifer' => 'ImplementationCorrectiveAction'
            // ])->first();

            // Decode the JSON data if it is a JSON string, or leave it as an array
            $correctiveActions = [];
            if ($proposedCorrectiveActionData && is_string($proposedCorrectiveActionData->data)) {
                // Decode if it is a string
                $correctiveActions = json_decode($proposedCorrectiveActionData->data, true);
            } elseif ($proposedCorrectiveActionData && is_array($proposedCorrectiveActionData->data)) {
                // If the data is already an array, use it as it is
                $correctiveActions = $proposedCorrectiveActionData->data;
            }

            $preventiveActions = [];
            if ($proposedPreventiveActionData && is_string($proposedPreventiveActionData->data)) {
                // Decode if it is a string
                $preventiveActions = json_decode($proposedPreventiveActionData->data, true);
            } elseif ($proposedPreventiveActionData && is_array($proposedPreventiveActionData->data)) {
                // If the data is already an array, use it as it is
                $preventiveActions = $proposedPreventiveActionData->data;
            }

            // $implementationcorrectiveActions = [];
            // if ($implementationCorrectiveActionData && is_string($implementationCorrectiveActionData->data)) {
            //     // Decode if it is a string
            //     $preventiveActions = json_decode($implementationCorrectiveActionData->data, true);
            // } elseif ($implementationCorrectiveActionData && is_array($implementationCorrectiveActionData->data)) {
            //     // If the data is already an array, use it as it is
            //     $preventiveActions = $implementationCorrectiveActionData->data;
            // }

            // Debugging to check if the correctiveActions data is correct
            // Uncomment below line if you want to debug
            // dd($correctiveActions);

            // Generate the PDF with the data
            $pdf = App::make('dompdf.wrapper');
            $pdf = PDF::loadview('frontend.capa.singleReport', compact('data', 'correctiveActions', 'preventiveActions'))
                ->setOptions([
                    'defaultFont' => 'sans-serif',
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                    'isPhpEnabled' => true,
                ]);

            $pdf->setPaper('A4');
            return $pdf->stream('Deviation' . $id . '.pdf');
        }
    }





    public static function auditReport($id)
    {
        $doc = Capa::find($id);
        if (!empty($doc)) {
            $doc->originator = User::where('id', $doc->initiator_id)->value('name');
            $data = CapaAuditTrial::where('capa_id', $id)->get();
            $pdf = App::make('dompdf.wrapper');
            $time = Carbon::now();
            $pdf = PDF::loadview('frontend.capa.auditReport', compact('data', 'doc'))
                ->setOptions([
                    'defaultFont' => 'sans-serif',
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                    'isPhpEnabled' => true,
                ]);
            $pdf->setPaper('A4');
            $pdf->render();
            $canvas = $pdf->getDomPDF()->getCanvas();
            $height = $canvas->get_height();
            $width = $canvas->get_width();
            $canvas->page_script('$pdf->set_opacity(0.1,"Multiply");');
            $canvas->page_text($width / 4, $height / 2, $doc->status, null, 25, [0, 0, 0], 2, 6, -20);
            return $pdf->stream('CAPA-Audit' . $id . '.pdf');
        }
    }
}
