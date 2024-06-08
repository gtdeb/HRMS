<?php

namespace App\Http\Controllers;

use App\BankInfo;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\EmpProfile;
use App\AccessControlList;
use App\Department;
use App\EmpDesignation;
use App\EmpQualification;
use App\EmpPayrollInfo;
use App\EmpLeaveAllotment;
use App\LeaveSchedule;
use App\LeaveCategory;
use App\EmpAttendance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
class EmpProfileController extends Controller
{
    private function now(){
        date_default_timezone_set('Indian/Chagos');
        $timezone = date_default_timezone_get();
        $now = date_create(date('Y-m-d H:i:s'));
        date_add($now,date_interval_create_from_date_string("-30 minutes"));
        return $now;
    }

    private function emailVerification($email, $name){
        $headers = '';
        $ms = '';

        $activationcode=md5($email.time());
        $to=$email;
        $msg= "Thanks for new Registration.";
        $subject="Email verification (hrms.com)";
        $headers .= "MIME-Version: 1.0"."\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
        $headers .= 'From:HRMS'."\r\n";
        $ms.="<html></body><div><div>Dear $name,</div></br></br>";
        $ms.="<div style='padding-top:8px;'>Please click The following link For verifying and activation of your account</div>
              <div style='padding-top:10px;'><a href='http://www.phpgurukul.com/demos/emailverify/email_verification.php?code=$activationcode'>
              Click Here</a></div>
              <div style='padding-top:4px;'>Powered by <a href='thedigitalexposure.com'>thedigitalexposure.com</a></div></div>
              </body></html>";

        //ini_set("SMTP","localhost");
        ini_set("smtp_port","2525");
        //ini_set("sendmail_from","mondaldebabrata.m@gmail.com");
        //ini_set("sendmail_path", "D:\xampp\sendmail\sendmail.exe -t");
        //mail($to,$subject,$ms,$headers);
        mail("stupid6moron9@gmail.com",$subject,$ms);
    }

