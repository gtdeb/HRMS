<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\LeaveCategory;
use Illuminate\Support\Facades\DB;
class LeaveCategoryController extends Controller
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
            'name' => 'required|unique:leave_types',
        ]);
    }

    private function new_rec_create(array $recs){
        $id = Auth::id();
        $user = User::find($id);

        foreach($recs as $rec){
            //Check for duplicate entry
            if(LeaveCategory::where('name', $rec['name'])->count() == 0) {
                //Save data in DB
                $record = new LeaveCategory();
                $record->name = $rec['name'];
                $record->CF = $rec['CF'];
                $record->created_by = $user->admin;
                $record->created_date = $this->now();
                $record->in_use = 0;
                $record->save();
            }
        }
    }

    private function rec_update(array $recs)
    {
        $uid = Auth::id();
        $user = User::find($uid);

        foreach ($recs as $rec) {
            //Check for duplicate entry
            //if(LeaveCategory::where('name', $rec['name'])->count() == 0) {
                //Update data in DB
                $record = LeaveCategory::find($rec['id']);
                $record->name = $rec['name'];
                $record->CF = $rec['CF'];
                $record->modified_by = $user->admin;
                $record->modified_date = $this->now();
                $record->save();
            //}
        }
    }

    private function rec_delete(array $recs){
        foreach ($recs as $rec) {
            $record = LeaveCategory::find($rec['id']);
            if($record) {
                $record->delete();
            }
        }
    }

    public function index(){
        $leaveCategories = LeaveCategory::getByPaginate(8);
        //dd($leaveCategories);
        $pageSetting = ["page" => "1", "recs" => 8, "sort_type" => "asc", "sort_by" => "id", "query" => ""];
        return view('admin.leaveCategory', compact('leaveCategories'))->with('pageSetting', $pageSetting);
    }

    public function fetch_data(Request $request)
    {
        $page = $request->input('fd_page');
        $recs = $request->input('fd_recs');
        $sort_by = $request->input('fd_sort_by');
        $sort_type = $request->input('fd_sort_type');
        $query = $request->input('fd_query');
        $query = Str::replaceArray(" ", ["%"], $query);

        $leaveCategories = LeaveCategory::getByConditionalPaginate($recs, $sort_by, $sort_type, $query);
        //dd($leaveCategories);
        $pageSetting = ["page" => $page, "recs" => $recs, "sort_type" => $sort_type, "sort_by" => $sort_by, "query" => $query];
        return view('admin.leaveCategory', compact('leaveCategories'))->with('pageSetting', $pageSetting);
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

        $leaveCategories = LeaveCategory::getByConditionalPaginate($recs, $sort_by, $sort_type, $query);
        //dd($leaveCategories);
        $pageSetting = ["page" => $page, "recs" => $recs, "sort_type" => $sort_type, "sort_by" => $sort_by, "query" => $query];
        return view('admin.leaveCategory', compact('leaveCategories'))->with('pageSetting', $pageSetting);
    }
}
