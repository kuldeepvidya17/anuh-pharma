<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>VidyaGxP - Software</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
</head>

<style>
    body {
        font-family: 'Roboto', sans-serif;
        margin: 0;
        padding: 0;
        min-height: 100vh;
    }

    .w-10 {
        width: 10%;
    }

    .w-20 {
        width: 20%;
    }

    .w-25 {
        width: 25%;
    }

    .w-30 {
        width: 30%;
    }

    .w-40 {
        width: 40%;
    }

    .w-50 {
        width: 50%;
    }

    .w-60 {
        width: 60%;
    }

    .w-70 {
        width: 70%;
    }

    .w-80 {
        width: 80%;
    }

    .w-90 {
        width: 90%;
    }

    .w-100 {
        width: 100%;
    }

    .h-100 {
        height: 100%;
    }

    header table,
    header th,
    header td,
    footer table,
    footer th,
    footer td,
    .border-table table,
    .border-table th,
    .border-table td {
        border: 1px solid black;
        border-collapse: collapse;
        font-size: 0.9rem;
        vertical-align: middle;
    }

    table {
        width: 100%;
    }

    th,
    td {
        padding: 10px;
        text-align: left;
    }

    footer .head,
    header .head {
        text-align: center;
        font-weight: bold;
        font-size: 1.2rem;
    }

    @page {
        size: A4;
        margin-top: 160px;
        margin-bottom: 60px;
    }

    header {
        position: fixed;
        top: -140px;
        left: 0;
        width: 100%;
        display: block;
    }

    footer {
        width: 100%;
        position: fixed;
        display: block;
        bottom: -40px;
        left: 0;
        font-size: 0.9rem;
    }

    footer td {
        text-align: center;
    }

    .inner-block {
        padding: 10px;
    }

    .inner-block tr {
        font-size: 0.8rem;
    }

    .inner-block .block {
        margin-bottom: 30px;
    }

    .inner-block .block-head {
        font-weight: bold;
        font-size: 1.1rem;
        padding-bottom: 5px;
        border-bottom: 2px solid #4274da;
        margin-bottom: 10px;
        color: #4274da;
    }

    .inner-block th,
    .inner-block td {
        vertical-align: baseline;
    }

    .table_bg {
        background: #4274da57;
    }
</style>