    private function new_rec_create($tab, array $recs){
        $id = Auth::id();
        $user = User::find($id);

        foreach($recs as $rec){
            if($tab == 'professional') {
                //Check for duplicate entry
                if(EmpProfile::where('emp_display_id', $rec['emp_display_id'])->count() == 0) {
                    //Save data in DB
                    $record = new EmpProfile();
                    if($rec['access_control'] != 'Select') {
                        $access_level_id = AccessControlList::where('access_level', $rec['access_control'])->select('id')->get();
                        $record->access_level_id = $access_level_id[0]->{'id'};
                        //
                        $after = AccessControlList::find($access_level_id[0]->{'id'});
                        $after->in_use += 1;
                        $after->save();
                    }
                    else{
                        $record->access_level_id = null;
                    }
                    $record->emp_display_id = $rec['emp_display_id'];
                    if($rec['department'] != 'Select') {
                        $department_id = Department::where('name', $rec['department'])->select('id')->get();
                        $record->department_id = $department_id[0]->{'id'};
                        //
                        $after = Department::find($department_id[0]->{'id'});
                        $after->in_use += 1;
                        $after->save();
                    }
                    else{
                        $record->department_id = null;
                    }
                    if($rec['designation'] != 'Select') {
                        $designation_id = EmpDesignation::where('name', $rec['designation'])->select('id')->get();
                        $record->designation_id = $designation_id[0]->{'id'};
                        //
                        $after = EmpDesignation::find($designation_id[0]->{'id'});
                        $after->in_use += 1;
                        $after->save();
                    }
                    else{
                        $record->designation_id = null;
                    }
                    $record->join_date = ($rec['join_date'] ? $rec['join_date'] : null);
                    $record->leave_date = ($rec['leave_date'] ? $rec['leave_date'] : null);
                    $record->last_login = null;
                    $record->last_logout = null;
                    $record->created_by = $user->admin;
                    $record->created_date = $this->now();
                    $record->status = $rec['status'];
                    $retVal = $record->save();
                    if($retVal){
                        $record = EmpProfile::where('emp_display_id', $rec['emp_display_id'])->select('profile_id', 'designation_id')->get();
                        $record2 = new EmpPayrollInfo();
                        $record2->id = $record[0]->{'profile_id'};
                        $record2->created_by = $user->admin;
                        $record2->created_date = $this->now();
                        $record2->save();
                        //
                        $record3 = LeaveSchedule::where('designation_id', $record[0]->{'designation_id'})->select('leave_type_id', 'day_count')->get();
                        $record4 = new EmpLeaveAllotment();
                        $record4->id = $record[0]->{'profile_id'};
                        $record4->designation_id = $record[0]->{'designation_id'};
                        foreach($record3 as $rec){
                            $record5 = LeaveCategory::where('id', $rec->{'leave_type_id'})->select('name')->get();
                            if($record5[0]->{'name'} == 'CL'){
                                $record4->CL = $rec->{'day_count'};
                            }
                            else if($record5[0]->{'name'} == 'SL'){
                                $record4->SL = $rec->{'day_count'};
                            }
                            else if($record5[0]->{'name'} == 'EL'){
                                $record4->EL = $rec->{'day_count'};
                            }
                            else if($record5[0]->{'name'} == 'PL'){
                                $record4->PL = $rec->{'day_count'};
                            }
                        }
                        $record4->save();
                    }
                }
            }
            else if($tab == 'kyc_qualification') {
                //Check for duplicate entry
                if(EmpProfile::where('emp_display_id', $rec['emp_display_id'])->count() == 0) {
                    //Save data in DB
                    $record = new EmpProfile();
                    $record->emp_display_id = $rec['emp_display_id'];
                    if($rec['qualification'] != 'Select') {
                        $qualification_id = EmpQualification::where('name', $rec['qualification'])->select('id')->get();
                        $record->qualification_id = $qualification_id[0]->{'id'};
                        //
                        $after = EmpQualification::find($qualification_id[0]->{'id'});
                        $after->in_use += 1;
                        $after->save();
                    }
                    else{
                        $record->qualification_id = null;
                    }

                    $record->qualification_other = $rec['qualification_other'];
                    if($rec['qualification_docs']) {
                        $record->qualification_docs = $rec['qualification_docs'];
                    }
                    $record->panNumber = $rec['panNumber'];
                    if($rec['pan_copy']) {
                        $record->pan_copy = $rec['pan_copy'];
                    }
                    $record->voterId = $rec['voterId'];
                    if($rec['voter_copy']) {
                        $record->voter_copy = $rec['voter_copy'];
                    }
                    $record->aadharNumber = $rec['aadharNumber'];
                    if($rec['aadhar_copy']) {
                        $record->aadhar_copy = $rec['aadhar_copy'];
                    }
                    $record->created_by = $user->admin;
                    $record->created_date = $this->now();
                    $record->save();
                }
            }
            else if($tab == 'personal') {
                //Check for duplicate entry
                if(EmpProfile::where('emp_display_id', $rec['emp_display_id'])->count() == 0) {
                    //Save data in DB
                    $record = new EmpProfile();
                    $record->emp_display_id = $rec['emp_display_id'];
                    $record->name = $rec['emp_name'];
                    if($rec['dob']) {
                        $record->DOB = $rec['dob'];
                    }
                    if($rec['gender'] != 'Select') {
                        $record->gender = $rec['gender'];
                    }
                    if($rec['mobile']) {
                        $record->mobile = $rec['mobile'];
                        $rec['mob_verified'] = 'yes';
                    }
                    if($rec['mob_verified'] != 'Select') {
                        $record->mobile_verified = $rec['mob_verified'];
                    }
                    if($rec['email']) {
                        $record->email = $rec['email'];
                        $rec['email_verified'] = 'yes';
                    }
                    if($rec['email_verified'] != 'Select') {
                        $record->email_verified = $rec['email_verified'];
                    }
                    if($rec['marital_status'] != 'Select') {
                        $record->marital_status = $rec['marital_status'];
                    }
                    if($rec['profile_image']) {
                        $record->profile_image = $rec['profile_image'];
                    }
                    $record->created_by = $user->admin;
                    $record->created_date = $this->now();
                    $record->save();
                }
            }
            else if($tab == 'address') {
                //Check for duplicate entry
                if(EmpProfile::where('emp_display_id', $rec['emp_display_id'])->count() == 0) {
                    //Save data in DB
                    $record = new EmpProfile();
                    $record->emp_display_id = $rec['emp_display_id'];
                    $record->address_current = $rec['address_current'];
                    $record->address_permanent = $rec['address_permanent'];
                    $record->gurdian_name = $rec['gurdian_name'];
                    $record->gurdian_contact = $rec['gurdian_contact'];
                    $record->emergency_name = $rec['emergency_name'];
                    $record->emergency_contact = $rec['emergency_contact'];
                    $record->emergency_address = $rec['emergency_address'];
                    $record->created_by = $user->admin;
                    $record->created_date = $this->now();
                    $record->save();
                }
            }
            else if($tab == 'payroll') {
                //Check for duplicate entry
                if(EmpProfile::where('emp_display_id', $rec['emp_display_id'])->count() == 0) {
                    //Save data in DB
                    $record = new EmpProfile();
                    $record->emp_display_id = $rec['emp_display_id'];
                    if($rec['bank_Ifscs'] != 'Select') {
                        $bankInfo_id = BankInfo::where('ifsc', $rec['bank_Ifscs'])->select('id')->get();
                        $record->bank_info_id = $bankInfo_id[0]->{'id'};
                        //
                        $after = BankInfo::find($bankInfo_id[0]->{'id'});
                        $after->in_use += 1;
                        $after->save();
                    }
                    else{
                        $record->bank_info_id = null;
                    }

                    if($rec['bank_account_no']) {
                        $record->bank_account_no = $rec['bank_account_no'];
                    }
                    $record->cancel_cheque = $rec['cancel_cheque'];
                    if($rec['pf_account_no']){
                        $record->pf_account_no = $rec['pf_account_no'];
                    }
                    $record->created_by = $user->admin;
                    $record->created_date = $this->now();
                    $record->save();
                }
            }
        }
    }

