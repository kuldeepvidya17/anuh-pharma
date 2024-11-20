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
                    Lab Incident Single Report
                </td>
                <td class="w-30">
                    <div class="logo">
                        <!-- for anuh pharma logo ----  https://www.anuhpharma.com/images/logo.png   -->
                        <img src="https://vidyagxp.com/vidyaGxp_logo.png" alt="" class="w-100">
                    </div>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="w-30">
                    <strong>Lab Incident No.</strong>
                </td>
                <td class="w-40">
                   {{ Helpers::divisionNameForQMS($data->division_id) }}/{{ Helpers::year($data->created_at) }}/{{ str_pad($data->record, 4, '0', STR_PAD_LEFT) }}
                </td>
                <td class="w-30">
                    <strong>Record No.</strong> {{ str_pad($data->record, 4, '0', STR_PAD_LEFT) }}
                </td>
            </tr>
        </table>
    </header>

    <div class="inner-block">
        <div class="content-table">
            <div class="block">
                <div class="block-head">
                    General Information
                </div>
                <table>
                    <tr>  {{ $data->created_at }} added by {{ $data->originator }}
                        <th class="w-20">Record Number</th>
                        <td class="w-30">{{ Helpers::divisionNameForQMS($data->division_id) }}/{{ Helpers::year($data->created_at) }}/{{ str_pad($data->record, 4, '0', STR_PAD_LEFT) }}</td>
                        <th class="w-20">Site/Location Code</th>
                        <td class="w-30">@if($data->division_code){{ $data->division_code }} @else Not Applicable @endif</td>
                    </tr>
                    <tr>
                        <th class="w-20">Initiator</th>
                        <td class="w-30">@if($data->initiator_id){{ Helpers::getInitiatorName($data->initiator_id) }} @else Not Applicable @endif</td>
                        <th class="w-20">Date of Initiation</th>
                        <td class="w-30">@if($data->intiation_date){{ Helpers::getDateFormat($data->intiation_date) }} @else Not Applicable @endif</td>
                    </tr>
                    <tr>
                        <th class="w-20">Assigned To</th>
                        <td class="w-30">@if($data->assign_to){{ Helpers::getInitiatorName($data->assign_to) }} @else Not Applicable @endif</td>
                        <th class="w-20">Date Due</th>
                        <td class="w-30">@if($data->due_date){{ Helpers::getDateFormat($data->due_date) }} @else Not Applicable @endif</td>
                    </tr>
                    <tr>
                        <th class="w-20">Name of Product/Material</th>
                        <td class="w-30">@if($data->name_of_product_material){{ $data->name_of_product_material }} @else Not Applicable @endif</td>
                        <th class="w-20">Batch No./A. R. Number</th>
                        <td class="w-30">@if($data->batch_number){{ $data->batch_number }} @else Not Applicable @endif</td>
                       
                    </tr>
                    <tr>
                        <th class="w-20">Description of the Incident</th>
                        <td class="w-80" colspan="3">
                            @if($data->short_desc){{ $data->short_desc }}@else Not Applicable @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="w-20">Stage</th>
                        <td class="w-30">@if($data->stage_number){{ $data->stage_number }}@else Not Applicable @endif</td>
                        <th class="w-20">Reference Specification No./ SOP No./ DOC No.</th>
                        <td class="w-30">@if($data->reference_specification_number){{ $data->reference_specification_number }}@else Not Applicable @endif</td>
                    </tr>
                    <tr>
                        <th class="w-20">Test</th>
                        <td class="w-30" colspan="3">@if($data->Document_Details){{ $data->Document_Details }}@else Not Applicable @endif</td>
                    </tr>
                    <tr>
                        <th class="w-20">Immediate action taken</th>
                        <td class="w-30" colspan="3">@if($data->immediate_action_taken){{ $data->immediate_action_taken }}@else Not Applicable @endif</td>
                    </tr>
                    <tr>
                        <th class="w-20">Investigation details</th>
                        <td class="w-30" colspan="3">@if($data->investigation_details){{ $data->investigation_details }}@else Not Applicable @endif</td>
                    </tr>
                    <tr>
                        <th class="w-20">Investigation by Concern Department(if Applicable: Yes/No)</th>
                        <td class="w-30" colspan="3">@if($data->investigation_concern_department){{ $data->investigation_concern_department }}@else Not Applicable @endif</td>
                    </tr>
                    <tr>
                        <th class="w-20">Root Cause</th>
                        <td class="w-30" colspan="3">@if($data->root_cause){{ $data->root_cause }}@else Not Applicable @endif</td>
                    </tr>
                    <tr>
                        <th class="w-20">Incident Root cause Categorization</th>
                        <td class="w-30" colspan="3">@if($data->is_repeat_gi){{ $data->is_repeat_gi }}@else Not Applicable @endif</td>
                    </tr>
                    <tr>
                        <th class="w-20">Miscellaneous/Others</th>
                        <td class="w-30" colspan="3">@if($data->miscellaneous_others){{ $data->miscellaneous_others }}@else Not Applicable @endif</td>
                    </tr>
                    <tr>
                        <th class="w-20">Current Control Measure</th>
                        <td class="w-30" colspan="3">@if($data->current_control_measure){{ $data->current_control_measure }}@else Not Applicable @endif</td>
                    </tr>
                    <tr>
                        <th class="w-20">Corrective Action</th>
                        <td class="w-30" colspan="3">@if($data->Currective_Action){{ $data->Currective_Action }}@else Not Applicable @endif</td>
                    </tr>
                    <tr>
                        <th class="w-20">Preventive Action (if any)</th>
                        <td class="w-30" colspan="3">@if($data->Preventive_Action){{ $data->Preventive_Action }}@else Not Applicable @endif</td>
                    </tr>
                    <tr>
                        <th class="w-20">Evaluation & Recommendation from QA</th>
                        <td class="w-30" colspan="3">@if($data->evaluation_from_qa){{ $data->evaluation_from_qa }}@else Not Applicable @endif</td>
                    </tr>
                    <tr>
                        <th class="w-20">Conclusion & Disposition by Head Quality/Designee</th>
                        <td class="w-30" colspan="3">@if($data->conclusion_by_head_quality){{ $data->conclusion_by_head_quality }}@else Not Applicable @endif</td>
                    </tr>
                    <tr>
                        <th class="w-20">Justification for Delay in Closing (if applicable - Yes/No)</th>
                        <td class="w-30" colspan="3">@if($data->justification_delay_closing){{ $data->justification_delay_closing }}@else Not Applicable @endif</td>
                    </tr>
                    <tr>
                        <th class="w-20">Target Closure Date</th>
                        <td class="w-30" colspan="3">@if($data->target_close_date){{ Helpers::getdateFormat($data->target_close_date) }}@else Not Applicable @endif</td>
                    </tr>
                    <tr>
                        <th class="w-20">Tentative extended closure date</th>
                        <td class="w-30" colspan="3">@if($data->tentavie_extended_closure_date){{ Helpers::getdateFormat($data->tentavie_extended_closure_date) }}@else Not Applicable @endif</td>
                    </tr>
                    <tr>
                        <th class="w-20">Justification</th>
                        <td class="w-30" colspan="3">@if($data->justification){{ $data->justification }}@else Not Applicable @endif</td>
                    </tr>
                    <tr>
                        <th class="w-20">Closure Verification</th>
                        <td class="w-30" colspan="3">@if($data->closure_verification){{ $data->closure_verification }}@else Not Applicable @endif</td>
                    </tr>
                </table>
            </div>

            <div class="block">
                <div class="block-head">
                    Incident Details
                </div>
                <table>
                    <tr>
                        <th class="w-20">Incident Details</th>
                        <td class="w-30" colspan="3">{{ $data->Incident_Details }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">Instrument Details</th>
                        <td class="w-30" colspan="3">{{ $data->Instrument_Details }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">Involved Personnel</th>
                        <td class="w-30" colspan="3">{{ $data->Involved_Personnel }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">Product Details,If Any</th>
                        <td class="w-30" colspan="3">{{ $data->Product_Details }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">Supervisor Review Comments</th>
                        <td class="w-30" colspan="3">{{ $data->Supervisor_Review_Comments }}</td>
                    </tr>
                </table>

                <div class="border-table">
                    <div class="block-head">
                        Attachment
                    </div>
                    <table>

                        <tr class="table_bg">
                            <th class="w-20">S.N.</th>
                            <th class="w-60">Batch No</th>
                        </tr>
                            @if($data->Attachments)
                            @foreach(json_decode($data->Attachments) as $key => $file)
                                <tr>
                                    <td class="w-20">{{ $key + 1 }}</td>
                                    <td class="w-20"><a href="{{ asset('upload/' . $file) }}" target="_blank"><b>{{ $file }}</b></a> </td>
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
                    Investigation Details
                </div>

                <div class="border-table">
                    <div class="block-head">
                        Inv Attachment
                    </div>
                    <table>

                        <tr class="table_bg">
                            <th class="w-20">S.N.</th>
                            <th class="w-60">Batch No</th>
                        </tr>
                            @if($data->Inv_Attachment)
                            @foreach(json_decode($data->Inv_Attachment) as $key => $file)
                                <tr>
                                    <td class="w-20">{{ $key + 1 }}</td>
                                    <td class="w-20"><a href="{{ asset('upload/' . $file) }}" target="_blank"><b>{{ $file }}</b></a> </td>
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

                    <table>
                        <tr>
                            <th class="w-20">Investigation Details</th>
                            <td class="w-30" colspan="3">{{ $data->Investigation_Details_sec }}</td>
                        </tr>
                        <tr>
                            <th class="w-20">Action Taken</th>
                            <td class="w-30" colspan="3">{{ $data->Action_Taken }}</td>
                        </tr>
                        <tr>
                            <th class="w-20">Root Cause</th>
                            <td class="w-30" colspan="3">{{ $data->Root_Cause_sec }}</td>
                        </tr>
                    </table>
            </div>


            <div class="block">
                <div class="block-head">
                    CAPA
                </div>
                <table>
                    <tr>
                        <th class="w-20">Corrective & Preventive Action</th>
                        <td class="w-30" colspan="3">{{ $data->Corrective_Preventive_Action }}</td>
                    </tr>
                </table>

                <div class="border-table">
                    <div class="block-head">
                        CAPA Attachment
                    </div>
                    <table>

                        <tr class="table_bg">
                            <th class="w-20">S.N.</th>
                            <th class="w-60">Batch No</th>
                        </tr>
                            @if($data->CAPA_Attachment)
                            @foreach(json_decode($data->CAPA_Attachment) as $key => $file)
                                <tr>
                                    <td class="w-20">{{ $key + 1 }}</td>
                                    <td class="w-20"><a href="{{ asset('upload/' . $file) }}" target="_blank"><b>{{ $file }}</b></a> </td>
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
                    QA Review
                </div>
                <table>
                    <th class="w-20">QA Review Comments</th>
                    <td class="w-30" colspan="3">{{ $data->QA_Review_Comments }}</td>
                </table>

                <div class="border-table">
                    <div class="block-head">
                    QA Head Attachment
                    </div>
                    <table>

                        <tr class="table_bg">
                            <th class="w-20">S.N.</th>
                            <th class="w-60">Batch No</th>
                        </tr>
                            @if($data->QA_Head_Attachment)
                            @foreach(json_decode($data->QA_Head_Attachment) as $key => $file)
                                <tr>
                                    <td class="w-20">{{ $key + 1 }}</td>
                                    <td class="w-20"><a href="{{ asset('upload/' . $file) }}" target="_blank"><b>{{ $file }}</b></a> </td>
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
                    QA Head/Designee Approval
                </div>

                <div class="block-head">
                Closure
                </div>

                <table>
                    <tr>
                        <th class="w-20">QA Head/Designee Comments</th>
                        <td class="w-30" colspan="3">{{ $data->QA_Head }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">Incident Type</th>
                        <td class="w-30">{{ $data->Incident_Type }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">Conclusion</th>
                        <td class="w-30" colspan="3">{{ $data->Conclusion }}</td>
                    </tr>
                    <div class="block-head">
                        Extension Justification
                    </div>
                    <tr>
                        <th class="w-20">Due Date Extension Justification</th>
                        <td class="w-30" colspan="3">{{ $data->due_date_extension }}</td>
                    </tr>
                </table>

            </div>    


             <div class="block">
                <div class="block-head">
                    Activity Log
                </div>
                <table>
                    <tr>
                        <th class="w-20">Submitted By</th>
                        <td class="w-30">{{ $data->submitted_by }}</td>
                        <th class="w-20">Submitted On</th>
                        <td class="w-30">{{ $data->submitted_on }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">Incident Review Completed By</th>
                        <td class="w-30">{{ $data->incident_review_completed_by }}</td>
                        <th class="w-20">Incident Review Completed On</th>
                        <td class="w-30">{{ $data->incident_review_completed_on }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">Investigation Completed By</th>
                        <td class="w-30">{{ $data->investigation_completed_by }}</td>
                        <th class="w-20">Investigation Completed On</th>
                        <td class="w-30">{{ $data->investigation_completed_on}}</td>
                    </tr>
                    <tr>
                        <th class="w-20">QA Review Completed By</th>
                        <td class="w-30">{{ $data->qA_review_completed_by }}</td>
                        <th class="w-20">QA Review Completed On</th>
                        <td class="w-30">{{ $data->qA_review_completed_on }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">QA Head Approval Completed By
                        </th>
                        <td class="w-30">{{ $data->qA_head_approval_completed_by }}</td>
                        <th class="w-20">QA Head Approval Completed On</th>
                        <td class="w-30">{{ $data->qA_head_approval_completed_on }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">All Activities Completed By</th>
                        <td class="w-30">{{ $data->all_activities_completed_by }}</td>
                        <th class="w-20">All Activities Completed On</th>
                        <td class="w-30">{{ $data->all_activities_completed_on }}</td>
                    </tr>
                    <!-- <tr>
                        <th class="w-20">Review Completed By</th>
                        <td class="w-30">{{ $data->incident_review_completed_by }}</td>
                        <th class="w-20">Review Completed On</th>
                        <td class="w-30">{{ $data->incident_review_completed_on }}</td>
                    </tr> -->
                    <tr>
                        <th class="w-20">Cancelled By</th>
                        <td class="w-30">{{ $data->cancelled_by }}</td>
                        <th class="w-20">
                        Cancelled On</th>
                        <td class="w-30">{{ $data->cancelled_on }}</td>
                    </tr>


                </table>
            </div> 
        </div>
    </div>

    <footer>
        <table>
            <tr>
                <td class="w-30">
                    <strong>Printed On :</strong> {{ date('d-M-Y') }}
                </td>
                <td class="w-40">
                    <strong>Printed By :</strong> {{ Auth::user()->name }}
                </td>
                <td class="w-30">
                    <strong>Page :</strong> 1 of 1
                </td>
            </tr>
        </table>
    </footer>

</body>

</html>
