<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;

class PayrollDeduction extends Model
{
    protected $table = 'payroll_deduct_info';
    protected $primaryKey = 'id';
    public $incrementing = true;
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'modified_date';

    public static function getByPaginate($recs){
        return DB::table('payroll_deduct_info')->select('id', 'designation', 'prof_tax', 'esi', 'pf', 'tds', 'in_use')
            ->orderBy('id', 'asc')->paginate($recs);
    }

    public static function getByConditionalPaginate($recs, $sort_by, $sort_type, $query){
        if($query) {
            $shiftCategories = DB::table('payroll_deduct_info')
                ->select('id', 'designation', 'prof_tax', 'esi', 'pf', 'tds', 'in_use')
                ->where('id', 'like', '%' . $query . '%')
                ->orWhere('designation', 'like', '%' . $query . '%')
                ->orWhere('prof_tax', 'like', '%' . $query . '%')
                ->orWhere('esi', 'like', '%' . $query . '%')
                ->orWhere('pf', 'like', '%' . $query . '%')
                ->orWhere('tds', 'like', '%' . $query . '%')
                ->orderBy($sort_by, $sort_type)
                ->paginate($recs);
        }
        else{
            $shiftCategories = DB::table('payroll_deduct_info')
                ->select('id', 'designation', 'prof_tax', 'esi', 'pf', 'tds', 'in_use')
                ->orderBy($sort_by, $sort_type)
                ->paginate($recs);
        }
        return $shiftCategories;
    }

    public static function pluck(array $columns){
        $result = null;

        if(count($columns)){
            $result = DB::table('payroll_deduct_info')->pluck($columns[0]);
        }
        return $result;
    }

    public static function getDeductionByDesignation($designation){
        return DB::table('payroll_deduct_info')
            ->select('id', 'designation', 'prof_tax', 'esi', 'pf', 'tds')
            ->where('designation', '=', $designation)
            ->get()->first();
    }
}
