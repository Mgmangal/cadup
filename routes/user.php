<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ThemeOne\AaiController;
use App\Http\Controllers\ThemeOne\AtaController;
use App\Http\Controllers\ThemeOne\AdtController;
use App\Http\Controllers\ThemeOne\SFAController;
use App\Http\Controllers\ThemeOne\TboController;
use App\Http\Controllers\ThemeOne\FDTLController;
use App\Http\Controllers\ThemeOne\HomeController;
use App\Http\Controllers\ThemeOne\UserController;
use App\Http\Controllers\ThemeOne\FlyingController;
use App\Http\Controllers\ThemeOne\MasterController;
use App\Http\Controllers\ThemeOne\LibraryController;
use App\Http\Controllers\ThemeOne\MyLeaveController;
use App\Http\Controllers\ThemeOne\PaymentController;
use App\Http\Controllers\ThemeOne\ReportsController;
use App\Http\Controllers\ThemeOne\AirCraftController;
use App\Http\Controllers\ThemeOne\ContractController;
use App\Http\Controllers\ThemeOne\LoadTrimController;
use App\Http\Controllers\ThemeOne\CertificateController;


Route::get('home', [HomeController::class, 'index'])->name('user.home');
Route::get('dashboard',[HomeController::class, 'index'])->name('admin.dashboard');
Route::get('profile',[UserController::class, 'profile'])->name('user.profile');
Route::get('password', [UserController::class, 'password'])->name('user.password');

Route::get('flying', [FlyingController::class, 'index'])->name('user.flying.index');
Route::post('flying-list', [FlyingController::class, 'list'])->name('user.flying.list');
Route::get('my-flying', [FlyingController::class, 'myFlying'])->name('user.flying.myFlying');
Route::post('my-flying-list', [FlyingController::class, 'myFlyingList'])->name('user.flying.myFlyingList');

Route::get('shortie', [FlyingController::class, 'shortie'])->name('user.flying.shortie');
Route::post('shortie-list', [FlyingController::class, 'shortieList'])->name('user.flying.shortieList');
Route::get('my-shortie', [FlyingController::class, 'myShortie'])->name('user.flying.myShortie');
Route::post('my-shortie-list', [FlyingController::class, 'myShortieList'])->name('user.flying.myShortieList');

Route::get('fdtl-report', [FDTLController::class, 'index'])->name('user.fdtl.index');
Route::get('my-fdtl-report', [FDTLController::class, 'myFdtlReport'])->name('user.fdtl.myFdtlReport');
Route::post('get-report', [FDTLController::class, 'getReport'])->name('user.fdtl.getReport');
Route::get('fdtl/report/print/{id?}/{front_date?}/{to_date?}', [FDTLController::class, 'printReport'])->name('user.fdtl.printReport');

Route::get('statistics', [FlyingController::class, 'statistics'])->name('user.flying.statistics');
Route::get('my-statistics', [FlyingController::class, 'myStatistics'])->name('user.flying.myStatistics');
Route::post('statistics-list', [FlyingController::class, 'statisticsList'])->name('user.flying.statisticsList');
Route::get('statistics/print/{from_date?}/{to_date?}/{aircraft?}/{pilot?}/{flying_type?}', [FlyingController::class, 'statisticsPrint'])->name('user.flying.statisticsPrint');

Route::get('voilations', [FDTLController::class, 'voilations'])->name('user.fdtl.voilations');
Route::get('my-voilations', [FDTLController::class, 'MyVoilations'])->name('user.fdtl.MyVoilations');
Route::post('voilations-list', [FDTLController::class, 'voilationsList'])->name('user.fdtl.voilationsList');

Route::get('/flying/verify/{id}',[FlyingController::class, 'verify'])->name('user.flying.verify');