    private function rec_update($tab, array $recs)
    {
        $uid = Auth::id();
        $user = User::find($uid);

        foreach ($recs as $rec) {
            if($tab == 'professional') {
                //Check for duplicate entry
                //if(EmpProfile::where('emp_display_id', $rec['emp_display_id'])->count() == 0) {
                    //Save data in DB
                    $record = EmpProfile::find($rec['id']);
                    if($rec['access_control'] != 'Select') {
                        $access_level_id = AccessControlList::where('access_level', $rec['access_control'])->select('id')->get();
                        //
                        $before = AccessControlList::find($record->access_level_id);
                        if ($before) {
                            $before->in_use -= 1;
                            $before->save();
                        }
                        //
                        $after = AccessControlList::find($access_level_id[0]->{'id'});
                        $after->in_use += 1;
                        $after->save();
                        //
                        $record->access_level_id = $access_level_id[0]->{'id'};
                    }
                    else{
                        if($record->access_level_id != '' and $record->access_level_id != 'Select'){
                            $before = AccessControlList::find($record->access_level_id);
                            $before->in_use -= 1;
                            $before->save();
                        }
                        $record->access_level_id = null;
                    }
                    $record->emp_display_id = $rec['emp_display_id'];
                    if($rec['department'] != 'Select') {
                        $department_id = Department::where('name', $rec['department'])->select('id')->get();
                        //
                        $before = Department::find($record->department_id);
                        if($before) {
                            $before->in_use -= 1;
                            $before->save();
                        }
                        //
                        $after = Department::find($department_id[0]->{'id'});
                        $after->in_use += 1;
                        $after->save();
                        //
                        $record->department_id = $department_id[0]->{'id'};
                    }
                    else{
                        if($record->department_id != '' and $record->department_id != 'Select'){
                            $before = Department::find($record->department_id);
                            $before->in_use -= 1;
                            $before->save();
                        }
                        $record->department_id = null;
                    }
                    if($rec['designation'] != 'Select') {
                        $designation_id = EmpDesignation::where('name', $rec['designation'])->select('id')->get();
                        //
                        $before = EmpDesignation::find($record->designation_id);
                        if($before) {
                            $before->in_use -= 1;
                            $before->save();
                        }
                        //
                        $after = EmpDesignation::find($designation_id[0]->{'id'});
                        $after->in_use += 1;
                        $after->save();
                        //
                        $record->designation_id = $designation_id[0]->{'id'};
                    }
                    else{
                        if($record->designation_id != '' and $record->designation_id != 'Select'){
                            $before = EmpDesignation::find($record->designation_id);
                            $before->in_use -= 1;
                            $before->save();
                        }
                        $record->designation_id = null;
                    }
                    $record->join_date = ($rec['join_date'] ? $rec['join_date'] : null);
                    $record->leave_date = ($rec['leave_date'] ? $rec['leave_date'] : null);
                    $record->last_login = null;
                    $record->last_logout = null;
                    $record->modified_by = $user->admin;
                    $record->modified_date = $this->now();
                    $record->status = $rec['status'];
                    $record->save();
                //}
            }
            else if($tab == 'kyc_qualification') {
                //Check for duplicate entry
                //if(EmpProfile::where('emp_display_id', $rec['emp_display_id'])->count() == 0) {
                    //Save data in DB
                    $record = EmpProfile::find($rec['id']);
                    $record->emp_display_id = $rec['emp_display_id'];
                    if($rec['qualification'] != 'Select') {
                        $qualification_id = EmpQualification::where('name', $rec['qualification'])->select('id')->get();
                        //
                        $before = EmpQualification::find($record->qualification_id);
                        if($before) {
                            $before->in_use -= 1;
                            $before->save();
                        }
                        //
                        $after = EmpQualification::find($qualification_id[0]->{'id'});
                        $after->in_use += 1;
                        $after->save();
                        //
                        $record->qualification_id = $qualification_id[0]->{'id'};
                    }
                    else{
                        if($record->qualification_id != '' and $record->qualification_id != 'Select'){
                            $before = EmpQualification::find($record->qualification_id);
                            $before->in_use -= 1;
                            $before->save();
                        }
                        $record->qualification_id = null;
                    }

                    $record->qualification_other = $rec['qualification_other'];
                    if($rec['qualification_docs']) {
                        $record->qualification_docs = $rec['qualification_docs'];
                    }
                    $record->panNumber = $rec['panNumber'];
                    if($rec['pan_copy']){
                        $record->pan_copy = $rec['pan_copy'];
                    }
                    $record->voterId = $rec['voterId'];
                    if($rec['voter_copy']){
                        $record->voter_copy = $rec['voter_copy'];
                    }
                    $record->aadharNumber = $rec['aadharNumber'];
                    if($rec['aadhar_copy']){
                        $record->aadhar_copy = $rec['aadhar_copy'];
                    }
                    $record->modified_by = $user->admin;
                    $record->modified_date = $this->now();
                    $record->save();
                //}
            }
            else if($tab == 'personal') {
                //Check for duplicate entry
                //if(EmpProfile::where('emp_display_id', $rec['emp_display_id'])->count() == 0) {
                    //Save data in DB
                    $record = EmpProfile::find($rec['id']);
                    $record->emp_display_id = $rec['emp_display_id'];
                    $record->name = $rec['emp_name'];
                    if($rec['dob']) {
                        $record->DOB = $rec['dob'];
                    }
                    if($rec['gender'] != 'Select') {
                        $record->gender = $rec['gender'];
                    }
                    if($rec['mobile']) {
                        $record->mobile = $rec['mobile'];
                        $rec['mob_verified'] = 'yes';
                    }
                    if($rec['mob_verified'] != 'Select') {
                        $record->mobile_verified = $rec['mob_verified'];
                    }
                    if($rec['email']) {
                        $record->email = $rec['email'];
                        $rec['email_verified'] = 'yes';
                    }
                    if($rec['email_verified'] != 'Select') {
                        $record->email_verified = $rec['email_verified'];
                    }
                    if($rec['marital_status'] != 'Select') {
                        $record->marital_status = $rec['marital_status'];
                    }
                    if($rec['profile_image']) {
                        $record->profile_image = $rec['profile_image'];
                    }
                    $record->modified_by = $user->admin;
                    $record->modified_date = $this->now();
                    $record->save();
                //}
            }
            else if($tab == 'address') {
                //Check for duplicate entry
                //if(EmpProfile::where('emp_display_id', $rec['emp_display_id'])->count() == 0) {
                    //Save data in DB
                    $record = EmpProfile::find($rec['id']);
                    $record->emp_display_id = $rec['emp_display_id'];
                    $record->address_current = $rec['address_current'];
                    $record->address_permanent = $rec['address_permanent'];
                    $record->gurdian_name = $rec['gurdian_name'];
                    $record->gurdian_contact = $rec['gurdian_contact'];
                    $record->emergency_name = $rec['emergency_name'];
                    $record->emergency_contact = $rec['emergency_contact'];
                    $record->emergency_address = $rec['emergency_address'];
                    $record->modified_by = $user->admin;
                    $record->modified_date = $this->now();
                    $record->save();
                //}
            }
            else if($tab == 'payroll') {
                //Check for duplicate entry
                //if(EmpProfile::where('emp_display_id', $rec['emp_display_id'])->count() == 0) {
                    //Save data in DB
                    $record = EmpProfile::find($rec['id']);
                    $record->emp_display_id = $rec['emp_display_id'];
                    if($rec['bank_Ifscs'] != 'Select') {
                        $bankInfo_id = BankInfo::where('ifsc', $rec['bank_Ifscs'])->select('id')->get();
                        //
                        $before = BankInfo::find($record->bank_info_id);
                        if($before) {
                            $before->in_use -= 1;
                            $before->save();
                        }
                        //
                        $after = BankInfo::find($bankInfo_id[0]->{'id'});
                        $after->in_use += 1;
                        $after->save();
                        //
                        $record->bank_info_id = $bankInfo_id[0]->{'id'};
                    }
                    else{
                        if($record->bank_info_id != '' and $record->bank_info_id != 'Select'){
                            $before = BankInfo::find($record->bank_info_id);
                            $before->in_use -= 1;
                            $before->save();
                        }
                        $record->bank_info_id = null;
                    }

                    if($rec['bank_account_no']){
                        $record->bank_account_no = $rec['bank_account_no'];
                    }
                    if($rec['cancel_cheque']) {
                        $record->cancel_cheque = $rec['cancel_cheque'];
                    }
                    if($rec['pf_account_no']){
                        $record->pf_account_no = $rec['pf_account_no'];
                    }
                    $record->modified_by = $user->admin;
                    $record->modified_date = $this->now();
                    $record->save();
                //}
            }
        }
    }

