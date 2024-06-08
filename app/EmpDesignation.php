<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;

class EmpDesignation extends Model
{
    protected $table = 'emp_designation';
    protected $primaryKey = 'id';
    public $incrementing = true;
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'modified_date';

    public static function getByPaginate($recs){
        return DB::table('emp_designation')->select('emp_designation.id as id', 'emp_designation.name as name',
            'emp_type_id', 'emp_types.name as emp_type', 'shift_type_id', 'shift_types.name as shift_type',
            'emp_designation.in_use as in_use')
            ->join('emp_types', 'emp_designation.emp_type_id', '=', 'emp_types.id')
            ->join('shift_types', 'emp_designation.shift_type_id', '=', 'shift_types.id')
            ->orderBy('emp_designation.id', 'asc')->paginate($recs);
    }

    public static function getByConditionalPaginate($recs, $sort_by, $sort_type, $query){
        if($query) {
            $shiftCategories = DB::table('emp_designation')
                ->select('emp_designation.id as id', 'emp_designation.name as name', 'emp_type_id',
                    'emp_types.name as emp_type', 'shift_type_id', 'shift_types.name as shift_type',
                    'emp_designation.in_use as in_use')
                ->join('emp_types', 'emp_designation.emp_type_id', '=', 'emp_types.id')
                ->join('shift_types', 'emp_designation.shift_type_id', '=', 'shift_types.id')
                ->where('emp_designation.id', 'like', '%' . $query . '%')
                ->orWhere('emp_designation.name', 'like', '%' . $query . '%')
                ->orWhere('emp_types.name', 'like', '%' . $query . '%')
                ->orWhere('shift_types.name', 'like', '%' . $query . '%')
                ->orderBy($sort_by, $sort_type)
                ->paginate($recs);
        }
        else{
            $shiftCategories = DB::table('emp_designation')
                ->select('emp_designation.id as id', 'emp_designation.name as name', 'emp_type_id',
                    'emp_types.name as emp_type', 'shift_type_id', 'shift_types.name as shift_type',
                    'emp_designation.in_use as in_use')
                ->join('emp_types', 'emp_designation.emp_type_id', '=', 'emp_types.id')
                ->join('shift_types', 'emp_designation.shift_type_id', '=', 'shift_types.id')
                ->orderBy($sort_by, $sort_type)
                ->paginate($recs);
        }
        return $shiftCategories;
    }

    public static function pluck(array $columns){
        $result = null;

        if(count($columns)){
            $result = DB::table('emp_designation')->pluck($columns[0]);
        }
        return $result;
    }
}
