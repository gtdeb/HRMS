<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;

class EmpLeaveAllotment extends Model
{
    protected $table = 'empleaveallotment';
    protected $primaryKey = 'id';
    public $incrementing = true;
    const CREATED_AT = null;
    const UPDATED_AT = null;

    public static function getByPaginate($recs){
        return DB::table('empleaveallotment')->select('emp_profile.emp_display_id as emp_display_id',
            'CL', 'SL', 'EL', 'PL')
            ->leftJoin('emp_profile', 'empleaveallotment.id', '=', 'emp_profile.profile_id')
            ->orderBy('id', 'asc')->paginate($recs);
    }

    public static function getByConditionalPaginate($recs, $sort_by, $sort_type, $query){
        if($query) {
            $empLeaves = DB::table('empleaveallotment')
                ->select('emp_profile.emp_display_id as emp_display_id',
                    'CL', 'SL', 'EL', 'PL')
                ->leftJoin('emp_profile', 'empleaveallotment.id', '=', 'emp_profile.profile_id')
                ->Where('emp_profile.emp_display_id', 'like', '%' . $query . '%')
                ->orWhere('CL', 'like', '%' . $query . '%')
                ->orWhere('SL', 'like', '%' . $query . '%')
                ->orWhere('EL', 'like', '%' . $query . '%')
                ->orWhere('PL', 'like', '%' . $query . '%')
                ->orderBy($sort_by, $sort_type)
                ->paginate($recs);
        }
        else{
            $empLeaves = DB::table('empleaveallotment')
                ->select('emp_profile.emp_display_id as emp_display_id',
                    'CL', 'SL', 'EL', 'PL')
                ->leftJoin('emp_profile', 'empleaveallotment.id', '=', 'emp_profile.profile_id')
                ->orderBy($sort_by, $sort_type)
                ->paginate($recs);
        }
        return $empLeaves;
    }

    public static function pluck(array $columns){
        $result = null;

        if(count($columns)){
            $result = DB::table('empleaveallotment')->pluck($columns[0]);
        }
        return $result;
    }

    public static function getLeaveAllotment($emp_id){
        return DB::table('empleaveallotment')
            ->select('id', 'designation_id', 'CL', 'SL', 'EL', 'PL')
            ->where('empleaveallotment.id', '=', $emp_id)
            ->get();
    }
}
