<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;

class Department extends Model
{
    protected $table = 'department';
    protected $primaryKey = 'id';
    public $incrementing = true;
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'modified_date';

    public static function getByPaginate($recs){
        return DB::table('department')->select('id', 'name', 'description', 'in_use')
            ->orderBy('id', 'asc')->paginate($recs);
    }

    public static function getByConditionalPaginate($recs, $sort_by, $sort_type, $query){
        if($query) {
            $shiftCategories = DB::table('department')
                ->select('id', 'name', 'description', 'in_use')
                ->where('id', 'like', '%' . $query . '%')
                ->orWhere('name', 'like', '%' . $query . '%')
                ->orWhere('description', 'like', '%' . $query . '%')
                ->orderBy($sort_by, $sort_type)
                ->paginate($recs);
        }
        else{
            $shiftCategories = DB::table('department')
                ->select('id', 'name', 'description', 'in_use')
                ->orderBy($sort_by, $sort_type)
                ->paginate($recs);
        }
        return $shiftCategories;
    }

    public static function pluck(array $columns){
        $result = null;

        if(count($columns)){
            $result = DB::table('department')->pluck($columns[0]);
        }
        return $result;
    }
}