Route::prefix('sfa')->group(function () {
    Route::get('/sfa-generate', [SFAController::class, 'sfaGenerate'])->name('user.sfa.sfaGenerate');
    
    Route::get('/my-sfa-generate', [SFAController::class, 'mySfaGenerate'])->name('user.sfa.mySfaGenerate');
    
    Route::post('/sfa-store', [SFAController::class, 'sfaStore'])->name('user.sfa.sfaStore');

    Route::get('/sfa-list', [SFAController::class, 'sfaList'])->name('user.sfa.sfaList');
    
    Route::get('/my-sfa-list', [SFAController::class, 'mySfaList'])->name('user.sfa.mySfaList');
    
    Route::post('/get-sfa-list', [SFAController::class, 'getSfaList'])->name('user.sfa.getSfaList');
    
    Route::post('/sfa-flying-list', [SFAController::class, 'list'])->name('user.sfa.flyinglist');
    
    Route::get('/view/{id}', [SFAController::class, 'sfaView'])->name('user.sfa.view');
    
    Route::get('/delete/{id}', [SFAController::class, 'sfaDelete'])->name('user.sfa.deleted');
    Route::post('/forward', [SfaController::class, 'sfaForward'])->name('user.sfa.forward');

    Route::get('/send-adt/{id}',[SFAController::class, 'sendAdt'])->name('user.sfa.sendadt');
    Route::get('/verify/{id}',[SFAController::class, 'verify'])->name('user.sfa.verify');
    Route::get('/approved/{id}',[SFAController::class, 'approved'])->name('user.sfa.approved');
    
    Route::get('/download/{id}', [SFAController::class, 'downloadSfaReport'])->name('user.sfa.download');
    
});
Route::prefix('load-trim')->group(function () {
    Route::get('/', [LoadTrimController::class, 'index'])->name('user.loadTrim');
    Route::get('/add', [LoadTrimController::class, 'apply'])->name('user.loadTrim.add');
    Route::post('list', [LoadTrimController::class, 'list'])->name('user.loadTrim.list');
    Route::post('store', [LoadTrimController::class, 'store'])->name('user.loadTrim.store');
    Route::get('edit/{id}', [LoadTrimController::class, 'edit'])->name('user.loadTrim.edit');
    Route::post('update/{id}', [LoadTrimController::class, 'update'])->name('user.loadTrim.update');
    Route::get('cancel/{id}', [LoadTrimController::class, 'cancelled'])->name('user.loadTrim.cancelled');
});

Route::group(['prefix' => 'certificate'], function () {

    Route::get('licence', [CertificateController::class, 'licence'])->name('user.certificate.licence');
    Route::get('my-licence', [CertificateController::class, 'myLicence'])->name('user.certificate.myLicence');
    Route::post('licence-list', [CertificateController::class, 'licenceList'])->name('user.certificate.licenceList');
    Route::get('trainings', [CertificateController::class, 'trainings'])->name('user.certificate.trainings');
    Route::get('my-trainings', [CertificateController::class, 'myTrainings'])->name('user.certificate.myTrainings');
    Route::post('training-list', [CertificateController::class, 'trainingList'])->name('user.certificate.trainingList');
    Route::get('medicals', [CertificateController::class, 'medicals'])->name('user.certificate.medicals');
    Route::get('my-medicals', [CertificateController::class, 'myMedicals'])->name('user.certificate.myMedicals');
    Route::post('medical-list', [CertificateController::class, 'medicalList'])->name('user.certificate.medicalList');
    Route::get('qualifications', [CertificateController::class, 'qualifications'])->name('user.certificate.qualifications');
    Route::get('my-qualifications', [CertificateController::class, 'myQualifications'])->name('user.certificate.myQualifications');
    Route::post('qualification-list', [CertificateController::class, 'qualificationList'])->name('user.certificate.qualificationList');
    Route::get('ground-trainings', [CertificateController::class, 'groundTrainings'])->name('user.certificate.groundTrainings');
    Route::get('my-ground-trainings', [CertificateController::class, 'myGroundTrainings'])->name('user.certificate.myGroundTrainings');
    Route::post('ground-training-list', [CertificateController::class, 'groundTrainingList'])->name('user.certificate.groundTrainingList');

    Route::get('{type}/{user_id}/{id}', [CertificateController::class, 'viewLogs'])->name('user.certificate.viewLogs');
    Route::post('get-log-list', [CertificateController::class, 'getLogList'])->name('user.certificate.getLogList');
});
Route::post('/certificate/view', [CertificateController::class, 'viewData'])->name('user.ltm.view');

