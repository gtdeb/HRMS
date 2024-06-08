<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\EmpPayroll;
use App\EmpProfile;
use App\EmpAttendance;
use App\EmpLeave;
use App\EmpPayrollInfo;
use App\PayrollPayable;
use App\EmpDesignation;
use App\PayrollDeduction;
use App\EmpLeaveAllotment;
use Illuminate\Support\Facades\DB;
class EmpPayrollController extends Controller
{
    private function now(){
        date_default_timezone_set('Indian/Chagos');
        $timezone = date_default_timezone_get();
        $now = date_create(date('Y-m-d H:i:s'));
        date_add($now,date_interval_create_from_date_string("-30 minutes"));
        return $now;
    }

    private function validation(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|unique:payroll_payable_info',
        ]);
    }

    private function new_rec_create(array $recs){
        $id = Auth::id();
        $user = User::find($id);

    }

    private function rec_update(array $recs)
    {
        $uid = Auth::id();
        $user = User::find($uid);

        foreach ($recs as $rec) {
            $empProfile = EmpProfile::where('emp_display_id', $rec['emp_display_id'])->select('profile_id')->get();
            //dd($empProfile);
            $empPayroll = EmpPayroll::where('emp_id', $empProfile[0]->{'profile_id'})->select('id')->get();
            //dd($empPayroll);
            if($empPayroll[0]) {
                $record = EmpPayroll::find($empPayroll[0]->{'id'});
                $record->medicine_due = $rec['medicine_due'];
                $record->food_charge = $rec['food_charge'];
                $record->other_deduction = $rec['other_deduction'];
                $record->net_amount_payable = (($record->basic + $record->hra + $record->conveyance + $record->ot_encashment +
                        $record->leave_encashment) - ($record->ptax_deduction + $record->esi_deduction + $record->pf_deduction +
                        $record->tds_deduction + $record->medicine_due + $record->food_charge + $record->loan_due_deduction +
                        $record->other_deduction));
                $record->modified_by = $user->admin;
                $record->modified_date = $this->now();
                $record->save();
            }
        }
    }

    private function rec_delete(array $recs){
        foreach ($recs as $rec) {
            $record = EmpPayroll::find($rec['id']);
            if($record) {
                $record->delete();
            }
        }
    }

    private function new_rec_insert($issue_date, $year, $month, $emp_id, $attendance_CountFD, $attendance_CountHD, $leaveCount, $otCount, $absentCount){
        $uid = Auth::id();
        $user = User::find($uid);

        $empProfile = EmpProfile::where('emp_display_id', $emp_id)->select('profile_id', 'designation_id')->get();
        //dd($empProfile);
        $empDesignation = EmpDesignation::where('id', $empProfile[0]->{'designation_id'})->select('name')->get();
        //dd($empDesignation);
        $empPayrollInfo = EmpPayrollInfo::getPayrollData($emp_id);
        //dd($empPayrollInfo);
        $empPayableInfo = PayrollPayable::getPayrollPayableByDesignation($empDesignation[0]->{'name'});
        //dd($empPayableInfo->{'designation'});
        $empDeductionInfo = PayrollDeduction::getDeductionByDesignation($empDesignation[0]->{'name'});
        //dd($empDeductionInfo);
        $empLeaveAllotment = EmpLeaveAllotment::getLeaveAllotment($uid);
        //dd($empLeaveAllotment);
        $cumulative_ctc = $empPayrollInfo[0]->{'ctc'} + $empPayrollInfo[0]->{'hike_amt'} + $empPayrollInfo[0]->{'indiv_hike_amt'};

        //Save data in DB
        $record = new EmpPayroll();
        $record->issue_date = $issue_date;
        $record->year = $year;
        $record->month = $month;
        $record->emp_id = $empProfile[0]->{'profile_id'};
        $record->attendance_count = $attendance_CountFD[$emp_id] + $attendance_CountHD[$emp_id];
        $record->leave_count = $leaveCount[$emp_id];
        $record->off_day_count = $record->attendance_count/6;
        $record->ot_count = $otCount[$emp_id];
        $record->absent_count = $absentCount[$emp_id];
        $payable_days = $record->attendance_count + $record->leave_count + $record->off_day_count + ($record->ot_count - $record->absent_count);
        $record->days_worked = $payable_days;
        $record->basic = round(($cumulative_ctc*($empPayableInfo->{'basic'}/30)*$payable_days)/100, 2);
        $record->hra = round(($cumulative_ctc*($empPayableInfo->{'hra'}/30)*$payable_days)/100, 2);
        $record->conveyance = round(($cumulative_ctc*($empPayableInfo->{'conveyance'}/30)*$payable_days)/100, 2);
        $record->ot_encashment = $record->ot_count*$empPayableInfo->{'ot'};
        $record->leave_encashment = $empLeaveAllotment[0]->{'PL'}*$empPayableInfo->{'leave_encashment'};
        $record->tot_earning = $record->basic + $record->hra + $record->conveyance + $record->ot_encashment + $record->leave_encashment;
        $record->ptax_deduction = round(($cumulative_ctc*$empDeductionInfo->prof_tax)/100, 2);
        $record->esi_deduction = round(($cumulative_ctc*$empDeductionInfo->esi)/100, 2);
        $record->pf_deduction = round(($cumulative_ctc*$empDeductionInfo->pf)/100, 2);
        $record->tds_deduction = round(($cumulative_ctc*$empDeductionInfo->tds)/100, 2);
        $record->medicine_due = 0;
        $record->food_charge = 0;
        $record->loan_due_deduction = $empPayrollInfo[0]->{'loan_deducted_amt'};
        $record->other_deduction = 0;
        $record->tot_deduction = $record->ptax_deduction + $record->esi_deduction + $record->pf_deduction + $record->tds_deduction +
                                 $record->medicine_due + $record->food_charge + $record->loan_due_deduction + $record->other_deduction;
        $record->net_amount_payable = $record->tot_earning - $record->tot_deduction;
        $record->created_by = $user->admin;
        $record->created_date = $this->now();
        $record->save();
    }

    public function index(){
        $uid = Auth::id();
        $user = User::find($uid);

        if($user->admin == '1002') {
            $empPayrolls = EmpPayroll::getByPaginate(8, null);
        }
        else{
            $empPayrolls = EmpPayroll::getByPaginate(8, $uid);
        }
        //dd($empPayrolls);
        $pageSetting = ["page" => "1", "recs" => 8, "sort_type" => "asc", "sort_by" => "id", "query" => "", "user" => $user];
        return view('admin.empPayroll', compact('empPayrolls'))->with('pageSetting', $pageSetting);
    }

    public function fetch_data(Request $request)
    {
        $uid = Auth::id();
        $user = User::find($uid);

        $page = $request->input('fd_page');
        $recs = $request->input('fd_recs');
        $sort_by = $request->input('fd_sort_by');
        $sort_type = $request->input('fd_sort_type');
        $query = $request->input('fd_query');
        $query = Str::replaceArray(" ", ["%"], $query);

        if($user->admin == '1002') {
            $empPayrolls = EmpPayroll::getByConditionalPaginate($recs, $sort_by, $sort_type, $query, null);
        }
        else{
            $empPayrolls = EmpPayroll::getByConditionalPaginate($recs, $sort_by, $sort_type, $query, $uid);
        }
        //dd($empPayrolls);
        $pageSetting = ["page" => $page, "recs" => $recs, "sort_type" => $sort_type, "sort_by" => $sort_by, "query" => $query, "user" => $user];
        return view('admin.empPayroll', compact('empPayrolls'))->with('pageSetting', $pageSetting);
    }

    public function processJSON(Request $request)
    {
        $uid = Auth::id();
        $user = User::find($uid);

        $resStr = '';
        $inputs = json_decode($request->input('fd_cud'),true);
        //dd($inputs);

        if(array_key_exists("C", $inputs)){
            //$this->new_rec_create($inputs["C"]);
            $resStr .= ($resStr == '' ? 'C' : ';C');
        }

        if(array_key_exists("U", $inputs)){
            $this->rec_update($inputs["U"]);
            $resStr .= ($resStr == '' ? 'U' : ';U');
        }

        if(array_key_exists("D", $inputs)){
            $this->rec_delete($inputs["D"]);
            $resStr .= ($resStr == '' ? 'D' : ';D');
        }

        //Now prepare the query
        $page = $request->input('fd_cud_page');
        $recs = $request->input('fd_cud_recs');
        $sort_by = $request->input('fd_cud_sort_by');
        $sort_type = $request->input('fd_cud_sort_type');
        $query = $request->input('fd_cud_query');
        $query = Str::replaceArray(" ", ["%"], $query);

        if($user->admin == '1002') {
            $empPayrolls = EmpPayroll::getByConditionalPaginate($recs, $sort_by, $sort_type, $query, null);
        }
        else{
            $empPayrolls = EmpPayroll::getByConditionalPaginate($recs, $sort_by, $sort_type, $query, $uid);
        }
        //dd($empPayrolls);
        $pageSetting = ["page" => $page, "recs" => $recs, "sort_type" => $sort_type, "sort_by" => $sort_by, "query" => $query, "user" => $user];
        return view('admin.empPayroll', compact('empPayrolls'))->with('pageSetting', $pageSetting);
    }

    public function generate_Pay_Slip(Request $request){
        $uid = Auth::id();
        $user = User::find($uid);

        $issue_date = $this->now();
        $attendance_CountFD = [];
        $attendance_CountHD = [];
        $leaveCount = [];
        $offDayCount = [];
        $otCount = [];
        $absentCount = [];
        $lateAttendanceCount = [];
        $index = 0;

        $emp_id = $request->input('emp_id');
        $year = $request->input('year');
        $month = $request->input('month');

        if($month > 0) {
            if($emp_id) {
                $empProf = EmpProfile::where('emp_display_id', $emp_id)->select('profile_id')->get();
                $empProfiles[0] = $empProf[0]->{'profile_id'};
                $emp_ids[0] = $emp_id;
                //dd($empProfiles);
            }
            else{
                $empProfiles = EmpProfile::pluck('profile_id');
                $emp_ids = EmpProfile::pluck('emp_display_id');
            }
            //dd($empProfiles);
            foreach($empProfiles as $empProfile){
                //dd($empProfile);
                $empPayroll = EmpPayroll::where([
                    ['emp_id', '=', $empProfile],
                    ['year', '=', $year],
                    ['month', '=', $month],
                ])->select('id')->get();

                if(count($empPayroll) > 0) {
                    $record = EmpPayroll::find($empPayroll[0]->{'id'});
                    if ($record) {
                        $record->delete();
                    }
                }

                $empPayroll = EmpPayroll::where([
                    ['emp_id', '=', $empProfile],
                    ['year', '=', $year],
                    ['month', '=', $month],
                ])->select('id')->get();

                if (count($empPayroll) == 0) {
                    //dd($emp_id);
                    $attendanceRecords = EmpAttendance::getByPaySlipPaginate($emp_ids[$index], $year, $month);
                    //dd($attendanceRecords);
                    if (count($attendanceRecords) > 0 and $emp_ids[$index]) {
                        $attendance_CountFD[$emp_ids[$index]] = 0;
                        $attendance_CountHD[$emp_ids[$index]] = 0;
                        $otCount[$emp_ids[$index]] = 0;
                        $absentCount[$emp_ids[$index]] = 0;
                        $lateAttendanceCount[$emp_ids[$index]] = 0;
                        foreach ($attendanceRecords as $attendanceRecord) {
                            if ($lateAttendanceCount[$emp_ids[$index]] == 3) {
                                $attendance_CountHD[$emp_ids[$index]]++;
                                $lateAttendanceCount[$emp_ids[$index]] -= 3;
                            }
                            if ($attendanceRecord->{'login_attendance'} == 'Late') {
                                $lateAttendanceCount[$emp_ids[$index]]++;
                            } else if ($attendanceRecord->{'attendance_status'} == 'FullDay' and $attendanceRecord->{'login_attendance'} != 'Late') {
                                $attendance_CountFD[$emp_ids[$index]]++;
                            } else if ($attendanceRecord->{'attendance_status'} == 'HalfDay' and $attendanceRecord->{'login_attendance'} != 'Late') {
                                $attendance_CountHD[$emp_ids[$index]]++;
                            } else if ($attendanceRecord->{'attendance_status'} == 'OT' and $attendanceRecord->{'login_attendance'} != 'Late') {
                                $otCount[$emp_ids[$index]]++;
                            } else if ($attendanceRecord->{'attendance_status'} == 'Absent' and $attendanceRecord->{'login_attendance'} != 'Late') {
                                $absentCount[$emp_ids[$index]]++;
                            }
                        }
                        $leaveRecords = EmpLeave::getByPaySlipPaginate($emp_ids[$index], $issue_date);
                        //dd($leaveRecords);
                        $leaveCount[$emp_ids[$index]] = 0;
                        foreach ($leaveRecords as $leaveRecord) {
                            $strDate = date_create($leaveRecord->{'start_date'});
                            $endDt = date_create($leaveRecord->{'end_date'});
                            $date_diff = date_diff($endDt, $strDate);
                            $date_diff_days = $date_diff->format('%a');
                            $leaveCount[$emp_ids[$index]] += $date_diff_days + 1;
                        }
                        $this->new_rec_insert($issue_date, $year, $month, $emp_ids[$index], $attendance_CountFD, $attendance_CountHD, $leaveCount, $otCount, $absentCount);
                    }
                }
                $index++;
            }
        }
        if($user->admin == '1002') {
            $empPayrolls = EmpPayroll::getByPaginate(8, null);
        }
        else{
            $empPayrolls = EmpPayroll::getByPaginate(8, $uid);
        }
        //dd($empPayrolls);
        $pageSetting = ["page" => "1", "recs" => 8, "sort_type" => "asc", "sort_by" => "id", "query" => "", "user" => $user];
        return view('admin.empPayroll', compact('empPayrolls'))->with('pageSetting', $pageSetting);
    }

    public function print_Pay_Slip(Request $request){
        $id = $request->input('id');
        $empSalaryData = EmpPayroll::getById($id);
        //dd($empSalaryData);
        $empProfile = EmpProfile::getByEmpDisplayId($empSalaryData[0]->{'emp_display_id'});
        //dd($empData);
        $empData = [
          'empSalaryData' => $empSalaryData,
          'empProfile' => $empProfile
        ];

        return view('admin.empPaySlip', compact('empData'));
    }
}
