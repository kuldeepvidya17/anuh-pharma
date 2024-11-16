@extends('frontend.layout.main')
@section('container')
    @php
        $users = DB::table('users')->get();
    @endphp
    <style>
        textarea.note-codable {
            display: none !important;
        }

        header {
            display: none;
        } 
        .remove-file  {
            color: white;
            cursor: pointer;
            margin-left: 10px;
        }

        .remove-file :hover {
            color: white;
        }
    </style>


    <script>
        function addMultipleFiles(input, block_id) {
            let block = document.getElementById(block_id);
            block.innerHTML = "";
            let files = input.files;
            for (let i = 0; i < files.length; i++) {
                let div = document.createElement('div');
                div.innerHTML += files[i].name;
                let viewLink = document.createElement("a");
                viewLink.href = URL.createObjectURL(files[i]);
                viewLink.textContent = "View";
                div.appendChild(viewLink);
                block.appendChild(div);
            }
        }
    </script>

    <script>
        function otherController(value, checkValue, blockID) {
            let block = document.getElementById(blockID)
            let blockTextarea = block.getElementsByTagName('textarea')[0];
            let blockLabel = block.querySelector('label span.text-danger');
            if (value === checkValue) {
                blockLabel.classList.remove('d-none');
                blockTextarea.setAttribute('required', 'required');
            } else {
                blockLabel.classList.add('d-none');
                blockTextarea.removeAttribute('required');
            }
        }
    </script>

    <div class="form-field-head">
        <div class="division-bar">
            <strong>Site Division/Project</strong> :
            {{ Helpers::getDivisionName($data->division_id) }}/ CAPA
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
                        <button class="button_theme1"> <a class="text-white" href="{{ url('CapaAuditTrial', $data->id) }}">
                                Audit Trail </a> </button>

                        @if ($data->stage == 1 && (in_array(3, $userRoleIds) || in_array(18, $userRoleIds)))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                CAPA Initiaion by the Concerned Department Head
                            </button>
                        @elseif($data->stage == 2 && (in_array(4, $userRoleIds) || in_array(18, $userRoleIds)))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#rejection-modal">
                                More Info Required
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Allocation of CAPA No.by QA
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#cancel-modal">
                                Cancel
                            </button>
                            {{-- <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#child-modal1">
                                Child
                            </button> --}}  
                        @elseif($data->stage == 3 && (in_array(7, $userRoleIds) || in_array(18, $userRoleIds)))
                               <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#modal1">
                              QA More Info Required
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                               Corrective Action Taken
                            </button>
                            <button id="major" type="button" class="button_theme1" data-bs-toggle="modal"
                                data-bs-target="#child-modal">
                                Child
                            </button>
                            {{-- <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#child-modal1">
                                Child
                            </button> --}}
                        @elseif($data->stage == 4 && (in_array(7, $userRoleIds) || in_array(18, $userRoleIds)))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Preventive Action 

                            </button>
                            <!-- <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#child-modal1">
                                Child
                            </button> -->
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#modal1">
                                Reject
                            </button>
                        @elseif($data->stage == 5)
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                               Implementation of CAPA
                            </button>
                        @elseif($data->stage == 6)
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                CAPA CLoser by QA
                            </button>
                    
                        @elseif($data->stage == 7)
                        <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#child-modal1">
                            Child
                        </button>
                        <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                            CAPA Effectiveness Strategy by QA-If Reconmmended
                        </button>
                        @elseif($data->stage == 8)
                        <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                            CAPA CLoser by QA
                        </button>
                        @elseif($data->stage == 9)
                        <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                            Closure of CAPA Effectiveness by QA
                        </button>
                
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
                            <div class="active">Concerned Department Head</div>
                        @else
                            <div class="">Concerned Department Head</div>
                        @endif
                    
                        @if ($data->stage >= 3)
                            <div class="active">Allocation of CAPA</div>
                        @else
                            <div class="">Allocation of CAPA</div>
                        @endif
                    
                        @if ($data->stage >= 4)
                            <div class="active">Corrective Action Taken</div>
                        @else
                            <div class="">Corrective Action Taken</div>
                        @endif
                    
                        @if ($data->stage >= 5)
                            <div class="active">Preventive Action</div>
                        @else
                            <div class="">Preventive Action</div>
                        @endif
                    
                        @if ($data->stage >= 6)
                            <div class="active">Implementation of CAPA</div>
                        @else
                            <div class="">Implementation of CAPA</div>
                        @endif
                    
                        @if ($data->stage >= 7)
                            <div class="active">CAPA Closer by QA</div>
                        @else
                            <div class="">CAPA Closer by QA</div>
                        @endif
                    
                        @if ($data->stage >= 8)
                            <div class="active">CAPA Effectiveness Strategy by QA</div>
                        @else
                            <div class="">CAPA Effectiveness Strategy by QA</div>
                        @endif
                    
                         @if ($data->stage >= 9)
                            <div class="active">Closure of CAPA Effectiveness by QA</div>
                        @else
                            <div class="">Closure of CAPA Effectiveness by QA</div>
                        @endif
                    
                        @if ($data->stage >= 10)
                            <div class="bg-danger">Closed - Done</div>
                        @else
                            <div class="">Closed - Done</div>
                        @endif 
                    </div>
                    
                @endif
                {{-- ---------------------------------------------------------------------------------------- --}}
            </div>
        </div>

        <div class="control-list">

            {{-- ======================================
                    DATA FIELDS
            ======================================= --}}
            <div id="change-control-fields">
                <div class="container-fluid">

                    <!-- Tab links -->
                    <div class="cctab">
                        <button class="cctablinks active" onclick="openCity(event, 'CCForm1')">General Information</button>
                        <button class="cctablinks" onclick="openCity(event, 'CCForm2')">Equipment/Material Info</button>
                        {{-- <button class="cctablinks" onclick="openCity(event, 'CCForm3')">Project/Study</button> --}}
                        <button class="cctablinks" onclick="openCity(event, 'CCForm4')">CAPA Details</button>
                        {{-- <button class="cctablinks" onclick="openCity(event, 'CCForm8')">Additional Information</button> --}}
                        {{-- <button class="cctablinks" onclick="openCity(event, 'CCForm7')">Group Comments</button> --}}
                        <button class="cctablinks" onclick="openCity(event, 'CCForm5')">CAPA Closure</button>
                        <button class="cctablinks" onclick="openCity(event, 'CCForm6')">Activity Log</button>
                    </div>

                    <form action="{{ route('capaUpdate', $data->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div id="step-form">

                            <!-- General information content -->
                            <div id="CCForm1" class="inner-block cctabcontent">
                                <div class="inner-block-content">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="RLS Record Number">CAPA No.</label>
                                                <input disabled type="text" name="record_number"
                                                    value="{{ Helpers::getDivisionName($data->division_id) }}/CAPA/{{ Helpers::year($data->created_at) }}/{{ $data->record }}">
                                                {{-- <div class="static"></div> --}}
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Division Code">Site/Location Code</label>
                                                <input readonly type="text" name="division_code"
                                                    value="{{ Helpers::getDivisionName(session()->get('division')) }}">
                                                <input type="hidden" name="division_id" value="{{ session()->get('division') }}">
                                                {{-- <div class="static">QMS-North America</div> --}}
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Initiator">Initiator</label>
                                                <input disabled type="text" name="initiator_id"
                                                    value="{{ $data->initiator_name }}">
                                                {{-- <div class="static"> </div> --}}
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Date Due">Date of Initiation</label>
                                                <input disabled type="text" value="{{ date('d-M-Y') }}" name="intiation_date">
                                                <input type="hidden" value="{{ date('Y-m-d') }}" name="intiation_date">
                                            </div>
                                        </div>

                                        <div class="col-lg-6 new-date-data-field">
                                            <div class="group-input input-date">
                                                <label for="due_date">Due Date</label>
                                                <div class="calenderauditee">
                                                    @php
                                                        $Date = isset($data->due_date)
                                                            ? new \DateTime($data->due_date)
                                                            : null;
                                                    @endphp
                                                    {{-- Format the date as desired --}}
                                                    <input type="text" id="due_date_display" placeholder="DD-MMM-YYYY"
                                                        value="{{ $Date ? $Date->format('d-M-Y') : '' }}" readonly />
            
                                                    <input type="date" name="due_date" id="due_date" class="hide-input"
                                                        min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                        value="{{ $data->due_date ?? '' }}"
                                                        oninput="handleDateInput(this, 'due_date_display')"
                                                        />
            
            
                                                </div>
                                            </div>
                                        </div>
            
                                        <script>
                                            function handleDateInput(input, displayId) {
                                                var display = document.getElementById(displayId);
                                                var date = new Date(input.value);
                                                var options = {
                                                    day: '2-digit',
                                                    month: 'short', // Change 'short' instead of 'Short'
                                                    year: 'numeric'
                                                };
                                                var formattedDate = date.toLocaleDateString('en-GB', options).replace(/ /g, '-');
                                                display.value = formattedDate;
                                            }
                                        </script>
            
            
                                        <style>
                                            .hide-input {
                                                display: none;
                                            }
                                        </style>
        

                                        <div class="col-12">
                                            <div class="group-input">
                                                <label for="Short Description">Short Description<span
                                                        class="text-danger">*</span></label><span id="rchars">255</span>characters remaining
                                                <textarea name="short_description"   id="docname" type="text"    maxlength="255" required  {{ $data->stage == 0 || $data->stage == 6 ? "disabled" : "" }}>{{ $data->short_description }}</textarea>
                                            </div>
                                            <p id="docnameError" style="color:red">**Short Description is required</p>
        
                                        </div>
        
                                        
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Initiator Group">Department</label>
                                                <select name="initiator_Group" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}
                                                     id="initiator_group">
                                                    <option value="CQA"
                                                        @if ($data->initiator_Group== 'CQA') selected @endif>Corporate
                                                        Quality Assurance</option>
                                                    <option value="QAB"
                                                        @if ($data->initiator_Group== 'QAB') selected @endif>Quality
                                                        Assurance Biopharma</option>
                                                    <option value="CQC"
                                                        @if ($data->initiator_Group== 'CQC') selected @endif>Central
                                                        Quality Control</option>
                                                    <option value="CQC"
                                                        @if ($data->initiator_Group== 'CQC') selected @endif>Manufacturing
                                                    </option>
                                                    <option value="PSG"
                                                        @if ($data->initiator_Group== 'PSG') selected @endif>Plasma
                                                        Sourcing Group</option>
                                                    <option value="CS"
                                                        @if ($data->initiator_Group== 'CS') selected @endif>Central
                                                        Stores</option>
                                                    <option value="ITG"
                                                        @if ($data->initiator_Group== 'ITG') selected @endif>Information
                                                        Technology Group</option>
                                                    <option value="MM"
                                                        @if ($data->initiator_Group== 'MM') selected @endif>Molecular
                                                        Medicine</option>
                                                    <option value="CL"
                                                        @if ($data->initiator_Group== 'CL') selected @endif>Central
                                                        Laboratory</option>
                                                    <option value="TT"
                                                        @if ($data->initiator_Group== 'TT') selected @endif>Tech
                                                        Team</option>
                                                    <option value="QA"
                                                        @if ($data->initiator_Group== 'QA') selected @endif>Quality
                                                        Assurance</option>
                                                    <option value="QM"
                                                        @if ($data->initiator_Group== 'QM') selected @endif>Quality
                                                        Management</option>
                                                    <option value="IA"
                                                        @if ($data->initiator_Group== 'IA') selected @endif>IT
                                                        Administration</option>
                                                    <option value="ACC"
                                                        @if ($data->initiator_Group== 'ACC') selected @endif>Accounting
                                                    </option>
                                                    <option value="LOG"
                                                        @if ($data->initiator_Group== 'LOG') selected @endif>Logistics
                                                    </option>
                                                    <option value="SM"
                                                        @if ($data->initiator_Group== 'SM') selected @endif>Senior
                                                        Management</option>
                                                    <option value="BA"
                                                        @if ($data->initiator_Group== 'BA') selected @endif>Business
                                                        Administration</option>

                                                </select>
                                            </div>
                                        </div>

                                        
                                        {{-- <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Division Code">Site/Location Code</label>
                                                <input disabled type="text" name="division_code"
                                                    value="{{ Helpers::getDivisionName($data->division_id) }}">
                                               
                                            </div>
                                        </div> --}}
                                        
                                        <div class="col-lg-6">
                                        <div class="group-input ">
                                            <label for="Date Due"><b>Date</b></label>
                                            <input disabled type="text" value="{{ date('d-M-Y') }}" name="intiation_date">
                                            <input type="hidden" value="{{ date('d-m-Y') }}" name="intiation_date">
                                        </div>
                                    </div>
                                       
                                        
                                        
                                       
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="source_of_capa">Source of CAPA</label>
                                                <div><small class="text-primary">Please select related information</small></div>
                                                <select name="source_of_capa" onchange="toggleOtherField(this.value)">
                                                    <option value="">Enter Your Selection Here</option>
                                                    <option value="deviation">Deviation</option>
                                                    <option value="Self_Inspection">Self Inspection</option>
                                                    <option value="market_complaint">Market Complaint</option>
                                                    <option value="oot">OOT</option>
                                                    <option value="External_Regulatory">External / Regulatory Audit Observation</option>
                                                    <option value="oos">OOS</option>
                                                    <option value="Input_from_Employees">Input from the Employees</option>
                                                    <option value="others">Others</option>
                                                </select>
                                            </div>
                                        </div>
                            
                                        <div class="col-lg-6">
                                            <div class="group-input" id="initiated_through_req" style="display: none;">
                                                <label for="others">Others<span class="text-danger d-none">*</span></label>
                                                <textarea name="others" class="form-control"></textarea>
                                            </div>
                                        </div>

                                        <script>
                                            // JavaScript function to show/hide the 'Others' text area
                                            function toggleOtherField(value) {
                                                var othersField = document.getElementById("initiated_through_req");
                                        
                                                // If 'Others' is selected, show the 'Others' field
                                                if (value === "others") {
                                                    othersField.style.display = "block";
                                                } else {
                                                    othersField.style.display = "none";
                                                }
                                            }
                                        </script>



                                        <div class="col-lg-6">
                                            <div class="group-input" id="source_document_name">
                                                <label for="initiated_through">Source Document Name / No
                                                       </label>
                                                <textarea name="source_document_name" value>{{ $data->source_document_name }}</textarea>
                                            </div>
                                        </div>

                                    
                                        <div class="col-12 sub-head">
                                            Proposed Corrective Action
                                        </div>
                                        <div class="col-12">
                                            <div class="group-input">
                                                <label for="Proposed Corrective Action">
                                                    Proposed Corrective Action
                                                    <button type="button" id="corrective_action_add">+</button>
                                                </label>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered" id="corrective_action_details" style="width: 100%;">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 100px;">Row #</th>
                                                                <th>Action</th>
                                                                <th>Responsibility (Department)</th>
                                                                <th>Target Date</th>
                                                                <th>Completion Date</th>
                                                                <th>Implementation Verified by QA</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if (!empty($proposedCorrectiveActionData) && is_array($proposedCorrectiveActionData->data))
                                                                @foreach ($proposedCorrectiveActionData->data as $index => $action)
                                                                    <tr>
                                                                        <td><input disabled type="text" name="corrective_action_details[{{ $index }}][serial]" value="{{ $index + 1 }}"></td>
                                                                        <td><input type="text" name="corrective_action_details[{{ $index }}][action]" value="{{ $action['action'] }}"></td>
                                                                        <td><input type="text" name="corrective_action_details[{{ $index }}][responsibility]" value="{{ $action['responsibility'] }}"></td>
                                                                        <td><input type="date" name="corrective_action_details[{{ $index }}][target_date]" value="{{ $action['target_date'] }}"></td>
                                                                        <td><input type="date" name="corrective_action_details[{{ $index }}][completion_date]" value="{{ $action['completion_date'] }}"></td>
                                                                        <td><input type="text" name="corrective_action_details[{{ $index }}][verified_by_qa]" value="{{ $action['verified_by_qa'] }}"></td>
                                                                        <td><button type="button" class="removeRowBtn">Remove</button></td>
                                                                    </tr>
                                                                @endforeach
                                                            @else
                                                                <tr class="no-data">
                                                                    <td colspan="7">No data found</td>
                                                                </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <script>
                                            $(document).ready(function() {
                                                // Add new row in Proposed Corrective Action table
                                                $('#corrective_action_add').click(function(e) {
                                                    e.preventDefault();
                                        
                                                    function generateCorrectiveActionRow(serialNumber) {
                                                        return (
                                                            '<tr>' +
                                                            '<td><input disabled type="text" name="corrective_action_details[' + serialNumber +
                                                            '][serial]" value="' + (serialNumber + 1) + '"></td>' +
                                                            '<td><input type="text" name="corrective_action_details[' + serialNumber + '][action]"></td>' +
                                                            '<td><input type="text" name="corrective_action_details[' + serialNumber + '][responsibility]"></td>' +
                                                            '<td><input type="date" name="corrective_action_details[' + serialNumber + '][target_date]"></td>' +
                                                            '<td><input type="date" name="corrective_action_details[' + serialNumber + '][completion_date]"></td>' +
                                                            '<td><input type="text" name="corrective_action_details[' + serialNumber + '][verified_by_qa]"></td>' +
                                                            '<td><button type="button" class="removeRowBtn">Remove</button></td>' +
                                                            '</tr>'
                                                        );
                                                    }
                                        
                                                    var tableBody = $('#corrective_action_details tbody');
                                                    var rowCount = tableBody.children('tr').not('.no-data').length;
                                        
                                                    // Remove "No data found" row if it exists
                                                    if (rowCount === 0) {
                                                        tableBody.find('.no-data').remove();
                                                        rowCount = 0; // Start count from 0 if no rows exist
                                                    }
                                        
                                                    var newRow = generateCorrectiveActionRow(rowCount);
                                                    tableBody.append(newRow);
                                                });
                                        
                                                // Remove row in Proposed Corrective Action table
                                                $(document).on('click', '.removeRowBtn', function() {
                                                    $(this).closest('tr').remove();
                                        
                                                    // Check if table is empty after deletion, add "No data found" row if so
                                                    if ($('#corrective_action_details tbody tr').length === 0) {
                                                        $('#corrective_action_details tbody').append('<tr class="no-data"><td colspan="7">No data found</td></tr>');
                                                    }
                                                });
                                            });
                                        </script>
                                        
                                        
                                        
                                        
                                        
                                        
                                    
                                    
                                   
                                        <div class="col-12 sub-head">
                                            Proposed Preventive Action
                                        </div>
                                        <div class="col-12">
                                            <div class="group-input">
                                                <label for="Proposed Preventive Action">
                                                    Proposed Preventive Action
                                                    <button type="button" id="preventive_action_add">+</button>
                                                </label>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered" id="preventive_action_details" style="width: 100%;">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 100px;">Row #</th>
                                                                <th>Action</th>
                                                                <th>Responsibility (Department)</th>
                                                                <th>Target Date</th>
                                                                <th>Completion Date</th>
                                                                <th>Implementation Verified by QA</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if (!empty($proposedPreventiveActionData) && is_array($proposedPreventiveActionData->data))
                                                                @foreach ($proposedPreventiveActionData->data as $index => $action)
                                                                    <tr>
                                                                        <td><input disabled type="text" name="preventive_action_details[{{ $index }}][serial]" value="{{ $index + 1 }}"></td>
                                                                        <td><input type="text" name="preventive_action_details[{{ $index }}][action]" value="{{ $action['action'] }}"></td>
                                                                        <td><input type="text" name="preventive_action_details[{{ $index }}][responsibility]" value="{{ $action['responsibility'] }}"></td>
                                                                        <td><input type="date" name="preventive_action_details[{{ $index }}][target_date]" value="{{ $action['target_date'] }}"></td>
                                                                        <td><input type="date" name="preventive_action_details[{{ $index }}][completion_date]" value="{{ $action['completion_date'] }}"></td>
                                                                        <td><input type="text" name="preventive_action_details[{{ $index }}][verified_by_qa]" value="{{ $action['verified_by_qa'] }}"></td>
                                                                        <td><button type="button" class="removeRowBtn">Remove</button></td>
                                                                    </tr>
                                                                @endforeach
                                                            @else
                                                                <tr class="no-data">
                                                                    <td colspan="7">No data found</td>
                                                                </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <script>
                                            $(document).ready(function() {
                                                // Add new row in Proposed Preventive Action table
                                                $('#preventive_action_add').click(function(e) {
                                                    e.preventDefault();
                                        
                                                    function generatePreventiveActionRow(serialNumber) {
                                                        return (
                                                            '<tr>' +
                                                            '<td><input disabled type="text" name="preventive_action_details[' + serialNumber +
                                                            '][serial]" value="' + (serialNumber + 1) + '"></td>' +
                                                            '<td><input type="text" name="preventive_action_details[' + serialNumber + '][action]"></td>' +
                                                            '<td><input type="text" name="preventive_action_details[' + serialNumber + '][responsibility]"></td>' +
                                                            '<td><input type="date" name="preventive_action_details[' + serialNumber + '][target_date]"></td>' +
                                                            '<td><input type="date" name="preventive_action_details[' + serialNumber + '][completion_date]"></td>' +
                                                            '<td><input type="text" name="preventive_action_details[' + serialNumber + '][verified_by_qa]"></td>' +
                                                            '<td><button type="button" class="removeRowBtn">Remove</button></td>' +
                                                            '</tr>'
                                                        );
                                                    }
                                        
                                                    var tableBody = $('#preventive_action_details tbody');
                                                    var rowCount = tableBody.children('tr').not('.no-data').length;
                                        
                                                    // Remove "No data found" row if it exists
                                                    if (rowCount === 0) {
                                                        tableBody.find('.no-data').remove();
                                                        rowCount = 0; // Start count from 0 if no rows exist
                                                    }
                                        
                                                    var newRow = generatePreventiveActionRow(rowCount);
                                                    tableBody.append(newRow);
                                                });
                                        
                                                // Remove row in Proposed Preventive Action table
                                                $(document).on('click', '.removeRowBtn', function() {
                                                    $(this).closest('tr').remove();
                                        
                                                    // Check if table is empty after deletion, add "No data found" row if so
                                                    if ($('#preventive_action_details tbody tr').length === 0) {
                                                        $('#preventive_action_details tbody').append('<tr class="no-data"><td colspan="7">No data found</td></tr>');
                                                    }
                                                });
                                            });
                                        </script>
                                        
                                                  
                                        
                                     
                                        
                                       
                                        
                                        <div class="col-12 sub-head">
                                            Implementation of Corrective Action by means of
                                        </div>
                                        <div class="col-12">
                                            <div class="group-input">
                                                <label for="ImplementationCorrectiveAction">
                                                    Implementation of Corrective Action
                                                    <button type="button" id="implementation_action_add">+</button>
                                                </label>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered" id="implementation_action_details" style="width: 100%;">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 100px;">Row #</th>
                                                                <th>Action Plan</th>
                                                                <th>Implemented Through</th>
                                                                <th>Implemented Date</th>
                                                                <th>Verification of Implementation</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if (!empty($implementationCorrectiveActionData) && is_array($implementationCorrectiveActionData->data))
                                                                @foreach ($implementationCorrectiveActionData->data as $index => $action)
                                                                    <tr>
                                                                        <td><input disabled type="text" name="implementation_action_details[{{ $index }}][serial]" value="{{ $index + 1 }}"></td>
                                                                        <td><input type="text" name="implementation_action_details[{{ $index }}][action_plan]" value="{{ $action['action_plan'] ?? '' }}"></td>
                                                                        <td><input type="text" name="implementation_action_details[{{ $index }}][implemented_through]" value="{{ $action['implemented_through'] ?? '' }}"></td>
                                                                        <td><input type="date" name="implementation_action_details[{{ $index }}][implemented_date]" value="{{ $action['implemented_date'] ?? '' }}"></td>
                                                                        <td><input type="text" name="implementation_action_details[{{ $index }}][verification]" value="{{ $action['verification'] ?? '' }}"></td>
                                                                        <td><button type="button" class="removeRowBtn">Remove</button></td>
                                                                    </tr>
                                                                @endforeach
                                                            @else
                                                                <tr class="no-data">
                                                                    <td colspan="6">No data found</td>
                                                                </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <script>
                                            $(document).ready(function() {
                                                // Add new row in Implementation Corrective Action table
                                                $('#implementation_action_add').click(function(e) {
                                                    e.preventDefault();
                                                
                                                    function generateImplementationActionRow(serialNumber) {
                                                        return (
                                                            '<tr>' +
                                                            '<td><input disabled type="text" name="implementation_action_details[' + serialNumber +
                                                            '][serial]" value="' + (serialNumber + 1) + '"></td>' +
                                                            '<td><input type="text" name="implementation_action_details[' + serialNumber + '][action_plan]"></td>' +
                                                            '<td><input type="text" name="implementation_action_details[' + serialNumber + '][implemented_through]"></td>' +
                                                            '<td><input type="date" name="implementation_action_details[' + serialNumber + '][implemented_date]"></td>' +
                                                            '<td><input type="text" name="implementation_action_details[' + serialNumber + '][verification]"></td>' +
                                                            '<td><button type="button" class="removeRowBtn">Remove</button></td>' +
                                                            '</tr>'
                                                        );
                                                    }
                                            
                                                    var tableBody = $('#implementation_action_details tbody');
                                                    var rowCount = tableBody.children('tr').not('.no-data').length;
                                            
                                                    // Remove "No data found" row if it exists
                                                    if (rowCount === 0) {
                                                        tableBody.find('.no-data').remove();
                                                        rowCount = 0; // Start count from 0 if no rows exist
                                                    }
                                            
                                                    var newRow = generateImplementationActionRow(rowCount);
                                                    tableBody.append(newRow);
                                                });
                                            
                                                // Remove row in Implementation Corrective Action table
                                                $(document).on('click', '.removeRowBtn', function() {
                                                    $(this).closest('tr').remove();
                                            
                                                    // Check if table is empty after deletion, add "No data found" row if so
                                                    if ($('#implementation_action_details tbody tr').length === 0) {
                                                        $('#implementation_action_details tbody').append('<tr class="no-data"><td colspan="6">No data found</td></tr>');
                                                    }
                                                });
                                            });
                                        </script>
                                        
                                        
                                        
                                        
                                        
                                 
                                        
                                       
                                        

                                   

    

    
                                    <div class="button-block">
                                        <button type="submit" id="ChangesaveButton" class="saveButton"
                                            {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>Save</button>
                                        {{-- <button type="button" id="ChangeNextButton" class="nextButton">Next</button> --}}
                                        <button type="button"> <a class="text-white"
                                                href="{{ url('rcms/qms-dashboard') }}"> Exit </a> </button>
                                    </div>
                                </div>
                            </div>
                            </div>

                            <!-- Product Information content -->
                            <div id="CCForm2" class="inner-block cctabcontent">
                                <div class="inner-block-content">
                                    <div class="row">
                                       
                                        <div class="col-12 sub-head">
                                            Closure of the CAPA
                                        </div>
                                        <div class="col-12">
                                            <div class="group-input">
                                                <label for="due_date_extension">Comments</label>
                                                
                                                <textarea name="comments_cloasure">{{ $data->comments_cloasure }}</textarea>
                                            </div>
                                        </div>
        
                                        <div class="col-12">
                                            <div class="group-input">
                                                <label for="Details">Head Quality / Designee</label>
                                                <input type="text" name="head_quality">
                                            </div>
                                        </div>
        
                                        <div class="col-12 sub-head">
                                            Extension (if required) 
                                        </div>
                                        <div class="col-12">
                                            <div class="group-input">
                                                <label for="due_date_extension">Justification</label>
                                                
                                                <textarea name="justification">{{ $data->justification }}</textarea>
                                            </div>
                                        </div>
        
                                       

                                        <div class="col-12 sub-head">
                                            Material Details
                                        </div>
                                        <div class="col-12">
                                            <div class="group-input">
                                                <label for="Material Details">
                                                    Material Details<button type="button" name="ann" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : ''}}
                                                    id="material">+</button>
                                                </label>
                                                <table class="table table-bordered" id="material_details">
                                                    <thead>
                                                        <tr>
                                                            <th>Row #</th>
                                                            <th>Material Name</th>
                                                            <th>Batch No./Lot No./AR No.</th>
                                                            <th>Manufacturing Date</th>
                                                            <th>Date Of Expiry</th>
                                                            <th>Batch Disposition Decision</th>
                                                            <th>Remark</th>
                                                            <th>Batch Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                     
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-12 sub-head">
                                            Equipment/Instruments Details
                                        </div>
                                        <div class="col-12">
                                            <div class="group-input">
                                                <label for="Material Details">
                                                    Equipment/Instruments Details<button type="button" name="ann"
                                                    id="equipment"
                                                        {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>+</button>
                                                </label>
                                                <table class="table table-bordered" id="equipment_details">
                                                    <thead>
                                                        <tr>
                                                            <th>Row #</th>
                                                            <th>Equipment/Instruments Name</th>
                                                            <th>Equipment/Instruments ID</th>
                                                            <th>Equipment/Instruments Comments</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-12 sub-head">
                                            Other type CAPA Details
                                        </div>
                                        <div class="col-12">
                                            <div class="group-input">
                                                <label for="Details">Details</label>
                                                <input type="text" name="details_new"
                                                    {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}
                                                    value="{{ $data->details_new }}">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="group-input">
                                                <label for="Comments"> CAPA QA Comments </label>
                                                <textarea name="capa_qa_comments2" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->capa_qa_comments2 }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="button-block">
                                        <button type="submit" class="saveButton"
                                            {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>Save</button>
                                        {{-- <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                        <button type="button" class="nextButton" onclick="nextStep()">Next</button> --}}
                                        <button type="button"> <a class="text-white"
                                                href="{{ url('rcms/qms-dashboard') }}"> Exit </a> </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Project Study content -->
                            <div id="CCForm3" class="inner-block cctabcontent">
                                <div class="inner-block-content">
                                    <div class="row">

                                        <div class="col-lg-12">
                                            <div class="group-input">
                                                <label for="Initiator Group">Effectiveness Verification of CAPA:</label>
                                                <select name="effectiveness_verification_capa" id="assignableSelect" onchange="toggleRootCauseInput()">
                                                    <option value=" ">Is Effectiveness verification required?</option>
                                                    <option value="YES" {{ (old('effectiveness_verification_capa', $data->effectiveness_verification_capa ?? '') == 'YES') ? 'selected' : '' }}>YES</option>
                                                    <option value="NO" {{ (old('effectiveness_verification_capa', $data->effectiveness_verification_capa ?? '') == 'NO') ? 'selected' : '' }}>NO</option>
                                                </select>
                                            </div>
                                        </div>
                                        
        
                                        <div class="col-lg-12" id="rootCauseGroup" style="display: none;">
                                            <div class="group-input">
                                                <label for="RootCause">Remark</label>
                                                <textarea name="effectivenessRemark"  id="effectivenessRemark" rows="4" placeholder="Describe the root cause here">{{ $data->effectivenessRemark }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                        <script>
                                            function toggleRootCauseInput() {
                                                var selectValue = document.getElementById("assignableSelect").value;
                                                var rootCauseGroup = document.getElementById("rootCauseGroup");
        
                                                if (selectValue === "YES") {
                                                    rootCauseGroup.style.display = "block"; // Show the textarea if "YES" is selected
                                                } else {
                                                    rootCauseGroup.style.display = "none"; // Hide the textarea if "NO" or "NA" is selected
                                                }
                                            }
                                        </script>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Project Datails Application">Project Datails
                                                    Application</label>
                                                <select name="project_details_application"
                                                    {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                    <option value="">Enter Your Selection Here</option>
                                                    <option
                                                        {{ $data->project_details_application == 'yes' ? 'selected' : '' }}
                                                        value="yes">Yes</option>
                                                    <option
                                                        {{ $data->project_details_application == 'no' ? 'selected' : '' }}
                                                        value="no">No</option>
                                                </select>
                                            </div>
                                        </div>

                                        {{-- <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Protocol/Study Number">Initiator Group</label>
                                                <select name="initiator_group"
                                                    {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                    <option value="">Enter Your Selection Here</option>
                                                    <option value="CQA"
                                                        @if ($data->initiator_group== 'CQA') selected @endif>Corporate
                                                        Quality
                                                        Assurance
                                                    </option>
                                                    <option value="QAB"
                                                        @if ($data->initiator_group== 'QAB') selected @endif>Quality
                                                        Assurance
                                                        Biopharma
                                                    </option>
                                                    <option value="CQC"
                                                        @if ($data->initiator_group== 'CQC') selected @endif>Central Quality
                                                        Control
                                                    </option>
                                                    <option value="CQC"
                                                        @if ($data->initiator_group== 'CQC') selected @endif>Manufacturing
                                                    </option>
                                                    <option value="PSG"
                                                        @if ($data->initiator_group== 'PSG') selected @endif>Plasma Sourcing
                                                        Group
                                                    </option>
                                                    <option value="CS"
                                                        @if ($data->initiator_group== 'CS') selected @endif>Central Stores
                                                    </option>
                                                    <option value="ITG"
                                                        @if ($data->initiator_group== 'ITG') selected @endif>Information
                                                        Technology Group
                                                    </option>
                                                    <option value="MM"
                                                        @if ($data->initiator_group== 'MM') selected @endif>Molecular
                                                        Medicine
                                                    </option>
                                                    <option value="CL"
                                                        @if ($data->initiator_group== 'CL') selected @endif>Central
                                                        Laboratory
                                                    </option>
                                                </select>
                                            </div>
                                        </div> --}}
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Site Number">Site Number</label>
                                                <input type="text" name="site_number"
                                                    {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}
                                                    value="{{ $data->site_number }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Subject Number">Subject Number</label>
                                                <input type="text" name="subject_number"
                                                    {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}
                                                    value="{{ $data->subject_number }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Subject Initials">Subject Initials</label>
                                                <input type="text" name="subject_initials"
                                                    {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}
                                                    value="{{ $data->subject_initials }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Sponsor">Sponsor</label>
                                                <input type="text" name="sponsor"
                                                    {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}
                                                    value="{{ $data->sponsor }}">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="group-input">
                                                <label for="General Deviation">General Deviation</label>
                                                <textarea name="general_deviation" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->general_deviation }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="button-block">
                                        <button type="submit" class="saveButton"
                                            {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>Save</button>
                                            <a href="/rcms/qms-dashboard">
                                        <button type="button" class="backButton">Back</button>
                                    </a>
                                        <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                        <button type="button"> <a class="text-white"
                                                href="{{ url('rcms/qms-dashboard') }}"> Exit </a> </button>
                                    </div>
                                </div>
                            </div>
                              <div id="CCForm8" class="inner-block cctabcontent">
                                <div class="inner-block-content">
                                    <div class="sub-head">
                                        CFT Information
                                    </div>
                                    <div class="row">

                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Microbiology">CFT Reviewer</label>
                                                <select {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} name="Microbiology_new">
                                                    <option value="0">-- Select --</option>
                                                    <option @if ($data->Microbiology_new=='yes') selected @endif value="yes" selected>Yes</option>
                                                    <option @if ($data->Microbiology_new=='no') selected @endif value="no">No</option>
                                                </select>
                                            </div>
                                        </div>
                                         <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Microbiology-Person">CFT Reviewer Person</label>
                                                <select {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}  name="Microbiology_Person[]"
                                                    placeholder="Select CFT Reviewers" data-search="false"
                                                    data-silent-initial-value-set="true" id="cft_reviewer">
                                                    <option value="0">-- Select --</option>
                                                    @foreach ($cft as $data)
                                                        <option value="{{ $data->id }}" selected>
                                                            {{ $data->name }}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div> 


                                     </div>
                                    <div class="sub-head">
                                        Concerned Information
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="group-input">
                                                <label for="group_review">Is Concerned Group Review Required?</label>
                                                <select name="goup_review"{{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                    <option value="0">-- Select --</option>
                                                    <option {{$data->goup_review == 'yes' ? 'selected' : '' }}
                                                        value="yes">Yes</option>
                                                    <option {{ $data->goup_review == 'no' ? 'selected' : '' }}
                                                        value="no">No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Production">Production</label>
                                                <select name="Production_new"{{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                    <option value="0">-- Select --</option>
                                                    <option {{ $data->Production_new== 'yes' ? 'selected' : '' }}
                                                        value="yes">Yes</option>
                                                    <option {{ $data->Production_new== 'no' ? 'selected' : '' }}
                                                        value="no">No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Production-Person">Production Person</label>
                                                <select name="Production_Person"{{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                    <option value="0">-- Select --</option>
                                                    @foreach ($users as $datas)
                                                        <option
                                                            {{ $data->Production_Person == $datas->id ? 'selected' : '' }}
                                                            value="{{ $datas->id }}">{{ $datas->name }}</option>
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Quality-Approver">Quality Approver</label>
                                                <select name="Quality_Approver"{{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                    <option value="0">-- Select --</option>
                                                    <option {{ $data->Quality_Approver == 'yes' ? 'selected' : '' }}
                                                        value="yes">Yes</option>
                                                    <option {{ $data->Quality_Approver == 'no' ? 'selected' : '' }}
                                                        value="no">No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Quality-Approver-Person">Quality Approver Person</label>
                                                <select name="Quality_Approver_Person"{{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                    <option value="0">-- Select --</option>

                                                    @foreach ($users as $datas)
                                                        <option
                                                            {{ $data->Quality_Approver_Person== $datas->id ? 'selected' : '' }}
                                                            value="{{ $datas->id }}">{{ $datas->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>


                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="bd_domestic">Others</label>
                                                <select name="bd_domestic"{{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                    <option value="0">-- Select --</option>
                                                    <option {{ $data->bd_domestic == 'yes' ? 'selected' : '' }}
                                                        value="yes">Yes</option>
                                                    <option {{ $data->bd_domestic == 'no' ? 'selected' : '' }}
                                                        value="no">No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="bd_domestic-Person">Others Person</label>
                                                <select name="Bd_Person"{{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                    <option value="0">-- Select --</option>

                                                    @foreach ($users as $datas)
                                                        <option {{ $data->Bd_Person == $datas->id ? 'selected' : '' }}
                                                            value="{{ $datas->id }}">{{ $datas->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                          <div class="col-12">
                                            <div class="group-input">
                                                <label for="Additional Attachments">Additional Attachments</label>
                                                <div class="file-attachment-field">
                                                    <div class="file-attachment-list" id="additional_attachments">
                                                        {{-- @if (is_array($data->additional_attachments)) --}}
                                                        @if ($data->additional_attachments)
                                                           @foreach(json_decode($data->additional_attachments) as $file) 
                                                          <h6 type="button" class="file-container text-dark" 
                                                                    style="background-color: rgb(243, 242, 240);">
                                                                    <b>{{ $file }}</b>
                                                                    <a href="{{ asset('upload/' . $file) }}"
                                                                        target="_blank"><i
                                                                            class="fa fa-eye text-primary"
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
                                                        <input type="file" id="myfile"
                                                            name="additional_attachments[]"{{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}
                                                            oninput="addMultipleFiles(this, 'additional_attachments')"
                                                            multiple>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                    <div class="button-block">
                                        <button type="submit" class="saveButton">Save</button>
                                        <a href="/rcms/qms-dashboard">
                                        <button type="button" class="backButton">Back</button>
                                    </a>

                                        <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                    </div>
                                </div>  
                            </div> 
                               <!-- Group Commentes-->
                             <div id="CCForm7" class="inner-block cctabcontent">
                                <div class="inner-block-content">

                                    <div class="sub-head">
                                        CFT Feedback
                                    </div>
                                    <div class="row">

                                        <div class="col-lg-12">
                                            <div class="group-input">
                                                <label for="comments">CFT Comments</label>
                                                <textarea name="cft_comments_form"{{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->cft_comments_form}}</textarea>
                                            </div>
                                        </div>
                                         <div class="col-lg-12">
                                            <div class="group-input">
                                                <label for="comments">CFT Attachment</label>
                                                <div class="file-attachment-field">
                                                    <div class="file-attachment-list" id="cft_attchament_new">
                                                        {{-- @if (is_array($data->cft_attchament_new)) --}}
                                                                 @if ($data->cft_attchament_new)
                                                                     @foreach (json_decode($data->cft_attchament_new) as $file) 
                                                                  <h6 type="button" class="file-container text-dark"
                                                                    style="background-color: rgb(243, 242, 240);">
                                                                    <b>{{ $file }}</b>
                                                                    <a href="{{ asset('upload/' . $file) }}"
                                                                        target="_blank"><i
                                                                            class="fa fa-eye text-primary"
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
                                                        <input type="file" id="myfile" name="cft_attchament_new[]"{{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}
                                                            oninput="addMultipleFiles(this, 'cft_attchament_new')"
                                                            multiple>
                                                    </div>
                                                </div>

                                            </div> 
                                        </div> 
                                    </div>
                                    <div class="row">
                                        <div class="sub-head">
                                            Concerned Group Feedback
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="comments">QA Comments</label>
                                                <textarea name="qa_comments_new"{{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->qa_comments_new}}
                                                    {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="comments">QA Head Designee Comments</label>
                                                <textarea name="designee_comments_new"{{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->designee_comments_new}}
                                                    {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="comments">Warehouse Comments</label>
                                                <textarea name="Warehouse_comments_new"{{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->Warehouse_comments_new}}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="comments">Engineering Comments</label>
                                                <textarea name="Engineering_comments_new"{{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->Engineering_comments_new}}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="comments">Instrumentation Comments</label>
                                                <textarea name="Instrumentation_comments_new"{{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->Instrumentation_comments_new}}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="comments">Validation Comments</label>
                                                <textarea name="Validation_comments_new"{{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->Validation_comments_new}}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="comments">Others Comments</label>
                                                <textarea name="Others_comments_new"{{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->Others_comments_new}}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="comments">Group Comments</label>
                                                <textarea name="Group_comments_new"{{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->Group_comments_new}}</textarea>
                                            </div>
                                        </div>
                                       
                                         <div class="col-12">
                                    
                                            <div class="group-input">
                                                <label for="group-attachments">Group Attachments</label>
                                                <div class="file-attachment-field">
                                                    <div class="file-attachment-list" id="group_attachments_new">
                                                       
                                                        {{-- @if (is_array($data->group_attachments_new)) --}}
                                                        @if ($data->group_attachments_new)
                                                             @foreach (json_decode($data->group_attachments_new) as $file) 
                                                                <h6 type="button" class="file-container text-dark"
                                                                    style="background-color: rgb(243, 242, 240);">
                                                                    <b>{{ $file}}</b>
                                                                    <a href="{{ asset('upload/' . $file) }}"
                                                                        target="_blank"><i
                                                                            class="fa fa-eye text-primary"
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
                                                        <input type="file" id="myfile"
                                                            name="group_attachments_new[]"{{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}
                                                            oninput="addMultipleFiles(this, 'group_attachments_new')"
                                                            multiple>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="button-block">
                                        <button type="submit" class="saveButton">Save</button>
                                        <a href="/rcms/qms-dashboard">
                                        <button type="button" class="backButton">Back</button>
                                    </a>

                                        <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                    </div>
                                </div>
                            </div>
                            <!-- CAPA Details content -->
                            <div id="CCForm4" class="inner-block cctabcontent">
                                <div class="inner-block-content">
                                    <div class="row">
                                        <div class="col-md-12">
                                    <div class="group-input">
                                        <label for="search">
                                            CAPA Type<span class="text-danger"></span>
                                        </label>
                                        <select id="select-state" placeholder="Select..." name="capa_type"{{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                            <option value="">Select a value</option>
                                            <option {{ $data->capa_type == "Corrective Action" ? 'selected' : '' }} value="Corrective Action">Corrective Action</option>
                                            <option {{ $data->capa_type == "Preventive Action" ? 'selected' : '' }} value="Preventive Action">Preventive Action</option>
                                            <option {{ $data->capa_type == "Corrective & Preventive Action"  ? 'selected' : '' }} value="Corrective & Preventive Action">Corrective & Preventive Action</option>

                                        </select>
                                        @error('assign_to')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                        <div class="col-12">
                                            <div class="group-input">
                                                <label for="Corrective Action">Corrective Action</label>
                                                <textarea name="corrective_action" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->corrective_action }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="group-input">
                                                <label for="Preventive Action">Preventive Action</label>
                                                <textarea name="preventive_action" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->preventive_action }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="group-input">
                                                <label for="Supervisor Review Comments">Supervisor Review
                                                    Comments</label>
                                                <textarea name="supervisor_review_comments" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->supervisor_review_comments }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="button-block">
                                        <button type="submit" class="saveButton"
                                            {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>Save</button>
                                        {{-- <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                        <button type="button" class="nextButton" onclick="nextStep()">Next</button> --}}
                                        <button type="button"> <a class="text-white"
                                                href="{{ url('rcms/qms-dashboard') }}"> Exit </a> </button>
                                    </div>
                                </div>
                            </div>

                            <!-- CAPA Closure content -->
                            <div id="CCForm5" class="inner-block cctabcontent">
                                <div class="inner-block-content">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="group-input">
                                                <label for="QA Review & Closure">QA Review & Closure</label>
                                                <textarea name="qa_review" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->qa_review }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="group-input">
                                                <label for="Closure Attachments">Closure Attachment</label>
                                                <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                                {{-- <input type="file" id="myfile" name="closure_attachment"
                                                    {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}> --}}
                                                <div class="file-attachment-field">
                                                    <div class="file-attachment-list" id="closure_attachment1">
                                                        @if ($data->closure_attachment)
                                                            @foreach (json_decode($data->closure_attachment) as $file)
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
                                                        <input
                                                            {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}
                                                            type="file" id="myfile" name="closure_attachment[]"{{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}
                                                            oninput="addMultipleFiles(this, 'closure_attachment1')"
                                                            multiple>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                         <!-- <div class="col-12 sub-head">
                                    Effectiveness Check Details -->
                                </div>
                                        <!-- <div class="col-12">
                                            <div class="group-input">
                                                <label for="Effectiveness Check required">Effectiveness Check
                                                    required</label>
                                                <select name="effect_check"{{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}
                                                    {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                    <option value="">Enter Your Selection Here</option>
                                                    <option {{ $data->effect_check == 'yes' ? 'selected' : '' }}
                                                        value="yes">Yes</option>
                                                    <option {{ $data->effect_check == 'no' ? 'selected' : '' }}
                                                        value="no">No</option>
                                                </select>
                                            </div>
                                        </div>
                                        {{-- <div class="col-6 new-date-data-field">
                                            <div class="group-input input-date">
                                                <label for="Effect.Check Creation Date">Effect.Check Creation
                                                    Date</label>
                                                <input type="date" name="effect_check_date"
                                                    value="{{ $data->effect_check_date }}"> 
                                                    <div class="calenderauditee">                                     
                                                        <input type="text"  value="{{ $data->effect_check_date }}" id="effect_check_date"  readonly placeholder="DD-MMM-YYYY" />
                                                        <input type="date" name="effect_check_date" value=""
                                                        class="hide-input"
                                                        oninput="handleDateInput(this, 'effect_check_date')"/>
                                                    </div>
                                            </div>
                                        </div> --}}

                                        <div class="col-6 new-date-data-field">
                                            <div class="group-input input-date">
                                                <label for="Effect Check Creation Date">Effectiveness Check Creation Date</label>
                                                {{-- <input type="date" name="effect_check_date"> --}}
                                                <div class="calenderauditee">
                                                    <input type="text"  id="effect_check_date" readonly
                                                        placeholder="DD-MMM-YYYY"value="{{ Helpers::getdateFormat($data->effect_check_date) }}"/>
                                                    <input type="date" name="effect_check_date" value=""class="hide-input"
                                                        oninput="handleDateInput(this,'effect_check_date')" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="group-input">
                                                <label for="Effectiveness_checker">Effectiveness Checker</label>
                                                <select name="Effectiveness_checker">{{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}
                                                    <option value="">Enter Your Selection Here</option>
                                                    @foreach ($users as $value)
                                                        <option
                                                            {{ $data->Effectiveness_checker == $value->id ? 'selected' : '' }}
                                                            value="{{ $value->id }}">{{ $value->name }}</option>
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="group-input">
                                                <label for="effective_check_plan">Effectiveness Check Plan</label>
                                                <textarea name="effective_check_plan"{{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}> {{ $data->effective_check_plan }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-12 sub-head">
                                            Extension Justification
                                        </div> -->
                                        <div class="col-12">
                                            <div class="group-input">
                                                <label for="due_date_extension">Due Date Extension Justification</label>
                                                <div><small class="text-primary">Please Mention justification if due date is crossed</small></div>
                                                <textarea name="due_date_extension"{{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->due_date_extension }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="button-block">
                                        <button type="submit" class="saveButton">Save</button>
                                        {{-- <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                        <button type="button" class="nextButton" onclick="nextStep()">Next</button> --}}
                                        <button type="button"> <a class="text-white"
                                                href="{{ url('rcms/qms-dashboard') }}"> Exit </a> </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Activity Log content -->
                            <div id="CCForm6" class="inner-block cctabcontent">
                                <div class="inner-block-content">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Plan Proposed By">Concerned Department Head By</label>
                                                <input type="hidden" name="plan_proposed_by"{{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                <div class="static">{{ $data->plan_proposed_by }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Concerned Department Head On">Concerned Department Head On</label>
                                                <input type="hidden" name="plan_proposed_on"{{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                <div class="static">{{ $data->plan_proposed_on }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Plan Approved By">Allocation of CAPA By</label>
                                                <input type="hidden" name="plan_approved_by"{{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                <div class="static">{{ $data->plan_approved_by }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Allocation of CAPA On">Allocation of CAPA On</label>
                                                <input type="hidden" name="plan_approved_on"{{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                <div class="static">{{ $data->Plan_approved_on }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="QA More Info Required By">QA More Info Required
                                                    By</label>
                                                <input type="hidden" name="qa_more_info_required_by"{{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                <div class="static">{{ $data->qa_more_info_required_by }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="QA More Info Required On">QA More Info Required
                                                    On</label>
                                                <input type="hidden" name="qa_more_info_required_on"{{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                <div class="static">{{ $data->qa_more_info_required_on }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Cancelled By">Cancelled By</label>
                                                <input type="hidden" name="cancelled_by"{{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                <div class="static">{{ $data->cancelled_by }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Cancelled On">Cancelled On</label>
                                                <input type="hidden" name="cancelled_on"{{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                <div class="static">{{ $data->cancelled_on }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Completed By">Corrective Action Taken By</label>
                                                <input type="hidden" name="completed_by"{{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                <div class="static">{{ $data->completed_by }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Completed On">Corrective Action Taken On</label>
                                                <input type="hidden" name="completed_on"{{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                <div class="static">{{ $data->completed_on }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Approved By">Preventive Action By</label>
                                                <input type="hidden" name="approved_by"{{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>

                                                <div class="static">{{ $data->approved_by }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Approved On">Preventive Action On</label>
                                                <input type="hidden" name="approved_on"{{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                <div class="static">{{ $data->approved_on }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Rejected By">Rejected By</label>
                                                <input type="hidden" name="rejected_by"{{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                <div class="static">{{ $data->rejected_by }}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Rejected On">Rejected On</label>
                                                <input type="hidden" name="rejected_on"{{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                <div class="static">{{ $data->rejected_on }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="button-block">
                                        <button type="submit" class="saveButton"{{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>Save</button>
                                        {{-- <button type="button" class="backButton" onclick="previousStep()">Back</button> --}}
                                        <button type="submit"{{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>Submit</button>
                                        <button type="button"> <a class="text-white"href="{{ url('rcms/qms-dashboard') }}"> Exit </a> </button>
                                    </div>
                                </div>
                            </div>
                             
                        </div>
                    </form>

                </div>

            </div>

            <div class="modal fade" id="child-modal1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Child</h4>
                        </div>
                        {{-- <form action="{{ route('capa_effectiveness_check', $data->parent_id) }}" method="POST"> --}}
                             <form action="{{ route('capa_child_changecontrol', $data->parent_id ? $data->parent_id : $data->id) }}" method="POST">

                            @csrf
                            <!-- Modal body -->
                            <div class="modal-body">
                                <div class="group-input">
                                    <label for="major">
                                        <input type="hidden" name="parent_name" value="Capa">
                                        <input type="hidden" name="due_date" value="{{ $data->due_date }}">
                                        <input type="radio" name="child_type" value="effectiveness_check">
                                        Effectiveness Check
                                    </label>

                                </div>

                            </div>

                            <!-- Modal footer -->
                            <div class="modal-footer">
                                <button type="button" data-bs-dismiss="modal">Close</button>
                                <button type="submit">Continue</button>
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
                        {{--pr <form action="{{ route('capa_child_changecontrol', $data->id) }}" method="POST"> --}}
                            <form action="{{ route('capa_child_changecontrol', $data->parent_id ? $data->parent_id : $data->id) }}" method="POST">
                            @csrf
                            <!-- Modal body -->
                            <div class="modal-body">
                                <div class="group-input">
                                    @if ($data->stage == 3)
                                        <label for="major">

                                        </label>
                                         <label for="major">
                                            <input type="radio" name="child_type" value="Change_control">
                                            Change Control
                                        </label>
                                        <label for="major">
                                            <input type="radio" name="child_type" value="Action_Item">
                                            Action Item
                                        </label>
                                        <!-- <label for="major">
                                            <input type="radio" name="child_type" value="extension">
                                            Extension
                                        </label> -->
                                    @endif
                                    
                                    @if ($data->stage == 6)
                                        <label for="major">
                                            <input type="radio" name="child_type" value="effectiveness_check">
                                            Effectiveness Check
                                        </label>
                                    @endif
                                </div>

                            </div>

                            <!-- Modal footer -->
                            <div class="modal-footer">
                                <button type="button" data-bs-dismiss="modal">Close</button>
                                <button type="submit">Continue</button>
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
                        <form action="{{ route('capa_effectiveness_check', $data->id) }}" method="POST">
                            @csrf
                            <!-- Modal body -->
                            <div class="modal-body">
                                <div class="group-input">
                                    <label for="major">
                                        <input type="radio" name="effectiveness_check" id="major"
                                            value="Effectiveness_check">
                                        Effectiveness Check
                                    </label>
                                </div>

                            </div>

                            <!-- Modal footer -->
                            <div class="modal-footer">
                                <button type="button" data-bs-dismiss="modal">Close</button>
                                <button type="submit">Continue</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            <div class="modal fade" id="rejection-modal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">E-Signature</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <form action="{{ route('capa_reject', $data->id) }}" method="POST">
                            @csrf
                            <!-- Modal body -->
                            <div class="modal-body">
                                <div class="mb-3 text-justify">
                                    Please select a meaning and a outcome for this task and enter your username
                                    and password for this task. You are performing an electronic signature,
                                    which is legally binding equivalent of a hand written signature.
                                </div>
                                <div class="group-input">
                                    <label for="username">Username <span class="text-danger">*</span></label>
                                    <input type="text" name="username" required>
                                </div>
                                <div class="group-input">
                                    <label for="password">Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" required>
                                </div>
                                <div class="group-input">
                                    <label for="comment">Comment <span class="text-danger">*</span></label>
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

            <div class="modal fade" id="cancel-modal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">E-Signature</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <form action="{{ route('capaCancel', $data->id) }}" method="POST">
                            @csrf
                            <!-- Modal body -->
                            <div class="modal-body">
                                <div class="mb-3 text-justify">
                                    Please select a meaning and a outcome for this task and enter your username
                                    and password for this task. You are performing an electronic signature,
                                    which is legally binding equivalent of a hand written signature.
                                </div>
                                <div class="group-input">
                                    <label for="username">Username <span class="text-danger">*</span></label>
                                    <input type="text" name="username" required>
                                </div>
                                <div class="group-input">
                                    <label for="password">Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" required>
                                </div>
                                <div class="group-input">
                                    <label for="comment">Comment <span class="text-danger">*</span></label>
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
                        <form action="{{ route('capa_send_stage', $data->id) }}" method="POST">
                            @csrf
                            <!-- Modal body -->
                            <div class="modal-body">
                                <div class="mb-3 text-justify">
                                    Please select a meaning and a outcome for this task and enter your username
                                    and password for this task. You are performing an electronic signature,
                                    which is legally binding equivalent of a hand written signature.
                                </div>
                                <div class="group-input">
                                    <label for="username">Username</label>
                                    <input type="text" name="username" required>
                                </div>
                                <div class="group-input">
                                    <label for="password">Password</label>
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
            <div class="modal fade" id="modal1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">E-Signature</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('capa_qa_more_info', $data->id) }}" method="POST">
                            @csrf
                            <!-- Modal body -->
                            <div class="modal-body">
                                <div class="mb-3 text-justify">
                                    Please select a meaning and a outcome for this task and enter your username
                                    and password for this task. You are performing an electronic signature,
                                    which is legally binding equivalent of a hand written signature.
                                </div>
                                <div class="group-input">
                                    <label for="username">Username</label>
                                    <input type="text" name="username" required>
                                </div>
                                <div class="group-input">
                                    <label for="password">Password</label>
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

            <style>
                #step-form>div {
                    display: none
                }

                #step-form>div:nth-child(1) {
                    display: block;
                }
            </style>

            <script>
                VirtualSelect.init({
                    ele: '#Facility, #Group, #Audit, #Auditee ,#capa_related_record'
                });

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
                        $('#rchars').text(textlen);});
                </script>
                
        @endsection
