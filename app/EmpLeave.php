<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;

class EmpLeave extends Model
{
    protected $table = 'emp_leave';
    protected $primaryKey = 'id';
    public $incrementing = true;
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'modified_date';

    public static function getByPaginate($recs, $uid){
        if($uid){
            $result = DB::table('emp_leave')->select('emp_leave.id as id', 'emp_profile.emp_display_id as emp_display_id',
                'leave_types.name as leave_type', 'reason', 'start_date', 'end_date', 'leave_status', 'prev_leave_status')
                ->leftJoin('emp_profile', 'emp_leave.emp_id', '=', 'emp_profile.profile_id')
                ->leftJoin('leave_schedule', 'emp_leave.leave_class_id', '=', 'leave_schedule.id')
                ->leftJoin('leave_types', 'leave_schedule.leave_type_id', '=', 'leave_types.id')
                ->where('emp_profile.profile_id', '=', $uid)
                ->orderBy('emp_leave.id', 'asc')->paginate($recs);
        }
        else{
            $result = DB::table('emp_leave')->select('emp_leave.id as id', 'emp_profile.emp_display_id as emp_display_id',
                'leave_types.name as leave_type', 'reason', 'start_date', 'end_date', 'leave_status', 'prev_leave_status')
                ->leftJoin('emp_profile', 'emp_leave.emp_id', '=', 'emp_profile.profile_id')
                ->leftJoin('leave_schedule', 'emp_leave.leave_class_id', '=', 'leave_schedule.id')
                ->leftJoin('leave_types', 'leave_schedule.leave_type_id', '=', 'leave_types.id')
                ->orderBy('emp_leave.id', 'asc')->paginate($recs);
        }
        return $result;
    }

    public static function getByConditionalPaginate($recs, $sort_by, $sort_type, $query, $uid){
        if($query) {
            if($uid) {
                $empLeaves = DB::table('emp_leave')
                    ->select('emp_leave.id as id', 'emp_profile.emp_display_id as emp_display_id',
                        'leave_types.name as leave_type', 'reason', 'start_date', 'end_date',
                        'leave_status', 'prev_leave_status')
                    ->leftJoin('emp_profile', 'emp_leave.emp_id', '=', 'emp_profile.profile_id')
                    ->leftJoin('leave_schedule', 'emp_leave.leave_class_id', '=', 'leave_schedule.id')
                    ->leftJoin('leave_types', 'leave_schedule.leave_type_id', '=', 'leave_types.id')
                    ->where('emp_profile.profile_id', '=', $uid)
                    ->where(function($param) use ($query) {
                        $param->where('emp_leave.id', 'like', '%' . $query . '%')
                            ->orWhere('emp_profile.emp_display_id', 'like', '%' . $query . '%')
                            ->orWhere('leave_types.name', 'like', '%' . $query . '%')
                            ->orWhere('reason', 'like', '%' . $query . '%')
                            ->orWhere('start_date', 'like', '%' . $query . '%')
                            ->orWhere('end_date', 'like', '%' . $query . '%')
                            ->orWhere('leave_status', 'like', '%' . $query . '%');
                    })
                    ->orderBy($sort_by, $sort_type)
                    ->paginate($recs);
            }
            else{
                $empLeaves = DB::table('emp_leave')
                    ->select('emp_leave.id as id', 'emp_profile.emp_display_id as emp_display_id',
                        'leave_types.name as leave_type', 'reason', 'start_date', 'end_date',
                        'leave_status', 'prev_leave_status')
                    ->leftJoin('emp_profile', 'emp_leave.emp_id', '=', 'emp_profile.profile_id')
                    ->leftJoin('leave_schedule', 'emp_leave.leave_class_id', '=', 'leave_schedule.id')
                    ->leftJoin('leave_types', 'leave_schedule.leave_type_id', '=', 'leave_types.id')
                    ->where('emp_leave.id', 'like', '%' . $query . '%')
                    ->orWhere('emp_profile.emp_display_id', 'like', '%' . $query . '%')
                    ->orWhere('leave_types.name', 'like', '%' . $query . '%')
                    ->orWhere('reason', 'like', '%' . $query . '%')
                    ->orWhere('start_date', 'like', '%' . $query . '%')
                    ->orWhere('end_date', 'like', '%' . $query . '%')
                    ->orWhere('leave_status', 'like', '%' . $query . '%')
                    ->orderBy($sort_by, $sort_type)
                    ->paginate($recs);
            }
        }
        else{
            if($uid) {
                $empLeaves = DB::table('emp_leave')
                    ->select('emp_leave.id as id', 'emp_profile.emp_display_id as emp_display_id',
                        'leave_types.name as leave_type', 'reason', 'start_date', 'end_date',
                        'leave_status', 'prev_leave_status')
                    ->leftJoin('emp_profile', 'emp_leave.emp_id', '=', 'emp_profile.profile_id')
                    ->leftJoin('leave_schedule', 'emp_leave.leave_class_id', '=', 'leave_schedule.id')
                    ->leftJoin('leave_types', 'leave_schedule.leave_type_id', '=', 'leave_types.id')
                    ->where('emp_profile.profile_id', '=', $uid)
                    ->orderBy($sort_by, $sort_type)
                    ->paginate($recs);
            }
            else{
                $empLeaves = DB::table('emp_leave')
                    ->select('emp_leave.id as id', 'emp_profile.emp_display_id as emp_display_id',
                        'leave_types.name as leave_type', 'reason', 'start_date', 'end_date',
                        'leave_status', 'prev_leave_status')
                    ->leftJoin('emp_profile', 'emp_leave.emp_id', '=', 'emp_profile.profile_id')
                    ->leftJoin('leave_schedule', 'emp_leave.leave_class_id', '=', 'leave_schedule.id')
                    ->leftJoin('leave_types', 'leave_schedule.leave_type_id', '=', 'leave_types.id')
                    ->orderBy($sort_by, $sort_type)
                    ->paginate($recs);
            }
        }
        return $empLeaves;
    }

    public static function pluck(array $columns){
        $result = null;

        if(count($columns)){
            $result = DB::table('emp_leave')->pluck($columns[0]);
        }
        return $result;
    }

    public static function getByPaySlipPaginate($emp_id, $issue_date){
        if($emp_id) {
            return DB::table('emp_leave')
                ->select('emp_profile.emp_display_id as emp_display_id', 'start_date', 'end_date', 'leave_status')
                ->leftJoin('emp_profile', 'emp_leave.emp_id', '=', 'emp_profile.profile_id')
                ->where([
                    ['emp_profile.emp_display_id', '=', $emp_id],
                    ['end_date', '<', $issue_date],
                    ['leave_status', '=', 'Accepted'],
                ])
                ->get();
        }
        else{
            return DB::table('emp_attendance')
                ->select('emp_profile.emp_display_id as emp_display_id', 'start_date', 'end_date', 'leave_status')
                ->leftJoin('emp_profile', 'emp_leave.emp_id', '=', 'emp_profile.profile_id')
                ->where([
                    ['end_date', '<', $issue_date],
                    ['leave_status', '=', 'Accepted'],
                ])
                ->get();
        }
    }
}
