@extends('layouts.app1')
@section('content')
    @if (Auth::guest())
        <script>window.location.href = '{{route("login")}}';</script>
    @endif
    <script></script>
    <div class="container">
        <div class="row">
            <div class="col-md-7 col-md-offset-2">
                <div class="table-responsive-sm" style="overflow-x:auto; overflow-y:auto;">
                    <div style="text-align:left">
                        {!! $leave_Schedules['leaveSchedules']->links() !!}
                    </div>
                    <table cellspacing="0" rules="all" border="1" id="Table1" style="border-collapse:collapse;" class="table table-hover table-bordered" >
                        <thead>
                            <tr>
                                <th align='center' colspan="1">
                                    {!! Form::selectRange('records', 5, 100, $pageSetting['recs'], array('onchange' => 'changePageRecs(this)')) !!}
                                </th>
                                <th align='center' colspan="4">
                                    <div class="row">
                                        <div style="text-align:center">
                                            <b>Leave Schedule</b>
                                        </div>
                                    </div>
                                </th>
                                <th align='center' colspan="2">
                                    <div class="row">
                                        <div style="text-align:center">
                                            <input style="text-align:center; width:40%; height:25px;" type="search" name="data_serach" id="data_serach" value="{{$pageSetting['query']}}" />
                                            <button style="text-align:center; font-size:12px;" onclick="search();"><i class="fa fa-search"></i></button>
                                            <button style="text-align:center; font-size:12px;" onclick="add();"><i class="fa fa-plus"></i></button>
                                            <button style="text-align:center; font-size:12px;" onclick="save();"><i class="fa fa-save"></i></button>
                                        </div>
                                    </div>
                                </th>
                            </tr>
                            <tr align='center'>
                                <th width="8%" align='center'><i class="fa fa-trash"></i></th>
                                <th width="8%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="id" style="cursor: pointer"> Sr <span id="id_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                <th width="20%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="designation" style="cursor: pointer"> Designation <span id="designation_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                <th width="15%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="leave_type" style="cursor: pointer"> Leave Type <span id="leave_type_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                <th width="05%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="day_count" style="cursor: pointer"> Leave Count[Day] <span id="day_count_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                <th width="34%" align='center'> In Use(count) </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr align='center'>
                            @if($leave_Schedules['leaveSchedules'])
                                <?php $index = 2; ?>
                                @foreach ($leave_Schedules['leaveSchedules'] as $leaveSchedule)
                                    <?php $index++; ?>
                                    <tr>
                                        @if($leaveSchedule->in_use == 0)
                                            <td><label class="action" hidden>D</label><input class='checkbox1' type="checkbox"></td>
                                        @else
                                            <td><label class="action" hidden>L</label>Locked</td>
                                        @endif
                                        <td>{{ $leaveSchedule->id }}</td>
                                        @php
                                            $designation_index = null;
                                            foreach($leave_Schedules['empDesignations'] as $key => $value){
                                                if($value == $leaveSchedule->designation){
                                                    $designation_index = $key;
                                                    break;
                                                }
                                            }
                                        @endphp
                                        <td><label class="lblname" for="{{$designation_index}}">{{$leaveSchedule->designation}}</label></td>
                                        @php
                                            $leave_type_index = null;
                                            foreach($leave_Schedules['leaveCategories'] as $key => $value){
                                                if($value == $leaveSchedule->leave_type){
                                                    $leave_type_index = $key;
                                                    break;
                                                }
                                            }
                                        @endphp
                                        <td><label class="lblname" for="{{$leave_type_index}}">{{$leaveSchedule->leave_type}}</label></td>
                                        <td><label class="lblname">{{$leaveSchedule->day_count}}</label></td>
                                        <td>
                                            @php
                                                $in_use = 'No';
                                                if($leaveSchedule->in_use > 0){
                                                    $in_use = 'Yes';
                                                }
                                                $in_use = $in_use . '(' . $leaveSchedule->in_use . ')';
                                            @endphp
                                            {{ $in_use }}
                                            <button style="font-size:15px" onclick="edit(this, {{$index}})"><i class="fa fa-pencil-square"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tr>
                        </tbody>
                    </table>
                    <div hidden>
                        <form id="searchfilter" method="POST" action="{{route('leave_schedule.fetch_data',null,1)}}" enctype="application/json">
                            {{csrf_field()}}
                            <input type="text" name="fd_page" id="fd_page" value="{{$pageSetting['page']}}">
                            <input type="text" name="fd_recs" id="fd_recs" value="{{$pageSetting['recs']}}">
                            <input type="text" name="fd_sort_by" id="fd_sort_by" value="{{$pageSetting['sort_by']}}">
                            <input type="text" name="fd_sort_type" id="fd_sort_type" value="{{$pageSetting['sort_type']}}">
                            <input type="text" name="fd_query" id="fd_query" value="{{$pageSetting['query']}}">
                        </form>
                        <form id="cud_action" method="POST" action="{{route('leave_schedule.update_data',null,1)}}" enctype="application/json">
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

            var columns = document.getElementById("Table1").rows[1].cells;
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
            alert(str);
            $('#fd_page').val(page);
            $('#fd_recs').val(recs);
            $('#fd_sort_by').val(sort_by);
            $('#fd_sort_type').val(sort_type);
            $('#fd_query').val(query);
            document.getElementById("searchfilter").action = '{{ route("leave_schedule.fetch_data") }}?page=' + page;

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
            grid.insertCell(1).innerHTML = "";

            grid.insertCell(2).innerHTML = "{{Html::decode(Form::select('designation', $leave_Schedules['empDesignations'], null, array('class' =>'name')))}}";
            grid.cells[2].innerHTML = grid.cells[2].innerText;

            grid.insertCell(3).innerHTML = "{{Html::decode(Form::select('leave_type', $leave_Schedules['leaveCategories'], null, array('class' =>'name')))}}";
            grid.cells[3].innerHTML = grid.cells[3].innerText;

            grid.insertCell(4).innerHTML = "<input class='name' type='number' min='1' max='50' value='10' style='border:none'>";

            grid.insertCell(5).innerHTML = "";
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
            var lblIndex = '';

            if(element.innerHTML.search("pencil") != -1){
                if(chkbx[0]) {
                    chkbx[0].style.display = 'none';
                    action[0].innerHTML = 'DU';
                }
                else {
                    action[0].innerHTML = 'LU';
                }
                element.innerHTML = "<i class=\"fa fa-undo\"></i>";

                lblelement = cells[2].getElementsByClassName("lblname");
                lblIndex = lblelement[0].htmlFor;
                lblName = lblelement[0].innerText;
                cells[2].innerHTML = "{{Html::decode(Form::select('designation', $leave_Schedules['empDesignations'], null, array('class' =>'name')))}}";
                cells[2].innerHTML = "<label class='lblname' for='" + lblIndex + "' hidden>" + lblName + "</label>" + cells[2].innerText;
                var designation = cells[2].getElementsByClassName("name");
                designation[0].options[lblIndex].selected = true;

                lblelement = cells[3].getElementsByClassName("lblname");
                lblIndex = lblelement[0].htmlFor;
                lblName = lblelement[0].innerText;
                cells[3].innerHTML = "{{Html::decode(Form::select('leave_type', $leave_Schedules['leaveCategories'], null, array('class' =>'name')))}}";
                cells[3].innerHTML = "<label class='lblname' for='" + lblIndex + "' hidden>" + lblName + "</label>" + cells[3].innerText;
                var leave_type = cells[3].getElementsByClassName("name");
                leave_type[0].options[lblIndex].selected = true;

                lblelement = cells[4].getElementsByClassName("lblname");
                lblName = lblelement[0].innerText;
                cells[4].innerHTML = "<label class='lblname' hidden>" + lblName + "</label><input class='name' type='number' style='border:none' min='1' max='50' value='" + lblName +  "'>";
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

                lblelement = cells[2].getElementsByClassName("lblname");
                lblIndex = lblelement[0].htmlFor;
                lblName = lblelement[0].innerText;
                cells[2].innerHTML = "<label class='lblname' for='" + lblIndex + "' >" + lblName + "</label>";

                lblelement = cells[3].getElementsByClassName("lblname");
                lblIndex = lblelement[0].htmlFor;
                lblName = lblelement[0].innerText;
                cells[3].innerHTML = "<label class='lblname' for='" + lblIndex + "' >" + lblName + "</label>";

                lblelement = cells[4].getElementsByClassName("lblname");
                lblName = lblelement[0].innerText;
                cells[4].innerHTML = "<label class='lblname'>" + lblName + "</label>";
                //name[0].readOnly  = 'readonly';
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
                    if(names[0].value) {
                        nArr[n++] = JSON.stringify({"designation": names[0].options[names[0].selectedIndex].text,
                        "leave_type": names[1].options[names[1].selectedIndex].text, "day_count": names[2].value});
                    }
                } else if (actions[k].innerHTML == 'LU' || actions[k].innerHTML == 'DU') {
                    //Existing record update request
                    var names = row.getElementsByClassName("name");
                    if(names[0].value) {
                        uArr[u++] = JSON.stringify({"id": row.cells[1].innerHTML,
                            "designation": names[0].options[names[0].selectedIndex].text,
                            "leave_type": names[1].options[names[1].selectedIndex].text, "day_count": names[2].value});
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
                document.getElementById("cud_action").action = '{{ route("leave_schedule.update_data") }}?page=' + $('#fd_cud_page').val();
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
