<?php

namespace App\Http\Controllers;

use App\EmpLeaveAllotment;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\LeaveSchedule;
use App\LeaveCategory;
use App\EmpDesignation;
use Illuminate\Support\Facades\DB;
class LeaveScheduleController extends Controller
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
            'name' => 'required|unique:leave_schedule',
        ]);
    }

    private function new_rec_create(array $recs){
        $id = Auth::id();
        $user = User::find($id);

        foreach($recs as $rec){
            //Check for duplicate entry
            $designation_id = EmpDesignation::where('name', $rec['designation'])->select('id')->get();
            $leave_type_id = LeaveCategory::where('name', $rec['leave_type'])->select('id')->get();
            //dd($designation_id);
            $temp_des_id = (count($designation_id) > 0 ? $designation_id[0]->{'id'} : null);
            if(LeaveSchedule::where('leave_type_id', $leave_type_id[0]->{'id'})
                    ->where('designation_id', $temp_des_id)->count() == 0) {
                if(count($designation_id) > 0 || count($leave_type_id) > 0) {
                    //Save data in DB
                    $record = new LeaveSchedule();
                    $record->designation_id = (count($designation_id) > 0 ? $designation_id[0]->{'id'} : null);
                    if(count($designation_id) > 0) {
                        $in_use_obj = EmpDesignation::where('id', $record->designation_id)->select('in_use')->get();
                        $in_use = $in_use_obj[0]->{'in_use'} + 1;
                        EmpDesignation::where('id', $record->designation_id)->update(['in_use' => $in_use]);
                    }
                    $record->leave_type_id = $leave_type_id[0]->{'id'};
                    $in_use_obj = LeaveCategory::where('id', $record->leave_type_id)->select('in_use')->get();
                    $in_use = $in_use_obj[0]->{'in_use'} + 1;
                    LeaveCategory::where('id', $record->leave_type_id)->update(['in_use' => $in_use]);
                    $record->day_count = $rec['day_count'];
                    $record->created_by = $user->admin;
                    $record->created_date = $this->now();
                    $record->in_use = 0;
                    $record->save();
                }
            }
        }
    }

    private function rec_update(array $recs)
    {
        $uid = Auth::id();
        $user = User::find($uid);

        //dd($recs);
        foreach ($recs as $rec) {
            //Check for duplicate entry
            //if(LeaveSchedule::where('name', $rec['name'])->count() == 0) {
                $designation_id = EmpDesignation::where('name', $rec['designation'])->select('id')->get();
                $leave_type_id = LeaveCategory::where('name', $rec['leave_type'])->select('id')->get();
                if(count($designation_id) > 0 || count($leave_type_id) > 0) {
                    //Update data in DB
                    $record = LeaveSchedule::find($rec['id']);
                    $extra_day = $rec['day_count'] - $record->day_count;
                    if($extra_day >= 0) {
                        if (($record->designation_id && count($designation_id) == 0) ||
                            (count($designation_id) > 0 && $record->designation_id <> $designation_id[0]->{'id'})) {
                            //dd($record->designation_id);
                            if ($record->designation_id) {
                                $in_use_obj = EmpDesignation::where('id', $record->designation_id)->select('in_use')->get();
                                $in_use = $in_use_obj[0]->{'in_use'} - 1;
                                EmpDesignation::where('id', $record->designation_id)->update(['in_use' => $in_use]);
                            }
                            $record->designation_id = (count($designation_id) > 0 ? $designation_id[0]->{'id'} : null);
                            if ($record->designation_id) {
                                $in_use_obj = EmpDesignation::where('id', $record->designation_id)->select('in_use')->get();
                                $in_use = $in_use_obj[0]->{'in_use'} + 1;
                                EmpDesignation::where('id', $record->designation_id)->update(['in_use' => $in_use]);
                            }
                        }
                        if ($record->leave_type_id <> $leave_type_id[0]->{'id'}) {
                            $in_use_obj = LeaveCategory::where('id', $record->leave_type_id)->select('in_use')->get();
                            $in_use = $in_use_obj[0]->{'in_use'} - 1;
                            LeaveCategory::where('id', $record->leave_type_id)->update(['in_use' => $in_use]);
                            $record->leave_type_id = $leave_type_id[0]->{'id'};
                            $in_use_obj = LeaveCategory::where('id', $record->leave_type_id)->select('in_use')->get();
                            $in_use = $in_use_obj[0]->{'in_use'} + 1;
                            LeaveCategory::where('id', $record->leave_type_id)->update(['in_use' => $in_use]);
                        }
                        //Update Leave Allotment Table [START]
                        $record_allot = EmpLeaveAllotment::where('designation_id', $record->designation_id)->select('*')->get();
                        //dd($record_allot);
                        foreach($record_allot as $rec_allot) {
                            if ($rec['leave_type'] == 'CL') {
                                $rec_allot->CL += $extra_day;
                            } else if ($rec['leave_type'] == 'SL') {
                                $rec_allot->SL += $extra_day;
                            } else if ($rec['leave_type'] == 'EL') {
                                $rec_allot->EL += $extra_day;
                            } else if ($rec['leave_type'] == 'PL') {
                                $rec_allot->PL += $extra_day;
                            }
                            $rec_allot->save();
                        }
                        //Update Leave Allotment Table [END]
                        $record->day_count = $rec['day_count'];
                        $record->modified_by = $user->admin;
                        $record->modified_date = $this->now();
                        //dd($record);
                        $record->save();
                    }
                }
            //}
        }
    }

    private function rec_delete(array $recs){
        foreach ($recs as $rec) {
            $record = LeaveSchedule::find($rec['id']);
            if($record) {
                if($record->designation_id) {
                    $in_use_obj = EmpDesignation::where('id', $record->designation_id)->select('in_use')->get();
                    $in_use = $in_use_obj[0]->{'in_use'} - 1;
                    EmpDesignation::where('id', $record->designation_id)->update(['in_use' => $in_use]);
                }
                $in_use_obj = LeaveCategory::where('id', $record->leave_type_id)->select('in_use')->get();
                $in_use = $in_use_obj[0]->{'in_use'} - 1;
                LeaveCategory::where('id', $record->leave_type_id)->update(['in_use' => $in_use]);

                $record->delete();
            }
        }
    }

    public function index(){
        $leaveSchedules = LeaveSchedule::getByPaginate(8);
        //dd($leaveSchedules);
        $empDesignations = EmpDesignation::pluck(['name']);
        $empDesignations[count($empDesignations)] = null;
        //dd($empDesignations);
        $leaveCategories = LeaveCategory::pluck(['name']);
        //dd($leaveCategories);
        $leave_Schedules = [
            'leaveSchedules' => $leaveSchedules,
            'empDesignations'  => $empDesignations,
            'leaveCategories' => $leaveCategories
        ];
        $pageSetting = ["page" => "1", "recs" => 8, "sort_type" => "asc", "sort_by" => "id", "query" => ""];
        return view('admin.leaveSchedule', compact('leave_Schedules'))->with('pageSetting', $pageSetting);
    }

    public function fetch_data(Request $request)
    {
        $page = $request->input('fd_page');
        $recs = $request->input('fd_recs');
        $sort_by = $request->input('fd_sort_by');
        $sort_type = $request->input('fd_sort_type');
        $query = $request->input('fd_query');
        $query = Str::replaceArray(" ", ["%"], $query);

        $leaveSchedules = LeaveSchedule::getByConditionalPaginate($recs, $sort_by, $sort_type, $query);
        //dd($leaveSchedules);
        $empDesignations = EmpDesignation::pluck(['name']);
        $empDesignations[count($empDesignations)] = null;
        //dd($empDesignations);
        $leaveCategories = LeaveCategory::pluck(['name']);
        //dd($leaveCategories);
        $leave_Schedules = [
            'leaveSchedules' => $leaveSchedules,
            'empDesignations'  => $empDesignations,
            'leaveCategories' => $leaveCategories
        ];
        $pageSetting = ["page" => $page, "recs" => $recs, "sort_type" => $sort_type, "sort_by" => $sort_by, "query" => $query];
        return view('admin.leaveSchedule', compact('leave_Schedules'))->with('pageSetting', $pageSetting);
    }

    public function processJSON(Request $request)
    {
        $resStr = null;
        $inputs = json_decode($request->input('fd_cud'),true);
        //dd($inputs);

        if(array_key_exists("C", $inputs)){
            $this->new_rec_create($inputs["C"]);
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

        $leaveSchedules = LeaveSchedule::getByConditionalPaginate($recs, $sort_by, $sort_type, $query);
        //dd($leaveSchedules);
        $empDesignations = EmpDesignation::pluck(['name']);
        $empDesignations[count($empDesignations)] = null;
        //dd($empDesignations);
        $leaveCategories = LeaveCategory::pluck(['name']);
        //dd($leaveCategories);
        $leave_Schedules = [
            'leaveSchedules' => $leaveSchedules,
            'empDesignations'  => $empDesignations,
            'leaveCategories' => $leaveCategories
        ];
        $pageSetting = ["page" => $page, "recs" => $recs, "sort_type" => $sort_type, "sort_by" => $sort_by, "query" => $query];
        return view('admin.leaveSchedule', compact('leave_Schedules'))->with('pageSetting', $pageSetting);
    }
}
