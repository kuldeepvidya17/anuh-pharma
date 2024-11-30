<?php

namespace App\Http\Controllers\rcms;

use App\Http\Controllers\Controller;
use App\Models\AuditReviewersDetails;
use App\Models\MarketComplaint;
use App\Models\MarketComplaintAuditTrial;
use App\Models\MarketComplaintGrids;
use App\Models\RecordNumber;
use App\Models\RoleGroup;
use App\Models\User;
use Carbon\Carbon;
use Helpers;
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
        // dd($record_number);
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

        $recordCounter = RecordNumber::first();
        $newRecordNumber = $recordCounter->counter + 1;
        $recordCounter->counter = $newRecordNumber;
        $recordCounter->save();

        $marketcomplaint->record = $newRecordNumber;

        // $marketcomplaint->record = ((RecordNumber::first()->value('counter')) + 1);
        $marketcomplaint->initiator_id = Auth::user()->id;
        // dd($marketcomplaint->record);

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
       
        $marketcomplaint->identification_cross_functional = $request->input('identification_cross_functional');
        $marketcomplaint->preliminary_investigation_report = $request->input('preliminary_investigation_report');
        $marketcomplaint->further_response_received = $request->input('further_response_received');
        // $marketcomplaint->details_of_response = $request->input('details_of_response');
        $marketcomplaint->further_investigation_additional_testing = $request->input('further_investigation_additional_testing');
        $marketcomplaint->method_tools_to_be_used_for = $request->input('method_tools_to_be_used_for');
        $marketcomplaint->capa_qa_comments2 = $request->input('capa_qa_comments2');
        $marketcomplaint->qa_review = $request->input('qa_review');
        $marketcomplaint->Details_of_Response = $request->input('Details_of_Response');
        $marketcomplaint->head_qulitiy_comment = $request->input('head_qulitiy_comment');
        $marketcomplaint->re_categoruzation_of_complaint = $request->input('re_categoruzation_of_complaint');
        $marketcomplaint->reson_for_re_cate = $request->input('reson_for_re_cate');
        $marketcomplaint->due_date_extension = $request->input('due_date_extension');


        $marketcomplaint->type="MarketComplaint";
        $marketcomplaint->stage=1;
        $marketcomplaint->status = "Opened";

        // $attachments = [];
        // if ($request->hasFile('attachment')) {
        //     foreach ($request->file('attachment') as $file) {
        //         $path = $file->store('attachments', 'public');
        //         $attachments[] = $path;
        //     }
        //     $marketcomplaint->attachments = json_encode($attachments);
        // }

                          //first attchment ============================
                          if (!empty($request->attachments_gi)) {
                            $files = [];
                            if ($request->hasFile('attachments_gi')) {
                                foreach ($request->file('attachments_gi') as $file) {
                                    // Generate a unique name for the file
                                    $name = $request->name . 'attachments_gi' . uniqid() . '.' . $file->getClientOriginalExtension();
                
                                    // Move the file to the upload directory
                                    $file->move(public_path('upload/'), $name);
                
                                    // Add the file name to the array
                                    $files[] = $name;
                                }
                            }
                            // Encode the file names array to JSON and assign it to the model
                            $marketcomplaint->attachments_gi = json_encode($files);
                        }
                
                     // Second attachment ============================
                        if (!empty($request->attachments_gi_2)) {
                            $files_2 = [];
                            if ($request->hasFile('attachments_gi_2')) {
                                foreach ($request->file('attachments_gi_2') as $file) {
                                    // Generate a unique name for the file
                                    $name = $request->name . 'attachments_gi_2' . uniqid() . '.' . $file->getClientOriginalExtension();

                                    // Move the file to the upload directory
                                    $file->move(public_path('upload/'), $name);

                                    // Add the file name to the array
                                    $files_2[] = $name;
                                }
                            }
                            // Encode the file names array to JSON and assign it to the model
                            $marketcomplaint->attachments_gi_2 = json_encode($files_2);
                        }

                        // Third attachment ============================
                            if (!empty($request->attachments_gi_3)) {
                                $files_3 = [];
                                if ($request->hasFile('attachments_gi_3')) {
                                    foreach ($request->file('attachments_gi_3') as $file) {
                                        $name = $request->name . 'attachments_gi_3' . uniqid() . '.' . $file->getClientOriginalExtension();
                                        $file->move(public_path('upload/'), $name);
                                        $files_3[] = $name;
                                    }
                                }
                                $marketcomplaint->attachments_gi_3 = json_encode($files_3);
                            }

                                                    // Fourth attachment ============================
                        if (!empty($request->attachments_gi_4)) {
                            $files_4 = [];
                            if ($request->hasFile('attachments_gi_4')) {
                                foreach ($request->file('attachments_gi_4') as $file) {
                                    $name = $request->name . 'attachments_gi_4' . uniqid() . '.' . $file->getClientOriginalExtension();
                                    $file->move(public_path('upload/'), $name);
                                    $files_4[] = $name;
                                }
                            }
                            $marketcomplaint->attachments_gi_4 = json_encode($files_4);
                        }



                        
                
    
        $marketcomplaint->save();

        // --------------------audit trail show data fileds start -------------------------
       // Define the fields and their respective activity types
            $fields = [
                'record_number' => 'Record Number',
                'division_id' => 'Site/Location Code',
                'division_code' => 'Initiator',
                'intiation_date' => 'Date of Initiation',
                'assign_to' => 'Assigned To',
                'short_description' => 'Short Description',
                'due_date' => 'Due Date',
                'initiator_group' => 'Department Group',
                'initiator_group_code' => 'Department Group Code',
               
                'nameAddressagency' => 'Name & Address of the complainant agency',
                'nameDesgnationCom' => 'Name & Designation complainer',
                'phone_no' => 'Phone No',
                'email_address' => 'Email Address',
                'sample_recd' => 'Sample Recd',
                'test_results_recd' => 'Test Results recd',
                'severity_level_form' => 'Classification based on receipt of complaint',
                'acknowledgment_sent' => 'Acknowledgment sent to customer through marketing department by Head QA',
                'analysis_physical_examination' => 'Analysis / Physical examination of control sample to be done',
                'identification_cross_functional' => 'Identification of Cross functional departments by QA for review of root cause of Market complaint',
                'preliminary_investigation_report' => 'Preliminary Investigation Report sent by QA to complainant on',
                'attachment' => 'Attachment',
                'further_response_received' => 'Further Response Received from customer',
                'Details_of_Response' => 'Details of Response',
                'further_investigation_additional_testing' => 'Further investigation / Additional testing required',
                'method_tools_to_be_used_for' => 'Method / Tools to be used for investigation',
                'capa_qa_comments2' => 'Comments',
                'head_qulitiy_comment' => 'Head Quality Comment',
                'qa_review' => 'QA Review & Closure',
                'closure_attachment' => 'Closure Attachment',
                'due_date_extension' => 'Due Date Extension Justification',
            ];

            // Loop through each field and create an audit trail entry
            foreach ($fields as $field => $activity_type) {
                if (!empty($marketcomplaint->$field)) {
                    $history = new MarketComplaintAuditTrial();
                    $history->market_id = $marketcomplaint->id;
                    $history->activity_type = $activity_type;
                    $history->previous = "Null";
                    $history->current = $marketcomplaint->$field;
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
            
         

          $qualityDetailsData_1 = MarketComplaintGrids::where(['market_id' => $griddata, 'identifer' => 'QualityControl_1'])->firstOrNew();
          $qualityDetailsData_1->market_id = $griddata;
          $qualityDetailsData_1->identifer = 'QualityControl_1';
          $qualityDetailsData_1->data = $request->qualitycontrol_1;
          $qualityDetailsData_1->save();
        // dd($qualityDetailsData_1);

        $qualityDetailsData_2 = MarketComplaintGrids::where(['market_id' => $griddata, 'identifer' => 'QualityControl_2'])->firstOrNew();
        $qualityDetailsData_2->market_id = $griddata;
        $qualityDetailsData_2->identifer = 'QualityControl_2';
        $qualityDetailsData_2->data = $request->qualitycontrol_2;
        $qualityDetailsData_2->save();
                // Store data for Complaint Details grid
        $complaintDetailsData = MarketComplaintGrids::where(['market_id' => $griddata, 'identifer' => 'ComplaintDetails'])->firstOrNew();
        $complaintDetailsData->market_id = $griddata;
        $complaintDetailsData->identifer = 'ComplaintDetails';
        $complaintDetailsData->data = $request->complaint_details; // assuming your form data is coming as 'complaint_details'
        $complaintDetailsData->save();

        $changecontrolDetailsData = MarketComplaintGrids::where(['market_id' => $griddata, 'identifer' => 'ChangeControlCapaDetails'])->firstOrNew();
        $changecontrolDetailsData->market_id = $griddata;
        $changecontrolDetailsData->identifer = 'ChangeControlCapaDetails';
        $changecontrolDetailsData->data = $request->changecontrol_capa_details; // assuming your form data is coming as 'complaint_details'
        $changecontrolDetailsData->save();
        // dd($changecontrolDetailsData);
        $closureVerificationData = MarketComplaintGrids::where(['market_id' => $griddata, 'identifer' => 'ClosureVerificationDetails'])->firstOrNew();
        $closureVerificationData->market_id = $griddata;
        $closureVerificationData->identifer = 'ClosureVerificationDetails';
        $closureVerificationData->data = $request->closureverification_details; // assuming your form data is coming as 'closureverification_details'
        $closureVerificationData->save();


        toastr()->success("Record is created Successfully");
        return redirect(url('rcms/qms-dashboard'));
    }


    public function marketshow($id)
    {
        $old_record = MarketComplaint::select('id', 'division_id', 'record')->get();
        $data = MarketComplaint::find($id);
        // dd($data->record);
        $userData = User::all();
        // $data1 = DeviationCft::where('deviation_id', $id)->latest()->first();
        // return $data1;
        $data->record = str_pad($data->record, 4, '0', STR_PAD_LEFT);
        $data->assign_to_name = User::where('id', $data->assign_id)->value('name');
        $productDetailsData = MarketComplaintGrids::where('market_id', $id)->where('identifer', 'ProductDetails')->first();
        $materialDetailsData = MarketComplaintGrids::where('market_id', $id)->where('identifer', 'MaterialDetails')->first();
        $historyDetailsData = MarketComplaintGrids::where('market_id', $id)->where('identifer', 'HistoryDetails')->first();
        $qualityDetailsData_1 = MarketComplaintGrids::where('market_id', $id)->where('identifer', 'QualityControl_1')->first();
        $qualityDetailsData_2 = MarketComplaintGrids::where('market_id', $id)->where('identifer', 'QualityControl_2')->first();
        $complaintDetailsData = MarketComplaintGrids::where('market_id', $id)->where('identifer', 'ComplaintDetails')->first();
        $changecontrolDetailsData = MarketComplaintGrids::where('market_id', $id)->where('identifer', 'ChangeControlCapaDetails')->first();
        $closureVerificationData = MarketComplaintGrids::where('market_id', $id)->where('identifer', 'ClosureVerificationDetails')->first();
// dd($changecontrolDetailsData);
        // $grid_data1 = DeviationGrid::where('deviation_grid_id', $id)->where('type', "Document")->first();
        // $grid_data2 = DeviationGrid::where('deviation_grid_id', $id)->where('type', "Product")->first();
        $data->initiator_name = User::where('id', $data->initiator_id)->value('name');
        $pre = MarketComplaint::all();
        $divisionName = DB::table('q_m_s_divisions')->where('id', $data->division_id)->value('name');
        // dd($changecontrolDetailsData);
        return view('frontend.marketcomplaint.marketcomplaint_view', compact('data','userData','productDetailsData','materialDetailsData','historyDetailsData', 'old_record', 'pre', 'divisionName','qualityDetailsData_1','qualityDetailsData_2','complaintDetailsData','changecontrolDetailsData','closureVerificationData'));
    }

    public function update(Request $request, $id)
    {
        $form_progress = null;
        $lastmarketcomplaint = MarketComplaint::find($id);
        $marketcomplaint = MarketComplaint::find($id);
        $marketcomplaint->assign_to = $request->input('assign_to');
        // $marketcomplaint->due_date = $request->input('due_date');
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
       
        $marketcomplaint->identification_cross_functional = $request->input('identification_cross_functional');
        $marketcomplaint->preliminary_investigation_report = $request->input('preliminary_investigation_report');
        $marketcomplaint->further_response_received = $request->input('further_response_received');
        $marketcomplaint->details_of_response = $request->input('details_of_response');
        $marketcomplaint->further_investigation_additional_testing = $request->input('further_investigation_additional_testing');
        $marketcomplaint->method_tools_to_be_used_for = $request->input('method_tools_to_be_used_for');
        $marketcomplaint->capa_qa_comments2 = $request->input('capa_qa_comments2');
        $marketcomplaint->qa_review = $request->input('qa_review');
        $marketcomplaint->Details_of_Response = $request->input('Details_of_Response');
        $marketcomplaint->head_qulitiy_comment = $request->input('head_qulitiy_comment');
        $marketcomplaint->re_categoruzation_of_complaint = $request->input('re_categoruzation_of_complaint');
        $marketcomplaint->reson_for_re_cate = $request->input('reson_for_re_cate');
        $marketcomplaint->due_date_extension = $request->input('due_date_extension');

// dd($marketcomplaint->further_investigation_additional_testing ,$marketcomplaint->further_response_received);
         // Handle file attachments
    // $attachments = $marketcomplaint->attachments ? json_decode($marketcomplaint->attachments, true) : [];
    // if ($request->hasFile('attachment')) {
    //     foreach ($request->file('attachment') as $file) {
    //         $path = $file->store('attachments', 'public');
    //         $attachments[] = $path;
    //     }
    // }
    // $marketcomplaint->attachments = json_encode($attachments);



             //first attchment ============================
             if (!empty($request->attachments_gi) || !empty($request->deleted_attachments_gi)) {
                $existingFiles = json_decode($marketcomplaint->attachments_gi, true) ?? [];
                        if (!empty($request->deleted_attachments_gi)) {
                    $filesToDelete = explode(',', $request->deleted_attachments_gi);
                    $existingFiles = array_filter($existingFiles, function($file) use ($filesToDelete) {
                        return !in_array($file, $filesToDelete);
                    });
                }
                $newFiles = [];
                if ($request->hasFile('attachments_gi')) {
                    foreach ($request->file('attachments_gi') as $file) {
                        $name = $request->name . 'attachments_gi' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                        $file->move(public_path('upload/'), $name);
                        $newFiles[] = $name;
                    }
                }
                $allFiles = array_merge($existingFiles, $newFiles);
                $marketcomplaint->attachments_gi = json_encode($allFiles);
            }
            // Second attachment ============================
            if (!empty($request->attachments_gi_2) || !empty($request->deleted_attachments_gi_2)) {
                $existingFiles_2 = json_decode($marketcomplaint->attachments_gi_2, true) ?? [];
                
                // Handle deleted files
                if (!empty($request->deleted_attachments_gi_2)) {
                    $filesToDelete_2 = explode(',', $request->deleted_attachments_gi_2);
                    $existingFiles_2 = array_filter($existingFiles_2, function ($file) use ($filesToDelete_2) {
                        return !in_array($file, $filesToDelete_2);
                    });
                }

                // Handle new files
                $newFiles_2 = [];
                if ($request->hasFile('attachments_gi_2')) {
                    foreach ($request->file('attachments_gi_2') as $file) {
                        $name = $request->name . 'attachments_gi_2' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                        $file->move(public_path('upload/'), $name);
                        $newFiles_2[] = $name;
                    }
                }

                // Merge existing and new files
                $allFiles_2 = array_merge($existingFiles_2, $newFiles_2);
                $marketcomplaint->attachments_gi_2 = json_encode($allFiles_2);
            }

                // Third attachment ============================
                if (!empty($request->attachments_gi_3) || !empty($request->deleted_attachments_gi_3)) {
                    $existingFiles_3 = json_decode($marketcomplaint->attachments_gi_3, true) ?? [];
                    
                    // Handle deleted files
                    if (!empty($request->deleted_attachments_gi_3)) {
                        $filesToDelete_3 = explode(',', $request->deleted_attachments_gi_3);
                        $existingFiles_3 = array_filter($existingFiles_3, function ($file) use ($filesToDelete_3) {
                            return !in_array($file, $filesToDelete_3);
                        });
                    }

                    // Handle new files
                    $newFiles_3 = [];
                    if ($request->hasFile('attachments_gi_3')) {
                        foreach ($request->file('attachments_gi_3') as $file) {
                            $name = $request->name . 'attachments_gi_3' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                            $file->move(public_path('upload/'), $name);
                            $newFiles_3[] = $name;
                        }
                    }

                    // Merge existing and new files
                    $allFiles_3 = array_merge($existingFiles_3, $newFiles_3);
                    $marketcomplaint->attachments_gi_3 = json_encode($allFiles_3);
                }



                                // Fourth attachment ============================
                if (!empty($request->attachments_gi_4) || !empty($request->deleted_attachments_gi_4)) {
                    $existingFiles_4 = json_decode($marketcomplaint->attachments_gi_4, true) ?? [];
                    
                    // Handle deleted files
                    if (!empty($request->deleted_attachments_gi_4)) {
                        $filesToDelete_4 = explode(',', $request->deleted_attachments_gi_4);
                        $existingFiles_4 = array_filter($existingFiles_4, function ($file) use ($filesToDelete_4) {
                            return !in_array($file, $filesToDelete_4);
                        });
                    }

                    // Handle new files
                    $newFiles_4 = [];
                    if ($request->hasFile('attachments_gi_4')) {
                        foreach ($request->file('attachments_gi_4') as $file) {
                            $name = $request->name . 'attachments_gi_4' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                            $file->move(public_path('upload/'), $name);
                            $newFiles_4[] = $name;
                        }
                    }

                    // Merge existing and new files
                    $allFiles_4 = array_merge($existingFiles_4, $newFiles_4);
                    $marketcomplaint->attachments_gi_4 = json_encode($allFiles_4);
                }

            

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

        $qualityDetailsData_1 = MarketComplaintGrids::where(['market_id' => $griddata, 'identifer' => 'QualityControl_1'])->firstOrNew();
        $qualityDetailsData_1->market_id = $griddata;
        $qualityDetailsData_1->identifer = 'QualityControl_1';
        $qualityDetailsData_1->data = $request->qualitycontrol_1;
        $qualityDetailsData_1->save();

        $qualityDetailsData_2 = MarketComplaintGrids::where(['market_id' => $griddata, 'identifer' => 'QualityControl_2'])->firstOrNew();
        $qualityDetailsData_2->market_id = $griddata;
        $qualityDetailsData_2->identifer = 'QualityControl_2';
        $qualityDetailsData_2->data = $request->qualitycontrol_2;
        $qualityDetailsData_2->save();

        // Store data for Complaint Details grid
        $complaintDetailsData = MarketComplaintGrids::where(['market_id' => $griddata, 'identifer' => 'ComplaintDetails'])->firstOrNew();
        $complaintDetailsData->market_id = $griddata;
        $complaintDetailsData->identifer = 'ComplaintDetails';
        $complaintDetailsData->data = $request->complaint_details; // assuming your form data is coming as 'complaint_details'
        $complaintDetailsData->save();
        // dd($complaintDetailsData);

        $changecontrolDetailsData = MarketComplaintGrids::where(['market_id' => $griddata, 'identifer' => 'ChangeControlCapaDetails'])->firstOrNew();
        $changecontrolDetailsData->market_id = $griddata;
        $changecontrolDetailsData->identifer = 'ChangeControlCapaDetails';
        $changecontrolDetailsData->data = $request->changecontrol_capa_details; // assuming your form data is coming as 'complaint_details'
        $changecontrolDetailsData->save();

        $closureVerificationData = MarketComplaintGrids::where(['market_id' => $griddata, 'identifer' => 'ClosureVerificationDetails'])->firstOrNew();
        $closureVerificationData->market_id = $griddata;
        $closureVerificationData->identifer = 'ClosureVerificationDetails';
        $closureVerificationData->data = $request->closureverification_details; // assuming your form data is coming as 'closureverification_details'
        $closureVerificationData->save();


       



        // -------audit trrail show start update -----------
        // dd($marketcomplaint->Method_Tools_to_be_used_for);/
// dd($marketcomplaint->method_tools_to_be_used_for);
        
// dd($lastmarketcomplaint->Method_Tools_to_be_used_for,  $marketcomplaint->method_tools_to_be_used_for);
        // if ($lastmarketcomplaint->Method_Tools_to_be_used_for != $marketcomplaint->method_tools_to_be_used_for) {
            
        //     $history = new MarketComplaintAuditTrial();
        //     $history->market_id = $marketcomplaint->id;
        //     $history->activity_type = 'Method / Tools to be used for investigation';
        //     $history->previous = $lastmarketcomplaint->Method_Tools_to_be_used_for;
        //     $history->current = $marketcomplaint->Method_Tools_to_be_used_for;
        //     $history->comment = $request->additional_inform_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastmarketcomplaint->status;
        //     $history->change_to = "Not Applicable";
        //     $history->change_from = $lastmarketcomplaint->status;
        //     if (is_null($lastmarketcomplaint->Method_Tools_to_be_used_for) || $lastmarketcomplaint->Method_Tools_to_be_used_for === '') {
        //         $history->action_name = "New";
        //     } else {
        //         $history->action_name = "Update";
        //     }

        //     $history->save();
        // }

        $fields = [
            'record_number' => 'Record Number',
            'division_id' => 'Site/Location Code',
            'division_code' => 'Initiator',
            'intiation_date' => 'Date of Initiation',
            'assign_to' => 'Assigned To',
            'short_description' => 'Short Description',
            'due_date' => 'Due Date',
            'initiator_group' => 'Department Group',
            'initiator_group_code' => 'Department Group Code',
            'nameAddressagency' => 'Name & Address of the complainant agency',
            'nameDesgnationCom' => 'Name & Designation complainer',
            'phone_no' => 'Phone No',
            'email_address' => 'Email Address',
            'sample_recd' => 'Sample Recd',
            'test_results_recd' => 'Test Results recd',
            'severity_level_form' => 'Classification based on receipt of complaint',
            'acknowledgment_sent' => 'Acknowledgment sent to customer through marketing department by Head QA',
            'analysis_physical_examination' => 'Analysis / Physical examination of control sample to be done',
            'identification_cross_functional' => 'Identification of Cross functional departments by QA for review of root cause of Market complaint',
            'preliminary_investigation_report' => 'Preliminary Investigation Report sent by QA to complainant on',
            'attachment' => 'Attachment',
            'further_response_received' => 'Further Response Received from customer',
            'Details_of_Response' => 'Details of Response',
            'further_investigation_additional_testing' => 'Further investigation / Additional testing required',
            'method_tools_to_be_used_for' => 'Method / Tools to be used for investigation',
            'capa_qa_comments2' => 'Comments',
            'head_qulitiy_comment' => 'Head Quality Comment',
            'qa_review' => 'QA Review & Closure',
            'closure_attachment' => 'Closure Attachment',
            'due_date_extension' => 'Due Date Extension Justification',
        ];
        
        // Retrieve the last saved market complaint for comparison
        $lastmarketcomplaint = MarketComplaint::find($marketcomplaint->id);
        
        // Loop through each field and create an audit trail entry
        foreach ($fields as $field => $activity_type) {
            $previousValue = $lastmarketcomplaint->$field ?? null;
            $currentValue = $marketcomplaint->$field ?? null;
        
            // Check if the field value has changed
            if ($previousValue !== $currentValue) {
                $history = new MarketComplaintAuditTrial();
                $history->market_id = $marketcomplaint->id;
                $history->activity_type = $activity_type;
                $history->previous = $previousValue ?? "Null";
                $history->current = $currentValue ?? "Null";
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $marketcomplaint->status;
                $history->change_to = "Opened";
                $history->change_from = "Initiation";
        
                // Determine the action type (New or Update)
                if (is_null($previousValue) || $previousValue === '') {
                    $history->action_name = "New";
                } else {
                    $history->action_name = "Update";
                }
        
                $history->save();
            }
        }
        
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

public function store_audit_review(Request $request, $id)
{
        $history = new AuditReviewersDetails;
        $history->deviation_id = $id;
        $history->user_id = Auth::user()->id;
        $history->reviewer_comment = $request->reviewer_comment;
        $history->reviewer_comment_by = Auth::user()->name;
        $history->reviewer_comment_on = Carbon::now()->toDateString();
        $history->save();

    return redirect()->back();
}


}