Route::prefix('reports')->group(function () {

    Route::get('external-flying', [ReportsController::class, 'externalFlying'])->name('user.reports.externalFlying');
    Route::post('external-flying-list', [ReportsController::class, 'externalFlyingList'])->name('user.reports.externalFlyingList');
    Route::get('external-flying-print/{from_date?}/{to_date?}/{aircraft?}/{flying_type?}', [ReportsController::class, 'externalFlyingPrint'])->name('user.reports.externalFlyingPrint');

    Route::get('pilot-flying-hours', [ReportsController::class, 'pilotFlyingHours'])->name('user.reports.pilotFlyingHours');
    Route::get('pilot-flying-hours-print/{from_date?}/{to_date?}', [ReportsController::class, 'pilotFlyingHoursPrint'])->name('user.reports.flyingHoursPrint');
    Route::get('aircraft-wise-summary-print/{from_date?}/{to_date?}', [ReportsController::class, 'aircraftWiseSummaryPrint'])->name('user.reports.aircraftWiseSummaryPrint');

    Route::get('pilot-ground-training', [ReportsController::class, 'pilotGroundTraining'])->name('user.reports.pilotGroundTraining');
    Route::get('pilot-ground-training-print/{date?}/{aircraft?}', [ReportsController::class, 'pilotGroundTrainingPrint'])->name('user.reports.pilotGroundTrainingPrint');

    Route::get('vip-recency', [ReportsController::class, 'vipRecency'])->name('user.reports.vipRecency');
    Route::get('vip-recency-print/{date?}/{aircraft_type?}', [ReportsController::class, 'vipRecencyPrint'])->name('user.reports.vipRecencyPrint');

    Route::get('/voilations/report', [FDTLController::class, 'voilationsReport'])->name('user.fdtl.voilations.report');
    Route::get('my-voilations/report', [FDTLController::class, 'myVoilationsReport'])->name('user.fdtl.myVoilations.report');
    Route::post('/voilations/report/list', [FDTLController::class, 'voilationsReportList'])->name('user.fdtl.voilations.report.list');

    Route::post('/voilation-details', [FDTLController::class, 'voilationDetails'])->name('user.fdtl.violation-details');
    Route::post('/update-exception', [FDTLController::class, 'updateException'])->name('user.fdtl.updateException');

    Route::post('/voilation-update', [FDTLController::class, 'voilationUpdate'])->name('user.fdtl.violation-update');
    Route::post('/update-re-update', [FDTLController::class, 'voilationReUpdate'])->name('user.fdtl.update.re-update');



    Route::get('aai-reports', [ReportsController::class, 'aaiReports'])->name('user.reports.aaiReports');
    Route::post('list', [AaiController::class, 'list'])->name('user.aai_report.list');
});

Route::get('/flying-currency', [ReportsController::class, 'flyingCurrency'])->name('user.reports.pilotFlyingCurrency');
Route::get('/flying-currency-print/{date?}/{aircraft?}/{report_type?}', [ReportsController::class, 'pilotFlyingCurrencyPrint'])->name('user.reports.pilotFlyingCurrencyPrint');
Route::get('/flying-test-details-print/{date?}', [ReportsController::class, 'FlyingTestDetailsPrint'])->name('user.reports.FlyingTestDetailsPrint');
Route::get('/training-and-checks-print/{date?}/{aircraft?}', [ReportsController::class, 'trainingChecksPrint'])->name('user.reports.trainingChecksPrint');

