<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\ShiftSchedule;
use App\ShiftCategory;
use Illuminate\Support\Facades\DB;

class ShiftScheduleController extends Controller
{
    private function now(){
        date_default_timezone_set('Indian/Chagos');
        $timezone = date_default_timezone_get();
        $now = date_create(date('Y-m-d H:i:s'));
        date_add($now,date_interval_create_from_date_string("-30 minutes"));
        return $now;
    }

    private function new_rec_create(array $recs){
        $id = Auth::id();
        $user = User::find($id);

        foreach($recs as $rec){
            //Check for duplicate entry
            if(ShiftSchedule::where('name', $rec['name'])->count() == 0) {
                $type_id = ShiftCategory::where('name', $rec['shift_type'])->select('id')->get();
                if(count($type_id) > 0) {
                    //Save data in DB
                    $record = new ShiftSchedule();
                    $record->name = $rec['name'];
                    $record->start_time = $rec['s_time'];
                    $record->end_time = $rec['e_time'];
                    $record->type_id = $type_id[0]->{'id'};
                    $in_use_obj = ShiftCategory::where('id', $record->type_id)->select('in_use')->get();
                    $in_use = $in_use_obj[0]->{'in_use'} + 1;
                    ShiftCategory::where('id', $record->type_id)->update(['in_use' => $in_use]);
                    $record->half_day_dur_minutes = $rec['half_day_dur'];
                    $record->delay_time_minutes = $rec['dur_delay'];
                    $record->login_punch_dur_mins = $rec['login_dur'];
                    $record->logout_punch_dur_mins = $rec['logout_dur'];
                    $record->ot_dur_mins = $rec['ot_dur'];
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

        foreach ($recs as $rec) {
            //Check for duplicate entry
            //if(ShiftSchedule::where('name', $rec['name'])->count() == 0) {
                $type_id = ShiftCategory::where('name', $rec['shift_type'])->select('id')->get();
                if(count($type_id) > 0) {
                    //Update data in DB
                    $record = ShiftSchedule::find($rec['id']);
                    $record->name = $rec['name'];
                    $record->start_time = $rec['s_time'];
                    $record->end_time = $rec['e_time'];
                    //dd($record->type_id);
                    //dd($type_id[0]->{'id'});
                    if($record->type_id <> $type_id[0]->{'id'}) {
                        $in_use_obj = ShiftCategory::where('id', $record->type_id)->select('in_use')->get();
                        if($in_use_obj[0]->{'in_use'} > 0) {
                            $in_use = $in_use_obj[0]->{'in_use'} - 1;
                            ShiftCategory::where('id', $record->type_id)->update(['in_use' => $in_use]);
                        }
                        $record->type_id = $type_id[0]->{'id'};
                        $in_use_obj = ShiftCategory::where('id', $record->type_id)->select('in_use')->get();
                        $in_use = $in_use_obj[0]->{'in_use'} + 1;
                        ShiftCategory::where('id', $record->type_id)->update(['in_use' => $in_use]);
                    }
                    $record->half_day_dur_minutes = $rec['half_day_dur'];
                    $record->delay_time_minutes = $rec['dur_delay'];
                    $record->login_punch_dur_mins = $rec['login_dur'];
                    $record->logout_punch_dur_mins = $rec['logout_dur'];
                    $record->ot_dur_mins = $rec['ot_dur'];
                    $record->modified_by = $user->admin;
                    $record->modified_date = $this->now();
                    $record->save();
                }
            //}
        }
    }

    private function rec_delete(array $recs){
        foreach ($recs as $rec) {
            $record = ShiftSchedule::find($rec['id']);
            if($record) {
                $in_use_obj = ShiftCategory::where('id', $record->type_id)->select('in_use')->get();
                if($in_use_obj[0]->{'in_use'} > 0) {
                    $in_use = $in_use_obj[0]->{'in_use'} - 1;
                    ShiftCategory::where('id', $record->type_id)->update(['in_use' => $in_use]);
                }
                $record->delete();
            }
        }
    }

    public function index(){
        $shiftSchedules = ShiftSchedule::getByPaginate(8);
        //dd($shiftSchedules);
        $shiftCategories = ShiftCategory::pluck(['name']);
        //dd($shiftCategories);
        $shifts = [
            'shiftCategories' => $shiftCategories,
            'shiftSchedules'  => $shiftSchedules
        ];
        $pageSetting = ["page" => "1", "recs" => 8, "sort_type" => "asc", "sort_by" => "id", "query" => ""];
        return view('admin.shiftSchedule', compact('shifts'))->with('pageSetting', $pageSetting);
    }

    public function fetch_data(Request $request)
    {
        $page = $request->input('fd_page');
        $recs = $request->input('fd_recs');
        $sort_by = $request->input('fd_sort_by');
        $sort_type = $request->input('fd_sort_type');
        $query = $request->input('fd_query');
        $query = Str::replaceArray(" ", ["%"], $query);

        $shiftSchedules = ShiftSchedule::getByConditionalPaginate($recs, $sort_by, $sort_type, $query);
        //dd($shiftSchedules);
        $shiftCategories = ShiftCategory::pluck(['name']);
        //dd($shiftCategories);
        $shifts = [
            'shiftCategories' => $shiftCategories,
            'shiftSchedules'  => $shiftSchedules
        ];
        $pageSetting = ["page" => $page, "recs" => $recs, "sort_type" => $sort_type, "sort_by" => $sort_by, "query" => $query];
        return view('admin.shiftSchedule', compact('shifts'))->with('pageSetting', $pageSetting);
    }

    public function processJSON(Request $request)
    {
        $resStr = '';
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

        $shiftSchedules = ShiftSchedule::getByConditionalPaginate($recs, $sort_by, $sort_type, $query);
        //dd($shiftSchedules);
        $shiftCategories = ShiftCategory::pluck(['name']);
        //dd($shiftCategories);
        $shifts = [
            'shiftCategories' => $shiftCategories,
            'shiftSchedules'  => $shiftSchedules
        ];
        $pageSetting = ["page" => $page, "recs" => $recs, "sort_type" => $sort_type, "sort_by" => $sort_by, "query" => $query];
        return view('admin.shiftSchedule', compact('shifts'))->with('pageSetting', $pageSetting);
    }
}
