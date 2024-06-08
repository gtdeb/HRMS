<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;

class AccessControlList extends Model
{
    protected $table = 'access_control_list';
    protected $primaryKey = 'id';
    public $incrementing = true;
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'modified_date';

    public static function getByPaginate($recs){
        return DB::table('access_control_list')->select('id', 'access_level', 'status', 'in_use')
            ->orderBy('id', 'asc')->paginate($recs);
    }

    public static function getByConditionalPaginate($recs, $sort_by, $sort_type, $query){
        if($query) {
            $AccessControlList = DB::table('access_control_list')
                ->select('id', 'access_level', 'status', 'in_use')
                ->where('id', 'like', '%' . $query . '%')
                ->orWhere('access_level', 'like', '%' . $query . '%')
                ->orWhere('status', 'like', '%' . $query . '%')
                ->orderBy($sort_by, $sort_type)
                ->paginate($recs);
        }
        else{
            $AccessControlList = DB::table('access_control_list')
                ->select('id', 'access_level', 'status', 'in_use')
                ->orderBy($sort_by, $sort_type)
                ->paginate($recs);
        }
        return $AccessControlList;
    }

    public static function pluck(array $columns){
        $result = null;

        if(count($columns)){
            $result = DB::table('access_control_list')->pluck($columns[0]);
        }
        return $result;
    }
}