    private function deleteDirTree($dirpath)
    {
        if (is_file($dirpath)) {
            return unlink($dirpath);
        } elseif (is_dir($dirpath)) {
            $scan = glob(rtrim($dirpath, '/') . '/*');
            foreach ($scan as $index => $path) {
                $this->deleteDirTree($path);
            }
            return @rmdir($dirpath);
        }
    }

    private function rec_delete($tab, array $recs){
        foreach ($recs as $rec) {
            $record = EmpProfile::find($rec['id']);
            $record2 = EmpPayrollInfo::find($rec['id']);
            $record3 = EmpLeaveAllotment::find($rec['id']);
            if($record) {
                $storePath = "DocsUpload"; //Default
                $storePath_file = $storePath . '\\' . $record->emp_display_id . '\\';
                $this->deleteDirTree($storePath_file);

                if($record3) {
                    $record3->delete();
                }
                if($record2) {
                    $record2->delete();
                }
                //
                $before = AccessControlList::find($record->access_level_id);
                if($before) {
                    $before->in_use -= 1;
                    $before->save();
                }
                //
                $record->delete();
            }
        }
    }

    private function initializeArrayObj($mode, $org){
        $changed = array();

        if($mode == 0) {
            $Select = ['Select'];
            $changed['Select'] = $Select;
            foreach($org as $key => $value){
                $changed[$key] = $value;
            }
        }
        else if($mode == 1){
            $changed[0] = 'Select';
            foreach($org as $key => $value){
                $changed[$key+1] = $value;
            }
        }
        //dd($changed);
        return $changed;
    }

