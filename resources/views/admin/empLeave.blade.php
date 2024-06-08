@extends('layouts.app1')
@section('content')
    @if (Auth::guest())
        <script>window.location.href = '{{route("login")}}';</script>
    @endif
    <style></style>
    <table>
        <tbody>
        <tr>
            <td>
                <div class="col-md-6 col-md-offset-0">
                <div class="table-responsive-sm" style="overflow-x:auto; overflow-y:auto;">
                    <table cellspacing="0" rules="all" border="1" id="Table2" style="border-collapse:collapse;" class="table table-hover table-bordered" >
                        <thead>
                        <tr>
                            <th align='center' colspan="1">
                            </th>
                            <th align='center' colspan="5">
                                <div class="row">
                                    <div style="text-align:center">
                                        <b>Employee Leave Allotment</b>
                                    </div>
                                </div>
                            </th>
                            <!--<th align='center' colspan="2">
                                <div class="row">
                                    <div style="text-align:center">
                                        <input style="text-align:center; width:40%; height:25px;" type="search" name="data_serach2" id="data_serach2" value="{{$pageSetting['query']}}" />
                                        <button style="text-align:center; font-size:12px;" onclick="searchTable2();"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </th>-->
                        </tr>
                        <tr align='center'>
                            <th>Emp Display Id</th>
                            <th>Emp Designation</th>
                            <th>CL</th>
                            <th>SL</th>
                            <th>EL</th>
                            <th>PL</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data['leaveAllotment'] as $leaveAllot)
                            <tr>
                                <td>{{$leaveAllot['emp_display_id']}}</td>
                                <td>{{$leaveAllot['designation']}}</td>
                                <td>{{$leaveAllot['CL']}}</td>
                                <td>{{$leaveAllot['SL']}}</td>
                                <td>{{$leaveAllot['EL']}}</td>
                                <td>{{$leaveAllot['PL']}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            </td>
            <td>
                <div class="col-md-8 col-md-offset-0">
                <div class="table-responsive-sm" style="overflow-x:auto; overflow-y:auto;">
                    <div style="text-align:left">
                        {!! $data['empLeaves']->links() !!}
                    </div>
                    <table cellspacing="0" rules="all" border="1" id="Table1" style="border-collapse:collapse;" class="table table-hover table-bordered" >
                        <thead>
                            <tr>
                                <th align='center' colspan="1">
                                    {!! Form::selectRange('records', 5, 100, $pageSetting['recs'], array('onchange' => 'changePageRecs(this)')) !!}
                                </th>
                                <th align='center' colspan="5">
                                    <div class="row">
                                        <div style="text-align:center">
                                            <b>Employee Leave</b>
                                        </div>
                                    </div>
                                </th>
                                <th align='center' colspan="2">
                                    <div class="row">
                                        <div style="text-align:center">
                                            <input style="text-align:center; width:40%; height:25px;" type="search" name="data_serach" id="data_serach" value="{{$pageSetting['query']}}" />
                                            <button style="text-align:center; font-size:12px;" onclick="search();"><i class="fa fa-search"></i></button>
                                            @if($pageSetting['isAdmin'])
                                                <button style="text-align:center; font-size:12px;" onclick="add();"><i class="fa fa-plus"></i></button>
                                                <button style="text-align:center; font-size:12px;" onclick="save();"><i class="fa fa-save"></i></button>
                                            @endif
                                        </div>
                                    </div>
                                </th>
                            </tr>
                            <tr align='center'>
                                <th width="10%" align='center'><i class="fa fa-trash"></i></th>
                                <th width="14%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="id" style="cursor: pointer"> Sr <span id="id_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                <th width="10%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="emp_display_id" style="cursor: pointer"> Emp. Display Id <span id="emp_display_id_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                <th width="12%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="leave_type" style="cursor: pointer"> Leave Type <span id="leave_type_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                <th width="12%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="reason" style="cursor: pointer"> Reason <span id="reason_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                <th width="14%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="start_date" style="cursor: pointer"> Start Date <span id="start_date_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                <th width="14%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="end_date" style="cursor: pointer"> End Date <span id="end_date_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                <th width="14%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="leave_status" style="cursor: pointer"> Leave Status <span id="leave_status_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr align='center'>
                            @if($data['empLeaves'])
                                <?php $index = 2; ?>
                                @foreach ($data['empLeaves'] as $empLeave)
                                    <?php $index++; ?>
                                    <tr>
                                        <td>
                                            @if($pageSetting['isAdmin'])
                                                <label class="action" hidden>D</label><input class='checkbox1' type="checkbox">
                                                <button style="font-size:10px" onclick="edit(this, {{$index}})"><i class="fa fa-pencil-square"></i></button>
                                            @endif
                                        </td>
                                        <td>{{ $empLeave->id }}</td>
                                        <td><label class="lblname">{{$empLeave->emp_display_id}}</label></td>
                                        <td><label class="lblname">{{$empLeave->leave_type}}</label></td>
                                        <td><label class="lblname">{{$empLeave->reason}}</label></td>
                                        <td><label class="lblname">{{$empLeave->start_date}}</label></td>
                                        <td><label class="lblname">{{$empLeave->end_date}}</label></td>
                                        <td><label class="lblname">{{$empLeave->leave_status}}</label></td>
                                    </tr>
                                @endforeach
                            @endif
                            </tr>
                        </tbody>
                    </table>
                    <div hidden>
                        <form id="searchfilter2" method="POST" action="{{route('shift_category.fetch_data',null,1)}}" enctype="application/json">
                            {{csrf_field()}}
                            <input type="text" name="fd_query2" id="fd_query2" value="{{$pageSetting['query']}}">
                        </form>
                        <form id="searchfilter" method="POST" action="{{route('shift_category.fetch_data',null,1)}}" enctype="application/json">
                            {{csrf_field()}}
                            <input type="text" name="fd_page" id="fd_page" value="{{$pageSetting['page']}}">
                            <input type="text" name="fd_recs" id="fd_recs" value="{{$pageSetting['recs']}}">
                            <input type="text" name="fd_sort_by" id="fd_sort_by" value="{{$pageSetting['sort_by']}}">
                            <input type="text" name="fd_sort_type" id="fd_sort_type" value="{{$pageSetting['sort_type']}}">
                            <input type="text" name="fd_query" id="fd_query" value="{{$pageSetting['query']}}">
                        </form>
                        <form id="cud_action" method="POST" action="{{route('shift_category.update_data',null,1)}}" enctype="application/json">
                            {{csrf_field()}}
                            <input type="text" name="fd_cud_page" id="fd_cud_page" value="{{$pageSetting['page']}}">
                            <input type="text" name="fd_cud_recs" id="fd_cud_recs" value="{{$pageSetting['recs']}}">
                            <input type="text" name="fd_cud_sort_by" id="fd_cud_sort_by" value="{{$pageSetting['sort_by']}}">
                            <input type="text" name="fd_cud_sort_type" id="fd_cud_sort_type" value="{{$pageSetting['sort_type']}}">
                            <input type="text" name="fd_cud_query" id="fd_cud_query" value="{{$pageSetting['query']}}">
                            <textarea name="fd_cud" id="fd_cud" rows="6" cols="50">
                            </textarea>
                        </form>
                    </div>
                </div>
            </div>
            </td>
        </tr>
        </tbody>
    </table>
    <script type="text/javascript">
        function pageSetting(){
            var column_name = $('#fd_sort_by').val();
            var order_type = $('#fd_sort_type').val();
            //alert(column_name + ':' + order_type);
            if(order_type == 'asc'){
                $('#'+column_name+'_sort_icon').html('<i class="fa fa-sort-asc"></i>');
            }
            else if(order_type == 'desc')
            {
                $('#'+column_name+'_sort_icon').html('<i class="fa fa-sort-desc"></i>');
            }

            columns = document.getElementById("Table1").rows[1].cells;
            for (var i = 1; i < columns.length-1; i++) {
                //alert($(columns[i]).data('column_name')  + ':' + column_name);
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
            document.getElementById("searchfilter").action = '{{ route("emp_leave.fetch_data") }}?page=' + page;
            $("#waitingScreen").modal("show");
            $("#searchfilter").submit();
        }

        function changePageRecs2(e){

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

        function searchTable2(){

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
            grid.insertCell(1).innerHTML = "";
            grid.insertCell(2).innerHTML = "<input class='name' type='text' style='border:none' size='8'>";
            grid.insertCell(3).innerHTML = "<select id='leave_type' class='name' style='text-align:center;height:20px;'>" +
                                "<option value='select'>Select</option><option value='CL'>CL</option><option value='SL'>SL</option>" +
                                "<option value='EL'>EL</option><option value='PL'>PL</option></select>";
            grid.insertCell(4).innerHTML = "<textarea class='name' id='reason' rows='2' cols='10'></textarea>"
            grid.insertCell(5).innerHTML = "<input type='date' class='name' id='start_date'>";
            grid.insertCell(6).innerHTML = "<input type='date' class='name' id='end_date'>";
            grid.insertCell(7).innerHTML = "<select id='leave_status' class='name' style='text-align:center;height:20px;'>" +
                "<option value='select'>Select</option><option value='Pending'>Pending</option><option value='Denied'>Denied</option>" +
                "<option value='Accepted'>Accepted</option><option value='Withdraw'>Withdraw</option></select>";
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
            var lblname = cells[7].getElementsByClassName("lblname");

            if(element.innerHTML.search("pencil") != -1){
                if(chkbx[0]) {
                    chkbx[0].style.display = 'none';
                    action[0].innerHTML = 'DU';
                }
                else {
                    action[0].innerHTML = 'LU';
                }
                element.innerHTML = "<i class='fa fa-undo'></i>";

                lblelement = cells[2].getElementsByClassName("lblname");
                lblName = lblelement[0].innerText;
                lblIndex = lblelement[0].htmlFor;
                cells[2].innerHTML = "<label class='lblname' for='" + lblIndex + "' hidden>" + lblName + "</label>" +
                    "<input type='text' class='name' id='emp_display_id' style='border:none' value='" + lblName + "' size='8'>";

                lblelement = cells[3].getElementsByClassName("lblname");
                lblName = lblelement[0].innerText;
                lblIndex = (lblName ? lblName : 'select');
                cells[3].innerHTML = "<label class='lblname' hidden>" + lblName + "</label><select id='leave_type'" +
                    "class='name' style='text-align:center;height:20px;'><option value='select'>Select</option>" +
                    "<option value='CL'>CL</option><option value='SL'>SL</option>" +
                    "<option value='EL'>EL</option><option value='PL'>PL</option></select>";
                cells[3].querySelector('option[value=' + lblIndex + ']').selected = true;

                lblelement = cells[4].getElementsByClassName("lblname");
                lblName = lblelement[0].innerText;
                cells[4].innerHTML = "<label class='lblname' hidden>" + lblName + "</label>" +
                    "<textarea class='name' id='reason' rows='2' cols='10'>" + lblName + "</textarea>";

                lblelement = cells[5].getElementsByClassName("lblname");
                lblName = lblelement[0].innerText;
                cells[5].innerHTML = "<label class='lblname' hidden>" + lblName + "</label>" +
                    "<input type='date' class='name' id='start_date' value='" + lblName + "'>";

                lblelement = cells[6].getElementsByClassName("lblname");
                lblName = lblelement[0].innerText;
                cells[6].innerHTML = "<label class='lblname' hidden>" + lblName + "</label>" +
                    "<input type='date' class='name' id='end_date' value='" + lblName + "'>";

                lblName = lblname[0].innerText;
                cells[7].innerHTML = "<label class='lblname' hidden>" + lblName + "</label>" +
                    "<select id='leave_status' class='name' style='text-align:center;height:20px;'>" +
                    "<option value='select'>Select</option>" +
                    "<option value='Pending'>Pending</option>" +
                    "<option value='Denied'>Denied</option>" +
                    "<option value='Accepted'>Accepted</option>" +
                    "</select>";
                cells[7].querySelector('option[value=' + lblName + ']').selected = true;
            }
            else{
                if(chkbx[0]) {
                    chkbx[0].style.display = '';
                    action[0].innerHTML = 'D';
                }
                else{
                    action[0].innerHTML = 'L';
                }
                element.innerHTML = "<i class='fa fa-pencil-square'></i>";
                //lblName = lblname[0].innerText;

                for(var i = 2; i <= 7; i++) {
                    lblelement = cells[i].getElementsByClassName("lblname");
                    lblName = lblelement[0].innerText;
                    cells[i].innerHTML = "<label class='lblname'>" + lblName + "</label>";
                }
            }
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
                    if(names[0].value && names[1].value && names[3].value && names[4].value) {
                        nArr[n++] = JSON.stringify({"emp_display_id": names[0].value, "leave_type": names[1].value,
                            "reason": names[2].value, "start_date": names[3].value, "end_date": names[4].value,
                            "leave_status": names[5].value});
                    }
                    else{
                        alert('Employee display Id, Leave Type, Start Date and End Date are mandatory');
                    }
                } else if (actions[k].innerHTML == 'LU' || actions[k].innerHTML == 'DU') {
                    //Existing record update request
                    var names = row.getElementsByClassName("name");
                    if(names[0].value && names[1].value && names[3].value && names[4].value) {
                        uArr[u++] = JSON.stringify({"id": row.cells[1].innerHTML, "emp_display_id": names[0].value,
                            "leave_type": names[1].value, "reason": names[2].value, "start_date": names[3].value,
                            "end_date": names[4].value, "leave_status": names[5].value});
                        if (actions[k].innerHTML == 'LU') {
                            l++;
                        }
                    }
                    else{
                        alert('Employee display Id, Leave Type, Start Date and End Date are mandatory');
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
                document.getElementById("cud_action").action = '{{ route("emp_leave.update_data") }}?page=' + $('#fd_cud_page').val();
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
