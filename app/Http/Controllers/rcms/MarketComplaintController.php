<?php

namespace App\Http\Controllers\rcms;

use App\Http\Controllers\Controller;
use App\Models\MarketComplaint;
use App\Models\MarketComplaintAuditTrial;
use App\Models\MarketComplaintGrids;
use App\Models\RecordNumber;
use App\Models\RoleGroup;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PDF;
use Helpers;




class MarketComplaintController extends Controller
{
    public function marketcomplaint()
    {
        $old_record = MarketComplaint::select('id', 'division_id', 'record')->get();
        $record_number = (RecordNumber::first()->value('counter')) + 1;
        $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->addDays(30);
        $due_date = $formattedDate->format('d-M-Y');
        $pre = MarketComplaint::all();
        return response()->view('frontend.marketcomplaint.marketcomplaint', compact('record_number', 'formattedDate', 'due_date', 'old_record', 'pre'));
    }

    public function store(Request $request)
    {
        $form_progress = null; // initialize form progress

        if ($request->form_name == 'general')
        {
            $validator = Validator::make($request->all(), [
                'Initiator_Group' => 'required',
                'short_description' => 'required'

            ], [
                'Initiator_Group.required' => 'Department field required!',
                'short_description_required.required' => 'Nature of repeat field required!'
            ]);

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            } else {
                $form_progress = 'general';
            }
        }


        if (!$request->short_description) {
            toastr()->error("Short description is required");
            return response()->redirect()->back()->withInput();
        }

        $marketcomplaint = new MarketComplaint();
        $marketcomplaint->form_type = "MarketComplaint";

        $marketcomplaint->record = ((RecordNumber::first()->value('counter')) + 1);
        $marketcomplaint->initiator_id = Auth::user()->id;

        $marketcomplaint->record_number = $request->input('record_number');
        $marketcomplaint->division_code = $request->input('division_code');
        $marketcomplaint->division_id = $request->input('division_id');
        $marketcomplaint->initiator = $request->input('initiator');
        $marketcomplaint->initiation_date = $request->input('initiation_date');
        $marketcomplaint->assign_to = $request->input('assign_to');
        $marketcomplaint->due_date = $request->input('due_date');
        $marketcomplaint->initiator_group = $request->input('initiator_group');
        $marketcomplaint->initiator_group_code = $request->input('initiator_group_code');
        $marketcomplaint->short_description = $request->input('short_description');
        $marketcomplaint->nameAddressagency = $request->input('nameAddressagency');
        $marketcomplaint->nameDesgnationCom = $request->input('nameDesgnationCom');
        $marketcomplaint->phone_no = $request->input('phone_no');
        $marketcomplaint->email_address = $request->input('email_address');
        $marketcomplaint->sample_recd = $request->input('sample_recd');
        $marketcomplaint->test_results_recd = $request->input('test_results_recd');
        $marketcomplaint->severity_level_form = $request->input('severity_level_form');
        $marketcomplaint->acknowledgment_sent = $request->input('acknowledgment_sent');
        $marketcomplaint->analysis_physical_examination = $request->input('analysis_physical_examination');
       
        $marketcomplaint->identification_cross_functional = $request->input('Identification_Cross_functional');
        $marketcomplaint->preliminary_investigation_report = $request->input('Preliminary_Investigation_Report');
        $marketcomplaint->further_response_received = $request->input('Further_Response_Received');
        $marketcomplaint->details_of_response = $request->input('Details_of_Response');
        $marketcomplaint->further_investigation_additional_testing = $request->input('Further_investigation_Additional_testing');
        $marketcomplaint->method_tools_to_be_used_for = $request->input('Method_Tools_to_be_used_for');
    
        $marketcomplaint->type="MarketComplaint";
        $marketcomplaint->stage=1;
        $marketcomplaint->status = "Opened";

        $attachments = [];
        if ($request->hasFile('attachment')) {
            foreach ($request->file('attachment') as $file) {
                $path = $file->store('attachments', 'public');
                $attachments[] = $path;
            }
            $marketcomplaint->attachments = json_encode($attachments);
        }
        // dd($marketcomplaint);
    
