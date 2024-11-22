@extends('frontend.rcms.layout.main_rcms')
@section('rcms_container')

    <style>
        #step-form>div {
            display: none
        }

        #step-form>div:nth-child(1) {
            display: block;
        }
        .hide-input{
            display: none !important;
        }
    </style>
    <style>
        header .header_rcms_bottom {
            display: none;
        }

        .calenderauditee {
            position: relative;
        }

        .new-date-data-field .input-date input.hide-input {
            position: absolute;
            top: 0;
            left: 0;
            opacity: 0;
        }

        .new-date-data-field input {
            border: 1px solid grey;
            border-radius: 5px;
            padding: 5px 15px;
            display: block;
            width: 100%;
            background: white;
        }

        .calenderauditee input::-webkit-calendar-picker-indicator {
            width: 100%;
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
    <div id="rcms_form-head">
        <div class="container-fluid">
            <div class="inner-block">


                <div class="slogan">
                    <strong>Site Division / Project </strong>:
                    {{ Helpers::getDivisionName(session()->get('division')) }} / Change Control
                </div>
            </div>
        </div>
    </div>

    {{-- ======================================
                CHANGE CONTROL VIEW
    ======================================= --}}
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
                        {{-- <button class="button_theme1" onclick="window.print();return false;" class="new-doc-btn">Print</button> --}}
                        {{--  <button class="button_theme1"> <a class="text-white" href="{{ url('send-notification', $data->id) }}"> Send Notification </a> </button>  --}}

                        <button class="button_theme1"> <a class="text-white"
                                href="{{ url('rcms/audit-trial', $data->id) }}"> Audit Trail </a> </button>
                        {{-- @if ($data->stage >= 9)
                            <button class="button_theme1"> <a class="text-white" href="{{ url('rcms/eCheck', $data->id) }}">
                                    Close Done </a> </button>
                        @endif --}}
                        @if ($data->stage == 1  && (in_array(3, $userRoleIds) || in_array(18, $userRoleIds)))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Submit
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#rejection-modal">
                                Cancel
                            </button>
                        @elseif($data->stage == 2  && (in_array(4, $userRoleIds) || in_array(18, $userRoleIds)))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#child-modal">
                                Child
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                HOD Review Complete
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#rejection-modal">
                                More Info-required
                            </button>
                        @elseif($data->stage == 3  && (in_array(3, $userRoleIds) || in_array(18, $userRoleIds)))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Send to CFT/SME/QA Reviewers
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#cft-modal">
                                CFT/SME/QA Review Not Required
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#rejection-modal">
                                More Information required
                            </button>
                            {{-- <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#child-modal1">
                                Child
                            </button> --}}
                        @elseif($data->stage == 4  && (in_array(5, $userRoleIds) || in_array(18, $userRoleIds)))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Review Complete
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#rejection-modal">
                                Request More Info
                            </button>
                        @elseif($data->stage == 6  && (in_array(6, $userRoleIds) || in_array(18, $userRoleIds)))
                            @if ($evaluation->training_required == 'yes')
                                <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                    Training Completed
                                </button>
                            @endif
                        @elseif($data->stage == 7  && (in_array(3, $userRoleIds) || in_array(18, $userRoleIds)))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Implemented
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#child-modal1">
                                Child
                            </button>
                        @elseif($data->stage == 8)
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Final Review Complete
                            </button>
                        @endif
                        <button class="button_theme1"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}"> Exit
                            </a> </button>


                    </div>

                </div>
                <div class="status">
                    <div class="head">Current Status</div>
                    {{-- @if ($data->stage == 0)
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
                            {{--  @if ($data->stage >= 2)
                            <div class="active">Superviser Review</div>
                        @else
                            <div class="">Superviser Review</div>
                        @endif  --}}
                    {{-- @if ($data->stage >= 2)
                                <div class="active">Under Superviser Review </div>
                            @else
                                <div class="">Under Superviser Review </div>
                            @endif
                            @if ($info->Quality_Approver == 'yes')
                            @if ($data->stage >= 3)
                                <div class="active">QA Review</div>
                            @else
                                <div class="">QA Review</div>
                            @endif
                            @endif
                            @if ($info->Microbiology == 'yes')
                            @if ($data->stage >= 4)
                                <div class="active">Pending CFT Review</div>
                            @else
                                <div class="">Pending CFT Review</div>
                            @endif


                            @if ($data->stage >= 5)
                                <div class="active">CFT Review Completed</div>
                            @else
                                <div class="">CFT Review Completed</div>
                            @endif
                            @endif
                            @if ($evaluation->training_required == 'yes')
                                @if ($data->stage >= 6)
                                    <div class="active">Pending Training Completion</div>
                                @else
                                    <div class="">Pending Training Completion</div>
                                @endif
                            @endif

                            @if ($data->stage >= 7)
                                <div class="active">Pending Change Implementation</div>
                            @else
                                <div class="">Pending Change Implementation</div>
                            @endif
                            @if ($info->Quality_Approver == 'yes')
                            @if ($data->stage >= 8)
                                <div class="active">QA Final Review</div>
                            @else
                                <div class="">QA Final Review</div>
                            @endif
                            @endif

                            @if ($data->stage >= 9)
                                <div class="active">Closed - Done</div>
                            @else
                                <div class="">Closed - Done</div>
                            @endif


                        </div>
                    @endif --}}

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
                            {{--  @if ($data->stage >= 2)
                        <div class="active">Superviser Review</div>
                    @else
                        <div class="">Superviser Review</div>
                    @endif  --}}
                            @if ($data->stage >= 2)
                                <div class="active">Under HOD Review </div>
                            @else
                                <div class="">Under HOD Review </div>
                            @endif
                            {{-- @if ($info->Quality_Approver == 'yes') --}}
                            @if ($data->stage >= 3)
                                <div class="active">Pending CFT/SME/QA Review</div>
                            @else
                                <div class="">Pending CFT/SME/QA Review</div>
                            @endif
                            {{-- @endif
                            @if ($info->Microbiology == 'yes') --}}
                            @if ($data->stage >= 4)
                                <div class="active"> CFT/SME/QA Review</div>
                            @else
                                <div class=""> CFT/SME/QA Review</div>
                            @endif


                            {{-- @if ($data->stage >= 5)
                            <div class="active">CFT Review Completed</div>
                        @else
                            <div class="">CFT Review Completed</div>
                        {{-- @endif --}}
                            {{-- @endif --}}
                            {{-- @if ($evaluation->training_required == 'yes')
                            @if ($data->stage >= 6)
                                <div class="active">Pending Training Completion</div>
                            @else
                                <div class="">Pending Training Completion</div>
                            @endif
                        @endif --}}

                            @if ($data->stage >= 7)
                                <div class="active">Pending Change Implementation</div>
                            @else
                                <div class="">Pending Change Implementation</div>
                            @endif
                            @if ($data->stage >= 8)
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
                @php
                    $users = DB::table('users')->get();
                @endphp
                <div id="change-control-fields">
                    <div class="container-fluid">
                        <!-- Tab links -->
                        <div class="cctab">
                            <button class="cctablinks active" onclick="openCity(event, 'CCForm1')">General
                                Information</button>
                            <button class="cctablinks" onclick="openCity(event, 'CCForm2')">Change Details</button>
                            <button class="cctablinks" onclick="openCity(event, 'CCForm3')">QA Review</button>
                            <button class="cctablinks" onclick="openCity(event, 'CCForm4')">Evaluation</button>
                            {{-- <button class="cctablinks" onclick="openCity(event, 'CCForm5')">Additional Information</button> --}}
                            <button class="cctablinks" onclick="openCity(event, 'CCForm6')">Comments</button>
                            <button class="cctablinks" onclick="openCity(event, 'CCForm7')">Risk Assessment</button>
                            <button class="cctablinks" onclick="openCity(event, 'CCForm8')">QA Approval Comments</button>
                            <button class="cctablinks" onclick="openCity(event, 'CCForm9')">Change Closure</button>
                            <button class="cctablinks" onclick="openCity(event, 'CCForm10')">Activity Log</button>
                        </div>
                        <form id="CCFormInput" action="{{ route('CC.update', $data->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Tab content -->
                            <div id="step-form">

                                <div id="CCForm1" class="inner-block cctabcontent">
                                    <div class="inner-block-content">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="rls">CC No.</label>
                                                    <div class="static">
                                                        <input disabled type="text"
                                                            value=" {{ Helpers::getDivisionName($data->division_id) }}/CC/{{ date('Y') }}/{{ str_pad($data->record, 4, '0', STR_PAD_LEFT) }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="Division Code"><b>Division Code</b></label>
                                                    <input disabled type="text" name="division_code"
                                                        value=" {{ Helpers::getDivisionName($data->division_id) }}">
                                                
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="Initiator">Initiator</label>
                                                    <div class="static"><input disabled type="text"
                                                            value="{{ Auth::user()->name }}"></div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="date_initiation">Date of Initiation</label>
                                                    <div class="static"><input disabled type="text"
                                                            value="{{ date('d-M-Y') }}"></div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="group-input">
                                                    <label for="due-date">Due Date <span class="text-danger"></span></label>
                                                    <div><small class="text-primary">If revising Due Date, kindly mention revision reason in "Due Date Extension Justification" data field.</small></div>
                                                    <input readonly type="text"
                                                        value="{{ Helpers::getdateFormat($data->due_date) }}"
                                                        name="due_date" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}> 
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="Initiator Group"><b>Department</b><span class="text-danger">*</span></label>
                                                    <select name="Initiator_Group" id="initiator_group" required onchange="showOtherInput()">
                                                        <option value="">-- Select --</option>
                                                        <option value="Production" @if ($data->Initiator_Group == 'Production') selected @endif>Production</option>
                                                        <option value="Warehouse" @if ($data->Initiator_Group == 'Warehouse') selected @endif>Warehouse</option>
                                                        <option value="Quality Control" @if ($data->Initiator_Group == 'Quality Control') selected @endif>Quality Control</option>
                                                        <option value="Engineering" @if ($data->Initiator_Group == 'Engineering') selected @endif>Engineering</option>
                                                        <option value="Information Technology" @if ($data->Initiator_Group == 'Information Technology') selected @endif>Information Technology</option>
                                                        <option value="Project Management" @if ($data->Initiator_Group == 'Project Management')selected @endif>Project Management</option>
                                                        <option value="Environment Health & Safety" @if ($data->Initiator_Group == 'Environment Health & Safety') selected @endif>Environment Health & Safety</option>
                                                        <option value="Human Resource & Administration" @if ($data->Initiator_Group == 'Human Resource & Administration') selected @endif>Human Resource & Administration</option>
                                                        <option value="Quality Assurance" @if ($data->Initiator_Group == 'Quality Assurance') selected @endif>Quality Assurance</option>
                                                        <option value="Analytical Development Library" @if ($data->Initiator_Group == 'Analytical Development Library') selected @endif>Analytical Development Library</option>
                                                        <option value="Process Development Laboratory / Kilo Lab" @if ($data->Initiator_Group == 'Process Development Laboratory / Kilo Lab')selected @endif>Process Development Laboratory / Kilo Lab</option>
                                                        <option value="Technology transfer/design" @if ($data->Initiator_Group == 'Technology transfer/design') selected @endif>Technology transfer/design</option>
                                                        <option value="Any Other" @if ($data->Initiator_Group == 'Any Other') selected @endif>Any Other</option>
                                                    </select>
                                                    @error('Initiator_Group')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            
                                                <div class="group-input" id="other_input_group" style="display: none;">
                                                    <label for="Other Department"><b>Department (Any Other)</b><span class="text-danger">*</span></label>
                                                    <input type="text" name="departments_other" id="other_department" value="{{ old('departments_other') }}" />
                                                    @error('departments_other')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            
                                            <script>
                                                function showOtherInput() {
                                                    var initiatorGroup = document.getElementById('initiator_group').value;
                                                    var otherInputGroup = document.getElementById('other_input_group');
                                            
                                                    if (initiatorGroup === 'Any Other') {
                                                        otherInputGroup.style.display = 'block';
                                                    } else {
                                                        otherInputGroup.style.display = 'none';
                                                    }
                                                }
                                            
                                                // Trigger showOtherInput function on page load to check for previously selected value
                                                document.addEventListener('DOMContentLoaded', function() {
                                                    showOtherInput();
                                                });
                                            </script>
                                            

                                            <script>
                                                function showOtherInput() {
                                                    const initiatorGroup = document.getElementById('initiator_group').value;
                                                    const otherInputGroup = document.getElementById('other_input_group');
                                                    const otherDepartmentInput = document.getElementById('other_department');
                                            
                                                    if (initiatorGroup === 'Any Other') {
                                                        otherInputGroup.style.display = 'block';
                                                        otherDepartmentInput.required = true;
                                                    } else {
                                                        otherInputGroup.style.display = 'none';
                                                        otherDepartmentInput.required = false;
                                                        otherDepartmentInput.value = ''; // Clear the input if not required
                                                    }
                                                }
                                            
                                                // Trigger the function on page load to handle old input
                                                document.addEventListener('DOMContentLoaded', function () {
                                                    showOtherInput();
                                                });
                                            </script>

                                            <div class="col-12">
                                                <div class="group-input">
                                                    <label for="Short Description">Short Description<span
                                                            class="text-danger">*</span></label><span id="rchars"  class="text-primary">255 </span><span class="text-primary"> characters remaining</span>
                                                    <textarea name="short_description"   id="docname" type="text"    maxlength="255" required  {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->short_description }}</textarea>
                                                </div>
                                                <p id="docnameError" style="color:red">**Short Description is required</p>
                                            </div>
                                            
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="audit type"><b>Type of Change Requested</b></label>
                                                    <select multiple 
                                                        name="audit_type[]" 
                                                        id="audit_type"
                                                        {{ $data->stage == 0 || $data->stage == 11 ? 'disabled' : '' }}>
                                                        <option 
                                                            value="Facilities" 
                                                            {{ strpos($data->audit_type, 'Facilities') !== false ? 'selected' : '' }}>
                                                            Facilities
                                                        </option>
                                                        <option 
                                                            value="Equipment/utilities/Instrument" 
                                                            {{ strpos($data->audit_type, 'Equipment/utilities/Instrument') !== false ? 'selected' : '' }}>
                                                            Equipment/Utilities/Instrument
                                                        </option>
                                                        <option 
                                                            value="Environmental" 
                                                            {{ strpos($data->audit_type, 'Environmental') !== false ? 'selected' : '' }}>
                                                            Environmental
                                                        </option>
                                                        <option 
                                                            value="Statutory Compliances" 
                                                            {{ strpos($data->audit_type, 'Statutory Compliances') !== false ? 'selected' : '' }}>
                                                            Statutory Compliances
                                                        </option>
                                                        <option 
                                                            value="Manufacturing formula/process optimization" 
                                                            {{ strpos($data->audit_type, 'Manufacturing Formula/Process Optimization') !== false ? 'selected' : '' }}>
                                                            Manufacturing Formula/Process Optimization
                                                        </option>
                                                        <option 
                                                            value="Change in Batch size" 
                                                            {{ strpos($data->audit_type, 'Change in Batch size') !== false ? 'selected' : '' }}>
                                                            Change in Batch size
                                                        </option>
                                                        <option 
                                                            value="Yield Improvement" 
                                                            {{ strpos($data->audit_type, 'Yield Improvement') !== false ? 'selected' : '' }}>
                                                            Yield Improvement
                                                        </option>
                                                        <option 
                                                            value="Time Reduction" 
                                                            {{ strpos($data->audit_type, 'Time Reduction') !== false ? 'selected' : '' }}>
                                                            Time Reduction
                                                        </option>
                                                        <option 
                                                            value="Better Quality/Impurity Profile" 
                                                            {{ strpos($data->audit_type, 'Better Quality/Impurity Profile') !== false ? 'selected' : '' }}>
                                                            Better Quality/Impurity Profile
                                                        </option>
                                                        <option 
                                                            value="Documentation" 
                                                            {{ strpos($data->audit_type, 'Documentation') !== false ? 'selected' : '' }}>
                                                            Documentation
                                                        </option>
                                                        <option 
                                                            value="Specifications, Test Procedures" 
                                                            {{ strpos($data->audit_type, 'Specifications, Test Procedures') !== false ? 'selected' : '' }}>
                                                            Specifications, Test Procedures
                                                        </option>
                                                        <option 
                                                            value="Introduction of new vendor/supplier" 
                                                            {{ strpos($data->audit_type, 'Introduction of new vendor/supplier') !== false ? 'selected' : '' }}>
                                                            Introduction of new vendor/supplier
                                                        </option>
                                                        <option 
                                                            value="Introduction of new product" 
                                                            {{ strpos($data->audit_type, 'Introduction of new product') !== false ? 'selected' : '' }}>
                                                            Introduction of new product
                                                        </option>
                                                        <option 
                                                            value="Raw and Packaging Materials" 
                                                            {{ strpos($data->audit_type, 'Raw and Packaging Materials') !== false ? 'selected' : '' }}>
                                                            Water System
                                                        </option>
                                                        <option 
                                                            value="Others(Specify)" 
                                                            {{ strpos($data->audit_type, 'Others(Specify)') !== false ? 'selected' : '' }}>
                                                            Others(Specify)
                                                        </option>
                                                    </select>
                                                </div>
                                                @error('audit_type')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            

                                            <div class="col-12">
                                                <div class="group-input">
                                                    <label for="Title"><b>Title</b></label>
                                                    <span id="rchars" class="text-primary">(Brief description for Type of Change)</span>
                                                    <input 
                                                        id="docname" 
                                                        type="text" 
                                                        name="title" 
                                                        value="{{ old('title', $data->title ?? '') }}" 
                                                        {{ $data->stage == 0 || $data->stage == 11 ? 'readonly' : '' }}>
                                                </div>
                                                @error('title')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            

                                            <div class="col-12">
                                                <div class="group-input">
                                                    <label for="Document Number"><b>Document Number</b></label>
                                                    <input 
                                                        id="docname" 
                                                        type="text" 
                                                        name="doc_no" 
                                                        value="{{ old('doc_no', $data->doc_no ?? '') }}" 
                                                        {{ $data->stage == 0 || $data->stage == 11 ? 'readonly' : '' }}>
                                                </div>
                                                @error('doc_no')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            

                                            <div class="col-md-12 mb-3">
                                                <div class="group-input">
                                                    <label for="Existing_Stage"><b>Existing Stage / System</b></label>
                                                    <div>
                                                        <small class="text-primary">Please insert "NA" in the data field if it does not require completion</small>
                                                    </div>
                                                    <textarea 
                                                        name="Existing_Stage[]" 
                                                        id="summernote-1"
                                                        {{ $data->stage == 0 || $data->stage == 11 ? 'readonly' : '' }}
                                                    >{{ old('Existing_Stage', $data->Existing_Stage ?? '') }}</textarea>
                                                </div>
                                                @error('Existing_Stage.0')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            

                                            <div class="col-md-12 mb-3">
                                                <div class="group-input">
                                                    <label for="Proposed_changes"><b>Proposed Changes</b></label>
                                                    <div>
                                                        <small class="text-primary">Please insert "NA" in the data field if it does not require completion</small>
                                                    </div>
                                                    <textarea 
                                                        name="Proposed_changes[]" 
                                                        id="summernote-1" 
                                                        {{ $data->stage == 0 || $data->stage == 11 ? 'readonly' : '' }}
                                                    >{{ old('Proposed_changes', $data->Proposed_changes ?? '') }}</textarea>
                                                </div>
                                                @error('Proposed_changes.0')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            

                                            <div class="col-md-12 mb-3">
                                                <div class="group-input">
                                                    <label for="justification_changes"><b>Justification for Change</b></label>
                                                    <div>
                                                        <small class="text-primary">Please insert "NA" in the data field if it does not require completion</small>
                                                    </div>
                                                    <textarea 
                                                        name="justification_changes[]" 
                                                        id="summernote-1" 
                                                        {{ $data->stage == 0 || $data->stage == 11 ? 'readonly' : '' }}
                                                    >{{ old('justification_changes', $data->justification_changes ?? '') }}</textarea>
                                                </div>
                                                @error('justification_changes.0')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            

                                            <div class="col-md-12 mb-3">
                                                <div class="group-input">
                                                    <label for="review_initiating"><b>Review by-Initiating Department Head</b></label>
                                                    <div>
                                                        <small class="text-primary">Please insert "NA" in the data field if it does not require completion</small>
                                                    </div>
                                                    <textarea 
                                                        name="review_initiating[]" 
                                                        id="summernote-1" 
                                                        {{ $data->stage == 0 || $data->stage == 11 ? 'readonly' : '' }}
                                                    >{{ old('review_initiating', $data->review_initiating ?? '') }}</textarea>
                                                </div>
                                                @error('review_initiating.0')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            

                                            <div class="col-md-12 mb-3">
                                                <div class="group-input">
                                                    <label for="impact_assessment">
                                                        <b>Impact Assessment By QA Executive / Designee in consultation with Head Quality</b>
                                                    </label>
                                            
                                                    <!-- Impact on Qualification -->
                                                    <div class="mb-3" style="display: flex; align-items: center; gap: 15px;">
                                                        <label style="margin: 0;"><strong>i) Impact on:</strong></label>
                                                        <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                                                            @foreach(['Qualification', 'Calibration', 'Validation', 'Stability'] as $item)
                                                                <span style="display: inline-flex; align-items: center; gap: 5px;">
                                                                    <input 
                                                                        type="checkbox" 
                                                                        id="impact_{{ $item }}" 
                                                                        name="impact_on[]" 
                                                                        value="{{ $item }}" 
                                                                        style="vertical-align: middle; position: relative; bottom: 1px;"
                                                                        {{ in_array($item, is_array($data->impact_on ?? null) ? $data->impact_on : explode(',', $data->impact_on ?? '')) ? 'checked' : '' }}>
                                                                    <label for="impact_{{ $item }}" style="margin: 0; display: block;">{{ $item }}</label>
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    
                                            
                                                    <!-- Impact on Facility -->
                                                    <div class="mb-3" style="display: flex; align-items: center; gap: 15px;">
                                                        <label style="margin: 0;"><strong>ii) Impact on:</strong></label>
                                                        <div style="display: flex; gap: 10px;">
                                                            @foreach(['Facility', 'Equipment', 'Instrument'] as $item)
                                                                <span style="display: inline-flex; align-items: center; gap: 5px;">
                                                                    <input 
                                                                        type="checkbox" 
                                                                        id="impact_facility_{{ $item }}" 
                                                                        name="impact_on_facility[]" 
                                                                        value="{{ $item }}" 
                                                                        {{ in_array($item, is_array($data->impact_on_facility ?? null) ? $data->impact_on_facility : explode(',', $data->impact_on_facility ?? '')) ? 'checked' : '' }}>
                                                                    <label for="impact_facility_{{ $item }}">{{ $item }}</label>
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    
                                            
                                                    <!-- Impact on Documents -->
                                                    <div class="mb-3" style="display: flex; align-items: flex-start; gap: 15px;">
                                                        <label style="margin: 0; white-space: nowrap;"><strong>iii) Impact on Documents:</strong></label>
                                                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                                            @foreach(['SOP', 'Specification', 'AMV', 'Protocols', 'Train', 'BMCR/BPCR'] as $item)
                                                                <span style="display: inline-flex; align-items: center; gap: 5px; margin-bottom: 5px;">
                                                                    <input 
                                                                        type="checkbox" 
                                                                        id="impact_documents_{{ $item }}" 
                                                                        name="impact_on_documents[]" 
                                                                        value="{{ $item }}" 
                                                                        {{ in_array($item, is_array($data->impact_on_documents ?? null) ? $data->impact_on_documents : explode(',', $data->impact_on_documents ?? '')) ? 'checked' : '' }}>
                                                                    <label for="impact_documents_{{ $item }}">{{ $item }}</label>
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    
                                            
                                                    <!-- Risk Assessment -->
                                                    <div class="mb-3">
                                                        <label><strong>iv) Risk Assessment:</strong></label>
                                                        <div style="display: inline-flex; gap: 15px; align-items: center; margin-left: 10px;">
                                                            <div>
                                                                <input type="radio" id="risk_yes" name="risk_assessment" value="Yes" 
                                                                    {{ old('risk_assessment', $data->risk_assessment ?? '') == 'Yes' ? 'checked' : '' }} required>
                                                                <label for="risk_yes">Yes</label>
                                                            </div>
                                                            <div>
                                                                <input type="radio" id="risk_no" name="risk_assessment" value="No" 
                                                                    {{ old('risk_assessment', $data->risk_assessment ?? '') == 'No' ? 'checked' : '' }} required>
                                                                <label for="risk_no">No</label>
                                                            </div>
                                                        </div>
                                                        <textarea 
                                                            name="risk_justification" 
                                                            id="risk_justification" 
                                                            placeholder="If No, provide justification" 
                                                            style="display: {{ old('risk_assessment', $data->risk_assessment ?? '') == 'No' ? 'block' : 'none' }}; margin-top: 10px;" 
                                                            aria-describedby="risk_help">{{ old('risk_justification', $data->risk_justification ?? '') }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                                                                       
                                                    <script>
                                                        // Show/Hide the justification textarea based on the radio button selection
                                                        document.querySelectorAll('input[name="risk_assessment"]').forEach((elem) => {
                                                            elem.addEventListener("change", function() {
                                                                var justificationField = document.getElementById('risk_justification');
                                                                if (document.getElementById('risk_no').checked) {
                                                                    justificationField.style.display = 'block';  // Show textarea if 'No' is selected
                                                                } else {
                                                                    justificationField.style.display = 'none';  // Hide textarea if 'Yes' is selected
                                                                }
                                                            });
                                                        });
                                                    </script>
                                            
                                                    <!-- Others -->
                                                    <div class="mb-3">
                                                        <label><strong>v) Others (Please specify):</strong></label>
                                                        <textarea 
                                                            name="others" 
                                                            class="form-control mt-2" 
                                                            placeholder="Specify other impacts" 
                                                            rows="3" 
                                                            style="resize: vertical;">{{ old('others', $data->others ?? '') }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            

                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="identification_cross_funct">Identification of Cross-functional departments by QA for review of change proposal & Impact</label>
                                                    <select name="identification_cross_funct" id="identification_cross_funct" class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="Stores" {{ old('identification_cross_funct', $data->identification_cross_funct ?? '') == 'Stores' ? 'selected' : '' }}>Stores</option>
                                                        <option value="Production" {{ old('identification_cross_funct', $data->identification_cross_funct ?? '') == 'Production' ? 'selected' : '' }}>Production</option>
                                                        <option value="Maintenance" {{ old('identification_cross_funct', $data->identification_cross_funct ?? '') == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                                                        <option value="Administration" {{ old('identification_cross_funct', $data->identification_cross_funct ?? '') == 'Administration' ? 'selected' : '' }}>Administration</option>
                                                        <option value="QA" {{ old('identification_cross_funct', $data->identification_cross_funct ?? '') == 'QA' ? 'selected' : '' }}>QA</option>
                                                        <option value="QC" {{ old('identification_cross_funct', $data->identification_cross_funct ?? '') == 'QC' ? 'selected' : '' }}>QC</option>
                                                        <option value="EHS" {{ old('identification_cross_funct', $data->identification_cross_funct ?? '') == 'EHS' ? 'selected' : '' }}>EHS</option>
                                                        <option value="IT" {{ old('identification_cross_funct', $data->identification_cross_funct ?? '') == 'IT' ? 'selected' : '' }}>IT</option>
                                                        <option value="GM Works/VP Technical" {{ old('identification_cross_funct', $data->identification_cross_funct ?? '') == 'GM Works/VP Technical' ? 'selected' : '' }}>GM Works/VP Technical</option>
                                                        <option value="Regulatory Affairs" {{ old('identification_cross_funct', $data->identification_cross_funct ?? '') == 'Regulatory Affairs' ? 'selected' : '' }}>Regulatory Affairs</option>
                                                        <option value="R & D" {{ old('identification_cross_funct', $data->identification_cross_funct ?? '') == 'R & D' ? 'selected' : '' }}>R & D</option>
                                                        <option value="Others" {{ old('identification_cross_funct', $data->identification_cross_funct ?? '') == 'Others' ? 'selected' : '' }}>Others</option>
                                                    </select>
                                            
                                                    @error('identification_cross_funct')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            
                                            <div class="col-12" id="actionsPlanGroup">
                                                <div class="group-input">
                                                    <label for="ActionsPlan">
                                                        Actions Plan, Tracking, Verification, and Closure
                                                        <button type="button" name="addActionRow" id="addActionRowButton">+</button>
                                                    </label>
                                                    <table class="table table-bordered" id="actionsPlanTable">
                                                        <thead>
                                                            <tr>
                                                                <th>Sr. No.</th>
                                                                <th>Description of Action</th>
                                                                <th>Responsible Department</th>
                                                                <th>Planned Completion Date</th>
                                                                <th>Actual Completion Date</th>
                                                                <th>Evidence Attached (Y/N)</th>
                                                                <th>HOD Sign & Date</th>
                                                                <th>QA Verification (Sign & Date)</th>
                                                                <th>Reference Annexures</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if (is_array($actionsPlanGridData) && !empty($actionsPlanGridData))
                                                                @foreach($actionsPlanGridData as $index => $action)
                                                                    <tr>
                                                                        <td>{{ $loop->iteration }}</td>
                                                                        <td><input type="text" name="action_description[]" class="form-control" value="{{ old('action_description.' . $index, $action['action_description']) }}" required></td>
                                                                        <td><input type="text" name="responsible_department[]" class="form-control" value="{{ old('responsible_department.' . $index, $action['responsible_department']) }}" required></td>
                                                                        <td><input type="date" name="planned_date[]" class="form-control" value="{{ old('planned_date.' . $index, $action['planned_date']) }}" required></td>
                                                                        <td><input type="date" name="actual_date[]" class="form-control" value="{{ old('actual_date.' . $index, $action['actual_date']) }}"></td>
                                                                        <td>
                                                                            <select name="evidence_attached[]" class="form-control" required>
                                                                                <option value="Yes" {{ old('evidence_attached.' . $index, $action['evidence_attached']) == 'Yes' ? 'selected' : '' }}>Yes</option>
                                                                                <option value="No" {{ old('evidence_attached.' . $index, $action['evidence_attached']) == 'No' ? 'selected' : '' }}>No</option>
                                                                            </select>
                                                                        </td>
                                                                        <td><input type="text" name="hod_sign_date[]" class="form-control" value="{{ old('hod_sign_date.' . $index, $action['hod_sign_date']) }}"></td>
                                                                        <td><input type="text" name="qa_verification[]" class="form-control" value="{{ old('qa_verification.' . $index, $action['qa_verification']) }}"></td>
                                                                        <td><input type="text" name="reference_annexures[]" class="form-control" value="{{ old('reference_annexures.' . $index, $action['reference_annexures']) }}"></td>
                                                                    </tr>
                                                                @endforeach
                                                            @else
                                                                <!-- Default empty row -->
                                                                <tr>
                                                                    <td>1</td>
                                                                    <td><input type="text" name="action_description[]" class="form-control" placeholder="Description of Action" required></td>
                                                                    <td><input type="text" name="responsible_department[]" class="form-control" placeholder="Responsible Department" required></td>
                                                                    <td><input type="date" name="planned_date[]" class="form-control" required></td>
                                                                    <td><input type="date" name="actual_date[]" class="form-control"></td>
                                                                    <td>
                                                                        <select name="evidence_attached[]" class="form-control" required>
                                                                            <option value="Yes">Yes</option>
                                                                            <option value="No">No</option>
                                                                        </select>
                                                                    </td>
                                                                    <td><input type="text" name="hod_sign_date[]" class="form-control" placeholder="HOD Sign & Date"></td>
                                                                    <td><input type="text" name="qa_verification[]" class="form-control" placeholder="QA Verification"></td>
                                                                    <td><input type="text" name="reference_annexures[]" class="form-control" placeholder="Reference Annexures"></td>
                                                                </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                                                                        
                                            
                                            <script>
                                               document.addEventListener("DOMContentLoaded", function() {
                                                // Set initial row count based on existing data
                                                let rowCount = {{ isset($actionsPlans) ? count($actionsPlans) : 0 }};

                                                // Get references to the "Add Row" button and the table body
                                                const addActionRowButton = document.getElementById("addActionRowButton");
                                                const tableBody = document.querySelector("#actionsPlanTable tbody");

                                                // Add event listener for the "Add Row" button
                                                addActionRowButton.addEventListener("click", function() {
                                                    rowCount++; // Increment row count

                                                    // Create a new table row with appropriate input fields
                                                    const newRow = document.createElement("tr");
                                                    newRow.innerHTML = `
                                                        <td class="text-center">${rowCount}</td>
                                                        <td><input type="text" name="action_description[]" class="form-control" required></td>
                                                        <td><input type="text" name="responsible_department[]" class="form-control" required></td>
                                                        <td><input type="date" name="planned_date[]" class="form-control" required></td>
                                                        <td><input type="date" name="actual_date[]" class="form-control"></td>
                                                        <td>
                                                            <select name="evidence_attached[]" class="form-control" required>
                                                                <option value="">Select</option>
                                                                <option value="Yes">Yes</option>
                                                                <option value="No">No</option>
                                                            </select>
                                                        </td>
                                                        <td><input type="text" name="hod_sign_date[]" class="form-control"></td>
                                                        <td><input type="text" name="qa_verification[]" class="form-control"></td>
                                                        <td><input type="text" name="reference_annexures[]" class="form-control"></td>
                                                    `;

                                                    // Append the new row to the table body
                                                    tableBody.appendChild(newRow);
                                                });
                                              });
                                            </script>
                                            

                                            <div class="col-md-12 mb-3">
                                                <div class="group-input">
                                                    <label for="evaluation">Evaluation and Approval by Head Quality / Designee</label>
                                                    <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div>
                                                    <!-- Editable textarea for update -->
                                                    <textarea name="evaluation[]" id="summernote-1" class="form-control">{{ old('evaluation', $data->evaluation ?? '') }}</textarea>
                                                </div>
                                                @error('evaluation.*')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-12 mb-3">
                                                <div class="group-input">
                                                    <label for="outcome_risk">Outcome of Risk Assessment (if Applicable)</label>
                                                    <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div>
                                                    <!-- Editable textarea for update -->
                                                    <textarea name="outcome_risk[]" id="summernote-1" class="form-control">{{ old('outcome_risk', $data->outcome_risk ?? '') }}</textarea>
                                                </div>
                                                @error('outcome_risk.*')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            

                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="proposal_change">Change Proposal Request</label>
                                                    <select name="proposal_change" id="proposal" class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="Approved" {{ old('proposal_change', $data->proposal_change) == 'Approved' ? 'selected' : '' }}>Approved</option>
                                                        <option value="Rejected" {{ old('proposal_change', $data->proposal_change) == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                                    </select>
                                                </div>
                                                @error('proposal_change')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            

                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="change_category">Category of Change</label>
                                                    <select name="change_category" id="change" class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="Major" {{ old('change_category', $data->change_category) == 'Major' ? 'selected' : '' }}>Major</option>
                                                        <option value="Minor" {{ old('change_category', $data->change_category) == 'Minor' ? 'selected' : '' }}>Minor</option>
                                                    </select>
                                                </div>
                                                @error('change_category')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            

                                            <div class="col-12">
                                                <div class="group-input">
                                                    <label for="reason">Reason for Categorization</label>
                                                    <input id="reason" type="text" name="reason_categorization" class="form-control" value="{{ old('reason_categorization', $data->reason_categorization) }}">
                                                </div>
                                                @error('reason_categorization')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            

                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="intimation">Intimation to be sent to Customer/Regulatory</label>
                                                    <select name="intimation" id="intimation" class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="Yes" {{ old('intimation', $data->intimation) == 'Yes' ? 'selected' : '' }}>Yes</option>
                                                        <option value="No" {{ old('intimation', $data->intimation) == 'No' ? 'selected' : '' }}>No</option>
                                                    </select>
                                                </div>
                                                @error('intimation')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            

                                            <div class="col-md-12 mb-3">
                                                <div class="group-input">
                                                    <label for="acknowledgement">Acknowledgement by HOD of change proposal initiator</label>
                                                    <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div>
                                                    <!-- Editable textarea for updating -->
                                                    <textarea name="acknowledgement[]" id="summernote-1" class="form-control">{{ old('acknowledgement', $data->acknowledgement) }}</textarea>
                                                </div>
                                                @error('acknowledgement.*')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            

                                            <div class="col-md-12 mb-3">
                                                <div class="group-input">
                                                    <label for="justification_extension">Justification for Extension (if required) for completion of identified actions with new Target Completion Date</label>
                                                    <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div>
                                                    <!-- Editable textarea for updating -->
                                                    <textarea name="justification_extension[]" id="summernote-1" class="form-control">{{ old('justification_extension', $data->justification_extension) }}</textarea>
                                                </div>
                                                @error('justification_extension.*')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-12 mb-3">
                                                <div class="group-input">
                                                    <label for="closure_remark">Closure Remark</label>
                                                    <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div>
                                                    <!-- Editable textarea for updating -->
                                                    <textarea name="closure_remark[]" id="summernote-1" class="form-control">{{ old('closure_remark', $data->closure_remark) }}</textarea>
                                                </div>
                                                @error('closure_remark.*')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            

                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="effectiveness">Effectiveness verification is required</label>
                                                    <select name="effectiveness" id="effectiveness" class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="Yes" {{ old('effectiveness', $data->effectiveness) == 'Yes' ? 'selected' : '' }}>Yes</option>
                                                        <option value="No" {{ old('effectiveness', $data->effectiveness) == 'No' ? 'selected' : '' }}>No</option>
                                                    </select>
                                                </div>
                                                @error('effectiveness')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            

                                            <div class="col-md-12 mb-3">
                                                <div class="group-input">
                                                    <label for="remark">Remark</label>
                                                    <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div>
                                                    <!-- Editable textarea -->
                                                    <textarea name="remark[]" id="summernote-1" class="form-control">{{ old('remark', $data->remark) }}</textarea>
                                                </div>
                                                @error('remark')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-12 mb-3">
                                                <div class="group-input">
                                                    <label for="closure_conclusion">Closure Conclusion by Head Quality / Designee</label>
                                                    <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div>
                                                    <!-- Editable textarea -->
                                                    <textarea name="closure_conclusion[]" id="summernote-1" class="form-control">{{ old('closure_conclusion', $data->closure_conclusion) }}</textarea>
                                                </div>
                                                @error('closure_conclusion')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                        </div>
                                        <div class="button-block">
                                            <button type="submit" class="saveButton">Save</button>
                                            <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                        </div>
                                    </div>
                                </div>

                                <div id="CCForm2" class="inner-block cctabcontent">
                                    <div class="inner-block-content">
                                        <div class="sub-head">
                                            Change Details
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="group-input">
                                                    <label for="doc-detail">
                                                        Document Details<button type="button" name="ann"
                                                            id="DocDetailbtn">+</button>
                                                    </label>
                                                    <table class="table-bordered table" id="doc-detail">
                                                        <thead>
                                                            <tr>
                                                                <th>Sr. No.</th>
                                                                <th>Current Document No.</th>
                                                                <th>Current Version No.</th>
                                                                <th>New Document No.</th>
                                                                <th>New Version No.</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if (!empty($docdetail->sno))
                                                                @foreach (unserialize($docdetail->current_doc_no) as $key => $datas)
                                                                    <tr>
                                                                        <td><input type="text" name="serial_number[]"
                                                                                value="{{ $key ? $key + 1 : '1' }}"></td>
                                                                        <td><input type="text"
                                                                                name="current_doc_number[]"
                                                                                value="{{ unserialize($docdetail->current_doc_no)[$key] ? unserialize($docdetail->current_doc_no)[$key] : 'Not Applicable' }}">
                                                                        </td>
                                                                        <td><input type="text" name="current_version[]"
                                                                                value="{{ unserialize($docdetail->current_version_no)[$key] ? unserialize($docdetail->current_version_no)[$key] : 'Not Applicale' }}">
                                                                        </td>
                                                                        <td><input type="text" name="new_doc_number[]"
                                                                                value="{{ unserialize($docdetail->new_doc_no)[$key] ? unserialize($docdetail->new_doc_no)[$key] : 'Not Applicable' }}">
                                                                        </td>
                                                                        <td><input type="text" name="new_version[]"
                                                                                value="{{ unserialize($docdetail->new_version_no)[$key] ? unserialize($docdetail->new_version_no)[$key] : 'Not Applicable' }}">
                                                                        </td>

                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                            <div id="docdetaildiv"></div>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="group-input">
                                                    <label for="current-practice">
                                                        Current Practice
                                                    </label>
                                                    <textarea name="current_practice">{{ $docdetail->current_practice }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="group-input">
                                                    <label for="proposed_change">
                                                        Proposed Change
                                                    </label>
                                                    <textarea name="proposed_change">{{ $docdetail->proposed_change }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="group-input">
                                                    <label for="reason_change">
                                                        Reason for Change
                                                    </label>
                                                    <textarea name="reason_change">{{ $docdetail->reason_change }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="group-input">
                                                    <label for="other_comment">
                                                        Any Other Comments
                                                    </label>
                                                    <textarea name="other_comment">{{ $docdetail->other_comment }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="group-input">
                                                    <label for="supervisor_comment">
                                                        Supervisor Comments
                                                    </label>
                                                    <textarea name="supervisor_comment">{{ $docdetail->supervisor_comment }}</textarea>
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

                                <div id="CCForm3" class="inner-block cctabcontent">
                                    <div class="inner-block-content">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="group-input">
                                                    <label for="type_change">Type of Change</label>
                                                    <select name="type_chnage">
                                                        <option value="0">-- Select --</option>
                                                        <option {{ $review->type_chnage == 'major' ? 'selected' : '' }}
                                                            value="major">Major</option>
                                                        <option {{ $review->type_chnage == 'minor' ? 'selected' : '' }}
                                                            value="minor">Minor</option>
                                                        <option {{ $review->type_chnage == 'critical' ? 'selected' : '' }}
                                                            value="critical">Critical</option>

                                                    </select>
                                                </div>



                                            </div>

                                            <div class="col-12">
                                                <div class="group-input">
                                                    <label for="qa_comments">QA Review Comments</label>
                                                    <textarea name="qa_review_comments">{{ $review->qa_comments }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="group-input">
                                                    <label for="related_records">Related Records</label>
                                                    {{--  <input type="text" name="related_records"
                                                        value="{{ $review->related_records }}">  --}}
                                                    <select {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} multiple id="related_records" name="related_records[]"
                                                        placeholder="Select Reference Records" data-search="false"
                                                        data-silent-initial-value-set="true" id="related_records">
                                                        @foreach ($pre as $prix)
                                                            <option value="{{ $prix->id }}" {{ in_array($prix->id, explode(',', $data->related_records)) ? 'selected' : '' }}>
                                                                {{ Helpers::getDivisionName($prix->division_id) }}/Change-Control/{{ Helpers::year($prix->created_at) }}/{{ Helpers::record($prix->record) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="group-input">
                                                    <label for="qa head">QA Attachments</label>
                                                    <div class="file-attachment-field">
                                                        <div class="file-attachment-list" id="qa_head">
                                                            @if ($review->qa_head)
                                                                @foreach (json_decode($review->qa_head) as $file)
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
                                                            <input type="file" id="myfile" name="qa_head[]"
                                                                oninput="addMultipleFiles(this, 'qa_head')" multiple>
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

                                <div id="CCForm4" class="inner-block cctabcontent">
                                    <div class="inner-block-content">
                                        <div class="sub-head">
                                            Evaluation Detail
                                        </div>
                                        <div class="group-input">
                                            <label for="qa-eval-comments">QA Evaluation Comments</label>
                                            <textarea name="qa_eval_comments">{{ $evaluation->qa_eval_comments }}</textarea>
                                        </div>
                                        <div class="group-input">
                                            <label for="qa-eval-attach">QA Evaluation Attachments</label>
                                            <div class="file-attachment-field">
                                                <div class="file-attachment-list" id="qa_eval_attach">
                                                    @if ($evaluation->qa_eval_attach)
                                                        @foreach (json_decode($evaluation->qa_eval_attach) as $file)
                                                            <h6 type="button" class="file-container text-dark"
                                                                style="background-color: rgb(243, 242, 240);">
                                                                <b>{{ $file }}</b>
                                                                <a href="{{ asset('upload/' . $file) }}" target="_blank"><i class="fa fa-eye text-primary"
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
                                                    <input {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} type="file" id="myfile" name="qa_eval_attach[]"
                                                        oninput="addMultipleFiles(this, 'qa_eval_attach')" multiple>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="sub-head">
                                            Training Information
                                        </div>
                                        <div class="group-input">
                                            <label for="nature-change">Training Required</label>
                                            <select name="training_required">
                                                <option value="0">-- Select --</option>
                                                <option {{ $evaluation->training_required == 'no' ? 'selected' : '' }}
                                                    value="no">No</option>
                                                <option {{ $evaluation->training_required == 'yes' ? 'selected' : '' }}
                                                    value="yes">Yes</option>
                                            </select>
                                        </div>
                                        <div class="group-input">
                                            <label for="train-comments">Training Comments</label>
                                            <textarea name="train_comments">{{ $evaluation->train_comments }}</textarea>
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

                                {{-- <div id="CCForm5" class="inner-block cctabcontent">
                                    <div class="inner-block-content">
                                        <div class="sub-head">
                                            CFT Information
                                        </div>
                                        <div class="row">

                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="Microbiology">CFT Reviewer</label>
                                                    <select name="Microbiology">
                                                        <option value="0">-- Select --</option>
                                                        <option value="yes" selected>Yes</option>
                                                        <option value="no">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="Microbiology-Person">CFT Reviewer Person</label>
                                                    <select multiple name="Microbiology_Person[]"
                                                        placeholder="Select CFT Reviewers" data-search="false"
                                                        data-silent-initial-value-set="true" id="cft_reviewer">
                                                         <option value="0">-- Select --</option>
                                                        @foreach ($cft as $data1)
                                                            @if(in_array($data1->id, $cft_aff))
                                                                <option value="{{ $data1->id }}" selected>{{ $data1->name }}</option>
                                                            @else
                                                                <option value="{{ $data1->id }}">{{ $data1->name }}</option>
                                                            @endif
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
                                                        <option {{ $info->goup_review == 'yes' ? 'selected' : '' }}
                                                            value="yes">Yes</option>
                                                        <option {{ $info->goup_review == 'no' ? 'selected' : '' }}
                                                            value="no">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="Production">Production</label>
                                                    <select name="Production">
                                                        <option value="0">-- Select --</option>
                                                        <option {{ $info->Production == 'yes' ? 'selected' : '' }}
                                                            value="yes">Yes</option>
                                                        <option {{ $info->Production == 'no' ? 'selected' : '' }}
                                                            value="no">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="Production-Person">Production Person</label>
                                                    <select name="Production_Person">
                                                        <option value="0">-- Select --</option>
                                                        @foreach ($users as $datas)
                                                            <option
                                                                {{ $info->Production_Person == $datas->id ? 'selected' : '' }}
                                                                value="{{ $datas->id }}">{{ $datas->name }}</option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="Quality-Approver">Quality Approver</label>
                                                    <select name="Quality_Approver">
                                                        <option value="0">-- Select --</option>
                                                        <option {{ $info->Quality_Approver == 'yes' ? 'selected' : '' }}
                                                            value="yes">Yes</option>
                                                        <option {{ $info->Quality_Approver == 'no' ? 'selected' : '' }}
                                                            value="no">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="Quality-Approver-Person">Quality Approver Person</label>
                                                    <select name="Quality_Approver_Person">
                                                        <option value="0">-- Select --</option>
                                                        @foreach ($users as $datas)
                                                            <option {{ $info->Quality_Approver_Person == $datas->id ? 'selected' : '' }}
                                                                value="{{ $datas->id }}">{{ $datas->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>


                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="bd_domestic">Others</label>
                                                    <select name="bd_domestic">
                                                        <option value="0">-- Select --</option>
                                                        <option {{ $info->bd_domestic == 'yes' ? 'selected' : '' }}
                                                            value="yes">Yes</option>
                                                        <option {{ $info->bd_domestic == 'no' ? 'selected' : '' }}
                                                            value="no">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="bd_domestic-Person">Others Person</label>
                                                    <select name="Bd_Person">
                                                        <option value="0">-- Select --</option>

                                                        @foreach ($users as $datas)
                                                            <option {{ $info->Bd_Person == $datas->id ? 'selected' : '' }}
                                                                value="{{ $datas->id }}">{{ $datas->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="group-input">
                                                    <label for="additional_attachments">Additional Attachments</label>
                                                    <div class="file-attachment-field">
                                                        <div class="file-attachment-list" id="additional_attachments">
                                                            @if ($info->additional_attachments)
                                                                @foreach (json_decode($info->additional_attachments) as $file)
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
                                                                name="additional_attachments[]"
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
                                </div> --}}

                                <div id="CCForm6" class="inner-block cctabcontent">
                                    <div class="inner-block-content">

                                        <div class="sub-head">
                                            Feedback
                                        </div>
                                        <div class="row">

                                            <div class="col-lg-12">
                                                <div class="group-input">
                                                    <label for="comments">Comments</label>
                                                    <textarea name="cft_comments">{{ $comments->cft_comments }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="group-input">
                                                    <label for="comments">Attachment</label>
                                                    <div class="file-attachment-field">
                                                        <div class="file-attachment-list" id="cft_attchament">
                                                            @if ($comments->cft_attchament)
                                                                @foreach (json_decode($comments->cft_attchament) as $file)
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
                                                            <input type="file" id="myfile" name="cft_attchament[]"
                                                                oninput="addMultipleFiles(this, 'cft_attchament')"
                                                                multiple>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="sub-head">
                                                Concerned Feedback
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="comments">QA Comments</label>
                                                    <textarea name="qa_commentss">{{ $comments->qa_commentss }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="comments">QA Head Designee Comments</label>
                                                    <textarea name="designee_comments">{{ $comments->designee_comments }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="comments">Warehouse Comments</label>
                                                    <textarea name="Warehouse_comments">{{ $comments->Warehouse_comments }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="comments">Engineering Comments</label>
                                                    <textarea name="Engineering_comments">{{ $comments->Engineering_comments }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="comments">Instrumentation Comments</label>
                                                    <textarea name="Instrumentation_comments">{{ $comments->Instrumentation_comments }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="comments">Validation Comments</label>
                                                    <textarea name="Validation_comments">{{ $comments->Validation_comments }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="comments">Others Comments</label>
                                                    <textarea name="Others_comments">{{ $comments->Others_comments }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="comments">Comments</label>
                                                    <textarea name="Group_comments">{{ $comments->Group_comments }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="group-input">
                                                    <label for="group-attachments">Attachments</label>
                                                    <div class="file-attachment-field">
                                                        <div class="file-attachment-list" id="group_attachments">
                                                            @if ($comments->group_attachments)
                                                                @foreach (json_decode($comments->group_attachments) as $file)
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
                                                                name="group_attachments[]"
                                                                oninput="addMultipleFiles(this, 'group_attachments')"
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

                                <div id="CCForm7" class="inner-block cctabcontent">
                                    <div class="inner-block-content">
                                        <div class="sub-head">
                                            Risk Assessment
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="group-input">
                                                    <label for="risk-identification">Risk Identification</label>
                                                    <textarea name="risk_identification">{{ $assessment->risk_identification }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="severity">Severity</label>
                                                    <select name="severity" id="analysisR"
                                                        onchange='calculateRiskAnalysis(this)'>
                                                        <option value="0">-- Select --</option>
                                                        <option {{ $assessment->severity == '1' ? 'selected' : '' }}
                                                            value="1">Negligible</option>
                                                        <option {{ $assessment->severity == '2' ? 'selected' : '' }}
                                                            value="2">Minor</option>
                                                        <option {{ $assessment->severity == '3' ? 'selected' : '' }}
                                                            value="3">Moderate</option>
                                                        <option {{ $assessment->severity == '4' ? 'selected' : '' }}
                                                            value="4">Major</option>
                                                        <option {{ $assessment->severity == '5' ? 'selected' : '' }}
                                                            value="5">Fatel</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="Occurance">Occurance</label>
                                                    <select name="Occurance" id="analysisP"
                                                        onchange='calculateRiskAnalysis(this)'>
                                                        <option value="0">-- Select --</option>
                                                        <option
                                                            {{ $assessment->Occurance == '5' ? 'selected' : '' }}
                                                            value="5">Extremely Unlikely</option>
                                                        <option {{ $assessment->Occurance == '4' ? 'selected' : '' }}
                                                            value="4">Rare</option>
                                                        <option {{ $assessment->Occurance == '3' ? 'selected' : '' }}
                                                            value="3">Unlikely</option>
                                                        <option {{ $assessment->Occurance == '2' ? 'selected' : '' }}
                                                            value="2">Likely</option>
                                                        <option {{ $assessment->Occurance == '1' ? 'selected' : '' }}
                                                            value="1">Very Likely</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="Detection">Detection</label>
                                                    <select name="Detection" id="analysisN"
                                                        onchange='calculateRiskAnalysis(this)'>
                                                        <option value="0">-- Select --</option>
                                                        <option {{ $assessment->Detection == '5' ? 'selected' : '' }}
                                                            value="5">Impossible</option>
                                                        <option {{ $assessment->Detection == '4' ? 'selected' : '' }}
                                                            value="4">Rare</option>
                                                        <option {{ $assessment->Detection == '3' ? 'selected' : '' }}
                                                            value="3">Unlikely</option>
                                                        <option {{ $assessment->Detection == '2' ? 'selected' : '' }}
                                                            value="2">Likely</option>
                                                        {{-- <option  {{   $assessment ->Detection=='Very-Likely'? 'selected' : ''}} value="Very-Likely">Very Likely</option> --}}
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="RPN">RPN</label>
                                                    <input type="text" name="RPN" id="analysisRPN"
                                                        value="{{ $assessment->RPN }}">
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="group-input">
                                                    <label for="risk-evaluation">Risk Evaluation</label>
                                                    <textarea name="risk_evaluation">{{ $assessment->risk_evaluation }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="group-input">
                                                    <label for="migration-action">Migration Action</label>
                                                    <textarea name="migration_action">{{ $assessment->migration_action }}</textarea>
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

                                <div id="CCForm8" class="inner-block cctabcontent">
                                    <div class="inner-block-content">
                                        <div class="group-input">
                                            <label for="qa-appro-comments">QA Approval Comments</label>
                                            <textarea name="qa_appro_comments">{{ $approcomments->qa_appro_comments }}</textarea>
                                        </div>
                                        <div class="group-input">
                                            <label for="feedback">Training Feedback</label>
                                            <textarea name="feedback">{{ $approcomments->feedback }}</textarea>
                                        </div>
                                        <div class="group-input">
                                            <label for="tran-attach">Training Attachments</label>
                                            <div class="file-attachment-field">
                                                <div class="file-attachment-list" id="tran_attach">
                                                    @if ($approcomments->tran_attach)
                                                        @foreach (json_decode($approcomments->tran_attach) as $file)
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
                                                    <input type="file" id="myfile" name="tran_attach[]"
                                                        oninput="addMultipleFiles(this, 'tran_attach')" multiple>
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

                                <div id="CCForm9" class="inner-block cctabcontent">
                                    <div class="inner-block-content">
                                        <div class="group-input">
                                            <label for="risk-assessment">
                                                Affected Documents<button type="button" name="ann"
                                                    id="addAffectedDocumentsbtn">+</button>
                                            </label>
                                            <table class="table table-bordered" id="affected-documents">
                                                <thead>
                                                    <tr>
                                                        <th>Sr. No.</th>
                                                        <th>Affected Documents</th>
                                                        <th>Document Name</th>
                                                        <th>Document No.</th>
                                                         <th>Version No.</th> 
                                                        <th>Implementation Date</th>
                                                        <th>New Document No.</th>
                                                        <th>New Version No.</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (!empty($closure->sno))
                                                    @foreach (unserialize($closure->affected_document) as $key => $datas)
                                                        <tr>
                                                            <td><input type="text" name="serial_number[]"
                                                                    value="{{ $key ? $key + 1 : '1' }}"></td>
                                                            <td><input type="text"
                                                                    name="affected_documents[]"
                                                                    value="{{ unserialize($closure->affected_document)[$key] ? unserialize($closure->affected_document)[$key] : 'Not Applicable' }}">
                                                            </td>
                                                            <td><input type="text" name="document_name[]"
                                                                    value="{{ unserialize($closure->doc_name)[$key] ? unserialize($closure->doc_name)[$key] : 'Not Applicale' }}">
                                                            </td>
                                                            <td>                                                                
                                                                <input type="number" name="document_no[]"
                                                                    value="{{ unserialize($closure->doc_no)[$key] ? unserialize($closure->doc_no)[$key] : 'Not Applicable' }}">
                                                            </td>
                                                             <td>
                                                                @if (!empty($closure->version_no))
                                                                <input type="text" name="version_no[]" value="{{ unserialize($closure->version_no)[$key] ? unserialize($closure->version_no)[$key] : 'Not Applicable' }}">
                                                                @else
                                                                <input type="text" name="version_no[]" value="Not Applicable">
                                                                @endif
                                                            </td> 
                                                            
                                                            <td><div class="group-input new-date-data-field ">
                                                                    <div class="  input-date  ">
                                                                        <div class="calenderauditee">
                                                                            {{-- <input type="text"  id="implementation_date{{$key}}" readonly placeholder="DD-MMM-YYYY"  value="{{  Helpers::getdateFormat(unserialize($closure->implementation_date)[$key]) ? Helpers::getdateFormat(unserialize($closure->implementation_date)[$key]) : 'Not Applicable' }}"/> --}}
                                                                            {{-- <input type="date" class="hide-input" name="implementation_date[]"  value="{{ Helpers::getdateFormat(unserialize($closure->implementation_date)[$key]) ? Helpers::getdateFormat(unserialize($closure->implementation_date)[$key]) : 'Not Applicable' }}"  oninput="handleDateInput(this, `implementation_date{{$key}}`)" /> --}}
                                                                            <input type="text"   id="implementation_date{{$key}}" {{ $data->stage == 0 || $data->stage == 3 ? 'disabled' : '' }}  readonly placeholder="DD-MMM-YYYY" value="{{ Helpers::getdateFormat(unserialize($closure->implementation_date)[$key]) }}" />
                                                                            <input type="date" id="implementation_date{{$key}}" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} value="{{unserialize($closure->implementation_date)[$key]}}"  name="implementation_date[]"  min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value="{{ Helpers::getdateFormat(unserialize($closure->implementation_date)[$key]) }}"class="hide-input" 
                                                                              oninput="handleDateInput(this, `implementation_date{{$key}}`)"  /></div></div></div></td>
                                                                        </div>
                                                                    </div>
                                                                </div> 
                                                            </td>
                                                            
                                                            <td><input type="text" name="new_document_no[]"
                                                                value="{{ unserialize($closure->new_doc_no)[$key] ? unserialize($closure->new_doc_no)[$key] : 'Not Applicable' }}">
                                                             </td>
                                                             <td><input type="text" name="new_version_no[]"
                                                                value="{{ unserialize($closure->new_version_no)[$key] ? unserialize($closure->new_version_no)[$key] : 'Not Applicable' }}">
                                                             </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                    <div id="docdetaildiv"></div>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="group-input">
                                            <label for="qa-closure-comments">QA Closure Comments</label>
                                            <textarea name="qa_closure_comments">{{ $closure->qa_closure_comments }}</textarea>
                                        </div>
                                        <div class="group-input">
                                            <label for="attach-list">List Of Attachments</label>
                                            <div class="file-attachment-field">
                                                <div class="file-attachment-list" id="tran_attach">
                                                    @if ($closure->attach_list)
                                                        @foreach (json_decode($closure->attach_list) as $file)
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
                                                    <input type="file" id="myfile" name="attach_list[]"
                                                        oninput="addMultipleFiles(this, 'attach_list')" multiple>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <div class="sub-head">
                                            Effectiveness Check Information
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="effective-check">Effectivess Check Required?</label>
                                                    <select name="effective_check">
                                                        <option value="0">-- Select --</option>
                                                        <option {{ $closure->effective_check == 'yes' ? 'selected' : '' }}
                                                            value="yes">Yes</option>
                                                        <option {{ $closure->effective_check == 'no' ? 'selected' : '' }}
                                                            value="no">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                           
                                            <div class="col-lg-6 new-date-data-field">
                                                <div class="group-input input-date">
                                                    <label for="effective-check-date">Effectiveness Check Creation Date</label>
                                                   <div class="calenderauditee">                                     
                                                          <input type="text"  id="effective_check_date"  readonly value="{{ Helpers::getdateFormat($data->effective_check_date)}}"
                                                           name="effective_check_date"  placeholder="DD-MMM-YYYY" />
                                                          <input type="date" name="effective_check_date" value="{{ $data->effective_check_date }}"  class="hide-input"
                                                           oninput="handleDateInput(this, 'effective_check_date')"/>
                                             </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="group-input">
                                                    <label for="Effectiveness_checker">Effectiveness Checker</label>
                                                    <select name="Effectiveness_checker">
                                                        <option value="0">Enter Your Selection Here</option>
                                                        @foreach ($users as $datas)
                                                            <option {{ $info->Effectiveness_checker == $datas->id ? 'selected' : '' }}
                                                                 value="{{ $datas->id }}">{{ $datas->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="group-input">
                                                    <label for="effective_check_plan">Effectiveness Check Plan</label>
                                                    <textarea name="effective_check_plan">{{$data->effective_check_plan}}</textarea>
                                                </div>
                                            </div> -->
                                            <div class="col-12 sub-head">
                                                Extension Justification
                                            </div>
                                            <div class="col-12">
                                                <div class="group-input">                                                    
                                                    <label for="due_date_extension">Due Date Extension
                                                        Justification</label>
                                                    <textarea name="due_date_extension"> {{ $due_date_extension }}</textarea>
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

                                @php
                                    $product = DB::table('products')->get();
                                    $material = DB::table('materials')->get();
                                @endphp

                                <div id="CCForm10" class="inner-block cctabcontent">
                                    <div class="inner-block-content">
                                        <div class="sub-head">
                                            Electronic Signatures
                                        </div>
                                        <div class="row">
                                            @if ($data->stage >= 2)
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="submitted">Submitted By</label>
                                                        @php
                                                            $submit = DB::table('c_c_stage_histories')
                                                                ->where('type', 'Change-Control')
                                                                ->where('doc_id', $data->id)
                                                                ->where('stage_id', 2)
                                                                ->get();
                                                        @endphp
                                                        @foreach ($submit as $temp)
                                                            <div class="static">{{ $temp->user_name }}</div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="submitted">Submitted On</label>
                                                        @php
                                                            $submit = DB::table('c_c_stage_histories')
                                                                ->where('type', 'Change-Control')
                                                                ->where('doc_id', $data->id)
                                                                ->where('stage_id', 2)
                                                                ->get();
                                                        @endphp
                                                        @foreach ($submit as $temp)
                                                            <div class="static">{{ $temp->created_at }}</div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                            @if ($data->stage == 0)
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="submitted">Cancelled By</label>
                                                        @php
                                                            $submit = DB::table('c_c_stage_histories')
                                                                ->where('type', 'Change-Control')
                                                                ->where('doc_id', $cc_lid)
                                                                ->where('stage_id', 0)
                                                                ->get();
                                                        @endphp
                                                        @foreach ($submit as $temp)
                                                            <div class="static">{{ $temp->user_name }}</div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="submitted">Cancelled On</label>
                                                        @php
                                                            $submit = DB::table('c_c_stage_histories')
                                                                ->where('type', 'Change-Control')
                                                                ->where('doc_id', $cc_lid)
                                                                ->where('stage_id', 0)
                                                                ->get();
                                                        @endphp
                                                        @foreach ($submit as $temp)
                                                            <div class="static">{{ $temp->created_at }}</div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                            {{-- <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="submitted">More Information Required By</label>
                                                    @php
                                                        $submit = DB::table('c_c_stage_histories')
                                                            ->where('type', 'Change-Control')
                                                            ->where('doc_id', $cc_lid)
                                                            ->where('status', 'More-info Required')
                                                            ->get();
                                                    @endphp
                                                    @foreach ($submit as $temp)
                                                        <div class="static">{{ $temp->user_name }}</div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="submitted">More Information Required On</label>
                                                    @php
                                                        $submit = DB::table('c_c_stage_histories')
                                                            ->where('type', 'Change-Control')
                                                            ->where('doc_id', $cc_lid)
                                                            ->where('status', 'More-info Required')
                                                            ->get();
                                                    @endphp
                                                    @foreach ($submit as $temp)
                                                        <div class="static">{{ $temp->created_at }}</div>
                                                    @endforeach
                                                </div>
                                            </div> --}}

                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="submitted">HOD Review Complete By</label>
                                                    @php
                                                        $submit = DB::table('c_c_stage_histories')
                                                            ->where('type', 'Change-Control')
                                                            ->where('doc_id', $cc_lid)
                                                            ->where('stage_id', 3)
                                                            ->get();
                                                    @endphp
                                                    @foreach ($submit as $temp)
                                                        <div class="static">{{ $temp->user_name }}</div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="submitted">HOD Review Complete On</label>
                                                    @php
                                                        $submit = DB::table('c_c_stage_histories')
                                                            ->where('type', 'Change-Control')
                                                            ->where('doc_id', $cc_lid)
                                                            ->where('stage_id', 3)
                                                            ->get();
                                                    @endphp
                                                    @foreach ($submit as $temp)
                                                        <div class="static">{{ $temp->created_at }}</div>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="submitted">Send to CFT/SME/QA Review By</label>
                                                    @php
                                                        $submit = DB::table('c_c_stage_histories')
                                                            ->where('type', 'Change-Control')
                                                            ->where('doc_id', $cc_lid)
                                                            ->where('stage_id', 4)
                                                            ->get();
                                                    @endphp
                                                    @foreach ($submit as $temp)
                                                        <div class="static">{{ $temp->user_name }}</div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="submitted">Send to CFT/SME/QA Review On</label>
                                                    @php
                                                        $submit = DB::table('c_c_stage_histories')
                                                            ->where('type', 'Change-Control')
                                                            ->where('doc_id', $cc_lid)
                                                            ->where('stage_id', 4)
                                                            ->get();
                                                    @endphp
                                                    @foreach ($submit as $temp)
                                                        <div class="static">{{ $temp->created_at }}</div>
                                                    @endforeach
                                                </div>
                                            </div>

                                            {{-- <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="submitted">CFT Reviewed By</label>
                                                    @php
                                                        $submit = DB::table('c_c_stage_histories')
                                                            ->where('type', 'Change-Control')
                                                            ->where('doc_id', $cc_lid)
                                                            ->where('stage_id', 5)
                                                            ->get();
                                                    @endphp
                                                    @foreach ($submit as $temp)
                                                        <div class="static">{{ $temp->user_name }}</div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="submitted">CFT Reviewed On</label>
                                                    @php
                                                        $submit = DB::table('c_c_stage_histories')
                                                            ->where('type', 'Change-Control')
                                                            ->where('doc_id', $cc_lid)
                                                            ->where('stage_id', 5)
                                                            ->get();
                                                    @endphp
                                                    @foreach ($submit as $temp)
                                                        <div class="static">{{ $temp->created_at }}</div>
                                                    @endforeach
                                                </div>
                                            </div> --}}


                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="submitted">CFT/SME/QA Review Not required By</label>
                                                    @php
                                                        $submit = DB::table('c_c_stage_histories')
                                                            ->where('type', 'Change-Control')
                                                            ->where('doc_id', $cc_lid)
                                                            ->where('stage_id', 6)
                                                            ->get();
                                                    @endphp
                                                    @foreach ($submit as $temp)
                                                        <div class="static">{{ $temp->user_name }}</div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="submitted">CFT/SME/QA Review Not required On</label>
                                                    @php
                                                        $submit = DB::table('c_c_stage_histories')
                                                            ->where('type', 'Change-Control')
                                                            ->where('doc_id', $cc_lid)
                                                            ->where('stage_id', 6)
                                                            ->get();
                                                    @endphp
                                                    @foreach ($submit as $temp)
                                                        <div class="static">{{ $temp->created_at }}</div>
                                                    @endforeach
                                                </div>
                                            </div>


                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="submitted">Review Completed By</label>
                                                    @php
                                                        $submit = DB::table('c_c_stage_histories')
                                                            ->where('type', 'Change-Control')
                                                            ->where('doc_id', $cc_lid)
                                                            ->where('stage_id', 7)
                                                            ->get();
                                                    @endphp
                                                    @foreach ($submit as $temp)
                                                        <div class="static">{{ $temp->user_name }}</div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="submitted">Review Completed On</label>
                                                    @php
                                                        $submit = DB::table('c_c_stage_histories')
                                                            ->where('type', 'Change-Control')
                                                            ->where('doc_id', $cc_lid)
                                                            ->where('stage_id', 7)
                                                            ->get();
                                                    @endphp
                                                    @foreach ($submit as $temp)
                                                        <div class="static">{{ $temp->created_at }}</div>
                                                    @endforeach
                                                </div>
                                            </div>


                                            {{-- <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="submitted">Change Implemented By</label>
                                                    @php
                                                        $submit = DB::table('c_c_stage_histories')
                                                            ->where('type', 'Change-Control')
                                                            ->where('doc_id', $cc_lid)
                                                            ->where('stage_id', 8)
                                                            ->get();
                                                    @endphp
                                                    @foreach ($submit as $temp)
                                                        <div class="static">{{ $temp->user_name }}</div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="submitted">Change Implemented On</label>
                                                    @php
                                                        $submit = DB::table('c_c_stage_histories')
                                                            ->where('type', 'Change-Control')
                                                            ->where('doc_id', $cc_lid)
                                                            ->where('stage_id', 8)
                                                            ->get();
                                                    @endphp
                                                    @foreach ($submit as $temp)
                                                        <div class="static">{{ $temp->created_at }}</div>
                                                    @endforeach
                                                </div>
                                            </div> --}}
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="submitted">Implemented By</label>
                                                    @php
                                                        $submit = DB::table('c_c_stage_histories')
                                                            ->where('type', 'Change-Control')
                                                            ->where('doc_id', $cc_lid)
                                                            ->where('stage_id', 9)
                                                            ->get();
                                                    @endphp
                                                    @foreach ($submit as $temp)
                                                        <div class="static">{{ $temp->user_name }}</div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="submitted">Implemented On</label>
                                                    @php
                                                        $submit = DB::table('c_c_stage_histories')
                                                            ->where('type', 'Change-Control')
                                                            ->where('doc_id', $cc_lid)
                                                            ->where('stage_id', 9)
                                                            ->get();
                                                    @endphp
                                                    @foreach ($submit as $temp)
                                                        <div class="static">{{ $temp->created_at }}</div>
                                                    @endforeach
                                                </div>
                                            </div>

                                        </div>
                                        <div class="button-block">
                                            <button type="submit" class="saveButton">Save</button>
                                            <a href="/rcms/qms-dashboard">
                                        <button type="button" class="backButton">Back</button>
                                    </a>

                                            <button type="submit">Submit</button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>

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
                <form action="{{ route('extension_child', $cc_lid) }}" method="POST">
                    @csrf
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="group-input">

                            <!-- <label for="major">
                                <input type="radio" name="child_type" value="extension">
                                Extension
                                <input type="hidden" name="parent_name" value="Change_control">
                                <input type="hidden" name="due_date" value="{{ $data->due_date }}">
                            </label> -->
                            <label for="major">
                                <input type="radio" name="child_type" value="documents">
                                New Document                               
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


    <div class="modal fade" id="signature-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">E-Signature</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ url('rcms/send-cc', $cc_lid) }}" method="POST">
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
                            <label for="comment">Comment</label>
                            <input type="comment" name="comment">
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" data-bs-dismiss="modal">Submit</button>
                        <button type="button" data-bs-dismiss="modal">Close</button>
                        {{-- <button>Close</button> --}}
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="division-modal" class="d-none">
        <div class="division-container">
            <div class="content-container">
                <form action="{{ route('division_submit') }}" method="post">
                    @csrf
                    <div class="division-tabs">
                        <div class="tab">
                            @php
                                $division = DB::table('divisions')->get();
                            @endphp
                            @foreach ($division as $temp)
                                <input type="hidden" value="{{ $temp->id }}" name="division_id" required>
                                <button class="divisionlinks"
                                    onclick="openDivision(event, {{ $temp->id }})">{{ $temp->name }}</button>
                            @endforeach

                        </div>
                        @php
                            $process = DB::table('processes')->get();
                        @endphp
                        @foreach ($process as $temp)
                            <div id="{{ $temp->division_id }}" class="divisioncontent">
                                @php
                                    $pro = DB::table('processes')
                                        ->where('division_id', $temp->division_id)
                                        ->get();
                                @endphp
                                @foreach ($pro as $test)
                                    <label for="process">
                                        <input type="radio" for="process" value="{{ $test->id }}"
                                            name="process_id" required> {{ $test->process_name }}
                                    </label>
                                @endforeach
                            </div>
                        @endforeach

                    </div>
                    <div class="button-container">
                        <button id="submit-division">Cancel</button>
                        <button id="submit-division" type="submit">Continue</button>
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
                <form action="{{ url('rcms/child', $cc_lid) }}" method="POST">
                    @csrf
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="group-input">
                            <label for="major">
                                <input type="radio" name="revision" id="major" value="Action-Item">
                                Action Item
                            </label>
                            @if ($data->stage == 10)
                                <label for="minor">
                                    <input type="radio" name="revision" id="minor" value="Extension">
                                    Extension
                                </label>
                            @elseif($data->stage == 7)
                                <label for="minor">
                                    <input type="radio" name="revision" id="minor" value="New Document">
                                    New Document
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

    <div class="modal fade" id="rejection-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">E-Signature</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form action="{{ url('rcms/send-rejection-field', $cc_lid) }}" method="POST">
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
                    <div class="modal-footer">
                        <button type="submit" data-bs-dismiss="modal">Submit</button>
                        <button type="button" data-bs-dismiss="modal">Close</button>
                        {{-- <button>Close</button> --}}
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="cft-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">E-Signature</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form action="{{ url('rcms/send-cft-field', $cc_lid) }}" method="POST">
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
                            <input type="comment" name="comment">
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" data-bs-dismiss="modal">Submit</button>
                        <button type="button" data-bs-dismiss="modal">Close</button>
                        {{-- <button>Close</button> --}}
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

                <form action="{{ url('rcms/send-cancel', $cc_lid) }}" method="POST">
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
                            <input type="comment" name="comment">
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" data-bs-dismiss="modal">Submit</button>
                        <button type="button" data-bs-dismiss="modal">Close</button>
                        {{-- <button>Close</button> --}}
                    </div>
                </form>
            </div>
        </div>
    </div>


    <style>
        #productTable,
        #materialTable {
            display: none;
        }
    </style>


    <script>
        VirtualSelect.init({
            ele: '#related_records, #cft_reviewer, #audit_type'
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

    <script>
        $(document).ready(function() {
            $('#add-input').click(function() {
                var lastInput = $('.bar input:last');
                var newInput = $('<input type="text" name="review_comment">');
                lastInput.after(newInput);
            });
        });
    </script>

    <!-- Example Blade View -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.all.min.js"></script>

    @if (session()->has('errorMessages'))
        <script>
            // Create an array to hold all the error messages
            var errorMessages = @json(session()->get('errorMessages'));

            if (!Array.isArray(errorMessages)) {
                errorMessages = [errorMessages];
            }

            errorMessages = errorMessages.map(function(message) {
                return '<div class="seperator">==================================================</div>' +
                    '<div class="slogan"><div>This form was not submitted because of the following errors.</div><div>Please correct the errors and re-submit.</div></div>' +
                    '<div class="data">This Activity cannot be performed, as there are some blank required fields.</div>' +
                    '<div class="message">' + message + '</div>';
            });

            Swal.fire({
                icon: '',
                title: 'Connexo DMS Says',
                html: errorMessages.join(''),

                showCloseButton: true, // Display a close button
                customClass: {
                    title: 'my-title-class', // Add a custom CSS class to the title
                    htmlContainer: 'my-html-class text-danger', // Add a custom CSS class to the popup content
                },
                confirmButtonColor: '#3085d6', // Customize the confirm button color
            });
        </script>
        @php session()->forget('errorMessages'); @endphp
    @endif

    <script>
        $(document).ready(function() {
            var disableInputs = {{ $data->stage }}; // Replace with your condition

            if (disableInputs == 0 || disableInputs > 8) {
                // Disable all input fields within the form
                $('#CCFormInput :input:not(select)').prop('disabled', true);
                $('#CCFormInput select').prop('disabled', true);
            } else {
               // $('#CCFormInput :input').prop('disabled', false);
            }
        });
    </script>
    <script>
        const productSelect = document.getElementById('productSelect');
        const productTable = document.getElementById('productTable');
        const materialSelect = document.getElementById('materialSelect');
        const materialTable = document.getElementById('materialTable');

        materialSelect.addEventListener('change', function() {
            if (materialSelect.value === 'yes') {
                materialTable.style.display = 'block';
            } else {
                materialTable.style.display = 'none';
            }
        });

        productSelect.addEventListener('change', function() {
            if (productSelect.value === 'yes') {
                productTable.style.display = 'block';
            } else {
                productTable.style.display = 'none';
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const removeButtons = document.querySelectorAll('.remove-file');

            removeButtons.forEach(button => {
                button.addEventListener('click', function() {
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
        function calculateRiskAnalysis(selectElement) {
            // Get the row containing the changed select element
            let row = selectElement.closest('tr');

            // Get values from select elements within the row
            let R = parseFloat(document.getElementById('analysisR').value) || 0;
            let P = parseFloat(document.getElementById('analysisP').value) || 0;
            let N = parseFloat(document.getElementById('analysisN').value) || 0;

            // Perform the calculation
            let result = R * P * N;

            // Update the result field within the row
            document.getElementById('analysisRPN').value = result;
        }
    </script>
     <script>
        var maxLength = 255;
        $('#docname').keyup(function() {
            var textlen = maxLength - $(this).val().length;
            $('#rchars').text(textlen);});
    </script>
@endsection
