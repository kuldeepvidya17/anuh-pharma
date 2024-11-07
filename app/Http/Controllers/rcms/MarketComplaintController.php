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
        $griddata = $marketcomplaint->id;

        $materialDetailsData = MarketComplaintGrids::where(['market_id' => $griddata, 'identifer' => 'MaterialDetails'])->firstOrNew();
        $materialDetailsData->market_id = $griddata;
        $materialDetailsData->identifer = 'MaterialDetails';
        $materialDetailsData->data = $request->material_details;
        // dd($materialDetailsData->data);
        $materialDetailsData->save();

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
        $materialDetailsData = MarketComplaintGrids::where('market_id', $id)->where('identifer', 'MaterialDetails')->first();
        // $grid_data1 = DeviationGrid::where('deviation_grid_id', $id)->where('type', "Document")->first();
        // $grid_data2 = DeviationGrid::where('deviation_grid_id', $id)->where('type', "Product")->first();
        $data->initiator_name = User::where('id', $data->initiator_id)->value('name');
        $pre = MarketComplaint::all();
        $divisionName = DB::table('q_m_s_divisions')->where('id', $data->division_id)->value('name');
        // dd($materialDetailsData);
        return view('frontend.marketcomplaint.marketcomplaint_view', compact('data','userData','materialDetailsData', 'old_record', 'pre', 'divisionName'));
    }

    public function update(Request $request, $id)
    {
        $form_progress = null;
        $lastDeviation = MarketComplaint::find($id);
        $marketcomplaint = MarketComplaint::find($id);
        $marketcomplaint->assign_to = $request->input('assign_to');
        // $marketcomplaint->due_date = $request->input('due_date');
        $marketcomplaint->initiator_group = $request->input('initiator_group');
        $marketcomplaint->initiator_group_code = $request->input('initiator_group_code');
        $marketcomplaint->short_description = $request->input('short_description');
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

        $materialDetailsData = MarketComplaintGrids::where(['market_id' => $griddata, 'identifer' => 'MaterialDetails'])->firstOrNew();
        $materialDetailsData->market_id = $griddata;
        $materialDetailsData->identifer = 'MaterialDetails';
        $materialDetailsData->data = $request->material_details;
        // dd($materialDetailsData->data);
        $materialDetailsData->update();


        $marketcomplaint->update();
        toastr()->success('Record is Update Successfully');

        return back();
    }


    public static function singleReport($id)
    {
        // dd("test");
        $data = MarketComplaint::find($id);
        // return $data;
        // $data1 =  DeviationCft::where('deviation_id', $id)->first();
        if (!empty ($data)) {
            $data->originator = User::where('id', $data->initiator_id)->value('name');
          
            $pdf = App::make('dompdf.wrapper');
            $time = Carbon::now();
            $pdf = PDF::loadview('frontend.marketcomplaint.SingleReport', compact('data',))
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


        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $marketcomplaint = MarketComplaint::find($id);
            $lastDocument = MarketComplaint::find($id);
            if ($marketcomplaint->stage == 1) {
                $marketcomplaint->stage = "2";
                $marketcomplaint->status = "Pending marketcomplaint Plan";
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
                    $history->stage = 'Plan Proposed';
                    $history->save();

                  
           
                $marketcomplaint->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($marketcomplaint->stage == 2) {
                $marketcomplaint->stage = "3";
                $marketcomplaint->status = "marketcomplaint In Progress";
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
                $history->stage = 'Plan Approved';
                $history->save();
                
              
                
                $marketcomplaint->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($marketcomplaint->stage == 3) {
                $marketcomplaint->stage = "4";
                $marketcomplaint->status = "marketcomplaint In Progress";
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
                $history->stage = 'Plan Approved';
                $history->save();
                
              
                
                $marketcomplaint->update();
                toastr()->success('Document Sent');
                return back();
            }
        }
    }



    public function AuditTrial($id)
    {
        // dd("test");
        $audit = MarketComplaintAuditTrial::where('market_id', $id)->orderByDESC('id')->get()->unique('activity_type');
        $today = Carbon::now()->format('d-m-y');
        $document = MarketComplaint::where('id', $id)->first();
        $document->initiator = User::where('id', $document->initiator_id)->value('name');


        // return $audit;

        return view('frontend.marketcomplaint.audit-trial', compact('audit', 'document', 'today'));
    }
    
}
