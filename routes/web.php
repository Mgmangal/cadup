<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PilotController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AirCraftController;
use App\Http\Controllers\JobFunctionController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\AdtController;
use App\Http\Controllers\FlyingLogController;
use App\Http\Controllers\ExternalFlyingLogController;
use App\Http\Controllers\NoneFlyingLogController;
use App\Http\Controllers\FDTLController;
use App\Http\Controllers\SFAController; 
use App\Http\Controllers\LTMController;
use App\Http\Controllers\ReceiveDispatchController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\CvrFdrController;
use App\Http\Controllers\FilesController;
use App\Http\Controllers\MyLeaveController;
use App\Http\Controllers\LoadTrimController;
use App\Http\Controllers\StampticketController;
use App\Http\Controllers\ContractController;

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
Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('config:clear');
    return '<h1>clear cache</h1>';
});

Route::get('/', function () {
    return view('welcome');
})->name('welcome');
 
Auth::routes();

Route::group(['prefix' => 'admin','middleware' => ['auth','timezone']], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('admin.home');
}); 

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::post('cities', [HomeController::class,'get_city'])->name('home.get_city');
Route::get('/sectors/autocomplete', [HomeController::class, 'getSector'])->name('app.sectors.autocomplete');

Route::post('change/timezone', [HomeController::class,'changeTimezone'])->name('home.change_timezone');

