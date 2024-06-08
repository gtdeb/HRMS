<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/transaction', 'ApiController@transaction')->name('transaction');
Route::get('/sayhello', 'ApiController@say_hello')->name('sayhello');
Route::get('/apilogin_out', 'ApiController@login_logout')->name('apilogin_out');
Route::post('/api_cardFlash', 'ApiController@card_flash')->name('api_cardFlash');

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');

Route::group(['middleware' => 'auth'], function () {
Route::get('/chart', function () {
    return view('admin/chart');
});

Route::get('/reset', function () {
    return view('auth/passwords/reset');
});

Route::get('/department', 'DepartmentController@index')->name('department.index');
Route::post('/department/fetch_data', 'DepartmentController@fetch_data')->name('department.fetch_data');
Route::post('/department/update_data', 'DepartmentController@processJSON')->name('department.update_data');


Route::get('access_control', 'AccessControlListController@index')->name('access_control.index');
Route::post('/access_control/fetch_data', 'AccessControlListController@fetch_data')->name('access_control.fetch_data');
Route::post('/access_control/update_data', 'AccessControlListController@processJSON')->name('access_control.update_data');


Route::get('shift_category', 'ShiftCategoryController@index')->name('shift_category.index');
Route::post('/shift_category/fetch_data', 'ShiftCategoryController@fetch_data')->name('shift_category.fetch_data');
Route::post('/shift_category/update_data', 'ShiftCategoryController@processJSON')->name('shift_category.update_data');


Route::get('shift_schedule', 'ShiftScheduleController@index')->name('shift_schedule.index');
Route::post('/shift_schedule/fetch_data', 'ShiftScheduleController@fetch_data')->name('shift_schedule.fetch_data');
Route::post('/shift_schedule/update_data', 'ShiftScheduleController@processJSON')->name('shift_schedule.update_data');


Route::get('leave_category', 'LeaveCategoryController@index')->name('leave_category.index');
Route::post('/leave_category/fetch_data', 'LeaveCategoryController@fetch_data')->name('leave_category.fetch_data');
Route::post('/leave_category/update_data', 'LeaveCategoryController@processJSON')->name('leave_category.update_data');


Route::get('leave_schedule', 'LeaveScheduleController@index')->name('leave_schedule.index');
Route::post('/leave_schedule/fetch_data', 'LeaveScheduleController@fetch_data')->name('leave_schedule.fetch_data');
Route::post('/leave_schedule/update_data', 'LeaveScheduleController@processJSON')->name('leave_schedule.update_data');


Route::get('emp_category', 'EmpCategoryController@index')->name('emp_category.index');
Route::post('/emp_category/fetch_data', 'EmpCategoryController@fetch_data')->name('emp_category.fetch_data');
Route::post('/emp_category/update_data', 'EmpCategoryController@processJSON')->name('emp_category.update_data');


Route::get('emp_designation', 'EmpDesignationController@index')->name('emp_designation.index');
Route::post('/emp_designation/fetch_data', 'EmpDesignationController@fetch_data')->name('emp_designation.fetch_data');
Route::post('/emp_designation/update_data', 'EmpDesignationController@processJSON')->name('emp_designation.update_data');


Route::get('emp_qualification', 'EmpQualificationController@index')->name('emp_qualification.index');
Route::post('/emp_qualification/fetch_data', 'EmpQualificationController@fetch_data')->name('emp_qualification.fetch_data');
Route::post('/emp_qualification/update_data', 'EmpQualificationController@processJSON')->name('emp_qualification.update_data');


Route::get('emp_profile', 'EmpProfileController@index')->name('emp_profile.index');
Route::post('/emp_profile/fetch_data', 'EmpProfileController@fetch_data')->name('emp_profile.fetch_data');
Route::post('/emp_profile/update_data', 'EmpProfileController@processJSON')->name('emp_profile.update_data');
Route::post('/emp_profile/docs_upload', 'EmpProfileController@upload_docs')->name('emp_profile.docs_upload');
Route::post('/emp_profile/docs_preview', 'EmpProfileController@preview_docs')->name('emp_profile.docs_preview');
Route::post('/emp_profile/docs_clean', 'EmpProfileController@clean_docs')->name('emp_profile.docs_clean');

Route::get('/emp_payroll_info', 'EmpPayrollInfoController@index')->name('emp_payroll_info.index');
Route::post('/emp_payroll_info/fetch_data', 'EmpPayrollInfoController@fetch_data')->name('emp_payroll_info.fetch_data');
Route::post('/emp_payroll_info/update_data', 'EmpPayrollInfoController@processJSON')->name('emp_payroll_info.update_data');


Route::get('/emp_leave', 'EmpLeaveController@index')->name('emp_leave.index');
Route::post('/emp_leave/fetch_data', 'EmpLeaveController@fetch_data')->name('emp_leave.fetch_data');
Route::post('/emp_leave/update_data', 'EmpLeaveController@processJSON')->name('emp_leave.update_data');
Route::get('/emp_leave/leave', 'EmpLeaveController@leave_calculation')->name('emp_attendance.leave');
Route::post('/emp_leave/leave_cond', 'EmpLeaveController@leave_calculation_cond')->name('emp_attendance.leave_cond');

Route::get('/emp_attendance', 'EmpAttendanceController@index')->name('emp_attendance.index');
Route::post('/emp_attendance/fetch_data', 'EmpAttendanceController@fetch_data')->name('emp_attendance.fetch_data');
Route::post('/emp_attendance/update_data', 'EmpAttendanceController@processJSON')->name('emp_attendance.update_data');
Route::post('/emp_attendance/attendance_login', 'EmpAttendanceController@loginAttendance')->name('emp_attendance.attendance_login');
Route::post('/emp_attendance/attendance_logout', 'EmpAttendanceController@logoutAttendance')->name('emp_attendance.attendance_logout');
Route::get('/emp_attendance/attendance', 'EmpAttendanceController@attendance_calculation')->name('emp_attendance.attendance');
Route::post('/emp_attendance/attendance_cond', 'EmpAttendanceController@attendance_calculation_cond')->name('emp_attendance.attendance_cond');

Route::get('/emp_payroll', 'EmpPayrollController@index')->name('emp_payroll.index');
Route::post('/emp_payroll/fetch_data', 'EmpPayrollController@fetch_data')->name('emp_payroll.fetch_data');
Route::post('/emp_payroll/update_data', 'EmpPayrollController@processJSON')->name('emp_payroll.update_data');
Route::post('/emp_payroll/payslip', 'EmpPayrollController@generate_Pay_Slip')->name('emp_payroll.payslip');
Route::post('/emp_payroll/payslip_print', 'EmpPayrollController@print_Pay_Slip')->name('emp_payroll.payslip_print');

Route::get('payroll_deduction', 'PayrollDeductionController@index')->name('payroll_deduction.index');
Route::post('/payroll_deduction/fetch_data', 'PayrollDeductionController@fetch_data')->name('payroll_deduction.fetch_data');
Route::post('/payroll_deduction/update_data', 'PayrollDeductionController@processJSON')->name('payroll_deduction.update_data');


Route::get('payroll_payable', 'PayrollPayableController@index')->name('payroll_payable.index');
Route::post('/payroll_payable/fetch_data', 'PayrollPayableController@fetch_data')->name('payroll_payable.fetch_data');
Route::post('/payroll_payable/update_data', 'PayrollPayableController@processJSON')->name('payroll_payable.update_data');


Route::get('bank_info', 'BankInfoController@index')->name('bank_info.index');
Route::post('/bank_info/fetch_data', 'BankInfoController@fetch_data')->name('bank_info.fetch_data');
Route::post('/bank_info/update_data', 'BankInfoController@processJSON')->name('bank_info.update_data');


Route::resource('pages','AttendenceController');

Route::resource('admin','UsersController');

Route::resource('user','AdminAttendenceController');

Route::resource('leaves','Leaveholidays');
});
