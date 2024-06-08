@extends('layouts.app1')
@section('content')
    @if (Auth::guest())
        <script>window.location.href = '{{route("login")}}';</script>
    @endif
    <script></script>
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-0">
                <div class="table-responsive-sm" style="overflow-x:auto; overflow-y:auto;">
                    <div style="text-align:left">
                        <button style="text-align:center; font-size:12px;" onclick="print();"><i class="fa fa-print"></i></button>
                    </div>
                    <table cellspacing="0" rules="all" border="1" id="Table1" style="border-collapse:collapse;" class="table table-hover table-bordered" >
                        <thead>
                            <tr>
                                <th align='center' colspan="4">
                                    <div class="row">
                                        <div style="text-align:center">
                                            <b>Sebabrata Hospital</b>
                                        </div>
                                        <div style="text-align:center">
                                            <b>Pay Slip for the period of {{$empData['empSalaryData'][0]->month}}/{{$empData['empSalaryData'][0]->year}}</b>
                                        </div>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr align='center'>
                                <td colspan="2">Employee ID: {{$empData['empProfile'][0]->emp_display_id}}</td>
                                <td colspan="2">Name: {{$empData['empProfile'][0]->name}}</td>
                            </tr>
                            <tr align='center'>
                                <td colspan="2">Department: {{$empData['empProfile'][0]->dept_name}}</td>
                                <td colspan="2">Designation: {{$empData['empProfile'][0]->designation}}</td>
                            </tr>
                            <tr align='center'>
                                <td colspan="2">Pay Date: {{$empData['empSalaryData'][0]->issue_date}}</td>
                                <td colspan="2">Date Of Joining: {{$empData['empProfile'][0]->join_date}}</td>
                            </tr>
                            <tr align='center'>
                                <td colspan="2">PF Account Number: {{$empData['empProfile'][0]->pf_account_no}}</td>
                                <td colspan="2">Days Worked: {{$empData['empSalaryData'][0]->days_worked}}</td>
                            </tr>
                            <tr align='center'>
                                <td colspan="2">Bank Account Number: {{$empData['empProfile'][0]->bank_account_no}}</td>
                                <td colspan="2"></td>
                            </tr>
                            <br><br>
                            <tr>
                                <td>Earnings</td>
                                <td>Amount</td>
                                <td>Deductions</td>
                                <td>Amount</td>
                            </tr>
                            <tr>
                                <td>Basic</td>
                                <td>{{$empData['empSalaryData'][0]->basic}}</td>
                                <td>Professional Tax</td>
                                <td>{{$empData['empSalaryData'][0]->ptax_deduction}}</td>
                            </tr>
                            <tr>
                                <td>HRA</td>
                                <td>{{$empData['empSalaryData'][0]->hra}}</td>
                                <td>ESI</td>
                                <td>{{$empData['empSalaryData'][0]->esi_deduction}}</td>
                            </tr>
                            <tr>
                                <td>Conveyance</td>
                                <td>{{$empData['empSalaryData'][0]->conveyance}}</td>
                                <td>PF</td>
                                <td>{{$empData['empSalaryData'][0]->pf_deduction}}</td>
                            </tr>
                            <tr>
                                <td>OT Encashment</td>
                                <td>{{$empData['empSalaryData'][0]->ot_encashment}}</td>
                                <td>TDS</td>
                                <td>{{$empData['empSalaryData'][0]->tds_deduction}}</td>
                            </tr>
                            <tr>
                                <td>Leave Encashment</td>
                                <td>{{$empData['empSalaryData'][0]->leave_encashment}}</td>
                                <td>Medicine Due</td>
                                <td>{{$empData['empSalaryData'][0]->medicine_due}}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>Food Charge</td>
                                <td>{{$empData['empSalaryData'][0]->food_charge}}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>Loan Due</td>
                                <td>{{$empData['empSalaryData'][0]->loan_due_deduction}}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>Others</td>
                                <td>{{$empData['empSalaryData'][0]->other_deduction}}</td>
                            </tr>
                            <tr>
                                <td>Total Earning</td>
                                <td>{{$empData['empSalaryData'][0]->tot_earning}}</td>
                                <td>Total Deduction</td>
                                <td>{{$empData['empSalaryData'][0]->tot_deduction}}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>Net Pay(Rounded)</td>
                                <td>{{round($empData['empSalaryData'][0]->net_amount_payable)}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