Route::prefix('my-leave')->group(function () {
    Route::get('/', [MyLeaveController::class, 'index'])->name('user.my.leave');
    Route::get('/apply', [MyLeaveController::class, 'apply'])->name('user.my.leave.apply');
    Route::post('list', [MyLeaveController::class, 'list'])->name('user.my.leave.list');
    Route::post('store', [MyLeaveController::class, 'store'])->name('user.my.leave.store');
    Route::get('edit/{id}', [MyLeaveController::class, 'edit'])->name('user.my.leave.edit');
    Route::post('update/{id}', [MyLeaveController::class, 'update'])->name('user.my.leave.update');
    Route::get('cancel/{id}', [MyLeaveController::class, 'cancelled'])->name('user.my.leave.cancelled');
});
Route::prefix('leave')->group(function () {
    
    Route::get('/', [MyLeaveController::class, 'leave'])->name('user.leave');
    
    Route::post('/list', [MyLeaveController::class, 'leaveList'])->name('user.leave.list');
    
    Route::get('/report', [MyLeaveController::class, 'leaveReport'])->name('user.leave.report');
    
    Route::post('/report/list', [MyLeaveController::class, 'leaveReportList'])->name('user.leave.report.list');
    
    Route::get('/create', [MyLeaveController::class, 'leaveCreate'])->name('user.leave.create');
    Route::get('/edit/{id}', [MyLeaveController::class, 'leaveEdit'])->name('user.leave.edit');
    Route::post('/store', [MyLeaveController::class, 'leaveStore'])->name('user.leave.store');
    Route::post('/update/{id}', [MyLeaveController::class, 'leaveUpdate'])->name('user.leave.update');
    Route::get('/view/{id}', [MyLeaveController::class, 'leaveShow'])->name('user.leave.view');
    Route::post('/change/status', [MyLeaveController::class, 'updateLeaveStatus'])->name('user.leave.status');
    Route::post('/checkValidLeave', [MyLeaveController::class, 'checkValidLeave'])->name('user.leave.checkValidLeave');
});





Route::get('/contract', [ContractController::class, 'index'])->name('user.contract');
Route::post('/contract', [ContractController::class, 'list'])->name('user.contract');

Route::group(['prefix' => 'users'], function () {
    Route::get('/', [UserController::class, 'index'])->name('user.users');
    Route::get('create', [UserController::class, 'create'])->name('user.users.create');
    Route::post('store', [UserController::class, 'store'])->name('user.users.store');
    Route::post('list', [UserController::class, 'list'])->name('user.users.list');
    
    Route::get('report', [UserController::class, 'userReport'])->name('user.users.report');
    Route::post('report/list', [UserController::class, 'Reportlist'])->name('user.users.report.list');
    
    Route::get('edit/{id}', [UserController::class, 'edit'])->name('user.users.edit');
    Route::put('update/{id}', [UserController::class, 'update'])->name('user.users.update');
    Route::post('change/status', [UserController::class, 'updateStatus'])->name('user.users.status');
    Route::get('destroy/{id}', [UserController::class, 'destroy'])->name('user.users.destroy');


    Route::get('profile', [UserController::class, 'profile'])->name('user.profile');
    Route::put('profile/update', [UserController::class, 'profileUpdate'])->name('user.profile.update');
    Route::get('password', [UserController::class, 'password'])->name('user.password');
    Route::put('password/update', [UserController::class, 'passwordUpdate'])->name('user.password.update');
    Route::post('get-user-by-section', [UserController::class,'getUserBySection'])->name('user.getUserBySection');

});

Route::group(['prefix' => 'aircrafts'], function () {
    Route::get('/', [AirCraftController::class, 'index'])->name('user.aircrafts');
    Route::post('list', [AirCraftController::class, 'list'])->name('user.aircraft.list');
    Route::get('create', [AirCraftController::class, 'create'])->name('user.aircraft.create');
    Route::post('store', [AirCraftController::class, 'store'])->name('user.aircraft.store');
    Route::get('edit/{id}', [AirCraftController::class, 'edit'])->name('user.aircraft.edit');
    Route::put('update/{id}', [AirCraftController::class, 'update'])->name('user.aircraft.update');
    Route::get('destroy/{id}', [AirCraftController::class, 'destroy'])->name('user.aircraft.destroy');

});

