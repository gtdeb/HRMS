<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;

class EmpProfile extends Model
{
    protected $table = 'emp_profile';
    protected $primaryKey = 'profile_id';
    public $incrementing = true;
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'modified_date';

    public static function getByPaginate($recs){
        return DB::table('emp_profile')->select('profile_id', 'access_level_id',
            'access_control_list.access_level as access_level', 'emp_display_id', 'department.name as dept_name',
            'designation_id', 'emp_designation.name as designation', 'join_date', 'leave_date',
            'last_login', 'last_logout', 'emp_profile.status as status')
            ->leftjoin('access_control_list', 'emp_profile.access_level_id', '=', 'access_control_list.id')
            ->leftJoin('emp_designation', 'emp_profile.designation_id', '=', 'emp_designation.id')
            ->leftJoin('department', 'emp_profile.department_id', '=', 'department.id')
            ->orderBy('emp_display_id', 'asc')->paginate($recs);
    }

    public static function getByConditionalPaginate($tab, $recs, $sort_by, $sort_type, $query){
        $empProfiles = null;

        if($tab == 'professional'){
            if($query) {
                $empProfiles = DB::table('emp_profile')->select('profile_id', 'access_level_id',
                    'access_control_list.access_level as access_level', 'emp_display_id', 'department.name as dept_name',
                    'designation_id', 'emp_designation.name as designation', 'join_date', 'leave_date',
                    'last_login', 'last_logout', 'emp_profile.status as status')
                    ->leftjoin('access_control_list', 'emp_profile.access_level_id', '=', 'access_control_list.id')
                    ->leftJoin('emp_designation', 'emp_profile.designation_id', '=', 'emp_designation.id')
                    ->leftJoin('department', 'emp_profile.department_id', '=', 'department.id')
                    ->where('profile_id', 'like', '%' . $query . '%')
                    ->orWhere('access_control_list.access_level', 'like', '%' . $query . '%')
                    ->orWhere('emp_display_id', 'like', '%' . $query . '%')
                    ->orWhere('department.name', 'like', '%' . $query . '%')
                    ->orWhere('emp_designation.name', 'like', '%' . $query . '%')
                    ->orWhere('join_date', 'like', '%' . $query . '%')
                    ->orWhere('leave_date', 'like', '%' . $query . '%')
                    ->orWhere('last_login', 'like', '%' . $query . '%')
                    ->orWhere('last_logout', 'like', '%' . $query . '%')
                    ->orWhere('emp_profile.status', 'like', '%' . $query . '%')
                    ->orderBy($sort_by, $sort_type)->paginate($recs);
            }
            else{
                $empProfiles = DB::table('emp_profile')->select('profile_id', 'access_level_id',
                    'access_control_list.access_level as access_level', 'emp_display_id', 'department.name as dept_name',
                    'designation_id', 'emp_designation.name as designation', 'join_date', 'leave_date',
                    'last_login', 'last_logout', 'emp_profile.status as status')
                    ->leftjoin('access_control_list', 'emp_profile.access_level_id', '=', 'access_control_list.id')
                    ->leftJoin('emp_designation', 'emp_profile.designation_id', '=', 'emp_designation.id')
                    ->leftJoin('department', 'emp_profile.department_id', '=', 'department.id')
                    ->orderBy($sort_by, $sort_type)->paginate($recs);
            }
        }
        else if($tab == 'kyc_qualification'){
            if($query) {
                $empProfiles = DB::table('emp_profile')->select('profile_id', 'emp_display_id', 'qualification_id',
                    'emp_qualification.name as qualification', 'qualification_other', 'qualification_docs',
                    'panNumber', 'pan_copy', 'voterId', 'voter_copy', 'aadharNumber', 'aadhar_copy')
                    ->leftjoin('emp_qualification', 'emp_profile.qualification_id', '=', 'emp_qualification.id')
                    ->where('emp_display_id', 'like', '%' . $query . '%')
                    ->orWhere('emp_qualification.name', 'like', '%' . $query . '%')
                    ->orWhere('qualification_other', 'like', '%' . $query . '%')
                    ->orWhere('qualification_docs', 'like', '%' . $query . '%')
                    ->orWhere('panNumber', 'like', '%' . $query . '%')
                    ->orWhere('pan_copy', 'like', '%' . $query . '%')
                    ->orWhere('voterId', 'like', '%' . $query . '%')
                    ->orWhere('voter_copy', 'like', '%' . $query . '%')
                    ->orWhere('aadharNumber', 'like', '%' . $query . '%')
                    ->orWhere('aadhar_copy', 'like', '%' . $query . '%')
                    ->orderBy($sort_by, $sort_type)->paginate($recs);
            }
            else{
                $empProfiles = DB::table('emp_profile')->select('profile_id', 'emp_display_id', 'qualification_id',
                    'emp_qualification.name as qualification', 'qualification_other', 'qualification_docs',
                    'panNumber', 'pan_copy', 'voterId', 'voter_copy', 'aadharNumber', 'aadhar_copy')
                    ->leftjoin('emp_qualification', 'emp_profile.qualification_id', '=', 'emp_qualification.id')
                    ->orderBy($sort_by, $sort_type)->paginate($recs);
            }
        }
        else if($tab == 'personal'){
            if($query) {
                $empProfiles = DB::table('emp_profile')->select('profile_id', 'emp_display_id', 'name', 'DOB', 'gender',
                    'mobile', 'mobile_verified', 'email', 'email_verified', 'marital_status', 'profile_image')
                    ->where('emp_display_id', 'like', '%' . $query . '%')
                    ->orWhere('name', 'like', '%' . $query . '%')
                    ->orWhere('DOB', 'like', '%' . $query . '%')
                    ->orWhere('gender', 'like', '%' . $query . '%')
                    ->orWhere('mobile', 'like', '%' . $query . '%')
                    ->orWhere('mobile_verified', 'like', '%' . $query . '%')
                    ->orWhere('email', 'like', '%' . $query . '%')
                    ->orWhere('email_verified', 'like', '%' . $query . '%')
                    ->orWhere('marital_status', 'like', '%' . $query . '%')
                    ->orWhere('profile_image', 'like', '%' . $query . '%')
                    ->orderBy($sort_by, $sort_type)->paginate($recs);
            }
            else{
                $empProfiles = DB::table('emp_profile')->select('profile_id', 'emp_display_id', 'name', 'DOB', 'gender',
                    'mobile', 'mobile_verified', 'email', 'email_verified', 'marital_status', 'profile_image')
                    ->orderBy($sort_by, $sort_type)->paginate($recs);
            }
        }
        else if($tab == 'address'){
            if($query) {
                $empProfiles = DB::table('emp_profile')->select('profile_id', 'emp_display_id', 'address_current',
                    'address_permanent', 'gurdian_name', 'gurdian_contact', 'emergency_name', 'emergency_contact',
                    'emergency_address')
                    ->where('emp_display_id', 'like', '%' . $query . '%')
                    ->orWhere('address_current', 'like', '%' . $query . '%')
                    ->orWhere('address_permanent', 'like', '%' . $query . '%')
                    ->orWhere('gurdian_name', 'like', '%' . $query . '%')
                    ->orWhere('gurdian_contact', 'like', '%' . $query . '%')
                    ->orWhere('emergency_name', 'like', '%' . $query . '%')
                    ->orWhere('emergency_address', 'like', '%' . $query . '%')
                    ->orderBy($sort_by, $sort_type)->paginate($recs);
            }
            else{
                $empProfiles = DB::table('emp_profile')->select('profile_id', 'emp_display_id', 'address_current',
                    'address_permanent', 'gurdian_name', 'gurdian_contact', 'emergency_name', 'emergency_contact',
                    'emergency_address')
                    ->orderBy($sort_by, $sort_type)->paginate($recs);
            }
        }
        else if($tab == 'payroll'){
            if($query) {
                $empProfiles = DB::table('emp_profile')->select('profile_id', 'emp_display_id',
                    'bank_info_id', 'bank_info.name as bank_name', 'bank_info.ifsc as bank_ifsc',
                    'bank_account_no', 'cancel_cheque', 'pf_account_no')
                    ->leftjoin('bank_info', 'emp_profile.bank_info_id', '=', 'bank_info.id')
                    ->where('emp_display_id', 'like', '%' . $query . '%')
                    ->orWhere('bank_info.name', 'like', '%' . $query . '%')
                    ->orWhere('bank_info.ifsc', 'like', '%' . $query . '%')
                    ->orWhere('bank_account_no', 'like', '%' . $query . '%')
                    ->orWhere('cancel_cheque', 'like', '%' . $query . '%')
                    ->orWhere('pf_account_no', 'like', '%' . $query . '%')
                    ->orderBy($sort_by, $sort_type)->paginate($recs);
            }
            else{
                $empProfiles = DB::table('emp_profile')->select('profile_id', 'emp_display_id', 'bank_info_id',
                    'bank_info.name as bank_name', 'bank_info.ifsc as bank_ifsc', 'bank_account_no',
                    'cancel_cheque', 'pf_account_no')
                    ->leftjoin('bank_info', 'emp_profile.bank_info_id', '=', 'bank_info.id')
                    ->orderBy($sort_by, $sort_type)->paginate($recs);
            }
        }
        return $empProfiles;
    }