        $marketcomplaint->save();

        // --------------------audit trail show data fileds start -------------------------
        $history = new MarketComplaintAuditTrial();
        $history->market_id = $marketcomplaint->id;
        $history->activity_type = 'Record Number';
        $history->previous = "Null";
        $history->current = Helpers::getDivisionName(session()->get('division')) . "/MC/" . Helpers::year($marketcomplaint->created_at) . "/" . str_pad($marketcomplaint->record, 4, '0', STR_PAD_LEFT);
        $history->comment = "Not Applicable";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $marketcomplaint->status;
        $history->change_to =   "Opened";
        $history->change_from = "Initiator";
        $history->action_name = 'Create';
        $history->save();

        if (!empty($marketcomplaint->short_description)) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketcomplaint->id;
            $history->activity_type = 'Short Description';
            $history->previous = "Null";
            $history->current = $marketcomplaint->short_description;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketcomplaint->status;
            $history->change_to = "Opened";
            $history->change_from = "Initiation";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($marketcomplaint->nameAddressagency)) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketcomplaint->id;
            $history->activity_type = 'Name & Address of the complainant agency';
            $history->previous = "Null";
            $history->current = $marketcomplaint->nameAddressagency;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketcomplaint->status;
            $history->change_to = "Opened";
            $history->change_from = "Initiation";
            $history->action_name = "Create";
            $history->save();
        }
        if (!empty($marketcomplaint->nameDesgnationCom)) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketcomplaint->id;
            $history->activity_type = 'Name & Designation complainer';
            $history->previous = "Null";
            $history->current = $marketcomplaint->nameDesgnationCom;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketcomplaint->status;
            $history->change_to = "Opened";
            $history->change_from = "Initiation";
            $history->action_name = "Create";
            $history->save();
        }
        if (!empty($marketcomplaint->phone_no)) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketcomplaint->id;
            $history->activity_type = 'Phone No';
            $history->previous = "Null";
            $history->current = $marketcomplaint->phone_no;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketcomplaint->status;
            $history->change_to = "Opened";
            $history->change_from = "Initiation";
            $history->action_name = "Create";
            $history->save();
        }
        if (!empty($marketcomplaint->email_address)) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketcomplaint->id;
            $history->activity_type = 'Email Address';
            $history->previous = "Null";
            $history->current = $marketcomplaint->email_address;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketcomplaint->status;
            $history->change_to = "Opened";
            $history->change_from = "Initiation";
            $history->action_name = "Create";
            $history->save();
        }
        if (!empty($marketcomplaint->sample_recd)) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketcomplaint->id;
            $history->activity_type = 'Sample Recd';
            $history->previous = "Null";
            $history->current = $marketcomplaint->sample_recd;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketcomplaint->status;
            $history->change_to = "Opened";
            $history->change_from = "Initiation";
            $history->action_name = "Create";
            $history->save();
        }
        if (!empty($marketcomplaint->test_results_recd)) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketcomplaint->id;
            $history->activity_type = 'Test Results recd';
            $history->previous = "Null";
            $history->current = $marketcomplaint->test_results_recd;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketcomplaint->status;
            $history->change_to = "Opened";
            $history->change_from = "Initiation";
            $history->action_name = "Create";
            $history->save();
        }
        if (!empty($marketcomplaint->severity_level_form)) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketcomplaint->id;
            $history->activity_type = 'Classification based on receipt of complaint';
            $history->previous = "Null";
            $history->current = $marketcomplaint->severity_level_form;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketcomplaint->status;
            $history->change_to = "Opened";
            $history->change_from = "Initiation";
            $history->action_name = "Create";
            $history->save();
        }
        if (!empty($marketcomplaint->acknowledgment_sent)) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketcomplaint->id;
            $history->activity_type = 'Acknowledgment sent to customer through marketing department by Head QA';
            $history->previous = "Null";
            $history->current = $marketcomplaint->acknowledgment_sent;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketcomplaint->status;
            $history->change_to = "Opened";
            $history->change_from = "Initiation";
            $history->action_name = "Create";
            $history->save();
        }
        if (!empty($marketcomplaint->analysis_physical_examination)) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketcomplaint->id;
            $history->activity_type = 'Analysis / Physical examination of control sample to be done';
            $history->previous = "Null";
            $history->current = $marketcomplaint->analysis_physical_examination;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketcomplaint->status;
            $history->change_to = "Opened";
            $history->change_from = "Initiation";
            $history->action_name = "Create";
            $history->save();
        }
        if (!empty($marketcomplaint->Identification_Cross_functional)) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketcomplaint->id;
            $history->activity_type = 'Identification of Cross functional departments by QA for review of root cause of Market complaint';
            $history->previous = "Null";
            $history->current = $marketcomplaint->Identification_Cross_functional;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketcomplaint->status;
            $history->change_to = "Opened";
            $history->change_from = "Initiation";
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($marketcomplaint->Preliminary_Investigation_Report)) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketcomplaint->id;
            $history->activity_type = 'Preliminary Investigation Report sent by QA to complainant on';
            $history->previous = "Null";
            $history->current = $marketcomplaint->Preliminary_Investigation_Report;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketcomplaint->status;
            $history->change_to = "Opened";
            $history->change_from = "Initiation";
            $history->action_name = "Create";
            $history->save();
        } if (!empty($marketcomplaint->attachment)) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketcomplaint->id;
            $history->activity_type = 'Attachment';
            $history->previous = "Null";
            $history->current = $marketcomplaint->attachment;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketcomplaint->status;
            $history->change_to = "Opened";
            $history->change_from = "Initiation";
            $history->action_name = "Create";
            $history->save();
        } if (!empty($marketcomplaint->Further_Response_Received)) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketcomplaint->id;
            $history->activity_type = ' Further Response Received from customer';
            $history->previous = "Null";
            $history->current = $marketcomplaint->Further_Response_Received;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketcomplaint->status;
            $history->change_to = "Opened";
            $history->change_from = "Initiation";
            $history->action_name = "Create";
            $history->save();
        } if (!empty($marketcomplaint->Details_of_Response)) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketcomplaint->id;
            $history->activity_type = 'Details of Response';
            $history->previous = "Null";
            $history->current = $marketcomplaint->Details_of_Response;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketcomplaint->status;
            $history->change_to = "Opened";
            $history->change_from = "Initiation";
            $history->action_name = "Create";
            $history->save();
        }
        if (!empty($marketcomplaint->Further_investigation_Additional_testing)) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketcomplaint->id;
            $history->activity_type = 'Further investigation / Additional testing required';
            $history->previous = "Null";
            $history->current = $marketcomplaint->Further_investigation_Additional_testing;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketcomplaint->status;
            $history->change_to = "Opened";
            $history->change_from = "Initiation";
            $history->action_name = "Create";
            $history->save();
        }if (!empty($marketcomplaint->Method_Tools_to_be_used_for)) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketcomplaint->id;
            $history->activity_type = 'Method / Tools to be used for investigation';
            $history->previous = "Null";
            $history->current = $marketcomplaint->Method_Tools_to_be_used_for;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketcomplaint->status;
            $history->change_to = "Opened";
            $history->change_from = "Initiation";
            $history->action_name = "Create";
            $history->save();
        }if (!empty($marketcomplaint->Details_of_Response)) {
            $history = new MarketComplaintAuditTrial();
            $history->market_id = $marketcomplaint->id;
            $history->activity_type = 'Details of Response';
            $history->previous = "Null";
            $history->current = $marketcomplaint->Details_of_Response;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $marketcomplaint->status;
            $history->change_to = "Opened";
            $history->change_from = "Initiation";
            $history->action_name = "Create";
            $history->save();
        }

        //------------------------audit trail show end ---------------------------------

        //------------------------Grid data code  trail ---------------------------------

        $griddata = $marketcomplaint->id;

        // Store data for Product Details grid
        $productDetailsData = MarketComplaintGrids::where(['market_id' => $griddata, 'identifer' => 'ProductDetails'])->firstOrNew();
        $productDetailsData->market_id = $griddata;
        $productDetailsData->identifer = 'ProductDetails';
        $productDetailsData->data = $request->product_details;
        $productDetailsData->save();
        // Store data for Material Details grid
        $materialDetailsData = MarketComplaintGrids::where(['market_id' => $griddata, 'identifer' => 'MaterialDetails'])->firstOrNew();
        $materialDetailsData->market_id = $griddata;
        $materialDetailsData->identifer = 'MaterialDetails';
        $materialDetailsData->data = $request->material_details;
        // dd($materialDetailsData->data);
        $materialDetailsData->save();

          // Store History Details
        $historyDetailsData = MarketComplaintGrids::where(['market_id' => $griddata, 'identifer' => 'HistoryDetails'])->firstOrNew();
        $historyDetailsData->market_id = $griddata;
        $historyDetailsData->identifer = 'HistoryDetails';
        $historyDetailsData->data = $request->history_details;
        $historyDetailsData->save();
            
        

        toastr()->success("Record is created Successfully");
        return redirect(url('rcms/qms-dashboard'));
    }


    public function marketshow($id)
    {
        $old_record = MarketComplaint::select('id', 'division_id', 'record')->get();
        $data = MarketComplaint::find($id);
        $userData = User::all();
        // $data1 = DeviationCft::where('deviation_id', $id)->latest()->first();
        // return $data1;
        $data->record = str_pad($data->record, 4, '0', STR_PAD_LEFT);
        $data->assign_to_name = User::where('id', $data->assign_id)->value('name');
        $productDetailsData = MarketComplaintGrids::where('market_id', $id)->where('identifer', 'ProductDetails')->first();
        $materialDetailsData = MarketComplaintGrids::where('market_id', $id)->where('identifer', 'MaterialDetails')->first();
        $historyDetailsData = MarketComplaintGrids::where('market_id', $id)->where('identifer', 'HistoryDetails')->first();
        // $grid_data1 = DeviationGrid::where('deviation_grid_id', $id)->where('type', "Document")->first();
        // $grid_data2 = DeviationGrid::where('deviation_grid_id', $id)->where('type', "Product")->first();
        $data->initiator_name = User::where('id', $data->initiator_id)->value('name');
        $pre = MarketComplaint::all();
        $divisionName = DB::table('q_m_s_divisions')->where('id', $data->division_id)->value('name');
        // dd($materialDetailsData);
        return view('frontend.marketcomplaint.marketcomplaint_view', compact('data','userData','productDetailsData','materialDetailsData','historyDetailsData', 'old_record', 'pre', 'divisionName'));
    }

    public function update(Request $request, $id)
    {
        $form_progress = null;
        $lastmarketcomplaint = MarketComplaint::find($id);
        $marketcomplaint = MarketComplaint::find($id);
        $marketcomplaint->assign_to = $request->input('assign_to');
        // $marketcomplaint->due_date = $request->input('due_date');
        $marketcomplaint->initiator_group = $request->input('initiator_group');
        $marketcomplaint->initiator_group_code = $request->input('initiator_group_code');
        $marketcomplaint->short_description = $request->input('short_description');
        $marketcomplaint->nameAddressagency = $request->input('nameAddressagency');
        $marketcomplaint->nameDesgnationCom = $request->input('nameDesgnationCom');
        $marketcomplaint->phone_no = $request->input('phone_no');
        $marketcomplaint->email_address = $request->input('email_address');
        $marketcomplaint->sample_recd = $request->input('sample_recd');
        $marketcomplaint->test_results_recd = $request->input('test_results_recd');
        $marketcomplaint->severity_level_form = $request->input('severity_level_form');
        $marketcomplaint->acknowledgment_sent = $request->input('acknowledgment_sent');
        $marketcomplaint->analysis_physical_examination = $request->input('analysis_physical_examination');


        $marketcomplaint->identification_cross_functional = $request->input('Identification_Cross_functional');
        $marketcomplaint->preliminary_investigation_report = $request->input('Preliminary_Investigation_Report');
        $marketcomplaint->further_response_received = $request->input('Further_Response_Received');
        $marketcomplaint->details_of_response = $request->input('Details_of_Response');
        $marketcomplaint->further_investigation_additional_testing = $request->input('Further_investigation_Additional_testing');
        $marketcomplaint->method_tools_to_be_used_for = $request->input('Method_Tools_to_be_used_for');

         // Handle file attachments
    $attachments = $marketcomplaint->attachments ? json_decode($marketcomplaint->attachments, true) : [];
    if ($request->hasFile('attachment')) {
        foreach ($request->file('attachment') as $file) {
            $path = $file->store('attachments', 'public');
            $attachments[] = $path;
        }
    }
    $marketcomplaint->attachments = json_encode($attachments);

        //=================grid updated --------------------
        $griddata = $marketcomplaint->id;

        // Store data for Product Details grid
        $productDetailsData = MarketComplaintGrids::where(['market_id' => $griddata, 'identifer' => 'ProductDetails'])->firstOrNew();
        $productDetailsData->market_id = $griddata;
        $productDetailsData->identifer = 'ProductDetails';
        $productDetailsData->data = $request->product_details;
        $productDetailsData->update();

        $materialDetailsData = MarketComplaintGrids::where(['market_id' => $griddata, 'identifer' => 'MaterialDetails'])->firstOrNew();
        $materialDetailsData->market_id = $griddata;
        $materialDetailsData->identifer = 'MaterialDetails';
        $materialDetailsData->data = $request->material_details;
        // dd($materialDetailsData->data);

          // Store History Details (new grid)
        $historyDetailsData = MarketComplaintGrids::where(['market_id' => $griddata, 'identifer' => 'HistoryDetails'])->firstOrNew();
        $historyDetailsData->market_id = $griddata;
        $historyDetailsData->identifer = 'HistoryDetails';
        $historyDetailsData->data = $request->history_details;
        $historyDetailsData->save();
        $materialDetailsData->update();


        $marketcomplaint->update();



        // -------audit trrail show start update -----------
        $fields = [
            'short_description' => 'Short Description',
            'nameAddressagency' => 'Name & Address of the complainant agency',
            'nameDesgnationCom' => 'Name & Designation complainer',
            'phone_no' => 'Phone No',
            'email_address' => 'Email Address',
            'sample_recd' => 'Sample Recd',
            'test_results_recd' => 'Test Results Recd',
            'severity_level_form' => 'Classification based on receipt of complaint',
            'acknowledgment_sent' => 'Acknowledgment sent to customer through marketing department by Head QA',
            'analysis_physical_examination' => 'Analysis / Physical examination of control sample to be done',
            'Identification_Cross_functional' => 'Identification of Cross functional departments by QA for review of root cause of Market complaint',
            'Preliminary_Investigation_Report' => 'Preliminary Investigation Report sent by QA to complainant on',
            'attachment' => 'Attachment',
            'Further_Response_Received' => 'Further Response Received from customer',
            'Details_of_Response' => 'Details of Response',
            'Further_investigation_Additional_testing' => 'Further investigation / Additional testing required',
            'Method_Tools_to_be_used_for' => 'Method / Tools to be used for investigation',
        ];

        foreach ($fields as $field => $activity_type) {
            if ($lastmarketcomplaint->$field != $marketcomplaint->$field || !empty($request->comment)) {
                $history = new MarketComplaintAuditTrial;
                $history->market_id = $id;
                $history->activity_type = $activity_type;
                $history->previous = $lastmarketcomplaint->$field;
                $history->current = $marketcomplaint->$field;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastmarketcomplaint->status;
                $history->change_to = "Not Applicable";
                $history->change_from = $lastmarketcomplaint->status;
                $history->action_name = 'Update';
                $history->save();
            }
        }
        // -------audit trrail show end update -----------

        toastr()->success('Record is Update Successfully');

        return back();
    }


    public static function singleReport($id)
    {
        // dd("test");
        $data = MarketComplaint::find($id);
        // return $data;
        // $data1 =  DeviationCft::where('deviation_id', $id)->first();
        // $historyDetails = $historyData ? json_decode($historyData->data, true) : [];
        if (!empty ($data)) {
            $data->originator = User::where('id', $data->initiator_id)->value('name');
            $productDetailsData = MarketComplaintGrids::where(['market_id' => $id, 'identifer' => 'ProductDetails'])->first(); 
            $historyDetailsData = MarketComplaintGrids::where(['market_id' => $id, 'identifer' => 'HistoryDetails'])->first(); 
            $materialDetailsData = MarketComplaintGrids::where(['market_id' => $id, 'identifer' => 'MaterialDetails'])->first(); 

            $productdeta = [];
            if ($productDetailsData && is_string($productDetailsData->data)) {
                // Decode if it is a string
                $productdeta = json_decode($productDetailsData->data, true);
            } elseif ($productDetailsData && is_array($productDetailsData->data)) {
                // If the data is already an array, use it as it is
                $productdeta = $productDetailsData->data;
            }

            $historydeta = [];
            if ($historyDetailsData && is_string($historyDetailsData->data)) {
                // Decode if it is a string
                $historydeta = json_decode($historyDetailsData->data, true);
            } elseif ($historyDetailsData && is_array($historyDetailsData->data)) {
                // If the data is already an array, use it as it is
                $historydeta = $historyDetailsData->data;
            }
            $materialdeta = [];
            if ($materialDetailsData && is_string($materialDetailsData->data)) {
                // Decode if it is a string
                $materialdeta = json_decode($materialDetailsData->data, true);
            } elseif ($materialDetailsData && is_array($materialDetailsData->data)) {
                // If the data is already an array, use it as it is
                $materialdeta = $materialDetailsData->data;
            }
    


            $pdf = App::make('dompdf.wrapper');
            $time = Carbon::now();
            $pdf = PDF::loadview('frontend.marketcomplaint.SingleReport', compact('productdeta','historydeta','materialdeta','data',))
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
            $canvas->page_text($width / 4, $height / 2, $data->status, null, 25, [0, 0, 0], 2, 6, -20);

            $filePath = public_path('user/pdf/'. $id .'.pdf');
            file_put_contents($filePath, $pdf->output());

            return $pdf->stream('Deviation' . $id . '.pdf');
        }
    }

    public function MC_send_stage(Request $request, $id)
    {

// dd("test");
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $marketcomplaint = MarketComplaint::find($id);
            $lastDocument = MarketComplaint::find($id);
            if ($marketcomplaint->stage == 1) {
                $marketcomplaint->stage = "2";
                $marketcomplaint->status = "HOD/Designee";
                $marketcomplaint->plan_proposed_by = Auth::user()->name;
                $marketcomplaint->plan_proposed_on = Carbon::now()->format('d-M-Y');
                   
                    $history = new MarketComplaintAuditTrial();
                    $history->market_id = $id;
                    $history->activity_type = 'Activity Log';
                    $history->previous = "";
                    $history->current = $marketcomplaint->plan_proposed_by;
                    $history->comment = $request->comment;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->change_to =   "HOD/Designee";
                    $history->change_from = $lastDocument->status;
                    $history->stage = 'HOD/Designee';
                    $history->save();

                  
           
                $marketcomplaint->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($marketcomplaint->stage == 2) {
                $marketcomplaint->stage = "3";
                $marketcomplaint->status = "QA Review";
                $marketcomplaint->plan_approved_by = Auth::user()->name;
                $marketcomplaint->plan_approved_on = Carbon::now()->format('d-M-Y');
                  
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $id;
                $history->activity_type = 'Activity Log';
                $history->previous = "";
                // $history->current = $capa->plan_approved_by;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->change_to =   "QA Review";
                $history->change_from = $lastDocument->status;
                $history->stage = 'QA Review';
                $history->save();
                
              
                
                $marketcomplaint->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($marketcomplaint->stage == 3) {
                $marketcomplaint->stage = "4";
                $marketcomplaint->status = "Head QA/Designee";
                $marketcomplaint->plan_approved_by = Auth::user()->name;
                $marketcomplaint->plan_approved_on = Carbon::now()->format('d-M-Y');
                  
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $id;
                $history->activity_type = 'Activity Log';
                $history->previous = "";
                // $history->current = $capa->plan_approved_by;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->change_to =   "Head QA/Designee";
                $history->change_from = $lastDocument->status;
                $history->stage = 'Head QA/Designee';
                $history->save();
                
              
                
                $marketcomplaint->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($marketcomplaint->stage == 4) {
                $marketcomplaint->stage = "5";
                $marketcomplaint->status = "Pending Actions Completion";
                $marketcomplaint->plan_approved_by = Auth::user()->name;
                $marketcomplaint->plan_approved_on = Carbon::now()->format('d-M-Y');
                  
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $id;
                $history->activity_type = 'Activity Log';
                $history->previous = "";
                // $history->current = $capa->plan_approved_by;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->change_to =   "Pending Actions Completion";
                $history->change_from = $lastDocument->status;
                $history->stage = 'Pending Actions Completion';
                $history->save();
                
              
                
                $marketcomplaint->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($marketcomplaint->stage == 5) {
                $marketcomplaint->stage = "6";
                $marketcomplaint->status = "Closed - Done";
                $marketcomplaint->plan_approved_by = Auth::user()->name;
                $marketcomplaint->plan_approved_on = Carbon::now()->format('d-M-Y');
                  
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $id;
                $history->activity_type = 'Activity Log';
                $history->previous = "";
                // $history->current = $capa->plan_approved_by;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status; 
                $history->change_to =   "Closed - Done";
                $history->change_from = $lastDocument->status;
                $history->stage = 'Closed - Done';
                $history->save();
                
              
                
                $marketcomplaint->update();
                toastr()->success('Document Sent');
                return back();
            }
            
        }
    }


    public function moreinfo_reject_market(Request $request, $id)
    {
        // dd("test");

        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            // return $request;
            $marketcomplaint = MarketComplaint::find($id);
            $lastDocument = MarketComplaint::find($id);
            $list = Helpers::getInitiatorUserList();
// dd($marketcomplaint->stage);

            if ($marketcomplaint->stage == 2) {
                // dd($deviation->stage);
                $marketcomplaint->stage = "1";
                $marketcomplaint->status = "Opened";
                $marketcomplaint->rejected_by = Auth::user()->name;
                // $marketcomplaint->rejected_email = Auth::user()->email;
                $marketcomplaint->rejected_on = Carbon::now()->format('d-M-Y');
                $marketcomplaint->rejected_comment = $request->comment;
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $id;
                $history->activity_type = 'Activity Log';
                $history->previous = "";
                $history->current = $marketcomplaint->rejected_by;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                // $history->stage = 'Send to QA Initial Review';
                $history->change_to =   "Opened";
                $history->change_from = $lastDocument->status;
                $history->action = 'More Info Required';
                $history->save();
                $marketcomplaint->update();
               
               

                toastr()->success('Document Sent');
                return back();
            }
            
            if ($marketcomplaint->stage == 3) {
                // dd($deviation->stage);
                $marketcomplaint->stage = "2";
                $marketcomplaint->status = "HOD/Designee";
                $marketcomplaint->rejected_by = Auth::user()->name;
                // $marketcomplaint->rejected_email = Auth::user()->email;
                $marketcomplaint->rejected_on = Carbon::now()->format('d-M-Y');
                $marketcomplaint->rejected_comment = $request->comment;
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $id;
                $history->activity_type = 'Activity Log';
                $history->previous = "";
                $history->current = $marketcomplaint->rejected_by;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                // $history->stage = 'Send to QA Initial Review';
                $history->change_to =   "Opened";
                $history->change_from = $lastDocument->status;
                $history->action = 'More Info Required';
                $history->save();
                $marketcomplaint->update();
               
               

                toastr()->success('Document Sent');
                return back();
            }

        }
        else {
            toastr()->error('E-signature Not match');
            return back();
        }
        
    }
    

    
    public function AuditTrial($id)
{
    // dd("test");
    $audit = MarketComplaintAuditTrial::where('market_id', $id)
        ->orderByDesc('id')
        ->paginate(10) // Change to paginate
        ;

    $today = Carbon::now()->format('d-m-y');
    $document = MarketComplaint::where('id', $id)->first();
    $document->initiator = User::where('id', $document->initiator_id)->value('name');
    $users = User::all();
    // dd($document );

    return view('frontend.marketcomplaint.audit-trial', compact('audit', 'document', 'today','users'));
}

public static function auditReport($id)
{
    $doc = MarketComplaint::find($id);
    if (!empty($doc)) {
        $doc->originator = User::where('id', $doc->initiator_id)->value('name');
        $data = MarketComplaintAuditTrial::where('market_id', $id)->get();


        $pdf = App::make('dompdf.wrapper');
        $time = Carbon::now();
        $pdf = PDF::loadview('frontend.marketcomplaint.auditReport', compact('data', 'doc'))
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
        return $pdf->stream('LabIncident-AuditTrial' . $id . '.pdf');
    }
}


public function audit_trail_filter_marketcomplaint(Request $request, $id)
{
    // Start query for DeviationAuditTrail
    $query = MarketComplaintAuditTrial::query();
    $query->where('LabIncident_id', $id);

    // Check if typedata is provided
    if ($request->filled('typedata')) {
        switch ($request->typedata) {
            case 'cft_review':
                // Filter by specific CFT review actions
                $cft_field = ['CFT Review Complete','CFT Review Not Required',];
                $query->whereIn('action', $cft_field);
                break;

            case 'stage':
                // Filter by activity log stage changes
                $stage=[  'Submit', 'HOD Review Complete', 'QA/CQA Initial Review Complete','Request For Cancellation',
                    'CFT Review Complete', 'QA/CQA Final Assessment Complete', 'Approved','Send to Initiator','Send to HOD','Send to QA/CQA Initial Review','Send to Pending Initiator Update',
                    'QA/CQA Final Review Complete', 'Rejected', 'Initiator Updated Complete',
                    'HOD Final Review Complete', 'More Info Required', 'Cancel','Implementation verification Complete','Closure Approved'];
                $query->whereIn('action', $stage); // Ensure correct activity_type value
                break;

            case 'user_action':
                // Filter by various user actions
                $user_action = [  'Submit', 'HOD Review Complete', 'QA/CQA Initial Review Complete','Request For Cancellation',
                    'CFT Review Complete', 'QA/CQA Final Assessment Complete', 'Approved','Send to Initiator','Send to HOD','Send to QA/CQA Initial Review','Send to Pending Initiator Update',
                    'QA/CQA Final Review Complete', 'Rejected', 'Initiator Updated Complete',
                    'HOD Final Review Complete', 'More Info Required', 'Cancel','Implementation verification Complete','Closure Approved'];
                $query->whereIn('action', $user_action);
                break;
                 case 'notification':
                // Filter by various user actions
                $notification = [];
                $query->whereIn('action', $notification);
                break;
                 case 'business':
                // Filter by various user actions
                $business = [];
                $query->whereIn('action', $business);
                break;

            default:
                break;
        }
    }

    // Apply additional filters
    if ($request->filled('user')) {
        $query->where('user_id', $request->user);
    }

    if ($request->filled('from_date')) {
        $query->whereDate('created_at', '>=', $request->from_date);
    }

    if ($request->filled('to_date')) {
        $query->whereDate('created_at', '<=', $request->to_date);
    }

    // Get the filtered results
    $audit = $query->orderByDesc('id')->get();

    // Flag for filter request
    $filter_request = true;

    // Render the filtered view and return as JSON
    $responseHtml = view('frontend.labincident.lab_incident_filter', compact('audit', 'filter_request'))->render();

    return response()->json(['html' => $responseHtml]);
}


}
