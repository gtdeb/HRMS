@extends('layouts.app1')
@section('content')
    <section id="tabs" class="project-tab">
        @if (Auth::guest())
            <script>window.location.href = '{{route("login")}}';</script>
        @endif
        <script></script>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-lg-offset-0">
                    <nav>
                        <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link" id="nav-professional-tab" data-toggle="tab" href="#nav-professional" role="tab" aria-controls="nav-home" aria-selected="true" onclick="tabSetting(this);">Professional</a>
                            <a class="nav-item nav-link" id="nav-kyc_qualification-tab" data-toggle="tab" href="#nav-kyc_qualification" role="tab" aria-controls="nav-kyc_qualification" aria-selected="false" onclick="tabSetting(this);">KYC & Qualification</a>
                            <a class="nav-item nav-link" id="nav-personal-tab" data-toggle="tab" href="#nav-personal" role="tab" aria-controls="nav-personal" aria-selected="false" onclick="tabSetting(this);">Personal</a>
                            <a class="nav-item nav-link" id="nav-address-tab" data-toggle="tab" href="#nav-address" role="tab" aria-controls="nav-address" aria-selected="false" onclick="tabSetting(this);">Address</a>
                            <a class="nav-item nav-link" id="nav-payroll-tab" data-toggle="tab" href="#nav-payroll" role="tab" aria-controls="nav-payroll" aria-selected="false" onclick="tabSetting(this);">Payroll</a>
                            <div style="text-align:right">
                                <input style="text-align:center; width:10%; height:25px;" type="search" name="data_serach" id="data_serach" value="{{$pageSetting['query']}}" />
                                <button style="font-size:12px" onclick="search();"><i class="fa fa-search"></i></button>
                                <button style="font-size:12px" onclick="add();"><i class="fa fa-plus"></i></button>
                                <button style="font-size:12px" onclick="save();"><i class="fa fa-save"></i></button>
                            </div>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        @if($pageSetting['tab'] == 'professional')
                        <div class="tab-pane" id="professional" role="tabpanel" aria-labelledby="nav-professional-tab">
                            <div class="table-responsive-lg" style="overflow-x:auto;overflow-y:auto">
                                <div style="text-align:left">
                                    {!! $profiles['empProfiles']->links() !!}
                                </div>
                                <table cellspacing="0" rules="all" border="1" id="professional-tab" style="border-collapse:collapse;" class="table table-hover table-bordered" >
                                    <thead>
                                        <tr>
                                            <th align='center' colspan="1">
                                                {!! Form::selectRange('records', 5, 100, $pageSetting['recs'], array('onchange' => 'changePageRecs(this)')) !!}
                                            </th>
                                            <th align='center' colspan="10">
                                                <div class="row">
                                                    <div style="text-align:center">
                                                        <b>Professional Information</b>
                                                    </div>
                                                </div>
                                            </th>
                                        </tr>
                                        <tr align='center'>
                                            <th width="10%" align='center'><i class="fa fa-trash"></i></th>
                                            <th width="10%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="profile_id" style="cursor: pointer"> Sr <span id="profile_id_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                            <th width="10%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="access_level" style="cursor: pointer"> Access Level <span id="access_level_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                            <th width="10%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="emp_display_id" style="cursor: pointer"> Display Id <span id="emp_display_id_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                            <th width="10%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="department_id" style="cursor: pointer"> Department <span id="department_id_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                            <th width="10%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="designation" style="cursor: pointer"> Designation <span id="designation_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                            <th width="10%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="join_date" style="cursor: pointer"> Join Date <span id="join_date_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                            <th width="10%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="leave_date" style="cursor: pointer"> Leave Date <span id="leave_date_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                            <!--<th width="10%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="last_login" style="cursor: pointer"> Last Login <span id="last_login_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                            <th width="10%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="last_logout" style="cursor: pointer"> Last Logout <span id="last_logout_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>-->
                                            <th width="10%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="status" style="cursor: pointer"> Status <span id="status_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr align='center'>
                                        @if($profiles['empProfiles'])
                                            <?php $index = 2; ?>
                                            @foreach ($profiles['empProfiles'] as $empProfile)
                                                <?php $index++; ?>
                                                <tr>
                                                    <td><label align='center' class="action" hidden>D</label><input class='checkbox1' type="checkbox">
                                                        <button style="font-size:10px" onclick="edit(this, {{$index}})"><i class="fa fa-pencil-square"></i></button>
                                                        <button style="font-size:10px" onclick="register(this, {{$index}})"><i class="fa fa-user-plus"></i></button>
                                                    </td>
                                                    <td>{{ $empProfile->profile_id }}</td>
                                                    @php
                                                        $access_level_index = null;
                                                        foreach($profiles['accessControlList'] as $key => $value){
                                                            if($value == $empProfile->access_level){
                                                                $access_level_index = $key;
                                                                break;
                                                            }
                                                        }
                                                    @endphp
                                                    <td><label class="lblname" for="{{$access_level_index}}">{{$empProfile->access_level}}</label></td>
                                                    <td><label class="lblname">{{$empProfile->emp_display_id}}</label></td>
                                                    @php
                                                        $department_index = null;
                                                        foreach($profiles['empDepartments'] as $key => $value){
                                                            if($value == $empProfile->dept_name){
                                                                $department_index = $key;
                                                                break;
                                                            }
                                                        }
                                                    @endphp
                                                    <td><label class="lblname" for="{{$department_index}}">{{$empProfile->dept_name}}</label></td>
                                                    @php
                                                        $designation_index = null;
                                                        foreach($profiles['empDesignations'] as $key => $value){
                                                            if($value == $empProfile->designation){
                                                                $designation_index = $key;
                                                                break;
                                                            }
                                                        }
                                                    @endphp
                                                    <td><label class="lblname" for="{{$designation_index}}">{{$empProfile->designation}}</label></td>
                                                    @php
                                                    if($empProfile->join_date){
                                                        $join_date = date_create($empProfile->join_date);
                                                        $formt_date1 = date_format($join_date,"Y-m-d");
                                                        $formt_time1 = date_format($join_date,"H:i:s");
                                                        $join_date_fmt = date_format($join_date, "Y-m-d A g:i");
                                                        $formtmatted1 = $formt_date1 . 'T' . $formt_time1;
                                                    }
                                                    else{
                                                        $join_date_fmt = $empProfile->join_date;
                                                        $formtmatted1 = $empProfile->join_date;
                                                    }
                                                    @endphp
                                                    <td><label class="lblname" for={{$formtmatted1}}>{{$join_date_fmt}}</label></td>
                                                    @php
                                                    if($empProfile->leave_date){
                                                        $leave_date = date_create($empProfile->leave_date);
                                                        $formt_date2 = date_format($leave_date,"Y-m-d");
                                                        $formt_time2 = date_format($leave_date,"H:i:s");
                                                        $leave_date_fmt = date_format($leave_date, "Y-m-d A g:i");
                                                        $formtmatted2 = $formt_date2 . 'T' . $formt_time2;
                                                    }
                                                    else{
                                                        $leave_date_fmt = $empProfile->leave_date;
                                                        $formtmatted2 = $empProfile->leave_date;
                                                    }
                                                    @endphp
                                                    <td><label class="lblname" for={{$formtmatted2}}>{{$leave_date_fmt}}</label></td>
                                                    <td><label class="lblname">{{$empProfile->status}}</label></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @elseif($pageSetting['tab'] == 'kyc_qualification')
                        <div class="tab-pane" id="kyc_qualification" role="tabpanel" aria-labelledby="nav-kyc_qualification-tab">
                            <div class="table-responsive-lg" style="overflow-x:auto;overflow-y:auto">
                                <div style="text-align:left">
                                    {!! $profiles['empProfiles']->links() !!}
                                </div>
                                <table cellspacing="0" rules="all" border="1" id="kyc_qualification-tab" style="border-collapse:collapse;" class="table table-hover table-bordered" >
                                    <thead>
                                    <tr>
                                        <th align='center' colspan="1">
                                            {!! Form::selectRange('records', 5, 100, $pageSetting['recs'], array('onchange' => 'changePageRecs(this)')) !!}
                                        </th>
                                        <th align='center' colspan="10">
                                            <div class="row">
                                                <div style="text-align:center">
                                                    <b>KYC & Qualification</b>
                                                </div>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr align='center'>
                                        <th width="10%" align='center'><i class="fa fa-trash"></i></th>
                                        <th width="9%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="emp_display_id" style="cursor: pointer"> Display Id <span id="emp_display_id_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                        <th width="9%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="qualification" style="cursor: pointer"> Qualification <span id="qualification_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                        <th width="9%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="qualification_other" style="cursor: pointer"> Qualification Other <span id="qualification_other_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                        <th width="9%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="qualification_docs" style="cursor: pointer"> Qualification Docs <span id="qualification_docs_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                        <th width="9%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="panNumber" style="cursor: pointer"> Pan Number <span id="panNumber_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                        <th width="9%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="pan_copy" style="cursor: pointer"> Pan Copy <span id="pan_copy_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                        <th width="9%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="voterId" style="cursor: pointer"> Voter Id <span id="voterId_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                        <th width="9%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="voter_copy" style="cursor: pointer"> Voter Copy <span id="voter_copy_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                        <th width="9%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="aadharNumber" style="cursor: pointer"> Aadhar Number <span id="aadharNumber_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                        <th width="9%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="aadhar_copy" style="cursor: pointer"> Aadhar Copy <span id="aadhar_copy_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr align='center'>
                                    @if($profiles['empProfiles'])
                                        <?php $index = 2; ?>
                                        @foreach ($profiles['empProfiles'] as $empProfile)
                                            <?php $index++; ?>
                                            <tr>
                                                <td><label class="action" hidden>D</label><input class='checkbox1' type="checkbox">
                                                    <button style="font-size:10px" onclick="edit(this, {{$index}})"><i class="fa fa-pencil-square"></i></button>
                                                </td>
                                                <td><label class="lblname" for="{{$empProfile->profile_id}}">{{$empProfile->emp_display_id}}</label></td>
                                                @php
                                                    $qualification_index = null;
                                                    foreach($profiles['empQualifications'] as $key => $value){
                                                        if($value == $empProfile->qualification){
                                                            $qualification_index = $key;
                                                            break;
                                                        }
                                                    }
                                                @endphp
                                                <td><label class="lblname" for="{{$qualification_index}}">{{$empProfile->qualification}}</label></td>
                                                <td><label class="lblname">{{$empProfile->qualification_other}}</label></td>
                                                @if($empProfile->qualification_docs && count(glob($empProfile->qualification_docs.'*.*')) > 0)
                                                    <td><label class="lblname" hidden>{{$empProfile->qualification_docs}}</label><button id='qualification_docs_id' style="font-size:15px" value="{{$empProfile->qualification_docs}}" onclick="preview_doc(1,this);"><i class="fa fa-eye"></i></button></td>
                                                @else
                                                    <td><label class="lblname" hidden></label></td>
                                                @endif
                                                <td><label class="lblname">{{$empProfile->panNumber}}</label></td>
                                                @if($empProfile->pan_copy && count(glob($empProfile->pan_copy.'*.*')) > 0)
                                                    <td><label class="lblname" hidden>{{$empProfile->pan_copy}}</label><button id='pan_copy_id' style="font-size:15px" value="{{$empProfile->pan_copy}}" onclick="preview_doc(2,this);"><i class="fa fa-eye"></i></button></td>
                                                @else
                                                    <td><label class="lblname" hidden></label></td>
                                                @endif
                                                <td><label class="lblname">{{$empProfile->voterId}}</label></td>
                                                @if($empProfile->voter_copy && count(glob($empProfile->voter_copy.'*.*')) > 0)
                                                    <td><label class="lblname" hidden>{{$empProfile->voter_copy}}</label><button id='voter_copy_id' style="font-size:15px" value="{{$empProfile->voter_copy}}" onclick="preview_doc(3,this);"><i class="fa fa-eye"></i></button></td>
                                                @else
                                                    <td><label class="lblname" hidden></label></td>
                                                @endif
                                                <td><label class="lblname">{{$empProfile->aadharNumber}}</label></td>
                                                @if($empProfile->aadhar_copy && count(glob($empProfile->aadhar_copy.'*.*')) > 0)
                                                    <td><label class="lblname" hidden>{{$empProfile->aadhar_copy}}</label><button id='aadhar_copy_id' style="font-size:15px" value="{{$empProfile->aadhar_copy}}" onclick="preview_doc(4,this);"><i class="fa fa-eye"></i></button></td>
                                                @else
                                                    <td><label class="lblname" hidden></label></td>
                                                @endif
                                            </tr>
                                            @endforeach
                                            @endif
                                            </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @elseif($pageSetting['tab'] == 'personal')
                        <div class="tab-pane" id="personal" role="tabpanel" aria-labelledby="nav-personal-tab">
                            <div class="table-responsive-lg" style="overflow-x:auto;overflow-y:auto">
                                <div style="text-align:left">
                                    {!! $profiles['empProfiles']->links() !!}
                                </div>
                                <table cellspacing="0" rules="all" border="1" id="personal-tab" style="border-collapse:collapse;" class="table table-hover table-bordered" >
                                    <thead>
                                    <tr>
                                        <th align='center' colspan="1">
                                            {!! Form::selectRange('records', 5, 100, $pageSetting['recs'], array('onchange' => 'changePageRecs(this)')) !!}
                                        </th>
                                        <th align='center' colspan="10">
                                            <div class="row">
                                                <div style="text-align:center">
                                                    <b>Personal Information</b>
                                                </div>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr align='center'>
                                        <th width="10%" align='center'><i class="fa fa-trash"></i></th>
                                        <th width="9%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="emp_display_id" style="cursor: pointer"> Display Id <span id="emp_display_id_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                        <th width="9%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="name" style="cursor: pointer"> Name <span id="name_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                        <th width="9%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="DOB" style="cursor: pointer"> DOB <span id="DOB_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                        <th width="9%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="gender" style="cursor: pointer"> Gender <span id="gender_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                        <th width="9%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="mobile" style="cursor: pointer"> Mobile <span id="mobile_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                        <th width="9%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="mobile_verified" style="cursor: pointer"> Mobile Verified <span id="mobile_verified_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                        <th width="9%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="email" style="cursor: pointer"> Email <span id="email_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                        <th width="9%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="email_verified" style="cursor: pointer"> Email Verified <span id="email_verified_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                        <th width="9%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="marital_status" style="cursor: pointer"> Marital Status <span id="marital_status_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                        <th width="9%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="profile_image" style="cursor: pointer"> Profile Image <span id="profile_image_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr align='center'>
                                    @if($profiles['empProfiles'])
                                        <?php $index = 2; ?>
                                        @foreach ($profiles['empProfiles'] as $empProfile)
                                            <?php $index++; ?>
                                            <tr>
                                                <td><label class="action" hidden>D</label><input class='checkbox1' type="checkbox">
                                                    <button style="font-size:10px" onclick="edit(this, {{$index}})"><i class="fa fa-pencil-square"></i></button>
                                                </td>
                                                <td><label class="lblname" for="{{$empProfile->profile_id}}">{{$empProfile->emp_display_id}}</label></td>
                                                <td><label class="lblname">{{$empProfile->name}}</label></td>
                                                <td><label class="lblname">{{$empProfile->DOB}}</label></td>
                                                <td><label class="lblname">{{$empProfile->gender}}</label></td>
                                                <td><label class="lblname">{{$empProfile->mobile}}</label></td>
                                                <td><label class="lblname">{{$empProfile->mobile_verified}}</label></td>
                                                <td><label class="lblname">{{$empProfile->email}}</label></td>
                                                <td><label class="lblname">{{$empProfile->email_verified}}</label></td>
                                                <td><label class="lblname">{{$empProfile->marital_status}}</label></td>
                                                @if($empProfile->profile_image && count(glob($empProfile->profile_image.'*.*')) > 0)
                                                    <td><label class="lblname" hidden>{{$empProfile->profile_image}}</label><button id='profile_image_id' style="font-size:15px" value="{{$empProfile->profile_image}}" onclick="preview_doc(5,this);"><i class="fa fa-eye"></i></button></td>
                                                @else
                                                    <td><label class="lblname" hidden></label></td>
                                                @endif
                                            </tr>
                                            @endforeach
                                            @endif
                                            </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @elseif($pageSetting['tab'] == 'address')
                        <div class="tab-pane" id="address" role="tabpanel" aria-labelledby="nav-address-tab">
                            <div class="table-responsive-lg" style="overflow-x:auto;overflow-y:auto">
                                <div style="text-align:left">
                                    {!! $profiles['empProfiles']->links() !!}
                                </div>
                                <table cellspacing="0" rules="all" border="1" id="address-tab" style="border-collapse:collapse;" class="table table-hover table-bordered" >
                                    <thead>
                                    <tr>
                                        <th align='center' colspan="1">
                                            {!! Form::selectRange('records', 5, 100, $pageSetting['recs'], array('onchange' => 'changePageRecs(this)')) !!}
                                        </th>
                                        <th align='center' colspan="8">
                                            <div class="row">
                                                <div style="text-align:center">
                                                    <b>Address</b>
                                                </div>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr align='center'>
                                        <th width="12%" align='center'><i class="fa fa-trash"></i></th>
                                        <th width="11%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="emp_display_id" style="cursor: pointer"> Display Id <span id="emp_display_id_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                        <th width="11%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="address_current" style="cursor: pointer"> Current Address <span id="address_current_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                        <th width="11%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="address_permanent" style="cursor: pointer"> Permanent Address <span id="address_permanent_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                        <th width="11%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="gurdian_name" style="cursor: pointer"> Gurdian Name <span id="gurdian_name_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                        <th width="11%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="gurdian_contact" style="cursor: pointer"> Gurdian Contact <span id="gurdian_contact_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                        <th width="11%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="emergency_name" style="cursor: pointer"> Emergency Name <span id="emergency_name_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                        <th width="11%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="emergency_contact" style="cursor: pointer"> Emergency Contact <span id="emergency_contact_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                        <th width="11%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="emergency_address" style="cursor: pointer"> Emergency Address <span id="emergency_address_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr align='center'>
                                    @if($profiles['empProfiles'])
                                        <?php $index = 2; ?>
                                        @foreach ($profiles['empProfiles'] as $empProfile)
                                            <?php $index++; ?>
                                            <tr>
                                                <td><label class="action" hidden>D</label><input class='checkbox1' type="checkbox">
                                                    <button style="font-size:10px" onclick="edit(this, {{$index}})"><i class="fa fa-pencil-square"></i></button>
                                                </td>
                                                <td><label class="lblname" for="{{$empProfile->profile_id}}">{{$empProfile->emp_display_id}}</label></td>
                                                <td><label class="lblname">{{$empProfile->address_current}}</label></td>
                                                <td><label class="lblname">{{$empProfile->address_permanent}}</label></td>
                                                <td><label class="lblname">{{$empProfile->gurdian_name}}</label></td>
                                                <td><label class="lblname">{{$empProfile->gurdian_contact}}</label></td>
                                                <td><label class="lblname">{{$empProfile->emergency_name}}</label></td>
                                                <td><label class="lblname">{{$empProfile->emergency_contact}}</label></td>
                                                <td><label class="lblname">{{$empProfile->emergency_address}}</label></td>
                                            </tr>
                                            @endforeach
                                            @endif
                                            </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @elseif($pageSetting['tab'] == 'payroll')
                        <div class="tab-pane" id="payroll" role="tabpanel" aria-labelledby="nav-payroll-tab">
                            <div class="table-responsive-lg" style="overflow-x:auto;overflow-y:auto">
                                <div style="text-align:left">
                                    {!! $profiles['empProfiles']->links() !!}
                                </div>
                                <table cellspacing="0" rules="all" border="1" id="payroll-tab" style="border-collapse:collapse;" class="table table-hover table-bordered" >
                                    <thead>
                                    <tr>
                                        <th align='center' colspan="1">
                                            {!! Form::selectRange('records', 5, 100, $pageSetting['recs'], array('onchange' => 'changePageRecs(this)')) !!}
                                        </th>
                                        <th align='center' colspan="5">
                                            <div class="row">
                                                <div style="text-align:center">
                                                    <b>Payroll</b>
                                                </div>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr align='center'>
                                        <th width="10%" align='center'><i class="fa fa-trash"></i></th>
                                        <th width="18%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="emp_display_id" style="cursor: pointer"> Display Id <span id="emp_display_id_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                        <th width="18%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="bank_ifsc" style="cursor: pointer"> Bank Ifsc <span id="bank_ifsc_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                        <th width="18%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="bank_account_no" style="cursor: pointer"> Bank Account No <span id="bank_account_no_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                        <th width="18%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="cancel_cheque" style="cursor: pointer"> Cancel Cheque <span id="cancel_cheque_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                        <th width="18%" align='center' class="sorting" data-sorting_type="{{$pageSetting['sort_type']}}" data-column_name="pf_account_no" style="cursor: pointer"> PF Account No <span id="pf_account_no_sort_icon"><i class="fa fa-sort" aria-hidden="true"></i></span></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr align='center'>
                                    @if($profiles['empProfiles'])
                                        <?php $index = 2; ?>
                                        @foreach ($profiles['empProfiles'] as $empProfile)
                                            <?php $index++; ?>
                                            <tr>
                                                <td><label class="action" hidden>D</label><input class='checkbox1' type="checkbox">
                                                    <button style="font-size:10px" onclick="edit(this, {{$index}})"><i class="fa fa-pencil-square"></i></button>
                                                </td>
                                                <td><label class="lblname" for="{{$empProfile->profile_id}}">{{$empProfile->emp_display_id}}</label></td>
                                                @php
                                                $bankifsc_index = 0;
                                                $flag = 0;

                                                if($empProfile->bank_name){
                                                    foreach($profiles['bankIfscs'] as $bank => $value){
                                                        if($bank == $empProfile->bank_name){
                                                            foreach($value as $id => $ifsc){
                                                                if($ifsc == $empProfile->bank_ifsc){
                                                                    $bankifsc_index++;
                                                                    $flag = 1;
                                                                    break;
                                                                }
                                                                else{
                                                                    $bankifsc_index++;
                                                                }
                                                            }
                                                        }
                                                        else{
                                                           $bankifsc_index += count($value) - 1;
                                                        }

                                                        if($flag == 1){
                                                            break;
                                                        }
                                                    }
                                                }
                                                @endphp
                                                <td><label class="lblname" for="{{$bankifsc_index}}">{{$empProfile->bank_ifsc}}</label></td>
                                                <td><label class="lblname">{{$empProfile->bank_account_no}}</label></td>
                                                @if($empProfile->cancel_cheque && count(glob($empProfile->cancel_cheque.'*.*')) > 0)
                                                    <td><label class="lblname" hidden>{{$empProfile->cancel_cheque}}</label><button id='cancel_cheque_id' style="font-size:15px" value="{{$empProfile->cancel_cheque}}" onclick="preview_doc(6,this);"><i class="fa fa-eye"></i></button></td>
                                                @else
                                                    <td><label class="lblname" hidden></label></td>
                                                @endif
                                                <td><label class="lblname">{{$empProfile->pf_account_no}}</label></td>
                                            </tr>
                                            @endforeach
                                            @endif
                                            </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div hidden>
                        <form id="searchfilter" method="POST" action="{{route('emp_profile.fetch_data',null,1)}}" enctype="application/json">
                            {{csrf_field()}}
                            <input type="text" name="fd_tab" id="fd_tab" value="{{$pageSetting['tab']}}">
                            <input type="text" name="fd_page" id="fd_page" value="{{$pageSetting['page']}}">
                            <input type="text" name="fd_recs" id="fd_recs" value="{{$pageSetting['recs']}}">
                            <input type="text" name="fd_sort_by" id="fd_sort_by" value="{{$pageSetting['sort_by']}}">
                            <input type="text" name="fd_sort_type" id="fd_sort_type" value="{{$pageSetting['sort_type']}}">
                            <input type="text" name="fd_query" id="fd_query" value="{{$pageSetting['query']}}">
                        </form>
                        <form id="cud_action" method="POST" action="{{route('emp_profile.update_data',null,1)}}" enctype="application/json">
                            {{csrf_field()}}
                            <input type="text" name="fd_tab" id="fd_tab" value="{{$pageSetting['tab']}}">
                            <input type="text" name="fd_cud_page" id="fd_cud_page" value="{{$pageSetting['page']}}">
                            <input type="text" name="fd_cud_recs" id="fd_cud_recs" value="{{$pageSetting['recs']}}">
                            <input type="text" name="fd_cud_sort_by" id="fd_cud_sort_by" value="{{$pageSetting['sort_by']}}">
                            <input type="text" name="fd_cud_sort_type" id="fd_cud_sort_type" value="{{$pageSetting['sort_type']}}">
                            <input type="text" name="fd_cud_query" id="fd_cud_query" value="{{$pageSetting['query']}}">
                            <textarea name="fd_cud" id="fd_cud" rows="6" cols="50"></textarea>
                        </form>
                        <form id="api_action" method="POST" action="{{route('api_cardFlash')}}" enctype="application/json">
                            {{csrf_field()}}
                            <input type="text" name="api_emp_id" id="api_emp_id" value=null>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script type="text/javascript">
        function pageSetting(){
            var columns = null;
            var column_name = $('#fd_sort_by').val();
            var order_type = $('#fd_sort_type').val();

            document.getElementById($('#fd_tab').val()).setAttribute('class', 'tab-pane active');

            if(order_type == 'asc'){
                $('#'+column_name+'_sort_icon').html('<i class="fa fa-sort-asc"></i>');
            }
            else if(order_type == 'desc')
            {
                $('#'+column_name+'_sort_icon').html('<i class="fa fa-sort-desc"></i>');
            }

            if($('#fd_tab').val() == 'professional'){
                columns = document.getElementById("professional-tab").rows[1].cells;
            }
            else if($('#fd_tab').val() == 'kyc_qualification'){
                columns = document.getElementById("kyc_qualification-tab").rows[1].cells;
            }
            else if($('#fd_tab').val() == 'personal'){
                columns = document.getElementById("personal-tab").rows[1].cells;
            }
            else if($('#fd_tab').val() == 'address'){
                columns = document.getElementById("address-tab").rows[1].cells;
            }
            else if($('#fd_tab').val() == 'payroll'){
                columns = document.getElementById("payroll-tab").rows[1].cells;
            }

            if(column) {
                for (var i = 1; i < columns.length - 1; i++) {
                    if ($(columns[i]).data('column_name') != column_name) {
                        $('#' + $(columns[i]).data('column_name') + '_sort_icon').html('<i class="fa fa-sort"></i>');
                        $(columns[i]).data('sorting_type', 'unsorted');
                    }
                }
            }
            $("#waitingScreen").modal("hide");
        }

        function tabSetting(e){
            var tab = e.getAttribute('id').split('-');

            $('#fd_tab').val(tab[1]);
            $('#fd_sort_by').val('emp_display_id');
            $('#fd_sort_type').val('asc');
            fetch_data();
        }

        function fetch_data()
        {
            var str = "tab=" + $('#fd_tab').val() + "page=" + $('#fd_page').val() + "recs=" + $('#fd_recs').val() + " sort_by=" + $('#fd_sort_by').val() + " sort_type=" + $('#fd_sort_type').val() + " query=" + $('#fd_query').val();
            alert(str);
            document.getElementById("searchfilter").action = '{{ route("emp_profile.fetch_data") }}?page=' + $('#fd_page').val();
            $("#waitingScreen").modal("show");
            $("#searchfilter").submit();
        }

        function changePageRecs(e){
            $('#fd_page').val('1');
            $('#fd_recs').val(e.value);
            fetch_data();
        }

        function search(){
            $('#fd_query').val($('#data_serach').val());
            $('#fd_page').val('1');
            fetch_data();
        }

        $(document).on('click', '.sorting', function(){
            var column_name = $(this).data('column_name');
            var order_type = $(this).data('sorting_type');
            var reverse_order = null;

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
            $('#fd_sort_by').val(column_name);
            $('#fd_sort_type').val(reverse_order);
            fetch_data();
        });

        $(document).on('click', '.pagination a', function(event){
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            $('#fd_page').val(page);
            //$('li').removeClass('active');
            //   $(this).parent().addClass('active');
            fetch_data();
        });

        function checkUploadFileExt(e){
            var SupportedExt = ['jpg','jpeg','png','pdf'];
            //var fileElement = document.getElementById("qualification_docs");
            var fileExtension = "";
            var failcount = 0, successcount = 0;
            var selectedcount = 0;
            var filesStr = '';
            const dt = new DataTransfer();
            selectedcount = e.files.length;

            for (var i = 0; i < selectedcount; ++i) {
                fileExtension = "";
                if(e.files[i].name.lastIndexOf(".") > 0){
                    fileExtension = e.files[i].name.substring(e.files[i].name.lastIndexOf(".") + 1, e.files[i].name.length);
                }
                if (!($.inArray(fileExtension.toLowerCase(), SupportedExt) == -1)) {
                    dt.items.add(e.files[i]);
                    successcount++;
                }
                else
                    failcount++;
                if(successcount == 20) //max limit reached!
                    break;
            }

            if(failcount > 0) {
                if (successcount == 20)
                    selectedcount = successcount;
                else
                    selectedcount -= failcount;
                e.files = dt.files;
                alert("Allowed: "+SupportedExt.join(', ')+"\n"+selectedcount+" files selected");
            }
            else if(successcount == 20)
                e.files = dt.files;
        }

        function copyCurToPerAddr(evt){
            addr_per = document.getElementById('address_permanent');
            if(evt.checked) {
                addr_cur = document.getElementById('address_current');
                addr_per.htmlFor = addr_per.value;
                addr_per.innerHTML = addr_cur.value;
            }
            else{
                addr_per.innerHTML = addr_per.htmlFor;
            }
        }

        function copyCurToEmrAddr(evt){
            addr_per = document.getElementById('emergency_address');
            if(evt.checked) {
                addr_cur = document.getElementById('address_current');
                addr_per.htmlFor = addr_per.value;
                addr_per.innerHTML = addr_cur.value;
            }
            else{
                addr_per.innerHTML = addr_per.htmlFor;
            }
        }

        function copyGurToEmerName(evt){
            emr_name = document.getElementById('emergency_name');
            if(evt.checked) {
                gur_name = document.getElementById('gurdian_name');
                emr_name.htmlFor = emr_name.value;
                emr_name.value = gur_name.value;
            }
            else{
                emr_name.value = emr_name.htmlFor;
            }
        }

        function copyGurToEmerContact(evt){
            emr_name = document.getElementById('emergency_contact');
            if(evt.checked) {
                gur_name = document.getElementById('gurdian_contact');
                emr_name.htmlFor = emr_name.value;
                emr_name.value = gur_name.value;
            }
            else{
                emr_name.value = emr_name.htmlFor;
            }
        }

        function add(){
            var grid = null;

            if($('#fd_tab').val() == 'professional'){
                grid = document.getElementById("professional-tab").insertRow(-1);
                grid.insertCell(0).innerHTML = "<label class='action' hidden>C</label><button style='font-size:8px' onclick='remove();'><i class='fa fa-remove'></i></button>";
                grid.insertCell(1).innerHTML = "";

                grid.insertCell(2).innerHTML = "{{Html::decode(Form::select('access_control', $profiles['accessControlList'], null, array('class' =>'access_control')))}}";
                grid.cells[2].innerHTML = grid.cells[2].innerText;

                grid.insertCell(3).innerHTML = "<input type='text' class='name' id='emp_display_id' style='border:none' size='8'>";

                grid.insertCell(4).innerHTML = "{{Html::decode(Form::select('department', $profiles['empDepartments'], null, array('class' =>'department')))}}";
                grid.cells[4].innerHTML = grid.cells[4].innerText;

                grid.insertCell(5).innerHTML = "{{Html::decode(Form::select('designation', $profiles['empDesignations'], null, array('class' =>'designation')))}}";
                grid.cells[5].innerHTML = grid.cells[5].innerText;

                grid.insertCell(6).innerHTML = "<input type='datetime-local' id='join_date' class='join_date'>";

                grid.insertCell(7).innerHTML = "<input type='datetime-local' id='leave_date' class='leave_date'>";

                //grid.insertCell(8).innerHTML = "<input type='datetime-local' id='last_login' class='last_login'>";

                //grid.insertCell(9).innerHTML = "<input type='datetime-local' id='last_logout' class='last_logout'>";

                grid.insertCell(8).innerHTML = "<select id='status' class='name' style='text-align:center;height:20px;'>" +
                    "<option value='Active'>Active</option><option value='In-Active'>In-Active</option></select>";
            }
            else if($('#fd_tab').val() == 'kyc_qualification'){
                grid = document.getElementById("kyc_qualification-tab").insertRow(-1);
                grid.insertCell(0).innerHTML = "<label class='action' hidden>C</label><button style='font-size:8px' onclick='remove();'><i class='fa fa-remove'></i></button>";

                grid.insertCell(1).innerHTML = "<input type='text' class='name' id='emp_display_id' style='border:none' size='8'>";

                grid.insertCell(2).innerHTML = "{{Html::decode(Form::select('qualification', $profiles['empQualifications'], null, array('class' =>'qualification')))}}";
                grid.cells[2].innerHTML = grid.cells[2].innerText;

                grid.insertCell(3).innerHTML = "<input type='text' class='name' id='qualification_other' style='border:none' size='8'>";

                grid.insertCell(4).innerHTML = "<input type='file' class='name' id='qualification_docs' name='qualification_docs[]' multiple " +
                    "onchange='checkUploadFileExt(this)'>";

                grid.insertCell(5).innerHTML = "<input type='text' class='name' id='panNumber' style='border:none' size='8'>";

                grid.insertCell(6).innerHTML = "<input type='file' class='name' id='pan_copy' accept='.jpg,.jpeg,.png,.bmp,.pdf,.doc,.docx,.xls,.xlsx' multiple>";

                grid.insertCell(7).innerHTML = "<input type='text' class='name' id='voterId' style='border:none' size='8'>";

                grid.insertCell(8).innerHTML = "<input type='file' class='name' id='voter_copy' accept='.jpg,.jpeg,.png,.bmp,.pdf,.doc,.docx,.xls,.xlsx' multiple>";

                grid.insertCell(9).innerHTML = "<input type='text' class='name' id='aadharNumber' style='border:none' size='8'>";

                grid.insertCell(10).innerHTML = "<input type='file' class='name' id='aadhar_copy' accept='.jpg,.jpeg,.png,.bmp,.pdf,.doc,.docx,.xls,.xlsx' multiple>";
            }
            else if($('#fd_tab').val() == 'personal'){
                grid = document.getElementById("personal-tab").insertRow(-1);
                grid.insertCell(0).innerHTML = "<label class='action' hidden>C</label><button style='font-size:8px' onclick='remove();'><i class='fa fa-remove'></i></button>";

                grid.insertCell(1).innerHTML = "<input type='text' class='name' id='emp_display_id' style='border:none' size='8'>";

                grid.insertCell(2).innerHTML = "<input type='text' class='name' id='emp_name' style='border:none' size='8'>";

                grid.insertCell(3).innerHTML = "<input type='date' class='name' id='dob'>";

                grid.insertCell(4).innerHTML = "<select id='gender' class='name' style='text-align:center;height:20px;'>" +
                    "<option value='select'>Select</option><option value='Male'>Male</option>" +
                    "<option value='Female'>Female</option><option value='Female'>Transgender</option></select>";

                grid.insertCell(5).innerHTML = "<input type='tel' class='name' id='mobile' pattern='+[1-9]{2}-[0-9]{10}' required>";

                grid.insertCell(6).innerHTML = "<select id='mob_verified' class='name' style='text-align:center;height:20px;'>" +
                    "<option value='select'>Select</option><option value='no'>No</option><option value='yes'>Yes</option></select>";

                grid.insertCell(7).innerHTML = "<input type='email' class='name' id='email' pattern='.+@globex.com' required>";

                grid.insertCell(8).innerHTML = "<select id='email_verified' class='name' style='text-align:center;height:20px;'>" +
                    "<option value='select'>Select</option><option value='no'>No</option><option value='yes'>Yes</option></select>";

                grid.insertCell(9).innerHTML = "<select id='marital_status' class='name' style='text-align:center;height:20px;'>" +
                    "<option value='select'>Select</option><option value='Single'>Single</option><option value='Married'>Married</option>" +
                    "<option value='Divorced'>Divorced</option></select>";

                grid.insertCell(10).innerHTML = "<input type='file' class='name' id='profile_image' accept='.jpg,.jpeg,.png,.bmp,.pdf,.doc,.docx,.xls,.xlsx' multiple>";
            }
            else if($('#fd_tab').val() == 'address'){
                grid = document.getElementById("address-tab").insertRow(-1);
                grid.insertCell(0).innerHTML = "<label class='action' hidden>C</label><button style='font-size:8px' onclick='remove();'><i class='fa fa-remove'></i></button>";

                grid.insertCell(1).innerHTML = "<input type='text' class='name' id='emp_display_id' style='border:none' size='8'>";

                grid.insertCell(2).innerHTML = "<textarea class='name' id='address_current' rows='2' cols='10'>";

                grid.insertCell(3).innerHTML = "<textarea class='name' id='address_permanent' rows='2' cols='15'></textarea>" +
                    "<br><i>Current Address:</i> <input type='checkbox' id='perAddr' onclick='copyCurToPerAddr(this)'>";

                grid.insertCell(4).innerHTML = "<input type='text' class='name' id='gurdian_name' style='border:none' size='8'>";

                grid.insertCell(5).innerHTML = "<input type='tel' class='name' id='gurdian_contact' pattern='+[1-9]{2}-[0-9]{10}' required>";

                grid.insertCell(6).innerHTML = "<input type='text' class='name' id='emergency_name' style='border:none' size='8'>" +
                    "<br><i>Gurdian:</i> <input type='checkbox' id='gurName' onclick='copyGurToEmerName(this)'>";

                grid.insertCell(7).innerHTML = "<input type='tel' class='name' id='emergency_contact' pattern='+[1-9]{2}-[0-9]{10}' required>" +
                            "<br><i>Gurdian:</i> <input type='checkbox' id='gurContact' onclick='copyGurToEmerContact(this)'>";

                grid.insertCell(8).innerHTML = "<textarea class='name' id='emergency_address' rows='2' cols='15'></textarea>" +
                            "<br><i>Current Address:</i> <input type='checkbox' id='emrAddr' onclick='copyCurToEmrAddr(this)'>";
            }
            else if($('#fd_tab').val() == 'payroll'){
                grid = document.getElementById("payroll-tab").insertRow(-1);
                grid.insertCell(0).innerHTML = "<label class='action' hidden>C</label><button style='font-size:8px' onclick='remove();'><i class='fa fa-remove'></i></button>";

                grid.insertCell(1).innerHTML = "<input type='text' class='name' id='emp_display_id' style='border:none' size='8'>";

                grid.insertCell(2).innerHTML = "{{Html::decode(Form::select('bankIfscs', $profiles['bankIfscs'], null, array('class' =>'bankIfscs')))}}";
                grid.cells[2].innerHTML = grid.cells[2].innerText;

                grid.insertCell(3).innerHTML = "<input type='number' class='name' id='bank_account_no' min='0' value='0'>";

                grid.insertCell(4).innerHTML = "<input type='file' class='name' id='cancel_cheque' accept='.jpg,.jpeg,.png,.bmp,.pdf,.doc,.docx,.xls,.xlsx' multiple>";

                grid.insertCell(5).innerHTML = "<input type='number' class='name' id='pf_account_no' min='0' value='0'>";
            }
        }

        function remove(){
            if($('#fd_tab').val() == 'professional'){
                document.getElementById("professional-tab").deleteRow(-1);
            }
            else if($('#fd_tab').val() == 'kyc_qualification'){
                document.getElementById("kyc_qualification-tab").deleteRow(-1);
            }
            else if($('#fd_tab').val() == 'personal'){
                document.getElementById("personal-tab").deleteRow(-1);
            }
            else if($('#fd_tab').val() == 'address'){
                document.getElementById("address-tab").deleteRow(-1);
            }
            else if($('#fd_tab').val() == 'payroll'){
                document.getElementById("payroll-tab").deleteRow(-1);
            }
        }

        function docs_preview_modal(data){
            var fileNames, fileName, wd, ht;
            var imgHolder = document.getElementById('documentPreview_dialog');
            imgHolder.innerHTML = '';

            if(data[0] == 1){
                wd = "75%";
                ht = "400";
            }
            else if(data[0] == 2){
                wd = "100%";
                ht = "230";
            }
            else if(data[0] == 3){
                wd = "100%";
                ht = "400";
            }
            else if(data[0] == 4){
                wd = "100%";
                ht = "230";
            }
            else if(data[0] == 5){
                wd = "50%";
                ht = "230";
            }
            else if(data[0] == 6){
                wd = "100%";
                ht = "230";
            }

            for(var i = 1; i < data.length; i++){
                fileNames = data[i+1].split('/');
                fileName = fileNames[fileNames.length - 1];
                if(data[i] == 'jpg' || data[i] == 'jpeg' || data[i] == 'png') {
                    imgHolder.innerHTML += "<img src='" + data[++i] + "' width='" + wd + "' height='" + ht + "' style='display:inline-block;'>" +
                        "<button style='font-size:15px' value='" + fileName + "' onclick='remove_preview_doc(" + data[0] + ", this);'>" +
                        "Remove</button><br><br>";
                }
                else if(data[i] == 'pdf'){
                    imgHolder.innerHTML += "<object class='PDFdoc' width='" + wd + "' height='" + ht + "' type='application/pdf' data='" + data[++i] + "'>" +
                        "</object><button style='font-size:15px' value='" + fileName + "' onclick='remove_preview_doc(" + data[0] + ", this);'>" +
                        "Remove</button><br><br>";
                }
                else{
                    imgHolder.innerHTML += "<iframe class='doc' src='https://docs.google.com/gview?url=http://writing.engr.psu.edu/workbooks/formal_report_template.doc&embedded=true'></iframe>";
                }
            }
            $("#documentPreview").modal("show");
        }

        function remove_preview_doc(id, evt){
            var formData = new FormData();

            alert(id);
            formData.append('id', id);
            formData.append('filePath', evt.value);
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
                },
                url: '\\emp_profile\\docs_clean',
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                async: false,
                success: function (data) {
                    //alert('SUCCESS2: ' + data);
                    if(data[1] == 1){
                        //$("#documentPreview").modal("hide");
                        if(data[0] == 1) {
                            $('#qualification_docs_id').click();
                        }
                        else if(data[0] == 2){
                            $('#pan_copy_id').click();
                        }
                        else if(data[0] == 3){
                            $('#voter_copy_id').click();
                        }
                        else if(data[0] == 4){
                            $('#aadhar_copy_id').click();
                        }
                        else if(data[0] == 5){
                            $('#profile_image_id').click();
                        }
                        else if(data[0] == 6){
                            $('#cancel_cheque_id').click();
                        }
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert('ERREOR: ' + JSON.stringify(xhr));
                    //alert(JSON.stringify(ajaxOptions));
                    //alert(JSON.stringify(thrownError));
                    //console.log(errors);
                }
            });
        }

        function preview_doc(id, evt){
            var formData = new FormData();

            formData.append('id', id);
            formData.append('path', evt.value);
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
                },
                url: '\\emp_profile\\docs_preview',
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                async: false,
                success: function (data) {
                    //alert('SUCCESS1: ' + data);
                    docs_preview_modal(data);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert('ERREOR: ' + JSON.stringify(xhr));
                    //alert(JSON.stringify(ajaxOptions));
                    //alert(JSON.stringify(thrownError));
                    //console.log(errors);
                }
            });
        }

        function edit(element, index){
            var cells = null;
            var chkbx = null;
            var action = null;
            var lblelement = null;
            var lblName = null;
            var lblIndex = null;
            var lblDateTime = null;

            if(element.innerHTML.search("pencil") != -1) {
                if($('#fd_tab').val() == 'professional'){
                    cells = document.getElementById("professional-tab").rows[index].cells;
                    chkbx = cells[0].getElementsByClassName("checkbox1");
                    action = cells[0].getElementsByClassName("action");

                    chkbx[0].style.display = 'none';
                    action[0].innerHTML = 'DU';
                    element.innerHTML = "<i class=\"fa fa-undo\"></i>";

                    lblelement = cells[2].getElementsByClassName("lblname");
                    lblIndex = (lblelement[0].htmlFor ? lblelement[0].htmlFor : 0);
                    lblName = lblelement[0].innerText;
                    cells[2].innerHTML = "{{Html::decode(Form::select('access_control', $profiles['accessControlList'], null, array('class' =>'access_control')))}}";
                    cells[2].innerHTML = "<label class='lblname' for='" + lblIndex + "' hidden>" + lblName + "</label>" + cells[2].innerText;
                    var access_control = cells[2].getElementsByClassName("access_control");
                    access_control[0].options[lblIndex].selected = true;

                    lblelement = cells[3].getElementsByClassName("lblname");
                    lblName = lblelement[0].innerText;
                    cells[3].innerHTML = "<label class='lblname' hidden>" + lblName + "</label>" +
                        "<input type='text' class='name' id='emp_display_id' style='border:none' value='" + lblName + "' size='8'>";

                    lblelement = cells[4].getElementsByClassName("lblname");
                    lblIndex = (lblelement[0].htmlFor ? lblelement[0].htmlFor : 0);
                    lblName = lblelement[0].innerText;
                    cells[4].innerHTML = "{{Html::decode(Form::select('department', $profiles['empDepartments'], null, array('class' =>'department')))}}";
                    cells[4].innerHTML = "<label class='lblname' for='" + lblIndex + "' hidden>" + lblName + "</label>" + cells[4].innerText;
                    var department = cells[4].getElementsByClassName("department");
                    department[0].options[lblIndex].selected = true;

                    lblelement = cells[5].getElementsByClassName("lblname");
                    lblIndex = (lblelement[0].htmlFor ? lblelement[0].htmlFor : 0);
                    lblName = lblelement[0].innerText;
                    cells[5].innerHTML = "{{Html::decode(Form::select('designation', $profiles['empDesignations'], null, array('class' =>'designation')))}}";
                    cells[5].innerHTML = "<label class='lblname' for='" + lblIndex + "' hidden>" + lblName + "</label>" + cells[5].innerText;
                    var designation = cells[5].getElementsByClassName("designation");
                    designation[0].options[lblIndex].selected = true;

                    lblelement = cells[6].getElementsByClassName("lblname");
                    lblDateTime = lblelement[0].htmlFor;
                    lblName = lblelement[0].innerText;
                    cells[6].innerHTML = "<label class='lblname' for='" + lblDateTime + "' hidden>" + lblName + "</label>" +
                        "<input type='datetime-local' id='join_date' class='join_date' value='" + lblDateTime + "' >";

                    lblelement = cells[7].getElementsByClassName("lblname");
                    lblDateTime = lblelement[0].htmlFor;
                    lblName = lblelement[0].innerText;
                    cells[7].innerHTML = "<label class='lblname' for='" + lblDateTime + "' hidden>" + lblName + "</label>" +
                        "<input type='datetime-local' id='leave_date' class='leave_date' value='" + lblDateTime + "'>";

                    /*lblelement = cells[8].getElementsByClassName("lblname");
                    lblDateTime = lblelement[0].htmlFor;
                    lblName = lblelement[0].innerText;
                    cells[8].innerHTML = "<label class='lblname' for='" + lblDateTime + "' hidden>" + lblName + "</label>" +
                        "<input type='datetime-local' id='last_login' class='last_login' value='" + lblDateTime + "'>";

                    lblelement = cells[9].getElementsByClassName("lblname");
                    lblDateTime = lblelement[0].htmlFor;
                    lblName = lblelement[0].innerText;
                    cells[9].innerHTML = "<label class='lblname' for='" + lblDateTime + "' hidden>" + lblName + "</label>" +
                        "<input type='datetime-local' id='last_logout' class='last_logout' value='" + lblDateTime + "'>";*/

                    lblelement = cells[8].getElementsByClassName("lblname");
                    lblName = lblelement[0].innerText;
                    cells[8].innerHTML = "<label class='lblname' hidden>" + lblName + "</label><select id='status' " +
                        "class='name' style='text-align:center;height:20px;'><option value='Active'>Active</option>" +
                        "<option value='In-Active'>In-Active</option></select>";
                    cells[8].querySelector('option[value=' + lblName + ']').selected = true;
                }
                else if($('#fd_tab').val() == 'kyc_qualification'){
                    cells = document.getElementById("kyc_qualification-tab").rows[index].cells;
                    chkbx = cells[0].getElementsByClassName("checkbox1");
                    action = cells[0].getElementsByClassName("action");

                    chkbx[0].style.display = 'none';
                    action[0].innerHTML = 'DU';
                    element.innerHTML = "<i class=\"fa fa-undo\"></i>";

                    lblelement = cells[1].getElementsByClassName("lblname");
                    lblName = lblelement[0].innerText;
                    lblIndex = lblelement[0].htmlFor;
                    cells[1].innerHTML = "<label class='lblname' for='" + lblIndex + "' hidden>" + lblName + "</label>" +
                        "<input type='text' class='name' id='emp_display_id' style='border:none' value='" + lblName + "' size='8'>";

                    lblelement = cells[2].getElementsByClassName("lblname");
                    lblIndex = (lblelement[0].htmlFor ? lblelement[0].htmlFor : 0);
                    lblName = lblelement[0].innerText;
                    cells[2].innerHTML = "{{Html::decode(Form::select('qualification', $profiles['empQualifications'], null, array('class' =>'qualification')))}}";
                    cells[2].innerHTML = "<label class='lblname' for='" + lblIndex + "' hidden>" + lblName + "</label>" + cells[2].innerText;
                    var qualification = cells[2].getElementsByClassName("qualification");
                    qualification[0].options[lblIndex].selected = true;

                    lblelement = cells[3].getElementsByClassName("lblname");
                    lblName = lblelement[0].innerText;
                    cells[3].innerHTML = "<label class='lblname' hidden>" + lblName + "</label>" +
                        "<input type='text' class='name' id='qualification_other' style='border:none' value='" + lblName + "' size='8'>";

                    lblelement = cells[4].getElementsByClassName("lblname");
                    lblName = lblelement[0].innerText;
                    cells[4].innerHTML = "<label class='lblname' hidden>" + lblName + "</label>" +
                        "<input type='file' class='name' id='qualification_docs' name='qualification_docs[]' multiple onchange='checkUploadFileExt(this)'>";

                    lblelement = cells[5].getElementsByClassName("lblname");
                    lblName = lblelement[0].innerText;
                    cells[5].innerHTML = "<label class='lblname' hidden>" + lblName + "</label>" +
                        "<input type='text' class='name' id='panNumber' style='border:none' value='" + lblName + "' size='8'>";

                    lblelement = cells[6].getElementsByClassName("lblname");
                    lblName = lblelement[0].innerText;
                    cells[6].innerHTML = "<label class='lblname' hidden>" + lblName + "</label>" +
                        "<input type='file' class='name' id='pan_copy' accept='.jpg,.jpeg,.png,.bmp,.pdf,.doc,.docx,.xls,.xlsx' multiple>";

                    lblelement = cells[7].getElementsByClassName("lblname");
                    lblName = lblelement[0].innerText;
                    cells[7].innerHTML = "<label class='lblname' hidden>" + lblName + "</label>" +
                        "<input type='text' class='name' id='voterId' style='border:none' value='" + lblName + "' size='8'>";

                    lblelement = cells[8].getElementsByClassName("lblname");
                    lblName = lblelement[0].innerText;
                    cells[8].innerHTML = "<label class='lblname' hidden>" + lblName + "</label>" +
                        "<input type='file' class='name' id='voter_copy' accept='.jpg,.jpeg,.png,.bmp,.pdf' multiple>";

                    lblelement = cells[9].getElementsByClassName("lblname");
                    lblName = lblelement[0].innerText;
                    cells[9].innerHTML = "<label class='lblname' hidden>" + lblName + "</label>" +
                        "<input type='text' class='name' id='aadharNumber' style='border:none' value='" + lblName + "' size='8'>";

                    lblelement = cells[10].getElementsByClassName("lblname");
                    lblName = lblelement[0].innerText;
                    cells[10].innerHTML = "<label class='lblname' hidden>" + lblName + "</label>" +
                        "<input type='file' class='name' id='aadhar_copy' accept='.jpg,.jpeg,.png,.bmp,.pdf' multiple>";
                }
                else if($('#fd_tab').val() == 'personal'){
                    cells = document.getElementById("personal-tab").rows[index].cells;
                    chkbx = cells[0].getElementsByClassName("checkbox1");
                    action = cells[0].getElementsByClassName("action");

                    chkbx[0].style.display = 'none';
                    action[0].innerHTML = 'DU';
                    element.innerHTML = "<i class=\"fa fa-undo\"></i>";

                    lblelement = cells[1].getElementsByClassName("lblname");
                    lblName = lblelement[0].innerText;
                    lblIndex = lblelement[0].htmlFor;
                    cells[1].innerHTML = "<label class='lblname' for='" + lblIndex + "' hidden>" + lblName + "</label>" +
                        "<input type='text' class='name' id='emp_display_id' style='border:none' value='" + lblName + "' size='8'>";

                    lblelement = cells[2].getElementsByClassName("lblname");
                    lblName = lblelement[0].innerText;
                    cells[2].innerHTML = "<label class='lblname' hidden>" + lblName + "</label>" +
                        "<input type='text' class='name' id='emp_name' style='border:none' value='" + lblName + "' size='8'>";

                    lblelement = cells[3].getElementsByClassName("lblname");
                    lblName = lblelement[0].innerText;
                    cells[3].innerHTML = "<label class='lblname' hidden>" + lblName + "</label>" +
                        "<input type='date' class='name' id='dob' value='" + lblName + "'>";

                    lblelement = cells[4].getElementsByClassName("lblname");
                    lblName = lblelement[0].innerText;
                    lblIndex = (lblName ? lblName : 'select');
                    cells[4].innerHTML = "<label class='lblname' hidden>" + lblName + "</label><select id='gender'" +
                        "class='name' style='text-align:center;height:20px;'><option value='select'>Select</option>" +
                        "<option value='Male'>Male</option><option value='Female'>Female</option>" +
                        "<option value='Transgender'>Transgender</option></select>";
                    cells[4].querySelector('option[value=' + lblIndex + ']').selected = true;

                    lblelement = cells[5].getElementsByClassName("lblname");
                    lblName = lblelement[0].innerText;
                    cells[5].innerHTML = "<label class='lblname' hidden>" + lblName + "</label>" +
                        "<input type='number' class='name' id='mobile' min='0' value='" + lblName + "'required>";

                    lblelement = cells[6].getElementsByClassName("lblname");
                    lblName = lblelement[0].innerText;
                    lblIndex = (lblName ? lblName : 'select');
                    cells[6].innerHTML = "<label class='lblname' hidden>" + lblName + "</label><select id='mob_verified'" +
                        "class='name' style='text-align:center;height:20px;'><option value='select'>Select</option>" +
                        "<option value='no'>No</option><option value='yes'>Yes</option></select>";
                    cells[6].querySelector('option[value=' + lblIndex + ']').selected = true;

                    lblelement = cells[7].getElementsByClassName("lblname");
                    lblName = lblelement[0].innerText;
                    cells[7].innerHTML = "<label class='lblname' hidden>" + lblName + "</label>" +
                        "<input type='email' class='name' id='email' pattern='.+@globex.com' value='" + lblName + "'>";

                    lblelement = cells[8].getElementsByClassName("lblname");
                    lblName = lblelement[0].innerText;
                    lblIndex = (lblName ? lblName : 'select');
                    cells[8].innerHTML = "<label class='lblname' hidden>" + lblName + "</label><select id='email_verified'" +
                        "class='name' style='text-align:center;height:20px;'><option value='select'>Select</option>" +
                        "<option value='no'>No</option><option value='yes'>Yes</option></select>";
                    cells[8].querySelector('option[value=' + lblIndex + ']').selected = true;

                    lblelement = cells[9].getElementsByClassName("lblname");
                    lblName = lblelement[0].innerText;
                    lblIndex = (lblName ? lblName : 'select');
                    cells[9].innerHTML = "<label class='lblname' hidden>" + lblName + "</label><select id='marital_status'" +
                        "class='name' style='text-align:center;height:20px;'><option value='select'>Select</option>" +
                        "<option value='Single'>Single</option><option value='Married'>Married</option>" +
                        "<option value='Divorced'>Divorced</option></select>";
                    cells[9].querySelector('option[value=' + lblIndex + ']').selected = true;

                    lblelement = cells[10].getElementsByClassName("lblname");
                    lblName = lblelement[0].innerText;
                    cells[10].innerHTML = "<label class='lblname' hidden>" + lblName + "</label>" +
                        "<input type='file' class='name' id='profile_image' accept='.jpg,.jpeg,.png,.bmp,.pdf' multiple>";
                }
                else if($('#fd_tab').val() == 'address'){
                    cells = document.getElementById("address-tab").rows[index].cells;
                    chkbx = cells[0].getElementsByClassName("checkbox1");
                    action = cells[0].getElementsByClassName("action");

                    chkbx[0].style.display = 'none';
                    action[0].innerHTML = 'DU';
                    element.innerHTML = "<i class=\"fa fa-undo\"></i>";

                    lblelement = cells[1].getElementsByClassName("lblname");
                    lblName = lblelement[0].innerText;
                    lblIndex = lblelement[0].htmlFor;
                    cells[1].innerHTML = "<label class='lblname' for='" + lblIndex + "' hidden>" + lblName + "</label>" +
                        "<input type='text' class='name' id='emp_display_id' style='border:none' value='" + lblName + "' size='8'>";

                    lblelement = cells[2].getElementsByClassName("lblname");
                    lblName = lblelement[0].innerText;
                    cells[2].innerHTML = "<label class='lblname' hidden>" + lblName + "</label>" +
                        "<textarea class='name' id='address_current' rows='2' cols='15'>" + lblName + "</textarea>";

                    lblelement = cells[3].getElementsByClassName("lblname");
                    lblName = lblelement[0].innerText;
                    cells[3].innerHTML = "<label class='lblname' hidden>" + lblName + "</label>" +
                        "<textarea class='name' id='address_permanent' rows='2' cols='15'>" + lblName + "</textarea>" +
                        "<br><i>Current Address:</i> <input type='checkbox' id='perAddr' onclick='copyCurToPerAddr(this)'>";

                    lblelement = cells[4].getElementsByClassName("lblname");
                    lblName = lblelement[0].innerText;
                    cells[4].innerHTML = "<label class='lblname' hidden>" + lblName + "</label>" +
                        "<input type='text' class='name' id='gurdian_name' style='border:none' value='" + lblName + "' size='8'>";

                    lblelement = cells[5].getElementsByClassName("lblname");
                    lblName = lblelement[0].innerText;
                    cells[5].innerHTML = "<label class='lblname' hidden>" + lblName + "</label>" +
                        "<input type='number' class='name' id='gurdian_contact' min='0' value='" + lblName + "'>";

                    lblelement = cells[6].getElementsByClassName("lblname");
                    lblName = lblelement[0].innerText;
                    cells[6].innerHTML = "<label class='lblname' hidden>" + lblName + "</label>" +
                        "<input type='text' class='name' id='emergency_name' style='border:none' value='" + lblName + "' size='8'>" +
                        "<br><i>Gurdian:</i> <input type='checkbox' id='gurName' onclick='copyGurToEmerName(this)'>";

                    lblelement = cells[7].getElementsByClassName("lblname");
                    lblName = lblelement[0].innerText;
                    cells[7].innerHTML = "<label class='lblname' hidden>" + lblName + "</label>" +
                        "<input type='number' class='name' id='emergency_contact' min='0' value='" + lblName + "'required>" +
                        "<br><i>Gurdian:</i> <input type='checkbox' id='gurContact' onclick='copyGurToEmerContact(this)'>";

                    lblelement = cells[8].getElementsByClassName("lblname");
                    lblName = lblelement[0].innerText;
                    cells[8].innerHTML = "<label class='lblname' hidden>" + lblName + "</label>" +
                        "<textarea class='name' id='emergency_address' rows='2' cols='15'>" + lblName + "</textarea>" +
                        "<br><i>Current Address:</i> <input type='checkbox' id='emrAddr' onclick='copyCurToEmrAddr(this)'>";
                }
                else if($('#fd_tab').val() == 'payroll'){
                    cells = document.getElementById("payroll-tab").rows[index].cells;
                    chkbx = cells[0].getElementsByClassName("checkbox1");
                    action = cells[0].getElementsByClassName("action");

                    chkbx[0].style.display = 'none';
                    action[0].innerHTML = 'DU';
                    element.innerHTML = "<i class=\"fa fa-undo\"></i>";

                    lblelement = cells[1].getElementsByClassName("lblname");
                    lblIndex = lblelement[0].htmlFor;
                    lblName = lblelement[0].innerText;
                    cells[1].innerHTML = "<label class='lblname' for='" + lblIndex + "' hidden>" + lblName + "</label>" +
                        "<input type='text' class='name' id='emp_display_id' style='border:none' value='" + lblName + "' size='8'>";

                    lblelement = cells[2].getElementsByClassName("lblname");
                    lblIndex = (lblelement[0].htmlFor ? lblelement[0].htmlFor : 0);
                    lblName = lblelement[0].innerText;
                    cells[2].innerHTML = "{{Html::decode(Form::select('bankIfscs', $profiles['bankIfscs'], null, array('class' =>'bankIfscs')))}}";
                    cells[2].innerHTML = "<label class='lblname' for='" + lblIndex + "' hidden>" + lblName + "</label>" + cells[2].innerText;
                    var bankIfscs = cells[2].getElementsByClassName("bankIfscs");
                    bankIfscs[0].options[lblIndex].selected = true;

                    lblelement = cells[3].getElementsByClassName("lblname");
                    lblName = lblelement[0].innerText;
                    cells[3].innerHTML = "<label class='lblname' hidden>" + lblName + "</label>" +
                        "<input type='number' class='name' id='bank_account_no' min='0' value='" + lblName + "' required>";

                    lblelement = cells[4].getElementsByClassName("lblname");
                    lblName = lblelement[0].innerText;
                    cells[4].innerHTML = "<label class='lblname' hidden>" + lblName + "</label>" +
                        "<input type='file' class='name' id='cancel_cheque' accept='.jpg,.jpeg,.png,.bmp,.pdf,.doc,.docx,.xls,.xlsx' multiple>";

                    lblelement = cells[5].getElementsByClassName("lblname");
                    lblName = lblelement[0].innerText;
                    cells[5].innerHTML = "<label class='lblname' hidden>" + lblName + "</label>" +
                        "<input type='number' class='name' id='pf_account_no' min='0' value='" + lblName + "'>";
                }
            }
            else{
                if($('#fd_tab').val() == 'professional'){
                    cells = document.getElementById("professional-tab").rows[index].cells;
                    chkbx = cells[0].getElementsByClassName("checkbox1");
                    action = cells[0].getElementsByClassName("action");

                    chkbx[0].style.display = '';
                    action[0].innerHTML = 'D';
                    element.innerHTML = "<i class=\"fa fa-pencil-square\"></i>";

                    for(var i = 2; i <= 10; i++) {
                        if (i == 2 || i == 4 || i == 5) {
                            lblelement = cells[i].getElementsByClassName("lblname");
                            lblIndex = lblelement[0].htmlFor;
                            lblName = lblelement[0].innerText;
                            cells[i].innerHTML = "<label class='lblname' for='" + lblIndex + "' >" + lblName + "</label>";
                        }
                        else if (i >= 6 && i <= 9) {
                            lblelement = cells[i].getElementsByClassName("lblname");
                            lblDateTime = lblelement[0].htmlFor;
                            lblName = lblelement[0].innerText;
                            cells[i].innerHTML = "<label class='lblname' for='" + lblDateTime + "'>" + lblName + "</label>";
                        }
                        else{
                            lblelement = cells[i].getElementsByClassName("lblname");
                            lblName = lblelement[0].innerText;
                            cells[i].innerHTML = "<label class='lblname'>" + lblName + "</label>";
                        }
                    }
                }
                else if($('#fd_tab').val() == 'kyc_qualification'){
                    cells = document.getElementById("kyc_qualification-tab").rows[index].cells;
                    chkbx = cells[0].getElementsByClassName("checkbox1");
                    action = cells[0].getElementsByClassName("action");

                    chkbx[0].style.display = '';
                    action[0].innerHTML = 'D';
                    element.innerHTML = "<i class=\"fa fa-pencil-square\"></i>";

                    for(var i = 1; i <= 10; i++) {
                        if (i <= 2) {
                            lblelement = cells[i].getElementsByClassName("lblname");
                            lblIndex = lblelement[0].htmlFor;
                            lblName = lblelement[0].innerText;
                            cells[i].innerHTML = "<label class='lblname' for='" + lblIndex + "' >" + lblName + "</label>";
                        }
                        else if(i == 4){
                            lblelement = cells[i].getElementsByClassName("lblname");
                            lblName = lblelement[0].innerText;
                            if(lblName){
                                cells[i].innerHTML = "<label class='lblname' hidden>" + lblName + "</label>" +
                                    "<button id='qualification_docs_id' style='font-size:15px' value='" + lblName + "' onclick='preview_doc(1,this);'>" +
                                    "<i class='fa fa-eye'></i></button>";
                            }
                            else {
                                cells[i].innerHTML = "<label class='lblname' hidden>" + lblName + "</label>";
                            }
                        }
                        else if(i == 6){
                            lblelement = cells[i].getElementsByClassName("lblname");
                            lblName = lblelement[0].innerText;
                            if(lblName){
                                cells[i].innerHTML = "<label class='lblname' hidden>" + lblName + "</label>" +
                                    "<button id='pan_copy_id' style='font-size:15px' value='" + lblName + "' onclick='preview_doc(2,this);'>" +
                                    "<i class='fa fa-eye'></i></button>";
                            }
                            else {
                                cells[i].innerHTML = "<label class='lblname' hidden>" + lblName + "</label>";
                            }
                        }
                        else if(i == 8){
                            lblelement = cells[i].getElementsByClassName("lblname");
                            lblName = lblelement[0].innerText;
                            if(lblName){
                                cells[i].innerHTML = "<label class='lblname' hidden>" + lblName + "</label>" +
                                    "<button id='voter_copy_id' style='font-size:15px' value='" + lblName + "' onclick='preview_doc(3,this);'>" +
                                    "<i class='fa fa-eye'></i></button>";
                            }
                            else {
                                cells[i].innerHTML = "<label class='lblname' hidden>" + lblName + "</label>";
                            }
                        }
                        else if(i == 10){
                            lblelement = cells[i].getElementsByClassName("lblname");
                            lblName = lblelement[0].innerText;
                            if(lblName){
                                cells[i].innerHTML = "<label class='lblname' hidden>" + lblName + "</label>" +
                                    "<button id='aadhar_copy_id' style='font-size:15px' value='" + lblName + "' onclick='preview_doc(4,this);'>" +
                                    "<i class='fa fa-eye'></i></button>";
                            }
                            else {
                                cells[i].innerHTML = "<label class='lblname' hidden>" + lblName + "</label>";
                            }
                        }
                        else{
                            lblelement = cells[i].getElementsByClassName("lblname");
                            lblName = lblelement[0].innerText;
                            cells[i].innerHTML = "<label class='lblname'>" + lblName + "</label>";
                        }
                    }
                }
                else if($('#fd_tab').val() == 'personal'){
                    cells = document.getElementById("personal-tab").rows[index].cells;
                    chkbx = cells[0].getElementsByClassName("checkbox1");
                    action = cells[0].getElementsByClassName("action");

                    chkbx[0].style.display = '';
                    action[0].innerHTML = 'D';
                    element.innerHTML = "<i class=\"fa fa-pencil-square\"></i>";

                    for(var i = 1; i <= 10; i++) {
                        if(i == 1){
                            lblelement = cells[i].getElementsByClassName("lblname");
                            lblIndex = lblelement[0].htmlFor;
                            lblName = lblelement[0].innerText;
                            cells[i].innerHTML = "<label class='lblname' for='" + lblIndex + "' >" + lblName + "</label>";
                        }
                        else if(i == 10){
                            lblelement = cells[i].getElementsByClassName("lblname");
                            lblName = lblelement[0].innerText;
                            if(lblName){
                                cells[i].innerHTML = "<label class='lblname' hidden>" + lblName + "</label>" +
                                    "<button id='profile_image_id' style='font-size:15px' value='" + lblName + "' onclick='preview_doc(5,this);'>" +
                                    "<i class='fa fa-eye'></i></button>";
                            }
                            else {
                                cells[i].innerHTML = "<label class='lblname' hidden>" + lblName + "</label>";
                            }
                        }
                        else {
                            lblelement = cells[i].getElementsByClassName("lblname");
                            lblName = lblelement[0].innerText;
                            cells[i].innerHTML = "<label class='lblname'>" + lblName + "</label>";
                        }
                    }
                }
                else if($('#fd_tab').val() == 'address'){
                    cells = document.getElementById("address-tab").rows[index].cells;
                    chkbx = cells[0].getElementsByClassName("checkbox1");
                    action = cells[0].getElementsByClassName("action");

                    chkbx[0].style.display = '';
                    action[0].innerHTML = 'D';
                    element.innerHTML = "<i class=\"fa fa-pencil-square\"></i>";

                    for(var i = 1; i <= 8; i++) {
                        if(i == 1) {
                            lblelement = cells[i].getElementsByClassName("lblname");
                            lblIndex = lblelement[0].htmlFor;
                            lblName = lblelement[0].innerText;
                            cells[i].innerHTML = "<label class='lblname' for='" + lblIndex + "' >" + lblName + "</label>";
                        }
                        else {
                            lblelement = cells[i].getElementsByClassName("lblname");
                            lblName = lblelement[0].innerText;
                            cells[i].innerHTML = "<label class='lblname'>" + lblName + "</label>";
                        }
                    }
                }
                else if($('#fd_tab').val() == 'payroll'){
                    cells = document.getElementById("payroll-tab").rows[index].cells;
                    chkbx = cells[0].getElementsByClassName("checkbox1");
                    action = cells[0].getElementsByClassName("action");

                    chkbx[0].style.display = '';
                    action[0].innerHTML = 'D';
                    element.innerHTML = "<i class=\"fa fa-pencil-square\"></i>";

                    for(var i = 1; i <= 5; i++) {
                        if (i <= 2) {
                            lblelement = cells[i].getElementsByClassName("lblname");
                            lblIndex = lblelement[0].htmlFor;
                            lblName = lblelement[0].innerText;
                            cells[i].innerHTML = "<label class='lblname' for='" + lblIndex + "' >" + lblName + "</label>";
                        }
                        else if(i == 4){
                            lblelement = cells[i].getElementsByClassName("lblname");
                            lblName = lblelement[0].innerText;
                            if(lblName){
                                cells[i].innerHTML = "<label class='lblname' hidden>" + lblName + "</label>" +
                                    "<button id='cancel_cheque_id' style='font-size:15px' value='" + lblName + "' onclick='preview_doc(6,this);'>" +
                                    "<i class='fa fa-eye'></i></button>";
                            }
                            else {
                                cells[i].innerHTML = "<label class='lblname' hidden>" + lblName + "</label>";
                            }
                        }
                        else{
                            lblelement = cells[i].getElementsByClassName("lblname");
                            lblName = lblelement[0].innerText;
                            cells[i].innerHTML = "<label class='lblname'>" + lblName + "</label>";
                        }
                    }
                }
            }
        }

        function register(element, index){
            var display_id = null;
            if($('#fd_tab').val() == 'professional') {
                cells = document.getElementById("professional-tab").rows[index].cells;
                display_id = cells[3].innerText;
                //alert(display_id);
                $('#api_emp_id').val(display_id);
                $("#api_action").submit();
            }
        }

        function uploadScannedCopy(emp_id, doc_type, docs){
            var returnVal = null;
            var formData = new FormData();

            if(docs[0].files.length > 0) {
                formData.append('empId', emp_id[0].value);
                formData.append('docType', doc_type);
                for (var i = 0; i < docs[0].files.length; i++) {
                    formData.append('file[]', docs[0].files[i]);
                }

                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '\\emp_profile\\docs_upload',
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    async: false,
                    success: function (data) {
                        //alert('SUCCESS: ' + data);
                        returnVal = data;
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alert('ERREOR: ' + JSON.stringify(xhr));
                        //alert(JSON.stringify(ajaxOptions));
                        //alert(JSON.stringify(thrownError));
                        //console.log(errors);
                    }
                });
            }
            return returnVal;
        }

        function inputValidaton(emp_display_id){
            var retFlag = false;
            if(emp_display_id[0].value != ''){
                retFlag = true;
            }
            return retFlag;
        }

        function validateEmail(email_to_test){
            var retVal = false;

            if (/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(email_to_test))
            {
                retVal = true;
            }
            return retVal;
        }

        function save(){
            var grid = null;
            var checkBoxes = null;
            var actions = null;
            var lblelement, sr_id;
            var nArr = [];
            var uArr = [];
            var dArr = [];
            var qArr = [];
            var qIndx = 0;
            var message = '';

            if ($('#fd_tab').val() == 'professional') {
                var access_control, emp_display_id, department, designation, status;
                var join_date, leave_date, last_login, last_logout;

                grid = document.getElementById("professional-tab");
                checkBoxes = grid.getElementsByClassName("checkbox1");
                actions = grid.getElementsByClassName("action");

                for (var k = 0, n = 0, u = 0, d = 0, l = 0; k < actions.length; k++) {
                    var row = actions[k].parentNode.parentNode;
                    if (actions[k].innerHTML == 'L') {
                        l++;
                    } else if (actions[k].innerHTML == 'C') {
                        if (inputValidaton(row.cells[3].getElementsByClassName("name"))) {
                            //New record insert request
                            access_control = row.cells[2].getElementsByClassName("access_control");
                            emp_display_id = row.cells[3].getElementsByClassName("name");
                            department = row.cells[4].getElementsByClassName("department");
                            designation = row.cells[5].getElementsByClassName("designation");
                            join_date = row.cells[6].getElementsByClassName("join_date");
                            leave_date = row.cells[7].getElementsByClassName("leave_date");
                            //last_login = row.cells[8].getElementsByClassName("last_login");
                            //last_logout = row.cells[9].getElementsByClassName("last_logout");
                            status = row.cells[8].getElementsByClassName("name");

                            nArr[n++] = JSON.stringify({
                                "access_control": access_control[0].options[access_control[0].selectedIndex].text,
                                "emp_display_id": emp_display_id[0].value,
                                "department": department[0].options[department[0].selectedIndex].text,
                                "designation": designation[0].options[designation[0].selectedIndex].text,
                                "join_date": join_date[0].value,
                                "leave_date": leave_date[0].value,
                                //"last_login": last_login[0].value,
                                //"last_logout": last_logout[0].value,
                                "status": status[0].options[status[0].selectedIndex].text
                            });
                        }
                        else{
                            alert('Employee display id is mandatory');
                        }
                    } else if (actions[k].innerHTML == 'LU' || actions[k].innerHTML == 'DU') {
                        if (inputValidaton(row.cells[3].getElementsByClassName("name"))) {
                            //Existing record update request
                            access_control = row.cells[2].getElementsByClassName("access_control");
                            emp_display_id = row.cells[3].getElementsByClassName("name");
                            department = row.cells[4].getElementsByClassName("department");
                            designation = row.cells[5].getElementsByClassName("designation");
                            join_date = row.cells[6].getElementsByClassName("join_date");
                            leave_date = row.cells[7].getElementsByClassName("leave_date");
                            //last_login = row.cells[8].getElementsByClassName("last_login");
                            //last_logout = row.cells[9].getElementsByClassName("last_logout");
                            status = row.cells[8].getElementsByClassName("name");

                            uArr[u++] = JSON.stringify({
                                "id": row.cells[1].innerHTML,
                                "access_control": access_control[0].options[access_control[0].selectedIndex].text,
                                "emp_display_id": emp_display_id[0].value,
                                "department": department[0].options[department[0].selectedIndex].text,
                                "designation": designation[0].options[designation[0].selectedIndex].text,
                                "join_date": join_date[0].value,
                                "leave_date": leave_date[0].value,
                                //"last_login": last_login[0].value,
                                //"last_logout": last_logout[0].value,
                                "status": status[0].options[status[0].selectedIndex].text
                            });
                        }
                        else{
                            alert('Employee display Id is mandatory');
                        }

                        if (actions[k].innerHTML == 'LU') {
                            l++;
                        }
                    } else if (actions[k].innerHTML == 'D') {
                        if (checkBoxes[k - l]) {
                            if (checkBoxes[k - l].checked) {
                                //Existing record delete request
                                dArr[d++] = JSON.stringify({"id": row.cells[1].innerHTML});
                            }
                        }
                    }
                }
            }
            else if ($('#fd_tab').val() == 'kyc_qualification') {
                var qualification, emp_display_id, qualification_other, qualification_docs;
                var panNumber, pan_copy, voterId, voter_copy, aadharNumber, aadhar_copy;

                grid = document.getElementById("kyc_qualification-tab");
                checkBoxes = grid.getElementsByClassName("checkbox1");
                actions = grid.getElementsByClassName("action");

                for (var k = 0, n = 0, u = 0, d = 0, l = 0; k < actions.length; k++) {
                    var row = actions[k].parentNode.parentNode;
                    if (actions[k].innerHTML == 'L') {
                        l++;
                    } else if (actions[k].innerHTML == 'C') {
                        if (inputValidaton(row.cells[1].getElementsByClassName("name"))) {
                            //New record insert request
                            //lblelement = row.cells[1].getElementsByClassName("lblname");
                            //sr_id = lblelement[0].htmlFor;
                            emp_display_id = row.cells[1].getElementsByClassName("name");
                            qualification = row.cells[2].getElementsByClassName("qualification");
                            qualification_other = row.cells[3].getElementsByClassName("name");
                            qualification_docs = uploadScannedCopy(emp_display_id, 'qualification_docs', row.cells[4].getElementsByClassName("name"));
                            panNumber = row.cells[5].getElementsByClassName("name");
                            pan_copy = uploadScannedCopy(emp_display_id, 'pan_copy', row.cells[6].getElementsByClassName("name"));
                            voterId = row.cells[7].getElementsByClassName("name");
                            voter_copy = uploadScannedCopy(emp_display_id, 'voter_copy', row.cells[8].getElementsByClassName("name"));
                            aadharNumber = row.cells[9].getElementsByClassName("name");
                            aadhar_copy = uploadScannedCopy(emp_display_id, 'aadhar_copy', row.cells[10].getElementsByClassName("name"));

                            nArr[n++] = JSON.stringify({
                                //"id": sr_id,
                                "emp_display_id": emp_display_id[0].value,
                                "qualification": qualification[0].options[qualification[0].selectedIndex].text,
                                "qualification_other": qualification_other[0].value,
                                "qualification_docs": qualification_docs,
                                "panNumber": panNumber[0].value,
                                "pan_copy": pan_copy,
                                "voterId": voterId[0].value,
                                "voter_copy": voter_copy,
                                "aadharNumber": aadharNumber[0].value,
                                "aadhar_copy": aadhar_copy
                            });
                        }
                        else{
                            alert('Employee display Id is mandatory');
                        }
                    } else if (actions[k].innerHTML == 'LU' || actions[k].innerHTML == 'DU') {
                        if (inputValidaton(row.cells[1].getElementsByClassName("name"))) {
                            //Existing record update request
                            lblelement = row.cells[1].getElementsByClassName("lblname");
                            sr_id = lblelement[0].htmlFor;
                            emp_display_id = row.cells[1].getElementsByClassName("name");
                            qualification = row.cells[2].getElementsByClassName("qualification");
                            qualification_other = row.cells[3].getElementsByClassName("name");
                            qualification_docs = uploadScannedCopy(emp_display_id, 'qualification_docs', row.cells[4].getElementsByClassName("name"));
                            panNumber = row.cells[5].getElementsByClassName("name");
                            pan_copy = uploadScannedCopy(emp_display_id, 'pan_copy', row.cells[6].getElementsByClassName("name"));
                            voterId = row.cells[7].getElementsByClassName("name");
                            voter_copy = uploadScannedCopy(emp_display_id, 'voter_copy', row.cells[8].getElementsByClassName("name"));
                            aadharNumber = row.cells[9].getElementsByClassName("name");
                            aadhar_copy = uploadScannedCopy(emp_display_id, 'aadhar_copy', row.cells[10].getElementsByClassName("name"));

                            uArr[u++] = JSON.stringify({
                                "id": sr_id,
                                "emp_display_id": emp_display_id[0].value,
                                "qualification": qualification[0].options[qualification[0].selectedIndex].text,
                                "qualification_other": qualification_other[0].value,
                                "qualification_docs": qualification_docs,
                                "panNumber": panNumber[0].value,
                                "pan_copy": pan_copy,
                                "voterId": voterId[0].value,
                                "voter_copy": voter_copy,
                                "aadharNumber": aadharNumber[0].value,
                                "aadhar_copy": aadhar_copy
                            });
                        }
                        else{
                            alert('Employee display Id is mandatory');
                        }

                        if (actions[k].innerHTML == 'LU') {
                            l++;
                        }
                    } else if (actions[k].innerHTML == 'D') {
                        if (checkBoxes[k - l]) {
                            if (checkBoxes[k - l].checked) {
                                //Existing record delete request
                                lblelement = row.cells[1].getElementsByClassName("lblname");
                                sr_id = lblelement[0].htmlFor;
                                dArr[d++] = JSON.stringify({"id": sr_id});
                            }
                        }
                    }
                }
            }
            else if ($('#fd_tab').val() == 'personal') {
                var emp_display_id, emp_name, dob, gender;
                var mobile, mob_verified, email, email_verified, marital_status, profile_image;

                grid = document.getElementById("personal-tab");
                checkBoxes = grid.getElementsByClassName("checkbox1");
                actions = grid.getElementsByClassName("action");

                for (var k = 0, n = 0, u = 0, d = 0, l = 0; k < actions.length; k++) {
                    var row = actions[k].parentNode.parentNode;
                    if (actions[k].innerHTML == 'L') {
                        l++;
                    } else if (actions[k].innerHTML == 'C') {
                        if (inputValidaton(row.cells[1].getElementsByClassName("name"))) {
                            //New record insert request
                            //lblelement = row.cells[1].getElementsByClassName("lblname");
                            //sr_id = lblelement[0].htmlFor;
                            emp_display_id = row.cells[1].getElementsByClassName("name");
                            emp_name = row.cells[2].getElementsByClassName("name");
                            dob = row.cells[3].getElementsByClassName("name");
                            gender = row.cells[4].getElementsByClassName("name");
                            mobile = row.cells[5].getElementsByClassName("name");
                            mob_verified = row.cells[6].getElementsByClassName("name");
                            email = row.cells[7].getElementsByClassName("name");
                            email_verified = row.cells[8].getElementsByClassName("name");
                            marital_status = row.cells[9].getElementsByClassName("name");
                            profile_image = uploadScannedCopy(emp_display_id, 'profile_image', row.cells[10].getElementsByClassName("name"));

                            nArr[n++] = JSON.stringify({
                                //"id": sr_id,
                                "emp_display_id": emp_display_id[0].value,
                                "emp_name": emp_name[0].value,
                                "dob": dob[0].value,
                                "gender": gender[0].options[gender[0].selectedIndex].text,
                                "mobile": mobile[0].value,
                                "mob_verified": mob_verified[0].options[mob_verified[0].selectedIndex].text,
                                "email": email[0].value,
                                "email_verified": email_verified[0].options[email_verified[0].selectedIndex].text,
                                "marital_status": marital_status[0].options[marital_status[0].selectedIndex].text,
                                "profile_image": profile_image
                            });
                        }
                        else{
                            alert('Employee display Id is mandatory');
                        }
                    } else if (actions[k].innerHTML == 'LU' || actions[k].innerHTML == 'DU') {
                        if (inputValidaton(row.cells[1].getElementsByClassName("name"))) {
                            //Existing record update request
                            lblelement = row.cells[1].getElementsByClassName("lblname");
                            sr_id = lblelement[0].htmlFor;
                            emp_display_id = row.cells[1].getElementsByClassName("name");
                            emp_name = row.cells[2].getElementsByClassName("name");
                            dob = row.cells[3].getElementsByClassName("name");
                            gender = row.cells[4].getElementsByClassName("name");
                            mobile = row.cells[5].getElementsByClassName("name");
                            mob_verified = row.cells[6].getElementsByClassName("name");
                            email = row.cells[7].getElementsByClassName("name");
                            email_verified = row.cells[8].getElementsByClassName("name");
                            marital_status = row.cells[9].getElementsByClassName("name");
                            profile_image = uploadScannedCopy(emp_display_id, 'profile_image', row.cells[10].getElementsByClassName("name"));

                            uArr[u++] = JSON.stringify({
                                "id": sr_id,
                                "emp_display_id": emp_display_id[0].value,
                                "emp_name": emp_name[0].value,
                                "dob": dob[0].value,
                                "gender": gender[0].options[gender[0].selectedIndex].text,
                                "mobile": mobile[0].value,
                                "mob_verified": mob_verified[0].options[mob_verified[0].selectedIndex].text,
                                "email": email[0].value,
                                "email_verified": email_verified[0].options[email_verified[0].selectedIndex].text,
                                "marital_status": marital_status[0].options[marital_status[0].selectedIndex].text,
                                "profile_image": profile_image
                            });
                        }
                        else{
                            alert('Employee display Id is mandatory');
                        }

                        if (actions[k].innerHTML == 'LU') {
                            l++;
                        }
                    } else if (actions[k].innerHTML == 'D') {
                        if (checkBoxes[k - l]) {
                            if (checkBoxes[k - l].checked) {
                                //Existing record delete request
                                lblelement = row.cells[1].getElementsByClassName("lblname");
                                sr_id = lblelement[0].htmlFor;
                                dArr[d++] = JSON.stringify({"id": sr_id});
                            }
                        }
                    }
                }
            }
            else if ($('#fd_tab').val() == 'address') {
                var emp_display_id, address_current, address_permanent, gurdian_name, gurdian_contact;
                var emergency_name, emergency_contact, emergency_address;

                grid = document.getElementById("address-tab");
                checkBoxes = grid.getElementsByClassName("checkbox1");
                actions = grid.getElementsByClassName("action");

                for (var k = 0, n = 0, u = 0, d = 0, l = 0; k < actions.length; k++) {
                    var row = actions[k].parentNode.parentNode;
                    if (actions[k].innerHTML == 'L') {
                        l++;
                    } else if (actions[k].innerHTML == 'C') {
                        if (inputValidaton(row.cells[1].getElementsByClassName("name"))) {
                            //New record insert request
                            //lblelement = row.cells[1].getElementsByClassName("lblname");
                            //sr_id = lblelement[0].htmlFor;
                            emp_display_id = row.cells[1].getElementsByClassName("name");
                            address_current = row.cells[2].getElementsByClassName("name");
                            address_permanent = row.cells[3].getElementsByClassName("name");
                            gurdian_name = row.cells[4].getElementsByClassName("name");
                            gurdian_contact = row.cells[5].getElementsByClassName("name");
                            emergency_name = row.cells[6].getElementsByClassName("name");
                            emergency_contact = row.cells[7].getElementsByClassName("name");
                            emergency_address = row.cells[8].getElementsByClassName("name");

                            nArr[n++] = JSON.stringify({
                                //"id": sr_id,
                                "emp_display_id": emp_display_id[0].value,
                                "address_current": address_current[0].value,
                                "address_permanent": address_permanent[0].value,
                                "gurdian_name": gurdian_name[0].value,
                                "gurdian_contact": gurdian_contact[0].value,
                                "emergency_name": emergency_name[0].value,
                                "emergency_contact": emergency_contact[0].value,
                                "emergency_address": emergency_address[0].value
                            });
                        }
                        else{
                            alert('Employee display Id is mandatory');
                        }
                    } else if (actions[k].innerHTML == 'LU' || actions[k].innerHTML == 'DU') {
                        if (inputValidaton(row.cells[1].getElementsByClassName("name"))) {
                            //Existing record update request
                            lblelement = row.cells[1].getElementsByClassName("lblname");
                            sr_id = lblelement[0].htmlFor;
                            emp_display_id = row.cells[1].getElementsByClassName("name");
                            address_current = row.cells[2].getElementsByClassName("name");
                            address_permanent = row.cells[3].getElementsByClassName("name");
                            gurdian_name = row.cells[4].getElementsByClassName("name");
                            gurdian_contact = row.cells[5].getElementsByClassName("name");
                            emergency_name = row.cells[6].getElementsByClassName("name");
                            emergency_contact = row.cells[7].getElementsByClassName("name");
                            emergency_address = row.cells[8].getElementsByClassName("name");

                            uArr[u++] = JSON.stringify({
                                "id": sr_id,
                                "emp_display_id": emp_display_id[0].value,
                                "address_current": address_current[0].value,
                                "address_permanent": address_permanent[0].value,
                                "gurdian_name": gurdian_name[0].value,
                                "gurdian_contact": gurdian_contact[0].value,
                                "emergency_name": emergency_name[0].value,
                                "emergency_contact": emergency_contact[0].value,
                                "emergency_address": emergency_address[0].value
                            });
                        }
                        else{
                            alert('Employee display Id is mandatory');
                        }

                        if (actions[k].innerHTML == 'LU') {
                            l++;
                        }
                    } else if (actions[k].innerHTML == 'D') {
                        if (checkBoxes[k - l]) {
                            if (checkBoxes[k - l].checked) {
                                //Existing record delete request
                                lblelement = row.cells[1].getElementsByClassName("lblname");
                                sr_id = lblelement[0].htmlFor;
                                dArr[d++] = JSON.stringify({"id": sr_id});
                            }
                        }
                    }
                }
            }
            else if ($('#fd_tab').val() == 'payroll') {
                var emp_display_id, bank_Ifscs, bank_account_no, cancel_cheque, pf_account_no;

                grid = document.getElementById("payroll-tab");
                checkBoxes = grid.getElementsByClassName("checkbox1");
                actions = grid.getElementsByClassName("action");

                for (var k = 0, n = 0, u = 0, d = 0, l = 0; k < actions.length; k++) {
                    var row = actions[k].parentNode.parentNode;
                    if (actions[k].innerHTML == 'L') {
                        l++;
                    } else if (actions[k].innerHTML == 'C') {
                        if (inputValidaton(row.cells[1].getElementsByClassName("name"))) {
                            //New record insert request
                            //lblelement = row.cells[1].getElementsByClassName("lblname");
                            //sr_id = lblelement[0].htmlFor;
                            emp_display_id = row.cells[1].getElementsByClassName("name");
                            bank_Ifscs = row.cells[2].getElementsByClassName("bankIfscs");
                            bank_account_no = row.cells[3].getElementsByClassName("name");
                            cancel_cheque = uploadScannedCopy(emp_display_id, 'cancel_cheque', row.cells[4].getElementsByClassName("name"));
                            pf_account_no = row.cells[5].getElementsByClassName("name");

                            nArr[n++] = JSON.stringify({
                                //"id": sr_id,
                                "emp_display_id": emp_display_id[0].value,
                                "bank_Ifscs": bank_Ifscs[0].options[bank_Ifscs[0].selectedIndex].text,
                                "bank_account_no": bank_account_no[0].value,
                                "cancel_cheque": cancel_cheque,
                                "pf_account_no": pf_account_no[0].value
                            });
                        }
                        else{
                            alert('Employee display Id is mandatory');
                        }
                    } else if (actions[k].innerHTML == 'LU' || actions[k].innerHTML == 'DU') {
                        if (inputValidaton(row.cells[1].getElementsByClassName("name"))) {
                            //Existing record update request
                            lblelement = row.cells[1].getElementsByClassName("lblname");
                            sr_id = lblelement[0].htmlFor;
                            emp_display_id = row.cells[1].getElementsByClassName("name");
                            bank_Ifscs = row.cells[2].getElementsByClassName("bankIfscs");
                            bank_account_no = row.cells[3].getElementsByClassName("name");
                            cancel_cheque = uploadScannedCopy(emp_display_id, 'cancel_cheque', row.cells[4].getElementsByClassName("name"));
                            pf_account_no = row.cells[5].getElementsByClassName("name");

                            uArr[u++] = JSON.stringify({
                                "id": sr_id,
                                "emp_display_id": emp_display_id[0].value,
                                "bank_Ifscs": bank_Ifscs[0].options[bank_Ifscs[0].selectedIndex].text,
                                "bank_account_no": bank_account_no[0].value,
                                "cancel_cheque": cancel_cheque,
                                "pf_account_no": pf_account_no[0].value
                            });
                        }
                        else{
                            alert('Employee display Id is mandatory');
                        }

                        if (actions[k].innerHTML == 'LU') {
                            l++;
                        }
                    } else if (actions[k].innerHTML == 'D') {
                        if (checkBoxes[k - l]) {
                            if (checkBoxes[k - l].checked) {
                                //Existing record delete request
                                lblelement = row.cells[1].getElementsByClassName("lblname");
                                sr_id = lblelement[0].htmlFor;
                                dArr[d++] = JSON.stringify({"id": sr_id});
                            }
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
                document.getElementById("cud_action").action = '{{ route("emp_profile.update_data") }}?page=' + $('#fd_cud_page').val();
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
    <!-- Modal #document_preview-->
    <div id="documentPreview" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Document Preview</h4>
                </div>
                <div class="modal-body" style="height: 400px;overflow-y: auto;">
                    <div id="documentPreview_dialog" style="text-align:center"></div>
                </div>
            </div>
            <script>
                function hide_documentPreview(){
                    $("#documentPreview").modal("hide");
                }
            </script>
        </div>
    </div>
@endsection