    public static function getByEmpDisplayId($emp_display_id){
        return DB::table('emp_profile')->select('profile_id', 'access_level_id', 'access_control_list.access_level as access_level',
            'emp_display_id', 'department.name as dept_name', 'designation_id', 'emp_designation.name as designation', 'join_date', 'leave_date',
            'last_login', 'last_logout', 'emp_profile.status as status', 'qualification_id', 'emp_qualification.name as qualification',
            'qualification_other', 'qualification_docs', 'panNumber', 'pan_copy', 'voterId', 'voter_copy', 'aadharNumber', 'aadhar_copy',
            'emp_profile.name as name', 'DOB', 'gender', 'mobile', 'mobile_verified', 'email', 'email_verified', 'marital_status', 'profile_image',
            'address_current', 'address_permanent', 'gurdian_name', 'gurdian_contact', 'emergency_name', 'emergency_contact', 'emergency_address',
            'bank_info_id', 'bank_info.name as bank_name', 'bank_info.ifsc as bank_ifsc', 'bank_account_no', 'cancel_cheque', 'pf_account_no')
            ->leftjoin('access_control_list', 'emp_profile.access_level_id', '=', 'access_control_list.id')
            ->leftJoin('emp_designation', 'emp_profile.designation_id', '=', 'emp_designation.id')
            ->leftJoin('department', 'emp_profile.department_id', '=', 'department.id')
            ->leftjoin('emp_qualification', 'emp_profile.qualification_id', '=', 'emp_qualification.id')
            ->leftjoin('bank_info', 'emp_profile.bank_info_id', '=', 'bank_info.id')
            ->where('emp_display_id', '=', $emp_display_id)
            ->get();
    }
}
