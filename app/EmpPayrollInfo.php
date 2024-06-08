<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;

class EmpPayrollInfo extends Model
{
    protected $table = 'emp_payroll_info';
    protected $primaryKey = 'id';
    public $incrementing = true;
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'modified_date';

    public static function getByPaginate($recs){
        return DB::table('emp_payroll_info')->select('emp_profile.emp_display_id as emp_display_id',
            'ctc', 'hike_amt', 'indiv_hike_amt', 'loan_taken_amt', 'loan_deducted_amt')
            ->leftjoin('emp_profile', 'emp_payroll_info.id', '=', 'emp_profile.profile_id')
            ->orderBy('emp_profile.emp_display_id', 'asc')->paginate($recs);
    }

    public static function getByConditionalPaginate($recs, $sort_by, $sort_type, $query){
        if($query) {
            $empPayrollInfo = DB::table('emp_payroll_info')
                ->select('emp_profile.emp_display_id as emp_display_id', 'ctc', 'hike_amt',
                    'indiv_hike_amt', 'loan_taken_amt', 'loan_deducted_amt')
                ->leftjoin('emp_profile', 'emp_payroll_info.id', '=', 'emp_profile.profile_id')
                ->where('emp_profile.emp_display_id', 'like', '%' . $query . '%')
                ->orWhere('ctc', 'like', '%' . $query . '%')
                ->orWhere('hike_amt', 'like', '%' . $query . '%')
                ->orWhere('indiv_hike_amt', 'like', '%' . $query . '%')
                ->orWhere('loan_taken_amt', 'like', '%' . $query . '%')
                ->orWhere('loan_deducted_amt', 'like', '%' . $query . '%')
                ->orderBy($sort_by, $sort_type)
                ->paginate($recs);
        }
        else{
            $empPayrollInfo = DB::table('emp_payroll_info')
                ->select('emp_profile.emp_display_id as emp_display_id', 'ctc', 'hike_amt',
                    'indiv_hike_amt', 'loan_taken_amt', 'loan_deducted_amt')
                ->leftjoin('emp_profile', 'emp_payroll_info.id', '=', 'emp_profile.profile_id')
                ->orderBy($sort_by, $sort_type)
                ->paginate($recs);
        }
        return $empPayrollInfo;
    }

    public static function pluck(array $columns){
        $result = null;

        if(count($columns)){
            $result = DB::table('emp_payroll_info')->pluck($columns[0]);
        }
        return $result;
    }

    public static function getPayrollData($emp_id){
        return DB::table('emp_payroll_info')
            ->select('emp_profile.emp_display_id as emp_display_id', 'ctc', 'hike_amt',
                'indiv_hike_amt', 'loan_taken_amt', 'loan_deducted_amt')
            ->leftjoin('emp_profile', 'emp_payroll_info.id', '=', 'emp_profile.profile_id')
            ->where('emp_profile.emp_display_id', '=', $emp_id)
            ->get();
    }
}
