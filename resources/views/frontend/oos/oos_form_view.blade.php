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


    <!-- --------------------------------------button--------------------- -->

    <script>
        VirtualSelect.init({
            ele: '#related_records, #hod'
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

    <!-- -----------------------------grid-1----------------------------script -->
    <script>
        $(document).ready(function() {
            $('#Product_Material').click(function(e) {
                function generateTableRow(serialNumber) {


                    var html =
                        '<tr>' +
                        '<td><input disabled type="text" name="serial[]" value="' + serialNumber +
                        '"></td>' +
                        '<td><input type="text" name="Number[]"></td>' +
                        '<td><input type="text" name="Product/ MaterialName[]"></td>' +
                        '<td><input type="text" name="Remarks[]"></td>' +


                        '</tr>';

                    // for (var i = 0; i < users.length; i++) {
                    //     html += '<option value="' + users[i].id + '">' + users[i].name + '</option>';
                    // }

                    // html += '</select></td>' + 

                    '</tr>';

                    return html;
                }

                var tableBody = $('#Product_Material_details tbody');
                var rowCount = tableBody.children('tr').length;
                var newRow = generateTableRow(rowCount + 1);
                tableBody.append(newRow);
            });
        });
    </script>



    <!-- --------------------------------grid-2--------------------------script -->
    <script>
        $(document).ready(function() {
            $('#details_stability').click(function(e) {
                function generateTableRow(serialNumber) {
                    var currentDate = new Date();
                    var formattedCurrentDate = currentDate.toISOString().split('T')[0].slice(0, 3);
                    var users = @json($users);
                    var html =
                    '<tr>' +
                        '<td><input disabled type="text" name="details_stability[' + serialNumber + '][serial]" value="' + serialNumber +
                        '"></td>' +
                        '<td><input type="text" name="details_stability[' + serialNumber + '][summary_of_previous_oos]" value=""></td>'+
                        '<td><input type="text" name="details_stability[' + serialNumber + '][capa_taken_for_oos]" value=""></td>' +
                        // '<td><button type="text" class="removeRowBtn">Remove</button></td>' +

                    '</tr>';
                    for (var i = 0; i < users.length; i++) {
                        html += '<option value="' + users[i].id + '">' + users[i].name + '</option>';
                    }

                    html += '</select></td>' +

                        '</tr>';

                    return html;
                }

                var tableBody = $('#details_stability_details tbody');
                var rowCount = tableBody.children('tr').length;
                var newRow = generateTableRow(rowCount + 1);
                tableBody.append(newRow);
            });
        });
    </script>

    <!-- ------------------------------grid-3-------------------------script -->
    <script>
        $(document).ready(function() {
            $('#oos_details').click(function(e) {
                function generateTableRow(serialNumber) {
                    var html =
                        '<tr>' +
                            '<td><input disabled type="text" name="oos_detail['+ serialNumber +'][serial]" value="' + serialNumber +
                            '"></td>' +
                            '<td><input type="text" name="oos_detail['+ serialNumber +'][oos_arnumber]"></td>'+
                            '<td><input type="text" name="oos_detail['+ serialNumber +'][oos_test_name]"></td>' +
                            '<td><input type="text" name="oos_detail['+ serialNumber +'][oos_results_obtained]"></td>' +
                            '<td><input type="text" name="oos_detail['+ serialNumber +'][oos_specification_limit]"></td>' +
                            '<td><input type="file" name="oos_detail['+ serialNumber +'][oos_file_attachment]"></td>' +
                            '<td><input type="text" name="oos_detail['+ serialNumber +'][oos_submit_by]"></td>' +
                            '<td>' +
                                '<div class="col-lg-6 new-date-data-field">' +
                                '<div class="group-input input-date">' +
                                '<div class="calenderauditee">' +
                                '<input type="text" readonly id="oos_submit_on' + serialNumber + '" placeholder="DD-MM-YYYY" />' +
                                '<input type="date" name="oos_detail[' + serialNumber + '][oos_submit_on]" value="" class="hide-input" oninput="handleDateInput(this, \'oos_submit_on' + serialNumber + '\')">' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                            '</td>' +

                            '<td><button type="text" class="removeRowBtn">Remove</button></td>'
                         '</tr>';
                    return html;
                }

                var tableBody = $('#oos_details_details tbody');
                var rowCount = tableBody.children('tr').length;
                var newRow = generateTableRow(rowCount + 1);
                tableBody.append(newRow);
            });
        });
    </script>

    <!-- ---------------------------grid-1 ---Preliminary Lab Invst. Review----------------------------- -->

    <script>
        $(document).ready(function() {
            $('#oos_capa').click(function(e) {
                function generateTableRow(serialNumber) {


                    var html =
                        '<tr>' +
                        '<td><input disabled type="text" name="serial[]" value="' + serialNumber +
                        '"></td>' +
                        '<td><input type="text" name="Number[]"></td>' +
                        '<td><input type="text" name="oos_capaName[]"></td>' +
                        '<td><input type="text" name="Remarks[]"></td>' +


                        '</tr>';

                    // for (var i = 0; i < users.length; i++) {
                    //     html += '<option value="' + users[i].id + '">' + users[i].name + '</option>';
                    // }

                    // html += '</select></td>' + 

                    '</tr>';

                    return html;
                }

                var tableBody = $('#oos_capa_details tbody');
                var rowCount = tableBody.children('tr').length;
                var newRow = generateTableRow(rowCount + 1);
                tableBody.append(newRow);
            });
        });
    </script>


    <!-- -----------------------------grid-1----------OOS Conclusion ---------------- -->

    <script>
        $(document).ready(function() {
            $('#oos_conclusion').click(function(e) {
                function generateTableRow(serialNumber) {


                    var html =
                        '<tr>' +
                        '<td><input disabled type="text" name="serial[]" value="' + serialNumber +
                        '"></td>' +
                        '<td><input type="text" name="Number[]"></td>' +
                        '<td><input type="text" name="oos_conclusionName[]"></td>' +
                        '<td><input type="text" name="Remarks[]"></td>' +


                        '</tr>';

                    // for (var i = 0; i < users.length; i++) {
                    //     html += '<option value="' + users[i].id + '">' + users[i].name + '</option>';
                    // }

                    // html += '</select></td>' + 

                    '</tr>';

                    return html;
                }

                var tableBody = $('#oos_conclusion_details tbody');
                var rowCount = tableBody.children('tr').length;
                var newRow = generateTableRow(rowCount + 1);
                tableBody.append(newRow);
            });
        });
    </script>


    
<!-- -----------------------------grid-1----------OOSConclusion_Review ---------------- -->
<script>
        $(document).ready(function() {
            $('#info_material').click(function(e) {
                function generateTableRow(serialNumber) {
                    var currentDate = new Date();
                    var formattedCurrentDate = currentDate.toISOString().split('T')[0].slice(0, 7);


                    var users = @json($users);
                    var html =
                    '<tr>' +
                        '<td><input disabled type="text" name="info_product_material[' + serialNumber + '][serial]" value="' + serialNumber +
                        '"></td>' +
                        // '<td><input type="text" id="info_product_code" name="info_product_material[' + serialNumber + '][info_product_code]" value=""></td>' +
                        '<td><input type="text" name="info_product_material[' + serialNumber + '][info_batch_no]" value=""></td>'+
               
              
                        '<td><input type="text" name="info_product_material[' + serialNumber + '][info_ar_no]" value=""></td>' +
                        '<td><input type="text" name="info_product_material[' + serialNumber + '][info_stage]" value=""></td>' +
                        '<td><input type="text" name="info_product_material[' + serialNumber + '][info_reference_specification_no]" value=""></td>' +
                        '<td><input type="text" name="info_product_material[' + serialNumber + '][info_test]" value=""></td>' +
                        '<td><input type="text" name="info_product_material[' + serialNumber + '][info_results_obtained]" value=""></td>' +
                        '<td><input type="text" name="info_product_material[' + serialNumber + '][info_specification_limit]" value=""></td>' +

                        // '<td><select name="info_product_material[' + serialNumber + '][info_specification_limit]"><option value="">--Select--</option><option value="Primary">Primary</option><option value="Secondary">Secondary</option><option value="Tertiary">Tertiary</option><option value="Not Applicable">Not Applicable</option></select></td>' +
                        // '<td><select name="info_product_material[' + serialNumber + '][info_stability_for]"><option value="">--Select Option--</option><option vlaue="Submission">Submission</option><option vlaue="Commercial">Commercial</option><option vlaue="Pack Evaluation">Pack Evaluation</option><option vlaue="Not Applicable">Not Applicable</option></select></td>' +
                        '<td><button type="text" class="removeRowBtn">Remove</button></td>' +

                    '</tr>';
                    for (var i = 0; i < users.length; i++) {
                        html += '<option value="' + users[i].id + '">' + users[i].name + '</option>';
                    }

                    html += '</select></td>' +

                        '</tr>';

                    return html;
                }

                var tableBody = $('#info_material_details tbody');
                var rowCount = tableBody.children('tr').length;
                var newRow = generateTableRow(rowCount + 1);
                tableBody.append(newRow);
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#oosconclusion_review').click(function(e) {
                function generateTableRow(serialNumber) {


                    var html =
                        '<tr>' +
                        '<td><input disabled type="text" name="serial[]" value="' + serialNumber +
                        '"></td>' +
                        '<td><input type="text" name="Number[]"></td>' +
                        '<td><input type="text" name="oosconclusion_reviewName[]"></td>' +
                        '<td><input type="text" name="Remarks[]"></td>' +


                        '</tr>';

                    // for (var i = 0; i < users.length; i++) {
                    //     html += '<option value="' + users[i].id + '">' + users[i].name + '</option>';
                    // }

                    // html += '</select></td>' + 

                    '</tr>';

                    return html;
                }

                var tableBody = $('#oosconclusion_review_details tbody');
                var rowCount = tableBody.children('tr').length;
                var newRow = generateTableRow(rowCount + 1);
                tableBody.append(newRow);
            });
        });
    </script>





    <div class="form-field-head">
        <!-- <div class="pr-id">
                New Document
            </div> -->
        <div class="division-bar pt-3">
            <strong>Site Division/Project</strong> :
            QMS-North America / OOS
        </div>
        <!-- <div class="button-bar">
                    <button type="button">Save</button>
                    <button type="button">Cancel</button>
                    <button type="button">New</button>
                    <button type="button">Copy</button>
                    <button type="button">Child</button>
                    <button type="button">Check Spelling</button>
                    <button type="button">Change Project</button>
                </div> -->
    </div>



    {{-- ======================================
                    DATA FIELDS
    ======================================= --}}
    <div id="change-control-fields">
        <div class="container-fluid">

        @include('frontend.OOS.comps.stage')

            <!-- Tab links -->
            <div class="cctab">
                <button class="cctablinks active" onclick="openCity(event, 'CCForm1')">General Information</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm2')">Preliminary Lab. Investigation</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm3')">Preliminary Lab Inv. Conclusion</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm4')">Preliminary Lab Invst. Review</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm5')">Phase II Investigation</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm6')">Phase II QC Review</button>
                {{-- <button class="cctablinks" onclick="openCity(event, 'CCForm7')">Additional Testing Proposal </button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm8')">OOS Conclusion</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm9')">OOS Conclusion Review</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm10')">OOS CQ Review</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm11')">Batch Disposition</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm12')">Re-Open</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm13')">Under Addendum Approval</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm14')">Under Addendum Execution</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm15')">Under Addendum Review</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm16')">Under Addendum Verification</button> --}}
                <button class="cctablinks" onclick="openCity(event, 'CCForm17')">Signature</button>

            </div>

            <!-- General Information -->
    <form action="{{ route('oosupdate', $data->id) }}" method="post" enctype="multipart/form-data">
     @csrf
        <div id="step-form">
            <div id="CCForm1" class="inner-block cctabcontent">
                <div class="inner-block-content">

                    <div class="sub-head">General Information</div>
                    <div class="row">

                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Initiator"> OOS Number </label>
                                <input type="number" disabled>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Initiator Group"> Division Code </label>
                                <select disabled>
                                    <option>Enter Your Selection Here</option>
                                    <option></option>
                                    <option></option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Initiator">Initiator <span class="text-danger"></span></label>
                                {{-- <input type="hidden" name="initiator_id" value="{{ Auth::user()->id }}">
                                <input disabled type="text" name="initiator" value="{{ Auth::user()->name }}"> --}}
                            </div>
                        </div>

                        <div class="col-md-6 ">
                            <div class="group-input ">
                                <label for="intiation-date"> Date Of Initiation<span class="text-danger"></span></label>
                                <input type="hidden" value="{{ date('Y-m-d') }}" name="intiation_date">
                                <input readonly type="text" value="{{ date('d-M-Y') }}" name="intiation_date">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Initiator"> Due Date
                                </label>

                                <small class="text-primary">
                                    Please mention expected date of completion
                                </small>
                                <input type="date" id="date" name="date-time">

                            </div>
                        </div>

                        <div class="col-md-6 ">
                            <div class="group-input ">
                                <label for="intiation-date"> Date Of OOS Occurrence<span class="text-danger"></span></label>
                                <input type="text" value="{{ date('Y-m-d') }}" name="intiation_date">
                                <!-- <input readonly type="text" value="{{ date('d-M-Y') }}" name="intiation_date"> -->
                            </div>
                        </div>

                        <div class="col-md-6 ">
                            <div class="group-input ">
                                <label for="intiation-date"> Date Of OOS reporting<span class="text-danger"></span></label>
                                <input type="text" value="{{ date('Y-m-d') }}" name="intiation_date">
                                <!-- <input readonly type="text" value="{{ date('d-M-Y') }}" name="intiation_date"> -->
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="group-input">
                                <label for="Short Description">Short Description
                                    <!-- <span class="text-danger">*</span> -->
                                </label>
                                    <span id="rchars">255</span>characters remaining
                                <input id="docname"  name="description_gi" value="{{$data->description_gi}}"  maxlength="255" required>
                            </div>
                            @error('short_description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Short Description"> Severity Level</label>
                                <select>
                                    <option>Enter Your Selection Here</option>
                                    <option></option>
                                    <option></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Short Description">Initiator group</label>
                                <select>
                                    <option>Enter Your Selection Here</option>
                                    <option></option>
                                    <option></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Initiator Group">Initiator group code</label>

                                <select>
                                    <option>Enter Your Selection Here</option>
                                    <option></option>
                                    <option></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Initiator Group Code">Initiated Through </label>
                                <textarea name="text"></textarea>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Initiator Group Code">If Others ?</label>

                                <select>
                                    <option>Enter Your Selection Here</option>
                                    <option>Yes</option>
                                    <option>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Initiator Group Code">Is Repeat ?</label>
                                <select name="Repeat" id="Repeat">
                                    <option>Enter Your Selection Here</option>
                                    <option>Yes</option>
                                    <option>No</option>
                                </select>


                            </div>
                        </div>


                        <div class="col-lg-6 mt-4">
                            <div class="group-input">
                                <label for="Initiator Group">Repeat Nature</label>
                                <textarea name="text"></textarea>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Initiator Group">Nature of Change</label>
                                <select>
                                    <option>Enter Your Selection Here</option>
                                    <option></option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Initiator Group">Deviation Occured On</label>
                                <input type="date" name="date">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Initiator Group">Description</label>
                                <textarea name="text"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Initiator Group">Initial Attachment</label>
                                <small class="text-primary">
                                    Please Attach all relevant or supporting documents
                                </small>

                                <div class="file-attachment-field">
                                    <div class="file-attachment-list" id=""></div>
                                    <div class="add-btn">
                                        <div>Add</div>
                                        <input type="file" id="myfile" name="" oninput="" multiple>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Tnitiaror Grouo">Source Document Type</label>
                                <select>
                                    <option>Enter Your Selection Here</option>
                                    <option>OOT</option>
                                    <option>Lab Incident</option>
                                    <option>Deviation</option>
                                    <option>Product Non-conformance</option>
                                    <option>Inspectional Observation</option>
                                    <option>Others</option>

                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input ">
                                <label for="Short Description ">Reference System Document </label>
                                <input type="text" name="initiator_group_code" id="initiator_group_code"
                                    value="">

                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Short Description"> Reference Document</label>
                                <input type="string" name="initiator_group_code" id="initiator_group_code"
                                    value="">

                            </div>
                        </div> --}}

                        <div class="sub-head pt-3">Preliminary Information</div>
                        <!-- <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Short Description ">Product / Material Name</label>

                                <input type="text">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input ">
                                <label for="Short Description ">Market</label>

                                <input type="text" name="num">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Initiator Group">Customer</label>
                                <input type="text">
                                {{-- <select>
                                    <option>Enter Your Selection Here</option>
                                    <option></option>
                                    <option></option>
                                </select> --}}
                            </div>
                        </div> -->

                        <!-- ---------------------------grid-1 -------------------------------- -->
                        <div class="group-input">
                            <label for="audit-agenda-grid">
                                Product/ Material Name (with Grade) :
                                <button type="button" name="audit-agenda-grid" id="info_material">+</button>

                                <span class="text-primary" data-bs-toggle="modal"
                                    data-bs-target="#document-details-field-instruction-modal"
                                    style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                    (Launch Instruction)
                                </span>
                            </label>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="info_material_details" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th style="width: 4%">Row#</th>
                                            <th style="width: 8%"> Batch No*.</th>
                                            <th style="width: 12%">AR No.</th>
                                            <th style="width: 10%">Stage</th>
                                            <th style="width: 12% pt-3">Reference Specification No.</th>
                                            <th style="width: 16% pt-2"> Test</th>
                                            <th style="width: 8%">Results Obtained</th>
                                            <th style="width: 8%">Specification limit</th>
                                            <th style="width: 15%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      
                                                @php
                                                    $serialNumber = 1;
                                                @endphp
                                                @foreach ($info_product_materials->data as $oogrid)
                                                    <tr>
                                                        <td disabled>{{ $serialNumber++ }}</td>
                                                        <td><input type="text"
                                                                name="info_product_material[{{ $loop->index }}][info_batch_no]"
                                                                value="{{ $oogrid['info_batch_no'] }}"></td>
                                                        <td><input type="text"
                                                                name="info_product_material[{{ $loop->index }}][info_ar_no]"
                                                                value="{{ $oogrid['info_ar_no'] }}"></td>
                                                        <td><input type="text"
                                                                name="info_product_material[{{ $loop->index }}][info_stage]"
                                                                value="{{ $oogrid['info_stage'] }}"></td>
                                                        <td><input type="text"
                                                                name="info_product_material[{{ $loop->index }}][info_reference_specification_no]"
                                                                value="{{ $oogrid['info_reference_specification_no'] }}"></td>
                                                        <td><input type="text"
                                                                name="info_product_material[{{ $loop->index }}][info_test]"
                                                                value="{{ $oogrid['info_test'] }}"></td>
                                                        <td><input type="text"
                                                                name="info_product_material[{{ $loop->index }}][info_results_obtained]"
                                                                value="{{ $oogrid['info_results_obtained'] }}"></td>
                                                        <td><input type="text"
                                                                name="info_product_material[{{ $loop->index }}][info_specification_limit]"
                                                                value="{{ $oogrid['info_specification_limit'] }}"></td>
                                                        <td><button class="removeRowBtn">Remove</button>
                                                @endforeach
                                                </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Reference Recores">Sample Type </label>
                                <select multiple id="" name="PhaseIIQCReviewProposedBy[]" id="">
                                    <option value=""> Enter Your Selection Here</option>
                                    <option value=""></option>
                                    <option value=""></option>
                                </select>
                            </div>
                        </div> --}}

                       


                        <!-- -------------------------------grid-2  ----------------------------------   -->
                        <!-- <div class="group-input">
                            <label for="audit-agenda-grid">
                                Details of Stability Study
                                <button type="button" name="audit-agenda-grid" id="Details_Stability">+</button>
                                <span class="text-primary" data-bs-toggle="modal"
                                    data-bs-target="#document-details-field-instruction-modal"
                                    style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                    (Launch Instruction)
                                </span>
                            </label>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="Details_Stability_details" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th style="width: 4%">Row#</th>
                                            <th style="width: 8%">AR Number</th>
                                            <th style="width: 12%">Condition: Temperature & RH</th>
                                            <th style="width: 12%">Interval</th>
                                            <th style="width: 16%">Orientation</th>
                                            <th style="width: 16%">Pack Details (if any)</th>
                                            <th style="width: 16%">Specification No.</th>
                                            <th style="width: 16%">Sample Description</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <td><input disabled type="text" name="serial[]" value="1"></td>
                                        <td><input type="text" name="Number[]"></td>
                                        <td><input type="text" name="Name[]"></td>
                                        <td><input type="text" name="Remarks[]"></td>
                                        <td><input type="text" name="Number[]"></td>
                                        <td><input type="text" name="Name[]"></td>
                                        <td><input type="text" name="Remarks[]"></td>
                                        <td><input type="text" name="Number[]"></td>



                                    </tbody>

                                </table>
                            </div>
                        </div> -->


                        <!--
        ------------------------------------------grid-3----------------------------------- -->

                        <!-- <div class="group-input">
                            <label for="audit-agenda-grid">
                                OOS Details
                                <button type="button" name="audit-agenda-grid" id="OOS_Details">+</button>
                                <span class="text-primary" data-bs-toggle="modal"
                                    data-bs-target="#document-details-field-instruction-modal"
                                    style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                    (Launch Instruction)
                                </span>
                            </label>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="OOS_Details_details" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th style="width: 4%">Row#</th>
                                            <th style="width: 8%">AR Number.</th>
                                            <th style="width: 8%">Test Name of OOS</th>
                                            <th style="width: 12%">Results Obtained</th>
                                            <th style="width: 16%">Specification Limit</th>
                                            <th style="width: 16%">Details of Obvious Error</th>
                                            <th style="width: 16%">File Attachment</th>
                                            {{-- <th style="width: 16%">Submit By</th>
                                            <th style="width: 16%">Submit On</th> --}}

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <td><input disabled type="text" name="serial[]" value="1"></td>
                                        <td><input type="text" name="Number[]"></td>
                                        <td><input type="text" name="Name[]"></td>
                                        <td><input type="text" name="Remarks[]"></td>
                                        <td><input type="text" name="Number[]"></td>
                                        <td><input type="text" name="text[]"></td>
                                        <td><input type="file" name="file[]"></td>
                                        {{-- <td><input type="text" name="text[]"></td>
                                        <td><input type="date" name="time[]"></td> --}}



                                    </tbody>

                                </table>
                            </div>
                        </div> -->



                        <div class="button-block">
                            <button type="submit" class="saveButton">Save</button>
                            <!-- <button type="button" class="backButton" onclick="previousStep()">Back</button> -->
                            <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                            <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white">
                                    Exit </a> </button>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Preliminary Lab. Investigation -->
            <div id="CCForm2" class="inner-block cctabcontent">
                <div class="inner-block-content">
                    <div class="sub-head">Preliminary Lab. Investigation </div>
                    <div class="row">

                    <div class="col-12">

                        <label style="font-weight: bold; for="Audit Attachments">Phase IB Investigation Checklist</label>

                            @php
                                $IIB_inv_questions = array(
                                        "Analyst trained in the particular test to operate the instrument used for analysis?",
                                        "Evident from the discussion that the analyst has understood Analytical Method and the
                                        Operation SOP of the equipment/Instrument?",
                                        "Evident that the correct techniques were used by the analyst to perform the test?",
                                        "Correct Analytical Method used for the analysis?",
                                        "Analytical Method followed properly?",
                                        "Whether pH of mobile phase correctly adjusted as per the analytical method?",
                                        "Correct parameters loaded for performing the test?",
                                        "Evident that the system suitability requirements of the analytical method were all met for initial as well as bracketing?",
                                        "Analyst has calculated the results correctly as mentioned in Analytical Method?",
                                        "Analyst calculated the results using correct potency of the standard/molarity of volumetric solution?",
                                        "Is the chromatographic / potentiometric graph pattem matching with previous analysis?",
                                        "Is the processing method followed correctly? All the required peaks integrated properly by using correct processing method/techniques?",
                                        "Proper glassware (Class-A) used for analysis?",
                                        "Proper volumes of pipettes / volumetric flask used for analysis?",
                                        "Any obvious evidence of glassware contamination? (Visual)",
                                        "Evidence or probability of the glassware was not washed or dried properly?",
                                        "Glassware used for analysis properly and legibly labeled?",
                                        "All vials legibly labeled and placed correctly as per sequence plan?",
                                        "Vials filled with appropriate volume and properly caped?",
                                        "Septa used are in good condition & appropriately fitted to the cap?",
                                        "Reagents / chemicals used of recommended grade and prepared as per the analytical method?",
                                        "Whether the correct sampling procedure followed?",
                                        "Were cleaned and correct sampling tool/s used?",
                                        "Correct sample used for the analysis?",
                                        "Was there any contamination or leakage of sample container?",
                                        "1s spillage of standard / sample reported during weighing/ transferring to respective container?",
                                        "Dilutions made in sample/ standard preparation as per Analytical Method?",
                                        "Examination of the retained solutions to check the clarity/ turbidity which yielded OOS result",
                                        "Assessment of the possibility that the sample contamination has occurred during the testing/re-testing procedure (e.g. sample left open to air or unattended).",
                                        "Any abnormality observed during filtration (such as solution filtered / not filtered easily as compared to other samples, clarity of solution etc.)",
                                        "1s any evidence of the sample was not stored properly during analysis?",
                                        "Correct standard used for analysis?",
                                        "Standards and reagents used were properly stored?",
                                        "Any evidence that the standards, reagents were not properly labeled?",
                                        "Standards, reagents used within their expiration dates?",
                                        "Evidence that the standards, reagents have degraded?",
                                        "Evidence that the reagents, standards or other materials used for test were contaminated?",
                                        "Working standards/volumetric solution standardized as per the Analytical method?",
                                        "Equipment/Instrument used for analysis in calibrated state?",
                                        "Appropriate column / electrode used as defined in Specification.",
                                        "Any evidence of malfunction / power outage of the relevant equipment?",
                                        "Preventive Maintenance program of the equipment performed as per schedule?",
                                        "Appropriate capacity analytical balance used?",
                                        "Instrument/equipment meeting the system suitability criteria mentioned in the analytical method?",
                                        "Environmental conditions (temperature, humidity, light) during analysis appropriate?",
                                        "Any previous history of product available?",
                                        "Are there any other sample analyzed for the same test together with the OOS samples?",
                                        "If yes (for above), then is there any implication for the results obtained for those samples?",
                                        "Review of trend data (for Stability):",
                                        "Other observation if any."
                                    );
                            @endphp
                        <div class="group-input">
                        <div class="why-why-chart mx-auto" style="width: 100%">
                            <table class="table table-bordered ">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">Sr.No.</th>
                                        <th style="width: 40%;">Question</th>
                                        <th style="width: 20%;">Response</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- @foreach ($IIB_inv_questions as $IIB_inv_question)
                                        <tr>
                                            <td class="flex text-center">{{ $loop->index + 1 }}</td>
                                            <td><input type="text" readonly name="question[]" value="{{ $IIB_inv_question }}">
                                            </td>
                                            <td>
                                                <div style="display: flex; justify-content: space-around; align-items: center;  margin: 5%; gap:5px">
                                                    <select name="checklist_IB_inv[{{ $loop->index }}][response]" id="response" style="padding: 2px; width:90%; border: 1px solid black;  background-color: #f0f0f0;">
                                                        <option value="">Enter Your Selection Here</option>
                                                        <option value="Yes">Yes</option>
                                                        <option value="No">No</option>
                                                        <option value="N/A">N/A</option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td style="vertical-align: middle;">
                                                <div style="margin: auto; display: flex; justify-content: center;">
                                                    <textarea name="checklist_IB_inv[{{ $loop->index }}][remark]" style="border-radius: 7px; border: 1.5px solid black;"></textarea>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach -->
                              

                                    @if ($checklist_IB_invs)
                                                @foreach ($IIB_inv_questions as $index => $IIB_inv_question)
                                                    <tr>
                                                        <td class="flex text-center">{{ $loop->index + 1 }}</td>
                                                        <td><input type="text" readonly name="question[]" value="{{ $IIB_inv_question }}">
                                                        </td>
                                                        <td>
                                                            <div style="display: flex; justify-content: space-around; align-items: center;  margin: 5%; gap:5px">

                                                            @php
                                                                $dataItem = $checklist_IB_invs->data[$loop->index] ?? null;
                                                            @endphp

                                                            <select name="checklist_IB_inv[{{ $loop->index }}][response]" id="response" style="padding: 2px; width:90%; border: 1px solid black; background-color: #f0f0f0;" >
                                                                <option value="">Select an Option</option>

                                                                <option value="Yes" {{ isset($dataItem) && Helpers::getArrayKey($dataItem, 'response') == 'Yes' ? 'selected' : '' }}>Yes</option>
                                                                <option value="No" {{ isset($dataItem) && Helpers::getArrayKey($dataItem, 'response') == 'No' ? 'selected' : '' }}>No</option>
                                                                <option value="N/A" {{ isset($dataItem) && Helpers::getArrayKey($dataItem, 'response') == 'N/A' ? 'selected' : '' }}>N/A</option>
                                                            </select>
                                                            </div>
                                                        </td>
                                                        <td style="vertical-align: middle;">
                                                            <div style="margin: auto; display: flex; justify-content: center;">
                                                            @php
                                                                $dataItem = $checklist_IB_invs->data[$loop->index] ?? null;
                                                                $remark = isset($dataItem) ? Helpers::getArrayKey($dataItem, 'remark') : '';
                                                            @endphp

                                                            <textarea name="checklist_IB_inv[{{ $loop->index }}][remark]" style="border-radius: 7px; border: 1.5px solid black;">{{ $remark }}</textarea>

                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                </tbody>
                            </table>
                        </div>

                        </div>
                        </div>

                        @php
                            $descriptions = is_array($data->Description_Deviation) 
                                ? $data->Description_Deviation 
                                : json_decode($data->Description_Deviation, true);
                        @endphp

                        <div class="col-lg-12 mb-4">
                            <div class="group-input">
                                <label for="Audit Schedule Start Date">Summary Of Discussion with Analyst</label>
                                <div class="col-md-12">
                                    <div class="group-input">
                      
                                    <textarea class="summernote" name="description_summary" id="summernote-1">{{$data->description_summary}}
                                    </textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 mb-4">
                            <div class="group-input">
                                <label for="Audit Schedule Start Date"> Discussion Points </label>
                                <div class="col-md-12 4">
                                    <div class="group-input">
                                        <textarea class="summernote" name="Discussion_points" id="summernote-1">{{$data->Discussion_points}}
                                    </textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 mb-4">
                            <div class="group-input">
                                <label for="Audit Schedule Start Date"> Remark Of QC investigator </label>
                                <div class="col-md-12 4">
                                    <div class="group-input">
                                        <textarea class="summernote" name="Remark_qc_investigator" id="summernote-1">
                                            {{$data->Remark_qc_investigator}}
                                    </textarea>
                                    </div>
                                </div>
                            </div> 
                        </div>

                        <div class="col-lg-12 mb-4">
                            <div class="group-input">
                                <label for="Audit Schedule Start Date"> Conclusion On Preliminary Investigation Response</label>
                                <div class="col-md-12 4"> 
                                    <div class="group-input">
                                        <textarea class="summernote" name="preliminary_investigation_response" id="summernote-1">
                                            {{$data->preliminary_investigation_response}}
                                    </textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 mb-4">
                            <div class="group-input">
                                <label for="Audit Schedule Start Date">Results Of Sample Analyzed in the same sequence (if any)</label>
                                <input type="text"  class="" name="simple_analyzed" value="{{$data->simple_analyzed}}">
                            </div>
                        </div>

                        <div class="col-lg-12 mb-4">
                            <div class="group-input">
                                <label for="Audit Schedule Start Date">Investigator</label>
                                <input type="text" class="" name="investigator" value="{{$data->investigator}}">
                            </div>
                        </div>

                       <!--  -->
                        <div class="group-input">
                            <label for="audit-agenda-grid">
                                Details Of Previous history Of Similar type (Same product, Same test) Of OOS Observed
                                <button type="button" name="audit-agenda-grid" id="details_stability">+</button>
                                <span class="text-primary" data-bs-toggle="modal"
                                    data-bs-target="#document-details-field-instruction-modal"
                                    style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                    (Launch Instruction)
                                </span>
                            </label>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="details_stability_details" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th style="width: 4%">Row#</th>
                                            <th style="width: 12%">Summary Of Previous OOS history</th>
                                            <th style="width: 12%">CAPA taken for OOS</th>
                                            <th style="width: 12%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>         
                                    @php
                                        $serialNumber = 1;
                                    @endphp
                                    @foreach ($details_stability->data as $oogrid)
                                        <tr>
                                            <td disabled>{{ $serialNumber++ }}</td>
                                            <td><input type="text"
                                                    name="details_stability[{{ $loop->index }}][summary_of_previous_oos]"
                                                    value="{{ $oogrid['summary_of_previous_oos'] }}"></td>
                                            <td><input type="text"
                                                    name="details_stability[{{ $loop->index }}][capa_taken_for_oos]"
                                                    value="{{ $oogrid['capa_taken_for_oos'] }}"></td>

                                            <td><button class="removeRowBtn">Remove</button>
                                    @endforeach
                                    </tr>
                                    </tbody>

                                </table>
                            </div>
                        </div>

                        <div class="col-lg-12 mb-4">
                            <div class="group-input">
                                <label for="Audit Schedule Start Date">Comment Of Robustnes Of Previously Recommended CAPA </label>
                                <div class="col-md-12 4"> 
                                    <div class="group-input">
                                        <textarea class="summernote" name="review_comments_plir" id="summernote-1">
                                        {{$data->review_comments_plir}}
                                    </textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Audit Schedule End Date"> Field Alert Required ?</label>
                                <select>
                                    <option>Enter Your Selection Here</option>
                                    <option>Yes</option>
                                    <option>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Reference Recores">Field Alert Ref.No. </label>
                                <select multiple id="" name="PhaseIIQCReviewProposedBy[]" id="">
                                    <option value=""> Enter Your Selection Here</option>
                                    <option value=""></option>
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12 mb-4">
                            <div class="group-input">
                                <label for="Description Deviation">Justify if no Field Alert</label>
                                <textarea class="summernote" name="Description_Deviation[]" id="summernote-1">
                                    </textarea>
                            </div>
                        </div>
                        
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Product/Material Name"> Verification Analysis Required ?</label>
                                <select>
                                    <option>Enter Your Selection Here</option>
                                    <option>Yes</option>
                                    <option>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Reference Recores">Verification Analysis Ref.</label>
                                <select multiple id="" name="PhaseIIQCReviewProposedBy[]" id="">
                                    <option value=""> Enter Your Selection Here</option>
                                    <option value=""></option>
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Product/Material Name">Analyst Interview Required ?</label>
                                <select>
                                    <option>Enter Your Selection Here</option>
                                    <option>Yes</option>
                                    <option>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Reference Recores">Analyst Interview Ref.</label>
                                <select multiple id="" name="PhaseIIQCReviewProposedBy[]" id="">
                                    <option value=""> Enter Your Selection Here</option>
                                    <option value=""></option>
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-12 mb-4">
                            <div class="group-input">
                                <label for="Audit Schedule Start Date">Justify if no Analyst Int. </label>


                                <textarea class="summernote" name="" id="summernote-1">
                                    </textarea>

                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Product/Material Name">Phase I Investigation Required ?</label>
                                <select>
                                    <option>Enter Your Selection Here</option>
                                    <option>Yes</option>
                                    <option>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Product/Material Name">Phase I Investigation</label>
                                <select>
                                    <option>Enter Your Selection Here</option>
                                    <option>Phase I Micro</option>
                                    <option>Phase I Chemical</option>
                                    <option>Hypothesis</option>
                                    <option>Resampling</option>
                                    <option>Others</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Reference Recores">Phase I Investigation Ref.</label>
                                <select multiple id="" name="PhaseIIQCReviewProposedBy[]" id="">
                                    <option value=""> Enter Your Selection Here</option>
                                    <option value=""></option>
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Audit Attachments">File Attachments</label>
                                <small class="text-primary">
                                    Please Attach all relevant or supporting documents
                                </small>
                                <div class="file-attachment-field">
                                    <div class="file-attachment-list" id="file_attach"></div>
                                    <div class="add-btn">
                                        <div>Add</div>
                                        <input type="file" id="myfile" name="file_attach[]"
                                            oninput="addMultipleFiles(this, 'file_attach')" multiple>
                                    </div>
                                </div>

                            </div>
                        </div> --}}

                        <div class="button-block">
                            <button type="submit" id="ChangesaveButton" class="saveButton">Save</button>
                            <button type="button" class="backButton" onclick="previousStep()">Back</button>
                            <button type="button" id="ChangeNextButton" class="nextButton"
                                onclick="nextStep()">Next</button>
                            <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white">
                                    Exit </a> </button>
                        </div>
                    </div>
                </div>

            </div>


            <!-- Preliminary Lab Inv. Conclusion -->
            <div id="CCForm3" class="inner-block cctabcontent">
                <div class="inner-block-content">
                    <div class="sub-head">PHASE -1(A) in case OBVIOUS ERROR is identified  (If Applicable - Yes/No.)</div>
                    <div class="row">

                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Lead Auditor">Root Cause</label>
                                <select name="root_cause_identified_plic">
                                    <option value="" >--Select---</option>
                                    <option value="yes" {{ $data->root_cause_identified_plic == 'yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="no" {{ $data->root_cause_identified_plic == 'no' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                        </div>



    <script>
        $(document).ready(function() {
            $('#instrument_detail').click(function(e) {
                function generateTableRow(serialNumber) {
                    var currentDate = new Date().toISOString().split('T')[0];

                    var html =
                        '<tr>' +
                            '<td><input disabled type="text" name="instrument_detail['+ serialNumber +'][serial]" value="' + serialNumber +
                            '"></td>' +
                            '<td><input type="text" name="instrument_detail['+ serialNumber +'][grid_batch_no]"></td>'+
                            '<td><input type="text" name="instrument_detail['+ serialNumber +'][grid_arnumber]"></td>'+
                            '<td><input type="text" name="instrument_detail['+ serialNumber +'][grid_stage_investigation]"></td>' +
                            '<td><input type="text" name="instrument_detail['+ serialNumber +'][grid_quantity]"></td>' +
                            '<td><input type="text" name="instrument_detail['+ serialNumber +'][grid_authorized_by]"></td>' +
                            '<td><input type="text" name="instrument_detail['+ serialNumber +'][grid_sampled_by]"></td>' +

                            // '<td><button type="text" class="removeRowBtn">Remove</button></td>' +

                        '</tr>';
                    return html;
                }

                var tableBody = $('#instrument_details_details tbody');
                var rowCount = tableBody.children('tr').length;
                var newRow = generateTableRow(rowCount + 1);
                tableBody.append(newRow);
            });
        });
    </script>

                        <div class="col-md-12 mb-4">
                            <div class="group-input">
                                <label for="Description Deviation">Correct the Obvious error/cause and document.</label>
                                <textarea class="summernote" name="summary_of_prelim_investiga_plic" id="summernote-1">{{$data->summary_of_prelim_investiga_plic}}</textarea> 
                            </div>
                        </div>

                        <div class="col-md-12 mb-4">
                            <div class="group-input">
                                <label for="Description Deviation">Impact assessment </label>
                                <span class="text-primary">(To be decided in coordination with QA Head / Designation)</span>
                               <textarea class="summernote" name="re_sampling_ref_no_piii" id="summernote-1">
                               {{$data->re_sampling_ref_no_piii}}
                                    </textarea>
                            </div>
                        </div>
                        {{-- <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Audit Team">OOS Category-Root Cause Identified</label>
                                <select>
                                    <option>Enter Your Selection Here</option>
                                    <option>Analyst Error</option>
                                    <option>Instrument Error</option>
                                    <option>Product/Material Related Error</option>
                                    <option>Other Error</option>
                                </select>
                            </div>
                        </div> --}}

                        <div class="col-md-12 mb-4">
                            <div class="group-input">
                                <label for="Description Deviation">Corrective Action</label>
                                <textarea class="summernote" name="corrective_action" id="summernote-1">
                                {{$data->corrective_action}}
                                    </textarea>
                            </div>
                        </div>
                        <div class="col-md-12 mb-4">
                            <div class="group-input">
                                <label for="Description Deviation">Preventive Action (if any):</label>
                                <textarea class="summernote" name="preventive_action1A" id="summernote-1">
                                {{$data->preventive_action1A}}
                                    </textarea>
                            </div>
                        </div>

                        <div class="col-md-12 mb-4">
                            <div class="group-input">
                                <label for="Description Deviation">Evaluation By Head Quality</label>
                                <!-- <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div> -->
                                <input type="text" class="" name="evaluation_by_head_quality" value="{{$data->evaluation_by_head_quality}}" id="">
                                    
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Reference Recores">Outcome Of Phase I(A) Investigation.</label>
                                <select multiple id="reference_record" name="outcome_phase_i_investigation" id="">
                                    <option value="">--Select---</option>
                                    <option value="Closure of OOS with CAPA">Closure of OOS with CAPA</option>
                                    <option value="Phase I (B) Investigation">Phase I (B) Investigation</option>
                                    <option value="Phase II">Phase II</option>
                                </select>
                            </div>
                        </div>
                    <br>
                    <div class="sub-head">PHASE -I(B) INVESTIGATION  (If Applicable -Yes/No.)</div>


                        <div class="col-md-12 mb-4">
                            <div class="group-input">
                                <label for="Description Deviation">Hypothesis Analysis</label>
                                <div><small class="text-primary">Hypothesis may vary depending on nature and test of OOS</small></div>
                                {{-- <textarea class="" name="hypothesis_analysis" id="">
                                    </textarea> --}}
                                <select name="hypothesis_analysis">
                                    <option value="">Enter Your Selection Here</option>
                                    <option value="yes" {{ $data->hypothesis_analysis == 'yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="no" {{ $data->hypothesis_analysis == 'no' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12 mb-4">
                            <div class="group-input">
                                <label for="Description Deviation">Results of Hypothesis:</label>
                                <!-- <div><small class="text-primary">Hypothesis may vary depending on nature and test of OOS</small></div> -->
                                <textarea class="" name="results_hypothesis" id="">
                                {{$data->results_hypothesis}}
                                    </textarea>
                            </div>
                        </div>

                        <div class="col-md-12 mb-4">
                            <div class="group-input">
                                <label for="Description Deviation">Evaluation Of Hypothesis & Comments</label>
                                <!-- <div><small class="text-primary">Hypothesis may vary depending on nature and test of OOS</small></div> -->
                                <textarea class="" name="evaluation_of_hypothesis_comments" id="">
                                {{$data->evaluation_of_hypothesis_comments}}
                                    </textarea>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Lead Auditor">Hypothesis Root Cause</label>
                                <!-- <div class="text-primary">Please Choose the relevent units</div> -->
                                <select name="hypothesis_root_cause" id="" onchange="">
                                    <option value="">Enter Your Selection Here</option>
                                    <option value="yes" {{ $data->hypothesis_root_cause == 'yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="no" {{ $data->hypothesis_root_cause == 'no' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12 mb-4">
                            <div class="group-input">
                                <label for="Description Deviation">Impact assessment/Risk assessment (If applicable)</label>
                                <textarea class="summernote" name="impact_assessment_risk" id="summernote-1">{{$data->impact_assessment_risk}}
                                    </textarea>
                            </div>
                        </div>

                        <div class="col-md-12 mb-4">
                            <div class="group-input">
                                <label for="Description Deviation">Preventive action(if any)</label>
                                <textarea class="summernote" name="preventive_action_phase1b" id="summernote-1">
                                {{$data->preventive_action_phase1b}}
                                    </textarea>
                            </div>
                        </div>

                        <div class="col-md-12 mb-4">
                            <div class="group-input">
                                <label for="Description Deviation">Evaluation By Head Quality / Designee</label>
                                <input type="text" class="" name="evaluation_by_head_quality" value="{{$data->evaluation_by_head_quality}}" id="">
                                    
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Reference Recores">Outcome Of Phase I(B) Investigation.</label>
                                <select multiple id="reference_record1" name="outcome_phase_ib_investigation2[]">
                                    <option value="">--Select---</option>
                                    <option value="Closure of OOS with CAPA">Closure of OOS with CAPA</option>
                                    <option value="Phase II">Phase II</option>
                                </select>
                            </div>
                        </div>

                        <div class="button-block">
                            <button type="submit" id="ChangesaveButton" class="saveButton">Save</button>
                            <button type="button" class="backButton" onclick="previousStep()">Back</button>
                            <button type="button" id="ChangeNextButton" class="nextButton"
                                onclick="nextStep()">Next</button>
                            <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white">
                                    Exit </a> </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Preliminary Lab Invst. Review--->
            <div id="CCForm4" class="inner-block cctabcontent">
                <div class="inner-block-content">
                    <div class="sub-head">PHASE II INVESTIGATION (Extended laboratory Investigation) (If Applicable - Yes/No)</div>
                    <div class="row">

                        <div class="col-md-12 mb-4">
                            <div class="group-input">
                                <label for="Description Deviation">Hypothesis Analysis</label>
                                <!-- <div><small class="text-primary">Hypothesis may vary depending on nature and test of OOS</small></div> -->
                                <select name="hypothesis_analysis_phase2">
                                    <option value="">Enter Your Selection Here</option>
                                    <option value="yes" {{ $data->hypothesis_analysis_phase2 == 'yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="no" {{ $data->hypothesis_analysis_phase2 == 'no' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12 mb-4">
                            <div class="group-input">
                                <label for="Description Deviation">Results of Hypothesis:</label>
                                <textarea class="" name="results_hypothesis_phase2" id="">
                                {{$data->results_hypothesis_phase2}}
                                    </textarea>
                            </div>
                        </div>

                        <div class="col-md-12 mb-4">
                            <div class="group-input">
                                <label for="Description Deviation">Evaluation Of Hypothesis & Comments</label>
                                <textarea class="" name="evaluation_of_hypothesis_comments_phase2" id="">
                                {{$data->evaluation_of_hypothesis_comments_phase2}}
                                    </textarea>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Lead Auditor">Root Cause</label>
                                <!-- <div class="text-primary">Please Choose the relevent units</div> -->
                                <select name="hypothesis_root_cause_phase2" id="" onchange="">
                                    <option value="">Enter Your Selection Here</option>
                                    <option value="yes" {{ $data->hypothesis_root_cause_phase2 == 'yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="no" {{ $data->hypothesis_root_cause_phase2 == 'no' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12 mb-4">
                            <div class="group-input">
                                <label for="Description Deviation">Impact assessment/Risk assessment (If applicable)</label>
                                <textarea class="summernote" name="impact_assessment_risk_phase2" id="summernote-1">
                                {{$data->impact_assessment_risk_phase2}}
                                    </textarea>
                            </div>
                        </div>

                        <div class="col-md-12 mb-4">
                            <div class="group-input">
                                <label for="Description Deviation">Preventive action(if any)</label>
                                <textarea class="summernote" name="preventive_action_phase2" id="summernote-1">
                                    </textarea>
                            </div>
                        </div>

                        <div class="col-md-12 mb-4">
                            <div class="group-input">
                                <label for="Description Deviation">Evaluation By Head Quality / Designee</label>
                                <input type="text" class="" name="evaluation_by_head_quality_disignee_phase2" value="{{$data->evaluation_by_head_quality_disignee_phase2}}" id="">
                                    
                            </div>
                        </div>


                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Reference Records">Outcome Of Phase II Extended Laboratory Investigation</label>
                                <select multiple id="reference_record2" name="outcome_phase_ii_investigation2[]" class="form-control">
                                    <option value="">--Select---</option>
                                    <option value="Closure of OOS with CAPA" 
                                        {{ in_array('Closure of OOS with CAPA', $data->outcome_phase_ii_investigation2 ?? []) ? 'selected' : '' }}>
                                        Closure of OOS with CAPA
                                    </option>
                                    <option value="Phase II" 
                                        {{ in_array('Phase II', $data->outcome_phase_ii_investigation2 ?? []) ? 'selected' : '' }}>
                                        Phase II (Manufacturing investigation)
                                    </option>
                                </select>
                            </div>
                        </div>




                        <!-- ---------------------------grid-1 ---Preliminary Lab Invst. Review----------------------------- -->
                        <!-- <div class="group-input">
                            <label for="audit-agenda-grid">
                                Info. On Product/ Material
                                <button type="button" name="audit-agenda-grid" id="oos_capa">+</button>
                                <span class="text-primary" data-bs-toggle="modal"
                                    data-bs-target="#document-details-field-instruction-modal"
                                    style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                    (Launch Instruction)
                                </span>
                            </label>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="oos_capa_details" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th style="width: 4%">Row#</th>
                                            <th style="width: 8%">OOS Number</th>
                                            <th style="width: 8%"> OOS Reported Date</th>
                                            <th style="width: 12%">Description of OOS</th>
                                            <th style="width: 16%">Previous OOS Root Cause</th>
                                            <th style="width: 16%"> CAPA</th>
                                            <th style="width: 16% pt-3">Closure Date of CAPA</th>
                                            <th style="width: 16%">CAPA Requirement</th>

                                            <th style="width: 16%">Reference CAPA Number</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <td><input disabled type="text" name="serial[]" value="1"></td>
                                        <td><input type="text" name="Number[]"></td>
                                        <td><input type="date" name="Name[]"></td>
                                        <td><input type="text" name="Remarks[]"></td>
                                        <td><input type="text" name="Number[]"></td>
                                        <td><input type="text" name="Name[]"></td>
                                        <td><input type="date" name="Remarks[]"></td>
                                        <td><select name="CAPARequirement[]">
                                                <option>Yes</option>
                                                <option>No</option>
                                            </select></td>
                                        <td><input type="text" name="Name[]"></td>


                                    </tbody>

                                </table>
                            </div>
                        </div>



                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Audit Start Date"> Phase II Inv. Required ?</label>
                                <select>
                                    <option>Enter Your Selection Here</option>
                                    <option>Yes</option>
                                    <option>No</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Audit Attachments"> Supporting Attachments</label>
                                <small class="text-primary">
                                    Please Attach all relevant or supporting documents
                                </small>
                                <div class="file-attachment-field">
                                    <div class="file-attachment-list" id="file_attach"></div>
                                    <div class="add-btn">
                                        <div>Add</div>
                                        <input type="file" id="myfile" name="file_attach[]"
                                            oninput="addMultipleFiles(this, 'file_attach')" multiple>
                                    </div>
                                </div>

                            </div>
                        </div> -->

                        <div class="button-block">
                            <button type="submit" id="ChangesaveButton" class="saveButton">Save</button>
                            <button type="button" class="backButton" onclick="previousStep()">Back</button>
                            <button type="button" id="ChangeNextButton" class="nextButton"
                                onclick="nextStep()">Next</button>
                            <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white">
                                    Exit </a> </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Phase II Investigation -->
        <div id="CCForm5" class="inner-block cctabcontent">
            <div class="inner-block-content">
                <div class="sub-head">
                    PHASE II INVESTIGATION (Manufacturing Investigation)
                </div>
                <div class="row">

                    <div class="col-12">

                        <label style="font-weight: bold; for="Audit Attachments">Phase IB Investigation Checklist</label>
                        @php
                            $categories = [
                                '1.0 PERSONNEL' => [
                                    '1.1 Was the person properly trained?',
                                    '1.2 Does he know the job properly?',
                                    '1.3 Was he wearing the necessary personal protective?',
                                    '1.4 Were the critical operations supervised by a supervisor?'
                                ],
                                '2.0 EQUIPMENT' => [
                                    '2.1 Was correct equipment used?',
                                    '2.2 Was condition of the equipment(s) good?',
                                    '2.3 Was/were equipment(s) inspected by QA before use, if required?',
                                    '2.4 Was the equipment provided with required utilities?',
                                    '2.5 Was the equipment calibrated?'
                                ],
                                '3.0 PRODUCTION' => [
                                    '3.1 Was the correct material received in right condition?',
                                    '3.2 Was the right material added as per BMR?',
                                    '3.3 Was the total process carried out as per BMR?'
                                ],
                                '4.0 QC' => [
                                    '4.1 Was any material used in the manufacturing released under deviation?',
                                    '4.2 Were there any other observations during chemical and instrumental analysis which could result in OOS?'
                                ],
                                '5.0 HISTORY' => [
                                    '5.1 Any previous history of the product available?'
                                ],
                                '6.0 STORE' => [
                                    '6.1 Was the dispensing carried out properly?',
                                    '6.2 Was any irregularity observed in material while dispensing?'
                                ]
                            ];
                        @endphp

                    <div class="group-input">
                        <div class="why-why-chart mx-auto" style="width: 100%">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">Sr. No.</th>
                                        <th style="width: 40%;">Check Points</th>
                                        <th style="width: 15%;">Response</th>
                                        <th style="width: 20%;">Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($oos_details)
                                    @php $srNo = 1; @endphp
                                    @foreach ($categories as $category => $questions)
                                        <tr>
                                            <td colspan="4" style="font-weight: bold; background-color: #f0f0f0;">{{ $category }}</td>
                                        </tr>
                                        @foreach ($questions as $index => $question)
                                            @php
                                                $questionNumber = $srNo . '.' . ($index + 1); // Generate question numbers like 1.1, 1.2, etc.
                                            @endphp
                                            <tr>
                                                <td class="text-center">{{ $questionNumber }}</td>
                                                <td>
                                                    <input type="text" readonly name="questions[{{ $questionNumber }}][question]" value="{{ $question }}" style="border: none; background: none; width: 100%;">
                                                </td>

                                                <td>
                                                    <select name="oos_detail[{{ $questionNumber }}][response]" style="padding: 5px; width: 100%; border: 1px solid black; background-color: #f0f0f0;">
                                                        <option value="">Select</option>
                                                        <option value="Yes" {{ $oos_details->oos_detail == 'Yes' ? 'selected' : '' }}>Yes</option>
                                                        <option value="No" {{ $oos_details->oos_detail == 'No' ? 'selected' : '' }}>No</option>
                                                        <option value="N/A" {{ $oos_details->oos_detail == 'N/A' ? 'selected' : '' }}>N/A</option>
                                                    </select>

                                                </td>
                                                <td>
                                                    
                                                    <textarea name="oos_detail[{{ $questionNumber }}][remark]" style="border-radius: 7px; border: 1px solid black; width: 100%;"></textarea>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @php $srNo++; @endphp
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    </div>



                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Brief Summary of Phase II Investigation</label>
                            <textarea class="summernote" name="phase_ii_investigation" id="summernote-1">
                            {{$data->phase_ii_investigation}}
                                    </textarea>
                        </div>
                    </div>

                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Brief Summary of Root Cause</label>
                            <!-- <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div> -->
                            <textarea class="summernote" name="brief_summary_root_cause" id="summernote-1">{{$data->brief_summary_root_cause}}
                                    </textarea>
                        </div>
                    </div>

                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Brief Summary of Action taken/planned</label>
                            <textarea class="summernote" name="brief_summary_taken_planned" id="summernote-1">
                            {{$data->brief_summary_taken_planned}}
                                    </textarea>
                        </div>
                    </div>

                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Comment Of Head Quality/Designee</label>
                            <textarea class="summernote" name="comment_of_head_qualiry" id="summernote-1">
                            {{$data->comment_of_head_qualiry}}
                                    </textarea>
                        </div>
                    </div>

                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Reference Recores">Recommendation for Batch Disposition</label>
                                <select multiple id="reference_record2" name="recommendation_for_batch[]">
                                    <option value="">--Select---</option>
                                    <option value="Reported OOS for closure">Reported OOS for closure</option>
                                    <option value="Recommended for Phase III investigation">Recommended for Phase III investigation</option>
                                    <option value="Batch is recommended for R&D investigation followed by reprocessing, application for in process/Finished product.">Batch is recommended for R&D investigation followed by reprocessing, application for in process/Finished product.</option>
                                    <option value="Material Reprocessing Authorization form">Material Reprocessing Authorization form</option>
                                </select>
                            </div>
                        </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Report Attachments"> Test Results </label>
                            <input type="text" name="phaseiii_results" value="{{$data->phaseiii_results}}">
                        </div>
                    </div>
                    
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Report Attachments"> Limit</label>
                            <input type="text" name="phaseiii_limit" value="{{$data->phaseiii_results}}">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Audit Attachments"> Conclusion </label>
                            <textarea class="" name="conclusion" id="">
                            {{$data->conclusion}}
                            </textarea>
                        </div>
                    </div>

                    <div class="sub-head">
                        Material Re-Sampling Authorization
                    </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Auditee"> Reason/Justification for re-sampling </label>
                            <select name="reason_justification">
                                <option value="">--Select here --</option>
                                <option value="yes" {{ $data->reason_justification == 'yes' ? 'selected' : '' }}>Yes</option>
                                <option value="no" {{ $data->reason_justification == 'no' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                    </div>


                        <div class="group-input">
                            <label for="audit-agenda-grid">
                            Instrument details
                                <button type="button" name="audit-agenda-grid" id="instrument_detail">+</button>
                                <span class="text-primary" data-bs-toggle="modal"
                                    data-bs-target="#document-details-field-instruction-modal"
                                    style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                    (Launch Instruction)
                                </span>
                            </label>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="instrument_details_details" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th style="width: 4%">Row#</th>
                                            <th style="width: 8%"> Batch No*.</th>
                                            <th style="width: 12%">AR No.</th>
                                            <th style="width: 10%">Stage of Investigation</th>
                                            <th style="width: 12% pt-3">Quantity</th>
                                            <th style="width: 16% pt-2"> Authorized by / Head Quality</th>
                                            <th style="width: 8%">Sampled by</th>
                                            <th style="width: 8%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        

                                        @php
                                          $serialNumber = 1;
                                        @endphp
                                        @foreach ($instrument_detail->data as $oogrid)
                                            <tr>
                                                <td disabled>{{ $serialNumber++ }}</td>
                                                <td><input type="text"
                                                        name="instrument_detail[{{ $loop->index }}][grid_batch_no]"
                                                        value="{{ $oogrid['grid_batch_no'] }}"></td>
                                                <td><input type="text"
                                                        name="instrument_detail[{{ $loop->index }}][grid_arnumber]"
                                                        value="{{ $oogrid['grid_arnumber'] }}"></td>
                                                <td><input type="text"
                                                        name="instrument_detail[{{ $loop->index }}][grid_stage_investigation]"
                                                        value="{{ $oogrid['grid_stage_investigation'] }}"></td>
                                                <td><input type="text"
                                                        name="instrument_detail[{{ $loop->index }}][grid_quantity]"
                                                        value="{{ $oogrid['grid_quantity'] }}"></td>
                                                <td><input type="text"
                                                        name="instrument_detail[{{ $loop->index }}][grid_authorized_by]"
                                                        value="{{ $oogrid['grid_authorized_by'] }}"></td>
                                                <td><input type="text"
                                                        name="instrument_detail[{{ $loop->index }}][grid_sampled_by]"
                                                        value="{{ $oogrid['grid_sampled_by'] }}"></td>
                                                <td><button class="removeRowBtn">Remove</button>
                                        @endforeach
                                            </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Audit Attachments">Results </label>
                            <input type="text" name="material_results" value="{{$data->material_results}}">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Audit Attachments"> Conclusion </label>
                            <textarea class="" name="material_conclusion" value="" id="">
                            {{$data->material_conclusion}}
                            </textarea>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Reference Recores">Evaluation by Quality Head/Designee</label>
                            <select  name="evaluation_by_quality_designee" id="">
                                <option value=""> Enter Your Selection Here</option>
                                <option value="yes" {{ $data->evaluation_by_quality_designee == 'yes' ? 'selected' : '' }}>Yes</option>
                                <option value="no" {{ $data->evaluation_by_quality_designee == 'no' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                    </div>

                    <div class="button-block">
                        <button type="submit" id="ChangesaveButton" class="saveButton">Save</button>
                        <button type="button" class="backButton" onclick="previousStep()">Back</button>
                        <button type="button" id="ChangeNextButton" class="nextButton"
                            onclick="nextStep()">Next</button>
                        <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white">
                                Exit </a> </button>
                    </div>

                </div>
            </div>
        </div>

        <!-- Phase II QC Review -->
        <div id="CCForm6" class="inner-block cctabcontent">
            <div class="inner-block-content">
                <div class="sub-head">PHASE -III - INVESTIGATION </div>
                <div class="row">
                    <div class="group-input">
                        <label for="audit-agenda-grid">
                            Results from Analyst - 2
                            <button type="button" name="audit-agenda-grid" id="oos_conclusion_review">+</button>
                            <span class="text-primary" data-bs-toggle="modal"
                                data-bs-target="#document-details-field-instruction-modal"
                                style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                (Launch Instruction)
                            </span>
                        </label>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="oos_conclusion_review_details"
                                style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th style="width: 4%">Row#</th>
                                        <th style="width: 16%">Set -I</th>
                                        <th style="width: 16%">Set-II</th>
                                        <th style="width: 16%">Set-III</th>
                                        <th style="width: 5%"> Action </th>
                                    </tr>
                                </thead>
                                <tbody>
                                        @php
                                          $serialNumber = 1;
                                        @endphp
                                        @foreach ($phase_iii_result->data as $oogrid)
                                            <tr>
                                                <td disabled>{{ $serialNumber++ }}</td>
                                                <td><input type="text"
                                                        name="phase_iii_result[{{ $loop->index }}][phase_iii_result_set_1]"
                                                        value="{{ $oogrid['phase_iii_result_set_1'] }}"></td>
                                                <td><input type="text"
                                                        name="phase_iii_result[{{ $loop->index }}][phase_iii_result_set_2]"
                                                        value="{{ $oogrid['phase_iii_result_set_2'] }}"></td>
                                                <td><input type="text"
                                                        name="phase_iii_result[{{ $loop->index }}][phase_iii_result_set_3]"
                                                        value="{{ $oogrid['phase_iii_result_set_3'] }}"></td>
                                                <td><button class="removeRowBtn">Remove</button>
                                        @endforeach
                                            </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <script>
                        $(document).ready(function() {
                            $('#oos_conclusion_review').click(function(e) {
                                function generateTableRow(serialNumber) {
                                    var html =
                                        '<tr>' +
                                        '<td><input disabled type="text" name="phase_iii_result[' + serialNumber + '][serial]" value="' + serialNumber +
                                        '"></td>' +
                                        '<td><input type="text" name="phase_iii_result[' + serialNumber + '][phase_iii_result_set_1]"></td>' +
                                        '<td><input type="text" name="phase_iii_result[' + serialNumber + '][phase_iii_result_set_2]"></td>' +
                                        '<td><input type="text" name="phase_iii_result[' + serialNumber + '][phase_iii_result_set_3]"></td>' +
                                        '<td><button type="text" class="removeRowBtn">Remove</button></td>'
                                        '</tr>';
                                    '</tr>';

                                    return html;
                                }

                                var tableBody = $('#oos_conclusion_review_details tbody');
                                var rowCount = tableBody.children('tr').length;
                                var newRow = generateTableRow(rowCount + 1);
                                tableBody.append(newRow);
                            });
                        });
                    </script>


                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Investigator</label>
                            <input type="text" class="" name="phase_iii_investigator" value="{{$data->phase_iii_investigator}}" id="">
                        </div>
                    </div>


                    <div class="group-input">
                        <label for="audit-agenda-grid">
                            Results from Analyst - 1
                            <button type="button" name="audit-agenda-grid" id="oos_review">+</button>
                            <span class="text-primary" data-bs-toggle="modal"
                                data-bs-target="#document-details-field-instruction-modal"
                                style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                (Launch Instruction)
                            </span>
                        </label>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="oos_review_details"
                                style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th style="width: 4%">Row#</th>
                                        <th style="width: 16%">Set -I</th>
                                        <th style="width: 16%">Set-II</th>
                                        <th style="width: 16%">Set-III</th>
                                        <th style="width: 5%"> Action </th>
                                    </tr>
                                </thead>
                                <tbody>
                                        @php
                                          $serialNumber = 1;
                                        @endphp
                                        @foreach ($phase_iii_result_i->data as $oogrid)
                                            <tr>
                                                <td disabled>{{ $serialNumber++ }}</td>
                                                <td><input type="text"
                                                        name="phase_iii_result_i[{{ $loop->index }}][phase_iii_result_i_set_1]"
                                                        value="{{ $oogrid['phase_iii_result_i_set_1'] }}"></td>
                                                <td><input type="text"
                                                        name="phase_iii_result_i[{{ $loop->index }}][phase_iii_result_i_set_2]"
                                                        value="{{ $oogrid['phase_iii_result_i_set_2'] }}"></td>
                                                <td><input type="text"
                                                        name="phase_iii_result_i[{{ $loop->index }}][phase_iii_result_i_set_3]"
                                                        value="{{ $oogrid['phase_iii_result_i_set_3'] }}"></td>
                                                <td><button class="removeRowBtn">Remove</button>
                                        @endforeach
                                            </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <script>
                        $(document).ready(function() {
                            $('#oos_review').click(function(e) {
                                function generateTableRow(serialNumber) {
                                    var html =
                                        '<tr>' +
                                        '<td><input disabled type="text" name="phase_iii_result_i[' + serialNumber + '][serial]" value="' + serialNumber +
                                        '"></td>' +
                                        '<td><input type="text" name="phase_iii_result_i[' + serialNumber + '][phase_iii_result_i_set_1]"></td>' +
                                        '<td><input type="text" name="phase_iii_result_i[' + serialNumber + '][phase_iii_result_i_set_2]"></td>' +
                                        '<td><input type="text" name="phase_iii_result_i[' + serialNumber + '][phase_iii_result_i_set_3]"></td>' +
                                        '<td><button type="text" class="removeRowBtn">Remove</button></td>'
                                        '</tr>';
                                    '</tr>';

                                    return html;
                                }

                                var tableBody = $('#oos_review_details tbody');
                                var rowCount = tableBody.children('tr').length;
                                var newRow = generateTableRow(rowCount + 1);
                                tableBody.append(newRow);
                            });
                        });
                    </script>


                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Average of all six test results :</label>
                            <!-- <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div> -->
                            <textarea class="summernote" name="average_all_six_result[]" id="summernote-1">
                            {{$data->phase_iii_investigator}}
                                    </textarea>
                        </div>
                    </div>

                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Investigator</label>
                            <!-- <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div> -->
                            <input type="text" class="" name="phase_iii_investigator_result1" value="{{$data->phase_iii_investigator_result1}}" id="">
                        </div>
                    </div>

                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Conclusion By Head QC</label>
                            <textarea  name="conclusion_by_qc_head" id="">{{$data->conclusion_by_qc_head}}
                                    </textarea>
                        </div>
                    </div>

                    <div class="sub-head">PHASE -III (ADDITIONAL INVESTIGATION) (If Applicable- Yes/No) </div>


                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Impact assessment on other batches or product</label>                 
                                    <textarea class="summernote" name="impact_assessment_on_batches" id="summernote-1">
                                        {{ $data->impact_assessment_on_batches }}
                                    </textarea>        
                        </div>
                    </div>


                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Corrective Action</label>
                      
                                    <textarea class="summernote" name="phase_iii_corrective" id="summernote-1">
                                        {{ $data->phase_iii_corrective}}
                                    </textarea>           
                        </div>
                    </div>

                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Preventive Action (If any)</label>
                            <textarea class="summernote" name="phase_iii_preventive" id="summernote-1">
                                        {{ $data->phase_iii_preventive }}
                                    </textarea>   
                        </div>
                    </div>


                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Evaluation by Head Quality/Designee </label>
                            <textarea class="summernote" name="phase_iii_evaluation[]" id="summernote-1">
                                        {{$data->phase_iii_evaluation}}
                                    </textarea>   
                        </div>
                    </div>


                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Reference Recores">OutCome Of Phase III (additional) Investigation</label>
                            <select multiple id="reference_outcome" name="phase_iii_investigation[]">
                                <option value="">--Select---</option>
                                <option value="Reported OOS for closure">Reported OOS for closure</option>

                           </select>
                        </div>
                    </div>
                    <div class="sub-head">JUSTIFICATION FOR DELAY IN CLOSING (If Applicable- Yes/No) </div>

                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">OOS No. Product/Material</label>
                            <input type="text" class="" name="justification_delay_no" value="{{$data->justification_delay_no}}" id="">
                        </div>
                    </div>

                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Target closure date</label>
                            <input type="date" class="" name="justification_closure_date" value="{{$data->justification_closure_date}}" id="">
                        </div>
                    </div>

                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Extended date for closure</label>
                            <input type="date" class="" name="justification_extended_date" value="{{$data->justification_extended_date}}" id="">
                        </div>
                    </div>

                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Justification</label>
                            <textarea  type="text" class="" name="justification_text" value="" id="">{{$data->justification_text}}</textarea>
                        </div>
                    </div>

                    <div class="button-block">
                        <button type="submit" id="ChangesaveButton" class="saveButton">Save</button>
                        <button type="button" class="backButton" onclick="previousStep()">Back</button>
                        <button type="button" id="ChangeNextButton" class="nextButton"
                            onclick="nextStep()">Next</button>
                        <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white">
                                Exit </a> </button>
                    </div>

                </div>
            </div>
        </div>



        <!--Additional Testing Proposal  -->
        <div id="CCForm7" class="inner-block cctabcontent">
            <div class="inner-block-content">
                <div class="sub-head">
                    Additional Testing Proposal by QA
                </div>
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Review Comment</label>
                            <!-- <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div> -->
                            <textarea class="summernote" name="Description_Deviation[]" id="summernote-1">
                                    </textarea>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Report Attachments"> Additional Test Proposal </label>
                            <select>
                                <option>Enter Your Selection Here</option>
                                <option>Yes</option>
                                <option>No</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Reference Recores">Additional Test Reference.</label>
                            <select multiple id="" name="PhaseIIQCReviewProposedBy[]" id="">
                                <option value=""> Enter Your Selection Here</option>
                                <option value=""></option>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Audit Attachments"> Any Other Actions Required ?</label>
                            <select>
                                <option>Yes</option>
                                <option>No</option>

                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Reference Recores">Action Task Reference</label>
                            <select multiple id="" name="PhaseIIQCReviewProposedBy[]" id="">
                                <option value=""> Enter Your Selection Here</option>
                                <option value=""></option>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="group-input">
                            <label for="Reference Recores">Additional Testing Attachment </label>
                            <small class="text-primary">
                                Please Attach all relevant or supporting documents
                            </small>
                            <div class="file-attachment-field">
                                <div class="file-attachment-list" id="file_attach"></div>
                                <div class="add-btn">
                                    <div>Add</div>
                                    <input type="file" id="myfile" name="file_attach[]"
                                        oninput="addMultipleFiles(this, 'file_attach')" multiple>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="button-block">
                        <button type="submit" id="ChangesaveButton" class="saveButton">Save</button>
                        <button type="button" class="backButton" onclick="previousStep()">Back</button>
                        <button type="button" id="ChangeNextButton" class="nextButton"
                            onclick="nextStep()">Next</button>
                        <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white">
                                Exit </a> </button>
                    </div>
                </div>
            </div>
        </div>


        <!--OOS Conclusion  -->
        <div id="CCForm8" class="inner-block cctabcontent">
            <div class="inner-block-content">
                <div class="sub-head">
                    Conclusion Comments
                </div>
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Conclusion Comments</label>
                            <!-- <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div> -->
                            <textarea class="summernote" name="Description_Deviation[]" id="summernote-1">
                                    </textarea>
                        </div>
                    </div>


                    <!-- ---------------------------grid-1 -------------------------------- -->
                    <div class="group-input">
                        <label for="audit-agenda-grid">
                            Summary of OOS Test Results
                            <button type="button" name="audit-agenda-grid" id="oos_conclusion">+</button>
                            <span class="text-primary" data-bs-toggle="modal"
                                data-bs-target="#document-details-field-instruction-modal"
                                style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                (Launch Instruction)
                            </span>
                        </label>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="oos_conclusion_details" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th style="width: 4%">Row#</th>
                                        <th style="width: 16%">Analysis Detials</th>
                                        <th style="width: 16%">Hypo./Exp./Add.Test PR No.</th>
                                        <th style="width: 16%">Results</th>
                                        <th style="width: 16%">Analyst Name.</th>
                                        <th style="width: 16%">Remarks</th>




                                    </tr>
                                </thead>
                                <tbody>
                                    <td><input disabled type="text" name="serial[]" value="1"></td>
                                    <td><input type="text" name="Number[]"></td>
                                    <td><input type="number" name="Name[]"></td>
                                    <td><input type="text" name="Remarks[]"></td>
                                    <td><input type="text" name="Name[]"></td>
                                    <td><input type="text" name="Remarks[]"></td>



                                </tbody>

                            </table>
                        </div>
                    </div>



                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Report Attachments">Specification Limit </label>
                            <input type="string" name="string">
                        </div>
                    </div>




                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Audit Attachments">Results to be Reported</label>
                            <select>
                                <option value="">Initial</option>
                                <option value="">Retested Result</option>

                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Reference Recores">Final Reportable Results</label>
                            <input type="string" name="string">
                        </div>
                    </div>
                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Justification for Averaging Results</label>
                            <!-- <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div> -->
                            <textarea class="summernote" name="Description_Deviation[]" id="summernote-1">
                                    </textarea>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Reference Recores">OOS Stands </label>
                            <select>
                                <option value="">Valid</option>
                                <option value="">Invalid</option>



                            </select>
                        </div>
                    </div>




                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Audit Attachments">CAPA Required ?</label>
                            <select>
                                <option>Yes</option>
                                <option>No</option>


                            </select>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Reference Recores">CAPA Ref No.</label>
                            <select multiple id="" name="PhaseIIQCReviewProposedBy[]" id="">
                                <option value=""> Enter Your Selection Here</option>
                                <option value=""></option>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Justify if CAPA not Required ?</label>
                            <!-- <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div> -->
                            <textarea class="summernote" name="Description_Deviation[]" id="summernote-1">
                                    </textarea>
                        </div>
                    </div>
                  <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Audit Attachments">CAPA Required ?</label>
                            <select>
                                <option>Yes</option>
                                <option>No</option>


                            </select>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Reference Recores">Action Plan Ref.</label>
                            <select multiple id="" name="PhaseIIQCReviewProposedBy[]" id="">
                                <option value=""> Enter Your Selection Here</option>
                                <option value=""></option>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Justification for Delay</label>
                            <!-- <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div> -->
                            <textarea class="summernote" name="Description_Deviation[]" id="summernote-1">
                                    </textarea>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="group-input">
                            <label for="Reference Recores">Attachments if Any</label>
                            <small class="text-primary">
                                Please Attach all relevant or supporting documents
                            </small>
                            <div class="file-attachment-field">
                                <div class="file-attachment-list" id="file_attach"></div>
                                <div class="add-btn">
                                    <div>Add</div>
                                    <input type="file" id="myfile" name="file_attach[]"
                                        oninput="addMultipleFiles(this, 'file_attach')" multiple>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="button-block">
                        <button type="submit" id="ChangesaveButton" class="saveButton">Save</button>
                        <button type="button" class="backButton" onclick="previousStep()">Back</button>
                        <button type="button" id="ChangeNextButton" class="nextButton"
                            onclick="nextStep()">Next</button>
                        <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white">
                                Exit </a> </button>
                    </div>

                </div>
            </div>
        </div>


        <!--OOS Conclusion Review -->
        <div id="CCForm9" class="inner-block cctabcontent">
            <div class="inner-block-content">
                <div class="sub-head">
                    Conclusion Review Comments
                </div>
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Conclusion Review Comments</label>
                            <!-- <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div> -->
                            <textarea class="summernote" name="Description_Deviation[]" id="summernote-1">
                                    </textarea>
                        </div>
                    </div>


                    <!-- ---------------------------grid-1 ------"OOSConclusion_Review-------------------------- -->
                    <div class="group-input">
                        <label for="audit-agenda-grid">
                            Summary of OOS Test Results
                            <button type="button" name="audit-agenda-grid" id="oosconclusion_review">+</button>
                            <span class="text-primary" data-bs-toggle="modal"
                                data-bs-target="#document-details-field-instruction-modal"
                                style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                (Launch Instruction)
                            </span>
                        </label>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="oosconclusion_review_details" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th style="width: 4%">Row#</th>
                                        <th style="width: 16%">Material/Product Name</th>
                                        <th style="width: 16%">Batch No.(s) / A.R. No. (s)</th>
                                        <th style="width: 16%">Any Other Information</th>
                                        <th style="width: 16%">Action Taken on Affec.batch</th>





                                    </tr>
                                </thead>
                                <tbody>
                                    <td><input disabled type="text" name="serial[]" value="1"></td>
                                    <td><input type="text" name="Number[]"></td>
                                    <td><input type="text" name="Name[]"></td>
                                    <td><input type="text" name="Remarks[]"></td>
                                    <td><input type="text" name="Number[]"></td>




                                </tbody>

                            </table>
                        </div>
                    </div>


                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Action Taken on Affec.batch</label>
                            <!-- <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div> -->
                            <textarea class="summernote" name="Description_Deviation[]" id="summernote-1">
                                    </textarea>
                        </div>
                    </div>






                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Audit Attachments">CAPA Required ?</label>
                            <select>
                                <option>Yes</option>
                                <option>No</option>


                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Reference Recores">CAPA Refer.</label>
                            <select multiple id="" name="PhaseIIQCReviewProposedBy[]" id="">
                                <option value=""> Enter Your Selection Here</option>
                                <option value=""></option>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Audit Attachments">Action plan Required ?</label>
                            <select>
                                <option>Yes</option>
                                <option>No</option>


                            </select>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Reference Recores">Ref. Action Plan?</label>
                            <select>
                                <option>Yes</option>
                                <option>No</option>

                            </select>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Reference Recores">Action Task Reference</label>
                            <select multiple id="" name="PhaseIIQCReviewProposedBy[]" id="">
                                <option value=""> Enter Your Selection Here</option>
                                <option value=""></option>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>


                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Audit Attachments">Risk Assessment Required ?</label>
                            <select>
                                <option>Yes</option>
                                <option>No</option>

                            </select>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Reference Recores">Risk Assessment Ref.</label>
                            <select multiple id="" name="PhaseIIQCReviewProposedBy[]" id="">
                                <option value=""> Enter Your Selection Here</option>
                                <option value=""></option>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Justify if No Risk Assessment</label>
                            <!-- <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div> -->
                            <textarea class="summernote" name="Description_Deviation[]" id="summernote-1">
                                    </textarea>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Reference Recores">Conclusion Attachment</label>
                            <small class="text-primary">
                                Please Attach all relevant or supporting documents
                            </small>
                            <div class="file-attachment-field">
                                <div class="file-attachment-list" id="file_attach"></div>
                                <div class="add-btn">
                                    <div>Add</div>
                                    <input type="file" id="myfile" name="file_attach[]"
                                        oninput="addMultipleFiles(this, 'file_attach')" multiple>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Audit Attachments">CQ Approver</label>
                            <input type="text" name="name">
                        </div>
                    </div>

                    <div class="button-block">
                        <button type="submit" id="ChangesaveButton" class="saveButton">Save</button>
                        <button type="button" class="backButton" onclick="previousStep()">Back</button>
                        <button type="button" id="ChangeNextButton" class="nextButton"
                            onclick="nextStep()">Next</button>
                        <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white">
                                Exit </a> </button>
                    </div>
                </div>
            </div>
        </div>





        <!--CQ Review Comments -->
        <div id="CCForm10" class="inner-block cctabcontent">
            <div class="inner-block-content">
                <div class="sub-head">
                    CQ Review Comments
                </div>
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">CQ Review comments</label>
                            <!-- <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div> -->
                            <textarea class="summernote" name="Description_Deviation[]" id="summernote-1">
                                    </textarea>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Report Attachments"> CAPA Required ?</label>
                            <select>
                                <option>Enter Your Selection Here</option>
                                <option>Yes</option>
                                <option>No</option>
                            </select>
                        </div>
                    </div>


                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Reference Recores">Reference of CAPA</label>
                            <select multiple id="" name="PhaseIIQCReviewProposedBy[]" id="">
                                <option value=""> Enter Your Selection Here</option>
                                <option value=""></option>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Report Attachments"> Action plan Required ?</label>
                            <select>
                                <option>Enter Your Selection Here</option>
                                <option>Yes</option>
                                <option>No</option>
                            </select>
                        </div>
                    </div>




                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Reference Recores">Ref Action Plan</label>
                            <select multiple id="" name="PhaseIIQCReviewProposedBy[]" id="">
                                <option value=""> Enter Your Selection Here</option>
                                <option value=""></option>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="group-input">
                            <label for="Audit Attachments"> CQ Attachment</label>
                            <small class="text-primary">
                                Please Attach all relevant or supporting documents
                            </small>
                            <div class="file-attachment-field">
                                <div class="file-attachment-list" id="file_attach"></div>
                                <div class="add-btn">
                                    <div>Add</div>
                                    <input type="file" id="myfile" name="file_attach[]"
                                        oninput="addMultipleFiles(this, 'file_attach')" multiple>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="button-block">
                        <button type="submit" id="ChangesaveButton" class="saveButton">Save</button>
                        <button type="button" class="backButton" onclick="previousStep()">Back</button>
                        <button type="button" id="ChangeNextButton" class="nextButton"
                            onclick="nextStep()">Next</button>
                        <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white">
                                Exit </a> </button>
                    </div>
                </div>

            </div>
        </div>





        <!-- Batch Disposition -->
        <div id="CCForm11" class="inner-block cctabcontent">
            <div class="inner-block-content">
                <div class="sub-head">
                    Batch Disposition
                </div>
                <div class="row">



                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Audit Attachments">OOS Category</label>
                            <select>
                                <option>Enter Your Selection Here</option>
                                <option>Analyst Error</option>
                                <option>Instrument Error</option>
                                <option>Procedure Error</option>
                                <option>Product Related Error</option>
                                <option>Material Related Error</option>
                                <option>Other Error</option>

                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Reference Recores">Other's</label>
                            <input type="string" name="string">
                        </div>
                    </div>
                    <!-- <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Report Attachments">Required ? Action Plan? </label>
                                <input type="num" name="num">
                            </div>
                        </div> -->

                    <div class="col-12">
                        <div class="group-input">
                            <label for="Reference Recores">Material/Batch Release</label>
                            <select>
                                <option>Enter Your Selection Here</option>
                                <option>To Be Release</option>
                                <option>To Be Rejected</option>
                                <option>Other Action (Specify)</option>

                            </select>
                        </div>
                    </div>

                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Other Action (Specify)</label>
                            <!-- <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div> -->
                            <textarea class="summernote" name="Description_Deviation[]" id="summernote-1">
                                    </textarea>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="group-input">
                            <label for="Report Attachments">Field alert reference </label>
                            <input type="string" name="string">
                        </div>
                    </div>

                    <div class="sub-head">Assessment for batch disposition</div>

                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Other Parameters Results</label>
                            <!-- <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div> -->
                            <textarea class="summernote" name="Description_Deviation[]" id="summernote-1">
                                    </textarea>
                        </div>
                    </div>



                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Trend of Previous Batches</label>
                            <!-- <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div> -->
                            <textarea class="summernote" name="Description_Deviation[]" id="summernote-1">
                                    </textarea>
                        </div>

                    </div>
                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Stability Data</label>
                            <!-- <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div> -->
                            <textarea class="summernote" name="Description_Deviation[]" id="summernote-1">
                                    </textarea>
                        </div>
                    </div>
                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Process Validation Data</label>
                            <!-- <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div> -->
                            <textarea class="summernote" name="Description_Deviation[]" id="summernote-1">
                                    </textarea>
                        </div>
                    </div>
                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Method Validation </label>
                            <!-- <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div> -->
                            <textarea class="summernote" name="Description_Deviation[]" id="summernote-1">
                                    </textarea>
                        </div>
                    </div>
                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Any Market Complaints </label>
                            <!-- <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div> -->
                            <textarea class="summernote" name="Description_Deviation[]" id="summernote-1">
                                    </textarea>
                        </div>

                    </div>

                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Statistical Evaluation </label>
                            <!-- <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div> -->
                            <textarea class="summernote" name="Description_Deviation[]" id="summernote-1">
                                    </textarea>
                        </div>

                    </div>
                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Risk Analysis for Disposition </label>
                            <!-- <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div> -->
                            <textarea class="summernote" name="Description_Deviation[]" id="summernote-1">
                                    </textarea>
                        </div>

                    </div>
                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Conclusion </label>
                            <!-- <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div> -->
                            <textarea class="summernote" name="Description_Deviation[]" id="summernote-1">
                                    </textarea>
                        </div>

                    </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Reference Recores">Phase-III Inves. Required ?</label>
                            <select>
                                <option>Enter Your Selection Here</option>
                                <option>Yes</option>
                                <option>No</option>


                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Reference Recores">Phase-III Inves. Reference</label>
                            <select multiple id="" name="PhaseIIQCReviewProposedBy[]" id="">
                                <option value=""> Enter Your Selection Here</option>
                                <option value=""></option>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Justify for Delay in Activity</label>
                            <!-- <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div> -->
                            <textarea class="summernote" name="Description_Deviation[]" id="summernote-1">
                                    </textarea>
                        </div>

                    </div>
                    <div class="col-12">
                        <div class="group-input">
                            <label for="Reference Recores">Disposition Attachment</label>
                            <small class="text-primary">
                                Please Attach all relevant or supporting documents
                            </small>
                            <div class="file-attachment-field">
                                <div class="file-attachment-list" id="file_attach"></div>
                                <div class="add-btn">
                                    <div>Add</div>
                                    <input type="file" id="myfile" name="file_attach[]"
                                        oninput="addMultipleFiles(this, 'file_attach')" multiple>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="button-block">
                        <button type="submit" id="ChangesaveButton" class="saveButton">Save</button>
                        <button type="button" class="backButton" onclick="previousStep()">Back</button>
                        <button type="button" id="ChangeNextButton" class="nextButton"
                            onclick="nextStep()">Next</button>
                        <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white">
                                Exit </a> </button>
                    </div>
                </div>
            </div>

        </div>
        <!-- Re-Open -->
        <div id="CCForm12" class="inner-block cctabcontent">
            <div class="inner-block-content">
                <div class="sub-head">
                    Reopen Request
                </div>
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Reason for Re-open</label>
                            <!-- <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div> -->
                            <textarea class="summernote" name="Description_Deviation[]" id="summernote-1">
                                    </textarea>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="group-input">
                            <label for="Reference Recores">Reopen Attachment</label>
                            <small class="text-primary">
                                Please Attach all relevant or supporting documents
                            </small>
                            <div class="file-attachment-field">
                                <div class="file-attachment-list" id="file_attach"></div>
                                <div class="add-btn">
                                    <div>Add</div>
                                    <input type="file" id="myfile" name="file_attach[]"
                                        oninput="addMultipleFiles(this, 'file_attach')" multiple>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="button-block">
                        <button type="submit" id="ChangesaveButton" class="saveButton">Save</button>
                        <button type="button" class="backButton" onclick="previousStep()">Back</button>
                        <button type="button" id="ChangeNextButton" class="nextButton"
                            onclick="nextStep()">Next</button>
                        <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white">
                                Exit </a> </button>
                    </div>
                </div>
            </div>

        </div>


        <!-- Under Addendum Approval -->

        <div id="CCForm13" class="inner-block cctabcontent">
            <div class="inner-block-content">
                <div class="sub-head">
                    Addendum Approval Comment
                </div>
                <div class="row">

                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Reopen Approval Comments </label>
                            <!-- <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div> -->
                            <textarea class="summernote" name="Description_Deviation[]" id="summernote-1">
                                    </textarea>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="group-input">
                            <label for="Reference Recores">Addendum Attachment</label>
                            <small class="text-primary">
                                Please Attach all relevant or supporting documents
                            </small>
                            <div class="file-attachment-field">
                                <div class="file-attachment-list" id="file_attach"></div>
                                <div class="add-btn">
                                    <div>Add</div>
                                    <input type="file" id="myfile" name="file_attach[]"
                                        oninput="addMultipleFiles(this, 'file_attach')" multiple>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="button-block">
                        <button type="submit" id="ChangesaveButton" class="saveButton">Save</button>
                        <button type="button" class="backButton" onclick="previousStep()">Back</button>
                        <button type="button" id="ChangeNextButton" class="nextButton"
                            onclick="nextStep()">Next</button>
                        <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white">
                                Exit </a> </button>
                    </div>
                </div>
            </div>

        </div>

        <!--Under Addendum Execution -->
        <div id="CCForm14" class="inner-block cctabcontent">
            <div class="inner-block-content">
                <div class="sub-head">
                    Addendum Execution Comment
                </div>
                <div class="row">

                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Execution Comments</label>
                            <!-- <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div> -->
                            <textarea class="summernote" name="Description_Deviation[]" id="summernote-1">
                                    </textarea>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Reference Recores">Action Task Required ?</label>
                            <select>
                                <option>Enter Your Selection Here</option>
                                <option>Yes</option>
                                <option>No</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Reference Recores">Action Task Reference No.</label>
                            <select multiple id="" name="PhaseIIQCReviewProposedBy[]" id="">
                                <option value=""> Enter Your Selection Here</option>
                                <option value=""></option>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Reference Recores">Addi.Testing Required ?</label>
                            <select>
                                <option>Enter Your Selection Here</option>
                                <option>Yes</option>
                                <option>No</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Reference Recores">Addi.Testing Ref.</label>
                            <select multiple id="" name="PhaseIIQCReviewProposedBy[]" id="">
                                <option value=""> Enter Your Selection Here</option>
                                <option value=""></option>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Reference Recores">Investigation Required ?</label>
                            <select>
                                <option>Enter Your Selection Here</option>
                                <option>Yes</option>
                                <option>No</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Reference Recores">Investigation Ref.</label>
                            <select multiple id="" name="PhaseIIQCReviewProposedBy[]" id="">
                                <option value=""> Enter Your Selection Here</option>
                                <option value=""></option>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Reference Recores">Hypo-Exp Required ?</label>
                            <select>
                                <option>Enter Your Selection Here</option>
                                <option>Yes</option>
                                <option>No</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Reference Recores">Hypo-Exp Ref.</label>
                            <select multiple id="" name="PhaseIIQCReviewProposedBy[]" id="">
                                <option value=""> Enter Your Selection Here</option>
                                <option value=""></option>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="group-input">
                            <label for="Reference Recores">Addendum Attachments
                            </label>
                            <small class="text-primary">
                                Please Attach all relevant or supporting documents
                            </small>
                            <div class="file-attachment-field">
                                <div class="file-attachment-list" id="file_attach"></div>
                                <div class="add-btn">
                                    <div>Add</div>
                                    <input type="file" id="myfile" name="file_attach[]"
                                        oninput="addMultipleFiles(this, 'file_attach')" multiple>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="button-block">
                        <button type="submit" id="ChangesaveButton" class="saveButton">Save</button>
                        <button type="button" class="backButton" onclick="previousStep()">Back</button>
                        <button type="button" id="ChangeNextButton" class="nextButton"
                            onclick="nextStep()">Next</button>
                        <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white">
                                Exit </a> </button>
                    </div>
                </div>

            </div>

        </div>

        <!-- Under Addendum Review-->
        <div id="CCForm15" class="inner-block cctabcontent">
            <div class="inner-block-content">
                <div class="sub-head">
                    Under Addendum Review
                </div>
                <div class="row">

                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Addendum Review Comments</label>
                            <!-- <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div> -->
                            <textarea class="summernote" name="Description_Deviation[]" id="summernote-1">
                    </textarea>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="group-input">
                            <label for="Reference Recores">Required ? Attachment</label>
                            <small class="text-primary">
                                Please Attach all relevant or supporting documents
                            </small>
                            <div class="file-attachment-field">
                                <div class="file-attachment-list" id="file_attach"></div>
                                <div class="add-btn">
                                    <div>Add</div>
                                    <input type="file" id="myfile" name="file_attach[]"
                                        oninput="addMultipleFiles(this, 'file_attach')" multiple>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="button-block">
                        <button type="submit" id="ChangesaveButton" class="saveButton">Save</button>
                        <button type="button" class="backButton" onclick="previousStep()">Back</button>
                        <button type="button" id="ChangeNextButton" class="nextButton"
                            onclick="nextStep()">Next</button>
                        <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white">
                                Exit </a> </button>
                    </div>

                </div>

            </div>

        </div>


        <!-- Under Addendum Verification -->
        <div id="CCForm16" class="inner-block cctabcontent">
            <div class="inner-block-content">
                <div class="sub-head">
                    Addendum Verification Comment
                </div>
                <div class="row">

                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Verification Comments </label>
                            <!-- <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div> -->
                            <textarea class="summernote" name="Description_Deviation[]" id="summernote-1">
                    </textarea>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="group-input">
                            <label for="Reference Recores">Verification Attachment</label>
                            <small class="text-primary">
                                Please Attach all relevant or supporting documents
                            </small>
                            <div class="file-attachment-field">
                                <div class="file-attachment-list" id="file_attach"></div>
                                <div class="add-btn">
                                    <div>Add</div>
                                    <input type="file" id="myfile" name="file_attach[]"
                                        oninput="addMultipleFiles(this, 'file_attach')" multiple>
                                </div>
                            </div>

                        </div>
                    </div>


                    <div class="button-block">
                        <button type="submit" id="ChangesaveButton" class="saveButton">Save</button>
                        <button type="button" class="backButton" onclick="previousStep()">Back</button>
                        <button type="button" id="ChangeNextButton" class="nextButton"
                            onclick="nextStep()">Next</button>
                        <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white">
                                Exit </a> </button>
                    </div>

                </div>
            </div>
        </div>


        <!----- Signature ----->

        <div id="CCForm17" class="inner-block cctabcontent">
            <div class="inner-block-content">
                <div class="sub-head">
                    Activity Log
                </div>
                <div class="row">

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Audit Agenda">Preliminary Lab Inves. Done By</label>
                            <div class="static"></div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Audit Agenda">Preliminary Lab Inves. Done On</label>
                            <div class="Date"></div>
                        </div>
                    </div>


                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Audit Team">Pre. Lab Inv. Conclusion By</label>
                            <div class="static"></div>

                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Audit Team">Pre. Lab Inv. Conclusion On</label>
                            <div class="Date"></div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="group-input">
                            <label for="Audit Comments"> Pre.Lab Invest. Review By </label>
                            <div class="static"></div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Audit Attachments">Pre.Lab Invest. Review On</label>
                            <div class="Date"></div>
                        </div>
                    </div>


                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Audit Attachments">Phase II Invest. Proposed By</label>
                            <div class="static"></div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Audit Attachments">Phase II Invest. Proposed On</label>
                            <div class="Date"></div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Audit Response Completed By"> Phase II QC Review Done By</label>
                            <div class=" static"></div>

                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Audit Response Completed On">Phase II QC Review Done On</label>
                            <div class="date"></div>

                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Audit Attachments">Additional Test Proposed By</label>
                            <div class=" static"></div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Audit Attachments">Additional Test Proposed On</label>
                            <div class="date"></div>
                        </div>
                    </div>


                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Audit Attachments">OOS Conclusion Complete By</label>
                            <div class=" static"></div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Audit Attachments">OOS Conclusion Complete On</label>
                            <div class="date"></div>
                        </div>
                    </div>


                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Audit Attachments">CQ Review Done By</label>
                            <div class=" static"></div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Audit Attachments">CQ Review Done On</label>
                            <div class="date"></div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Audit Attachments">Disposition Decision Done by</label>
                            <div class=" static"></div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Audit Attachments">Disposition Decision Done On</label>
                            <div class="date"></div>
                        </div>
                    </div>


                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Reference Recores">Reopen Addendum Complete By

                            </label>
                            <div class=" static"></div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Reference Recores">Reopen Addendum Complete on

                            </label>
                            <div class="date"></div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Reference Recores">Addendum Approval Completed By

                            </label>
                            <div class=" static"></div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Reference Recores">Reopen Addendum Complete on

                            </label>
                            <div class="date"></div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Reference Recores">Addendum Execution Done By

                            </label>
                            <div class=" static"></div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Reference Recores">Addendum Execution Done On

                            </label>
                            <div class="date"></div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Reference Recores">Addendum Review Done By

                            </label>
                            <div class=" static"></div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Reference Recores">Addendum Review Done On

                            </label>
                            <div class="date"></div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Reference Recores">Verification Review Done By
                            </label>
                            <div class=" static"></div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Reference Recores">Verification Review Done On

                            </label>
                            <div class="date"></div>
                        </div>
                    </div>




                    <!-- ====================================================================== -->
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="submitted by">Submitted By :</label>
                            <div class="static"></div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="submitted on">Submitted On :</label>
                            <div class="Date"></div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="cancelled by">Cancelled By :</label>
                            <div class="static"></div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="cancelled on">Cancelled On :</label>
                            <div class="Date"></div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="More information Required ? By">More information Required ? By :</label>
                            <div class="static"></div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="More information Required ? On">More information Required ? On :</label>
                            <div class="Date"></div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="completed by">Completed By :</label>
                            <div class="static"></div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="completed on">Completed On :</label>
                            <div class="Date"></div>
                        </div>
                    </div>

                </div>

                <div class="button-block">
                    <button type="submit" id="ChangesaveButton" class="saveButton">Save</button>
                    <button type="button" class="backButton" onclick="previousStep()">Back</button>
                    <button type="button" id="ChangeNextButton" class="nextButton"
                        onclick="nextStep()">Next</button>
                    <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white">
                            Exit </a> </button>
                </div>
            </div>
        </div>
    </div>
    </div>
 </div>
</form>


    <script>
        VirtualSelect.init({
            ele: '#reference_record, #reference_record2,#reference_record1, #reference_outcome, #notify_to'
        });

        $('#summernote').summernote({
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear', 'italic']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });

        $('.summernote').summernote({
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear', 'italic']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });

        let referenceCount = 1;

        function addReference() {
            referenceCount++;
            let newReference = document.createElement('div');
            newReference.classList.add('row', 'reference-data-' + referenceCount);
            newReference.innerHTML = `
            <div class="col-lg-6">
                <input type="text" name="reference-text">
            </div>
            <div class="col-lg-6">
                <input type="file" name="references" class="myclassname">
            </div><div class="col-lg-6">
                <input type="file" name="references" class="myclassname">
            </div>
        `;
            let referenceContainer = document.querySelector('.reference-data');
            referenceContainer.parentNode.insertBefore(newReference, referenceContainer.nextSibling);
        }
    </script>

    <script>
        VirtualSelect.init({
            ele: '#facility_name, #group_name, #auditee, #audit_team'
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
    </script>
@endsection
