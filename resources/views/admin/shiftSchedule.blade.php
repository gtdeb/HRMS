@extends('layouts.app1')
@section('content')
    @if (Auth::guest())
        <script>window.location.href = '{{route("login")}}';</script>
    @endif
    <script></script>
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-lg-offset-0">
                <div class="table-responsive-lg" style="overflow-x:auto;overflow-y:auto">
                    <div style="text-align:left">
                        {!! $shifts['shiftSchedules']->links() !!}
                    </div>
                    <table cellspacing="0" rules="all" border="1" id="Table1" style="border-collapse:collapse;" class="table table-hover table-bordered" >
                        <thead>
                            <tr>
                                <th align='center' colspan="1">
                                    {!! Form::selectRange('records', 5, 100, $pageSetting['recs'], array('onchange' => 'changePageRecs(this)')) !!}
                                </th>
                                <th align='center' colspan="10">
                                    <div class="row">
                                        <div style="text-align:center">
                                            <b>Shift Schedule</b>
                                        </div>
                                    </div>
                                </th>
                                <th align='center' colspan="1">
                                    <div class="row">
                                        <div style="text-align:center">
                                            <input style="text-align:center;width:60%; height:25px;" type="search" name="data_serach" id="data_serach" width="40%" value="{{$pageSetting['query']}}" />
                                            <button style="text-align:center;font-size:12px"; onclick="search();"><i class="fa fa-search"></i></button><br>
                                            <button style="text-align:center;font-size:12px"; onclick="add();"><i class="fa fa-plus"></i></button>
                                            <button style="text-align:center;font-size:12px"; onclick="save();"><i class="fa fa-save"></i></button>
                                        </div>
                                    </div>
                                </th>
                            </tr>
                            <tr align='center'>
                                <th width="9%" align='center'><i class="fa fa-trash"></i></th>
                                <th width="5%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="id" style="cursor: pointer"> Sr <span id="id_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                <th width="10%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="name" style="cursor: pointer"> Name <span id="name_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                <th width="10%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="start_time" style="cursor: pointer"> Start Time <span id="start_time_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                <th width="10%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="end_time" style="cursor: pointer"> End Time <span id="end_time_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                <th width="10%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="shift_type" style="cursor: pointer"> Shift Type <span id="shift_type_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                <th width="7%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="half_day_dur_minutes" style="cursor: pointer"> Half Day Duration(HH:MM) <span id="half_day_dur_minutes_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                <th width="7%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="delay_time_minutes" style="cursor: pointer"> Duration Delay(HH:MM) <span id="delay_time_minutes_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                <th width="7%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="login_punch_dur_mins" style="cursor: pointer"> Log-In Delay(HH:MM) <span id="login_punch_dur_mins_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                <th width="7%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="logout_punch_dur_mins" style="cursor: pointer"> Log-Out Delay(HH:MM) <span id="logout_punch_dur_mins_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                <th width="7%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="ot_dur_mins" style="cursor: pointer"> OT Log-Out-In Delay(HH:MM) <span id="ot_dur_mins_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                <th width="11%" align='center'> Action </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr align='center'>
                            @if($shifts['shiftSchedules'])
                                <?php $index = 2; ?>
                                @foreach ($shifts['shiftSchedules'] as $shiftSchedule)
                                    <?php $index++; ?>
                                    <tr>
                                        @if($shiftSchedule->in_use == 0)
                                            <td><label class="action" hidden>D</label><input class='checkbox1' type="checkbox"></td>
                                        @else
                                            <td><label class="action" hidden>L</label>Locked</td>
                                        @endif
                                        <td>{{ $shiftSchedule->id }}</td>
                                        <td><label class="lblname">{{$shiftSchedule->name}}</label></td>
                                        @php
                                            $s_time = date("g:i A", strtotime($shiftSchedule->start_time));
                                            $e_time = date("g:i A", strtotime($shiftSchedule->end_time));
                                        @endphp
                                        <td><label class="lblname">{{$s_time}}</label></td>
                                        <td><label class="lblname">{{$e_time}}</label></td>
                                        @php
                                            $shift_type_index = null;
                                            foreach($shifts['shiftCategories'] as $key => $value){
                                                if($value == $shiftSchedule->shift_type){
                                                    $shift_type_index = $key;
                                                    break;
                                                }
                                            }
                                        @endphp
                                        <td><label class="lblname" for="{{$shift_type_index}}">{{$shiftSchedule->shift_type}}</label></td>
                                        @php
                                            $hrs = floor($shiftSchedule->half_day_dur_minutes / 60);
                                            $mins = ($shiftSchedule->half_day_dur_minutes % 60);
                                        @endphp
                                        <td><label class="lblname">{{$hrs.':'.$mins}}</label></td>
                                        @php
                                            $hrs = floor($shiftSchedule->delay_time_minutes / 60);
                                            $mins = ($shiftSchedule->delay_time_minutes % 60);
                                        @endphp
                                        <td><label class="lblname">{{$hrs.':'.$mins}}</label></td>
                                        @php
                                            $hrs = floor($shiftSchedule->login_punch_dur_mins / 60);
                                            $mins = ($shiftSchedule->login_punch_dur_mins % 60);
                                        @endphp
                                        <td><label class="lblname">{{$hrs.':'.$mins}}</label></td>
                                        @php
                                            $hrs = floor($shiftSchedule->logout_punch_dur_mins / 60);
                                            $mins = ($shiftSchedule->logout_punch_dur_mins % 60);
                                        @endphp
                                        <td><label class="lblname">{{$hrs.':'.$mins}}</label></td>
                                        @php
                                            $hrs = floor($shiftSchedule->ot_dur_mins / 60);
                                            $mins = ($shiftSchedule->ot_dur_mins % 60);
                                        @endphp
                                        <td><label class="lblname">{{$hrs.':'.$mins}}</label></td>
                                        <td>
                                            @php
                                                $in_use = 'No';
                                                if($shiftSchedule->in_use > 0){
                                                    $in_use = 'Yes';
                                                }
                                                $in_use = $in_use . '(' . $shiftSchedule->in_use . ')';
                                            @endphp
                                            <button style="font-size:15px" onclick="edit(this, {{$index}})"><i class="fa fa-pencil-square"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tr>
                        </tbody>
                    </table>
                    <div hidden>
                        <form id="searchfilter" method="POST" action="{{route('shift_schedule.fetch_data',null,1)}}" enctype="application/json">
                            {{csrf_field()}}
                            <input type="text" name="fd_page" id="fd_page" value="{{$pageSetting['page']}}">
                            <input type="text" name="fd_recs" id="fd_recs" value="{{$pageSetting['recs']}}">
                            <input type="text" name="fd_sort_by" id="fd_sort_by" value="{{$pageSetting['sort_by']}}">
                            <input type="text" name="fd_sort_type" id="fd_sort_type" value="{{$pageSetting['sort_type']}}">
                            <input type="text" name="fd_query" id="fd_query" value="{{$pageSetting['query']}}">
                        </form>
                        <form id="cud_action" method="POST" action="{{route('shift_schedule.update_data',null,1)}}" enctype="application/json">
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
            document.getElementById("searchfilter").action = '{{ route("shift_schedule.fetch_data") }}?page=' + page;
            $("#waitingScreen").modal("show");
            $("#searchfilter").submit();
        }

        function changePageRecs(e){
            var query = $('#fd_query').val();
            var column_name = $('#fd_sort_by').val();
            var sort_type = $('#fd_sort_type').val();
            var page = 1;
            var recs = Number(e.value);
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
                //$('#'+column_name+'_icon').html('<i class="fa fa-sort"></i>');
            }
            else if(order_type == 'unsorted' || order_type == 'desc')
            {
                $(this).data('sorting_type', 'asc');
                reverse_order = 'asc';
                //$('#'+column_name+'_icon').html('<i class="fa fa-sort"></i>');
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

        function getTime(minutes){
            if(minutes == 0){
                var advDate = new Date();
            }
            else {
                var date = new Date();
                var advDate = new Date(date.getTime() + minutes * 60000);
            }
            var hours = advDate.getHours();
            var minutes = advDate.getMinutes();
            var ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12; // the hour '0' should be '12'
            hours = hours < 10 ? '0' + hours : hours;
            minutes = minutes < 10 ? '0' + minutes : minutes;
            return hours + ':' + minutes + ' ' + ampm;
        }

        function add(){
            var grid = document.getElementById("Table1").insertRow(-1);
            grid.insertCell(0).innerHTML = "<label class='action' hidden>C</label><button style='font-size:8px' onclick='remove();'><i class='fa fa-remove'></i></button>";
            grid.insertCell(1).innerHTML = "";
            grid.insertCell(2).innerHTML = "<input type='text' id='name' class='name' style='border:none' size='8'>";

            var cur_time = getTime(0);
            var lblArr = new Array();

            lblArr = cur_time.split(/[:\s]/);
            grid.insertCell(3).innerHTML = "<input type='number' id='s_time_hr' class='s_time' style='text-align:center; height:20px;' min='01' max='12' value='" +
                lblArr[0] + "'>" + ":" +
                "<input type='number' id='s_time_mi' class='s_time' style='text-align:center;height:20px;' min='00' max='59' value='" +
                lblArr[1] + "'>" + "  " +
                "<select id='s_time_12' class='s_time' style='text-align:center;height:20px;'>" +
                "<option value='AM'>AM</option><option value='PM'>PM</option></select>";
            grid.cells[3].querySelector('option[value=' + lblArr[2] + ']').selected = true;

            cur_time = getTime(480);
            lblArr = cur_time.split(/[:\s]/);
            grid.insertCell(4).innerHTML = "<input type='number' id='e_time_hr' class='e_time' style='text-align:center; height:20px;' min='01' max='12' value='" +
                lblArr[0] + "'>" + ":" +
                "<input type='number' id='e_time_mi' class='e_time' style='text-align:center;height:20px;' min='00' max='59' value='" +
                lblArr[1] + "'>" + "  " +
                "<select id='e_time_12' class='e_time' style='text-align:center;height:20px;'>" +
                "<option value='AM'>AM</option><option value='PM'>PM</option></select>";
            grid.cells[4].querySelector('option[value=' + lblArr[2] + ']').selected = true;

            grid.insertCell(5).innerHTML = "{{Html::decode(Form::select('shift_type', $shifts['shiftCategories'], null, array('class' =>'shift_type')))}}";
            grid.cells[5].innerHTML = grid.cells[5].innerText;

            grid.insertCell(6).innerHTML = "<input type='number' id='half_day_dur_hr' class='half_day_dur' style='text-align:center; height:20px;' min='00' max='08' value='01'>" +
                ":" + "<input type='number' id='half_day_dur_mi' class='half_day_dur' style='text-align:center;height:20px;' min='00' max='59' value='00'>";

            grid.insertCell(7).innerHTML = "<input type='number' id='dur_delay_hr' class='dur_delay' style='text-align:center; height:20px;' min='00' max='08' value='01'>" +
            ":" + "<input type='number' id='dur_delay_mi' class='dur_delay' style='text-align:center;height:20px;' min='00' max='59' value='00'>";

            grid.insertCell(8).innerHTML = "<input type='number' id='login_dur_hr' class='login_dur' style='text-align:center; height:20px;' min='00' max='08' value='00'>" +
            ":" + "<input type='number' id='login_dur_mi' class='login_dur' style='text-align:center;height:20px;' min='00' max='59' value='30'>";

            grid.insertCell(9).innerHTML = "<input type='number' id='logout_dur_hr' class='logout_dur' style='text-align:center; height:20px;' min='00' max='08' value='01'>" +
            ":" + "<input type='number' id='logout_dur_mi' class='logout_dur' style='text-align:center;height:20px;' min='00' max='59' value='00'>";

            grid.insertCell(10).innerHTML = "<input type='number' id='ot_dur_hr' class='ot_dur' style='text-align:center; height:20px;' min='00' max='08' value='01'>" +
            ":" + "<input type='number' id='ot_dur_mi' class='ot_dur' style='text-align:center;height:20px;' min='00' max='59' value='00'>";

            grid.insertCell(11).innerHTML = "";
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
            var lblName = '';
            var lblIndex = '';
            var lblArr = new Array();

            if(element.innerHTML.search("pencil") != -1) {
                if (chkbx[0]) {
                    chkbx[0].style.display = 'none';
                    action[0].innerHTML = 'DU';
                } else {
                    action[0].innerHTML = 'LU';
                }
                element.innerHTML = "<i class=\"fa fa-undo\"></i>";

                lblelement = cells[2].getElementsByClassName("lblname");
                lblName = lblelement[0].innerText;
                cells[2].innerHTML = "<label class='lblname' hidden>" + lblName + "</label><input type='text' class='name' id='name' style='border:none' value='" + lblName + "' size='8'>";

                lblelement = cells[3].getElementsByClassName("lblname");
                lblName = lblelement[0].innerText;
                lblArr = lblName.split(/[:\s]/);
                cells[3].innerHTML = "<label class='lblname' hidden>" + lblName +
                    "</label><input type='number' id='s_time_hr' class='s_time' style='text-align:center; height:20px;' min='01' max='12' value='" +
                    lblArr[0] + "'>" + ":" +
                    "<input type='number' id='s_time_mi' class='s_time' style='text-align:center;height:20px;' min='00' max='59' value='" +
                    lblArr[1] + "'>" + "  " +
                    "<select id='s_time_12' class='s_time' style='text-align:center;height:20px;'>" +
                    "<option value='AM'>AM</option><option value='PM'>PM</option></select>";
                cells[3].querySelector('option[value=' + lblArr[2] + ']').selected = true;

                lblelement = cells[4].getElementsByClassName("lblname");
                lblName = lblelement[0].innerText;
                lblArr = lblName.split(/[:\s]/);
                cells[4].innerHTML = "<label class='lblname' hidden>" + lblName +
                    "</label><input type='number' id='e_time_hr' class='e_time' style='text-align:center; height:20px;' min='01' max='12' value='" +
                    lblArr[0] + "'>" + ":" +
                    "<input type='number' id='e_time_mi' class='e_time' style='text-align:center;height:20px;' min='00' max='59' value='" +
                    lblArr[1] + "'>" + "  " +
                    "<select id='e_time_12' class='e_time' style='text-align:center;height:20px;'>" +
                    "<option value='AM'>AM</option><option value='PM'>PM</option></select>";
                cells[4].querySelector('option[value=' + lblArr[2] + ']').selected = true;

                lblelement = cells[5].getElementsByClassName("lblname");
                //alert(lblelement[0].htmlFor + " " + lblelement[0].innerText);
                lblIndex = lblelement[0].htmlFor;
                lblName = lblelement[0].innerText;
                cells[5].innerHTML = "{{Html::decode(Form::select('shift_type', $shifts['shiftCategories'], null, array('class' =>'shift_type')))}}";
                cells[5].innerHTML = "<label class='lblname' for='" + lblIndex + "' hidden>" + lblName + "</label>" + cells[5].innerText;
                var shift_type = cells[5].getElementsByClassName("shift_type");
                shift_type[0].options[lblIndex].selected = true;

                lblelement = cells[6].getElementsByClassName("lblname");
                lblName = lblelement[0].innerText;
                lblArr = lblName.split(/[:\s]/);
                cells[6].innerHTML = "<label class='lblname' hidden>" + lblName +
                    "</label><input type='number' id='half_day_dur_hr' class='half_day_dur' style='text-align:center; height:20px;' min='00' max='08' value='" +
                    lblArr[0] + "'>" + ":" +
                    "<input type='number' id='half_day_dur_mi' class='half_day_dur' style='text-align:center;height:20px;' min='00' max='59' value='" +
                    lblArr[1] + "'>";

                lblelement = cells[7].getElementsByClassName("lblname");
                lblName = lblelement[0].innerText;
                lblArr = lblName.split(/[:\s]/);
                cells[7].innerHTML = "<label class='lblname' hidden>" + lblName +
                    "</label><input type='number' id='dur_delay_hr' class='dur_delay' style='text-align:center; height:20px;' min='00' max='08' value='" +
                    lblArr[0] + "'>" + ":" +
                    "<input type='number' id='dur_delay_mi' class='dur_delay' style='text-align:center;height:20px;' min='00' max='59' value='" +
                    lblArr[1] + "'>";

                lblelement = cells[8].getElementsByClassName("lblname");
                lblName = lblelement[0].innerText;
                lblArr = lblName.split(/[:\s]/);
                cells[8].innerHTML = "<label class='lblname' hidden>" + lblName +
                    "</label><input type='number' id='login_dur_hr' class='login_dur' style='text-align:center; height:20px;' min='00' max='08' value='" +
                    lblArr[0] + "'>" + ":" +
                    "<input type='number' id='login_dur_mi' class='login_dur' style='text-align:center;height:20px;' min='00' max='59' value='" +
                    lblArr[1] + "'>";

                lblelement = cells[9].getElementsByClassName("lblname");
                lblName = lblelement[0].innerText;
                lblArr = lblName.split(/[:\s]/);
                cells[9].innerHTML = "<label class='lblname' hidden>" + lblName +
                    "</label><input type='number' id='logout_dur_hr' class='logout_dur' style='text-align:center; height:20px;' min='00' max='08' value='" +
                    lblArr[0] + "'>" + ":" +
                    "<input type='number' id='logout_dur_mi' class='logout_dur' style='text-align:center;height:20px;' min='00' max='59' value='" +
                    lblArr[1] + "'>";

                lblelement = cells[10].getElementsByClassName("lblname");
                lblName = lblelement[0].innerText;
                lblArr = lblName.split(/[:\s]/);
                cells[10].innerHTML = "<label class='lblname' hidden>" + lblName +
                    "</label><input type='number' id='ot_dur_hr' class='ot_dur' style='text-align:center; height:20px;' min='00' max='08' value='" +
                    lblArr[0] + "'>" + ":" +
                    "<input type='number' id='ot_dur_mi' class='ot_dur' style='text-align:center;height:20px;' min='00' max='59' value='" +
                    lblArr[1] + "'>";
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
                lblName = lblelement[0].innerText;
                cells[2].innerHTML = "<label class='lblname'>" + lblName + "</label>";

                lblelement = cells[3].getElementsByClassName("lblname");
                lblName = lblelement[0].innerText;
                cells[3].innerHTML = "<label class='lblname'>" + lblName + "</label>";

                lblelement = cells[4].getElementsByClassName("lblname");
                lblName = lblelement[0].innerText;
                cells[4].innerHTML = "<label class='lblname'>" + lblName + "</label>";

                lblelement = cells[5].getElementsByClassName("lblname");
                lblIndex = lblelement[0].htmlFor;
                lblName = lblelement[0].innerText;
                cells[5].innerHTML = "<label class='lblname' for='" + lblIndex + "' >" + lblName + "</label>";

                lblelement = cells[6].getElementsByClassName("lblname");
                lblName = lblelement[0].innerText;
                cells[6].innerHTML = "<label class='lblname'>" + lblName + "</label>";

                lblelement = cells[7].getElementsByClassName("lblname");
                lblName = lblelement[0].innerText;
                cells[7].innerHTML = "<label class='lblname'>" + lblName + "</label>";

                lblelement = cells[8].getElementsByClassName("lblname");
                lblName = lblelement[0].innerText;
                cells[8].innerHTML = "<label class='lblname'>" + lblName + "</label>";

                lblelement = cells[9].getElementsByClassName("lblname");
                lblName = lblelement[0].innerText;
                cells[9].innerHTML = "<label class='lblname'>" + lblName + "</label>";

                lblelement = cells[10].getElementsByClassName("lblname");
                lblName = lblelement[0].innerText;
                cells[10].innerHTML = "<label class='lblname'>" + lblName + "</label>";
            }
        }

        function save(){
            var grid = document.getElementById("Table1");
            var checkBoxes = grid.getElementsByClassName("checkbox1");
            var actions = grid.getElementsByClassName("action");
            var name, shift_type;
            var time_start, time_end, shift_time;
            var half_day_dur, dur_delay, login_dur, logout_dur, ot_dur, dur;
            var nArr = [];
            var uArr = [];
            var dArr = [];
            var qArr = [];
            var qIndx = 0;
            var message = '';

            //alert(actions.length);
            for (var k = 0, n = 0, u = 0, d = 0, l = 0; k < actions.length; k++) {
                var row = actions[k].parentNode.parentNode;
                if (actions[k].innerHTML == 'L') {
                    l++;
                } else if (actions[k].innerHTML == 'C') {
                    //New record insert request
                    name = row.cells[2].getElementsByClassName("name");

                    shift_time = row.cells[3].getElementsByClassName("s_time");
                    //alert(shift_time[2].value);
                    if(shift_time[2].value == 'PM'){
                        time_start = Number(shift_time[0].value) + Number(12);
                        time_start = time_start + ':' + shift_time[1].value;
                    }
                    else{
                        time_start = shift_time[0].value + ':' + shift_time[1].value;
                    }

                    shift_time = row.cells[4].getElementsByClassName("e_time");
                    if(shift_time[2].value == 'PM'){
                        time_end = Number(shift_time[0].value) + Number(12);
                        time_end = time_end + ':' + shift_time[1].value;
                    }
                    else{
                        time_end = shift_time[0].value + ':' + shift_time[1].value;
                    }

                    shift_type = row.cells[5].getElementsByClassName("shift_type");

                    dur = row.cells[6].getElementsByClassName("half_day_dur");
                    half_day_dur = Number(dur[0].value)* Number(60) + Number(dur[1].value);

                    dur = row.cells[7].getElementsByClassName("dur_delay");
                    dur_delay = Number(dur[0].value)* Number(60) + Number(dur[1].value);

                    dur = row.cells[8].getElementsByClassName("login_dur");
                    login_dur = Number(dur[0].value)* Number(60) + Number(dur[1].value);

                    dur = row.cells[9].getElementsByClassName("logout_dur");
                    logout_dur = Number(dur[0].value)* Number(60) + Number(dur[1].value);

                    dur = row.cells[10].getElementsByClassName("ot_dur");
                    ot_dur = Number(dur[0].value)* Number(60) + Number(dur[1].value);

                    nArr[n++] = JSON.stringify({"name": name[0].value, "s_time": time_start, "e_time": time_end,
                        "shift_type": shift_type[0].options[shift_type[0].selectedIndex].text, "half_day_dur": half_day_dur, "dur_delay": dur_delay,
                        "login_dur": login_dur, "logout_dur": logout_dur, "ot_dur": ot_dur});
                }
                else if (actions[k].innerHTML == 'LU' || actions[k].innerHTML == 'DU') {
                    //Existing record update request
                    name = row.cells[2].getElementsByClassName("name");

                    shift_time = row.cells[3].getElementsByClassName("s_time");
                    if(shift_time[2].value == 'PM'){
                        time_start = Number(shift_time[0].value) + Number(12);
                        time_start = time_start + ':' + shift_time[1].value;
                    }
                    else{
                        time_start = shift_time[0].value + ':' + shift_time[1].value;
                    }

                    shift_time = row.cells[4].getElementsByClassName("e_time");
                    if(shift_time[2].value == 'PM'){
                        time_end = Number(shift_time[0].value) + Number(12);
                        time_end = time_end + ':' + shift_time[1].value;
                    }
                    else{
                        time_end = shift_time[0].value + ':' + shift_time[1].value;
                    }

                    shift_type = row.cells[5].getElementsByClassName("shift_type");

                    dur = row.cells[6].getElementsByClassName("half_day_dur");
                    half_day_dur = Number(dur[0].value)* Number(60) + Number(dur[1].value);

                    dur = row.cells[7].getElementsByClassName("dur_delay");
                    dur_delay = Number(dur[0].value)* Number(60) + Number(dur[1].value);

                    dur = row.cells[8].getElementsByClassName("login_dur");
                    login_dur = Number(dur[0].value)* Number(60) + Number(dur[1].value);

                    dur = row.cells[9].getElementsByClassName("logout_dur");
                    logout_dur = Number(dur[0].value)* Number(60) + Number(dur[1].value);

                    dur = row.cells[10].getElementsByClassName("ot_dur");
                    ot_dur = Number(dur[0].value)* Number(60) + Number(dur[1].value);

                    uArr[u++] = JSON.stringify({"id": row.cells[1].innerHTML, "name": name[0].value, "s_time": time_start, "e_time": time_end,
                        "shift_type": shift_type[0].options[shift_type[0].selectedIndex].text, "half_day_dur": half_day_dur, "dur_delay": dur_delay,
                        "login_dur": login_dur, "logout_dur": logout_dur, "ot_dur": ot_dur});

                    if(actions[k].innerHTML == 'LU'){
                        l++;
                    }
                }
                else if (actions[k].innerHTML == 'D') {
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
                $('#fd_cud_recs').val($('#fd_recs').val());
                $('#fd_cud_sort_by').val($('#fd_sort_by').val());
                $('#fd_cud_sort_type').val($('#fd_sort_type').val());
                $('#fd_cud_query').val($('#fd_query').val());
                $('#fd_cud').val(reqJsonData);
                document.getElementById("cud_action").action = '{{ route("shift_schedule.update_data") }}?page=' + $('#fd_cud_page').val();
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
