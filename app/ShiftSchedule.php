<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;

class ShiftSchedule extends Model
{
    protected $table = 'shift';
    protected $primaryKey = 'id';
    public $incrementing = true;
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'modified_date';

    public static function getByPaginate($recs){
        return DB::table('shift')->select('shift.id as id', 'shift.name as name', 'start_time', 'end_time', 'type_id',
            'shift_types.name as shift_type', 'half_day_dur_minutes','delay_time_minutes','login_punch_dur_mins',
            'logout_punch_dur_mins','ot_dur_mins','shift.in_use as in_use')
            ->join('shift_types', 'shift.type_id', '=', 'shift_types.id')
            ->orderBy('shift.id', 'asc')->paginate($recs);
    }

    public static function getByConditionalPaginate($recs, $sort_by, $sort_type, $query){
        if($query) {
            $shiftSchedules = DB::table('shift')->select('shift.id as id', 'shift.name as name', 'start_time', 'end_time', 'type_id',
                'shift_types.name as shift_type', 'half_day_dur_minutes','delay_time_minutes','login_punch_dur_mins',
                'logout_punch_dur_mins','ot_dur_mins','shift.in_use as in_use')
                ->join('shift_types', 'shift.type_id', '=', 'shift_types.id')
                ->where('shift.id', 'like', '%' . $query . '%')
                ->orWhere('shift.name', 'like', '%' . $query . '%')
                ->orWhere('start_time', 'like', '%' . $query . '%')
                ->orWhere('end_time', 'like', '%' . $query . '%')
                ->orWhere('shift_types.name', 'like', '%' . $query . '%')
                ->orWhere('half_day_dur_minutes', 'like', '%' . $query . '%')
                ->orWhere('delay_time_minutes', 'like', '%' . $query . '%')
                ->orWhere('login_punch_dur_mins', 'like', '%' . $query . '%')
                ->orWhere('logout_punch_dur_mins', 'like', '%' . $query . '%')
                ->orWhere('ot_dur_mins', 'like', '%' . $query . '%')
                ->orderBy($sort_by, $sort_type)->paginate($recs);
        }
        else{
            $shiftSchedules = DB::table('shift')->select('shift.id as id', 'shift.name as name', 'start_time', 'end_time', 'type_id',
                'shift_types.name as shift_type', 'half_day_dur_minutes','delay_time_minutes','login_punch_dur_mins',
                'logout_punch_dur_mins','ot_dur_mins','shift.in_use as in_use')
                ->join('shift_types', 'shift.type_id', '=', 'shift_types.id')
                ->orderBy($sort_by, $sort_type)->paginate($recs);
        }
        return $shiftSchedules;
    }
}