Route::group(['prefix' => 'masters'], function () {
    Route::get('aircraft-type', [MasterController::class, 'aircraft_type'])->name('user.master.aircraft_type');
    Route::post('aircraft-type-list', [MasterController::class, 'aircraft_type_list'])->name('user.master.aircraft_type_list');
    Route::post('aircraft-type-store', [MasterController::class, 'aircraft_type_store'])->name('user.master.aircraft_type_store');
    Route::get('aircraft-type-edit/{id}', [MasterController::class, 'aircraft_type_edit'])->name('user.master.aircraft_type_edit');
    Route::get('aircraft-type-destroy/{id}', [MasterController::class, 'aircraft_type_destroy'])->name('user.master.aircraft_type_destroy');

    Route::get('amp', [MasterController::class, 'amp'])->name('user.master.amp');
    Route::post('amp-list', [MasterController::class, 'amp_list'])->name('user.master.amp_list');
    Route::post('amp-store', [MasterController::class, 'amp_store'])->name('user.master.amp_store');
    Route::get('amp-edit/{id}', [MasterController::class, 'amp_edit'])->name('user.master.amp_edit');
    Route::post('amp-status', [MasterController::class, 'amp_status'])->name('user.master.amp_status');
    Route::get('amp-destroy/{id}', [MasterController::class, 'amp_destroy'])->name('user.master.amp_destroy');

    Route::get('job-functions', [MasterController::class, 'job_function'])->name('user.master.job_function');
    Route::post('job-function-list', [MasterController::class, 'job_function_list'])->name('user.master.job_function_list');
    Route::post('job-function-store', [MasterController::class, 'job_function_store'])->name('user.master.job_function_store');
    Route::get('job-function-edit/{id}', [MasterController::class, 'job_function_edit'])->name('user.master.job_function_edit');
    Route::get('job-function-destroy/{id}', [MasterController::class, 'job_function_destroy'])->name('user.master.job_function_destroy');

    Route::get('departments', [MasterController::class, 'department'])->name('user.master.department');
    Route::post('departments-list', [MasterController::class, 'department_list'])->name('user.master.department_list');
    Route::post('departments-store', [MasterController::class, 'department_store'])->name('user.master.department_store');
    Route::get('departments-edit/{id}', [MasterController::class, 'department_edit'])->name('user.master.department_edit');
    Route::get('departments-destroy/{id}', [MasterController::class, 'department_destroy'])->name('user.master.department_destroy');

    Route::get('designations', [MasterController::class, 'designation'])->name('user.master.designation');
    Route::post('designation-list', [MasterController::class, 'designation_list'])->name('user.master.designation_list');
    Route::post('designation-store', [MasterController::class, 'designation_store'])->name('user.master.designation_store');
    Route::get('designation-edit/{id}', [MasterController::class, 'designation_edit'])->name('user.master.designation_edit');
    Route::get('designation-destroy/{id}', [MasterController::class, 'designation_destroy'])->name('user.master.designation_destroy');

    Route::get('sections', [MasterController::class, 'section'])->name('user.master.section');
    Route::post('section-list', [MasterController::class, 'section_list'])->name('user.master.section_list');
    Route::post('section-store', [MasterController::class, 'section_store'])->name('user.master.section_store');
    Route::get('section-edit/{id}', [MasterController::class, 'section_edit'])->name('user.master.section_edit');
    Route::get('section-destroy/{id}', [MasterController::class, 'section_destroy'])->name('user.master.section_destroy');

    Route::get('roles', [MasterController::class, 'role'])->name('user.master.role');
    Route::post('role-list', [MasterController::class, 'role_list'])->name('user.master.role_list');
    Route::post('role-store', [MasterController::class, 'role_store'])->name('user.master.role_store');
    Route::get('role-edit/{id}', [MasterController::class, 'role_edit'])->name('user.master.role_edit');
    Route::get('role-destroy/{id}', [MasterController::class, 'role_destroy'])->name('user.master.role_destroy');
    Route::get('role/permission/{id}', [MasterController::class, 'permission'])->name('user.master.permission');
    Route::post('role/permission-store/{id}', [MasterController::class, 'permission_store'])->name('user.master.permission_store');

    // Route::get('subroles/{id}', [RoleController::class, 'subroles'])->name('app.settings.subroles');

    Route::get('resource-type', [MasterController::class, 'resource_type'])->name('user.master.resource_type');
    Route::post('resource-type-list', [MasterController::class, 'resource_type_list'])->name('user.master.resource_type_list');
    Route::post('resource-type-store', [MasterController::class, 'resource_type_store'])->name('user.master.resource_type_store');
    Route::get('resource-type-edit/{id}', [MasterController::class, 'resource_type_edit'])->name('user.master.resource_type_edit');
    Route::get('resource-type-delete/{id}', [MasterController::class, 'resource_type_delete'])->name('user.master.resource_type_delete');


});

