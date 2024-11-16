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
    </style>

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
      <!-- <script>
        $(document).ready(function() {
            $('#material').click(function(e) {
                function generateTableRow(serialNumber) {
                    var users = @json($users);
                    console.log(users);
                    var html =
                        '<tr>' +
                        '<td><input disabled type="text" name="serial_number[]" value="' + serialNumber +
                        '"></td>' +
                        '<td><input type="text" name="material_name[]"></td>' +
                        '<td><input type="text" name="material_batch_no[]"></td>' +
                       
                        '<td><div class="group-input new-date-data-field mb-0"><div class="input-date "><div class="calenderauditee"> <input type="text" id="material_mfg_date' + serialNumber +'" readonly placeholder="DD-MMM-YYYY" /><input type="date" name="material_mfg_date[]" id="material_mfg_date' + serialNumber +'_checkdate"  class="hide-input" oninput="handleDateInput(this, `material_mfg_date' + serialNumber +'`);checkDate(`material_mfg_date1' + serialNumber +'_checkdate`,`material_expiry_date' + serialNumber +'_checkdate`)" /></div></div></div></td>' +

                        
                        '<td><div class="group-input new-date-data-field mb-0"><div class="input-date "><div class="calenderauditee"> <input type="text" id="material_expiry_date' + serialNumber +'" readonly placeholder="DD-MMM-YYYY" /><input type="date" name="material_expiry_date[]" id="material_expiry_date'+ serialNumber +'_checkdate" class="hide-input" oninput="handleDateInput(this, `material_expiry_date' + serialNumber +'`);checkDate(`material_mfg_date' + serialNumber +'_checkdate`,`material_expiry_date' + serialNumber +'_checkdate`)" /></div></div></div></td>' +
                        
                        '<td><input type="text" name="material_batch_desposition[]"></td>' +
                        '<td><input type="text" name="material_remark[]"></td>' +
                        '<td><select name="material_batch_status[]">' +
                        '<option value="">Select a value</option>';

                    for (var i = 0; i < users.length; i++) {
                        html += '<option value="' + users[i].id + '">' + users[i].name + '</option>';
                    }

                    html += '</select></td>' +
                      
                     
                        '</tr>';

                    return html;
                }

                var tableBody = $('#material tbody');
                var rowCount = tableBody.children('tr').length;
                var newRow = generateTableRow(rowCount + 1);
                tableBody.append(newRow);
            });
        });
    </script> -->

    <div class="form-field-head">

        <div class="division-bar">
            <strong>Site Division/Project</strong> :
            {{ Helpers::getDivisionName(session()->get('division')) }} / CAPA
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
                <button class="cctablinks" onclick="openCity(event, 'CCForm2')">Equipment/Material Info</button>
                {{-- <button class="cctablinks" onclick="openCity(event, 'CCForm3')">Project/Study</button> --}}
                <button class="cctablinks" onclick="openCity(event, 'CCForm4')">CAPA Details</button>
                {{-- <button class="cctablinks" onclick="openCity(event, 'CCForm5')">Additional Information</button> --}}
                {{-- <button class="cctablinks" onclick="openCity(event, 'CCForm6')">Group Comments</button> --}}
                <button class="cctablinks" onclick="openCity(event, 'CCForm7')">CAPA Closure</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm8')">Activity Log</button>
            </div>

            <form action="{{ route('capastore') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div id="step-form">

                    @if (!empty($parent_id))
                        <input type="hidden" name="parent_id" value="{{ $parent_id }}">
                        <input type="hidden" name="parent_type" value="{{ $parent_type }}">
                        <input type="hidden" name="parent_record" value="{{ $parent_record }}">
                    @endif
                    <!-- General information content -->
                    <div id="CCForm1" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        {{-- <label for="RLS Record Number">Record Number</label>
                                        <input disabled type="text" name="record_number"
                                            value="{{ Helpers::getDivisionName(session()->get('division')) }}/CAPA/{{ date('Y') }}/{{ $record_number }}"> --}}

                                        {{-- <div class="static">QMS-EMEA/CAPA/{{ date('Y') }}/{{ $record_number }}</div> --}}
                                        <label for="RLS Record Number">CAPA No.</label>
                                        <input disabled type="text" name="record_number"
                                            value="{{ Helpers::getDivisionName(session()->get('division')) }}/CAPA/{{ date('Y') }}/{{ $record_number }}">
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
                                        {{-- <div class="static">{{ Auth::user()->name }}</div> --}}
                                        <input disabled type="text" name="division_code"
                                            value="{{ Auth::user()->name }}">
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
                                        <label for="Date Due"> Due Date</label>
                                         <div><small class="text-primary">If revising Due Date, kindly mention revision reason in "Due Date Extension Justification" data field.</small></div>
                                        <div class="calenderauditee">
                                            <input type="text" id="due_date" readonly
                                                placeholder="DD-MMM-YYYY" />
                                            <input type="date" name="due_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input"
                                                oninput="handleDateInput(this, 'due_date')" />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Short Description">Short Description<span
                                                class="text-danger">*</span></label><span id="rchars">255</span>
                                        characters remaining
                                        <input id="docname" type="text" name="short_description" maxlength="255" required>
                                    </div>
                                </div>  
                                    <p id="docnameError" style="color:red">**Short Description is required</p>
                               
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group">Department</label>
                                        <select name="initiator_group" id="initiator_group">
                                            <option value="">-- Select --</option>
                                            <option value="CQA" @if (old('initiator_group') == 'CQA') selected @endif>
                                                Corporate Quality Assurance</option>
                                            <option value="QAB" @if (old('initiator_group') == 'QAB') selected @endif>Quality
                                                Assurance Biopharma</option>
                                            <option value="CQC" @if (old('initiator_group') == 'CQA') selected @endif>Central
                                                Quality Control</option>
                                            <option value="CQC" @if (old('initiator_group') == 'CQC') selected @endif>
                                                Manufacturing</option>
                                            <option value="PSG" @if (old('initiator_group') == 'PSG') selected @endif>Plasma
                                                Sourcing Group</option>
                                            <option value="CS" @if (old('initiator_group') == 'CS') selected @endif>
                                                Central
                                                Stores</option>
                                            <option value="ITG" @if (old('initiator_group') == 'ITG') selected @endif>
                                                Information Technology Group</option>
                                            <option value="MM" @if (old('initiator_group') == 'MM') selected @endif>
                                                Molecular Medicine</option>
                                            <option value="CL" @if (old('initiator_group') == 'CL') selected @endif>
                                                Central
                                                Laboratory</option>
                                            <option value="TT" @if (old('initiator_group') == 'TT') selected @endif>Tech
                                                Team</option>
                                            <option value="QA" @if (old('initiator_group') == 'QA') selected @endif>
                                                Quality Assurance</option>
                                            <option value="QM" @if (old('initiator_group') == 'QM') selected @endif>
                                                Quality Management</option>
                                            <option value="IA" @if (old('initiator_group') == 'IA') selected @endif>IT
                                                Administration</option>
                                            <option value="ACC" @if (old('initiator_group') == 'ACC') selected @endif>
                                                Accounting</option>
                                            <option value="LOG" @if (old('initiator_group') == 'LOG') selected @endif>
                                                Logistics</option>
                                            <option value="SM" @if (old('initiator_group') == 'SM') selected @endif>
                                                Senior Management</option>
                                            <option value="BA" @if (old('initiator_group') == 'BA') selected @endif>
                                                Business Administration</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Department Group Code</label>
                                        <input type="text" name="initiator_group_code" id="initiator_group_code"
                                            value="" >
                                    </div>
                                </div>
                              
                                {{-- <div class="col-lg-6 new-date-data-field">
                                    <div class="group-input input-date">
                                        <label for="Date Due">Proposed Date of Completion</label>
                                         <div><small class="text-primary">If revising Due Date, kindly mention revision reason in "Due Date Extension Justification" data field.</small></div>
                                        <div class="calenderauditee">
                                            <input type="text" id="due_date" readonly
                                                placeholder="DD-MMM-YYYY" />
                                            <input type="date" name="due_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input"
                                                oninput="handleDateInput(this, 'due_date')" />
                                        </div>
                                    </div>
                                </div> --}}
                               
                              

                                    <div class="col-lg-6">
                                        <div class="group-input">
                                            <label for="Initiator Group">Source of CAPA</label>
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
                                            <label for="initiated_through">Others<span class="text-danger d-none">*</span></label>
                                            <textarea name="others"></textarea>
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
                                    <div class="group-input" id="initiated_through_req">
                                        <label for="initiated_through">Source Document Name / No
                                               </label>
                                        <textarea name="source_document_name"></textarea>
                                    </div>
                                </div>

                               
                                <div class="col-12 sub-head">
                                    Proposed Corrective Action
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Corrective Action Details">
                                            Corrective Action Details
                                            <button type="button" id="corrective_action_add">+</button>
                                        </label>
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="corrective_action_details" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 100px;">Sr. No.</th>
                                                        <th>Action</th>
                                                        <th>Responsibility (Department)</th>
                                                        <th>Target Date</th>
                                                        <th>Completion Date</th>
                                                        <th>Implementation Verified by QA</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Initial Row Placeholder -->
                                                    <tr>
                                                        <td><input disabled type="text" name="corrective_action_details[0][serial]" value="1"></td>
                                                        <td><input type="text" name="corrective_action_details[0][action]"></td>
                                                        <td><input type="text" name="corrective_action_details[0][responsibility]"></td>
                                                        <td><input type="date" name="corrective_action_details[0][target_date]"></td>
                                                        <td><input type="date" name="corrective_action_details[0][completion_date]"></td>
                                                        <td><input type="text" name="corrective_action_details[0][verified_by_qa]"></td>
                                                        <td><button type="button" class="removeRowBtn">Remove</button></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                
                                <script>
                                    $(document).ready(function() {
                                        // Add new row in Corrective Action Details table
                                        $('#corrective_action_add').click(function(e) {
                                            e.preventDefault();
                                
                                            function generateCorrectiveActionTableRow(serialNumber) {
                                                var html = 
                                                    '<tr>' +
                                                    '<td><input disabled type="text" name="corrective_action_details[' + serialNumber + '][serial]" value="' + (serialNumber + 1) + '"></td>' +
                                                    '<td><input type="text" name="corrective_action_details[' + serialNumber + '][action]"></td>' +
                                                    '<td><input type="text" name="corrective_action_details[' + serialNumber + '][responsibility]"></td>' +
                                                    '<td><input type="date" name="corrective_action_details[' + serialNumber + '][target_date]"></td>' +
                                                    '<td><input type="date" name="corrective_action_details[' + serialNumber + '][completion_date]"></td>' +
                                                    '<td><input type="text" name="corrective_action_details[' + serialNumber + '][verified_by_qa]"></td>' +
                                                    '<td><button type="button" class="removeRowBtn">Remove</button></td>' +
                                                    '</tr>';
                                                return html;
                                            }
                                
                                            var tableBody = $('#corrective_action_details tbody');
                                            var rowCount = tableBody.children('tr').length;
                                            var newRow = generateCorrectiveActionTableRow(rowCount);
                                            tableBody.append(newRow);
                                        });
                                
                                        // Remove row in Corrective Action Details table
                                        $(document).on('click', '.removeRowBtn', function() {
                                            $(this).closest('tr').remove();
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
                                                        <th style="width: 100px;">Sr. No</th>
                                                        <th>Action</th>
                                                        <th>Responsibility (Department)</th>
                                                        <th>Target Date</th>
                                                        <th>Completion Date</th>
                                                        <th>Implementation Verified by QA</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Initial Row Placeholder -->
                                                    <tr>
                                                        <td><input disabled type="text" name="preventive_action_details[0][serial]" value="1"></td>
                                                        <td><input type="text" name="preventive_action_details[0][action]"></td>
                                                        <td><input type="text" name="preventive_action_details[0][responsibility]"></td>
                                                        <td><input type="date" name="preventive_action_details[0][target_date]"></td>
                                                        <td><input type="date" name="preventive_action_details[0][completion_date]"></td>
                                                        <td><input type="text" name="preventive_action_details[0][verified_by_qa]"></td>
                                                        <td><button type="button" class="removeRowBtn">Remove</button></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                
                                <script>
                                    $(document).ready(function() {
                                        // Add new row in Preventive Action Details table
                                        $('#preventive_action_add').click(function(e) {
                                            e.preventDefault();
                                
                                            function generatePreventiveActionTableRow(serialNumber) {
                                                var html = 
                                                    '<tr>' +
                                                    '<td><input disabled type="text" name="preventive_action_details[' + serialNumber + '][serial]" value="' + (serialNumber + 1) + '"></td>' +
                                                    '<td><input type="text" name="preventive_action_details[' + serialNumber + '][action]"></td>' +
                                                    '<td><input type="text" name="preventive_action_details[' + serialNumber + '][responsibility]"></td>' +
                                                    '<td><input type="date" name="preventive_action_details[' + serialNumber + '][target_date]"></td>' +
                                                    '<td><input type="date" name="preventive_action_details[' + serialNumber + '][completion_date]"></td>' +
                                                    '<td><input type="text" name="preventive_action_details[' + serialNumber + '][verified_by_qa]"></td>' +
                                                    '<td><button type="button" class="removeRowBtn">Remove</button></td>' +
                                                    '</tr>';
                                                return html;
                                            }
                                
                                            var tableBody = $('#preventive_action_details tbody');
                                            var rowCount = tableBody.children('tr').length;
                                            var newRow = generatePreventiveActionTableRow(rowCount);
                                            tableBody.append(newRow);
                                        });
                                
                                        // Remove row in Preventive Action Details table
                                        $(document).on('click', '.removeRowBtn', function() {
                                            $(this).closest('tr').remove();
                                        });
                                    });
                                </script>
                                
                                
                                
                                
                              
                                
                                <div class="col-12 sub-head">
                                    Implementation of Corrective Action by means of:
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Corrective Action Details">
                                            Corrective Action Details
                                            <button type="button" id="ImplementatioCorrective_action_add">+</button>
                                        </label>
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="implementation_corrective_action_details" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 100px;">Sr. No.</th>
                                                        <th>Action Plan</th>
                                                        <th>Implemented through</th>
                                                        <th>Implemented date</th>
                                                        <th>Verification of Implementation</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Initial Row Placeholder -->
                                                    <tr>
                                                        <td><input disabled type="text" name="implementation_corrective_action_details[0][serial]" value="1"></td>
                                                        <td><input type="text" name="implementation_corrective_action_details[0][action_plan]"></td>
                                                        <td><input type="text" name="implementation_corrective_action_details[0][implemented_through]"></td>
                                                        <td><input type="date" name="implementation_corrective_action_details[0][implemented_date]"></td>
                                                        <td><input type="text" name="implementation_corrective_action_details[0][verification_of_implementation]"></td>
                                                        <td><button type="button" class="removeRowBtn">Remove</button></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                
                                <script>
                                    $(document).ready(function() {
                                        // Add new row in Corrective Action Details table
                                        $('#ImplementatioCorrective_action_add').click(function(e) {
                                            e.preventDefault();
                                
                                            function generateCorrectiveActionTableRow(serialNumber) {
                                                var html = 
                                                    '<tr>' +
                                                    '<td><input disabled type="text" name="implementation_corrective_action_details[' + serialNumber + '][serial]" value="' + (serialNumber + 1) + '"></td>' +
                                                    '<td><input type="text" name="implementation_corrective_action_details[' + serialNumber + '][action_plan]"></td>' +
                                                    '<td><input type="text" name="implementation_corrective_action_details[' + serialNumber + '][implemented_through]"></td>' +
                                                    '<td><input type="date" name="implementation_corrective_action_details[' + serialNumber + '][implemented_date]"></td>' +
                                                    '<td><input type="text" name="implementation_corrective_action_details[' + serialNumber + '][verification_of_implementation]"></td>' +
                                                    '<td><button type="button" class="removeRowBtn">Remove</button></td>' +
                                                    '</tr>';
                                                return html;
                                            }
                                
                                            var tableBody = $('#implementation_corrective_action_details tbody');
                                            var rowCount = tableBody.children('tr').length;
                                            var newRow = generateCorrectiveActionTableRow(rowCount);
                                            tableBody.append(newRow);
                                        });
                                
                                        // Remove row in Corrective Action Details table
                                        $(document).on('click', '.removeRowBtn', function() {
                                            $(this).closest('tr').remove();
                                        });
                                    });
                                </script>
                                

                                

                                
                            </div> 
                           
                        </div>
                        <div class="button-block">
                            <button type="submit" id="ChangesaveButton" class="saveButton">Save</button>
                            {{-- <button type="button" id="ChangeNextButton" class="nextButton">Next</button> --}}
                            <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white"> Exit </a> </button>

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
                                        <label for="comments_new">Comments</label>
                                        <textarea name="comments_cloasure"></textarea>
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
                                        
                                        <textarea name="justification"></textarea>
                                    </div>
                                </div>

                               
                                {{-- </div> --}}
                                <div class="col-12 sub-head">
                                    Material Details
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Material Details">
                                            Material Details<button type="button" name="ann"
                                                id="material">+</button>
                                        </label>
                                        <table class="table table-bordered" id="material_details" >
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
                                                id="equipment">+</button>
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
                                                <td><input disabled type="text" name="serial_number[]" value="1">
                                                </td>
                                                <td><input type="text" name="equipment[]"></td>
                                                <td><input type="text" name="equipment_instruments[]"></td>
                                                <td><input type="text" name="equipment_comments[]"></td>
                                            </tbody> 
                                        </table>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton">Save</button>
                                {{-- <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button> --}}
                                <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white"> Exit </a> </button>
                            </div>
                        </div>
                    </div>

                    <!-- Project Study content****************************** -->
                    <div id="CCForm3" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Project Datails Application">Project Datails Application</label>
                                        <select name="project_details_application">
                                            <option value="">Enter Your Selection Here</option>
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Protocol/Study Number">Initiator Group</label>
                                        <select name="project_initiator_group">
                                            <option value="">Enter Your Selection Here</option>
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Site Number">Site Number</label>
                                        <input type="text" name="site_number">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Subject Number">Subject Number</label>
                                        <input type="text" name="subject_number">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Subject Initials">Subject Initials</label>
                                        <input type="text" name="subject_initials">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Sponsor">Sponsor</label>
                                        <input type="text" name="sponsor">
                                    </div>
                                </div>
                                 <div class="col-12">
                                    <div class="group-input">
                                        <label for="General Deviation">General Deviation</label>
                                        <textarea name="general_deviation"></textarea>
                                    </div>
                                </div> 
                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton">Save</button>
                                {{-- <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button> --}}
                                <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white"> Exit </a> </button>
                            </div>
                        </div>
                    </div>

                    <!-- CAPA Details content ****************************-->
                    <div id="CCForm4" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="Initiator Group">Effectiveness Verification of CAPA:</label>
                                        <select name="effectiveness_verification_capa"  id="assignableSelect"
                                            onchange="toggleRootCauseInput()">
                                            <option value=" ">Is Effectiveness verification required?</option>
                                            <option value="YES">YES</option>
                                            <option value="NO">NO</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-12" id="rootCauseGroup" style="display: none;">
                                    <div class="group-input">
                                        <label for="RootCause">Remark</label>
                                        <textarea name="effectivenessRemark"  id="rootCauseTextarea" rows="4" placeholder="Describe the root cause here"></textarea>
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
                                    
                                <div class="col-md-12">
                                    <div class="group-input">
                                        <label for="search">
                                            CAPA Type<span class="text-danger"></span>
                                        </label>
                                        <select id="select-state" placeholder="Select..." name="capa_type">
                                            <option value="">Select a value</option>
                                            <option value="Corrective Action">Corrective Action</option>
                                            <option value="Preventive Action">Preventive Action</option>
                                            <option value="Corrective & Preventive Action">Corrective & Preventive Action
                                            </option>
                                        </select>
                                        @error('assign_to')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Corrective Action">Corrective Action</label>
                                        <textarea name="corrective_action"></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Preventive Action">Preventive Action</label>
                                        <textarea name="preventive_action"></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Supervisor Review Comments">Supervisor Review Comments</label>
                                        <textarea name="supervisor_review_comments"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton">Save</button>
                                {{-- <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button> --}}
                                <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white"> Exit </a> </button>
                            </div>
                        </div>
                    </div>
                      <div id="CCForm5" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="sub-head">
                                CFT Information
                            </div>
                            <div class="row">

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Microbiology">CFT Reviewer</label>
                                        <select name="Microbiology_new">
                                            <option value="0">-- Select --</option>
                                            <option value="yes" selected>Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Microbiology-Person">CFT Reviewer Person</label>
                                        <select  name="Microbiology_Person[]" placeholder="Select CFT Reviewers"
                                            data-search="false" data-silent-initial-value-set="true" id="cft_reviewer">
                                            <option value="0">-- Select --</option>
                                            @foreach ($cft as $data)
                                                <option value="{{ $data->id}}" selected>{{ $data->name}}</option>
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
                                        <select name="goup_review">
                                            <option value="0">-- Select --</option>
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Production">Production</label>
                                        <select name="Production_new">
                                            <option value="0">-- Select --</option>
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Production-Person">Production Person</label>
                                        <select name="Production_Person">
                                            <option value="0">-- Select --</option>
                                            @foreach ($users as $data)
                                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Quality-Approver">Quality Approver</label>
                                        <select name="Quality_Approver">
                                            <option value="0">-- Select --</option>
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Quality-Approver-Person">Quality Approver Person</label>
                                        <select name="Quality_Approver_Person">
                                            <option value="0">-- Select --</option>
                                            @foreach ($users as $data)
                                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="bd_domestic">Others</label>
                                        <select name="bd_domestic">
                                            <option value="0">-- Select --</option>
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="bd_domestic-Person">Others Person</label>
                                        <select name="Bd_Person">
                                            <option value="0">-- Select --</option>
                                            @foreach ($users as $data)
                                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Additional Attachments">Additional Attachments</label>
                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                documents</small></div>
                                        <div class="file-attachment-field">
                                            <div class="file-attachment-list" id="additional_attachments"></div>
                                            <div class="add-btn">
                                                <div>Add</div>
                                                <input type="file" id="myfile" name="additional_attachments[]"
                                                    oninput="addMultipleFiles(this, 'additional_attachments')" multiple>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton">Save</button>
                                {{-- <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button> --}}
                                <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}">
                                        Exit </a> </button>

                            </div>
                        </div>
                    </div> 
                    <div id="CCForm6" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="sub-head">
                                CFT Feedback
                            </div>
                            <div class="row">

                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="comments">CFT Comments</label>
                                        <textarea name="cft_comments_form"></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="comments">CFT Attachment</label>
                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                documents</small></div>
                                        <div class="file-attachment-field">
                                            <div class="file-attachment-list" id="cft_attchament_new"> </div>
                                                                                        
                                            <div class="add-btn">
                                                <div>Add</div>
                                                <input type="file" id="myfile" name="cft_attchament_new[]"
                                                    oninput="addMultipleFiles(this, 'cft_attchament_new')" multiple>
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
                                        <textarea name="qa_comments_new"></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="comments">QA Head Designee Comments</label>
                                        <textarea name="designee_comments_new"></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="comments">Warehouse Comments</label>
                                        <textarea name="Warehouse_comments_new"></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="comments">Engineering Comments</label>
                                        <textarea name="Engineering_comments_new"></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="comments">Instrumentation Comments</label>
                                        <textarea name="Instrumentation_comments_new"></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="comments">Validation Comments</label>
                                        <textarea name="Validation_comments_new"></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="comments">Others Comments</label>
                                        <textarea name="Others_comments_new"></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="comments">Group Comments</label>
                                        <textarea name="Group_comments_new"></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="group-attachments">Group Attachments</label>
                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                documents</small></div>
                                        <div class="file-attachment-field">
                                            <div class="file-attachment-list" id="group_attachments_new"></div>
                                            <div class="add-btn">
                                                <div>Add</div>
                                                <input type="file" id="myfile" name="group_attachments_new[]"
                                                    oninput="addMultipleFiles(this, 'group_attachments_new')" multiple>
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
                                <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}">
                                        Exit </a> </button>
                            </div>
                        </div>
                    </div>
                    <!-- CAPA Closure content -->
                    <div id="CCForm7" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="QA Review & Closure">QA Review & Closure</label>
                                        <textarea name="qa_review"></textarea>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="QA Review & Closure">Remark</label>
                                        <textarea name="qa_review"></textarea>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="QA Review & Closure"> Closure Conclusion by Head Quality / Designee</label>
                                        <textarea name="qa_review"></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Closure Attachments">Closure Attachment</label>
                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                documents</small></div>
                                        {{-- <input multiple type="file" id="myfile" name="closure_attachment[]"> --}}
                                        <div class="file-attachment-field">
                                            <div class="file-attachment-list" id="closure_attachment"></div>
                                            <div class="add-btn">
                                                <div>Add</div>
                                                <input type="file" id="myfile" name="closure_attachment[]"
                                                    oninput="addMultipleFiles(this, 'closure_attachment')" multiple>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="col-12 sub-head">
                                    Effectiveness Check Details
                                </div> -->
                                <!-- <div class="col-12">
                                    <div class="group-input">
                                        <label for="Effectiveness Check Required">Effectiveness Check
                                            Required?</label>
                                        <select name="effect_check" onChange="setCurrentDate(this.value)">
                                            <option value="">Enter Your Selection Here</option>
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                    </div>
                                </div> -->
                                <!-- <div class="col-6 new-date-data-field">
                                    <div class="group-input input-date">
                                        <label for="EffectCheck Creation Date">Effectiveness Check Creation Date</label>
                                        {{-- <input type="date" name="effect_check_date"> --}}
                                        <div class="calenderauditee">
                                            <input type="text" name="effect_check_date" id="effect_check_date" readonly
                                                placeholder="DD-MMM-YYYY" />
                                            <input type="date" name="effect_check_date" class="hide-input"
                                                oninput="handleDateInput(this, 'effect_check_date')" />
                                        </div>
                                    </div>
                                </div> -->
                                <!-- <div class="col-6">
                                    <div class="group-input">
                                        <label for="Effectiveness_checker">Effectiveness Checker</label>
                                        <select id="select-state" placeholder="Select..." name="Effectiveness_checker">
                                            <option value="">Select a person</option>
                                            @foreach ($users as $value)
                                                <option value="{{ $value->id }}">{{ $value->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> -->
                                <!-- <div class="col-12">
                                    <div class="group-input">
                                        <label for="effective_check_plan">Effectiveness Check Plan</label>
                                        <textarea name="effective_check_plan"></textarea>
                                    </div>
                                </div> -->
                                <div class="col-12 sub-head">
                                    Extension Justification
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="due_date_extension">Due Date Extension Justification</label>
                                        <div><small class="text-primary">Please Mention justification if due date is
                                                crossed</small></div>
                                        <textarea name="due_date_extension"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton">Save</button>
                                <!-- <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button> -->
                                <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white"> Exit </a> </button>
                            </div>
                        </div>
                    </div>

                    <!-- Activity Log content -->
                    <div id="CCForm8" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Plan Proposed By">Plan Proposed By</label>
                                        <input type="hidden" name="plan_proposed_by">
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Plan Proposed On">Plan Proposed On</label>
                                        <input type="hidden" name="plan_proposed_on">
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Plan Approved By">Plan Approved By</label>
                                        <input type="hidden" name="Plan_approved_by">
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Plan Approved On">Plan Approved On</label>
                                        <input type="hidden" name="Plan_approved_on">
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="QA More Info Required By">QA More Info Required By</label>
                                        <input type="hidden" name="qa_more_info_required_by">
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="QA More Info Required On">QA More Info Required On</label>
                                        <input type="hidden" name="qa_more_info_required_on">
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Cancelled By">Cancelled By</label>
                                        <input type="hidden" name="cancelled_by">
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Cancelled On">Cancelled On</label>
                                        <input type="hidden" name="cancelled_on">
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Completed By">Completed By</label>
                                        <input type="hidden" name="completed_by">
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Completed On">Completed On</label>
                                        <input type="hidden" name="completed_on">
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Approved By">Approved By</label>
                                        <input type="hidden" name="approved_by">

                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Approved On">Approved On</label>
                                        <input type="hidden" name="approved_on">
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Rejected By">Rejected By</label>
                                        <input type="hidden" name="rejected_by">
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Rejected On">Rejected On</label>
                                        <input type="hidden" name="rejected_on">
                                        <div class="static"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="button-block">
                                <!-- <button type="submit" class="saveButton">Save</button>
                                <button type="button" class="backButton" onclick="previousStep()">Back</button> -->
                                <button type="submit">Submit</button>
                                <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white" href="#"> Exit </a> </button>
                            </div>
                        </div>
                    </div>

                </div>
            </form>

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

    <script>
        VirtualSelect.init({
            ele: '#Facility, #Group, #Audit, #Auditee , #capa_related_record,#cft_reviewer'
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
        
        function setCurrentDate(item){
            if(item == 'yes'){
                $('#effect_check_date').val('{{ date('d-M-Y')}}');
            }
            else{
                $('#effect_check_date').val('');
            }
        }
    </script>
    <script>
        var maxLength = 255;
        $('#docname').keyup(function() {
            var textlen = maxLength - $(this).val().length;
            $('#rchars').text(textlen);});
    </script>
@endsection
