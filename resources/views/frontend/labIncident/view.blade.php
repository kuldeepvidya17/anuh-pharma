@extends('frontend.layout.main')
@section('container')
    <style>
        textarea.note-codable {
            display: none !important;
        }

        header {
            display: none;
        }
    </style>

    @php
        $users = DB::table('users')->get();
    @endphp

    <div class="form-field-head">

        <div class="division-bar">
            <strong>Site Division/Project</strong> :
            {{ Helpers::getDivisionName($data->division_id) }} / Lab Incident
        </div>
    </div>

    {{-- ---------------------- --}}
    <div id="change-control-view">
        <div class="container-fluid">

            <div class="inner-block state-block">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="main-head">Record Workflow </div>

                    <div class="d-flex" style="gap:20px;">
                        @php
                        $userRoles = DB::table('user_roles')->where(['user_id' => Auth::user()->id, 'q_m_s_divisions_id' => $data->division_id])->get();
                        $userRoleIds = $userRoles->pluck('q_m_s_roles_id')->toArray();
                    @endphp
                        {{-- <button class="button_theme1" onclick="window.print();return false;"
                            class="new-doc-btn">Print</button> --}}
                        <button class="button_theme1"> <a class="text-white"
                                href="{{ route('audittrialLabincident', $data->id) }}"> Audit Trail </a> </button>

                        @if ($data->stage == 1)
                        <!-- && (in_array(3, $userRoleIds) || in_array(18, $userRoleIds)) -->
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Submit
                            </button>
                        @elseif($data->stage == 2)
                        <!-- && (in_array(4, $userRoleIds) || in_array(18, $userRoleIds)) -->
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Incident Review Completed
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#cancel-modal">
                                Cancellation Request
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#rejection-modal">
                                Request More Info
                            </button>
                        @elseif($data->stage == 3)
                        <!-- && (in_array(10, $userRoleIds) || in_array(18, $userRoleIds)) -->
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Investigation Completed
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#rejection-modal">
                                Request More Info
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#child-modal">
                                Child
                            </button>
                        @elseif($data->stage == 4)
                        <!-- && (in_array(10, $userRoleIds) || in_array(18, $userRoleIds)) -->
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                All Activities Completed
                            </button>
                        @elseif($data->stage == 5)
                        <!-- && (in_array(10, $userRoleIds) || in_array(18, $userRoleIds)) -->
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Review
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#rejection-modal">
                                Further Investigation Required
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#child-modal1">
                                Child
                            </button>
                        @elseif($data->stage == 6)
                        <!-- && (in_array(7, $userRoleIds) || in_array(18, $userRoleIds)) -->
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                QA Review Complete
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#rejection-modal">
                                Return To Pending CAPA
                            </button>
                        @elseif($data->stage == 7)
                        <!-- && (in_array(9, $userRoleIds) || in_array(18, $userRoleIds) || in_array(7, $userRoleIds)) -->
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                QA Head Approval Complete
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#rejection-modal">
                                Return to QA Review
                            </button>
                            <!-- <button class="button_theme1"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}">
                                    Exit
                                </a> </button> -->
                        @endif
                        <button class="button_theme1"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}"> Exit
                            </a> </button>

                    </div>

                </div>
                <div class="status">
                    <div class="head">Current Status</div>
                    {{-- ------------------------------By Pankaj-------------------------------- --}}
                    @if ($data->stage == 0)
                        <div class="progress-bars">
                            <div class="bg-danger">Closed-Cancelled</div>
                        </div>
                    @else
                        <div class="progress-bars">
                            @if ($data->stage >= 1)
                                <div class="active">Opened</div>
                            @else
                                <div class="">Opened</div>
                            @endif

                            @if ($data->stage >= 2)
                                <div class="active">Pending Incident Review </div>
                            @else
                                <div class="">Pending Incident Review</div>
                            @endif

                            @if ($data->stage >= 3)
                                <div class="active">Pending Investigation</div>
                            @else
                                <div class="">Pending Investigation</div>
                            @endif

                            @if ($data->stage >= 4)
                                <div class="active">Pending Activity Completion</div>
                            @else
                                <div class="">Pending Activity Completion</div>
                            @endif
                            @if ($data->stage >= 5)
                                <div class="active">Pending CAPA</div>
                            @else
                                <div class="">Pending CAPA</div>
                            @endif
                            @if ($data->stage >= 6)
                                <div class="active">Pending QA Review</div>
                            @else
                                <div class="">Pending QA Review</div>
                            @endif
                            @if ($data->stage >= 7)
                                <div class="active">Pending QA Head Approval Complete</div>
                            @else
                                <div class="">Pending QA Head Approval Complete</div>
                            @endif
                            @if ($data->stage >= 8)
                                <div class="bg-danger">Closed - Done</div>
                            @else
                                <div class="">Closed - Done</div>
                            @endif
                    @endif



                </div>
                {{-- @endif --}}
                {{-- ---------------------------------------------------------------------------------------- --}}
            </div>
        </div>

    </div>
    {{-- ======================================
                    DATA FIELDS
    ======================================= --}}
    <div id="change-control-fields">
        <div class="container-fluid">

            <!-- Tab links -->
            <div class="cctab">
                <button class="cctablinks active" onclick="openCity(event, 'CCForm1')">General Information</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm2')">Incident Details</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm3')">Investigation Details</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm4')">CAPA</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm5')">QA Review</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm6')">QA Head/Designee Approval</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm7')">Activity Log</button>
            </div>

            <form action="{{ route('LabIncidentUpdate', $data->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                <div id="step-form">

                    <!-- General information content -->
                    <div id="CCForm1" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="RLS Record Number"><b>Record Number</b></label>
                                        <input disabled type="text" name="record_number"
                                            value="{{ Helpers::getDivisionName($data->division_id) }}/LI/{{ Helpers::year($data->created_at) }}/{{ $data->record }}">
                                        {{-- <div class="static">QMS-EMEA/CAPA/{{ date('Y') }}/{{ $record_number }}</div> --}}
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Division Code"><b>Site/Location Code</b></label>
                                        <input readonly type="text" name="division_id" value="{{ Helpers::getDivisionName($data->division_id) }}">
                                        {{-- <div class="static">QMS-North America</div> --}}
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator"><b>Initiator</b></label>
                                        {{-- <div class="static">{{ Auth::user()->name }}</div> --}}
                                        <input disabled type="text" name="division_code"
                                            value="{{ $data->initiator_name }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Date Due"><b>Date of Initiation</b></label>
                                        <input disabled type="text" value="{{ Helpers::getdateFormat($data->intiation_date) }}" name="intiation_date">
                                        <input type="hidden"  name="intiation_date">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="group-input">
                                        <label for="search">
                                            Assigned To <span class="text-danger"></span>
                                        </label>
                                        <select id="select-state" placeholder="Select..." name="assign_to" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>
                                            <option value="">Select a value</option>
                                            @foreach ($users as $key=> $value)
                                                <option  @if ($data->assign_to == $value->id) selected @endif  value="{{ $value->id }}">{{ $value->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('assign_to')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                             
                                <div class="col-lg-6 new-date-data-field">
                                    <div class="group-input input-date">
                                        <label for="Date Due"> Due Date</label>
                                        <div><small class="text-primary">Please mention expected date of completion</small>
                                        </div>
                                        <div class="calenderauditee">
                                            <input type="text" id="due_date" readonly
                                                placeholder="DD-MMM-YYYY" value="{{ Helpers::getdateFormat($data->due_date) }}" {{ $data->stage == 0 || $data->stage == 8 ? 'disabled' : ''}}/>
                                            <input type="date" name="due_date" {{ $data->stage == 0 || $data->stage == 8 ? 'disabled' : ''}}  min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input"
                                                oninput="handleDateInput(this, 'due_date')" />
                                        </div>
                                    </div>
                                </div> 
                                
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Name of Product">Name of Product/Material</label>
                                        <input name="name_of_product_material" value="{{ $data->name_of_product_material }}" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}></input>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Batch No.">Batch No./A. R. Number</label>
                                        <input name="batch_number" value="{{ $data->batch_number }}" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}></input>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="group-input">
                                        <label for="Stage">Stage</label>
                                        <input name="stage_number" value="{{ $data->stage_number }}" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}></input>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="group-input">
                                        <label for="Reference Specification No.">Reference Specification No./ SOP No./ DOC No.</label>
                                        <input name="reference_specification_number" value="{{ $data->reference_specification_number }}" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}></input>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Test">Test</label>
                                        <textarea name="Document_Details" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->Document_Details }}</textarea>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Short Description">Description of the Incident<span
                                                class="text-danger">*</span></label><span id="rchars">255</span>
                                        characters remaining
                                        
                                        <input name="short_desc"   id="docname" type="text" value="{{ $data->short_desc }}"   maxlength="255" required  {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}></input>
                                    </div>
                                    <p id="docnameError" style="color:red">**Short Description is required</p>

                                </div>

                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Immediate action taken">Immediate action taken</label>
                                        <textarea name="immediate_action_taken" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->immediate_action_taken }}</textarea>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Investigation details">Investigation details</label>
                                        <textarea name="investigation_details" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->investigation_details }}</textarea>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Investigation by Concern Department">Investigation by Concern Department(if Applicable: Yes/No)</label>
                                        <textarea name="investigation_concern_department" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->investigation_concern_department }}</textarea>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Root Cause">Root Cause</label>
                                        <textarea name="root_cause" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->root_cause }}</textarea>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Incident Root cause Categorization">Incident Root cause Categorization</label>
                                        <select name="is_repeat_gi" id="assignableSelect" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>
                                            <option value="0">Enter Your Selection Here</option>
                                            <option value="Power Outage Error" {{ $data->is_repeat_gi == 'Power Outage Error' ? 'selected' : '' }}>Power Outage Error</option>
                                            <option value="Human error" {{ $data->is_repeat_gi == 'Human error' ? 'selected' : '' }}>Human error</option>
                                            <option value="Instrument Failure error" {{ $data->is_repeat_gi == 'Instrument Failure error' ? 'selected' : '' }}>Instrument Failure error</option>
                                            <option value="Spillage of the sample solution" {{ $data->is_repeat_gi == 'Spillage of the sample solution' ? 'selected' : '' }}>Spillage of the sample solution</option>
                                            <option value="Incorrect Instrument Parameter" {{ $data->is_repeat_gi == 'Incorrect Instrument Parameter' ? 'selected' : '' }}>Incorrect Instrument Parameter</option>
                                            <option value="System Suitability Failure" {{ $data->is_repeat_gi == 'System Suitability Failure' ? 'selected' : '' }}>System Suitability Failure</option>
                                            <option value="Wrong MOA" {{ $data->is_repeat_gi == 'Wrong MOA' ? 'selected' : '' }}>Wrong MOA</option>
                                            <option value="Communication error" {{ $data->is_repeat_gi == 'Communication error' ? 'selected' : '' }}>Communication error</option>
                                            <option value="yes" {{ $data->is_repeat_gi == 'yes' ? 'selected' : '' }}>Miscellaneous/ Others (Describe in brief)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6" id="rootCauseGroup" style="display: none;">
                                    <div class="group-input">
                                        <label for="Miscellaneous">Miscellaneous/Others(Describe in brief)<span class="text-danger">*</span></label>
                                        <textarea name="miscellaneous_others" id="rootCauseTextarea" rows="3" placeholder="Enter Miscellaneous/Others(Describe in brief)" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->miscellaneous_others }}</textarea>

                                    </div>
                                </div>

                                <script>
                                    document.addEventListener("DOMContentLoaded", function() {
                                        // Initialize the display of the textarea based on the current value
                                        toggleRootCauseInput();

                                        function toggleRootCauseInput() {
                                            var selectValue = document.getElementById("assignableSelect").value.toLowerCase(); // Convert value to lowercase for consistency
                                            var rootCauseGroup = document.getElementById("rootCauseGroup");
                                            var rootCauseTextarea = document.getElementById("rootCauseTextarea");

                                            if (selectValue === "yes") {
                                                rootCauseGroup.style.display = "block";  // Show the textarea if "Yes" is selected
                                                rootCauseTextarea.setAttribute('required', 'required');  // Make textarea required
                                            } else {
                                                rootCauseGroup.style.display = "none";   // Hide the textarea if "No" is selected
                                                rootCauseTextarea.removeAttribute('required');  // Remove required attribute
                                            }
                                        }

                                        // Attach the event listener
                                        document.getElementById("assignableSelect").addEventListener("change", toggleRootCauseInput);
                                    });
                                </script>

                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Current Control Measure">Current Control Measure</label>
                                        <textarea name="current_control_measure" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->current_control_measure }}</textarea>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Corrective Action">Corrective Action</label>
                                        <textarea name="Currective_Action" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->Currective_Action }}</textarea>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Preventive Action (if any)">Preventive Action (if any)</label>
                                        <textarea name="Preventive_Action" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->Preventive_Action }}</textarea>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Evaluation & Recommendation from QA">Evaluation & Recommendation from QA</label>
                                        <textarea name="evaluation_from_qa" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->evaluation_from_qa }}</textarea>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Conclusion & Disposition by Head Quality/Designee">Conclusion & Disposition by Head Quality/Designee</label>
                                        <textarea name="conclusion_by_head_quality" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->conclusion_by_head_quality }}</textarea>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Justification for Delay in Closing">Justification for Delay in Closing (if applicable - Yes/No)</label>
                                        <textarea name="justification_delay_closing" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->justification_delay_closing }}</textarea>
                                    </div>
                                </div>

                                <div class="col-lg-6 new-date-data-field">
                                    <div class="group-input input-date">
                                        <label for="Date Due">Target Closure Date</label>
                                        <div class="calenderauditee">
                                            <input type="text" id="target_close_date" value="{{ Helpers::getdateFormat($data->target_close_date) }}" readonly
                                                placeholder="DD-MMM-YYYY"/>
                                            <input type="date" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }} name="target_close_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input"
                                                oninput="handleDateInput(this, 'target_close_date')"  />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 new-date-data-field">
                                    <div class="group-input input-date">
                                        <label for="Date Due">Tentative extended closure date</label>
                                        <div class="calenderauditee">
                                            <input type="text" id="tentavie_extended_closure_date" value="{{ Helpers::getdateFormat($data->tentavie_extended_closure_date) }}" readonly
                                                placeholder="DD-MMM-YYYY"/>
                                            <input type="date" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }} name="tentavie_extended_closure_date" value="{{ $data->tentavie_extended_closure_date }}" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input"
                                                oninput="handleDateInput(this, 'tentavie_extended_closure_date')"  />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Preventive Action (if any)">Justification</label>
                                        <textarea name="justification" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->justification }}</textarea>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Preventive Action (if any)">Closure Verification</label>
                                        <textarea name="closure_verification" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->closure_verification }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="button-block">
                                <button type="submit" id="ChangesaveButton" class="saveButton" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>Save</button>
                                <button type="button" id="ChangeNextButton" class="nextButton">Next</button>
                                <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}"> Exit </a> </button>
                            </div>
                        </div>
                    </div>

                    <!-- Incident Details content -->
                    <div id="CCForm2" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Incident Details">Incident Details</label>
                                        <textarea name="Incident_Details" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->Incident_Details }}</textarea>
                                    </div>
                                </div>
                                <!-- <div class="col-12">
                                    <div class="group-input">
                                        <label for="Document Details ">Document Details</label>
                                        <textarea name="Document_Details" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->Document_Details }}</textarea>
                                    </div>
                                </div> -->
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Instrument Details">Instrument Details</label>
                                        <textarea name="Instrument_Details" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->Instrument_Details }}</textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Involved Personnel">Involved Personnel</label>
                                        <textarea name="Involved_Personnel" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->Involved_Personnel }}</textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Product Details,If Any">Product Details,If Any</label>
                                        <textarea name="Product_Details" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->Product_Details }}</textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Supervisor Review Comments">Supervisor Review Comments</label>
                                        <textarea name="Supervisor_Review_Comments" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->Supervisor_Review_Comments }}</textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Attachments">Attachments</label>
                                        <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                        {{-- <input type="file" id="myfile" name="Attachments" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}
                                            value="{{ $data->Attachments }}"> --}}
                                            <div class="file-attachment-field">
                                                <div class="file-attachment-list" id="Attachments">
                                                    @if ($data->Attachments)
                                                    @foreach (json_decode($data->Attachments) as $file)
                                                        <h6 type="button" class="file-container text-dark"
                                                            style="background-color: rgb(243, 242, 240);">
                                                            <b>{{ $file }}</b>
                                                            <a href="{{ asset('upload/' . $file) }}"
                                                                target="_blank"><i class="fa fa-eye text-primary"
                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                            <a type="button" class="remove-file"
                                                                data-file-name="{{ $file }}"><i
                                                                    class="fa-solid fa-circle-xmark"
                                                                    style="color:red; font-size:20px;"></i></a>
                                                        </h6>
                                                    @endforeach
                                                @endif
                                                </div>
                                                <div class="add-btn">
                                                    <div>Add</div>
                                                    <input {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }} type="file" id="myfile" name="Attachments[]"
                                                        oninput="addMultipleFiles(this, 'Attachments')" multiple>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                                {{-- <div class="col-12 sub-head">
                                    Cancelation
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Cancelation Remarks">Cancelation Remarks</label>
                                        <textarea name="Cancelation_Remarks" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->Cancelation_Remarks }}</textarea>
                                    </div>
                                </div> --}}
                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>Save</button>
                                        <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}"> Exit </a> </button>
                            </div>
                        </div>
                    </div>

                    <!-- Investigation Details content -->
                    <div id="CCForm3" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                {{-- <div class="col-12 sub-head">
                                    Questionnaire
                                </div>  --}}
                                {{-- <div class="col-12">
                                    <div class="group-input">
                                        <label for="INV Questionnaire">INV Questionnaire</label>
                                        <div class="static">Question datafield</div>
                                    </div>
                                </div> --}}
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Inv Attachments">Inv Attachment</label>
                                        <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                        {{-- <input type="file" id="myfile" name="Inv_Attachment" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}
                                            value="{{ $data->Inv_Attachment }}"> --}}
                                            <div class="file-attachment-field">
                                                <div class="file-attachment-list" id="Inv_Attachment">
                                                    @if ($data->Inv_Attachment)
                                                    @foreach (json_decode($data->Inv_Attachment) as $file)
                                                        <h6 type="button" class="file-container text-dark"
                                                            style="background-color: rgb(243, 242, 240);">
                                                            <b>{{ $file }}</b>
                                                            <a href="{{ asset('upload/' . $file) }}"
                                                                target="_blank"><i class="fa fa-eye text-primary"
                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                            <a type="button" class="remove-file"
                                                                data-file-name="{{ $file }}"><i
                                                                    class="fa-solid fa-circle-xmark"
                                                                    style="color:red; font-size:20px;"></i></a>
                                                        </h6>
                                                    @endforeach
                                                @endif
                                                </div>
                                                <div class="add-btn">
                                                    <div>Add</div>
                                                    <input {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }} type="file" id="myfile" name="Inv_Attachment[]"
                                                        oninput="addMultipleFiles(this, 'Inv_Attachment')" multiple>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Investigation Details ">Investigation Details</label>
                                        <textarea name="Investigation_Details_sec" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->Investigation_Details_sec }}</textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Action Taken">Action Taken</label>
                                        <textarea name="Action_Taken" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->Action_Taken }}</textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Root Cause">Root Cause</label>
                                        <textarea name="Root_Cause_sec" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->Root_Cause_sec }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>Save</button>
                                        <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}"> Exit </a> </button>
                            </div>
                        </div>
                    </div>

                    <!-- CAPA content -->
                    <div id="CCForm4" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <!-- <div class="col-12">
                                    <div class="group-input">
                                        <label for="Currective Action">Corrective Action</label>
                                        <textarea name="Currective_Action" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->Currective_Action }}</textarea>
                                    </div>
                                </div> -->
                                <!-- <div class="col-12">
                                    <div class="group-input">
                                        <label for="Preventive Action">Preventive Action</label>
                                        <textarea name="Preventive_Action" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->Preventive_Action }}</textarea>
                                    </div>
                                </div> -->
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Corrective & Preventive Action">Corrective & Preventive Action</label>
                                        <textarea name="Corrective_Preventive_Action" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->Corrective_Preventive_Action }}</textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="CAPA Attachments">CAPA Attachment</label>
                                        <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                        {{-- <input type="file" id="myfile" name="CAPA_Attachment" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}
                                            value="{{ $data->CAPA_Attachment }}"> --}}
                                            <div class="file-attachment-field">
                                                <div class="file-attachment-list" id="CAPA_Attachment">
                                                    @if ($data->CAPA_Attachment)
                                                    @foreach (json_decode($data->CAPA_Attachment) as $file)
                                                        <h6 type="button" class="file-container text-dark"
                                                            style="background-color: rgb(243, 242, 240);">
                                                            <b>{{ $file }}</b>
                                                            <a href="{{ asset('upload/' . $file) }}"
                                                                target="_blank"><i class="fa fa-eye text-primary"
                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                            <a type="button" class="remove-file"
                                                                data-file-name="{{ $file }}"><i
                                                                    class="fa-solid fa-circle-xmark"
                                                                    style="color:red; font-size:20px;"></i></a>
                                                        </h6>
                                                    @endforeach
                                                @endif
                                                </div>
                                                <div class="add-btn">
                                                    <div>Add</div>
                                                    <input {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }} type="file" id="myfile" name="CAPA_Attachment[]"
                                                        oninput="addMultipleFiles(this, 'CAPA_Attachment')" multiple>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>Save</button>
                                        <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}"> Exit </a> </button>
                            </div>
                        </div>
                    </div>

                    <!-- QA Review content -->
                    <div id="CCForm5" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="QA Review Comments">QA Review Comments</label>
                                        <textarea name="QA_Review_Comments" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->QA_Review_Comments }}</textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="QA Head Attachments">QA Head Attachment</label>
                                        <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                        {{-- <input type="file" id="myfile" name="QA_Head_Attachment" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}
                                            value="{{ $data->QA_Head_Attachment }}"> --}}
                                            <div class="file-attachment-field">
                                                <div class="file-attachment-list" id="QA_Head_Attachment">
                                                    @if ($data->QA_Head_Attachment)
                                                    @foreach (json_decode($data->QA_Head_Attachment) as $file)
                                                        <h6 type="button" class="file-container text-dark"
                                                            style="background-color: rgb(243, 242, 240);">
                                                            <b>{{ $file }}</b>
                                                            <a href="{{ asset('upload/' . $file) }}"
                                                                target="_blank"><i class="fa fa-eye text-primary"
                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                            <a type="button" class="remove-file"
                                                                data-file-name="{{ $file }}"><i
                                                                    class="fa-solid fa-circle-xmark"
                                                                    style="color:red; font-size:20px;"></i></a>
                                                        </h6>
                                                    @endforeach
                                                @endif
                                                </div>
                                                <div class="add-btn">
                                                    <div>Add</div>
                                                    <input {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }} type="file" id="myfile" name="QA_Head_Attachment[]"
                                                        oninput="addMultipleFiles(this, 'QA_Head_Attachment')" multiple>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>Save</button>
                                        <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}"> Exit </a> </button>
                            </div>
                        </div>
                    </div>

                    <!-- QA Head/Designee Approval content -->
                    <div id="CCForm6" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="col-12 sub-head">
                                    Closure
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="QA Head/Designee Comments">QA Head/Designee Comments</label>
                                        <textarea name="QA_Head" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->QA_Head }}</textarea>
                                    </div>
                                </div>
                            <div class="col-lg-6">
                                    <!-- <div class="group-input">
                                        <label for="Effectiveness Check required?">Effectiveness Check
                                            required?</label>
                                        <select name="Effectiveness_Check" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>
                                            <option value="">Enter Your Selection Here</option>
                                            <option value="yes" @if ($data->Effectiveness_Check == 'yes') selected @endif>yes
                                            </option>
                                            <option value="no" @if ($data->Effectiveness_Check == 'no') selected @endif>no
                                            </option>
                                        </select>
                                    </div> -->
                                </div> 
                               
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Incident Type">Incident Type</label>
                                        <select name="Incident_Type" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>
                                            <option value="">Enter Your Selection Here</option>
                                            <option value="1" @if ($data->Incident_Type == '1') selected @endif>Type
                                                A
                                            </option>
                                            <option value="2" @if ($data->Incident_Type == '2') selected @endif>Type
                                                B
                                            </option>
                                            <option value="3" @if ($data->Incident_Type == '3') selected @endif>Type
                                                c
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Conclusion">Conclusion</label>
                                        <textarea name="Conclusion"{{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }} >{{ $data->Conclusion }}</textarea>
                                    </div>
                                </div>
                                <div class="col-12 sub-head">
                                    Extension Justification
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="due_date_extension">Due Date Extension Justification</label>
                                        <div><small class="text-primary">Please Mention justification if due date is crossed</small></div>
                                        <span id="rchar">240</span>
                                        characters remaining
                                        <textarea name="due_date_extension" id="duedoc" type="text"    maxlength="240"{{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{$data->due_date_extension}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>Save</button>
                                        <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}"> Exit </a> </button>
                            </div>
                        </div>
                    </div>

                    <!-- Activity Log content -->
                    <div id="CCForm7" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Submitted By">Submitted By</label>
                                        <div class="static">{{ $data->submitted_by }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Submitted On">Submitted On</label>
                                        <div class="Date">{{ $data->submitted_on }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Incident Review Completed By">Incident Review Completed
                                            By</label>
                                        <div class="static">{{ $data->incident_review_completed_by }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Incident Review Completed On">Incident Review Completed
                                            On</label>
                                        <div class="Date">{{ $data->incident_review_completed_on }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Investigation Completed By">Investigation Completed By</label>
                                        <div class="static">{{ $data->investigation_completed_by }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Investigation Completed On">Investigation Completed On</label>
                                        <div class="Date">{{ $data->investigation_completed_on }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="QA Review Completed By">QA Review Completed By</label>
                                        <div class="static">{{ $data->qA_review_completed_by }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="QA Review Completed On">QA Review Completed On</label>
                                        <div class="Date">{{ $data->qA_review_completed_on }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="QA Head Approval Completed By">QA Head Approval Completed By</label>
                                        <div class="static">{{ $data->qA_head_approval_completed_by }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="QA Head Approval Completed On">QA Head Approval Completed On</label>
                                        <div class="Date">{{ $data->qA_head_approval_completed_on }}</div>
                                    </div>
                                </div>
                               
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="All Activities Completed By">All Activities Completed By</label>
                                        <div class="static">{{ $data->all_activities_completed_by }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="All Activities Completed On">All Activities Completed On</label>
                                        <div class="Date">{{ $data->all_activities_completed_on }}</div>
                                    </div>
                                </div>
                                 <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Review Completed By">Review Completed By</label>
                                        <div class="static">{{$data->review_completed_by}}</div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Review Completed On">Review Completed On</label>
                                        <div class="Date">{{$data->review_completed_on}}</div>
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Cancelled By">Cancelled By</label>
                                        <div class="static">{{ $data->cancelled_by }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Cancelled On">Cancelled On</label>
                                        <div class="Date">{{ $data->cancelled_on }}</div>
                                    </div>
                                </div>
                                {{-- <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="All Activities Completed By">All Activities Completed By</label>
                                        <div class="static">{{ $data->all_activities_completed_by }}</div>
                                    </div>
                                </div> --}}
                                {{-- <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="All Activities Completed On">All Activities Completed On</label>
                                        <div class="Date">{{ $data->all_activities_completed_on }}</div>
                                    </div>
                                </div> --}}
                                {{-- <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Review Completed By">Review Completed By</label>
                                        <div class="static">{{$data->review_completed_by}}</div>
                                    </div>
                                </div> --}}
                                {{-- <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Review Completed On">Review Completed On</label>
                                        <div class="Date">{{$data->review_completed_on}}</div>
                                    </div>
                                </div> --}}
                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>Save</button>
                                        <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="submit" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>Submit</button>
                                <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}"> Exit </a> </button>
                            </div>
                        </div>
                    </div>

                </div>
            </form>

        </div>
    </div>
    {{-- ----------------------modal------------- --}}

    <div class="modal fade" id="rejection-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">E-Signature</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ url('rcms/RejectStateChangeEsign', $data->id) }}" method="POST">
                    @csrf
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="mb-3 text-justify">
                            Please select a meaning and a outcome for this task and enter your username
                            and password for this task. You are performing an electronic signature,
                            which is legally binding equivalent of a hand written signature.
                        </div>
                        <div class="group-input">
                            <label for="username">Username  <span
                                class="text-danger">*</span></label>
                            <input type="text" name="username" required>
                        </div>
                        <div class="group-input">
                            <label for="password">Password  <span
                                class="text-danger">*</span></label>
                            <input type="password" name="password" required>
                        </div>
                        <div class="group-input">
                            <label for="comment">Comment <span
                                class="text-danger">*</span></label>
                            <input type="comment" name="comment" required>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <!-- <div class="modal-footer">
                        <button type="submit" data-bs-dismiss="modal">Submit</button>
                        <button>Close</button>
                    </div> -->
                    <div class="modal-footer">
                              <button type="submit">Submit</button>
                                <button type="button" data-bs-dismiss="modal">Close</button>
                              
                     </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="signature-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">E-Signature</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('StageChangeLabIncident', $data->id) }}" method="POST">
                    @csrf
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="mb-3 text-justify">
                            Please select a meaning and a outcome for this task and enter your username
                            and password for this task. You are performing an electronic signature,
                            which is legally binding equivalent of a hand written signature.
                        </div>
                        <div class="group-input">
                            <label for="username">Username  <span
                                class="text-danger">*</span></label>
                            <input type="text" name="username" required>
                        </div>
                        <div class="group-input">
                            <label for="password">Password  <span
                                class="text-danger">*</span></label>
                            <input type="password" name="password" required>
                        </div>
                        <div class="group-input">
                            <label for="comment">Comment</label>
                            <input type="comment" name="comment">
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <!-- <div class="modal-footer">
                        <button type="submit" data-bs-dismiss="modal">Submit</button>
                        <button>Close</button>
                    </div> -->
                    <div class="modal-footer">
                              <button type="submit">Submit</button>
                             <button type="button" data-bs-dismiss="modal">Close</button>                         
                   </div>
                </form>
            </div>
        </div>
    </div>

    

    <div class="modal fade" id="cancel-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">E-Signature</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form action="{{ route('LabIncidentCancel', $data->id) }}" method="POST">
                    @csrf
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="mb-3 text-justify">
                            Please select a meaning and a outcome for this task and enter your username
                            and password for this task. You are performing an electronic signature,
                            which is legally binding equivalent of a hand written signature.
                        </div>
                        <div class="group-input">
                            <label for="username">Username  <span
                                class="text-danger">*</span></label>
                            <input type="text" name="username" required>
                        </div>
                        <div class="group-input">
                            <label for="password">Password  <span
                                class="text-danger">*</span></label>
                            <input type="password" name="password" required>
                        </div>
                        <div class="group-input">
                            <label for="comment">Comment  <span
                                class="text-danger">*</span></label>
                            <input type="comment" name="comment" required>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <!-- <div class="modal-footer">
                        <button type="submit" data-bs-dismiss="modal">Submit</button>
                        <button>Close</button>
                    </div> -->
                    <div class="modal-footer">
                              <button type="submit">Submit</button>
                             <button type="button" data-bs-dismiss="modal">Close</button>                         
                   </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="child-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Child</h4>
                </div>
                <form action="{{ route('lab_incident_root_child', $data->id) }}" method="POST">
                    @csrf
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="group-input">
                            <label for="major">
                                <input type="radio" name="revision" id="major" value="Action-Item">
                                Root Cause Analysis
                            </label>
                        </div>

                    </div>

                    <!-- Modal footer -->
                    <!-- <div class="modal-footer">
                        <button type="button" data-bs-dismiss="modal">Close</button>
                        <button type="submit">Continue</button>
                    </div> -->
                    <div class="modal-footer">
                              <button type="submit">Submit</button>
                             <button type="button" data-bs-dismiss="modal">Close</button>                         
                   </div>
                </form>

            </div>
        </div>
    </div>
    <div class="modal fade" id="child-modal1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Child</h4>
                </div>
                <form action="{{ route('lab_incident_root_child', $data->id) }}" method="POST">
                    @csrf
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="group-input">
                            <label for="major">
                                <input type="radio" name="revision" id="major" value="Action-Item">
                                CAPA
                            </label>
                        </div>

                    </div>

                    <!-- Modal footer -->
                    <!-- <div class="modal-footer">
                        <button type="button" data-bs-dismiss="modal">Close</button>
                        <button type="submit">Continue</button>
                    </div> -->
                    <div class="modal-footer">
                              <button type="submit">Submit</button>
                             <button type="button" data-bs-dismiss="modal">Close</button>                         
                   </div>
                </form>

            </div>
        </div>
    </div>

    <style>
        #step-form>div {
            display: none
        }

        #step-form>div:nth-child(1) {
            display: block;
        }
    </style>

    <script>
        function openCity(evt, cityName) {
            var i, cctabcontent, cctablinks;
            cctabcontent = document.getElementsByClassName("cctabcontent");
            for (i = 0; i < cctabcontent.length; i++) {
                cctabcontent[i].style.display = "none";
            }
            cctablinks = document.getElementsByClassName("cctablinks");
            for (i = 0; i < cctablinks.length; i++) {
                cctablinks[i].className = cctablinks[i].className.replace(" active", "");
            }
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";
        }



        function openCity(evt, cityName) {
            var i, cctabcontent, cctablinks;
            cctabcontent = document.getElementsByClassName("cctabcontent");
            for (i = 0; i < cctabcontent.length; i++) {
                cctabcontent[i].style.display = "none";
            }
            cctablinks = document.getElementsByClassName("cctablinks");
            for (i = 0; i < cctablinks.length; i++) {
                cctablinks[i].className = cctablinks[i].className.replace(" active", "");
            }
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";

            // Find the index of the clicked tab button
            const index = Array.from(cctablinks).findIndex(button => button === evt.currentTarget);

            // Update the currentStep to the index of the clicked tab
            currentStep = index;
        }

        const saveButtons = document.querySelectorAll(".saveButton");
        const nextButtons = document.querySelectorAll(".nextButton");
        const form = document.getElementById("step-form");
        const stepButtons = document.querySelectorAll(".cctablinks");
        const steps = document.querySelectorAll(".cctabcontent");
        let currentStep = 0;

        function nextStep() {
            // Check if there is a next step
            if (currentStep < steps.length - 1) {
                // Hide current step
                steps[currentStep].style.display = "none";

                // Show next step
                steps[currentStep + 1].style.display = "block";

                // Add active class to next button
                stepButtons[currentStep + 1].classList.add("active");

                // Remove active class from current button
                stepButtons[currentStep].classList.remove("active");

                // Update current step
                currentStep++;
            }
        }

        function previousStep() {
            // Check if there is a previous step
            if (currentStep > 0) {
                // Hide current step
                steps[currentStep].style.display = "none";

                // Show previous step
                steps[currentStep - 1].style.display = "block";

                // Add active class to previous button
                stepButtons[currentStep - 1].classList.add("active");

                // Remove active class from current button
                stepButtons[currentStep].classList.remove("active");

                // Update current step
                currentStep--;
            }
        }
    </script>
    <script>
        // JavaScript
        document.getElementById('initiator_group').addEventListener('change', function() {
            var selectedValue = this.value;
            document.getElementById('initiator_group_code').value = selectedValue;
        });
    </script>
     <script>
            document.addEventListener('DOMContentLoaded', function () {
                const removeButtons = document.querySelectorAll('.remove-file');

                removeButtons.forEach(button => {
                    button.addEventListener('click', function () {
                        const fileName = this.getAttribute('data-file-name');
                        const fileContainer = this.closest('.file-container');

                        // Hide the file container
                        if (fileContainer) {
                            fileContainer.style.display = 'none';
                        }
                    });
                });
            });
        </script>
        <script>
            var maxLength = 255;
            $('#docname').keyup(function() {
                var textlen = maxLength - $(this).val().length;
                $('#rchars').text(textlen);
            });
        </script>
        <script>
        var maxLength = 240;
        $('#duedoc').keyup(function() {
            var textlen = maxLength - $(this).val().length;
            $('#rchar').text(textlen);});
    </script>
@endsection
