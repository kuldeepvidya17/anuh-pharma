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
            {{ Helpers::getDivisionName(session()->get('division')) }} / Market Complaint
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
                <button class="cctablinks" onclick="openCity(event, 'CCForm2')">QA Head / Designee</button>
                {{-- <button class="cctablinks" onclick="openCity(event, 'CCForm3')">Project/Study</button> --}}
                <button class="cctablinks" onclick="openCity(event, 'CCForm4')">Quality Control</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm5')">CFT</button>
                {{-- <button class="cctablinks" onclick="openCity(event, 'CCForm6')">Group Comments</button> --}}
                <button class="cctablinks" onclick="openCity(event, 'CCForm7')"> Closure</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm8')">Activity Log</button>
            </div>

            <form action="{{ route('marketcomplaintstore') }}" method="post" enctype="multipart/form-data">
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
                                        <label for="RLS Record Number">Record Number</label>
                                        <input disabled type="text" name="record_number"
                                            value="{{ Helpers::getDivisionName(session()->get('division')) }}/CAPA/{{ date('Y') }}/{{ $record_number }}">
                                        {{-- <div class="static">QMS-EMEA/CAPA/{{ date('Y') }}/{{ $record_number }}</div> --}}
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
                                <div class="col-md-6">
                                    <div class="group-input">
                                        <label for="search">
                                            Assigned To <span class="text-danger"></span>
                                        </label>
                                        <select id="select-state" placeholder="Select..." name="assign_to">
                                            <option value="">Select a value</option>
                                            @foreach ($users as $value)
                                                <option value="{{ $value->id }}">{{ $value->name }}</option>
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
                                         <div><small class="text-primary">If revising Due Date, kindly mention revision reason in "Due Date Extension Justification" data field.</small></div>
                                        <div class="calenderauditee">
                                            <input type="text" id="due_date" readonly
                                                placeholder="DD-MMM-YYYY" />
                                            <input type="date" name="due_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input"
                                                oninput="handleDateInput(this, 'due_date')" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group">Initiator Group</label>
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
                                        <label for="Initiator Group Code">Initiator Group Code</label>
                                        <input type="text" name="initiator_group_code" id="initiator_group_code"
                                            value="" >
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

                                    <div class="col-12 sub-head">
                                    Product Plan
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Product Details">
                                            Product Details
                                            <button type="button" id="product_add">+</button>
                                        </label>
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="product_details" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 100px;">Row #</th>
                                                        <th>Product Name</th>
                                                        <th>Batch No</th>
                                                        <th>Mfg. Date</th>
                                                        <th>Exp. Date / Retest Date</th>
                                                        <th>Batch Size</th>
                                                        <th>Dispatch Date</th>
                                                        <th>Dispatch Qty.</th>
                                                        <th>Date of Completion</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Initial Row Placeholder -->
                                                    <tr>
                                                        <td><input disabled type="text" name="product_details[0][serial]" value="1"></td>
                                                        <td><input type="text" name="product_details[0][product_name]"></td>
                                                        <td><input type="text" name="product_details[0][batch_no]"></td>
                                                        <td><input type="date" name="product_details[0][mfg_date]"></td>
                                                        <td><input type="date" name="product_details[0][exp_date]"></td>
                                                        <td><input type="text" name="product_details[0][batch_size]"></td>
                                                        <td><input type="date" name="product_details[0][dispatch_date]"></td>
                                                        <td><input type="text" name="product_details[0][dispatch_qty]"></td>
                                                        <td><input type="date" name="product_details[0][completion_date]"></td>
                                                        <td><button type="button" class="removeRowBtn">Remove</button></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                
                                <script>
                                    $(document).ready(function() {
                                        // Add new row in Product Details table
                                        $('#product_add').click(function(e) {
                                            e.preventDefault();
                                
                                            function generateProductTableRow(serialNumber) {
                                                var html = 
                                                    '<tr>' +
                                                    '<td><input disabled type="text" name="product_details[' + serialNumber + '][serial]" value="' + (serialNumber + 1) + '"></td>' +
                                                    '<td><input type="text" name="product_details[' + serialNumber + '][product_name]"></td>' +
                                                    '<td><input type="text" name="product_details[' + serialNumber + '][batch_no]"></td>' +
                                                    '<td><input type="date" name="product_details[' + serialNumber + '][mfg_date]"></td>' +
                                                    '<td><input type="date" name="product_details[' + serialNumber + '][exp_date]"></td>' +
                                                    '<td><input type="text" name="product_details[' + serialNumber + '][batch_size]"></td>' +
                                                    '<td><input type="date" name="product_details[' + serialNumber + '][dispatch_date]"></td>' +
                                                    '<td><input type="text" name="product_details[' + serialNumber + '][dispatch_qty]"></td>' +
                                                    '<td><input type="date" name="product_details[' + serialNumber + '][completion_date]"></td>' +
                                                    '<td><button type="button" class="removeRowBtn">Remove</button></td>' +
                                                    '</tr>';
                                                return html;
                                            }
                                
                                            var tableBody = $('#product_details tbody');
                                            var rowCount = tableBody.children('tr').length;
                                            var newRow = generateProductTableRow(rowCount);
                                            tableBody.append(newRow);
                                        });
                                
                                        // Remove row in Product Details table
                                        $(document).on('click', '.removeRowBtn', function() {
                                            $(this).closest('tr').remove();
                                        });
                                    });
                                </script>
    

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="repeat">Name & Address of the complainant agency</label>
                                        
                                       {{-- <textarea type="longtext" name="nameAddressagency"></textarea> --}}
                                       <textarea name="nameAddressagency" id="" ></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="repeat">Name & Designation complainer
                                            </label>
                                        
                                       <input type="text" name="nameDesgnationCom">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="repeat">Phone No
                                            </label>
                                        
                                       <input type="text" name="phone_no">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="repeat">Email address
                                            </label>
                                        
                                       <input type="text" name="email_address">
                                    </div>
                                </div>

                                    <div class="col-lg-6">
                                        <div class="group-input">
                                            <label for="repeat">Sample Recd</label>
                                            
                                            <select name="sample_recd">
                                
                                                <option value="">Enter Your Selection Here</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                                <option value="NA">NA</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="group-input">
                                            <label for="repeat">Test Results recd</label>
                                            
                                            <select name="test_results_recd">
                                
                                                <option value="">Enter Your Selection Here</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                                <option value="NA">NA</option>
                                            </select>
                                        </div>
                                    </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="severity-level">Classification based on receipt of complaint</label>
                                        <select name="severity_level_form">
                                            <option value="0">-- Select --</option>
                                            <option value="minor">Minor</option>
                                            <option value="major">Major</option>
                                            <option value="critical">Critical</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="repeat">Acknowledgment sent to customer through marketing department by Head QA </label>
                                        
                                        <select name="acknowledgment_sent">
                            
                                            <option value="">Enter Your Selection Here</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>
                               
                                <div class="col-12 sub-head">
                                    Previous History of Product Specific 
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Previous History">
                                            Same nature of Complaint (If any)
                                            <button type="button" id="history_add">+</button>
                                        </label>
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="history_details" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 100px;">Row #</th>
                                                        <th>Complaint Receipt Date</th>
                                                        <th>Complaint Received From</th>
                                                        <th>Nature of Complaint</th>
                                                        <th>CAPA Taken</th>
                                                        <th>Remark</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><input disabled type="text" name="history_details[0][serial]" value="1"></td>
                                                        <td><input type="text" name="history_details[0][receipt_date]"></td>
                                                        <td><input type="text" name="history_details[0][received_from]"></td>
                                                        <td><input type="text" name="history_details[0][nature_of_complaint]"></td>
                                                        <td><input type="text" name="history_details[0][capa_taken]"></td>
                                                        <td><input type="text" name="history_details[0][remark]"></td>
                                                        <td><button type="button" class="removeRowBtn">Remove</button></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                
                                <script>
                                    $(document).ready(function() {
                                        // Add new row in History Details table
                                        $('#history_add').click(function(e) {
                                            e.preventDefault();
                                
                                            function generateHistoryTableRow(serialNumber) {
                                                var html =
                                                    '<tr>' +
                                                    '<td><input disabled type="text" name="history_details[' + serialNumber +
                                                    '][serial]" value="' + (serialNumber + 1) + '"></td>' +
                                                    '<td><input type="text" name="history_details[' + serialNumber +
                                                    '][receipt_date]"></td>' +
                                                    '<td><input type="text" name="history_details[' + serialNumber +
                                                    '][received_from]"></td>' +
                                                    '<td><input type="text" name="history_details[' + serialNumber +
                                                    '][nature_of_complaint]"></td>' +
                                                    '<td><input type="text" name="history_details[' + serialNumber +
                                                    '][capa_taken]"></td>' +
                                                    '<td><input type="text" name="history_details[' + serialNumber +
                                                    '][remark]"></td>' +
                                                    '<td><button type="button" class="removeRowBtn">Remove</button></td>' +
                                                    '</tr>';
                                                return html;
                                            }
                                
                                            var tableBody = $('#history_details tbody');
                                            var rowCount = tableBody.children('tr').length;
                                            var newRow = generateHistoryTableRow(rowCount);
                                            tableBody.append(newRow);
                                        });
                                
                                        // Remove row in both tables
                                        $(document).on('click', '.removeRowBtn', function() {
                                            $(this).closest('tr').remove();
                                        });
                                    });
                                </script>
                                
                               
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="repeat">Analysis / Physical examination of control sample to be done</label>
                                        
                                        <select name="analysis_physical_examination"
                                            onchange="otherController(this.value, 'Yes', 'repeat_nature')">
                                            <option value="">Enter Your Selection Here</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>
                                
                               
                            </div>
                            <div class="button-block">
                                <button type="submit" id="ChangesaveButton" class="saveButton">Save</button>
                                {{-- <button type="button" id="ChangeNextButton" class="nextButton">Next</button> --}}
                                <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white"> Exit </a> </button>

                            </div>
                        </div>
                    </div>

                    <!-- Product Information content -->
                    <div id="CCForm2" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                               
                                {{-- </div> --}}
                                <div class="col-12 sub-head">
                                    Testing Plan
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Material Details">
                                            Material Details
                                            <button type="button" id="material_add">+</button>
                                        </label>
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="material_details" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 100px;">Row #</th>
                                                        <th>Batch No</th>
                                                        <th>Physical Test to be Performed</th>
                                                        <th>Observation</th>
                                                        <th>Specification</th>
                                                        <th>Batch Disposition Decision</th>
                                                        <th>Remark</th>
                                                        <th>Batch Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><input disabled type="text" name="material_details[0][serial]" value="1"></td>
                                                        <td><input type="text" name="material_details[0][batch_no]"></td>
                                                        <td><input type="text" name="material_details[0][physical_test]"></td>
                                                        <td><input type="text" name="material_details[0][observation]"></td>
                                                        <td><input type="text" name="material_details[0][specification]"></td>
                                                        <td><input type="text" name="material_details[0][batch_disposition_decision]"></td>
                                                        <td><input type="text" name="material_details[0][remark]"></td>
                                                        <td><input type="text" name="material_details[0][batch_status]"></td>
                                                        <td><button type="button" class="removeRowBtn">Remove</button></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                
                                <script>
                                    $(document).ready(function() {
                                        // Add new row in Material Details table
                                        $('#material_add').click(function(e) {
                                            e.preventDefault();
                                
                                            function generateMaterialTableRow(serialNumber) {
                                                var html =
                                                    '<tr>' +
                                                    '<td><input disabled type="text" name="material_details[' + serialNumber +
                                                    '][serial]" value="' + (serialNumber + 1) + '"></td>' +
                                                    '<td><input type="text" name="material_details[' + serialNumber +
                                                    '][batch_no]"></td>' +
                                                    '<td><input type="text" name="material_details[' + serialNumber +
                                                    '][physical_test]"></td>' +
                                                    '<td><input type="text" name="material_details[' + serialNumber +
                                                    '][observation]"></td>' +
                                                    '<td><input type="text" name="material_details[' + serialNumber +
                                                    '][specification]"></td>' +
                                                    '<td><input type="text" name="material_details[' + serialNumber +
                                                    '][batch_disposition_decision]"></td>' +
                                                    '<td><input type="text" name="material_details[' + serialNumber +
                                                    '][remark]"></td>' +
                                                    '<td><input type="text" name="material_details[' + serialNumber +
                                                    '][batch_status]"></td>' +
                                                    '<td><button type="button" class="removeRowBtn">Remove</button></td>' +
                                                    '</tr>';
                                                return html;
                                            }
                                
                                            var tableBody = $('#material_details tbody');
                                            var rowCount = tableBody.children('tr').length;
                                            var newRow = generateMaterialTableRow(rowCount);
                                            tableBody.append(newRow);
                                        });
                                
                                        // Remove row in Material Details table
                                        $(document).on('click', '.removeRowBtn', function() {
                                            $(this).closest('tr').remove();
                                        });
                                    });
                                </script>
                                
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Comments">Comments </label>
                                        <textarea name="capa_qa_comments2"></textarea>
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
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Material Details">
                                            Quality Control<button type="button" name="ann"
                                                id="material">+</button>
                                        </label>
                                        <table class="table table-bordered" id="material_details" >
                                            <thead>
                                                <tr>
                                                    <th>Sr. No.</th>
                                                    <th>Test</th>
                                                    <th>Control sample</th>
                                                    <th>Complaint sample</th>
                                                    <th>Initial Result</th>
                                                    <th>Limits</th>
                                                    <th>Remark</th>
                                                    <th>Batch Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                           
                                        </table>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="repeat">QA for review of root cause of Market complaint</label>
                                        <div><small class="text-primary">Identification of Cross functional departments by QA for review of root cause of Market complaint</small></div>
                                        <select name="Identification_Cross_functional">
                                            <option value="">Enter Your Selection Here</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>
                               
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Corrective Action">Preliminary Investigation Report sent by QA to complainant on</label>
                                        <textarea name="Preliminary_Investigation_Report"></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Closure Attachments">Attachment(if any)</label>
                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                documents</small></div>
                                        {{-- <input multiple type="file" id="myfile" name="closure_attachment[]"> --}}
                                        <div class="file-attachment-field">
                                            <div class="file-attachment-list" id="attachment"></div>
                                            <div class="add-btn">
                                                <div>Add</div>
                                                <input type="file" id="myfile" name="attachment[]"
                                                    oninput="addMultipleFiles(this, 'attachment')" multiple>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 new-date-data-field">
                                    <div class="group-input input-date">
                                        <label for="Date Due"> Further Response Received from customer </label>
                                        <div class="calenderauditee">
                                            <input type="text" id="Further_Response_Received" readonly
                                                placeholder="DD-MMM-YYYY" />
                                            <input type="date" name="Further_Response_Received" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input"
                                                oninput="handleDateInput(this, 'Further_Response_Received')" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Preventive Action">Details of Response </label>
                                        <textarea name="Details_of_Response"></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="repeat">Further investigation / Additional testing required</label>
                                        <select name="Further_investigation_Additional_testing">
                                            <option value="">Enter Your Selection Here</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="repeat">Method / Tools to be used for investigation</label>
                                        <select name="Method_Tools_to_be_used_for">
                                            <option value="">Enter Your Selection Here</option>
                                            <option value="Method / Tools to be used for">Method / Tools to be used for</option>
                                            <option value="5 Why’s">5 Why’s </option>
                                            <option value="BMCR and BPCR review">BMCR and BPCR review </option>
                                            <option value="others (Pl. specify)">others (Pl. specify) </option>
                                           
                                        </select>
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
                   
                   {{-- -------------------CFTstrt----------- --}}
                   <div id="CCForm5" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">
                            <div class="sub-head">
                                Production
                            </div>
                            <script>
                                $(document).ready(function() {
                                    $('.p_erson').hide();

                                    $('[name="Production_Review"]').change(function() {
                                        if ($(this).val() === 'yes') {
                                            $('.p_erson').show();
                                            $('.p_erson span').show();
                                        } else {
                                            $('.p_erson').hide();
                                            $('.p_erson span').hide();
                                        }
                                    });
                                });
                            </script>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Production Review">Production Review Required ?</label>
                                    <select name="Production_Review" id="Production_Review" disabled>
                                        <option value="0">-- Select --</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                        <option value="na">NA</option>

                                    </select>

                                </div>
                            </div>
                            @php
                                $division = DB::table('q_m_s_divisions')
                                    ->where('name', Helpers::getDivisionName(session()->get('division')))
                                    ->first();
                                $userRoles = DB::table('user_roles')
                                    ->where(['q_m_s_roles_id' => 22, 'q_m_s_divisions_id' => $division->id])
                                    ->get();
                                $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                            @endphp

                            <div class="col-lg-6 p_erson">
                                <div class="group-input">
                                    <label for="Production person">Production Person</label>
                                    <select name="Production_person" id="Production_person">
                                        <option value="">-- Select --</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3 p_erson" >
                                <div class="group-input">
                                    <label for="Production assessment">Impact Assessment (By Production)</label>
                                    <div><small class="text-primary">Please insert "NA" in the data field if it does
                                            not require completion</small></div>
                                    <textarea class="" name="Production_assessment" id="summernote-17">
                                </textarea>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3 p_erson">
                                <div class="group-input">
                                    <label for="Production feedback">Production Feedback</label>
                                    <div><small class="text-primary">Please insert "NA" in the data field if it does
                                            not require completion</small></div>
                                    <textarea class="" name="Production_feedback" id="summernote-18">
                                </textarea>
                                </div>
                            </div>

                            <div class="col-lg-12 p_erson">
                                <div class="group-input">
                                    <label for="production attachment"> Production Attachments</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id="production_attachment"></div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile" name="production_attachment[]"
                                                oninput="addMultipleFiles(this, 'production_attachment')" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3 p_erson">
                                <div class="group-input">
                                    <label for="Production Review Completed By">Production Review Completed By</label>
                                    <input disabled type="text" name="production_by" id="production_by">
                                </div>
                            </div>
                            <div class="col-lg-6 new-date-data-field p_erson">
                                <div class="group-input input-date">
                                    <label for="Production Review Completed On">Production Review Completed On</label>
                                    <div class="calenderauditee">
                                        <input type="text" id="production_on" readonly
                                            placeholder="DD-MM-YYYY" />
                                        <input type="date" name="production_on"
                                            min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input"
                                            oninput="handleDateInput(this, 'production_on')" />
                                    </div>
                                </div>
                            </div>
                            <script>
                                $(document).ready(function() {
                                    $('.warehouse').hide();

                                    $('[name="Warehouse_review"]').change(function() {
                                        if ($(this).val() === 'yes') {
                                            $('.warehouse').show();
                                            $('.warehouse span').show();
                                        } else {
                                            $('.warehouse').hide();
                                            $('.warehouse span').hide();
                                        }
                                    });
                                });
                            </script>
                            <div class="sub-head">
                                Warehouse
                            </div>
                            <div class="col-lg-6 ">
                                <div class="group-input">
                                    <label for="Warehouse Review Required">Warehouse Review Required ?</label>
                                    <select name="Warehouse_review" id="Warehouse_review" disabled>
                                        <option value="0">-- Select --</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                        <option value="na">NA</option>

                                    </select>

                                </div>
                            </div>
                            @php
                                $division = DB::table('q_m_s_divisions')
                                    ->where('name', Helpers::getDivisionName(session()->get('division')))
                                    ->first();
                                $userRoles = DB::table('user_roles')
                                    ->where(['q_m_s_roles_id' => 23, 'q_m_s_divisions_id' => $division->id])
                                    ->get();
                                $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                            @endphp
                            <div class="col-lg-6 warehouse">
                                <div class="group-input">
                                    <label for="Customer notification">Warehouse Person</label>
                                    <select name="Warehouse_notification" id="Warehouse_person">
                                        <option value="0">-- Select --</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3 warehouse">
                                <div class="group-input">
                                    <label for="Impact Assessment1">Impact Assessment (By Warehouse)</label>
                                    <div><small class="text-primary">Please insert "NA" in the data field if it does
                                            not require completion</small></div>
                                    <textarea class="" name="Warehouse_assessment" id="summernote-19">
                                </textarea>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3 warehouse">
                                <div class="group-input">
                                    <label for="productionfeedback">Warehouse Feedback</label>
                                    <div><small class="text-primary">Please insert "NA" in the data field if it does
                                            not require completion</small></div>
                                    <textarea class="" name="Warehouse_feedback" id="summernote-20">
                                </textarea>
                                </div>
                            </div>
                            <div class="col-lg-12 warehouse">
                                <div class="group-input">
                                    <label for="Warehouse attachment"> Warehouse Attachments</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id="Warehouse_attachment"></div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile" name="Warehouse_attachment[]"
                                                oninput="addMultipleFiles(this, 'Warehouse_attachment')" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3 warehouse">
                                <div class="group-input">
                                    <label for="Warehousefeedback">Warehouse Review Completed By</label>
                                    <input disabled type="text" name="Warehouse_by" id="Warehouse_by">

                                </div>
                            </div>

                            <div class="col-lg-6 new-date-data-field warehouse">
                                <div class="group-input input-date">
                                    <label for="Warehouse Review Completed On">Warehouse Review Completed On</label>
                                    <div class="calenderauditee">
                                        <input type="text" id="Warehouse_on" readonly placeholder="DD-MM-YYYY" />
                                        <input type="date" name="Warehouse_on"
                                            min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input"
                                            oninput="handleDateInput(this, 'Warehouse_on')" />
                                    </div>
                                </div>
                            </div>
                            <script>
                                $(document).ready(function() {
                                    $('.quality_control').hide();

                                    $('[name="Quality_review"]').change(function() {
                                        if ($(this).val() === 'yes') {
                                            $('.quality_control').show();
                                            $('.quality_control span').show();
                                        } else {
                                            $('.quality_control').hide();
                                            $('.quality_control span').hide();
                                        }
                                    });
                                });
                            </script>
                            <div class="sub-head">
                                Quality Control
                            </div>
                            <div class="col-lg-6 quality_control">
                                <div class="group-input">
                                    <label for="Quality Control Review Required">Quality Control Review Required
                                        ?</label>
                                    <select name="Quality_review" id="Quality_review" disabled>
                                        <option value="0">-- Select --</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                        <option value="na">NA</option>

                                    </select>

                                </div>
                            </div>
                            @php
                                $division = DB::table('q_m_s_divisions')
                                    ->where('name', Helpers::getDivisionName(session()->get('division')))
                                    ->first();
                                $userRoles = DB::table('user_roles')
                                    ->where(['q_m_s_roles_id' => 24, 'q_m_s_divisions_id' => $division->id])
                                    ->get();
                                $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                            @endphp
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Quality Control Person">Quality Control Person</label>
                                    <select name="Quality_Control_Person" id="Quality_Control_Person" disabled>
                                        <option value="0">-- Select --</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>
                            <div class="col-md-12 mb-3 quality_control">
                                <div class="group-input">
                                    <label for="Impact Assessment2">Impact Assessment (By Quality Control)</label>
                                    <textarea class="" name="Quality_Control_assessment" id="summernote-21">
                                </textarea>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3 quality_control">
                                <div class="group-input">
                                    <label for="Quality Control Feedback">Quality Control Feedback</label>
                                    <textarea class="" name="Quality_Control_feedback" id="summernote-22">
                                </textarea>
                                </div>
                            </div>
                            <div class="col-lg-12 quality_control">
                                <div class="group-input">
                                    <label for="Quality Control Attachments">Quality Control Attachments</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id="Quality_Control_attachment"></div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile" name="Quality_Control_attachment[]"
                                                oninput="addMultipleFiles(this, 'Quality_Control_attachment')"
                                                multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3 quality_control">
                                <div class="group-input">
                                    <label for="productionfeedback">Quality Control Review Completed By</label>
                                    <input type="text" name="QualityAssurance__by" disabled>

                                </div>
                            </div>
                            <div class="col-lg-6 new-date-data-field quality_control">
                                <div class="group-input input-date">
                                    <label for="Quality Control Review Completed On">Quality Control Review Completed
                                        On</label>
                                    <div class="calenderauditee">
                                        <input type="text" id="Quality_Control_on" readonly
                                            placeholder="DD-MM-YYYY" />
                                        <input type="date" name="Quality_Control_on"
                                            min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input"
                                            oninput="handleDateInput(this, 'Quality_Control_on')" />
                                    </div>
                                </div>
                            </div>

                            <script>
                                $(document).ready(function() {
                                    $('.quality_assurance').hide();

                                    $('[name="Quality_Assurance"]').change(function() {
                                        if ($(this).val() === 'yes') {
                                            $('.quality_assurance').show();
                                            $('.quality_assurance span').show();
                                        } else {
                                            $('.quality_assurance').hide();
                                            $('.quality_assurance span').hide();
                                        }
                                    });
                                });
                            </script>
                            <div class="sub-head">
                                Quality Assurance
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Customer notification">Quality Assurance Review Required ?</label>
                                    <select name="Quality_Assurance" id="QualityAssurance_review" disabled>
                                        <option value="0">-- Select --</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                        <option value="na">NA</option>

                                    </select>

                                </div>
                            </div>
                            @php
                                $division = DB::table('q_m_s_divisions')
                                    ->where('name', Helpers::getDivisionName(session()->get('division')))
                                    ->first();
                                $userRoles = DB::table('user_roles')
                                    ->where(['q_m_s_roles_id' => 25, 'q_m_s_divisions_id' => $division->id])
                                    ->get();
                                $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                            @endphp
                            <div class="col-lg-6 quality_assurance">
                                <div class="group-input">
                                    <label for="Quality Assurance Person">Quality Assurance Person</label>
                                    <select name="QualityAssurance_person" id="QualityAssurance_person">
                                        <option value="0">-- Select --</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>
                            <div class="col-md-12 mb-3 quality_assurance">
                                <div class="group-input">
                                    <label for="Impact Assessment3">Impact Assessment (By Quality Assurance)</label>
                                    <div><small class="text-primary">Please insert "NA" in the data field if it does
                                            not require completion</small></div>
                                    <textarea class="" name="QualityAssurance_assessment" id="summernote-23">
                                </textarea>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3 quality_assurance">
                                <div class="group-input">
                                    <label for="Quality Assurance Feedback">Quality Assurance Feedback</label>
                                    <div><small class="text-primary">Please insert "NA" in the data field if it does
                                            not require completion</small></div>
                                    <textarea class="" name="QualityAssurance_feedback" id="summernote-24">
                                </textarea>
                                </div>
                            </div>
                            <div class="col-lg-12 quality_assurance">
                                <div class="group-input">
                                    <label for="Quality Assurance Attachments">Quality Assurance Attachments</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id="Quality_Assurance_attachment"></div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile"
                                                name="Quality_Assurance_attachment[]"
                                                oninput="addMultipleFiles(this, 'Quality_Assurance_attachment')"
                                                multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3 quality_assurance">
                                <div class="group-input">
                                    <label for="Quality Assurance Review Completed By">Quality Assurance Review
                                        Completed By</label>
                                    <input type="text" name="QualityAssurance_by" disabled>

                                </div>
                            </div>
                            <div class="col-lg-6 new-date-data-field quality_assurance">
                                <div class="group-input input-date">
                                    <label for="Quality Assurance Review Completed On">Quality Assurance Review
                                        Completed On</label>
                                    <div class="calenderauditee">
                                        <input type="text" id="QualityAssurance_on" readonly
                                            placeholder="DD-MM-YYYY" />
                                        <input type="date" name="QualityAssurance_on"
                                            min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input"
                                            oninput="handleDateInput(this, 'QualityAssurance_on')" />
                                    </div>
                                </div>
                            </div>

                            <script>
                                $(document).ready(function() {
                                    $('.engineering').hide();

                                    $('[name="Engineering_review"]').change(function() {
                                        if ($(this).val() === 'yes') {
                                            $('.engineering').show();
                                            $('.engineering span').show();
                                        } else {
                                            $('.engineering').hide();
                                            $('.engineering span').hide();
                                        }
                                    });
                                });
                            </script>
                            <div class="sub-head">
                                Engineering
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Engineering Review Required">Engineering Review Required ?</label>
                                    <select name="Engineering_review" id="Engineering_review" disabled>
                                        <option value="0">-- Select --</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                        <option value="na">NA</option>
                                    </select>

                                </div>
                            </div>
                            @php
                                $division = DB::table('q_m_s_divisions')
                                    ->where('name', Helpers::getDivisionName(session()->get('division')))
                                    ->first();
                                $userRoles = DB::table('user_roles')
                                    ->where(['q_m_s_roles_id' => 26, 'q_m_s_divisions_id' => $division->id])
                                    ->get();
                                $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                            @endphp
                            <div class="col-lg-6 engineering">
                                <div class="group-input">
                                    <label for="Engineering Person">Engineering Person</label>
                                    <select name="Engineering_person" id="Engineering_person">
                                        <option value="0">-- Select --</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>
                            <div class="col-md-12 mb-3 engineering">
                                <div class="group-input">
                                    <label for="Impact Assessment4">Impact Assessment (By Engineering)</label>
                                    <div><small class="text-primary">Please insert "NA" in the data field if it does
                                            not require completion</small></div>
                                    <textarea class="" name="Engineering_assessment" id="summernote-25">
                                </textarea>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3 engineering">
                                <div class="group-input">
                                    <label for="productionfeedback">Engineering Feedback</label>
                                    <div><small class="text-primary">Please insert "NA" in the data field if it does
                                            not require completion</small></div>
                                    <textarea class="" name="Engineering_feedback" id="summernote-26">
                                </textarea>
                                </div>
                            </div>
                            <div class="col-lg-12 engineering">
                                <div class="group-input">
                                    <label for="Audit Attachments">Engineering Attachments</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id="Engineering_attachment"></div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile" name="Engineering_attachment[]"
                                                oninput="addMultipleFiles(this, 'Engineering_attachment')" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3 engineering">
                                <div class="group-input">
                                    <label for="Engineering Review Completed By">Engineering Review Completed
                                        By</label>
                                    <input type="text" name="Engineering_by" id="Engineering_by" disabled>

                                </div>
                            </div>
                            <div class="col-lg-6 new-date-data-field engineering">
                                <div class="group-input input-date">
                                    <label for="Engineering Review Completed On">Engineering Review Completed
                                        On</label>
                                    <div class="calenderauditee">
                                        <input type="text" id="Engineering_on" readonly
                                            placeholder="DD-MM-YYYY" />
                                        <input type="date" name="Engineering_on"
                                            min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input"
                                            oninput="handleDateInput(this, 'Engineering_on')" />
                                    </div>
                                </div>
                            </div>
                            <script>
                                $(document).ready(function() {
                                    $('.analytical_development').hide();

                                    $('[name="Analytical_Development_review"]').change(function() {
                                        if ($(this).val() === 'yes') {
                                            $('.analytical_development').show();
                                            $('.analytical_development span').show();
                                        } else {
                                            $('.analytical_development').hide();
                                            $('.analytical_development span').hide();
                                        }
                                    });
                                });
                            </script>
                            <div class="sub-head">
                                Analytical Development Laboratory
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Analytical Development Laboratory Review Required">Analytical
                                        Development Laboratory Review Required ?</label>
                                    <select name="Analytical_Development_review" id="Analytical_Development_review" disabled>
                                        <option value="0">-- Select --</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                        <option value="na">NA</option>

                                    </select>

                                </div>
                            </div>
                            @php
                                $division = DB::table('q_m_s_divisions')
                                    ->where('name', Helpers::getDivisionName(session()->get('division')))
                                    ->first();
                                $userRoles = DB::table('user_roles')
                                    ->where(['q_m_s_roles_id' => 27, 'q_m_s_divisions_id' => $division->id])
                                    ->get();
                                $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                            @endphp
                            <div class="col-lg-6 analytical_development">
                                <div class="group-input">
                                    <label for="Analytical Development Laboratory Person">Analytical Development
                                        Laboratory Person</label>
                                    <select name="Analytical_Development_person" id="Analytical_Development_person">
                                        <option value="0">-- Select --</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3 analytical_development">
                                <div class="group-input">
                                    <label for="Impact Assessment5">Impact Assessment (By Analytical Development
                                        Laboratory)</label>
                                    <textarea class="" name="Analytical_Development_assessment" id="summernote-27">
                                </textarea>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3 analytical_development">
                                <div class="group-input">
                                    <label for="Analytical Development Laboratory Feedback"> Analytical Development
                                        Laboratory Feedback</label>
                                    <textarea class="" name="Analytical_Development_feedback" id="summernote-28">
                                </textarea>
                                </div>
                            </div>
                            <div class="col-lg-12 analytical_development">
                                <div class="group-input">
                                    <label for="Audit Attachments"> Analytical Development Laboratory
                                        Attachments</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id="Analytical_Development_attachment">
                                        </div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile"
                                                name="Analytical_Development_attachment[]"
                                                oninput="addMultipleFiles(this, 'Analytical_Development_attachment')"
                                                multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3 analytical_development">
                                <div class="group-input">
                                    <label for="Analytical Development Laboratory Review Completed By">Analytical
                                        Development Laboratory Review Completed By</label>
                                    <input type="text" name="Analytical_Development_by"
                                        id="Analytical_Development_by" disabled>

                                </div>
                            </div>
                            {{-- <div class="col-md-6 mb-3">
                                <div class="group-input">
                                    <label for="Analytical Development Laboratory Review Completed On">Analytical Development Laboratory Review Completed On</label>
                                    <input type="date" name="Analytical_Development_on" disabled>

                                </div>
                            </div> --}}
                            <div class="col-lg-6 new-date-data-field analytical_development">
                                <div class="group-input input-date">
                                    <label for="Analytical Development Laboratory Review Completed On">Analytical
                                        Development Laboratory Review Completed On</label>
                                    <div class="calenderauditee">
                                        <input type="text" id="Analytical_Development_on" readonly
                                            placeholder="DD-MM-YYYY" />
                                        <input type="date" name="Analytical_Development_on"
                                            min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input"
                                            oninput="handleDateInput(this, 'Analytical_Development_on')" />
                                    </div>
                                </div>
                            </div>
                            <script>
                                $(document).ready(function() {
                                    $('.kilo_lab').hide();

                                    $('[name="Kilo_Lab_review"]').change(function() {
                                        if ($(this).val() === 'yes') {
                                            $('.kilo_lab').show();
                                            $('.kilo_lab span').show();
                                        } else {
                                            $('.kilo_lab').hide();
                                            $('.kilo_lab span').hide();
                                        }
                                    });
                                });
                            </script>
                            <div class="sub-head">
                                Process Development Laboratory / Kilo Lab
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Process Development Laboratory"> Process Development Laboratory / Kilo
                                        Lab Review Required ?</label>
                                    <select name="Kilo_Lab_review" id="Kilo_Lab_review" disabled>
                                        <option value="0">-- Select --</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                        <option value="na">NA</option>

                                    </select>

                                </div>
                            </div>
                            @php
                                $division = DB::table('q_m_s_divisions')
                                    ->where('name', Helpers::getDivisionName(session()->get('division')))
                                    ->first();
                                $userRoles = DB::table('user_roles')
                                    ->where(['q_m_s_roles_id' => 28, 'q_m_s_divisions_id' => $division->id])
                                    ->get();
                                $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                            @endphp
                            <div class="col-lg-6 kilo_lab">
                                <div class="group-input">
                                    <label for="Process Development Laboratory"> Process Development Laboratory / Kilo
                                        Lab Person</label>
                                    <select name="Kilo_Lab_person" id="Kilo_Lab_person">
                                        <option value="0">-- Select --</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach


                                    </select>

                                </div>
                            </div>
                            <div class="col-md-12 mb-3 kilo_lab">
                                <div class="group-input">
                                    <label for="Impact Assessment6">Impact Assessment (By Process Development
                                        Laboratory / Kilo Lab)</label>
                                    <textarea class="" name="Kilo_Lab_assessment" id="summernote-29">
                                </textarea>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3 kilo_lab">
                                <div class="group-input">
                                    <label for="Kilo Lab Feedback"> Process Development Laboratory / Kilo Lab
                                        Feedback</label>
                                    <textarea class="" name="Kilo_Lab_feedback" id="summernote-30">
                                </textarea>
                                </div>
                            </div>
                            <div class="col-lg-12 kilo_lab">
                                <div class="group-input">
                                    <label for="Audit Attachments"> Process Development Laboratory / Kilo Lab
                                        Attachments</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id="Kilo_Lab_attachment"></div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile" name="Kilo_Lab_attachment[]"
                                                oninput="addMultipleFiles(this, 'Kilo_Lab_attachment')" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3 kilo_lab">
                                <div class="group-input">
                                    <label for="Kilo Lab Review Completed By">Process Development Laboratory / Kilo
                                        Lab Review Completed By</label>
                                    <input type="text" name="Kilo_Lab_attachment_by"
                                        id="Kilo_Lab_attachment_by" disabled>

                                </div>
                            </div>
                            <div class="col-lg-6 new-date-data-field kilo_lab">
                                <div class="group-input input-date">
                                    <label for="Kilo Lab Review Completed On">Process Development Laboratory / Kilo
                                        Lab Review Completed On</label>
                                    <div class="calenderauditee">
                                        <input type="text" id="Kilo_Lab_attachment_on" readonly
                                            placeholder="DD-MM-YYYY" />
                                        <input type="date" name="Kilo_Lab_attachment_on"
                                            min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input"
                                            oninput="handleDateInput(this, 'Kilo_Lab_attachment_on')" />
                                    </div>
                                </div>
                            </div>
                            <script>
                                $(document).ready(function() {
                                    $('.technology_transfer').hide();

                                    $('[name="Technology_transfer_review"]').change(function() {
                                        if ($(this).val() === 'yes') {
                                            $('.technology_transfer').show();
                                            $('.technology_transfer span').show();
                                        } else {
                                            $('.technology_transfer').hide();
                                            $('.technology_transfer span').hide();
                                        }
                                    });
                                });
                            </script>
                            <div class="sub-head">
                                Technology Transfer / Design
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Design Review Required">Technology Transfer / Design Review Required
                                        ?</label>
                                    <select name="Technology_transfer_review" id="Technology_transfer_review" disabled>
                                        <option value="0">-- Select --</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                        <option value="na">NA</option>

                                    </select>

                                </div>
                            </div>
                            @php
                                $division = DB::table('q_m_s_divisions')
                                    ->where('name', Helpers::getDivisionName(session()->get('division')))
                                    ->first();
                                $userRoles = DB::table('user_roles')
                                    ->where(['q_m_s_roles_id' => 29, 'q_m_s_divisions_id' => $division->id])
                                    ->get();
                                $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                            @endphp
                            <div class="col-lg-6 technology_transfer">
                                <div class="group-input">
                                    <label for="Design Person"> Technology Transfer / Design Person</label>
                                    <select name="Technology_transfer_person" id="Technology_transfer_person">
                                        <option value="0">-- Select --</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach


                                    </select>

                                </div>
                            </div>
                            <div class="col-md-12 mb-3 technology_transfer">
                                <div class="group-input">
                                    <label for="Impact Assessment7">Impact Assessment (By Technology Transfer /
                                        Design)</label>
                                    <textarea class="" name="Technology_transfer_assessment" id="summernote-31">
                                </textarea>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3 technology_transfer">
                                <div class="group-input">
                                    <label for="Design Feedback"> Technology Transfer / Design Feedback</label>
                                    <textarea class="" name="Technology_transfer_feedback" id="summernote-32">
                                </textarea>
                                </div>
                            </div>
                            <div class="col-lg-12 technology_transfer">
                                <div class="group-input">
                                    <label for="Audit Attachments"> Technology Transfer / Design Attachments</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id="Technology_transfer_attachment"></div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile"
                                                name="Technology_transfer_attachment[]"
                                                oninput="addMultipleFiles(this, 'Technology_transfer_attachment')"
                                                multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3 technology_transfer">
                                <div class="group-input">
                                    <label for="Design Review Completed By">Technology Transfer / Design Review
                                        Completed By</label>
                                    <input type="text" name="Technology_transfer_by" disabled>

                                </div>
                            </div>
                            <div class="col-lg-6 new-date-data-field technology_transfer">
                                <div class="group-input input-date">
                                    <label for="Design Review Completed On">Technology Transfer / Design Review
                                        Completed On</label>
                                    <div class="calenderauditee">
                                        <input type="text" id="Technology_transfer_on" readonly
                                            placeholder="DD-MM-YYYY" />
                                        <input type="date" name="Technology_transfer_on"
                                            min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input"
                                            oninput="handleDateInput(this, 'Technology_transfer_on')" />
                                    </div>
                                </div>
                            </div>
                            <script>
                                $(document).ready(function() {
                                    $('.environmental_health').hide();

                                    $('[name="Environment_Health_review"]').change(function() {
                                        if ($(this).val() === 'yes') {
                                            $('.environmental_health').show();
                                            $('.environmental_health span').show();
                                        } else {
                                            $('.environmental_health').hide();
                                            $('.environmental_health span').hide();
                                        }
                                    });
                                });
                            </script>
                            <div class="sub-head">
                                Environment, Health & Safety
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Safety Review Required">Environment, Health & Safety Review Required
                                        ?</label>
                                    <select name="Environment_Health_review" id="Environment_Health_review" disabled>
                                        <option value="0">-- Select --</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                        <option value="na">NA</option>

                                    </select>

                                </div>
                            </div>
                            @php
                                $division = DB::table('q_m_s_divisions')
                                    ->where('name', Helpers::getDivisionName(session()->get('division')))
                                    ->first();
                                $userRoles = DB::table('user_roles')
                                    ->where(['q_m_s_roles_id' => 30, 'q_m_s_divisions_id' => $division->id])
                                    ->get();
                                $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                            @endphp
                            <div class="col-lg-6 environmental_health">
                                <div class="group-input">
                                    <label for="Safety Person"> Environment, Health & Safety Person</label>
                                    <select name="Environment_Health_Safety_person"
                                        id="Environment_Health_Safety_person">
                                        <option value="0">-- Select --</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>
                            <div class="col-md-12 mb-3 environmental_health">
                                <div class="group-input">
                                    <label for="Impact Assessment8">Impact Assessment (By Environment, Health &
                                        Safety)</label>
                                    <textarea class="" name="Health_Safety_assessment" id="summernote-33">
                                </textarea>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3 environmental_health">
                                <div class="group-input">
                                    <label for="productionfeedback">Environment, Health & Safety Feedback</label>
                                    <textarea class="" name="Health_Safety_feedback" id="summernote-34">
                                </textarea>
                                </div>
                            </div>
                            <div class="col-lg-12 environmental_health">
                                <div class="group-input">
                                    <label for="Audit Attachments"> Environment, Health & Safety Attachments</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id="Environment_Health_Safety_attachment">
                                        </div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile"
                                                name="Environment_Health_Safety_attachment[]"
                                                oninput="addMultipleFiles(this, 'Environment_Health_Safety_attachment')"
                                                multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3 environmental_health">
                                <div class="group-input">
                                    <label for="productionfeedback">Environment, Health & Safety Review Completed
                                        By</label>
                                    <input type="text" name="Environment_Health_Safety_by"
                                        id="Environment_Health_Safety_by" disabled>

                                </div>
                            </div>
                            <div class="col-lg-6 new-date-data-field environmental_health">
                                <div class="group-input input-date">
                                    <label for="Safety Review Completed On">Environment, Health & Safety Review
                                        Completed On</label>
                                    <div class="calenderauditee">
                                        <input type="text" id="Environment_Health_Safety_on" readonly
                                            placeholder="DD-MM-YYYY" />
                                        <input type="date" name="Environment_Health_Safety_on"
                                            min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input"
                                            oninput="handleDateInput(this, 'Environment_Health_Safety_on')" />
                                    </div>
                                </div>
                            </div>

                            <script>
                                $(document).ready(function() {
                                    $('.human_resources').hide();

                                    $('[name="Human_Resource_review"]').change(function() {
                                        if ($(this).val() === 'yes') {
                                            $('.human_resources').show();
                                            $('.human_resources span').show();
                                        } else {
                                            $('.human_resources').hide();
                                            $('.human_resources span').hide();
                                        }
                                    });
                                });
                            </script>
                            <div class="sub-head">
                                Human Resource & Administration
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Administration Review Required">Human Resource & Administration Review
                                        Required ?</label>
                                    <select name="Human_Resource_review" id="Human_Resource_review" disabled>
                                        <option value="0">-- Select --</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                        <option value="na">NA</option>

                                    </select>

                                </div>
                            </div>
                            @php
                                $division = DB::table('q_m_s_divisions')
                                    ->where('name', Helpers::getDivisionName(session()->get('division')))
                                    ->first();
                                $userRoles = DB::table('user_roles')
                                    ->where(['q_m_s_roles_id' => 31, 'q_m_s_divisions_id' => $division->id])
                                    ->get();
                                $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                            @endphp
                            <div class="col-lg-6 human_resources" >
                                <div class="group-input">
                                    <label for="Administration Person"> Human Resource & Administration Person</label>
                                    <select name="Human_Resource_person" id="Human_Resource_person">
                                        <option value="0">-- Select --</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach


                                    </select>

                                </div>
                            </div>
                            <div class="col-md-12 mb-3 human_resources">
                                <div class="group-input">
                                    <label for="Impact Assessment9">Impact Assessment (By Human Resource &
                                        Administration )</label>
                                    <textarea class="" name="Human_Resource_assessment" id="summernote-35">
                                </textarea>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3 human_resources">
                                <div class="group-input">
                                    <label for="productionfeedback">Human Resource & Administration Feedback</label>
                                    <textarea class="" name="Human_Resource_feedback" id="summernote-36">
                                </textarea>
                                </div>
                            </div>
                            <div class="col-lg-12 human_resources">
                                <div class="group-input">
                                    <label for="Audit Attachments"> Human Resource & Administration
                                        Attachments</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id="Human_Resource_attachment"></div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile"
                                                name="Human_Resource_attachment[]"
                                                oninput="addMultipleFiles(this, 'Human_Resource_attachment')"
                                                multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3 human_resources">
                                <div class="group-input">
                                    <label for="Administration Review Completed By"> Human Resource & Administration
                                        Review Completed By</label>
                                    <input type="text" name="Human_Resource_by" id="Human_Resource_by"
                                        disabled>

                                </div>
                            </div>
                            <div class="col-lg-6 new-date-data-field human_resources">
                                <div class="group-input input-date">
                                    <label for="Administration Review Completed On">Human Resource & Administration
                                        Review Completed On</label>
                                    <div class="calenderauditee">
                                        <input type="text" id="Human_Resource_on" readonly
                                            placeholder="DD-MM-YYYY" />
                                        <input type="date" name="Human_Resource_on"
                                            min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input"
                                            oninput="handleDateInput(this, 'Human_Resource_on')" />
                                    </div>
                                </div>
                            </div>
                            <script>
                                $(document).ready(function() {
                                    $('.information_technology').hide();

                                    $('[name="Information_Technology_review"]').change(function() {
                                        if ($(this).val() === 'yes') {
                                            $('.information_technology').show();
                                            $('.information_technology span').show();
                                        } else {
                                            $('.information_technology').hide();
                                            $('.information_technology span').hide();
                                        }
                                    });
                                });
                            </script>
                            <div class="sub-head">
                                Information Technology
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Information Technology Review Required"> Information Technology Review
                                        Required ?</label>
                                    <select name=" Information_Technology_review"
                                        id=" Information_Technology_review" disabled>
                                        <option value="0">-- Select --</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                        <option value="na">NA</option>

                                    </select>

                                </div>
                            </div>
                            @php
                                $division = DB::table('q_m_s_divisions')
                                    ->where('name', Helpers::getDivisionName(session()->get('division')))
                                    ->first();
                                $userRoles = DB::table('user_roles')
                                    ->where(['q_m_s_roles_id' => 32, 'q_m_s_divisions_id' => $division->id])
                                    ->get();
                                $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                            @endphp
                            <div class="col-lg-6 information_technology">
                                <div class="group-input">
                                    <label for="Information Technology Person"> Information Technology Person</label>
                                    <select name=" Information_Technology_person"
                                        id=" Information_Technology_person">
                                        <option value="0">-- Select --</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>
                            <div class="col-md-12 mb-3 information_technology">
                                <div class="group-input">
                                    <label for="Impact Assessment10">Impact Assessment (By Information
                                        Technology)</label>
                                    <textarea class="" name="Information_Technology_assessment" id="summernote-37">
                                </textarea>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3 information_technology">
                                <div class="group-input">
                                    <label for="Information Technology Feedback"> Information Technology
                                        Feedback</label>
                                    <textarea class="" name="Information_Technology_feedback" id="summernote-38">
                                </textarea>
                                </div>
                            </div>
                            <div class="col-lg-12 information_technology">
                                <div class="group-input">
                                    <label for="Audit Attachments"> Information Technology Attachments</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id="Information_Technology_attachment">
                                        </div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile"
                                                name="Information_Technology_attachment[]"
                                                oninput="addMultipleFiles(this, 'Information_Technology_attachment')"
                                                multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3 information_technology">
                                <div class="group-input">
                                    <label for="Information Technology Review Completed By"> Information Technology
                                        Review Completed By</label>
                                    <input type="text" name="Information_Technology_by" disabled>

                                </div>
                            </div>
                            <div class="col-lg-6 new-date-data-field information_technology">
                                <div class="group-input input-date">
                                    <label for="Information Technology Review Completed On">Information Technology
                                        Review Completed On</label>
                                    <div class="calenderauditee">
                                        <input type="text" id="Information_Technology_on" readonly
                                            placeholder="DD-MM-YYYY" />
                                        <input type="date" name="Information_Technology_on"
                                            min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input"
                                            oninput="handleDateInput(this, 'Information_Technology_on')" />
                                    </div>
                                </div>
                            </div>

                            <script>
                                $(document).ready(function() {
                                    $('.project_management').hide();

                                    $('[name="Project_management_review"]').change(function() {
                                        if ($(this).val() === 'yes') {
                                            $('.project_management').show();
                                            $('.project_management span').show();
                                        } else {
                                            $('.project_management').hide();
                                            $('.project_management span').hide();
                                        }
                                    });
                                });
                            </script>
                            <div class="sub-head">
                                Project Management
                            </div>
                            <div class="col-lg-6 project_management">
                                <div class="group-input">
                                    <label for="Project management Review Required"> Project management Review
                                        Required ?</label>
                                    <select name="Project_management_review" id="Project_management_review" disabled>
                                        <option value="0">-- Select --</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                        <option value="na">NA</option>

                                    </select>

                                </div>
                            </div>
                            @php
                                $division = DB::table('q_m_s_divisions')
                                    ->where('name', Helpers::getDivisionName(session()->get('division')))
                                    ->first();
                                $userRoles = DB::table('user_roles')
                                    ->where(['q_m_s_roles_id' => 33, 'q_m_s_divisions_id' => $division->id])
                                    ->get();
                                $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                            @endphp
                            <div class="col-lg-6 project_management">
                                <div class="group-input">
                                    <label for="Project management Person"> Project management Person</label>
                                    <select name="Project_management_person" id="Project_management_person">
                                        <option value="0">-- Select --</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach


                                    </select>

                                </div>
                            </div>
                            <div class="col-md-12 mb-3 project_management">
                                <div class="group-input">
                                    <label for="Impact Assessment11">Impact Assessment (By Project management
                                        )</label>
                                    <textarea class="" name="Project_management_assessment" id="summernote-39">
                                </textarea>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3 project_management">
                                <div class="group-input">
                                    <label for="Project management Feedback"> Project management Feedback</label>
                                    <textarea class="" name="Project_management_feedback" id="summernote-40">
                                </textarea>
                                </div>
                            </div>
                            <div class="col-lg-12 project_management">
                                <div class="group-input">
                                    <label for="Audit Attachments"> Project management Attachments</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id="Project_management_attachment"></div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile"
                                                name="Project_management_attachment[]"
                                                oninput="addMultipleFiles(this, 'Project_management_attachment')"
                                                multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3 project_management">
                                <div class="group-input">
                                    <label for="Project management Review Completed By"> Project management Review
                                        Completed By</label>
                                    <input type="text" name="Project_management_by"id="Project_management_by"
                                        disabled>

                                </div>
                            </div>
                            <div class="col-lg-6 new-date-data-field project_management">
                                <div class="group-input input-date">
                                    <label for="Project management Review Completed On">Information Technology Review
                                        Completed On</label>
                                    <div class="calenderauditee">
                                        <input type="text" id="Project_management_on" readonly
                                            placeholder="DD-MM-YYYY" />
                                        <input type="date" name="Project_management_on"
                                            min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input"
                                            oninput="handleDateInput(this, 'Project_management_on')" />
                                    </div>
                                </div>
                            </div>
                            <script>
                                $(document).ready(function() {
                                    $('.other1_reviews').hide();

                                    $('[name="Other1_review"]').change(function() {
                                        if ($(this).val() === 'yes') {
                                            $('.other1_reviews').show();
                                            $('.other1_reviews span').show();
                                        } else {
                                            $('.other1_reviews').hide();
                                            $('.other1_reviews span').hide();
                                        }
                                    });
                                });
                            </script>
                            <div class="sub-head">
                                Other's 1 ( Additional Person Review From Departments If Required)
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Customer notification"> Other's 1 Review Required ?</label>
                                    <select name="Other1_review" id="Other1_review" disabled>
                                        <option value="0">-- Select --</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                        <option value="na">NA</option>

                                    </select>

                                </div>
                            </div>
                            @php
                                $division = DB::table('q_m_s_divisions')
                                    ->where('name', Helpers::getDivisionName(session()->get('division')))
                                    ->first();
                                $userRoles = DB::table('user_roles')
                                    ->where(['q_m_s_roles_id' => 34, 'q_m_s_divisions_id' => $division->id])
                                    ->get();
                                $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                            @endphp
                            <div class="col-lg-6 other1_reviews">
                                <div class="group-input">
                                    <label for="Customer notification"> Other's 1 Person</label>
                                    <select name="Other1_person" id="Other1_person">
                                        <option value="0">-- Select --</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach

                                    </select>

                                </div>
                            </div>
                            <div class="col-lg-12 other1_reviews">
                                <div class="group-input">
                                    <label for="Customer notification"> Other's 1 Department</label>
                                    <select name="Other1_Department_person" id="Other1_Department_person">
                                        <option value="0">-- Select --</option>
                                        <option value="Production">Production</option>
                                        <option value="Warehouse">Warehouse</option>
                                        <option value="Quality_Control">Quality Control</option>
                                        <option value="Quality_Assurance">Quality Assurance</option>
                                        <option value="Engineering">Engineering</option>
                                        <option value="Analytical_Development_Laboratory">Analytical Development
                                            Laboratory</option>
                                        <option value="Process_Development_Lab">Process Development Laboratory / Kilo
                                            Lab</option>
                                        <option value="Technology transfer/Design">Technology Transfer/Design</option>
                                        <option value="Environment, Health & Safety">Environment, Health & Safety
                                        </option>
                                        <option value="Human Resource & Administration">Human Resource &
                                            Administration</option>
                                        <option value="Information Technology">Information Technology</option>
                                        <option value="Project management">Project management</option> 



                                    </select>

                                </div>
                            </div>
                            <div class="col-md-12 mb-3 other1_reviews">
                                <div class="group-input">
                                    <label for="productionfeedback">Impact Assessment (By Other's 1)</label>
                                    <textarea class="" name="Other1_assessment" id="summernote-41">
                                    </textarea>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3 other1_reviews">
                                <div class="group-input">
                                    <label for="productionfeedback"> Other's 1 Feedback</label>
                                    <textarea class="" name="Other1_feedback" id="summernote-42">
                                    </textarea>
                                </div>
                            </div>
                            <div class="col-lg-12 other1_reviews">
                                <div class="group-input">
                                    <label for="Audit Attachments"> Other's 1 Attachments</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id="Other1_attachment"></div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile" name="Other1_attachment[]"
                                                oninput="addMultipleFiles(this, 'Other1_attachment')" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3 other1_reviews">
                                <div class="group-input">
                                    <label for="productionfeedback"> Other's 1 Review Completed By</label>
                                    <input type="text" name="Other1_by" id="Other1_by" disabled>

                                </div>
                            </div>
                            <div class="col-lg-6 new-date-data-field other1_reviews">
                                <div class="group-input input-date">
                                    <label for="Review Completed On1">Other's 1 Review Completed On</label>
                                    <div class="calenderauditee">
                                        <input type="text" id="Other1_on" name="Other1_on" readonly
                                            placeholder="DD-MM-YYYY" />
                                    </div>
                                </div>
                            </div>
                            <script>
                                $(document).ready(function() {
                                    $('.Other2_reviews').hide();

                                    $('[name="Other2_review"]').change(function() {
                                        if ($(this).val() === 'yes') {
                                            $('.Other2_reviews').show();
                                            $('.Other2_reviews span').show();
                                        } else {
                                            $('.Other2_reviews').hide();
                                            $('.Other2_reviews span').hide();
                                        }
                                    });
                                });
                            </script>
                            <div class="sub-head">
                                Other's 2 ( Additional Person Review From Departments If Required)
                            </div>
                            <div class="col-lg-6 ">
                                <div class="group-input">
                                    <label for="Customer notification"> Other's 2 Review Required ?</label>
                                    <select name="Other2_review" id="Other2_review" disabled>
                                        <option value="0">-- Select --</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                        <option value="na">NA</option>

                                    </select>

                                </div>
                            </div>
                            @php
                                $division = DB::table('q_m_s_divisions')
                                    ->where('name', Helpers::getDivisionName(session()->get('division')))
                                    ->first();
                                $userRoles = DB::table('user_roles')
                                    ->where(['q_m_s_roles_id' => 35, 'q_m_s_divisions_id' => $division->id])
                                    ->get();
                                $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                            @endphp
                            <div class="col-lg-6 Other2_reviews">
                                <div class="group-input">
                                    <label for="Customer notification"> Other's 2 Person</label>
                                    <select name="Other2_person" id="Other2_person">
                                        <option value="0">-- Select --</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach

                                    </select>

                                </div>
                            </div>
                            <div class="col-lg-12 Other2_reviews">
                                <div class="group-input">
                                    <label for="Customer notification"> Other's 2 Department</label>
                                    <select name="Other2_Department_person" id="Other2_Department_person">
                                        <option value="0">-- Select --</option>
                                        <option value="Production">Production</option>
                                        <option value="Warehouse">Warehouse</option>
                                        <option value="Quality_Control">Quality Control</option>
                                        <option value="Quality_Assurance">Quality Assurance</option>
                                        <option value="Engineering">Engineering</option>
                                        <option value="Analytical_Development_Laboratory">Analytical Development
                                            Laboratory</option>
                                        <option value="Process_Development_Lab">Process Development Laboratory / Kilo
                                            Lab</option>
                                        <option value="Technology transfer/Design">Technology Transfer/Design</option>
                                        <option value="Environment, Health & Safety">Environment, Health & Safety
                                        </option>
                                        <option value="Human Resource & Administration">Human Resource &
                                            Administration</option>
                                        <option value="Information Technology">Information Technology</option>
                                        <option value="Project management">Project management</option>



                                    </select>

                                </div>
                            </div>
                            <div class="col-md-12 mb-3 Other2_reviews">
                                <div class="group-input">
                                    <label for="Impact Assessment13">Impact Assessment (By Other's 2)</label>
                                    <textarea class="" name="Other2_Assessment" id="summernote-43">
                                    </textarea>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3 Other2_reviews">
                                <div class="group-input">
                                    <label for="Feedback2"> Other's 2 Feedback</label>
                                    <textarea class="" name="Other2_feedback" id="summernote-44">
                                    </textarea>
                                </div>
                            </div>
                            <div class="col-lg-12 Other2_reviews">
                                <div class="group-input">
                                    <label for="Audit Attachments"> Other's 2 Attachments</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id="Other2_attachment"></div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile" name="Other2_attachment[]"
                                                oninput="addMultipleFiles(this, 'Other2_attachment')" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3 Other2_reviews">
                                <div class="group-input">
                                    <label for="Review Completed By2"> Other's 2 Review Completed By</label>
                                    <input type="text" name="Other2_by" disabled>

                                </div>
                            </div>
                            <div class="col-lg-6 new-date-data-field Other2_reviews">
                                <div class="group-input input-date">
                                    <label for="Review Completed On2">Other's 2 Review Completed On</label>
                                    <div class="calenderauditee">
                                        <input type="text" id="Other2_on" name="Other2_on" readonly
                                            placeholder="DD-MM-YYYY" />
                                        {{-- <input type="date"  name="Other2_on" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input"
                                                oninput="handleDateInput(this, 'Other2_on')" /> --}}
                                    </div>
                                </div>
                            </div>
                            <script>
                                $(document).ready(function() {
                                    $('.Other3_reviews').hide();

                                    $('[name="Other3_review"]').change(function() {
                                        if ($(this).val() === 'yes') {
                                            $('.Other3_reviews').show();
                                            $('.Other3_reviews span').show();
                                        } else {
                                            $('.Other3_reviews').hide();
                                            $('.Other3_reviews span').hide();
                                        }
                                    });
                                });
                            </script>
                            <div class="sub-head">
                                Other's 3 ( Additional Person Review From Departments If Required)
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Customer notification"> Other's 3 Review Required ?</label>
                                    <select name="Other3_review" id="Other3_review" disabled>
                                        <option value="0">-- Select --</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                        <option value="na">NA</option>

                                    </select>

                                </div>
                            </div>
                            @php
                                $division = DB::table('q_m_s_divisions')
                                    ->where('name', Helpers::getDivisionName(session()->get('division')))
                                    ->first();
                                $userRoles = DB::table('user_roles')
                                    ->where(['q_m_s_roles_id' => 36, 'q_m_s_divisions_id' => $division->id])
                                    ->get();
                                $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                            @endphp
                            <div class="col-lg-6 Other3_reviews">
                                <div class="group-input">
                                    <label for="Customer notification"> Other's 3 Person</label>
                                    <select name="Other3_person" id="Other3_person">
                                        <option value="0">-- Select --</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach

                                    </select>

                                </div>
                            </div>
                            <div class="col-lg-12 Other3_reviews ">
                                <div class="group-input">
                                    <label for="Customer notification"> Other's 3 Department</label>
                                    <select name="Other3_Department_person" id="Other3_Department_person">
                                        <option value="0">-- Select --</option>
                                        <option value="Production">Production</option>
                                        <option value="Warehouse">Warehouse</option>
                                        <option value="Quality_Control">Quality Control</option>
                                        <option value="Quality_Assurance">Quality Assurance</option>
                                        <option value="Engineering">Engineering</option>
                                        <option value="Analytical_Development_Laboratory">Analytical Development
                                            Laboratory</option>
                                        <option value="Process_Development_Lab">Process Development Laboratory / Kilo
                                            Lab</option>
                                        <option value="Technology transfer/Design">Technology Transfer/Design</option>
                                        <option value="Environment, Health & Safety">Environment, Health & Safety
                                        </option>
                                        <option value="Human Resource & Administration">Human Resource &
                                            Administration</option>
                                        <option value="Information Technology">Information Technology</option>
                                        <option value="Project management">Project management</option>



                                    </select>

                                </div>
                            </div>
                            <div class="col-md-12 mb-3 Other3_reviews">
                                <div class="group-input">
                                    <label for="productionfeedback">Impact Assessment (By Other's 3)</label>
                                    <textarea class="" name="Other3_Assessment" id="summernote-45">
                                    </textarea>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3 Other3_reviews">
                                <div class="group-input">
                                    <label for="productionfeedback"> Other's 3 Feedback</label>
                                    <textarea class="" name="Other3_feedback" id="summernote-46">
                                    </textarea>
                                </div>
                            </div>
                            <div class="col-lg-12 Other3_reviews">
                                <div class="group-input">
                                    <label for="Audit Attachments"> Other's 3 Attachments</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id="Other3_attachment"></div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile" name="Other3_attachment[]"
                                                oninput="addMultipleFiles(this, 'Other3_attachment')" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3 Other3_reviews">
                                <div class="group-input">
                                    <label for="productionfeedback"> Other's 3 Review Completed By</label>
                                    <input type="text" name="Other3_by" disabled>

                                </div>
                            </div>
                            <div class="col-lg-6 new-date-data-field Other3_reviews">
                                <div class="group-input input-date">
                                    <label for="Review Completed On3">Other's 3 Review Completed On</label>
                                    <div class="calenderauditee">
                                        <input type="text" id="Other3_on" name="Other3_on" readonly
                                            placeholder="DD-MM-YYYY" />
                                        {{-- <input type="date"  name="Other3_on" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input"
                                                oninput="handleDateInput(this, 'Other3_on')" /> --}}
                                    </div>
                                </div>
                            </div>
                            <script>
                                $(document).ready(function() {
                                    $('.Other4_reviews').hide();

                                    $('[name="Other4_review"]').change(function() {
                                        if ($(this).val() === 'yes') {
                                            $('.Other4_reviews').show();
                                            $('.Other4_reviews span').show();
                                        } else {
                                            $('.Other4_reviews').hide();
                                            $('.Other4_reviews span').hide();
                                        }
                                    });
                                });
                            </script>
                            <div class="sub-head">
                                Other's 4 ( Additional Person Review From Departments If Required)
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="review4"> Other's 4 Review Required ?</label>
                                    <select name="Other4_review" id="Other4_review" disabled>
                                        <option value="0">-- Select --</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                        <option value="na">NA</option>

                                    </select>

                                </div>
                            </div>
                            @php
                                $division = DB::table('q_m_s_divisions')
                                    ->where('name', Helpers::getDivisionName(session()->get('division')))
                                    ->first();
                                $userRoles = DB::table('user_roles')
                                    ->where(['q_m_s_roles_id' => 37, 'q_m_s_divisions_id' => $division->id])
                                    ->get();
                                $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                            @endphp
                            <div class="col-lg-6 Other4_reviews">
                                <div class="group-input">
                                    <label for="Person4"> Other's 4 Person</label>
                                    <select name="Other4_person" id="Other4_person">
                                        <option value="0">-- Select --</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach

                                    </select>

                                </div>
                            </div>
                            <div class="col-lg-12 Other4_reviews">
                                <div class="group-input">
                                    <label for="Department4"> Other's 4 Department</label>
                                    <select name="Other4_Department_person" id="Other4_Department_person">
                                        <option value="0">-- Select --</option>
                                        <option value="Production">Production</option>
                                        <option value="Warehouse">Warehouse</option>
                                        <option value="Quality_Control">Quality Control</option>
                                        <option value="Quality_Assurance">Quality Assurance</option>
                                        <option value="Engineering">Engineering</option>
                                        <option value="Analytical_Development_Laboratory">Analytical Development
                                            Laboratory</option>
                                        <option value="Process_Development_Lab">Process Development Laboratory / Kilo
                                            Lab</option>
                                        <option value="Technology transfer/Design">Technology Transfer/Design</option>
                                        <option value="Environment, Health & Safety">Environment, Health & Safety
                                        </option>
                                        <option value="Human Resource & Administration">Human Resource &
                                            Administration</option>
                                        <option value="Information Technology">Information Technology</option>
                                        <option value="Project management">Project management</option>



                                    </select>

                                </div>
                            </div>
                            <div class="col-md-12 mb-3 Other4_reviews">
                                <div class="group-input">
                                    <label for="Impact Assessment15">Impact Assessment (By Other's 4)</label>
                                    <textarea class="" name="Other4_Assessment" id="summernote-47">
                                    </textarea>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3 Other4_reviews">
                                <div class="group-input">
                                    <label for="feedback4"> Other's 4 Feedback</label>
                                    <textarea class="" name="Other4_feedback" id="summernote-48">
                                    </textarea>
                                </div>
                            </div>
                            <div class="col-lg-12 Other4_reviews">
                                <div class="group-input">
                                    <label for="Audit Attachments"> Other's 4 Attachments</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id="Other4_attachment"></div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile" name="Other4_attachment[]"
                                                oninput="addMultipleFiles(this, 'Other4_attachment')" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3 Other4_reviews">
                                <div class="group-input">
                                    <label for="Review Completed By4"> Other's 4 Review Completed By</label>
                                    <input type="text" name="Other4_by" disabled>

                                </div>
                            </div>
                            <div class="col-lg-6 new-date-data-field Other4_reviews">
                                <div class="group-input input-date">
                                    <label for="Review Completed On4">Other's 4 Review Completed On</label>
                                    <div class="calenderauditee">
                                        <input type="text" id="Other4_on" name="Other4_on" readonly
                                            placeholder="DD-MM-YYYY" />
                                        {{-- <input type="date"  name="Other4_on" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input"
                                                oninput="handleDateInput(this, 'Other4_on')" /> --}}
                                    </div>
                                </div>
                            </div>

                            <script>
                                $(document).ready(function() {
                                    $('.Other5_reviews').hide();

                                    $('[name="Other5_review"]').change(function() {
                                        if ($(this).val() === 'yes') {
                                            $('.Other5_reviews').show();
                                            $('.Other5_reviews span').show();
                                        } else {
                                            $('.Other5_reviews').hide();
                                            $('.Other5_reviews span').hide();
                                        }
                                    });
                                });
                            </script>
                            <div class="sub-head">
                                Other's 5 ( Additional Person Review From Departments If Required)
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="review5"> Other's 5 Review Required ?</label>
                                    <select name="Other5_review" id="Other5_review" disabled>
                                        <option value="0">-- Select --</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                        <option value="na">NA</option>

                                    </select>

                                </div>
                            </div>
                            @php
                                $division = DB::table('q_m_s_divisions')
                                    ->where('name', Helpers::getDivisionName(session()->get('division')))
                                    ->first();
                                $userRoles = DB::table('user_roles')
                                    ->where(['q_m_s_roles_id' => 38, 'q_m_s_divisions_id' => $division->id])
                                    ->get();
                                $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                            @endphp
                            <div class="col-lg-6 Other5_reviews">
                                <div class="group-input">
                                    <label for="Person5">Other's 5 Person</label>
                                    <select name="Other5_person" id="Other5_person">
                                        <option value="0">-- Select --</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach

                                    </select>

                                </div>
                            </div>
                            <div class="col-lg-12 Other5_reviews">
                                <div class="group-input">
                                    <label for="Department5"> Other's 5 Department</label>
                                    <select name="Other5_Department_person" id="Other5_Department_person">
                                        <option value="0">-- Select --</option>
                                        <option value="Production">Production</option>
                                        <option value="Warehouse">Warehouse</option>
                                        <option value="Quality_Control">Quality Control</option>
                                        <option value="Quality_Assurance">Quality Assurance</option>
                                        <option value="Engineering">Engineering</option>
                                        <option value="Analytical_Development_Laboratory">Analytical Development
                                            Laboratory</option>
                                        <option value="Process_Development_Lab">Process Development Laboratory / Kilo
                                            Lab</option>
                                        <option value="Technology transfer/Design">Technology Transfer/Design</option>
                                        <option value="Environment, Health & Safety">Environment, Health & Safety
                                        </option>
                                        <option value="Human Resource & Administration">Human Resource &
                                            Administration</option>
                                        <option value="Information Technology">Information Technology</option>
                                        <option value="Project management">Project management</option>



                                    </select>

                                </div>
                            </div>
                            <div class="col-md-12 mb-3 Other5_reviews">
                                <div class="group-input">
                                    <label for="productionfeedback">Impact Assessment (By Other's 5)</label>
                                    <textarea class="" name="Other5_Assessment" id="summernote-49">
                                    </textarea>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3 Other5_reviews">
                                <div class="group-input">
                                    <label for="productionfeedback"> Other's 5 Feedback</label>
                                    <textarea class="" name="Other5_feedback" id="summernote-50">
                                    </textarea>
                                </div>
                            </div>
                            <div class="col-lg-12 Other5_reviews">
                                <div class="group-input">
                                    <label for="Audit Attachments"> Other's 5 Attachments</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id="Other5_attachment"></div>
                                        <div class="add-btn">
                                            <div>Add</div>
                                            <input type="file" id="myfile" name="Other5_attachment[]"
                                                oninput="addMultipleFiles(this, 'Other5_attachment')" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3 Other5_reviews">
                                <div class="group-input">
                                    <label for="Review Completed By5"> Other's 5 Review Completed By</label>
                                    <input type="text" name="Other5_by" disabled>

                                </div>
                            </div>
                            <div class="col-lg-6 new-date-data-field Other5_reviews">
                                <div class="group-input input-date">
                                    <label for="Review Completed On5">Other's 5 Review Completed On</label>
                                    <div class="calenderauditee">
                                        <input type="text" id="Other5_on" name="Other5_on" readonly
                                            placeholder="DD-MM-YYYY" />
                                        {{-- <input type="date"  name="Other5_on" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input"
                                                oninput="handleDateInput(this, 'Other5_on')" /> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="button-block">
                            <button type="submit" id="ChangesaveButton" style=" justify-content: center; width: 4rem; margin-left: auto;" class="saveButton">Save</button>
                            <a href="/rcms/qms-dashboard" style=" justify-content: center; width: 4rem; margin-left: auto;">
                                <button type="button"  class="backButton">Back</button>
                            </a>
                            <button type="button" style=" justify-content: center; width: 4rem; margin-left: auto;" id="ChangeNextButton" class="nextButton"
                                onclick="nextStep()">Next</button>
                            <button type="button" style=" justify-content: center; width: 4rem; margin-left: auto;"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white">
                                    Exit </a> </button>
                                    {{-- <a style="  justify-content: center; width: 10rem; margin-left: auto;" type="button"
                                    class="button  launch_extension" data-bs-toggle="modal"
                                    data-bs-target="#launch_extension">
                                    Launch Extension
                                </a> --}}
                                {{-- <a type="button" class="button  launch_extension" data-bs-toggle="modal"
                                    data-bs-target="#effectivenss_extension">
                                    Launch Effectiveness Check
                                </a> --}}
                        </div>

                    </div>
                </div>
                   {{-- -----------------------------cft end ------------ --}}
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