Route::group(['prefix'=>'admin', 'middleware' => ['auth', 'verified','timezone']], function () {
    Route::get('/dashboard', function () {
        return view('app.dashboard');
    })->name('admin.dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('admin.profile.destroy');
    
    Route::get('/dashboard', [HomeController::class, 'index'])->name('app.dashboard');
    Route::post('/assign-license', [HomeController::class, 'getLicense'])->name('app.assignlicense');
    Route::post('/assign-post-flight-doc', [HomeController::class, 'getPostFlightDoc'])->name('app.assignPostFlightDoc');
   
    Route::post('/post-flight-doc', [HomeController::class, 'postFlightDoc'])->name('app.postFlightDoc');
    Route::post('/license', [HomeController::class, 'license'])->name('app.license');
    
    Route::post('/assign-leave', [HomeController::class, 'getLeave'])->name('app.assignleave');
    Route::post('/leave', [HomeController::class, 'leave'])->name('app.leave');

    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [UserController::class, 'index'])->name('app.users');
        Route::get('/create', [UserController::class, 'create'])->name('app.users.create');
        Route::post('/store', [UserController::class, 'store'])->name('app.users.store');
        Route::post('/list', [UserController::class, 'list'])->name('app.users.list');
        Route::get('/role/{id}', [UserController::class, 'role'])->name('app.users.roles');
        Route::post('/role/store/{id}', [UserController::class, 'roleStore'])->name('app.users.roles.store');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('app.users.edit');
        Route::put('/update/{id}', [UserController::class, 'update'])->name('app.users.update');
        Route::post('/change/status', [UserController::class, 'updateStatus'])->name('app.users.status');
        Route::get('/destroy/{id}', [UserController::class, 'destroy'])->name('app.users.destroy');
        Route::post('/get-section', [UserController::class, 'getSection'])->name('app.users.getSection');
        Route::post('/get-job-function', [UserController::class, 'getJobFunction'])->name('app.users.getJobFunction');
        Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
        Route::get('/licenses/{id}', [UserController::class, 'licenses'])->name('app.users.licenses');
    
        Route::put('/profile/update', [UserController::class, 'profileUpdate'])->name('user.profile.update');

        Route::get('/password', [UserController::class, 'password'])->name('user.password');
        Route::put('/password/update', [UserController::class, 'passwordUpdate'])->name('user.password.update');
        
        Route::post('get-user-by-section', [UserController::class,'getUserBySection'])->name('user.getUserBySection');
        
    });

    Route::group(['prefix' => 'pilot'], function () {
        Route::get('/', [PilotController::class, 'index'])->name('app.pilot');
        Route::get('/create', [PilotController::class, 'create'])->name('app.pilot.create');
        Route::post('/store', [PilotController::class, 'store'])->name('app.pilot.store');
        Route::post('/list', [PilotController::class, 'list'])->name('app.pilot.list');
        Route::get('/role/{id}', [PilotController::class, 'role'])->name('app.pilot.roles');
        Route::post('/role/store/{id}', [PilotController::class, 'roleStore'])->name('app.pilot.roles.store');
        Route::get('/edit/{id}', [PilotController::class, 'edit'])->name('app.pilot.edit');
        Route::put('/update/{id}', [PilotController::class, 'update'])->name('app.pilot.update');
        Route::post('/change/status', [PilotController::class, 'updateStatus'])->name('app.pilot.status');
        Route::get('/destroy/{id}', [PilotController::class, 'destroy'])->name('app.pilot.destroy');
        Route::post('/get-section', [PilotController::class, 'getSection'])->name('app.pilot.getSection');
        Route::post('/get-job-function', [PilotController::class, 'getJobFunction'])->name('app.pilot.getJobFunction');
        Route::get('/leave', [PilotController::class, 'leave'])->name('app.pilot.leave');
        Route::post('/leave/list', [PilotController::class, 'leaveList'])->name('app.pilot.leave.list');
        Route::get('/leave/create', [PilotController::class, 'leaveCreate'])->name('app.pilot.leave.create');
        Route::get('/leave/edit/{id}', [PilotController::class, 'leaveEdit'])->name('app.pilot.leave.edit');
        Route::post('/leave/store', [PilotController::class, 'leaveStore'])->name('app.pilot.leave.store');
        Route::post('/leave/update/{id}', [PilotController::class, 'leaveUpdate'])->name('app.pilot.leave.update');
        Route::get('/leave/view/{id}', [PilotController::class, 'leaveShow'])->name('app.pilot.leave.view');
        Route::post('/leave/change/status', [PilotController::class, 'updateLeaveStatus'])->name('app.pilot.leave.status');
        Route::post('/leave/checkValidLeave', [PilotController::class, 'checkValidLeave'])->name('app.pilot.leave.checkValidLeave');

        Route::get('/availability', [PilotController::class, 'availability'])->name('app.pilot.availability');
        Route::post('/availability/list', [PilotController::class, 'availabilityList'])->name('app.pilot.availability.list');
        
        Route::group(['prefix' => 'documents'], function () {
            Route::get('{user_id}', [PilotController::class, 'documents'])->name('app.pilot.documents');
            Route::post('list', [PilotController::class, 'documentsList'])->name('app.pilot.documents.list');
            Route::post('store', [PilotController::class, 'documentsStore'])->name('app.pilot.documents.store');
            Route::get('edit/{id?}', [PilotController::class, 'documentsEdit'])->name('app.pilot.documents.edit');
            Route::get('delete/{id?}', [PilotController::class, 'documentsDelete'])->name('app.pilot.documents.delete');
        });
        
        Route::get('/monitoring', [PilotController::class, 'monitoring'])->name('app.pilot.monitoring');
        Route::post('/monitoring/list', [PilotController::class, 'monitoringList'])->name('app.pilot.monitoring.list');

        Route::get('/profile', [PilotController::class, 'profile'])->name('pilot.profile');
        Route::get('/licenses/{id}', [PilotController::class, 'licenses'])->name('app.pilot.licenses');
        Route::post('/licenses/store', [PilotController::class, 'licensesStore'])->name('app.pilot.license.store');
        Route::post('/licenses/edit', [PilotController::class, 'licensesEdit'])->name('app.pilot.license.edit');
        Route::post('/licenses/update', [PilotController::class, 'licensesUpdate'])->name('app.pilot.license.update');
        Route::post('/training/store', [PilotController::class, 'trainingStore'])->name('app.pilot.training.store');
        Route::post('/training/edit', [PilotController::class, 'trainingEdit'])->name('app.pilot.training.edit');
        Route::post('/training/update', [PilotController::class, 'trainingUpdate'])->name('app.pilot.training.update');
        Route::post('/medical/store', [PilotController::class, 'medicalStore'])->name('app.pilot.medical.store');
        Route::post('/medical/edit', [PilotController::class, 'medicalEdit'])->name('app.pilot.medical.edit');
        Route::post('/medical/update', [PilotController::class, 'medicalUpdate'])->name('app.pilot.medical.update');
        
        Route::post('/qualification/store', [PilotController::class, 'qualificationStore'])->name('app.pilot.qualification.store');
        Route::post('/qualification/edit', [PilotController::class, 'qualificationEdit'])->name('app.pilot.qualification.edit');
        Route::post('/qualification/update', [PilotController::class, 'qualificationUpdate'])->name('app.pilot.qualification.update');
        
        Route::post('/ground-training/store', [PilotController::class, 'groundTrainingStore'])->name('app.pilot.groundTrainingStore');
        Route::post('/ground-training/edit', [PilotController::class, 'groundTrainingEdit'])->name('app.pilot.groundTrainingEdit');
        Route::post('/ground-training/update', [PilotController::class, 'groundTrainingUpdate'])->name('app.pilot.groundTrainingUpdate');
        
        Route::post('/certificat/delete', [PilotController::class, 'certificatDelete'])->name('app.pilot.certificat.delete');
        Route::post('/certificat/applicable', [PilotController::class, 'certificatApplicable'])->name('app.pilot.certificat.applicable');

        Route::get('/flying-hours', [PilotController::class, 'flyingHourMonthly'])->name('app.pilot.flyingHourMonthly');
        Route::get('/flying-hours/print/{from_date?}/{to_date?}', [PilotController::class, 'pilotFlyingHoursMonthlyPrint'])->name('app.pilot.flyingHoursMonthlyPrint');

        Route::get('/authorization/{id}', [PilotController::class, 'authorization'])->name('app.pilot.authorization');
        Route::get('/medical/{id}', [PilotController::class, 'medical'])->name('app.pilot.medical');
        Route::put('/profile/update', [PilotController::class, 'profileUpdate'])->name('pilot.profile.update');
        Route::get('/password', [PilotController::class, 'password'])->name('pilot.password');
        Route::put('/password/update', [PilotController::class, 'passwordUpdate'])->name('pilot.password.update');
    });

    Route::group(['prefix' => 'settings'], function () {
        Route::get('/contract-type', [SettingController::class, 'contractType'])->name('app.settings.contract.type');
        Route::post('/contract-type/list', [SettingController::class, 'contractTypeList'])->name('app.settings.contract.type.list');
        Route::post('/contract-type/store', [SettingController::class, 'contractTypeStore'])->name('app.settings.contract.type.store');
        Route::get('/contract-type/edit/{id}', [SettingController::class, 'contractTypeEdit'])->name('app.settings.contract.type.edit');
        Route::get('/contract-type/destroy/{id}', [SettingController::class, 'contractTypeDestroy'])->name('app.settings.contract.type.destroy');
        
        Route::get('/passenger', [SettingController::class, 'passengerIndex'])->name('app.settings.passenger');
        Route::post('/passenger/list', [SettingController::class, 'passengerList'])->name('app.settings.passenger.list');
        Route::post('/passenger/store', [SettingController::class, 'passengerStore'])->name('app.settings.passenger.store');
        Route::get('/passenger/edit/{id}', [SettingController::class, 'passengerEdit'])->name('app.settings.passenger.edit');
        Route::get('/passenger/destroy/{id}', [SettingController::class, 'passengerDestroy'])->name('app.settings.passenger.destroy');
        
        
        
        Route::get('/sfarate', [SettingController::class, 'sfarate'])->name('app.settings.sfarate');
        Route::post('/sfarate/list', [SettingController::class, 'sfarateList'])->name('app.settings.sfarate.list');
        Route::post('/sfarate/store', [SettingController::class, 'sfarateStore'])->name('app.settings.sfarate.store');
        Route::get('/sfarate/edit/{id}', [SettingController::class, 'sfarateEdit'])->name('app.settings.sfarate.edit');
        Route::get('/sfarate/destroy/{id}', [SettingController::class, 'sfarateDestroy'])->name('app.settings.sfarate.destroy');
        
        Route::get('/', [SettingController::class, 'index'])->name('app.settings');
        Route::put('/update', [SettingController::class, 'update'])->name('app.settings.update');
        Route::get('/roles', [RoleController::class, 'index'])->name('app.settings.roles');
        Route::post('/roles/list', [RoleController::class, 'list'])->name('app.settings.roles.list');
        Route::post('/roles/store', [RoleController::class, 'store'])->name('app.settings.roles.store');
        Route::get('/roles/edit/{id}', [RoleController::class, 'edit'])->name('app.settings.roles.edit');
        Route::get('/roles/destroy/{id}', [RoleController::class, 'destroy'])->name('app.settings.roles.destroy');
        Route::get('/subroles/{id}', [RoleController::class, 'subroles'])->name('app.settings.subroles');
        Route::get('/role/permissions/{id}', [RoleController::class, 'permissions'])->name('app.settings.permissions');
        Route::post('/role/permissions/store/{id}', [RoleController::class, 'permissionsStore'])->name('app.settings.permissions.store');
        
        Route::get('/sectors', [SettingController::class, 'sectors'])->name('app.settings.sectors');
        Route::post('/sectors/list', [SettingController::class, 'sectorsList'])->name('app.settings.sectors.list');
        Route::post('/sectors/store', [SettingController::class, 'sectorsStore'])->name('app.settings.sectors.store');
        Route::get('/sectors/edit/{id}', [SettingController::class, 'sectorsEdit'])->name('app.settings.sectors.edit');
        Route::get('/sectors/destroy/{id}', [SettingController::class, 'sectorsDestroy'])->name('app.settings.sectors.destroy');
        
        Route::get('/sections', [SectionController::class, 'index'])->name('app.settings.sections');
        Route::post('/sections/list', [SectionController::class, 'list'])->name('app.settings.sections.list');
        Route::post('/sections/store', [SectionController::class, 'store'])->name('app.settings.sections.store');
        Route::get('/sections/edit/{id}', [SectionController::class, 'edit'])->name('app.settings.sections.edit');
        Route::get('/sections/destroy/{id}', [SectionController::class, 'destroy'])->name('app.settings.sections.destroy');
       
        Route::get('departments', [DepartmentController::class, 'index'])->name('app.settings.departments');
        Route::post('departments/list', [DepartmentController::class, 'list'])->name('app.settings.departments.list');
        Route::post('departments/store', [DepartmentController::class, 'store'])->name('app.settings.departments.store');
        Route::get('departments/edit/{id}', [DepartmentController::class, 'edit'])->name('app.settings.departments.edit');
        Route::get('departments/destroy/{id}', [DepartmentController::class, 'destroy'])->name('app.settings.departments.destroy');

        Route::get('designations', [DesignationController::class, 'index'])->name('app.settings.designations');
        Route::post('designations/list', [DesignationController::class, 'list'])->name('app.settings.designations.list');
        Route::post('designations/store', [DesignationController::class, 'store'])->name('app.settings.designations.store');
        Route::get('designations/edit/{id}', [DesignationController::class, 'edit'])->name('app.settings.designations.edit');
        Route::get('designations/destroy/{id}', [DesignationController::class, 'destroy'])->name('app.settings.designations.destroy');
        

        Route::get('jobfunctions', [JobFunctionController::class, 'index'])->name('app.settings.jobfunctions');
        Route::post('jobfunctions/list', [JobFunctionController::class, 'list'])->name('app.settings.jobfunctions.list');
        Route::post('jobfunctions/store', [JobFunctionController::class, 'store'])->name('app.settings.jobfunctions.store');
        Route::get('jobfunctions/edit/{id}', [JobFunctionController::class, 'edit'])->name('app.settings.jobfunctions.edit');
        Route::get('jobfunctions/destroy/{id}', [JobFunctionController::class, 'destroy'])->name('app.settings.jobfunctions.destroy');
        
        Route::get('certificates', [SettingController::class, 'certificate'])->name('app.settings.certificates');
        Route::post('certificates/list', [SettingController::class, 'certificateList'])->name('app.settings.certificates.list');
        Route::post('certificates/store', [SettingController::class, 'certificateStore'])->name('app.settings.certificates.store');
        Route::get('certificates/edit/{id}', [SettingController::class, 'certificateEdit'])->name('app.settings.certificates.edit');
        Route::get('certificates/destroy/{id}', [SettingController::class, 'certificateDestroy'])->name('app.settings.certificates.destroy');
        
        Route::get('pilot-role', [SettingController::class, 'pilotroleIndex'])->name('app.settings.pilotrole');
        Route::post('pilot-role/list', [SettingController::class, 'pilotroleList'])->name('app.settings.pilotrole.list');
        Route::post('pilot-role/store', [SettingController::class, 'pilotroleStore'])->name('app.settings.pilotrole.store');
        Route::get('pilot-role/edit/{id}', [SettingController::class, 'pilotroleEdit'])->name('app.settings.pilotrole.edit');
        Route::get('pilot-role/destroy/{id}', [SettingController::class, 'pilotroleDestroy'])->name('app.settings.pilotrole.destroy');
        
        Route::get('flying-type', [SettingController::class, 'flyingtypeIndex'])->name('app.settings.flyingtype');
        Route::post('flying-type/list', [SettingController::class, 'flyingtypeList'])->name('app.settings.flyingtype.list');
        Route::post('flying-type/store', [SettingController::class, 'flyingtypeStore'])->name('app.settings.flyingtype.store');
        Route::get('flying-type/edit/{id}', [SettingController::class, 'flyingtypeEdit'])->name('app.settings.flyingtype.edit');
        Route::get('flying-type/destroy/{id}', [SettingController::class, 'flyingtypeDestroy'])->name('app.settings.flyingtype.destroy');
        
        Route::get('aircraft-type', [SettingController::class, 'aircraftTypeIndex'])->name('app.settings.aircraftType');
        Route::post('aircraft-type/list', [SettingController::class, 'aircraftTypeList'])->name('app.settings.aircraftType.list');
        Route::post('aircraft-type/store', [SettingController::class, 'aircraftTypeStore'])->name('app.settings.aircraftType.store');
        Route::get('aircraft-type/edit/{id}', [SettingController::class, 'aircraftTypeEdit'])->name('app.settings.aircraftType.edit');
        Route::get('aircraft-type/destroy/{id}', [SettingController::class, 'aircraftTypeDestroy'])->name('app.settings.aircraftType.destroy');
        
        Route::get('expenses-type', [SettingController::class, 'expensesType'])->name('app.settings.expensesType');
        Route::post('expenses-type-list', [SettingController::class, 'expensesTypeList'])->name('app.settings.expensesTypeList');
        Route::post('expenses-type-store', [SettingController::class, 'expensesTypeStore'])->name('app.settings.expensesTypeStore');
        Route::get('expenses-type-edit/{id}', [SettingController::class, 'expensesTypeEdit'])->name('app.settings.expensesTypeEdit');
        Route::get('expenses-type-delete/{id}', [SettingController::class, 'expensesTypeDelete'])->name('app.settings.expensesTypeDelete');
        
        Route::get('leave-type', [SettingController::class, 'leaveType'])->name('app.settings.leaveType');
        Route::post('leave-type-list', [SettingController::class, 'leaveTypeList'])->name('app.settings.leaveTypeList');
        Route::post('leave-type-store', [SettingController::class, 'leaveTypeStore'])->name('app.settings.leaveTypeStore');
        Route::get('leave-type-edit/{id}', [SettingController::class, 'leaveTypeEdit'])->name('app.settings.leaveTypeEdit');
        Route::get('leave-type-delete/{id}', [SettingController::class, 'leaveTypeDelete'])->name('app.settings.leaveTypeDelete');
        
        Route::get('post-flight-doc', [SettingController::class, 'postFlightDoc'])->name('app.settings.postFlightDoc');
        Route::post('post-flight-doc-list', [SettingController::class, 'postFlightDocList'])->name('app.settings.postFlightDocList');
        Route::post('post-flight-doc-store', [SettingController::class, 'postFlightDocStore'])->name('app.settings.postFlightDocStore');
        Route::get('post-flight-doc-edit/{id}', [SettingController::class, 'postFlightDocEdit'])->name('app.settings.postFlightDocEdit');
        Route::get('post-flight-doc-delete/{id}', [SettingController::class, 'postFlightDocDelete'])->name('app.settings.postFlightDocDelete');
        
        Route::get('expenditure', [SettingController::class, 'expenditure'])->name('app.settings.expenditure');
        Route::post('expenditure/list', [SettingController::class, 'expenditureList'])->name('app.settings.expenditureList');
        Route::post('expenditure/store', [SettingController::class, 'expenditureStore'])->name('app.settings.expenditureStore');
        Route::get('expenditure/edit/{id}', [SettingController::class, 'expenditureEdit'])->name('app.settings.expenditureEdit');
        Route::get('expenditure/delete/{id}', [SettingController::class, 'expenditureDelete'])->name('app.settings.expenditureDelete');
    });

    Route::group(['prefix' => 'adt'], function () {
        Route::get('staff', [AdtController::class, 'index'])->name('app.adt.staff');
        Route::post('staff/list', [AdtController::class, 'list'])->name('app.adt.staff.list');
        Route::get('report', [AdtController::class, 'report'])->name('app.adt.report');
        Route::post('report/list', [AdtController::class, 'reportList'])->name('app.adt.report.list');
        Route::get('report/download/staff/list/{id}/1', [AdtController::class, 'downloadStaffList'])->name('app.adt.staff.download');
        Route::get('report/download/test/list/{id}', [AdtController::class, 'downloadTestList'])->name('app.adt.test.download');
        Route::post('report/upload', [AdtController::class, 'upload'])->name('app.adt.report.upload');
        Route::get('report/generate', [AdtController::class, 'generate'])->name('app.adt.report.generate');
        Route::post('report/store', [AdtController::class, 'generateReport'])->name('app.adt.report.store');
        Route::post('report/all/list', [AdtController::class, 'reportAllList'])->name('app.adt.report.all.list');
    });

    Route::group(['prefix' => 'air-crafts'], function () {
        Route::get('/', [AirCraftController::class, 'index'])->name('app.air-crafts');
        Route::post('/list', [AirCraftController::class, 'list'])->name('app.air-crafts.list');
        Route::get('/create', [AirCraftController::class, 'create'])->name('app.air-crafts.create');
        Route::post('/store', [AirCraftController::class, 'store'])->name('app.air-crafts.store');
        Route::get('/edit/{id}', [AirCraftController::class, 'edit'])->name('app.air-crafts.edit');
        Route::put('/update/{id}', [AirCraftController::class, 'update'])->name('app.air-crafts.update');
        Route::get('/destroy/{id}', [AirCraftController::class, 'destroy'])->name('app.air-crafts.destroy');
        Route::get('/availability', [AirCraftController::class, 'availability'])->name('app.air-crafts.availability');
    });

    Route::group(['prefix' => 'flying-details'], function () {
        Route::get('/', [FlyingLogController::class, 'index'])->name('app.flying-details');
        Route::get('/lkohe-vilk-lko', [FlyingLogController::class, 'lkoheVilkLko'])->name('app.flying-details.lkoheVilkLko');
        Route::post('/lkohe-vilk-lko/list', [FlyingLogController::class, 'lkoheVilkLkoList'])->name('app.flying-details.lkoheVilkLko.list');
        
        Route::post('/list', [FlyingLogController::class, 'list'])->name('app.flying-details.list');
        Route::get('/create', [FlyingLogController::class, 'create'])->name('app.flying-details.create');
        Route::post('/store', [FlyingLogController::class, 'store'])->name('app.flying-details.store');
        Route::get('/edit/{id}', [FlyingLogController::class, 'edit'])->name('app.flying-details.edit');
        Route::put('/update/{id}', [FlyingLogController::class, 'update'])->name('app.flying-details.update');
        Route::get('/destroy/{id}', [FlyingLogController::class, 'destroy'])->name('app.flying-details.destroy');
        Route::post('/last/location', [FlyingLogController::class, 'lastLocation'])->name('app.flying-details.last.location');
        
        Route::get('/process', [FlyingLogController::class, 'processFlyingLog'])->name('app.flying-details.process');
        Route::post('/process/save', [FlyingLogController::class, 'processSave'])->name('app.flying-details.process.save');
        Route::post('/analyze-violation', [FlyingLogController::class, 'analyzeViolation'])->name('app.flying-details.analyze.violation');
        
        Route::get('/statistics', [FlyingLogController::class, 'statistics'])->name('app.flying-details.statistics');
        Route::post('/statistics/list', [FlyingLogController::class, 'statisticsList'])->name('app.flying-details.statistics.list');
        Route::get('/statistics/print/{from_date?}/{to_date?}/{aircraft?}/{flying_type?}', [FlyingLogController::class, 'statisticsPrint'])->name('app.flying-details.statistics.print');
        
        
        Route::get('/receive-flight-doc', [FlyingLogController::class, 'receiveFlightDoc'])->name('app.flying-details.receiveFlightDoc');
        Route::get('/receive-flight-doc/add', [FlyingLogController::class, 'receiveFlightDocAdd'])->name('app.flying-details.receiveFlightDoc.add');
        Route::post('/receive-flight-doc/store', [FlyingLogController::class, 'receiveFlightDocStore'])->name('app.flying-details.receiveFlightDoc.store');
        Route::post('/receive-flight-doc/list', [FlyingLogController::class, 'receiveFlightDocList'])->name('app.flying-details.receiveFlightDoc.list');
        Route::get('/receive-flight-doc/edit/{id}', [FlyingLogController::class, 'receiveFlightDocEdit'])->name('app.flying-details.receiveFlightDoc.edit');
        Route::post('/receive-flight/list', [FlyingLogController::class, 'receiveFlightList'])->name('app.flying-details.receiveFlight.list');
        Route::post('/receive-flight-doc/update', [FlyingLogController::class, 'receiveFlightDocUpdate'])->name('app.flying-details.receiveFlightDoc.update');
        Route::get('/post-flight-doc/print/{from_date?}/{to_date?}/{passenger?}/{bunch_no?}', [FlyingLogController::class, 'postFlightDocPrint'])->name('app.flying-details.assignPostFlightDocPrint');
        Route::post('/receive-flight-doc/view-details', [FlyingLogController::class, 'openFlightDetailModel'])->name('app.openFlightDetailModel');

    });
    
    Route::group(['prefix' => 'external-flying-details'], function () {
        Route::get('/', [ExternalFlyingLogController::class, 'index'])->name('app.external.flying-details');
        Route::post('/list', [ExternalFlyingLogController::class, 'list'])->name('app.external.flying-details.list');
        Route::get('/create', [ExternalFlyingLogController::class, 'create'])->name('app.external.flying-details.create');
        Route::post('/store', [ExternalFlyingLogController::class, 'store'])->name('app.external.flying-details.store');
        Route::get('/edit/{id}', [ExternalFlyingLogController::class, 'edit'])->name('app.external.flying-details.edit');
        Route::put('/update/{id}', [ExternalFlyingLogController::class, 'update'])->name('app.external.flying-details.update');
        Route::get('/destroy/{id}', [ExternalFlyingLogController::class, 'destroy'])->name('app.external.flying-details.destroy');
        Route::post('/last/location', [ExternalFlyingLogController::class, 'lastLocation'])->name('app.external.flying-details.last.location');
        
        Route::get('/statistics', [ExternalFlyingLogController::class, 'statistics'])->name('app.external.flying-details.statistics');
        Route::post('/statistics/list', [ExternalFlyingLogController::class, 'statisticsList'])->name('app.external.flying-details.statistics.list');
        Route::get('/statistics/print/{from_date?}/{to_date?}/{aircraft?}/{flying_type?}', [ExternalFlyingLogController::class, 'statisticsPrint'])->name('app.external.flying-details.statistics.print');
    });

    Route::group(['prefix' => 'none-flying-details'], function () {
        Route::get('/', [NoneFlyingLogController::class, 'index'])->name('app.none-flying-details');
        Route::post('/list', [NoneFlyingLogController::class, 'list'])->name('app.none-flying-details.list');
        Route::get('/create', [NoneFlyingLogController::class, 'create'])->name('app.none-flying-details.create');
        Route::post('/store', [NoneFlyingLogController::class, 'store'])->name('app.none-flying-details.store');
        Route::get('/edit/{id}', [NoneFlyingLogController::class, 'edit'])->name('app.none-flying-details.edit');
        Route::put('/update/{id}', [NoneFlyingLogController::class, 'update'])->name('app.none-flying-details.update');
        Route::get('/destroy/{id}', [NoneFlyingLogController::class, 'destroy'])->name('app.none-flying-details.destroy');
    });

    Route::group(['prefix' => 'fdtl'], function () {
        Route::get('/', [FDTLController::class, 'index'])->name('app.fdtl');
        Route::post('/list', [FDTLController::class, 'list'])->name('app.fdtl.list');
       
        Route::post('/report/get', [FDTLController::class, 'getReport'])->name('app.get.fdtl.report');
        Route::get('/monitoring', [FDTLController::class, 'monitoring'])->name('app.fdtl.monitoring');
        Route::get('/voilations', [FDTLController::class, 'voilations'])->name('app.fdtl.voilations');

        Route::get('/voilations/report', [FDTLController::class, 'voilationsReport'])->name('app.fdtl.voilations.report');
        Route::post('/voilations/report/list', [FDTLController::class, 'voilationsReportList'])->name('app.fdtl.voilations.report.list');

        Route::post('/voilation-details', [FDTLController::class, 'voilationDetails'])->name('app.fdtl.violation-details');
        Route::post('/update-exception', [FDTLController::class, 'updateException'])->name('app.fdtl.updateException');
        
        Route::post('/voilation-update', [FDTLController::class, 'voilationUpdate'])->name('app.fdtl.violation-update');
        Route::post('/update-re-update', [FDTLController::class, 'voilationReUpdate'])->name('app.fdtl.update.re-update');
        
        
        
        Route::get('/report/print/{id?}/{front_date?}/{to_date?}', [FDTLController::class, 'printReport'])->name('app.print.fdtl.report');
        Route::get('/report/{id}', [FDTLController::class, 'report'])->name('app.fdtl.report');
    });

    Route::group(['prefix' => 'ltm'], function () {
        Route::get('/', [LTMController::class, 'index'])->name('app.ltm');
        Route::post('/list',[LTMController::class, 'list'])->name('app.ltm.list');
        // Route::get('/add', [LTMController::class, 'add'])->name('app.ltm.add');
        // Route::get('/renuew', [LTMController::class, 'renuew'])->name('app.ltm.renuew');
        Route::get('/history', [LTMController::class, 'history'])->name('app.ltm.history');
        Route::post('/history/list', [LTMController::class, 'historyLicenseList'])->name('app.ltm.history.list');
        // Route::get('/pilot/log', [LTMController::class, 'pilotLtmLog'])->name('app.ltm.log');
        Route::get('/monitoring', [LTMController::class, 'monitoring'])->name('app.ltm.monitoring');
        Route::post('/monitoring/license-list', [LTMController::class, 'monitoringLicenseList'])->name('app.ltm.monitoringLicenseList');
        Route::post('/monitoring/training-list', [LTMController::class, 'monitoringTrainingList'])->name('app.ltm.monitoringTrainingList');
        Route::post('/monitoring/medical-list', [LTMController::class, 'monitoringMedicalList'])->name('app.ltm.monitoringMedicalList');
        Route::post('/monitoring/qualification-list', [LTMController::class, 'monitoringQualificationList'])->name('app.ltm.monitoringQualificationList');
        Route::post('/monitoring/ground-training-list', [LTMController::class, 'monitoringGroundTrainingList'])->name('app.ltm.monitoringGroundTrainingList');
        
    });
    
    Route::group(['prefix' => 'library'], function () {
        Route::get('/car', [FDTLController::class, 'index'])->name('app.library.car');
        Route::get('/fsdms', [FDTLController::class, 'fsdms'])->name('app.library.fsdms');
        Route::get('/generic', [FDTLController::class, 'generic'])->name('app.library.generic');
    });
    
    Route::group(['prefix' => 'sfa'], function () {

        Route::get('/', [SfaController::class, 'index'])->name('app.sfa');
        Route::get('/generate', [SfaController::class, 'generate'])->name('app.sfa.generate');
        Route::post('/generate', [SfaController::class, 'generateSfa'])->name('app.sfa.generate');
        Route::post('/list', [SfaController::class, 'list'])->name('app.sfa.list');
        Route::get('/view/{id}', [SfaController::class, 'sfaView'])->name('app.sfa.view');
        Route::get('/delete/{id}', [SfaController::class, 'sfaDelete'])->name('app.sfa.deleted');
        Route::get('/download/{id}', [SfaController::class, 'downloadSfaReport'])->name('app.sfa.download');
        Route::post('/user/list', [SfaController::class, 'getUserSfaList'])->name('app.user.sfa.list');
    });
    
   
    
    Route::group(['prefix' => 'receipt-dispatch'], function () {
        Route::get('/receipt', [ReceiveDispatchController::class, 'receiveIndex'])->name('app.receive');
        Route::post('receipt/list', [ReceiveDispatchController::class, 'receiveList'])->name('app.receive.list');
        Route::get('/receipt/create', [ReceiveDispatchController::class, 'receiveAdd'])->name('app.receive.add');
        Route::post('receipt/store', [ReceiveDispatchController::class, 'receiveStore'])->name('app.receive.store');
        Route::get('receipt/edit/{id}', [ReceiveDispatchController::class, 'receiveEdit'])->name('app.receive.edit');
        Route::get('receipt/destroy/{id}', [ReceiveDispatchController::class, 'receiveDestroy'])->name('app.receive.destroy');
        
        
        Route::get('/receipt/bill', [ReceiveDispatchController::class, 'receiveBillIndex'])->name('app.bill');
        Route::post('bill/list', [ReceiveDispatchController::class, 'receiptBillList'])->name('app.bill.list');
        
        Route::get('/receipt/bill/{id}', [ReceiveDispatchController::class, 'receiveBill'])->name('app.receive.bill');
        Route::post('receipt/store/bill', [ReceiveDispatchController::class, 'receiveStoreBill'])->name('app.receive.store.bill');
        Route::post('receipt/bill/list', [ReceiveDispatchController::class, 'receiveBillList'])->name('app.receive.bill.list');
        Route::get('receipt/bill/edit/{id}', [ReceiveDispatchController::class, 'receiveBillEdit'])->name('app.receive.bill.edit');
        Route::get('receipt/bill/destroy/{id}', [ReceiveDispatchController::class, 'receiveBillDestroy'])->name('app.receive.bill.destroy');
        
        Route::get('/receipt/bill/flying-verify-logs/{receipt_id}/{bill_id}', [ReceiveDispatchController::class, 'flyingVerifyLogs'])->name('app.receive.flyingVerifyLogs');
        Route::post('get-flying-verify-logs', [ReceiveDispatchController::class, 'getFlyingLogs'])->name('app.receive.getFlyingLogs');
        Route::post('flying-verify-logs-store', [ReceiveDispatchController::class, 'flyingLogsStore'])->name('app.receive.FlyingLogsStore');
        
        Route::post('receipt/check-file', [ReceiveDispatchController::class, 'checkFile'])->name('app.receive.check.file');
        Route::post('receipt/unverify', [ReceiveDispatchController::class, 'receiptUnverify'])->name('app.receive.unverify');
        
        Route::post('receipt/file-store', [ReceiveDispatchController::class, 'fileStore'])->name('app.receive.fileStore');
        
        Route::get('/dispatch', [ReceiveDispatchController::class, 'dispatchIndex'])->name('app.dispatch');
        Route::post('dispatch/list', [ReceiveDispatchController::class, 'dispatchList'])->name('app.dispatch.list');
        Route::get('/dispatch/create', [ReceiveDispatchController::class, 'dispatchAdd'])->name('app.dispatch.add');
        Route::post('dispatch/store', [ReceiveDispatchController::class, 'dispatchStore'])->name('app.dispatch.store');
        Route::get('dispatch/edit/{id}', [ReceiveDispatchController::class, 'dispatchEdit'])->name('app.dispatch.edit');
        Route::get('dispatch/destroy/{id}', [ReceiveDispatchController::class, 'dispatchDestroy'])->name('app.dispatch.destroy');
        Route::post('get-assign-ticket-form', [ReceiveDispatchController::class, 'getStampTicketForm'])->name('app.dispatch.getStampTicketForm');
        Route::post('assign-tickets', [ReceiveDispatchController::class, 'assignTickets'])->name('app.dispatch.assignTickets');
        
        Route::get('/dispatch/bill/{id}', [ReceiveDispatchController::class, 'dispatchBill'])->name('app.dispatch.bill');
        
        Route::get('/receive/leave', [ReceiveDispatchController::class, 'leaveIndex'])->name('app.receive.leave');
        Route::post('/receiveleave/list', [ReceiveDispatchController::class, 'leaveList'])->name('app.receive.leave.list');
        Route::get('/receive/leave/create', [ReceiveDispatchController::class, 'leaveAdd'])->name('app.receive.leave.add');
        Route::post('/receive/leave/store', [ReceiveDispatchController::class, 'leaveStore'])->name('app.receive.leave.store');
        Route::get('/receive/leave/edit/{id}', [ReceiveDispatchController::class, 'leaveEdit'])->name('app.receive.leave.edit');
        Route::get('/receive/leave/destroy/{id}', [ReceiveDispatchController::class, 'leaveDestroy'])->name('app.receive.leave.destroy');
        
        // Route::post('/preview', [ReceiveDispatchController::class, 'dispatchBill'])->name('app.sfa.preview');
    });
    
    Route::group(['prefix' => 'reports'], function () {
        Route::get('pilot-ground-training', [ReportController::class, 'pilotGroundTraining'])->name('app.reports.pilotGroundTraining');
        Route::get('pilot-ground-training-print/{date?}/{aircraft?}', [ReportController::class, 'pilotGroundTrainingPrint'])->name('app.reports.pilotGroundTrainingPrint');

        Route::get('pilot-flying-currency', [ReportController::class, 'pilotFlyingCurrency'])->name('app.reports.pilotFlyingCurrency');
        Route::get('pilot-flying-currency-print/{date?}/{aircraft?}/{report_type?}', [ReportController::class, 'pilotFlyingCurrencyPrint'])->name('app.reports.pilotFlyingCurrencyPrint');
        Route::get('flying-test-details-print/{date?}', [ReportController::class, 'FlyingTestDetailsPrint'])->name('app.reports.FlyingTestDetailsPrint');
        Route::get('training-and-checks-print/{date?}/{aircraft?}', [ReportController::class, 'trainingChecksPrint'])->name('app.reports.trainingChecksPrint');
        Route::get('aircraft-wise-summary-print/{from_date?}/{to_date?}', [ReportController::class, 'printAircraftWiseSummary'])->name('app.reports.printAircraftWiseSummary');
        
        Route::get('vip-recency', [ReportController::class, 'vipRecency'])->name('app.reports.vipRecency');
        Route::get('vip-recency-print/{date?}/{aircraft_type?}', [ReportController::class, 'printVipRecency'])->name('app.reports.printVipRecency');
    });
    
    Route::prefix('/states')->group(function () {
        Route::get('/', [StateController::class, 'index'])->name('app.states');
        Route::post('list', [StateController::class, 'list'])->name('app.states.list');
        Route::post('store', [StateController::class, 'store'])->name('app.states.store');
        Route::get('edit/{id}', [StateController::class, 'edit'])->name('app.states.edit');
        Route::post('delete', [StateController::class, 'delete'])->name('app.states.delete');

    });

    Route::prefix('/cities')->group(function () {
        Route::get('/', [CityController::class, 'index'])->name('app.cities');
        Route::post('list', [CityController::class, 'list'])->name('app.cities.list');
        Route::post('store', [CityController::class, 'store'])->name('app.cities.store');
        Route::get('edit/{id}', [CityController::class, 'edit'])->name('app.cities.edit');
        Route::post('delete', [CityController::class, 'delete'])->name('app.cities.delete');

    });

    Route::prefix('manage/cvr')->group(function () {
        Route::get('/', [CvrFdrController::class, 'cvr'])->name('app.cvr');
        Route::post('list', [CvrFdrController::class, 'list_cvr'])->name('app.cvr.list');
        Route::post('store', [CvrFdrController::class, 'store_cvr'])->name('app.cvr.store');
        Route::get('edit/{id}', [CvrFdrController::class, 'edit_cvr'])->name('app.cvr.edit');
        Route::get('destroy/{id}', [CvrFdrController::class, 'destroy_cvr'])->name('app.cvr.destroy');
    });

    Route::prefix('manage/fdr')->group(function () {
        Route::get('/', [CvrFdrController::class, 'fdr'])->name('app.fdr');
        Route::post('list', [CvrFdrController::class, 'list_fdr'])->name('app.fdr.list');
        Route::post('store', [CvrFdrController::class, 'store_fdr'])->name('app.fdr.store');
        Route::get('edit/{id}', [CvrFdrController::class, 'edit_fdr'])->name('app.fdr.edit');
        Route::get('destroy/{id}', [CvrFdrController::class, 'destroy_fdr'])->name('app.fdr.destroy');
    });
    
    Route::prefix('file')->group(function () {
        Route::get('/', [FilesController::class, 'index'])->name('app.file');
        Route::post('list', [FilesController::class, 'list'])->name('app.file.list');
        Route::post('store', [FilesController::class, 'store'])->name('app.file.store');
        Route::get('edit/{id}', [FilesController::class, 'edit'])->name('app.file.edit');
        Route::get('delete/{id}', [FilesController::class, 'delete'])->name('app.file.delete');

    });

    Route::prefix('load-trim')->group(function () {
        Route::get('/', [LoadTrimController::class, 'index'])->name('app.load.trim');
        Route::get('/add', [LoadTrimController::class, 'apply'])->name('app.load.trim.add');
        Route::post('list', [LoadTrimController::class, 'list'])->name('app.load.trim.list');
        Route::post('store', [LoadTrimController::class, 'store'])->name('app.load.trim.store');
        Route::get('edit/{id}', [LoadTrimController::class, 'edit'])->name('app.load.trim.edit');
        Route::post('update/{id}', [LoadTrimController::class, 'update'])->name('app.load.trim.update');
        Route::get('cancel/{id}', [LoadTrimController::class, 'cancelled'])->name('app.load.trim.cancelled');

    });
    
    Route::prefix('my-leave')->group(function () {
        Route::get('/', [MyLeaveController::class, 'index'])->name('app.my.leave');
        Route::get('/apply', [MyLeaveController::class, 'apply'])->name('app.my.leave.apply');
        Route::post('list', [MyLeaveController::class, 'list'])->name('app.my.leave.list');
        Route::post('store', [MyLeaveController::class, 'store'])->name('app.my.leave.store');
        Route::get('edit/{id}', [MyLeaveController::class, 'edit'])->name('app.my.leave.edit');
        Route::post('update/{id}', [MyLeaveController::class, 'update'])->name('app.my.leave.update');
        Route::get('cancel/{id}', [MyLeaveController::class, 'cancelled'])->name('app.my.leave.cancelled');

    });

    Route::prefix('stamp-tickets')->group(function () {
        Route::get('/', [StampticketController::class, 'index'])->name('app.stamp_ticket');
        Route::post('list', [StampticketController::class, 'list'])->name('app.stamp_ticket.list');
        Route::post('store', [StampticketController::class, 'store'])->name('app.stamp_ticket.store');
        Route::get('edit/{id}', [StampticketController::class, 'edit'])->name('app.stamp_ticket.edit');
        Route::get('delete/{id}', [StampticketController::class, 'delete'])->name('app.stamp_ticket.delete');
        Route::post('assign', [StampticketController::class, 'assign'])->name('app.stamp_ticket.assign');
        Route::post('get-add-ticket-form', [StampticketController::class, 'getAddTicketForm'])->name('app.stamp_ticket.getAddTicketForm');
        Route::post('get-assign-ticket-form', [StampticketController::class, 'getAssignTicketForm'])->name('app.stamp_ticket.getAssignTicketForm');

    });
    Route::prefix('contract')->group(function () {
        Route::get('/', [ContractController::class, 'index'])->name('app.contract');
        Route::post('list', [ContractController::class, 'list'])->name('app.contract.list');
        Route::post('store', [ContractController::class, 'store'])->name('app.contract.store');
        Route::get('edit/{id}', [ContractController::class, 'edit'])->name('app.contract.edit');
        Route::get('delete/{id}', [ContractController::class, 'delete'])->name('app.contract.delete');
    });
});

