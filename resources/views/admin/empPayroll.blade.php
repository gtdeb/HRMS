@extends('layouts.app1')
@section('content')
    @if (Auth::guest())
        <script>window.location.href = '{{route("login")}}';</script>
    @endif
    <script></script>
    @if($pageSetting['user']->admin == '1002')
    <div class="container">
        <form method="post" action="{{route('emp_payroll.payslip', null,1 )}}" enctype="multipart/form-data">
            {{csrf_field()}}
            <label for="emp_id">Employee ID: </label>
            <input type="text" id="emp_id" name="emp_id">
            <label for="year">Year: </label>
            {!! Form::selectRange('year', 2020, 3000, 2020) !!}
            <label for="month">Month: </label>
            {!! Form::selectRange('month', 00, 12, 00) !!}
            <button type="submit" class="btn btn-success">Generate</button><br>
        </form>
    </div>
    @endif
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-0">
                <div class="table-responsive-sm" style="overflow-x:auto; overflow-y:auto;">
                    @if($empPayrolls)
                    <div style="text-align:left">
                        {!! $empPayrolls->links() !!}
                    </div>
                    @endif
                    <table cellspacing="0" rules="all" border="1" id="Table1" style="border-collapse:collapse;" class="table table-hover table-bordered" >
                        <thead>
                            <tr>
                                <th align='center' colspan="1">
                                    {!! Form::selectRange('records', 5, 100, $pageSetting['recs'], array('onchange' => 'changePageRecs(this)')) !!}
                                </th>
                                <th align='center' colspan="24">
                                    <div class="row">
                                        <div style="text-align:center">
                                            <b>Employee Payroll</b>
                                        </div>
                                    </div>
                                </th>
                                <th align='center' colspan="3">
                                    <div class="row">
                                        <div style="text-align:center">
                                            <input style="text-align:center; width:40%; height:25px;" type="search" name="data_serach" id="data_serach" value="{{$pageSetting['query']}}" />
                                            <button style="text-align:center; font-size:12px;" onclick="search();"><i class="fa fa-search"></i></button>
                                            <button style="text-align:center; font-size:12px;" onclick="add();" disabled><i class="fa fa-plus"></i></button>
                                            <button style="text-align:center; font-size:12px;" onclick="save();"><i class="fa fa-save"></i></button>
                                        </div>
                                    </div>
                                </th>
                            </tr>
                            <tr align='center'>
                                <th width="4%" align='center'><i class="fa fa-trash"></i></th>
                                <th width="4%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="id" style="cursor: pointer"> Sr <span id="id_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                <th width="4%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="issue_date" style="cursor: pointer"> Issue Date <span id="issue_date_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                <th width="4%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="year" style="cursor: pointer"> Year <span id="year_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                <th width="4%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="month" style="cursor: pointer"> Month <span id="month_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                <th width="4%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="emp_display_id" style="cursor: pointer"> Emp. Display Id <span id="emp_display_id_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                <th width="4%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="attendance_count" style="cursor: pointer"> Attendance Count <span id="attendance_count_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                <th width="4%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="leave_count" style="cursor: pointer"> Leave Count <span id="leave_count_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                <th width="4%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="off_day_count" style="cursor: pointer"> Off Day Count <span id="off_day_count_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                <th width="4%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="ot_count" style="cursor: pointer"> OT Count <span id="ot_count_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                <th width="4%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="absent_count" style="cursor: pointer"> Absent Count <span id="absent_count_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                <th width="4%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="days_worked" style="cursor: pointer"> Days Worked <span id="days_worked_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                <th width="4%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="basic" style="cursor: pointer"> Basic <span id="basic_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                <th width="4%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="hra" style="cursor: pointer"> HRA <span id="hra_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                <th width="4%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="conveyance" style="cursor: pointer"> Conveyance <span id="conveyance_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                <th width="4%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="ot_encashment" style="cursor: pointer"> OT Encashment <span id="ot_encashment_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                <th width="4%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="leave_encashment" style="cursor: pointer"> Leave Encashment <span id="leave_encashment_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                <th width="4%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="tot_earning" style="cursor: pointer"> Total Earning <span id="tot_earning_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                <th width="4%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="ptax_deduction" style="cursor: pointer"> Ptax Deduction <span id="ptax_deduction_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                <th width="4%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="esi_deduction" style="cursor: pointer"> ESI Deduction <span id="esi_deduction_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                <th width="4%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="pf_deduction" style="cursor: pointer"> PF Deduction <span id="pf_deduction_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                <th width="4%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="tds_deduction" style="cursor: pointer"> TDS Deduction <span id="tds_deduction_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                <th width="4%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="medicine_due" style="cursor: pointer"> Medicine Due <span id="medicine_due_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                <th width="4%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="food_charge" style="cursor: pointer"> Food Charge <span id="food_charge_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                <th width="4%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="loan_due_deduction" style="cursor: pointer"> Loan Due Deduction <span id="loan_due_deduction_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                <th width="4%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="other_deduction" style="cursor: pointer"> Other Deduction <span id="other_deduction_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                <th width="4%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="tot_deduction" style="cursor: pointer"> Total Deduction <span id="tot_deduction_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                <th width="4%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="net_amount_payable" style="cursor: pointer"> Net Amount Payable <span id="net_amount_payable_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr align='center'>
                            @if($empPayrolls)
                                <?php $index = 2; ?>
                                @foreach ($empPayrolls as $payroll)
                                    <?php $index++; ?>
                                    <tr>
                                        <td>
                                            <label class="action" hidden>D</label><input class='checkbox1' type="checkbox" disabled>
                                            <button style="font-size:10px" onclick="edit(this, {{$index}})"><i class="fa fa-pencil-square"></i></button>
                                            <button style="font-size:10px" onclick="redirect(this, {{$index}})"><i class="fa fa-external-link"></i></button>
                                        </td>
                                        <td>{{ $payroll->id }}</td>
                                        @php
                                            if($payroll->issue_date){
                                                $leave_date = date_create($payroll->issue_date);
                                                $formt_date = date_format($leave_date,"Y-m-d");
                                                $formt_time = date_format($leave_date,"H:i:s");
                                                $leave_date_fmt = date_format($leave_date, "Y-m-d A g:i");
                                                $formtmatted = $formt_date . 'T' . $formt_time;
                                            }
                                            else{
                                                $leave_date_fmt = $payroll->issue_date;
                                                $formtmatted = $payroll->issue_date;
                                            }
                                        @endphp
                                        <td><label class="lblname" for={{$formtmatted}}>{{$leave_date_fmt}}</label></td>
                                        <td><label class="lblname">{{$payroll->year}}</label></td>
                                        <td><label class="lblname">{{$payroll->month}}</label></td>
                                        <td>{{ $payroll->emp_display_id }}</td>
                                        <td><label class="lblname">{{$payroll->attendance_count}}</label></td>
                                        <td><label class="lblname">{{$payroll->leave_count}}</label></td>
                                        <td><label class="lblname">{{$payroll->off_day_count}}</label></td>
                                        <td><label class="lblname">{{$payroll->ot_count}}</label></td>
                                        <td><label class="lblname">{{$payroll->absent_count}}</label></td>
                                        <td><label class="lblname">{{$payroll->days_worked}}</label></td>
                                        <td><label class="lblname">{{$payroll->basic}}</label></td>
                                        <td><label class="lblname">{{$payroll->hra}}</label></td>
                                        <td><label class="lblname">{{$payroll->conveyance}}</label></td>
                                        <td><label class="lblname">{{$payroll->ot_encashment}}</label></td>
                                        <td><label class="lblname">{{$payroll->leave_encashment}}</label></td>
                                        <td><label class="lblname">{{$payroll->tot_earning}}</label></td>
                                        <td><label class="lblname">{{$payroll->ptax_deduction}}</label></td>
                                        <td><label class="lblname">{{$payroll->esi_deduction}}</label></td>
                                        <td><label class="lblname">{{$payroll->pf_deduction}}</label></td>
                                        <td><label class="lblname">{{$payroll->tds_deduction}}</label></td>
                                        <td><label class="lblname">{{$payroll->medicine_due}}</label></td>
                                        <td><label class="lblname">{{$payroll->food_charge}}</label></td>
                                        <td><label class="lblname">{{$payroll->loan_due_deduction}}</label></td>
                                        <td><label class="lblname">{{$payroll->other_deduction}}</label></td>
                                        <td><label class="lblname">{{$payroll->tot_deduction}}</label></td>
                                        <td><label class="lblname">{{$payroll->net_amount_payable}}</label></td>
                                    </tr>
                                @endforeach
                            @endif
                            </tr>
                        </tbody>
                    </table>
                    <div hidden>
                        <form id="searchfilter" method="POST" action="{{route('emp_payroll.fetch_data',null,1)}}" enctype="application/json">
                            {{csrf_field()}}
                            <input type="text" name="fd_page" id="fd_page" value="{{$pageSetting['page']}}">
                            <input type="text" name="fd_recs" id="fd_recs" value="{{$pageSetting['recs']}}">
                            <input type="text" name="fd_sort_by" id="fd_sort_by" value="{{$pageSetting['sort_by']}}">
                            <input type="text" name="fd_sort_type" id="fd_sort_type" value="{{$pageSetting['sort_type']}}">
                            <input type="text" name="fd_query" id="fd_query" value="{{$pageSetting['query']}}">
                        </form>
                        <form id="cud_action" method="POST" action="{{route('emp_payroll.update_data',null,1)}}" enctype="application/json">
                            {{csrf_field()}}
                            <input type="text" name="fd_cud_page" id="fd_cud_page" value="{{$pageSetting['page']}}">
                            <input type="text" name="fd_cud_recs" id="fd_cud_recs" value="{{$pageSetting['recs']}}">
                            <input type="text" name="fd_cud_sort_by" id="fd_cud_sort_by" value="{{$pageSetting['sort_by']}}">
                            <input type="text" name="fd_cud_sort_type" id="fd_cud_sort_type" value="{{$pageSetting['sort_type']}}">
                            <input type="text" name="fd_cud_query" id="fd_cud_query" value="{{$pageSetting['query']}}">
                            <textarea name="fd_cud" id="fd_cud" rows="6" cols="50">
                            </textarea>
                        </form>
                        <form id="payslip_print" method="POST" action="{{route('emp_payroll.update_data',null,1)}}" enctype="application/json">
                            {{csrf_field()}}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function pageSetting(){
            var column_name = $('#fd_sort_by').val();
            var order_type = $('#fd_sort_type').val();

            if(order_type == 'asc'){
                $('#'+column_name+'_sort_icon').html('<i class="fa fa-sort-asc"></i>');
            }
            else if(order_type == 'desc')
            {
                $('#'+column_name+'_sort_icon').html('<i class="fa fa-sort-desc"></i>');
            }

            columns = document.getElementById("Table1").rows[1].cells;
            for (var i = 1; i < columns.length-1; i++) {
                if($(columns[i]).data('column_name') != column_name){
                    $('#'+$(columns[i]).data('column_name')+'_sort_icon').html('<i class="fa fa-sort"></i>');
                    $(columns[i]).data('sorting_type', 'unsorted');
                }
            }
            $("#waitingScreen").modal("hide");
        }

        function fetch_data(page, recs, sort_type, sort_by, query)
        {
            var str = "page=" + page + "recs=" + recs + " sort_type=" + sort_type + " sort_by=" + sort_by + " query=" + query;
            //alert(str);
            $('#fd_page').val(page);
            $('#fd_recs').val(recs);
            $('#fd_sort_by').val(sort_by);
            $('#fd_sort_type').val(sort_type);
            $('#fd_query').val(query);
            document.getElementById("searchfilter").action = '{{ route("emp_payroll.fetch_data") }}?page=' + page;
            $("#waitingScreen").modal("show");
            $("#searchfilter").submit();
        }

        function changePageRecs(e){
            var query = $('#fd_query').val();
            var column_name = $('#fd_sort_by').val();
            var sort_type = $('#fd_sort_type').val();
            var page = 1;
            var recs = Number(e.value);
            if(recs < 5){
                recs = 5;
                $('#fd_recs').val(recs);
            }
            //alert(recs);
            fetch_data(page, recs, sort_type, column_name, query);
        }

        function search(){
            var query = $('#data_serach').val();
            var column_name = $('#fd_sort_by').val();
            var sort_type = $('#fd_sort_type').val();
            var page = 1;
            var recs = $('#fd_recs').val();
            fetch_data(page, recs, sort_type, column_name, query);
        }

        $(document).on('click', '.sorting', function(){
            var column_name = $(this).data('column_name');
            var order_type = $(this).data('sorting_type');
            var reverse_order = '';

            if(order_type == 'asc')
            {
                $(this).data('sorting_type', 'desc');
                reverse_order = 'desc';
                //$('#'+column_name+'_sort_icon').html('<i class="fa fa-sort-asc"></i>');
            }
            else if(order_type == 'unsorted' || order_type == 'desc')
            {
                $(this).data('sorting_type', 'asc');
                reverse_order = 'asc';
                //$('#'+column_name+'_sort_icon').html('<i class="fa fa-sort-desc"></i>');
            }
            var page = $('#fd_page').val();
            var recs = $('#fd_recs').val();
            var query = $('#fd_query').val();
            fetch_data(page, recs, reverse_order, column_name, query);
        });

        $(document).on('click', '.pagination a', function(event){
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            var recs = $('#fd_recs').val();
            var column_name = $('#fd_sort_by').val();
            var sort_type = $('#fd_sort_type').val();
            var query = $('#fd_query').val();

            //$('li').removeClass('active');
            //   $(this).parent().addClass('active');
            fetch_data(page, recs, sort_type, column_name, query);
        });

        function add(){
            var grid = document.getElementById("Table1").insertRow(-1);
            grid.insertCell(0).innerHTML = "<label class='action' hidden>C</label><button style='font-size:8px' onclick='remove();'><i class='fa fa-remove'></i></button>";
            grid.insertCell(1).innerHTML = "<input type='text' class='name' id='emp_display_id' style='border:none' size='8'>";
            grid.insertCell(2).innerHTML = "<input class='name' type='text' style='border:none' size='8'>";
            grid.insertCell(3).innerHTML = "<input class='name' type='number' min='00.00' step='00.01' value='00.00' style='border:none'>";
            grid.insertCell(4).innerHTML = "<input class='name' type='number' min='00.00' step='00.01' value='00.00' style='border:none'>";
            grid.insertCell(5).innerHTML = "<input class='name' type='number' min='00.00' step='00.01' value='00.00' style='border:none'>";
            grid.insertCell(6).innerHTML = "<input class='name' type='number' min='00.00' step='00.01' value='00.00' style='border:none'>";
            grid.insertCell(7).innerHTML = "";
        }

        function remove(){
            document.getElementById("Table1").deleteRow(-1);
        }

        function edit(element, index){
            //alert('Edit=' + index);
            var cells = document.getElementById("Table1").rows[index].cells;
            var chkbx = cells[0].getElementsByClassName("checkbox1");
            var action = cells[0].getElementsByClassName("action");
            //var srId = cells[1].innerText;
            var lblelement = '';
            var lblname = '';

            if(element.innerHTML.search("pencil") != -1){
                if(chkbx[0]) {
                    chkbx[0].style.display = 'none';
                    action[0].innerHTML = 'DU';
                }
                else {
                    action[0].innerHTML = 'LU';
                }
                element.innerHTML = "<i class='fa fa-undo'></i>";

                lblelement = cells[22].getElementsByClassName("lblname");
                lblName = lblelement[0].innerText;
                cells[22].innerHTML = "<label class='lblname' hidden>" + lblName + "</label><input class='name' type='number' min='00.00' step='00.01' style='border:none' value='" + lblName +  "'>";

                lblelement = cells[23].getElementsByClassName("lblname");
                lblName = lblelement[0].innerText;
                cells[23].innerHTML = "<label class='lblname' hidden>" + lblName + "</label><input class='name' type='number' min='00.00' step='00.01' style='border:none' value='" + lblName +  "'>";

                lblelement = cells[25].getElementsByClassName("lblname");
                lblName = lblelement[0].innerText;
                cells[25].innerHTML = "<label class='lblname' hidden>" + lblName + "</label><input class='name' type='number' min='00.00' step='00.01' style='border:none' value='" + lblName +  "'>";
            }
            else{
                if(chkbx[0]) {
                    chkbx[0].style.display = '';
                    action[0].innerHTML = 'D';
                }
                else{
                    action[0].innerHTML = 'L';
                }
                element.innerHTML = "<i class=\"fa fa-pencil-square\"></i>";

                lblelement = cells[22].getElementsByClassName("lblname");
                lblName = lblelement[0].innerText;
                cells[22].innerHTML = "<label class='lblname'>" + lblName + "</label>";

                lblelement = cells[23].getElementsByClassName("lblname");
                lblName = lblelement[0].innerText;
                cells[23].innerHTML = "<label class='lblname'>" + lblName + "</label>";

                lblelement = cells[25].getElementsByClassName("lblname");
                lblName = lblelement[0].innerText;
                cells[25].innerHTML = "<label class='lblname'>" + lblName + "</label>";
            }
        }

        function redirect(element, index){
            var cells = document.getElementById("Table1").rows[index].cells;
            var sr_id = cells[1].innerText;

            document.getElementById("payslip_print").action = '{{ route("emp_payroll.payslip_print") }}?id=' + sr_id;
            $("#waitingScreen").modal("show");
            $("#payslip_print").submit();
        }

        function save(){
            var grid = document.getElementById("Table1");
            var checkBoxes = grid.getElementsByClassName("checkbox1");
            var actions = grid.getElementsByClassName("action");
            var nArr = [];
            var uArr = [];
            var dArr = [];
            var qArr = [];
            var qIndx = 0;
            var message = '';

            for (var k = 0, n = 0, u = 0, d = 0, l = 0; k < actions.length; k++) {
                var row = actions[k].parentNode.parentNode;
                if (actions[k].innerHTML == 'L') {
                    l++;
                } else if (actions[k].innerHTML == 'C') {
                    //New record insert request
                    var names = row.getElementsByClassName("name");
                    if(names[0].value) {
                        nArr[n++] = JSON.stringify({"emp_display_id": names[0].value, "ctc": names[1].value,
                            "hike_amt": names[2].value, "indiv_hike_amt": names[3].value,
                            "loan_taken_amt": names[4].value, "loan_deducted_amt": names[5].value});
                    }
                } else if (actions[k].innerHTML == 'LU' || actions[k].innerHTML == 'DU') {
                    //Existing record update request
                    var names = row.getElementsByClassName("name");
                    if(names[0].value) {
                        uArr[u++] = JSON.stringify({"id": row.cells[1].innerHTML, "emp_display_id": row.cells[5].innerText,
                            "medicine_due": names[0].value, "food_charge": names[1].value, "other_deduction": names[2].value});
                        if (actions[k].innerHTML == 'LU') {
                            l++;
                        }
                    }
                } else if (actions[k].innerHTML == 'D') {
                    if(checkBoxes[k-l]) {
                        if (checkBoxes[k-l].checked) {
                            //Existing record delete request
                            dArr[d++] = JSON.stringify({"id": row.cells[1].innerHTML});
                        }
                    }
                }
            }

            if (nArr.length) {
                qArr[qIndx++] = '"C":[' + nArr.join(",") + ']';
                message = 'Record/s to insert: ' + nArr.length + "\n";
            }

            if (uArr.length) {
                qArr[qIndx++] = '"U":[' + uArr.join(",") + ']';
                message += 'Record/s to update: ' + uArr.length + "\n";
            }

            if (dArr.length) {
                qArr[qIndx++] = '"D":[' + dArr.join(",") + ']';
                message += 'Record/s to delete: ' + dArr.length + "\n";
            }
            //alert(qIndx);
            if (qIndx && confirm(message)) {
                var reqJsonData = '{' + qArr.join(',') + '}';
                //alert(reqJsonData);

                $('#fd_cud_page').val($('#fd_page').val());
                cur_recs = $('#fd_recs').val();
                $('#fd_cud_recs').val(cur_recs);
                $('#fd_cud_sort_by').val($('#fd_sort_by').val());
                $('#fd_cud_sort_type').val($('#fd_sort_type').val());
                $('#fd_cud_query').val($('#fd_query').val());
                $('#fd_cud').val(reqJsonData);
                document.getElementById("cud_action").action = '{{ route("emp_payroll.update_data") }}?page=' + $('#fd_cud_page').val();
                $("#waitingScreen").modal("show");
                $("#cud_action").submit();
            }
        }
        window.onload = pageSetting;
    </script>
    <!-- Modal #waitingScreen-->
    <div id="waitingScreen" class="modal fade" align="center">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
@endsection
