<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ThemeOne\LTMController;
use App\Http\Controllers\ThemeOne\SFAController;
use App\Http\Controllers\ThemeOne\FDTLController;
use App\Http\Controllers\ThemeOne\HomeController;
use App\Http\Controllers\ThemeOne\UserController;
use App\Http\Controllers\ThemeOne\FlyingController;
use App\Http\Controllers\ThemeOne\ReportController;
use App\Http\Controllers\ThemeOne\MyLeaveController;
use App\Http\Controllers\ThemeOne\ContractController;
use App\Http\Controllers\ThemeOne\FlyingLogController;
use App\Http\Controllers\ThemeOne\CertificateController;


Route::get('home', [HomeController::class, 'userIndex'])->name('user.home');
Route::get('dashboard',[HomeController::class, 'userIndex'])->name('admin.dashboard');
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


Route::get('/flying/verify/{id}',[FlyingLogController::class, 'verify'])->name('user.flying.verify');


Route::get('/statistics', [FlyingLogController::class, 'statistics'])->name('user.flying.statistics');
Route::post('/statistics/list', [FlyingLogController::class, 'statisticsList'])->name('user.flying.statistics.list');
Route::get('/statistics/print/{from_date?}/{to_date?}/{aircraft?}/{flying_type?}', [FlyingLogController::class, 'statisticsPrint'])->name('user.flying.statistics.print');

Route::get('/sfa/generate', [SFAController::class, 'generate'])->name('user.sfa.generate');
Route::post('/sfa/generate', [SFAController::class, 'generateSfa'])->name('user.sfa.generate');
Route::get('/sfa/list', [SFAController::class, 'index'])->name('user.sfa.list');
Route::post('/sfa/list', [SFAController::class, 'getUserSfaList'])->name('user.sfa.list');
Route::get('/sfa/view/{id}', [SFAController::class, 'sfaView'])->name('user.sfa.view');
Route::get('/sfa/delete/{id}', [SFAController::class, 'sfaDelete'])->name('user.sfa.deleted');
Route::get('/sfa/verify/{id}',[SFAController::class, 'verify'])->name('user.sfa.verify');
Route::get('/sfa/approved/{id}',[SFAController::class, 'approved'])->name('user.sfa.approved');
Route::get('/sfa/download/{id}', [SFAController::class, 'downloadSfaReport'])->name('user.sfa.download');

Route::get('/certificate/licence', [CertificateController::class, 'index'])->name('user.certificate.licence');
Route::get('/certificate/trainings', [CertificateController::class, 'trainings'])->name('user.certificate.trainings');
Route::get('/certificate/medicals', [CertificateController::class, 'medicals'])->name('user.certificate.medicals');
Route::get('/certificate/qualifications', [CertificateController::class, 'qualifications'])->name('user.certificate.qualifications');
Route::get('/certificate/ground-trainings', [CertificateController::class, 'groundTrainings'])->name('user.certificate.groundTrainings');

Route::post('/monitoring/license-list', [LTMController::class, 'monitoringLicenseList'])->name('user.ltm.monitoringLicenseList');
Route::post('/monitoring/training-list', [LTMController::class, 'monitoringTrainingList'])->name('user.ltm.monitoringTrainingList');
Route::post('/monitoring/medical-list', [LTMController::class, 'monitoringMedicalList'])->name('user.ltm.monitoringMedicalList');
Route::post('/monitoring/qualification-list', [LTMController::class, 'monitoringQualificationList'])->name('user.ltm.monitoringQualificationList');
Route::post('/monitoring/ground-training-list', [LTMController::class, 'monitoringGroundTrainingList'])->name('user.ltm.monitoringGroundTrainingList');
Route::group(['prefix' => 'log'], function () {

    Route::get('/certificate/licence/{id}', [CertificateController::class, 'licenceLog'])->name('user.certificate.licence.log');
    Route::get('/certificate/trainings/{id}', [CertificateController::class, 'trainingsLog'])->name('user.certificate.trainings.log');
    Route::get('/certificate/medicals/{id}', [CertificateController::class, 'medicalsLog'])->name('user.certificate.medicals.log');
    Route::get('/certificate/qualifications/{id}', [CertificateController::class, 'qualificationsLog'])->name('user.certificate.qualifications.log');
    Route::get('/certificate/ground-trainings/{id}', [CertificateController::class, 'groundTrainingsLog'])->name('user.certificate.groundTrainings.log');

    Route::post('/monitoring/license-list', [CertificateController::class, 'monitoringLicenseLogList'])->name('user.ltm.monitoringLicenseList.log');
    Route::post('/monitoring/training-list', [CertificateController::class, 'monitoringTrainingLogList'])->name('user.ltm.monitoringTrainingList.log');
    Route::post('/monitoring/medical-list', [CertificateController::class, 'monitoringMedicalLogList'])->name('user.ltm.monitoringMedicalList.log');
    Route::post('/monitoring/qualification-list', [CertificateController::class, 'monitoringQualificationLogList'])->name('user.ltm.monitoringQualificationList.log');
    Route::post('/monitoring/ground-training-list', [CertificateController::class, 'monitoringGroundTrainingLogList'])->name('user.ltm.monitoringGroundTrainingList.log');
});

Route::post('/certificate/view', [CertificateController::class, 'viewData'])->name('user.ltm.view');
Route::get('/pilot/licence', [LTMController::class, 'index'])->name('user.pilot.licenses');

Route::get('/flying-currency', [ReportController::class, 'pilotFlyingCurrency'])->name('user.reports.pilotFlyingCurrency');
Route::get('/flying-currency-print/{date?}/{aircraft?}/{report_type?}', [ReportController::class, 'pilotFlyingCurrencyPrint'])->name('user.reports.pilotFlyingCurrencyPrint');
Route::get('/flying-test-details-print/{date?}', [ReportController::class, 'FlyingTestDetailsPrint'])->name('user.reports.FlyingTestDetailsPrint');
Route::get('/training-and-checks-print/{date?}/{aircraft?}', [ReportController::class, 'trainingChecksPrint'])->name('user.reports.trainingChecksPrint');
Route::prefix('my-leave')->group(function () {
    Route::get('/', [MyLeaveController::class, 'index'])->name('user.my.leave');
    Route::get('/apply', [MyLeaveController::class, 'apply'])->name('user.my.leave.apply');
    Route::post('list', [MyLeaveController::class, 'list'])->name('user.my.leave.list');
    Route::post('store', [MyLeaveController::class, 'store'])->name('user.my.leave.store');
    Route::get('edit/{id}', [MyLeaveController::class, 'edit'])->name('user.my.leave.edit');
    Route::post('update/{id}', [MyLeaveController::class, 'update'])->name('user.my.leave.update');
    Route::get('cancel/{id}', [MyLeaveController::class, 'cancelled'])->name('user.my.leave.cancelled');
});
Route::get('/contract', [ContractController::class, 'index'])->name('user.contract');
Route::post('/contract', [ContractController::class, 'list'])->name('user.contract');
Route::get('/voilations', [FDTLController::class, 'voilationsReport'])->name('user.voilations');
Route::post('/voilations/list', [FDTLController::class, 'voilationsReportList'])->name('user.voilations.report.list');



