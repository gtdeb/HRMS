<?php

namespace App\Http\Controllers;

use App\EmpProfile;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\EmpAttendance;
use Illuminate\Support\Facades\DB;
class EmpAttendanceController extends Controller
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
            'name' => 'required|unique:emp_attendance',
        ]);
    }

    private function new_rec_create(array $recs){
        $id = Auth::id();
        $user = User::find($id);

    }

    private function rec_update(array $recs)
    {
        $uid = Auth::id();
        $user = User::find($uid);

        foreach ($recs as $rec) {
            //Update data in DB
            $record = EmpAttendance::find($rec['id']);
            $record->attendance_day = $rec['attendance_day'];
            $record->attendance_year = $rec['attendance_year'];
            if($rec['attendance_month']) {
                $record->attendance_month = $rec['attendance_month'];
            }
            $record->login_date = $rec['login_date'];
            $record->logout_date = ($rec['logout_date'] ? $rec['logout_date'] : null);
            $record->working_minutes = ($rec['working_minutes'] ? $rec['working_minutes'] : null);
            if($rec['login_attendance'] != 'select') {
                $record->login_attendance = $rec['login_attendance'];
            }
            if($rec['attendance_status']) {
                $record->attendance_status = $rec['attendance_status'];
            }
            //$record->modified_by = $user->admin;
            //$record->modified_date = $this->now();
            $record->save();
        }
    }

    private function rec_delete(array $recs){
        foreach ($recs as $rec) {
            $record = EmpAttendance::find($rec['id']);
            if($record) {
                $record->delete();
            }
        }
    }

    public function index(){
        $empAttendances = EmpAttendance::getByPaginate(8);
        //dd($empAttendances);
        $pageSetting = ["page" => "1", "recs" => 8, "sort_type" => "asc", "sort_by" => "id", "query" => ""];
        return view('admin.empAttendance', compact('empAttendances'))->with('pageSetting', $pageSetting);
    }

    public function fetch_data(Request $request)
    {
        $page = $request->input('fd_page');
        $recs = $request->input('fd_recs');
        $sort_by = $request->input('fd_sort_by');
        $sort_type = $request->input('fd_sort_type');
        $query = $request->input('fd_query');
        $query = Str::replaceArray(" ", ["%"], $query);

        $empAttendances = EmpAttendance::getByConditionalPaginate($recs, $sort_by, $sort_type, $query);
        //dd($empAttendances);
        $pageSetting = ["page" => $page, "recs" => $recs, "sort_type" => $sort_type, "sort_by" => $sort_by, "query" => $query];
        return view('admin.empAttendance', compact('empAttendances'))->with('pageSetting', $pageSetting);
    }

    public function processJSON(Request $request)
    {
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

        $empAttendances = EmpAttendance::getByConditionalPaginate($recs, $sort_by, $sort_type, $query);
        //dd($empAttendances);
        $pageSetting = ["page" => $page, "recs" => $recs, "sort_type" => $sort_type, "sort_by" => $sort_by, "query" => $query];
        return view('admin.empAttendance', compact('empAttendances'))->with('pageSetting', $pageSetting);
    }

    public function loginAttendance(){
        $c_status = '';
        $status_msg = '';
        $uid = Auth::id();
        $user = User::find($uid);
        $login = $this->now();
        $strLogIn = $login->format('Y-m-d H:i:s');

        DB::statement('call attendance_sheet_login(?,?,?,?)', [$user->profile_id, $strLogIn, @c_status, @status_msg]);
        //dd(@c_status);
        return view('home', compact('strLogIn'));
    }

    public function logoutAttendance(){
        $c_status = '';
        $status_msg = '';
        $uid = Auth::id();
        $user = User::find($uid);
        $logout = $this->now();
        $strLogOut = $logout->format('Y-m-d H:i:s');

        DB::statement('call attendance_sheet_logout(?,?,?,?)', [$user->profile_id, $strLogOut, @c_status, @status_msg]);
        //dd($status_msg);
        return view('home', compact('strLogOut'));
    }

    public function attendance_calculation(){
        $uid = Auth::id();
        $empAttendances = EmpAttendance::getAttendance($uid);
        //dd($empAttendances);
        $pageSetting = ["page" => "1", "recs" => 8, "sort_type" => "asc", "sort_by" => "id", "query" => "", "user" => $uid];
        return view('admin.indivAttendance', compact('empAttendances'))->with('pageSetting', $pageSetting);
    }

    public function attendance_calculation_cond(Request $request){
        $uid = Auth::id();

        $page = $request->input('fd_page');
        $recs = $request->input('fd_recs');
        $sort_by = $request->input('fd_sort_by');
        $sort_type = $request->input('fd_sort_type');
        $query = $request->input('fd_query');
        $query = Str::replaceArray(" ", ["%"], $query);

        $empAttendances = EmpAttendance::getAttendance_cond($uid, $recs, $sort_by, $sort_type, $query);
        //dd($empAttendances);
        $pageSetting = ["page" => $page, "recs" => $recs, "sort_type" => $sort_type, "sort_by" => $sort_by, "query" => $query, "user" => $uid];
        return view('admin.indivAttendance', compact('empAttendances'))->with('pageSetting', $pageSetting);
    }
}