<body>

    <header>
        <table>
            <tr>
                <td class="w-70 head">
                    Market Complaint Report
                </td>
                <td class="w-30">
                    <div class="logo">
                        <img src="https://navin.mydemosoftware.com/public/user/images/logo.png" alt=""
                            class="w-100">
                    </div>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="w-30">
                    <strong> Market Complaint No.</strong>
                </td>
                <td class="w-40">
                    {{ Helpers::divisionNameForQMS($data->division_id) }}/MC/{{ Helpers::year($data->created_at) }}/{{ str_pad($data->record, 4, '0', STR_PAD_LEFT) }}
                </td>
                <td class="w-30">
                    <strong>Record No.</strong> {{ str_pad($data->record, 4, '0', STR_PAD_LEFT) }}
                </td>
            </tr>
        </table>
    </header>
    <footer>
        <table>
            <tr>
                <td class="w-30">
                    <strong>Printed On :</strong> {{ date('d-M-Y') }}
                </td>
                <td class="w-40">
                    <strong>Printed By :</strong> {{ Auth::user()->name }}
                </td>
                {{-- <td class="w-30">
                    <strong>Page :</strong> 
                </td> --}}
            </tr>
        </table>
    </footer>
    <div class="inner-block">
        <div class="content-table">
            <div class="block">
                <div class="block-head">
                    General Information
                </div>
                <table>
                    <tr> {{ $data->created_at }} added by {{ $data->originator }}
                        <th class="w-20">Site/Location Code</th>
                        <td class="w-30"> {{ Helpers::getDivisionName($data->division_id) }}</td>
                        <th class="w-20">Initiator</th>
                        <td class="w-30">{{ Helpers::getInitiatorName($data->initiator_id) }}</td>
                        </td>
                    </tr>
                    <tr>
                        <th class="w-20">Date of Initiation</th>
                        {{-- <td class="w-30">@if{{ Helpers::getdateFormat($data->intiation_date) }} @else Not Applicable @endif</td> --}}
                        {{-- <td class="w-30">@if (Helpers::getdateFormat($data->intiation_date)) {{ Helpers::getdateFormat($data->intiation_date) }} @else Not Applicable @endif</td> --}}
                        <td class="w-30">{{ $data->created_at ? $data->created_at->format('d-m-Y') : '' }} </td>

                        <th class="w-20">Due Date</th>
                        <td class="w-30">
                            @if ($data->due_date)
                            {{ \Carbon\Carbon::parse($data->due_date)->format('d-m-Y') }}
                        @else
                            Not Applicable
                        @endif
                        </td>
                    </tr>
                    <tr>
                        
                        <th class="w-20">Department Group</th>
                        <td class="w-30">
                            @if ($data->initiator_group)
                            {{ Helpers::getFullDepartmentName($data->initiator_group) }}
                        @else
                            Not Applicable
                        @endif                        </td>
                        <th class="w-20">Department Group Code</th>
                        <td class="w-30">{{ $data->initiator_group_code ? $data->initiator_group_code : '' }} </td>
                    </tr>

                </table>
                

                <div class="block-head">
                    Product Plan
                </div>
                <div class="border-table" style="margin-bottom: 15px;">
                    <div class="table-responsive">
                        <!-- First Half of the Table -->
                        <table class="table table-bordered" id="product_details" style="width: 100%;">
                            <thead>
                                <tr class="table_bg">
                                    <th style="width: 15px;">Row #</th>
                                    <th style="width: 100px;">Product Name</th>
                                    <th style="width: 100px;">Batch No</th>
                                    <th style="width: 100px;">Mfg. Date</th>
                                    <th style="width: 120px;">Exp. Date / Retest Date</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                @if($productdeta && is_array($productdeta))
                                    @php $serialNumber = 1; @endphp
                                    @foreach($productdeta as $key => $detail)
                                        <tr>
                                            <td>{{ $serialNumber++ }}</td>
                                            <td>{{ $detail['product_name'] ?? 'Not Applicable' }}</td>
                                            <td>{{ $detail['batch_no'] ?? 'Not Applicable' }}</td>
                                            <td>{{ $detail['mfg_date'] ?? 'Not Applicable' }}</td>
                                            <td>{{ $detail['exp_date'] ?? 'Not Applicable' }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td>1</td>
                                        <td>Not Applicable</td>
                                        <td>Not Applicable</td>
                                        <td>Not Applicable</td>
                                        <td>Not Applicable</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                
                        <!-- Second Half of the Table -->
                        <table class="table table-bordered" id="product_dispatch" style="width: 100%; margin-top: 15px;">
                            <thead>
                                <tr class="table_bg">
                                    <th style="width: 15px;">Row #</th>
                                    <th style="width: 80px;">Batch Size</th>
                                    <th style="width: 100px;">Dispatch Date</th>
                                    <th style="width: 80px;">Dispatch Qty.</th>
                                    <th style="width: 120px;">Date of Completion</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($productdeta && is_array($productdeta))
                                @php $serialNumber = 1; @endphp
                                    @foreach($productdeta as $key => $detail)
                                        <tr> 
                                            <td>{{ $serialNumber++ }}</td>
                                            <td>{{ $detail['batch_size'] ?? 'Not Applicable' }}</td>
                                            <td>{{ $detail['dispatch_date'] ?? 'Not Applicable' }}</td>
                                            <td>{{ $detail['dispatch_qty'] ?? 'Not Applicable' }}</td>
                                            <td>{{ $detail['completion_date'] ?? 'Not Applicable' }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr> <td>1</td>
                                        <td>Not Applicable</td>
                                        <td>Not Applicable</td>
                                        <td>Not Applicable</td>
                                        <td>Not Applicable</td>

                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                
                
                
                   <table>
                    <th class="w-20">Short Description</th>
                    <td class="w-30">
                        @if ($data->short_description)
                            {{ $data->short_description }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                   </table>
                <table>
                    <tr>
                        <th class="w-20">Name & Address of The Complainant Agency</th>
                            @if ($data->nameAddressagency)
                            {{ \Carbon\Carbon::parse($data->nameAddressagency)->format('d-m-Y') }}
                        @else
                            Not Applicable
                        @endif
                        </td>
                        <th class="w-20"> Name & Designation complainer</th>
                        <td class="w-30"> @if ($data->nameDesgnationCom)
                            {{ $data->nameDesgnationCom }}
                        @else
                            Not Applicable
                        @endif</td>
                       

                    </tr>
                    <tr>
                        
                        <th class="w-20">Phone No</th>
                        <td>
                            @if ($data->phone_no)
                            {{ $data->phone_no }}
                        @else
                            Not Applicable
                        @endif
                        </td>


                        {{-- <td class="w-30">@if ($data->Facility){{ $data->Facility }} @else Not Applicable @endif</td> --}}

                    </tr>

                    <tr>
                        <th class="w-20">Email address </th>
                        <td class="w-30">
                            @if ($data->email_address)
                            {{ $data->email_address }}
                        @else
                            Not Applicable
                        @endif
                        </td>
                        <th class="w-20">Sample Recd</th>
                        <td class="w-30">
                            @if ($data->sample_recd)
                                {{ $data->sample_recd }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                    <tr>

                        <th class="w-20"> Test Results recd</th>
                        <td class="w-30">
                            @if ($data->test_results_recd)
                                {{ $data->test_results_recd }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                        <th class="w-20">Classification Based on Receipt of Complaint</th>
                        <td class="w-30">
                            @if ($data->severity_level_form)
                                {{ $data->severity_level_form }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                    </tr>
                    <tr>

                        <th class="w-20">Acknowledgment Sent to Customer Through Marketing Department by Head QA</th>
                        <td class="w-30">
                            @if ($data->acknowledgment_sent)
                                {{ $data->acknowledgment_sent }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                </table>
              
                <div class="block-head">
                    Previous History of Product Specific 
                </div>
                <div class="border-table" style="margin-bottom: 15px;">
                    <div class="table-responsive">
                        <!-- First Half of the Table -->
                        <table class="table table-bordered" id="product_details" style="width: 100%;">
                                <thead>
                                    <tr class="table_bg">
                                        <th style="width: 15px;">Row #</th>
                                        <th style="width: 150px;">Complaint Receipt Date</th>
                                        <th style="width: 150px;">Complaint Received From</th>
                                        <th style="width: 200px;">Nature of Complaint</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($historydeta && is_array($historydeta))
                                        @php $serialNumber = 1; @endphp
                                        @foreach($historydeta as $key => $detail)
                                            <tr>
                                                <td>{{ $serialNumber++ }}</td>
                                                <td>{{ $detail['receipt_date'] ?? 'Not Applicable' }}</td>
                                                <td>{{ $detail['received_from'] ?? 'Not Applicable' }}</td>
                                                <td>{{ $detail['nature_of_complaint'] ?? 'Not Applicable' }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td>1</td>
                                            <td>Not Applicable</td>
                                            <td>Not Applicable</td>
                                            <td>Not Applicable</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            
                            <!-- Second Half of the Table -->
                            <table class="table table-bordered" id="history_details_second" style="width: 100%; margin-top: 15px;">
                                <thead>
                                    <tr class="table_bg">
                                        <th style="width: 15px;">Row #</th>
                                        <th style="width: 150px;">CAPA Taken</th>
                                        <th style="width: 150px;">Remark</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($historydeta && is_array($historydeta))
                                        @php $serialNumber = 1; @endphp
                                        @foreach($historydeta as $key => $detail)
                                            <tr>
                                                <td>{{ $serialNumber++ }}</td>
                                                <td>{{ $detail['capa_taken'] ?? 'Not Applicable' }}</td>
                                                <td>{{ $detail['remark'] ?? 'Not Applicable' }}</td>
                                                
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td>1</td>
                                            <td>Not Applicable</td>
                                            <td>Not Applicable</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                



                <table>
                    <tr>
                        <th class="w-20">Analysis / Physical Examination of Control Sample to be Done</th>
                        <td class="w-30">
                            @if ($data->analysis_physical_examination)
                                {{ $data->analysis_physical_examination }}
                            @else
                                Not Applicable
                            @endif
                        </td>


                    </tr>


                    {{-- <tr> --}}
                    {{-- <th class="w-20">Name of Product & Batch No</th> --}}
                    {{-- <td class="w-30">@if ($data->Product_Batch){{ ($data->Product_Batch) }} @else Not Applicable @endif</td> --}}
                    {{-- </tr> --}}
                 

                </table>
                <div class="block">
                    <table>
                        <tr>
                            <th class="w-20">Comments</th>
                            <td class="w-80">
                                @if ($data->Description_Deviation)
                                    {{ strip_tags($data->Description_Deviation) }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                           </tr>
                            <tr>
                                <th class="w-20">QA for Review of Root Cause of Market Complaint</th>
                                <td class="w-80">
                                    @if ($data->Immediate_Action)
                                        {{ strip_tags($data->Immediate_Action) }}
                                    @else
                                        Not Applicable
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="w-20">Preliminary Investigation Report sent by QA to Complainant on</th>
                                <td class="w-80">
                                    @if ($data->Preliminary_Investigation_Report)
                                        {{strip_tags($data->Preliminary_Investigation_Report) }}
                                    @else
                                        Not Applicable
                                    @endif
                                </td>
                            </tr>
                    </table>
                </div>
            
                <div class="block">
                    <div class="block-head">
                        Testing Plan
                    </div>
                    <div class="border-table">
                                   <!-- First Half of the Table -->
            <table class="table table-bordered" id="material_details_first" style="width: 100%;">
                <thead>
                    <tr class="table_bg">
                        <th style="width: 15px;">Row #</th>
                        <th style="width: 100px;">Batch No</th>
                        <th style="width: 150px;">Physical Test to be Performed</th>
                        <th style="width: 100px;">Observation</th>
                        <th style="width: 120px;">Specification</th>
                    </tr>
                </thead>
                <tbody>
                    @if($materialdeta && is_array($materialdeta))
                        @php $serialNumber = 1; @endphp
                        @foreach($materialdeta as $key => $detail)
                            <tr>
                                <td>{{ $serialNumber++ }}</td>
                                <td>{{ $detail['batch_no'] ?? 'Not Applicable' }}</td>
                                <td>{{ $detail['physical_test'] ?? 'Not Applicable' }}</td>
                                <td>{{ $detail['observation'] ?? 'Not Applicable' }}</td>
                                <td>{{ $detail['specification'] ?? 'Not Applicable' }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td>1</td>
                            <td>Not Applicable</td>
                            <td>Not Applicable</td>
                            <td>Not Applicable</td>
                            <td>Not Applicable</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            
            <!-- Second Half of the Table -->
            <table class="table table-bordered" id="material_details_second" style="width: 100%; margin-top: 15px;">
                <thead>
                    <tr class="table_bg">
                        <th style="width: 15px;">Row #</th>
                        <th style="width: 150px;">Batch Disposition Decision</th>
                        <th style="width: 150px;">Remark</th>
                        <th style="width: 100px;">Batch Status</th>
                    </tr>
                </thead>
                <tbody>
                    @if($materialdeta && is_array($materialdeta))
                        @php $serialNumber = 1; @endphp
                        @foreach($materialdeta as $key => $detail)
                            <tr>
                                <td>{{ $serialNumber++ }}</td>
                                <td>{{ $detail['batch_disposition_decision'] ?? 'Not Applicable' }}</td>
                                <td>{{ $detail['remark'] ?? 'Not Applicable' }}</td>
                                <td>{{ $detail['batch_status'] ?? 'Not Applicable' }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td>1</td>
                            <td>Not Applicable</td>
                            <td>Not Applicable</td>
                            <td>Not Applicable</td>
                        </tr>
                    @endif
                </tbody>
            </table>

                    </div>
                </div>
              

                <div class="border-table">
                    <div class="block-head">
                        Initial Attachments
                    </div>
                    <table>

                        <tr class="table_bg">
                            <th class="w-20">S.N.</th>
                            <th class="w-60">Attachment</th>
                        </tr>
                        @if ($data->Audit_file)
                            @foreach (json_decode($data->Audit_file) as $key => $file)
                                <tr>
                                    <td class="w-20">{{ $key + 1 }}</td>
                                    <td class="w-20"><a href="{{ asset('upload/' . $file) }}"
                                            target="_blank"><b>{{ $file }}</b></a> </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="w-20">1</td>
                                <td class="w-20">Not Applicable</td>
                            </tr>
                        @endif

                    </table>
                </div>
                <!-- {{-- ==================================      --}} -->

                <div class="block">
                    <div class="block-head">
                        HOD Review
                    </div>
                    <table>
                        <tr>
                            <th class="w-20">HOD Remarks</th>
                            <td class="w-80">
                                @if ($data->HOD_Remarks)
                                    {{ strip_tags($data->HOD_Remarks) }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>
                    </table>
                    <div class="border-table">
                        <div class="block-head">
                            HOD Attachments
                        </div>
                        <table>

                            <tr class="table_bg">
                                <th class="w-20">S.N.</th>
                                <th class="w-60">Attachment</th>
                            </tr>
                            @if ($data->Audit_file)
                                @foreach (json_decode($data->Audit_file) as $key => $file)
                                    <tr>
                                        <td class="w-20">{{ $key + 1 }}</td>
                                        <td class="w-20"><a href="{{ asset('upload/' . $file) }}"
                                                target="_blank"><b>{{ $file }}</b></a> </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="w-20">1</td>
                                    <td class="w-20">Not Applicable</td>
                                </tr>
                            @endif

                        </table>
                    </div>



                </div>
            </div>
            <div class="block">
                <div class="block-head">
                    QA Initial  Review
                </div>
                <table>
                    <tr>
                        <th class="w-20"> Repeat Deviation?</th>
                        <td class="w-80">
                            @if ($data->short_description_required)
                                {{ $data->short_description_required }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="w-20"> Repeat Nature</th>
                        <td class="w-80">
                            @if ($data->nature_of_repeat)
                                {{ $data->nature_of_repeat }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                    </tr>
                    <tr>
                        <th class="w-20">Customer Notification Required?</th>
                        <td class="w-80">
                            @if ($data->Customer_notification)
                                {{ $data->Customer_notification }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="w-20">QA Initial Remarks  </th>
                        <td class="w-80">
                            @if ($data->QAInitialRemark)
                                {{ strip_tags($data->QAInitialRemark) }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                  
                    <tr>
                        {{-- <th class="w-20">Customer Notification Required ?</th> --}}
                        {{-- <td class="w-30">@if ($data->Customer_notification){{$data->Customer_notification}}@else Not Applicable @endif</td> --}}
                        {{-- <th class="w-20">Customers</th> --}}
                        {{-- <td class="w-30">@if ($data->customers){{ $data->customers }}@else Not Applicable @endif</td> --}}
                        {{-- @php
                            $customer = DB::table('customer-details')->where('id', $data->customers)->first();
                            $customer_name = $customer ? $customer->customer_name : 'Not Applicable';
                        @endphp --}}

                        {{-- <td>
                        @if ($data->customers)
                            {{ $customer_name }}
                        @else
                            Not Applicable
                        @endif
                    </td> --}}
                    </tr>

                   
                   
                </table>
                <div class="border-table">
                    <div class="block-head">
                        QA Initial Attachments
                    </div>
                    <table>

                        <tr class="table_bg">
                            <th class="w-20">S.N.</th>
                            <th class="w-60">Attachment</th>
                        </tr>
                        @if ($data->Initial_attachment)
                            @foreach (json_decode($data->Initial_attachment) as $key => $file)
                                <tr>
                                    <td class="w-20">{{ $key + 1 }}</td>
                                    <td class="w-20"><a href="{{ asset('upload/' . $file) }}"
                                            target="_blank"><b>{{ $file }}</b></a> </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="w-20">1</td>
                                <td class="w-20">Not Applicable</td>
                            </tr>
                        @endif

                    </table>
                </div>
            </div>
           
          


            <div class="block">
                <div class="block-head">
                    Activity Log
                </div>
                <table>
                    <tr>
                        <th class="w-20">Submit By</th>
                        <td class="w-30">{{ $data->submit_by }}</td>
                        <th class="w-20">Submit On</th>
                        <td class="w-30"> {{ \Carbon\Carbon::parse($data->submit_on)->format('d-m-Y') }}</td>
                        <th class="w-20">Submit Comments</th>
                        <td class="w-30">{{ $data->submit_comment }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">HOD Review Complete By</th>
                        <td class="w-30">{{ $data->HOD_Review_Complete_By }}</td>
                        <th class="w-20">HOD Review Complete On</th>
                        <td class="w-30">{{ $data->HOD_Review_Complete_On }}</td>
                        <th class="w-20">HOD Review Comments</th>
                        <td class="w-30">{{ $data->HOD_Review_Comments }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">QA Initial Review Complete by</th>
                        <td class="w-30">{{ $data->QA_Initial_Review_Complete_By }}</td>
                        <th class="w-20">QA Initial Review Complete On</th>
                        <td class="w-30">{{ Helpers::getdateFormat($data->QA_Initial_Review_Complete_On) }}</td>
                        <th class="w-20">QA Initial Review Comments</th>
                        <td class="w-30">{{ $data->QA_Initial_Review_Comments }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">CFT Review Complete By</th>
                        <td class="w-30">{{ $data->CFT_Review_Complete_By }}</td>
                        <th class="w-20">CFT Review Complete On</th>
                        <td class="w-30">{{ $data->CFT_Review_Complete_On }}</td>
                        <th class="w-20">CFT Review Comments</th>
                        <td class="w-30">{{ $data->CFT_Review_Comments }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">QA Secondary Review Complete By</th>
                        <td class="w-30"></td>
                        <th class="w-20">QA Secondary Review Complete On</th>
                        <td class="w-30"></td>
                        <th class="w-20">QA Secondary Review Complete Comments</th>
                        <td class="w-30"></td>
                    </tr>
                    <tr>
                        <th class="w-20">QAH Primary Approved Completed By</th>
                        <td class="w-30"></td>
                        <th class="w-20">QAH Primary Approved Completed On</th>
                        <td class="w-30"></td>
                        <th class="w-20">QAH Primary Approved Completed Comments</th>
                        <td class="w-30"></td>
                    </tr>
                    <tr>
                        <th class="w-20">Initiator Update By</th>
                        <td class="w-30">{{ $data->CFT_Review_Complete_By }}</td>
                        <th class="w-20">Initiator Update On </th>
                        <td class="w-30">{{ $data->CFT_Review_Complete_On }}</td>
                        <th class="w-20">Initiator Update Comments</th>
                        <td class="w-30">{{ $data->CFT_Review_Comments }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">HOD Final Review By </th>
                        <td class="w-30">{{ $data->CFT_Review_Complete_By }}</td>
                        <th class="w-20">HOD Final Review On </th>
                        <td class="w-30">{{ $data->CFT_Review_Complete_On }}</td>
                        <th class="w-20">HOD Final Review Comments</th>
                        <td class="w-30">{{ $data->CFT_Review_Comments }}</td>
                    </tr>

                    <tr>
                        <th class="w-20">QA Final Review Complete By</th>
                        <td class="w-30">{{ $data->QA_Final_Review_Complete_By }}</td>
                        <th class="w-20">QA Final Review Complete On</th>
                        <td class="w-30">{{ $data->QA_Final_Review_Complete_On }}</td>
                        <th class="w-20">QA Final Review Comments</th>
                        <td class="w-30">{{ $data->QA_Final_Review_Comments }}</td>
                    </tr>

                    <tr>
                        <th class="w-20">Approved By</th>
                        <td class="w-30">{{ $data->Approved_By }}</td>
                        <th class="w-20">Approved ON</th>
                        <td class="w-30">{{ $data->Approved_On }}</td>
                        <th class="w-20">Approved Comments</th>
                        <td class="w-30">{{ $data->Approved_Comments }}</td>

                    </tr>

                </table>
            </div>
        </div>

    </div>

  

</body>

</html>