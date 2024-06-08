<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;

class LeaveCategory extends Model
{
    protected $table = 'leave_types';
    protected $primaryKey = 'id';
    public $incrementing = true;
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'modified_date';

    public static function getByPaginate($recs){
        return DB::table('leave_types')->select('id', 'name', 'CF', 'in_use')->orderBy('id', 'asc')->paginate($recs);
    }

    public static function getByConditionalPaginate($recs, $sort_by, $sort_type, $query){
        if($query) {
            $shiftCategories = DB::table('leave_types')
                ->select('id', 'name', 'CF', 'in_use')
                ->where('id', 'like', '%' . $query . '%')
                ->orWhere('name', 'like', '%' . $query . '%')
                ->orWhere('CF', 'like', '%' . $query . '%')
                ->orderBy($sort_by, $sort_type)
                ->paginate($recs);
        }
        else{
            $shiftCategories = DB::table('leave_types')
                ->select('id', 'name', 'CF', 'in_use')
                ->orderBy($sort_by, $sort_type)
                ->paginate($recs);
        }
        return $shiftCategories;
    }

    public static function pluck(array $columns){
        $result = null;

        if(count($columns)){
            $result = DB::table('leave_types')->pluck($columns[0]);
        }
        return $result;
    }
}
