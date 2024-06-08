<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;

class EmpAttendance extends Model
{
    protected $table = 'emp_attendance';
    protected $primaryKey = 'id';
    public $incrementing = true;
    const CREATED_AT = 'created_date';
    const UPDATED_AT = null;

    public static function getByPaginate($recs){
        return DB::table('emp_attendance')->select('emp_attendance.id as id', 'emp_profile.emp_display_id as emp_display_id',
            'attendance_day', 'attendance_year', 'attendance_month', 'login_date', 'logout_date', 'working_minutes',
            'shift.name as shift_name', 'login_attendance', 'attendance_status')
            ->leftJoin('emp_profile', 'emp_attendance.emp_id', '=', 'emp_profile.profile_id')
            ->leftJoin('shift', 'emp_attendance.shift_id', '=', 'shift.id')
            ->orderBy('id', 'asc')->paginate($recs);
    }

    public static function getByConditionalPaginate($recs, $sort_by, $sort_type, $query){
        if($query) {
            $empLeaves = DB::table('emp_attendance')
                ->select('emp_attendance.id as id', 'emp_profile.emp_display_id as emp_display_id',
                    'attendance_day', 'attendance_year', 'attendance_month', 'login_date', 'logout_date',
                    'working_minutes', 'shift.name as shift_name', 'login_attendance', 'attendance_status')
                ->leftJoin('emp_profile', 'emp_attendance.emp_id', '=', 'emp_profile.profile_id')
                ->leftJoin('shift', 'emp_attendance.shift_id', '=', 'shift.id')
                ->where('emp_attendance.id', 'like', '%' . $query . '%')
                ->orWhere('emp_profile.emp_display_id', 'like', '%' . $query . '%')
                ->orWhere('attendance_day', 'like', '%' . $query . '%')
                ->orWhere('attendance_year', 'like', '%' . $query . '%')
                ->orWhere('attendance_month', 'like', '%' . $query . '%')
                ->orWhere('login_date', 'like', '%' . $query . '%')
                ->orWhere('logout_date', 'like', '%' . $query . '%')
                ->orWhere('working_minutes', 'like', '%' . $query . '%')
                ->orWhere('shift.name', 'like', '%' . $query . '%')
                ->orWhere('login_attendance', 'like', '%' . $query . '%')
                ->orWhere('attendance_status', 'like', '%' . $query . '%')
                ->orderBy($sort_by, $sort_type)
                ->paginate($recs);
        }
        else{
            $empLeaves = DB::table('emp_attendance')
                ->select('emp_attendance.id as id', 'emp_profile.emp_display_id as emp_display_id',
                    'attendance_day', 'attendance_year', 'attendance_month', 'login_date', 'logout_date',
                    'working_minutes', 'shift.name as shift_name', 'login_attendance', 'attendance_status')
                ->leftJoin('emp_profile', 'emp_attendance.emp_id', '=', 'emp_profile.profile_id')
                ->leftJoin('shift', 'emp_attendance.shift_id', '=', 'shift.id')
                ->orderBy($sort_by, $sort_type)
                ->paginate($recs);
        }
        return $empLeaves;
    }

    public static function pluck(array $columns){
        $result = null;

        if(count($columns)){
            $result = DB::table('emp_attendance')->pluck($columns[0]);
        }
        return $result;
    }

    public static function getAttendance($emp_id){
        return DB::table('emp_attendance')->select('emp_attendance.id as id', 'emp_attendance.emp_id as emp_id',
            'emp_profile.emp_display_id as emp_display_id', 'attendance_day', 'attendance_year', 'attendance_month',
            'login_date', 'logout_date', 'working_minutes', 'shift.name as shift_name', 'login_attendance', 'attendance_status')
            ->leftJoin('emp_profile', 'emp_attendance.emp_id', '=', 'emp_profile.profile_id')
            ->leftJoin('shift', 'emp_attendance.shift_id', '=', 'shift.id')
            ->where('emp_attendance.emp_id', $emp_id)
            ->orderBy('emp_attendance.id', 'asc')
            ->paginate(8);
    }

