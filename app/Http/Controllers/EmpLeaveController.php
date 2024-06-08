<?php

namespace App\Http\Controllers;

use App\EmpProfile;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\EmpLeave;
use App\EmpLeaveAllotment;
use App\EmpDesignation;
use App\LeaveCategory;
use App\LeaveSchedule;
use Illuminate\Support\Facades\DB;
class EmpLeaveController extends Controller
{
    private function now(){
        date_default_timezone_set('Indian/Chagos');
        $timezone = date_default_timezone_get();
        $now = date_create(date('Y-m-d H:i:s'));
        date_add($now,date_interval_create_from_date_string("-30 minutes"));
        return $now;
    }

    private function validation(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|unique:empLeave',
        ]);
    }

    private function manageLeaveAllotment($emp_id, $leave_type, $leave_status, $prev_leave_status, $start_date, $end_date){
        $status = true;
        $today = date("Y-m-d");
        if($today > $start_date){
            $dateDiff = date_diff(date_create($end_date), date_create($start_date));
            $days = $dateDiff->format('%a') + 1;
            //dd($days);
            if($days > 0 and $leave_status == 'Accepted'){
                $record = EmpLeaveAllotment::find($emp_id);
                if($leave_type == 'CL' && $record->CL >= $days){
                    $record->CL -= $days;
                }
                else if($leave_type == 'SL' && $record->SL >= $days){
                    $record->SL -= $days;
                }
                else if($leave_type == 'EL' && $record->EL >= $days){
                    $record->EL -= $days;
                }
                else if($leave_type == 'PL' && $record->PL >= $days){
                    $record->PL -= $days;
                }
                else{
                    $status = false;
                }
                $record->update();
            }
            else if($days > 0 and $prev_leave_status == 'Accepted'){
                $record = EmpLeaveAllotment::find($emp_id);
                if($leave_type == 'CL'){
                    $record->CL += $days;
                }
                else if($leave_type == 'SL'){
                    $record->SL += $days;
                }
                else if($leave_type == 'EL'){
                    $record->EL += $days;
                }
                else if($leave_type == 'PL'){
                    $record->PL += $days;
                }
                else{
                    $status = false;
                }
                $record->update();
            }
        }
        return $status;
    }

    private function new_rec_create(array $recs){
        $status = null;
        $id = Auth::id();
        $user = User::find($id);

        foreach ($recs as $rec) {
            $record = new EmpLeave();
            $profile_id = EmpProfile::where('emp_display_id', $rec['emp_display_id'])->select('profile_id')->get();
            $record->emp_id = $profile_id[0]->{'profile_id'};
            if($rec['leave_type'] != 'select') {
                $leave_type_id = LeaveCategory::where('name', $rec['leave_type'])->select('id')->get();
                $leave_schedule_id = LeaveSchedule::where('leave_type_id', $leave_type_id[0]->{'id'})->select('id')->get();
                $record->leave_class_id = $leave_schedule_id[0]->{'id'};
            }
            $record->reason = $rec['reason'];
            $record->start_date = $rec['start_date'];
            $record->end_date = $rec['end_date'];
            if($rec['leave_status'] != 'select') {
                $record->leave_status = $rec['leave_status'];
                $status = $this->manageLeaveAllotment($record->emp_id, $rec['leave_type'], $record->leave_status, $record->prev_leave_status, $record->start_date, $record->end_date);
                $record->prev_leave_status = $rec['leave_status'];
            }
            $record->created_by = $user->admin;
            $record->created_date = $this->now();
            if($status == true) {
                $record->save();
            }
        }
    }

    private function rec_update(array $recs)
    {
        $status = null;
        $uid = Auth::id();
        $user = User::find($uid);

        foreach ($recs as $rec) {
            //Check for duplicate entry
            //Update data in DB
            $record = EmpLeave::find($rec['id']);
            $profile_id = EmpProfile::where('emp_display_id', $rec['emp_display_id'])->select('profile_id')->get();
            $record->emp_id = $profile_id[0]->{'profile_id'};
            if($rec['leave_type'] != 'select') {
                $leave_type_id = LeaveCategory::where('name', $rec['leave_type'])->select('id')->get();
                $leave_schedule_id = LeaveSchedule::where('leave_type_id', $leave_type_id[0]->{'id'})->select('id')->get();
                $record->leave_class_id = $leave_schedule_id[0]->{'id'};
            }
            $record->reason = $rec['reason'];
            $record->start_date = $rec['start_date'];
            $record->end_date = $rec['end_date'];
            if($rec['leave_status'] != 'select') {
                $record->leave_status = $rec['leave_status'];
                $status =  $this->manageLeaveAllotment($record->emp_id, $rec['leave_type'], $record->leave_status, $record->prev_leave_status, $record->start_date, $record->end_date);
                $record->prev_leave_status = $rec['leave_status'];
            }
            $record->modified_by = $user->admin;
            $record->modified_date = $this->now();
            if($status == true) {
                $record->save();
            }
        }
    }

    private function rec_delete(array $recs){
        foreach ($recs as $rec) {
            $record = EmpLeave::find($rec['id']);
            if($record) {
                $record->delete();
            }
        }
    }

    private function fetchRelativeData($leaveAllotment){
        $result = [];
        $i = 0;
        foreach($leaveAllotment as $leaveAllot){
            $record = EmpProfile::find($leaveAllot->id);
            //dd($record);
            $res['emp_display_id'] = $record->emp_display_id;
            if($leaveAllot->designation_id){
                $designationName  = EmpDesignation::find($leaveAllot->designation_id);
                //dd($designationName)
                $res['designation'] = $designationName->name;
            }
            else if($record->designation_id){
                $designationName  = EmpDesignation::find($record->designation_id);
                //dd($designationName)
                $res['designation'] = $designationName->name;
                //
                $rec_leaveAllot = EmpLeaveAllotment::find($leaveAllot->id);
                //dd($rec_leaveAllot);
                $rec_leaveAllot->designation_id = $record->designation_id;
                $rec_leaveAllot->save();
            }
            else{
                $res['designation'] = null;
            }
            $res['CL'] = $leaveAllot->CL;
            $res['SL'] = $leaveAllot->SL;
            $res['EL'] = $leaveAllot->EL;
            $res['PL'] = $leaveAllot->PL;
            $result[$i++] = $res;
            //break;
        }
        //$designation = $leaveName->name;
        return $result;
    }

    public function index(){
        $isAdmin = null;
        $uid = Auth::id();
        $user = User::find($uid);

        if($user->admin == '1002') {
            $empLeaves = EmpLeave::getByPaginate(8, null);
            //dd($empLeaves);
            $leaveAllotment = EmpLeaveAllotment::All();
            $leaveAllotment = $this->fetchRelativeData($leaveAllotment);
            //dd($leaveAllotment);
            $data = [
                'leaveAllotment' => $leaveAllotment,
                'empLeaves' => $empLeaves
            ];
            $isAdmin = 1;
        }
        else{
            $empLeaves = EmpLeave::getByPaginate(8, $uid);
            //dd($empLeaves);
            $leaveAllotment = EmpLeaveAllotment::getLeaveAllotment($uid);
            $leaveAllotment = $this->fetchRelativeData($leaveAllotment);
            //dd($leaveAllotment);
            $data = [
                'leaveAllotment' => $leaveAllotment,
                'empLeaves' => $empLeaves
            ];
            $isAdmin = 0;
        }
        $pageSetting = ["page" => "1", "recs" => 8, "sort_type" => "asc", "sort_by" => "id", "query" => "", "isAdmin" => $isAdmin];
        return view('admin.empLeave', compact('data'))->with('pageSetting', $pageSetting);
    }

    public function fetch_data(Request $request)
    {
        $isAdmin = null;
        $uid = Auth::id();
        $user = User::find($uid);

        $page = $request->input('fd_page');
        $recs = $request->input('fd_recs');
        $sort_by = $request->input('fd_sort_by');
        $sort_type = $request->input('fd_sort_type');
        $query = $request->input('fd_query');
        $query = Str::replaceArray(" ", ["%"], $query);

        if($user->admin == '1002') {
            $empLeaves = EmpLeave::getByConditionalPaginate($recs, $sort_by, $sort_type, $query, null);
            //dd($empLeaves);
            $leaveAllotment = EmpLeaveAllotment::All();
            $leaveAllotment = $this->fetchRelativeData($leaveAllotment);
            //dd($leaveAllotment);
            $data = [
                'leaveAllotment' => $leaveAllotment,
                'empLeaves' => $empLeaves
            ];
            $isAdmin = 1;
        }
        else{
            $empLeaves = EmpLeave::getByConditionalPaginate($recs, $sort_by, $sort_type, $query, $uid);
            //dd($empLeaves);
            $leaveAllotment = EmpLeaveAllotment::getLeaveAllotment($uid);
            $leaveAllotment = $this->fetchRelativeData($leaveAllotment);
            //dd($leaveAllotment);
            $data = [
                'leaveAllotment' => $leaveAllotment,
                'empLeaves' => $empLeaves
            ];
            $isAdmin = 0;
        }
        $pageSetting = ["page" => $page, "recs" => $recs, "sort_type" => $sort_type, "sort_by" => $sort_by, "query" => $query, "isAdmin" => $isAdmin];
        return view('admin.empLeave', compact('data'))->with('pageSetting', $pageSetting);
    }

    public function processJSON(Request $request)
    {
        $uid = Auth::id();
        $user = User::find($uid);

        $resStr = '';
        $inputs = json_decode($request->input('fd_cud'),true);
        //dd($inputs);

        if(array_key_exists("C", $inputs)){
            $this->new_rec_create($inputs["C"]);
            $resStr .= ($resStr == '' ? 'C' : ';C');
        }

        if(array_key_exists("U", $inputs)){
            $this->rec_update($inputs["U"]);
            $resStr .= ($resStr == '' ? 'U' : ';U');
        }

        if(array_key_exists("D", $inputs)){
            $this->rec_delete($inputs["D"]);
            $resStr .= ($resStr == '' ? 'D' : ';D');
        }

        //Now prepare the query
        $page = $request->input('fd_cud_page');
        $recs = $request->input('fd_cud_recs');
        $sort_by = $request->input('fd_cud_sort_by');
        $sort_type = $request->input('fd_cud_sort_type');
        $query = $request->input('fd_cud_query');
        $query = Str::replaceArray(" ", ["%"], $query);

        if($user->admin == '1002') {
            $empLeaves = EmpLeave::getByConditionalPaginate($recs, $sort_by, $sort_type, $query, null);
            //dd($empLeaves);
            $leaveAllotment = EmpLeaveAllotment::All();
            $leaveAllotment = $this->fetchRelativeData($leaveAllotment);
            //dd($leaveAllotment);
            $data = [
                'leaveAllotment' => $leaveAllotment,
                'empLeaves' => $empLeaves
            ];
        }
        else{
            $empLeaves = EmpLeave::getByConditionalPaginate($recs, $sort_by, $sort_type, $query, $uid);
            //dd($empLeaves);
            $leaveAllotment = EmpLeaveAllotment::getLeaveAllotment($uid);
            $leaveAllotment = $this->fetchRelativeData($leaveAllotment);
            //dd($leaveAllotment);
            $data = [
                'leaveAllotment' => $leaveAllotment,
                'empLeaves' => $empLeaves
            ];
        }
        $pageSetting = ["page" => $page, "recs" => $recs, "sort_type" => $sort_type, "sort_by" => $sort_by, "query" => $query];
        return view('admin.empLeave', compact('data'))->with('pageSetting', $pageSetting);
    }
}
