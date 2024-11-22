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
                    var formattedCurrentDate = currentDate.toISOString().split('T')[0].slice(0, 7);
                    var users = @json($users);
                    var html =
                    '<tr>' +
                        '<td><input disabled type="text" name="details_stability[' + serialNumber + '][serial]" value="' + serialNumber +
                        '"></td>' +
                        '<td><input type="text" name="details_stability[' + serialNumber + '][summary_of_previous_oos]" value=""></td>'+
                        '<td><input type="text" name="details_stability[' + serialNumber + '][capa_taken_for_oos]" value=""></td>' +
                        '<td><button type="text" class="removeRowBtn">Remove</button></td>' +

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
                        '<td><select name="info_product_material[' + serialNumber + '][info_specification_limit]"><option value="">--Select--</option><option value="Primary">Primary</option><option value="Secondary">Secondary</option><option value="Tertiary">Tertiary</option><option value="Not Applicable">Not Applicable</option></select></td>' +
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
    <form action="{{ route('oos_create') }}" method="post" enctype="multipart/form-data">
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
                                    <span class="text-danger">*</span></label>
                                    <span id="rchars">255</span>characters remaining
                                <input id="docname"  name="description_gi" maxlength="255" required>
                            </div>
                            @error('short_description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- <p id="docnameError" style="color:red">**Short Description is required</p> -->
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
                                Product/ Material Name (with Grade) 
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
                                        <tr>
                                            <td><input disabled type="text" name="info_product_material[0][serial]" value="1"></td>
                                            <td><input type="text" name="info_product_material[0][info_batch_no]" value=""></td>
                                            <td><input type="text" name="info_product_material[0][info_ar_no]" value=""></td>

                                            <td>
                                                <select name="info_product_material[0][info_stage]">
                                                <option value="">Enter Your Selection Here</option>
                                                    <option value="finished_product">Finished Product</option>
                                                    <option value="raw_material">Raw Material</option>
                                                    <option value="reaction_material">Reaction Material</option>
                                                    <option value="stability_study">Stability Study</option>
                                                    <option value="other">Other (Specify)</option>
                                                </select>
                                            </td>
                                            <td><input type="text" name="info_product_material[0][info_reference_specification_no]" value=""></td>
                                            <td><input type="text" name="info_product_material[0][info_test]" value=""></td>
                                            <td><input type="text" name="info_product_material[0][info_results_obtained]" value=""></td>
                                            <td><input type="text" name="info_product_material[0][info_specification_limit]" value=""></td>


                                            <td><button type="text" class="removeRowBtn">Remove</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                       


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
                                    @foreach ($IIB_inv_questions as $IIB_inv_question)
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
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        </div>
                        </div>



                        <div class="col-lg-12 mb-4">
                            <div class="group-input">
                                <label for="Audit Schedule Start Date"> Summary Of Discussion with analyst </label>
                                <div class="col-md-12 4">
                                    <div class="group-input">
                                        <textarea class="summernote" name="description_summary" id="summernote-1">
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
                                        <textarea class="summernote" name="Discussion_points" id="summernote-1">
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
                                    </textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 mb-4">
                            <div class="group-input">
                                <label for="Audit Schedule Start Date">Results Of Sample Analyzed in the same sequence (if any)</label>
                                <input type="text"  class="" name="simple_analyzed">
                            </div>
                        </div>

                        <div class="col-lg-12 mb-4">
                            <div class="group-input">
                                <label for="Audit Schedule Start Date">Investigator</label>
                                <input type="text" class="" name="investigator">
                            </div>
                        </div>

                       <!--  -->
                        <div class="group-input">
                            <label for="audit-agenda-grid">
                                Details Of Previous history Of Similar type (Same product, Same test) Of OOS Observed
                                <button type="button" name="audit-agenda-grid" id="details_tability">+</button>
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <td><input disabled type="text" name="details_stability[0][serial]" value="1"></td>
                                        <td><input type="text" name="details_stability[0][summary_of_previous_oos]" value=""></td>
                                        <td><input type="text" name="details_stability[0][capa_taken_for_oos]" value=""></td>
     
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
                                    </textarea>
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


            <!-- Preliminary Lab Inv. Conclusion -->
            <div id="CCForm3" class="inner-block cctabcontent">
                <div class="inner-block-content">
                    <div class="sub-head">PHASE -1(A) in case OBVIOUS ERROR is identified  (If Applicable - Yes/No.)</div>
                    <div class="row">

                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Lead Auditor">Root Cause</label>
                                <select name="root_cause_identified_plic">
                                    <!-- root_cause_identified_plic -->
                                    <option value="">Enter Your Selection Here</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
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
                                <textarea class="summernote" name="summary_of_prelim_investiga_plic" id="summernote-1"></textarea> 
                            </div>
                        </div>

                        <div class="col-md-12 mb-4">
                            <div class="group-input">
                                <label for="Description Deviation">Impact assessment </label>
                                <span class="text-primary">(To be decided in coordination with QA Head / Designation)</span>
                               <textarea class="summernote" name="re_sampling_ref_no_piii" id="summernote-1">
                                    </textarea>
                            </div>
                        </div>

                        <div class="col-md-12 mb-4">
                            <div class="group-input">
                                <label for="Description Deviation">Corrective Action</label>
                                <textarea class="summernote" name="corrective_action" id="summernote-1">
                                    </textarea>
                            </div>
                        </div>
                        <div class="col-md-12 mb-4">
                            <div class="group-input">
                                <label for="Description Deviation">Preventive Action (if any):</label>
                                <textarea class="summernote" name="preventive_action1A" id="summernote-1">
                                    </textarea>
                            </div>
                        </div>

                        <div class="col-md-12 mb-4">
                            <div class="group-input">
                                <label for="Description Deviation">Evaluation By Head Quality</label>
                                <!-- <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div> -->
                                <input type="text" class="evaluation_by_head_quality" name="" id="">
                                    
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
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12 mb-4">
                            <div class="group-input">
                                <label for="Description Deviation">Results of Hypothesis:</label>
                                <!-- <div><small class="text-primary">Hypothesis may vary depending on nature and test of OOS</small></div> -->
                                <textarea class="" name="results_hypothesis" id="">
                                    </textarea>
                            </div>
                        </div>

                        <div class="col-md-12 mb-4">
                            <div class="group-input">
                                <label for="Description Deviation">Evaluation Of Hypothesis & Comments</label>
                                <!-- <div><small class="text-primary">Hypothesis may vary depending on nature and test of OOS</small></div> -->
                                <textarea class="" name="evaluation_of_hypothesis_comments" id="">
                                    </textarea>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Lead Auditor">Hypothesis Root Cause</label>
                                <!-- <div class="text-primary">Please Choose the relevent units</div> -->
                                <select name="" id="hypothesis_root_cause" onchange="">
                                    <option value="">Enter Your Selection Here</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12 mb-4">
                            <div class="group-input">
                                <label for="Description Deviation">Impact assessment/Risk assessment (If applicable)</label>
                                <textarea class="summernote" name="impact_assessment_risk" id="summernote-1">
                                    </textarea>
                            </div>
                        </div>

                        <div class="col-md-12 mb-4">
                            <div class="group-input">
                                <label for="Description Deviation">Preventive action(if any)</label>
                                <textarea class="summernote" name="preventive_action_phase1b" id="summernote-1">
                                    </textarea>
                            </div>
                        </div>

                        <div class="col-md-12 mb-4">
                            <div class="group-input">
                                <label for="Description Deviation">Evaluation By Head Quality / Designee</label>
                                <!-- <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div> -->
                                <input type="text" class="" name="evaluation_by_head_quality" id="">
                                    
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
                                <select name="hypothesis_analysis_phase2">
                                    <option value="">Enter Your Selection Here</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12 mb-4">
                            <div class="group-input">
                                <label for="Description Deviation">Results of Hypothesis:</label>
                                <!-- <div><small class="text-primary">Hypothesis may vary depending on nature and test of OOS</small></div> -->
                                <textarea class="" name="results_hypothesis_phase2" id="">
                                    </textarea>
                            </div>
                        </div>

                        <div class="col-md-12 mb-4">
                            <div class="group-input">
                                <label for="Description Deviation">Evaluation Of Hypothesis & Comments</label>
                                <!-- <div><small class="text-primary">Hypothesis may vary depending on nature and test of OOS</small></div> -->
                                <textarea class="" name="evaluation_of_hypothesis_comments_phase2" id="">
                                    </textarea>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Lead Auditor">Root Cause</label>
                                <!-- <div class="text-primary">Please Choose the relevent units</div> -->
                                <select name="hypothesis_root_cause_phase2" id="" onchange="">
                                    <option value="">Enter Your Selection Here</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12 mb-4">
                            <div class="group-input">
                                <label for="Description Deviation">Impact assessment/Risk assessment (If applicable)</label>
                                <textarea class="summernote" name="impact_assessment_risk_phase2" id="summernote-1">
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
                                <!-- <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div> -->
                                <input type="text" class="" name="evaluation_by_head_quality_disignee_phase2" id="">
                                    
                            </div>
                        </div>

                        <!-- <div class="col-lg-6">
                            <div class="group-input">
                                <label for="Reference Recores">Outcome Of Phase II Extended Laboratory Investigation.</label>
                                <select multiple id="reference_record2" name="outcome_phase_ii_investigation2[]">
                                    <option value="">--Select---</option>
                                    <option value="Closure of OOS with CAPA">Closure of OOS with CAPA</option>
                                    <option value="Phase II">Phase II (Manufacturing investigation)</option>
                                </select>
                            </div>
                        </div> -->


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
                                                        <option value="Yes">Yes</option>
                                                        <option value="No">No</option>
                                                        <option value="N/A">N/A</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <textarea name="oos_detail[{{ $questionNumber }}][remark]" style="border-radius: 7px; border: 1px solid black; width: 100%;"></textarea>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @php $srNo++; @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    </div>



                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Brief Summary of Phase II Investigation</label>
                            <!-- <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div> -->
                            <textarea class="summernote" name="phase_ii_investigation" id="summernote-1">
                                    </textarea>
                        </div>
                    </div>

                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Brief Summary of Root Cause</label>
                            <!-- <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div> -->
                            <textarea class="summernote" name="brief_summary_root_cause" id="summernote-1">
                                    </textarea>
                        </div>
                    </div>

                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Brief Summary of Action taken/planned</label>
                            <!-- <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div> -->
                            <textarea class="summernote" name="brief_summary_taken_planned" id="summernote-1">
                                    </textarea>
                        </div>
                    </div>

                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Comment Of Head Quality/Designee</label>
                            <textarea class="summernote" name="comment_of_head_qualiry" id="summernote-1">
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
                            <input type="text" name="phaseiii_results">
                        </div>
                    </div>
                    
                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Report Attachments"> Limit</label>
                            <input type="text" name="phaseiii_limit">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Audit Attachments"> Conclusion </label>
                            <textarea class="" name="conclusion" id="">
                            </textarea>
                            <!-- <select name="conclusion">
                                <option value="complies">Complies</option>
                                <option value="does_not_complies">Does not Complies</option>
                            </select> -->
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
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                    </div>



                        <!-- <div class="group-input">
                            <label for="audit-agenda-grid">
                                Details Of Stability Study
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
                                            <th style="width: 8%"> Batch No*.</th>
                                            <th style="width: 12%">AR No.</th>
                                            <th style="width: 10%">Stage of Investigation</th>
                                            <th style="width: 12% pt-3">Quantity</th>
                                            <th style="width: 16% pt-2"> Authorized by / Head Quality</th>
                                            <th style="width: 8%">Sampled by</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input disabled type="text" name="details_stability[0][serial]" value="1"></td>
                                            <td><input type="text" name="details_stability[0][grid_arnumber]"></td>
                                            <td><input type="text" name="details_stability[0][grid_stage_investigation]"></td>
                                            <td><input type="text" name="details_stability[0][grid_quantity]"></td>
                                            <td><input type="text" name="details_stability[0][grid_authorized]"></td>
                                            <td><input type="text" name="details_stability[0][grid_]"></td>

                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div> -->

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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input disabled type="text" name="instrument_detail[0][serial]" value="1"></td>
                                            <td><input type="text" name="instrument_detail[0][grid_batch_no]"></td>
                                            <td><input type="text" name="instrument_detail[0][grid_arnumber]"></td>
                                            <td><input type="text" name="instrument_detail[0][grid_stage_investigation]"></td>
                                            <td><input type="text" name="instrument_detail[0][grid_quantity]"></td>
                                            <td><input type="text" name="instrument_detail[0][grid_authorized_by]"></td>
                                            <td><input type="text" name="instrument_detail[0][grid_sampled_by]"></td>
                                            <!-- <td><button type="text" class="removeRowBtn">Remove</button></td> -->
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Audit Attachments">Results </label>
                            <input type="text" name="material_results">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Audit Attachments"> Conclusion </label>
                            <textarea class="" name="material_conclusion" id="">
                            </textarea>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Reference Recores">Evaluation by Quality Head/Designee</label>
                            <select  name="evaluation_by_quality_designee" id="">
                                <option value=""> Enter Your Selection Here</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                            </select>
                        </div>
                    </div>
                    
                <!--<div class="col-lg-6">
                        <div class="group-input">
                            <label for="Audit Attachments"> Hypo/Exp. Required ?</label>
                            <select>
                                <option>Yes</option>
                                <option>No</option>

                            </select>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Reference Recores">Hypo/Exp. Reference  .</label>
                            <select multiple id="" name="PhaseIIQCReviewProposedBy[]" id="">
                                <option value=""> Enter Your Selection Here</option>
                                <option value=""></option>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="group-input">
                            <label for="Audit Attachments"> Attachment</label>
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
                                    <td><input disabled type="text" name="phase_iii_result[0][serial]" value="1"></td>
                                    <td><input type="text" name="phase_iii_result[0][phase_iii_result_set_1]"></td>
                                    <td><input type="text" name="phase_iii_result[0][phase_iii_result_set_2]"></td>
                                    <td><input type="text" name="phase_iii_result[0][phase_iii_result_set_3]"></td>
                                    <td><button type="text" class="removeRowBtn">Remove</button></td>
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
                            <!-- <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div> -->
                            <input type="text" class="" name="phase_iii_investigator" id="">
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
                                    <td><input disabled type="text" name="phase_iii_result_i[0][serial]" value="1"></td>
                                    <td><input type="text" name="phase_iii_result_i[0][phase_iii_result_i_set_1]"></td>
                                    <td><input type="text" name="phase_iii_result_i[0][phase_iii_result_i_set_2]"></td>
                                    <td><input type="text" name="phase_iii_result_i[0][phase_iii_result_i_set_3]"></td>
                                    <td><button type="text" class="removeRowBtn">Remove</button></td>
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
                            <textarea class="summernote" name="average_all_six_result" id="summernote-1">
                                    </textarea>
                        </div>
                    </div>

                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Investigator</label>
                            <!-- <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div> -->
                            <input type="text" class="" name="phase_iii_investigator_result1" id="">
                        </div>
                    </div>

                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Conclusion By Head QC</label>
                            <textarea class="" name="conclusion_by_qc_head" id="">
                                    </textarea>
                        </div>
                    </div>

                    <div class="sub-head">PHASE -III (ADDITIONAL INVESTIGATION) (If Applicable- Yes/No) </div>


                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Impact assessment on other batches or product</label>
                            <!-- <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div> -->
                            <textarea class="summernote" name="impact_assessment_on_batches" id="summernote-1">
                                    </textarea>
                        </div>
                    </div>

                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Corrective Action</label>
                            <textarea class="summernote" name="phase_iii_corrective" id="summernote-1">
                                    </textarea>
                        </div>
                    </div>
                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Preventive Action (If any)</label>
                            <textarea class="summernote" name="phase_iii_preventive" id="summernote-1">
                                    </textarea>
                        </div>
                    </div>

                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Evaluation by Head Quality/Designee </label>
                            <textarea class="summernote" name="phase_iii_evaluation" id="summernote-1">
                                    </textarea>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Reference Recores">OutCome Of Phase III (additional) Investigation</label>
                            <select multiple id="reference_outcome" name="phase_iii_investigation[]">
                                <option value="">--Select---</option>
                                <option value="Reported OOS for closure">Reported OOS for closure</option>
                                <!-- <option value="Recommended for Phase III investigation">Recommended for Phase III investigation</option>
                                <option value="Batch is recommended for R&D investigation followed by reprocessing, application for in process/Finished product.">Batch is recommended for R&D investigation followed by reprocessing, application for in process/Finished product.</option>
                                <option value="Material Reprocessing Authorization form">Material Reprocessing Authorization form</option> -->

                           </select>
                        </div>
                    </div>
                    <div class="sub-head">JUSTIFICATION FOR DELAY IN CLOSING (If Applicable- Yes/No) </div>

                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">OOS No. Product/Material</label>
                            <input type="text" class="" name="justification_delay_no" id="">
                        </div>
                    </div>

                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Target closure date</label>
                            <input type="date" class="" name="justification_closure_date" id="">
                        </div>
                    </div>

                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Extended date for closure</label>
                            <input type="date" class="" name="justification_extended_date" id="">
                        </div>
                    </div>

                    <div class="col-md-12 mb-4">
                        <div class="group-input">
                            <label for="Description Deviation">Justification</label>
                            <textarea  type="text" class="" name="justification_text" id=""></textarea>
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