Route::group(['prefix' => 'ata'], function () {
    Route::get('/', [AtaController::class, 'ata'])->name('user.ata');
    Route::post('ata-list', [AtaController::class, 'ata_list'])->name('user.ata.ata_list');
    Route::get('ata-create', [AtaController::class, 'ata_create'])->name('user.ata.ata_create');
    Route::post('ata-store', [AtaController::class, 'ata_store'])->name('user.ata.ata_store');
    Route::get('ata-edit/{id}', [AtaController::class, 'ata_edit'])->name('user.ata.ata_edit');
    Route::put('ata-update/{id}', [AtaController::class, 'ata_update'])->name('user.ata.ata_update');
    Route::post('ata-status', [AtaController::class, 'ata_status'])->name('user.ata.ata_status');
    Route::get('ata-destroy/{id}', [AtaController::class, 'ata_destroy'])->name('user.ata.ata_destroy');

    Route::get('category', [AtaController::class, 'category'])->name('user.ata.category');
    Route::post('category-list', [AtaController::class, 'category_list'])->name('user.ata.category_list');
    Route::get('category-create', [AtaController::class, 'category_create'])->name('user.ata.category_create');
    Route::post('category-store', [AtaController::class, 'category_store'])->name('user.ata.category_store');
    Route::get('category-edit/{id}', [AtaController::class, 'category_edit'])->name('user.ata.category_edit');
    Route::put('category-update/{id}', [AtaController::class, 'category_update'])->name('user.ata.category_update');
    Route::post('category-status', [AtaController::class, 'category_status'])->name('user.ata.category_status');
    Route::get('category-destroy/{id}', [AtaController::class, 'category_destroy'])->name('user.ata.category_destroy');

});

Route::group(['prefix' => 'tbo'], function () {
    Route::get('/', [TboController::class, 'index'])->name('user.tbo');
    Route::get('add', [TboController::class, 'add'])->name('user.tbo.add');
    Route::post('list', [TboController::class, 'list'])->name('user.tbo.list');
    Route::post('store', [TboController::class, 'store'])->name('user.tbo.store');
    Route::get('edit/{id}', [TboController::class, 'edit'])->name('user.tbo.edit');
    Route::post('update/{id}', [TboController::class, 'update'])->name('user.tbo.update');
    Route::post('status', [TboController::class, 'status'])->name('user.tbo.status');
    Route::get('destroy/{id}', [TboController::class, 'destroy'])->name('user.tbo.destroy');

});
Route::group(['prefix' => 'adt'], function () {
    Route::get('staff', [AdtController::class, 'index'])->name('user.adt.staff');
    Route::post('staff/list', [AdtController::class, 'list'])->name('user.adt.staff.list');
    Route::get('report', [AdtController::class, 'report'])->name('user.adt.report');
    Route::post('report/list', [AdtController::class, 'reportList'])->name('user.adt.report.list');
    Route::get('report/download/staff/list/{id}/1', [AdtController::class, 'downloadStaffList'])->name('user.adt.staff.download');
    Route::get('report/download/test/list/{id}', [AdtController::class, 'downloadTestList'])->name('user.adt.test.download');
    Route::post('report/upload', [AdtController::class, 'upload'])->name('user.adt.report.upload');
    Route::get('report/generate', [AdtController::class, 'generate'])->name('user.adt.report.generate');
    Route::post('report/store', [AdtController::class, 'generateReport'])->name('user.adt.report.store');
    Route::post('report/all/list', [AdtController::class, 'reportAllList'])->name('user.adt.report.all.list');
});

