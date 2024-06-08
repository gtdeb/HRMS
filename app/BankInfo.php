<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
class BankInfo extends Model
{
    protected $table = 'bank_info';
    protected $primaryKey = 'id';
    public $incrementing = true;
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'modified_date';

    public static function getByPaginate($recs){
        return DB::table('bank_info')->select('id', 'name', 'ifsc', 'address', 'in_use')
            ->orderBy('id', 'asc')->paginate($recs);
    }

    public static function getByConditionalPaginate($recs, $sort_by, $sort_type, $query){
        if($query) {
            $shiftCategories = DB::table('bank_info')
                ->select('id', 'name', 'ifsc', 'address', 'in_use')
                ->where('id', 'like', '%' . $query . '%')
                ->orWhere('name', 'like', '%' . $query . '%')
                ->orWhere('ifsc', 'like', '%' . $query . '%')
                ->orWhere('address', 'like', '%' . $query . '%')
                ->orderBy($sort_by, $sort_type)
                ->paginate($recs);
        }
        else{
            $shiftCategories = DB::table('bank_info')
                ->select('id', 'name', 'ifsc', 'address', 'in_use')
                ->orderBy($sort_by, $sort_type)
                ->paginate($recs);
        }
        return $shiftCategories;
    }

    public static function pluck(array $columns){
        $result = null;

        if(count($columns)){
            $result = DB::table('bank_info')->distinct()->pluck($columns[0]);
        }
        return $result;
    }

    public static function composite_pluck(array $columns){
        $result = [];
        if(count($columns)){
            $bankNames = BankInfo::pluck(['name']);
            foreach($bankNames as $key => $value){
                $result[$value] = DB::table('bank_info')->where('name', $value)->distinct()->pluck($columns[0]);
            }
        }
        return $result;
    }
}
