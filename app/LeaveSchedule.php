<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;

class LeaveSchedule extends Model
{
    protected $table = 'leave_schedule';
    protected $primaryKey = 'id';
    public $incrementing = true;
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'modified_date';

    public static function getByPaginate($recs){
        return DB::table('leave_schedule')->select('leave_schedule.id as id', 'designation_id',
                'emp_designation.name as designation', 'leave_type_id',
                'leave_types.name as leave_type', 'day_count', 'leave_schedule.in_use as in_use')
            ->leftJoin('emp_designation', 'leave_schedule.designation_id', '=', 'emp_designation.id')
            //->whereNull('leave_schedule.designation_id')
            ->join('leave_types', 'leave_schedule.leave_type_id', '=', 'leave_types.id')
            ->orderBy('leave_schedule.id', 'asc')->paginate($recs);
    }

    public static function getByConditionalPaginate($recs, $sort_by, $sort_type, $query){
        if($query) {
            $shiftCategories = DB::table('leave_schedule')
                ->select('leave_schedule.id as id', 'designation_id', 'emp_designation.name as designation',
                    'leave_type_id', 'leave_types.name as leave_type', 'day_count', 'leave_schedule.in_use as in_use')
                ->leftJoin('emp_designation', 'leave_schedule.designation_id', '=', 'emp_designation.id')
                //->whereNull('leave_schedule.designation_id')
                ->join('leave_types', 'leave_schedule.leave_type_id', '=', 'leave_types.id')
                ->where('leave_schedule.id', 'like', '%' . $query . '%')
                ->orWhere('emp_designation.name', 'like', '%' . $query . '%')
                ->orWhere('leave_types.name', 'like', '%' . $query . '%')
                ->orWhere('day_count', 'like', '%' . $query . '%')
                ->orderBy($sort_by, $sort_type)
                ->paginate($recs);
        }
        else{
            $shiftCategories = DB::table('leave_schedule')
                ->select('leave_schedule.id as id', 'designation_id', 'emp_designation.name as designation',
                    'leave_type_id', 'leave_types.name as leave_type', 'day_count', 'leave_schedule.in_use as in_use')
                ->leftJoin('emp_designation', 'leave_schedule.designation_id', '=', 'emp_designation.id')
                //->whereNull('leave_schedule.designation_id')
                ->join('leave_types', 'leave_schedule.leave_type_id', '=', 'leave_types.id')
                ->orderBy($sort_by, $sort_type)
                ->paginate($recs);
        }
        return $shiftCategories;
    }

    public static function pluck(array $columns){
        $result = null;

        if(count($columns)){
            $result = DB::table('leave_schedule')->pluck($columns[0]);
        }
        return $result;
    }
}
