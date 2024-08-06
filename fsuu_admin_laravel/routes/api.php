<?php

use App\Models\Profile;
use App\Models\RefCourse;
use App\Models\RefDepartment;
use App\Models\RefExamSchedule;
use App\Models\RefSemester;
use App\Models\StudentAcademic;
use App\Models\StudentExam;
use App\Models\StudentExamResult;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('api.access')->group(function () {

// Your API routes go here
Route::get('exam_schedule_display', [App\Http\Controllers\RefExamScheduleController::class, 'exam_schedule_display']);
Route::get('ref_school_level', [App\Http\Controllers\RefSchoolLevelController::class, 'ref_school_level']);
Route::get('ref_nationality', [App\Http\Controllers\RefNationalityController::class, 'index']);

Route::get('evaluation_print/{id}', [App\Http\Controllers\FormController::class, 'evaluation_print']);
Route::get('faculty_load_report_print', [App\Http\Controllers\FacultyLoadMonitoringController::class, 'faculty_load_report_print']);
Route::get('system_link_list', [App\Http\Controllers\SystemLinkController::class, 'index']);

Route::post('login', [App\Http\Controllers\AuthController::class, 'login']);
Route::post('register', [App\Http\Controllers\AuthController::class, 'register']);
Route::post('initial_registration', [App\Http\Controllers\AuthController::class, 'initial_registration']);

Route::apiResource('region_list', App\Http\Controllers\RefRegionController::class);
Route::apiResource('province_list', App\Http\Controllers\RefProvinceController::class);
Route::apiResource('municipality_list', App\Http\Controllers\RefMunicipalityController::class);
Route::apiResource('barangay_list', App\Http\Controllers\RefBarangayController::class);
Route::apiResource('school_level_list', App\Http\Controllers\RefSchoolLevelController::class);
Route::apiResource('school_name_list', App\Http\Controllers\RefSchoolController::class);
Route::apiResource('exam_category_list', App\Http\Controllers\RefExamCategoryController::class);
Route::apiResource('exam_schedule_list', App\Http\Controllers\RefExamScheduleController::class);
Route::apiResource('course_list', App\Http\Controllers\RefCourseController::class);

// Route::post('register', '\App\Http\Controllers\AuthController@register');
// Route::post('forgot_password', 'App\Http\Controllers\AuthController@forgot_password');

Route::middleware('auth:api')->group(function () {
    // UserController
    Route::post('multiple_archived_user', [App\Http\Controllers\UserController::class, "multiple_archived_user"]);

    Route::post('user_profile_photo_update', [App\Http\Controllers\UserController::class, "user_profile_photo_update"]);
    Route::get('user_profile_info', [App\Http\Controllers\UserController::class, "user_profile_info"]);
    Route::post('user_profile_info_update', [App\Http\Controllers\UserController::class, "user_profile_info_update"]);
    Route::post('user_update_role', [App\Http\Controllers\UserController::class, "user_update_role"]);
    Route::post('user_deactivate', [App\Http\Controllers\UserController::class, "user_deactivate"]);
    Route::post('users_update_email', [App\Http\Controllers\UserController::class, "users_update_email"]);
    Route::post('users_update_password', [App\Http\Controllers\UserController::class, "users_update_password"]);
    Route::post('users_info_update_password', [App\Http\Controllers\UserController::class, "users_info_update_password"]);
    Route::post('add_user', [App\Http\Controllers\UserController::class, "add_user"]);
    Route::apiResource('users', App\Http\Controllers\UserController::class);
    // END UserController

    // UserPermissionController
    Route::post('user_permission_status', [App\Http\Controllers\UserPermissionController::class, 'user_permission_status']);
    Route::apiResource('user_permission', App\Http\Controllers\UserPermissionController::class);
    // END UserPermissionController

    // ModuleController
    Route::apiResource('module', App\Http\Controllers\ModuleController::class);
    // END ModuleController

    // UserRolePermissionController
    Route::apiResource('user_role_permission', App\Http\Controllers\UserRolePermissionController::class);
    // END UserRolePermissionController

    // EmailTemplateController
    Route::post('email_template_multiple', [App\Http\Controllers\EmailTemplateController::class, 'email_template_multiple']);
    Route::apiResource('email_template', App\Http\Controllers\EmailTemplateController::class);
    // END EmailTemplateController

    // NotificationTemplateController
    Route::post('notification_template_multiple', [App\Http\Controllers\NotificationTemplateController::class, 'notification_template_multiple']);
    Route::apiResource('notification_template', App\Http\Controllers\NotificationTemplateController::class);
    // END NotificationTemplateController


    // ProfileController
    Route::post('student_subject_upload_excel', [App\Http\Controllers\ProfileController::class, "student_subject_upload_excel"]);
    Route::post('upload_signature', [App\Http\Controllers\ProfileController::class, "upload_signature"]);
    Route::post('profile_update', [App\Http\Controllers\ProfileController::class, "profile_update"]);
    Route::post('profile_deactivate', [App\Http\Controllers\ProfileController::class, "profile_deactivate"]);
    Route::post('profile_data_consent', [App\Http\Controllers\ProfileController::class, "profile_data_consent"]);
    Route::post('update_profile_photo', [App\Http\Controllers\ProfileController::class, "update_profile_photo"]);
    Route::post('profile_archived', [App\Http\Controllers\ProfileController::class, "profile_archived"]);
    Route::apiResource('profile', App\Http\Controllers\ProfileController::class);

    Route::apiResource('profile_address', App\Http\Controllers\ProfileAddressController::class);
    Route::apiResource('profile_department', App\Http\Controllers\ProfileDepartmentController::class);
    // END ProfileController

    // StudentExamController
    Route::post('update_additional_information', [App\Http\Controllers\StudentExamController::class, 'update_additional_information']);
    Route::post('update_academic_profile', [App\Http\Controllers\StudentExamController::class, 'update_academic_profile']);
    Route::post('update_emergency_contact', [App\Http\Controllers\StudentExamController::class, 'update_emergency_contact']);
    Route::post('update_family_profile', [App\Http\Controllers\StudentExamController::class, 'update_family_profile']);
    Route::post('update_school_attended', [App\Http\Controllers\StudentExamController::class, 'update_school_attended']);
    Route::post('update_student_address', [App\Http\Controllers\StudentExamController::class, 'update_student_address']);

    Route::post('update_student_exam_schedule', [App\Http\Controllers\StudentExamController::class, 'update_student_exam_schedule']);
    Route::post('update_student_basic_info', [App\Http\Controllers\StudentExamController::class, 'update_student_basic_info']);
    Route::post('multiple_applicant_auth', [App\Http\Controllers\StudentExamController::class, 'multiple_applicant_auth']);

    Route::post('student_exam_report_print', [App\Http\Controllers\StudentExamController::class, 'student_exam_report_print']);
    Route::post('exam_report_print', [App\Http\Controllers\StudentExamController::class, 'exam_report_print']);
    Route::apiResource('student_exams', App\Http\Controllers\StudentExamController::class);
    // END StudentExamController

    // StudentAcademicController
    Route::apiResource('student_academics', App\Http\Controllers\StudentAcademicController::class);
    // END StudentAcademicController

    // FACULTY LOAD

    // FacultyLoadMonitoringController
    Route::get('faculty_load_monitoring_graph2', [App\Http\Controllers\FacultyLoadMonitoringController::class, 'faculty_load_monitoring_graph2']);
    Route::get('faculty_load_monitoring_graph', [App\Http\Controllers\FacultyLoadMonitoringController::class, 'faculty_load_monitoring_graph']);
    Route::post('faculty_load_deduction', [App\Http\Controllers\FacultyLoadMonitoringController::class, 'faculty_load_deduction']);
    Route::post('faculty_load_monitoring_remarks', [App\Http\Controllers\FacultyLoadMonitoringController::class, 'faculty_load_monitoring_remarks']);
    Route::apiResource('faculty_load_monitoring', App\Http\Controllers\FacultyLoadMonitoringController::class);
    // END FacultyLoadMonitoringController

    // FacultyLoadController
    Route::post('grade_file_approval', [App\Http\Controllers\FacultyLoadController::class, 'grade_file_approval']);
    Route::post('faculty_load_status_bulk', [App\Http\Controllers\FacultyLoadController::class, 'faculty_load_status_bulk']);
    Route::post('faculty_load_status', [App\Http\Controllers\FacultyLoadController::class, 'faculty_load_status']);
    Route::post('faculty_load_update_room', [App\Http\Controllers\FacultyLoadController::class, 'faculty_load_update_room']);
    Route::post('faculty_load_upload', [App\Http\Controllers\FacultyLoadController::class, 'faculty_load_upload']);
    Route::post('faculty_load_report_print', [App\Http\Controllers\FacultyLoadController::class, 'faculty_load_report_print']);
    Route::post('faculty_load_multiple_archived', [App\Http\Controllers\FacultyLoadController::class, 'multiple_archived']);
    Route::apiResource('faculty_load', App\Http\Controllers\FacultyLoadController::class);
    // END FacultyLoadController

    Route::apiResource('faculty_load_schedule', App\Http\Controllers\FacultyLoadScheduleController::class);

    // FacultyLoadMonitoringJustificationController
    Route::post('flm_justification_approved', [App\Http\Controllers\FacultyLoadMonitoringJustificationController::class, 'flm_justification_approved']);
    Route::post('flm_justification_update_status', [App\Http\Controllers\FacultyLoadMonitoringJustificationController::class, 'flm_justification_update_status']);
    Route::post('flm_endorse_for_approval', [App\Http\Controllers\FacultyLoadMonitoringJustificationController::class, 'flm_endorse_for_approval']);
    Route::apiResource('flm_justification', App\Http\Controllers\FacultyLoadMonitoringJustificationController::class);
    // END FacultyLoadMonitoringJustificationController
    // END FACULTY LOAD

    // SCHEDULES
    // ScheduleController
    Route::apiResource('scheduling', App\Http\Controllers\ScheduleController::class);
    // END ScheduleController

    // ScheduleDayTimeController
    Route::apiResource('schedule_day_time', App\Http\Controllers\ScheduleDayTimeController::class);
    // END ScheduleDayTimeController
    // END SCHEDULES

    // SETTINGS
    Route::get('region_dropdown', [App\Http\Controllers\RefRegionController::class, 'region_dropdown']);

    Route::post('multiple_archived_exam_sched', [App\Http\Controllers\RefExamScheduleController::class, 'multiple_archived_exam_sched']);
    Route::post('multiple_archived_exam_category', [App\Http\Controllers\RefExamCategoryController::class, 'multiple_archived_exam_category']);
    Route::post('multiple_archived_department', [App\Http\Controllers\RefDepartmentController::class, 'multiple_archived_department']);
    Route::post('multiple_archived_course', [App\Http\Controllers\RefCourseController::class, 'multiple_archived_course']);

    Route::apiResource('user_role', App\Http\Controllers\UserRoleController::class);
    Route::apiResource('ref_building', App\Http\Controllers\RefBuildingController::class);
    Route::apiResource('ref_floor', App\Http\Controllers\RefFloorController::class);
    Route::apiResource('ref_room', App\Http\Controllers\RefRoomController::class);

    Route::apiResource('ref_exam_category', App\Http\Controllers\RefExamCategoryController::class);
    Route::apiResource('ref_department', App\Http\Controllers\RefDepartmentController::class);
    Route::apiResource('ref_course', App\Http\Controllers\RefCourseController::class);
    Route::apiResource('ref_section', App\Http\Controllers\RefSectionController::class);
    Route::apiResource('ref_subject', App\Http\Controllers\RefSubjectController::class);

    Route::apiResource('ref_status_category', App\Http\Controllers\RefStatusCategoryController::class);
    Route::apiResource('ref_status', App\Http\Controllers\RefStatusController::class);

    Route::apiResource('ref_day_schedule', App\Http\Controllers\RefDayScheduleController::class);
    Route::apiResource('ref_time_schedule', App\Http\Controllers\RefTimeScheduleController::class);
    Route::apiResource('ref_rate', App\Http\Controllers\RefRateController::class);

    Route::apiResource('ref_semester', App\Http\Controllers\RefSemesterController::class);
    Route::apiResource('ref_school_year', App\Http\Controllers\RefSchoolYearController::class);
    Route::apiResource('ref_exam_schedule', App\Http\Controllers\RefExamScheduleController::class);

    Route::apiResource('ref_civilstatus', App\Http\Controllers\RefCivilStatusController::class);
    Route::apiResource('ref_nationality', App\Http\Controllers\RefNationalityController::class);
    Route::apiResource('ref_religion', App\Http\Controllers\RefReligionController::class);
    Route::apiResource('ref_language', App\Http\Controllers\RefLanguageController::class);

    Route::apiResource('ref_region', App\Http\Controllers\RefRegionController::class);
    Route::apiResource('ref_province', App\Http\Controllers\RefProvinceController::class);
    Route::apiResource('ref_municipality', App\Http\Controllers\RefMunicipalityController::class);
    Route::apiResource('ref_barangay', App\Http\Controllers\RefBarangayController::class);

    Route::apiResource('ref_scholarship', App\Http\Controllers\RefScholarshipController::class);
    Route::apiResource('ref_school_level', App\Http\Controllers\RefSchoolLevelController::class);
    Route::apiResource('ref_school_name', App\Http\Controllers\RefSchoolController::class);
    Route::apiResource('ref_position', App\Http\Controllers\RefPositionController::class);
    // END SETTINGS

    Route::get('grade_submision_graph', [App\Http\Controllers\GradeFileController::class, 'grade_submision_graph']);
    Route::post('grade_file_status', [App\Http\Controllers\GradeFileController::class, 'grade_file_status']);
    Route::apiResource('grade_file', App\Http\Controllers\GradeFileController::class);

    Route::apiResource('notifications', App\Http\Controllers\NotificationController::class);
    Route::post('update_notification', [App\Http\Controllers\NotificationUserController::class, 'update_notification']);
    Route::apiResource('user_notifications', App\Http\Controllers\NotificationUserController::class);

    Route::apiResource('form', App\Http\Controllers\FormController::class);
    Route::get('mobile_student_form', [App\Http\Controllers\FormController::class, 'mobile_student_form']);
    Route::post('form_change_status', [App\Http\Controllers\FormController::class, 'form_change_status']);
    Route::get('form_question_category_view_result/{id}', [App\Http\Controllers\FormQuestionCategoryController::class, 'form_question_category_view_result']);
    Route::post('form_question_category_order', [App\Http\Controllers\FormQuestionCategoryController::class, 'form_question_category_order']);
    Route::post('form_question_category_change_status', [App\Http\Controllers\FormQuestionCategoryController::class, 'form_question_category_change_status']);
    Route::apiResource('form_question_category', App\Http\Controllers\FormQuestionCategoryController::class);
    Route::apiResource('form_question_option', App\Http\Controllers\FormQuestionOptionController::class);

    Route::post('form_question_answer_bulk_store', [App\Http\Controllers\FormQuestionAnswerController::class, 'form_question_answer_bulk_store']);
    Route::apiResource('form_question_answer', App\Http\Controllers\FormQuestionAnswerController::class);

    Route::post('system_link_archived', [App\Http\Controllers\SystemLinkController::class, 'system_link_archived']);
    Route::apiResource('system_link', App\Http\Controllers\SystemLinkController::class);
});


// function pp($data)
// {
//     echo '<pre>';
//     print_r($data);
//     echo '</pre>';
// }