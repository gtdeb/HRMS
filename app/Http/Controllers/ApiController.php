<?php

namespace App\Http\Controllers;

//use App\User;
//use Illuminate\Http\Request;
use App\ApiModel;
//use App\EmpAttendance;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;
//use Illuminate\Support\Facades\Validator;
//use Illuminate\Support\Str;

class ApiController extends Controller
{
    //
    private function now(){
        date_default_timezone_set('Indian/Chagos');
        $timezone = date_default_timezone_get();
        $now = date_create(date('Y-m-d H:i:s'));
        date_add($now,date_interval_create_from_date_string("-30 minutes"));
        return $now;
    }

    public function say_hello(Request $request){
        $data = $request->all();

        $sayhelloData = [];
        $sayhelloData['dvcSrNo'] = filter_var($data['dvcSrNo'], FILTER_SANITIZE_STRING);
        $sayhelloData['dvcTime'] = filter_var($data['dvcTime'], FILTER_SANITIZE_STRING);
        //$this->logger->info("API SayHello call received with data  dvcSrNo : " . $sayhelloData['dvcSrNo'] . " and dvcTime : " .  $sayhelloData['dvcTime']  );
        $responseMessage['status'] = 1; // 1 for success and 0 for error
        //header("Content-Type: application/json");
        //header('Access-Control-Allow-Origin', '*');
        //$response->getBody()->write(json_encode($responseMessage));
        //return $response;
        $userResponse = json_encode($responseMessage);
        print $userResponse;
    }

    public function transaction(Request $request){
        $data = $request->all();

        $transactionData = $data['trans'];
        $responseMessage["transStatus"] = array();


        $i = 1;
        foreach($transactionData as $transaction ){
            $reponseTrans = [];
            $logMessage = "API Transaction call received with following transaction data \n";
            $logMessage .= "txnId : " .filter_var($transaction["txnId"], FILTER_SANITIZE_STRING). ", " ;
            $logMessage .= "dvcId : " .filter_var($transaction["dvcId"], FILTER_SANITIZE_STRING) . ", ";
            $logMessage .= "dvcIP : " .filter_var($transaction["dvcIP"], FILTER_SANITIZE_STRING) .  ", ";
            $logMessage .= "punchId : " .filter_var($transaction["punchId"], FILTER_SANITIZE_STRING) . ", ";
            $logMessage .= "txnDateTime : " .filter_var($transaction["txnDateTime"], FILTER_SANITIZE_STRING) . ", ";
            $logMessage .= "mode : " .filter_var($transaction["mode"], FILTER_SANITIZE_STRING) . ", " ;
            //$this->logger->info($logMessage);
            $reponseTrans["txnId"] = $transaction["txnId"];
            $reponseTrans["status"] = 1;
            //array_push($responseMessage, $reponseTrans);
            //$responseMessage["transStatus"][] = '{"txnId":'.$transaction["txnId"].' ,"status":1}';
            $responseMessage["transStatus"][] = $reponseTrans;
        }

        //header("Content-Type: application/json");
        //$response->getBody()->write(json_encode($responseMessage));
        //return $response;
        $userResponse = json_encode($responseMessage);
        print $userResponse;
    }

    public function login_logout(){
        $index = 0;
        foreach($_GET as $key => $value){
            $param[$key] = $value;
            ++$index;
            if($index%2 == 0){
                $this->process_api_call($param);
                $param = null;
            }
        }
    }

    private function process_api_call($param) {
        $key = $param['secret_key'];
        $token = $param['token_key'];
        $validate = ApiModel::ApiValidate($token, $key);
        if ($validate == 1) {
            $result = $this->punchin_punchout($param);
            //dd($result);
            if (count($result)>0) {
                $responseData = array('success' => '1', 'data' => array($result));
            } else {
                $responseData = array('success' => '0', 'data' => array($result));
            }
        } else {
            $user_data = array('error' => array());
            $responseData = array('success' => '0', 'data' => $user_data, 'message' => "Unauthenticated call.");
        }
        $userResponse = json_encode($responseData);
        print $userResponse;
    }

    private function punchin_punchout($param) {
        if($this->loggedin($param)){
            return $this->logoutAttendance($param);
        }
        else{
            return $this->loginAttendance($param);
        }
    }

    private function loggedin($param){
        $display_id = $param['emp_id'];

        $query = DB::table('emp_profile')
            ->select('profile_id')
            ->where('emp_display_id', '=', $display_id)
            ->get();

        if($query[0]->profile_id){
            $query2 = DB::table('emp_attendance')
                ->select('emp_attendance.*')
                //->exists('login_date')
                ->where('emp_id', '=', $query[0]->profile_id)
                ->where('logout_date', '=', null)
                ->get();

            if(count($query2) > 0){
                return true;
            }
            else{
                return false;
            }
        }
    }

    private function loginAttendance($param){
        $c_status = '';
        $status_msg = '';
        $display_id = $param['emp_id'];
        //$user = User::find($uid);
        $strLogIn = $param['punch_time'];
        //$strLogIn = $login->format('Y-m-d H:i:s');

        $query = DB::table('emp_profile')
            ->select('profile_id')
            ->where('emp_display_id', '=', $display_id)
            ->get();

        try {
            DB::statement('call attendance_sheet_login(?,?,?,?)', [$query[0]->profile_id, $strLogIn, @c_status, @status_msg]);
            //dd(@c_status);
            //return view('home', compact('strLogIn'));
            $user_data = array(
                'login' => $strLogIn,
                'message' => 'Successful Check-In',
            );
        }
        catch(Exception $e){
            $user_data = array(
                'logout' => $strLogIn,
                'message' => $e,
            );
        }
        return $user_data;
    }

    private function logoutAttendance($param){
        $c_status = '';
        $status_msg = '';
        $display_id = $param['emp_id'];
        //$user = User::find($uid);
        $strLogOut = $param['punch_time'];;
        //$strLogOut = $logout->format('Y-m-d H:i:s');

        $query = DB::table('emp_profile')
            ->select('profile_id')
            ->where('emp_display_id', '=', $display_id)
            ->get();

        try {
            DB::statement('call attendance_sheet_logout(?,?,?,?)', [$query[0]->profile_id, $strLogOut, @c_status, @status_msg]);
            //dd($status_msg);
            //return view('home', compact('strLogOut'));
            $user_data = array(
                'logout' => $strLogOut,
                'message' => 'Successful Check-Out',
            );
        }
        catch(Exception $e){
            $user_data = array(
                'logout' => $strLogOut,
                'message' => $e,
            );
        }
        return $user_data;
    }

    public function card_flash(Request $request){
        $emp_display_id = $request->input('api_emp_id');
        dd($emp_display_id);

    }
}
