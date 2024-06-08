@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-5 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading">Todays Attendence</div>
                <div class="panel-body">
                    @if (Auth::guest())
                        <script>window.location.href = '{{route("login")}}';</script>
                    @endif
                        <p style="font-size:12px;"><b>Note:</b>&nbsp;&nbsp; Please use your office system to Punch In/Out but if you forgot<br>
                                                      then E-mail at stupid6moron9@gmail.com mentioning Employee Id<br>
                                                      and Punch In/Out timing.<br><br></p>
                        <div class="container">
                            <form id="punch_in" method="POST" action="{{route('emp_attendance.attendance_login',null,1)}}" enctype="application/json">
                                {{csrf_field()}}
                                <button type="submit" id='punch_in' style="text-align:left;">Punch In</button>
                                 @if($strLogIn ?? '')
                                    Log In Date&Time: {{$strLogIn ?? ''}}
                                @endif
                            </form>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp
                            <form id="punch_out" method="POST" action="{{route('emp_attendance.attendance_logout',null,1)}}" enctype="application/json">
                                {{csrf_field()}}
                                <button type="submit" id='punch_out' style="text-align:right;">Punch Out</button>
                                @if($strLogOut ?? '')
                                    Log Out Date&Time: {{$strLogOut ?? ''}}
                                @endif
                            </form>
                        </div>
                        <script>
                            function punchIn() {
                                //document.getElementById("punch_in").disabled = true;
                                //document.getElementById("punch_out").disabled = false;
                                window.location.href = '{{route("emp_attendance.attendance_login")}}';
                            }
                            function punchOut() {
                                //document.getElementById("punch_in").disabled = false;
                                //document.getElementById("punch_out").disabled = true;
                                window.location.href = '{{route("pages.create")}}';
                            }
                        </script>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