Route::group(['prefix' => 'users','middleware' => ['auth','timezone']], function () {
    Route::get('/home', [HomeController::class, 'userIndex'])->name('user.home');
    Route::get('/dashboard',[HomeController::class, 'userIndex'])->name('admin.dashboard');
    Route::get('/profile',[UserController::class, 'profile'])->name('user.profile');
    Route::get('/password', [UserController::class, 'password'])->name('user.password');
    Route::get('/shortie', [FlyingLogController::class, 'index'])->name('user.flying.shortie');
    Route::get('/my-shortie', [FlyingLogController::class, 'myShortie'])->name('user.flying.myshortie');
    Route::post('/my-shortie', [FlyingLogController::class, 'myShortieList'])->name('user.flying.myshortie');
    Route::get('/flying/verify/{id}',[FlyingLogController::class, 'verify'])->name('user.flying.verify');
    Route::post('/report/get', [FDTLController::class, 'getReport'])->name('user.get.fdtl.report');
    Route::get('/report/print/{id?}/{front_date?}/{to_date?}', [FDTLController::class, 'printReport'])->name('user.print.fdtl.report');
    Route::get('/report/{id}', [FDTLController::class, 'report'])->name('user.fdtl.report');

    Route::get('/statistics', [FlyingLogController::class, 'statistics'])->name('user.flying.statistics');
    Route::post('/statistics/list', [FlyingLogController::class, 'statisticsList'])->name('user.flying.statistics.list');
    Route::get('/statistics/print/{from_date?}/{to_date?}/{aircraft?}/{flying_type?}', [FlyingLogController::class, 'statisticsPrint'])->name('user.flying.statistics.print');
        
    Route::get('/sfa/generate', [SfaController::class, 'generate'])->name('user.sfa.generate');
    Route::post('/sfa/generate', [SfaController::class, 'generateSfa'])->name('user.sfa.generate');
    Route::get('/sfa/list', [SfaController::class, 'index'])->name('user.sfa.list');
    Route::post('/sfa/list', [SfaController::class, 'getUserSfaList'])->name('user.sfa.list');
    Route::get('/sfa/view/{id}', [SfaController::class, 'sfaView'])->name('user.sfa.view');
    Route::get('/sfa/delete/{id}', [SfaController::class, 'sfaDelete'])->name('user.sfa.deleted');
    Route::get('/sfa/verify/{id}',[SfaController::class, 'verify'])->name('user.sfa.verify');
    Route::get('/sfa/approved/{id}',[SfaController::class, 'approved'])->name('user.sfa.approved');
    Route::get('/sfa/download/{id}', [SfaController::class, 'downloadSfaReport'])->name('user.sfa.download');

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


});