    public static function getAttendance_cond($emp_id, $recs, $sort_by, $sort_type, $query){
        if($query) {
            return DB::table('emp_attendance')
                ->select('emp_attendance.id as id', 'emp_attendance.emp_id as emp_id', 'emp_profile.emp_display_id as emp_display_id',
                    'attendance_day', 'attendance_year', 'attendance_month', 'login_date', 'logout_date', 'working_minutes',
                    'shift.name as shift_name', 'login_attendance', 'attendance_status')
                ->leftJoin('emp_profile', 'emp_attendance.emp_id', '=', 'emp_profile.profile_id')
                ->leftJoin('shift', 'emp_attendance.shift_id', '=', 'shift.id')
                ->where('emp_attendance.emp_id', '=', $emp_id)
                ->where(function($param) use ($query){
                    $param->where('emp_attendance.id', 'like', '%' . $query . '%')
                        ->orWhere('emp_profile.emp_display_id', 'like', '%' . $query . '%')
                        ->orWhere('attendance_day', 'like', '%' . $query . '%')
                        ->orWhere('attendance_year', 'like', '%' . $query . '%')
                        ->orWhere('attendance_month', 'like', '%' . $query . '%')
                        ->orWhere('login_date', 'like', '%' . $query . '%')
                        ->orWhere('logout_date', 'like', '%' . $query . '%')
                        ->orWhere('working_minutes', 'like', '%' . $query . '%')
                        ->orWhere('shift.name', 'like', '%' . $query . '%')
                        ->orWhere('login_attendance', 'like', '%' . $query . '%')
                        ->orWhere('attendance_status', 'like', '%' . $query . '%');
                })
                ->orderBy($sort_by, $sort_type)
                ->paginate($recs);
        }
        else{
            return DB::table('emp_attendance')
                ->select('emp_attendance.id as id', 'emp_attendance.emp_id as emp_id', 'emp_profile.emp_display_id as emp_display_id',
                    'attendance_day', 'attendance_year', 'attendance_month', 'login_date', 'logout_date', 'working_minutes',
                    'shift.name as shift_name', 'login_attendance', 'attendance_status')
                ->leftJoin('emp_profile', 'emp_attendance.emp_id', '=', 'emp_profile.profile_id')
                ->leftJoin('shift', 'emp_attendance.shift_id', '=', 'shift.id')
                ->where('emp_attendance.emp_id', '=', $emp_id)
                ->orderBy($sort_by, $sort_type)
                ->paginate($recs);
        }
    }

    public static function getByPaySlipPaginate($emp_id, $year, $month){
        if($emp_id) {
            return DB::table('emp_attendance')
                ->select('emp_attendance.emp_id as emp_id', 'emp_profile.emp_display_id as emp_display_id',
                    'attendance_year', 'attendance_month', 'login_attendance', 'attendance_status')
                ->leftJoin('emp_profile', 'emp_attendance.emp_id', '=', 'emp_profile.profile_id')
                ->where('emp_profile.emp_display_id', '=', $emp_id)
                ->where('attendance_year', '=', $year)
                ->where('attendance_month', '=', $month)
                ->get();
        }
        else{
            return DB::table('emp_attendance')
                ->select('emp_attendance.emp_id as emp_id', 'emp_profile.emp_display_id as emp_display_id',
                    'attendance_year', 'attendance_month', 'login_attendance', 'attendance_status')
                ->leftJoin('emp_profile', 'emp_attendance.emp_id', '=', 'emp_profile.profile_id')
                ->where('attendance_year', '=', $year)
                ->where('attendance_month', '=', $month)
                ->get();
        }
    }

    public static function getLogData(){
        return DB::table('emp_attendance')->select(max(['login_date']), max(['logout_date']))
            ->leftJoin('emp_profile', 'emp_attendance.emp_id', '=', 'emp_profile.profile_id')
            ->get()->groupBy('emp_profile.emp_display_id');
    }
}