    public function index(){
        $empProfiles = EmpProfile::getByPaginate(8);
        //dd($empProfiles);
        $accessControlList = AccessControlList::where('status', 'Active')->pluck('access_level');
        $accessControlList = $this->initializeArrayObj(1, $accessControlList);
        //dd($accessControlList);
        $empDepartments = Department::pluck(['name']);
        $empDepartments = $this->initializeArrayObj(1, $empDepartments);
        //dd($empDepartments);
        $empDesignations = EmpDesignation::pluck(['name']);
        $empDesignations = $this->initializeArrayObj(1, $empDesignations);
        //dd($empDesignations);
        $profiles = [
            'accessControlList' => $accessControlList,
            'empDepartments' => $empDepartments,
            'empDesignations' => $empDesignations,
            'empQualifications' => [],
            'bankIfscs' => [],
            'empProfiles'  => $empProfiles
        ];
        $pageSetting = ["tab" => 'professional', "page" => "1", "recs" => 8, "sort_type" => "asc", "sort_by" => "profile_id", "query" => ""];
        return view('admin.empProfile', compact('profiles'))->with('pageSetting', $pageSetting);
    }

    public function fetch_data(Request $request)
    {
        $accessControlList = null;
        $empDesignations = null;
        $empQualifications = null;
        $bankIfscs = null;

        $tab = $request->input('fd_tab');
        $page = $request->input('fd_page');
        $recs = $request->input('fd_recs');
        $sort_by = $request->input('fd_sort_by');
        $sort_type = $request->input('fd_sort_type');
        $query = $request->input('fd_query');
        $query = Str::replaceArray(" ", ["%"], $query);

        $empProfiles = EmpProfile::getByConditionalPaginate($tab, $recs, $sort_by, $sort_type, $query);
        //dd($empProfiles);
        if($tab == 'professional'){
            $accessControlList = AccessControlList::where('status', 'Active')->pluck('access_level');
            $accessControlList = $this->initializeArrayObj(1, $accessControlList);
            //dd($accessControlList);
            $empDepartments = Department::pluck(['name']);
            $empDepartments = $this->initializeArrayObj(1, $empDepartments);
            //dd($empDepartments);
            $empDesignations = EmpDesignation::pluck(['name']);
            $empDesignations = $this->initializeArrayObj(1, $empDesignations);
            //dd($empDesignations);
            $profiles = [
                'accessControlList' => $accessControlList,
                'empDepartments' => $empDepartments,
                'empDesignations' => $empDesignations,
                'empQualifications' => [],
                'bankIfscs' => [],
                'empProfiles'  => $empProfiles
            ];
            //$empAttendance = EmpAttendance::getLogData();
            //dd($empAttendance);
        }
        else if($tab == 'kyc_qualification'){
            $empQualifications = EmpQualification::pluck(['name']);
            $empQualifications = $this->initializeArrayObj(1, $empQualifications);
            //dd($empQualifications);
            $profiles = [
                'accessControlList' => [],
                'empDepartments' => [],
                'empDesignations' => [],
                'empQualifications' => $empQualifications,
                'bankIfscs' => [],
                'empProfiles'  => $empProfiles
            ];
        }
        else if($tab == 'personal'){
            $profiles = [
                'accessControlList' => [],
                'empDepartments' => [],
                'empDesignations' => [],
                'empQualifications' => [],
                'bankIfscs' => [],
                'empProfiles'  => $empProfiles
            ];
        }
        else if($tab == 'address'){
            $profiles = [
                'accessControlList' => [],
                'empDepartments' => [],
                'empDesignations' => [],
                'empQualifications' => [],
                'bankIfscs' => [],
                'empProfiles'  => $empProfiles
            ];
        }
        else if($tab == 'payroll'){
            $bankIfscs = BankInfo::composite_pluck(['ifsc']);
            $bankIfscs = $this->initializeArrayObj(0, $bankIfscs);
            //dd($bankIfscs);
            $profiles = [
                'accessControlList' => [],
                'empDepartments' => [],
                'empDesignations' => [],
                'empQualifications' => [],
                'bankIfscs' => $bankIfscs,
                'empProfiles'  => $empProfiles
            ];
        }
        $pageSetting = ["tab" => $tab, "page" => $page, "recs" => $recs, "sort_type" => $sort_type, "sort_by" => $sort_by, "query" => $query];
        return view('admin.empProfile', compact('profiles'))->with('pageSetting', $pageSetting);
    }