// Manage Library
Route::prefix('manage-library')->group(function () {

    Route::get('hr', [LibraryController::class, 'hr'])->name('user.library.hr');
    Route::post('hr-list', [LibraryController::class, 'hr_list'])->name('user.library.hr_list');
    Route::get('hr-create', [LibraryController::class, 'hr_create'])->name('user.library.hr_create');
    Route::post('hr-store', [LibraryController::class, 'hr_store'])->name('user.library.hr_store');
    Route::get('hr-edit/{id}', [LibraryController::class, 'hr_edit'])->name('user.library.hr_edit');
    Route::put('hr-update/{id}', [LibraryController::class, 'hr_update'])->name('user.library.hr_update');
    Route::get('hr-delete/{id?}', [LibraryController::class, 'hr_delete'])->name('user.library.hr_delete');

    Route::get('car', [LibraryController::class, 'car'])->name('user.library.car');
    Route::post('car-list', [LibraryController::class, 'car_list'])->name('user.library.car_list');
    Route::get('car-create', [LibraryController::class, 'car_create'])->name('user.library.car_create');
    Route::post('car-store', [LibraryController::class, 'car_store'])->name('user.library.car_store');
    Route::get('car-edit/{id}', [LibraryController::class, 'car_edit'])->name('user.library.car_edit');
    Route::put('car-update/{id}', [LibraryController::class, 'car_update'])->name('user.library.car_update');
    Route::get('car-delete/{id?}', [LibraryController::class, 'car_delete'])->name('user.library.car_delete');

    Route::get('fsdms', [LibraryController::class, 'fsdms'])->name('user.library.fsdms');
    Route::post('fsdms-list', [LibraryController::class, 'fsdms_list'])->name('user.library.fsdms_list');
    Route::get('fsdms-create', [LibraryController::class, 'fsdms_create'])->name('user.library.fsdms_create');
    Route::post('fsdms-store', [LibraryController::class, 'fsdms_store'])->name('user.library.fsdms_store');
    Route::get('fsdms-edit/{id}', [LibraryController::class, 'fsdms_edit'])->name('user.library.fsdms_edit');
    Route::put('fsdms-update/{id}', [LibraryController::class, 'fsdms_update'])->name('user.library.fsdms_update');
    Route::get('fsdms-delete/{id}', [LibraryController::class, 'fsdms_delete'])->name('user.library.fsdms_delete');

    Route::get('generic', [LibraryController::class, 'generic'])->name('user.library.generic');
    Route::post('generic-list', [LibraryController::class, 'generic_list'])->name('user.library.generic_list');
    Route::get('generic-create', [LibraryController::class, 'generic_create'])->name('user.library.generic_create');
    Route::post('generic-store', [LibraryController::class, 'generic_store'])->name('user.library.generic_store');
    Route::get('generic-edit/{id}', [LibraryController::class, 'generic_edit'])->name('user.library.generic_edit');
    Route::put('generic-update/{id}', [LibraryController::class, 'generic_update'])->name('user.library.generic_update');
    Route::get('generic-delete/{id}', [LibraryController::class, 'generic_delete'])->name('user.library.generic_delete');

    Route::get('{type?}-library/{id?}', [LibraryController::class, 'library_view'])->name('user.library.library_view');
});

// Manage Payment
Route::prefix('manage-payment')->group(function () {
    Route::get('', [PaymentController::class, 'history'])->name('user.payment.history');
    Route::post('history-list', [PaymentController::class, 'history_list'])->name('user.payment.history_list');
    Route::get('create', [PaymentController::class, 'create'])->name('user.payment.create');
    Route::post('store', [PaymentController::class, 'store'])->name('user.payment.store');
    Route::get('edit/{id}', [PaymentController::class, 'edit'])->name('user.payment.edit');
    Route::put('update/{id}', [PaymentController::class, 'update'])->name('user.payment.update');
    Route::get('delete/{id}', [PaymentController::class, 'destroy'])->name('user.payment.delete');

    Route::get('sfa', [PaymentController::class, 'sfa'])->name('user.payment.sfa');
    Route::post('sfa-list', [PaymentController::class, 'sfa_list'])->name('user.payment.sfa_list');
    Route::get('bill', [PaymentController::class, 'bill'])->name('user.payment.bill');
    Route::post('bill-list', [PaymentController::class, 'bill_list'])->name('user.payment.bill_list');
});