    public function processJSON(Request $request)
    {
        $resStr = '';

        $inputs = json_decode($request->input('fd_cud'),true);
        //dd($inputs);
        $tab = $request->input('fd_tab');

        if(array_key_exists("C", $inputs)){
            $this->new_rec_create($tab, $inputs["C"]);
            $resStr .= ($resStr == '' ? 'C' : ';C');
        }

        if(array_key_exists("U", $inputs)){
            $this->rec_update($tab, $inputs["U"]);
            $resStr .= ($resStr == '' ? 'U' : ';U');
        }

        if(array_key_exists("D", $inputs)){
            $this->rec_delete($tab, $inputs["D"]);
            $resStr .= ($resStr == '' ? 'D' : ';D');
        }


        //Now prepare the query
        $page = $request->input('fd_cud_page');
        $recs = $request->input('fd_cud_recs');
        $sort_by = $request->input('fd_cud_sort_by');
        $sort_type = $request->input('fd_cud_sort_type');
        $query = $request->input('fd_cud_query');
        $query = Str::replaceArray(" ", ["%"], $query);

        $empProfiles = EmpProfile::getByConditionalPaginate($tab, $recs, $sort_by, $sort_type, $query);
        //dd($empProfiles);
        if($tab == 'professional'){
            $accessControlList = AccessControlList::where('status', 'Active')->pluck('access_level');
            $accessControlList = $this->initializeArrayObj(1, $accessControlList);
            //dd($accessControlList);
            $empDepartments = Department::pluck(['name']);
            $empDepartments = $this->initializeArrayObj(1, $empDepartments);
            //dd($empDepartments);
            $empDesignations = EmpDesignation::pluck(['name']);
            $empDesignations = $this->initializeArrayObj(1, $empDesignations);
            //dd($empDesignations);
            $profiles = [
                'accessControlList' => $accessControlList,
                'empDepartments' => $empDepartments,
                'empDesignations' => $empDesignations,
                'empQualifications' => [],
                'bankIfscs' => [],
                'empProfiles'  => $empProfiles
            ];
        }
        else if($tab == 'kyc_qualification'){
            $empQualifications = EmpQualification::pluck(['name']);
            $empQualifications = $this->initializeArrayObj(1, $empQualifications);
            //dd($empQualifications);
            $profiles = [
                'accessControlList' => [],
                'empDepartments' => [],
                'empDesignations' => [],
                'empQualifications' => $empQualifications,
                'bankIfscs' => [],
                'empProfiles'  => $empProfiles
            ];
        }
        else if($tab == 'personal'){
            $profiles = [
                'accessControlList' => [],
                'empDepartments' => [],
                'empDesignations' => [],
                'empQualifications' => [],
                'bankIfscs' => [],
                'empProfiles'  => $empProfiles
            ];
        }
        else if($tab == 'address'){
            $profiles = [
                'accessControlList' => [],
                'empDepartments' => [],
                'empDesignations' => [],
                'empQualifications' => [],
                'bankIfscs' => [],
                'empProfiles'  => $empProfiles
            ];
        }
        else if($tab == 'payroll'){
            $bankIfscs = BankInfo::composite_pluck(['ifsc']);
            $bankIfscs = $this->initializeArrayObj(0, $bankIfscs);
            //dd($bankIfscs);
            $profiles = [
                'accessControlList' => [],
                'empDepartments' => [],
                'empDesignations' => [],
                'empQualifications' => [],
                'bankIfscs' => $bankIfscs,
                'empProfiles'  => $empProfiles
            ];
        }
        $pageSetting = ["tab" => $tab, "page" => $page, "recs" => $recs, "sort_type" => $sort_type, "sort_by" => $sort_by, "query" => $query];
        return view('admin.empProfile', compact('profiles'))->with('pageSetting', $pageSetting);
    }

    public function upload_docs(Request $request){
        $fileabspath = '';
        $storePath = "DocsUpload"; //Default
        $storePath_file = $storePath . '\\' . $request->get('empId') . '\\' . $request->get('docType') . '\\';
        if(!file_exists($storePath_file)){
            mkdir($storePath_file, 0777, true);
        }

        if($request->hasfile('file')){
            foreach($request->file('file') as $single_file){
                $filename = $single_file->getClientOriginalName();
                $fileabspath = $single_file->move($storePath_file, $filename);
            }
        }
        return $storePath_file;
    }

    public function preview_docs(Request $request){
        $filenames = [];
        $i = 0;

        $filenames[$i++] = $request->get('id');
        $dirname = $request->get('path');
        $docs = glob($dirname."*.*");
        foreach($docs as $doc) {
            $filenames[$i++] = pathinfo($doc)['extension'];
            $filenames[$i++] = asset($doc);
        }
        return $filenames;
    }

    public function clean_docs(Request $request){
        $status = [];
        $i = 0;

        $status[$i++] = $request->get('id');
        $filePath = $request->get('filePath');
        $path_parts = pathinfo($filePath);
        $status[$i] = unlink($filePath);
        $docs = glob($path_parts['dirname']."*.*");
        if(count($docs) == 0){
            rmdir($path_parts['dirname']);
        }
        return $status;
    }
}
