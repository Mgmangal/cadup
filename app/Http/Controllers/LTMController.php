<?php
namespace App\Http\Controllers;

use App\Models\AirCraft;
use App\Models\Master;
use App\Models\MasterAssign;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PilotLicense;
use App\Models\PilotMedical;
use App\Models\PilotTraining;
use App\Models\PilotQualification;
use App\Models\PilotGroundTraining; 
use App\Models\UserCertificate; 
use App\Models\Leave;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class LTMController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['permission:Staff Add|Staff Edit|Staff Delete|Staff View']);
    }
    public function index()
    {
        return view('ltm.index');
    }

    public function list(Request $request)
    {
        $column=['id','id','emp_id','name','email','phone','designation','status','created_at','id','id'];
        $users=User::with('designation')->where('designation','1')->where('is_delete','0')->where('status','active');

        if (isset($_POST['parent_id'])) {
            $users->where('parent_id', '=', $_POST['parent_id']);
		}

        $total_row=$users->count();
        if (!empty($_POST['search']['value'])) {
            $search = $_POST['search']['value'];
            $users->where(function ($q) use($search){
                $q->where('salutation', 'LIKE', '%' . $search . '%');
                $q->orWhere('name', 'LIKE', '%' . $search . '%');
                $q->orWhere('email', 'LIKE', '%' . $search . '%');
                $q->orWhere('phone', 'LIKE', '%' . $search . '%');
            });
		}

		if (isset($_POST['order'])) {
            $users->orderBy($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else {
            $users->orderBy('id', 'desc');
        }
		$filter_row =$users->count();
        if (isset($_POST["length"]) && $_POST["length"] != -1) {
            $users->skip($_POST["start"])->take($_POST["length"]);
		}
        $result =$users->get();
        $data = array();
		foreach($result as $key => $value) {
            $action  = '<a href="'.route('app.pilot.licenses', $value->id).'" class="btn btn-primary btn-sm m-1">View</a>';
            //$action .= '<a href="'.route('app.pilot.licenses', $value->id).'" class="btn btn-success btn-sm m-1">License Traning & Medical</a>';
            $status='<select class="form-control" onchange="changeStatus('.$value->id.',this.value);">';
                $status.='<option '.($value->status=='active'?'selected':'').' value="active">Active</option>';
                $status.='<option '.($value->status=='inactive'?'selected':'').' value="inactive">Inactive</option>';
            $status.='</select>';
            $sub_array = array();
			$sub_array[] = ++$key;
            $sub_array[] = $value->salutation.' '.$value->name;
            $sub_array[] = checkUserLapsedCertificate($value->id,'license');//checkCrewLicenses($value->id,getCetificateIds($value->id,'license'));
            $sub_array[] = checkUserLapsedCertificate($value->id,'training');//checkCrewTrainings($value->id,getCetificateIds($value->id,'training'));
            $sub_array[] = checkUserLapsedCertificate($value->id,'medical');//checkCrewMedicals($value->id,getCetificateIds($value->id,'medical'));
            $sub_array[] = $action;
            $data[] = $sub_array;
        }
        $output = array(
			"draw"   =>  intval($_POST["draw"]),
			"recordsTotal"   =>  $total_row,
			"recordsFiltered"  =>  $filter_row,
			"data"   =>  $data
		);
		echo json_encode($output);
    }
    
    public function monitoring()
    {
        return view('ltm.monitoring');
    }
    
    public function monitoringLicenseList(Request $request)
    {
        $column=['pilot_licenses.id','pilot_licenses.id','users.salutation','users.name','masters.name','pilot_licenses.renewed_on','pilot_licenses.extended_date','pilot_licenses.next_due','pilot_licenses.status','pilot_licenses.created_at','pilot_licenses.id','pilot_licenses.id'];
      
        $users = DB::table('user_certificates')
            ->leftjoin('pilot_licenses', 'user_certificates.master_id', '=', 'pilot_licenses.license_id')
            ->leftjoin('masters', 'pilot_licenses.license_id', '=', 'masters.id')
            ->leftjoin('users', 'user_certificates.user_id', '=', 'users.id')
            ->select(
                'masters.id as master_id', 
                'masters.name as name',
                'users.salutation', 
                'users.id as user_id', 
                'users.name as user_name', 
                'pilot_licenses.id',
                'pilot_licenses.extended_date',
                'pilot_licenses.next_due',
                'pilot_licenses.status',
                'pilot_licenses.created_at',
                'pilot_licenses.renewed_on'
            )
            ->where('user_certificates.certificate_type', 'license');
            $users->groupBy('pilot_licenses.license_id');
            
        if(!empty($_POST['user_id']))
        {
            $users->where('user_certificates.user_id',$_POST['user_id']);
        }

        $dates=date('Y-m-d');
        if(!empty($_POST['dates']))
        {
            $dates=$_POST['dates'];
        }
            
        $total_row=$users->get()->count();
        if (!empty($_POST['search']['value'])) {
            $search = $_POST['search']['value'];
            $users->where(function ($q) use($search){
                // $q->where('users.salutation', 'LIKE', '%' . $search . '%');
                // $q->orWhere('users.name', 'LIKE', '%' . $search . '%');
                // $q->orWhere('license.name', 'LIKE', '%' . $search . '%');
            });
		}
		
		if (isset($_POST['order'])) {
            $users->orderBy($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else {
            $users->orderBy('pilot_licenses.id', 'desc');
        }
        
		$filter_row =$users->get()->count();
        if (isset($_POST["length"]) && $_POST["length"] != -1) {
            $users->skip($_POST["start"])->take($_POST["length"]);
		}
        $result =$users->get();
        $data = array();
		foreach($result as $key => $value) {
           
            $action  = '<a href="javascript:void(0);" onclick="showData('.$value->id.',\'license\')" class="btn btn-primary btn-sm m-1">View</a>';
            //$action  .= '<a href="'.route('user.certificate.licence.log', $value->license->id).'" class="btn btn-warning btn-sm m-1">Log</a>';
            
            $action  = '<a href="'.route('app.pilot.licenses', $value->user_id).'" class="btn btn-primary btn-sm m-1">View</a>';
            
            $status='Active';
            $sub_array = array();
			$sub_array[] = ++$key;
            $sub_array[] = $value->salutation.' '.$value->user_name;
            $sub_array[] = $value->name;
            $sub_array[] = '<b>' . $value->renewed_on . '</b>';
            $sub_array[] = $value->extended_date;
            $sub_array[] = $value->next_due;
            $next_due='';
            if(!empty($value->next_due)&& strtotime($value->next_due) > strtotime($dates))
            {
                $day=\Carbon\Carbon::parse( $dates )->diffInDays($value->next_due );  
                $bt='style="background-color: #1e24dd;color: white;"';
                if($day<=60)
                {
                    $bt='style="background-color: yellow;color: #161515;"';
                }
                if($day<=30)
                {
                   $bt='style="background-color: orange;color: #161515;"';
                }
                $next_due='<button '.$bt.' type="button" class="btn btn-sm position-relative">'.$day.'<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">Valid<span class="visually-hidden">unread messages</span></span></button>';
            }else{
                $next_due='<span class="btn btn-sm btn-danger">Lapsed</span>';
            }
            
            $sub_array[] = $next_due;
            $sub_array[] = $status;
            $sub_array[] = $action;
            $data[] = $sub_array;
        }
        $output = array(
			"draw"   =>  intval($_POST["draw"]),
			"recordsTotal"   =>  $total_row,
			"recordsFiltered"  =>  $filter_row,
			"data"   =>  $data
		);
		echo json_encode($output);
    }
    
    public function monitoringTrainingList(Request $request)
    {
        $column=['user_certificates.id','user_certificates.id','users.salutation','masters.name','user_certificates.id','user_certificates.id','user_certificates.id','user_certificates.id','user_certificates.id','user_certificates.id','user_certificates.id','user_certificates.id'];
        
        $users = DB::table('user_certificates')
            //->leftjoin('pilot_trainings', 'user_certificates.master_id', '=', 'pilot_trainings.training_id')
            ->leftjoin('masters', 'user_certificates.master_id', '=', 'masters.id')
            ->leftjoin('users', 'user_certificates.user_id', '=', 'users.id')
            ->select(
                'masters.id as master_id', 
                'masters.name as name',
                'users.salutation', 
                'users.id as user_id', 
                'users.name as user_name'
                //'pilot_trainings.id',
                ///'pilot_trainings.extended_date',
                //'pilot_trainings.next_due',
                //'pilot_trainings.status',
                //'pilot_trainings.created_at',
                //'pilot_trainings.renewed_on'
            )
            ->where('user_certificates.certificate_type', 'training');
            //->groupBy('pilot_trainings.training_id')
            //->orderBy('pilot_trainings.id', 'DESC');
        
        
        if(!empty($_POST['user_id']))
        {
            $users->where('users.user_id',$_POST['user_id']);
        }

        $dates=date('Y-m-d');
        if(!empty($_POST['dates']))
        {
            $dates=$_POST['dates'];
        }
        
        $total_row=$users->get()->count();
        if (!empty($_POST['search']['value'])) {
            $search = $_POST['search']['value'];
            $users->where(function ($q) use($search){
                // $q->where('users.salutation', 'LIKE', '%' . $search . '%');
                // $q->orWhere('users.name', 'LIKE', '%' . $search . '%');
                // $q->orWhere('training.name', 'LIKE', '%' . $search . '%');
            });    
		}

		if (isset($_POST['order'])) {
            $users->orderBy($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else {
            $users->orderBy('user_certificates.id', 'desc');
        }
		$filter_row =$users->get()->count();
        if (isset($_POST["length"]) && $_POST["length"] != -1) {
            $users->skip($_POST["start"])->take($_POST["length"]);
		}
        $result =$users->get();
        $data = array();
		foreach($result as $key => $value) {
		    $action  = '';
		    $d=PilotTraining::where('user_id',$value->user_id)->where('training_id',$value->master_id)->orderBy('id','DESC')->first();
            if(getUserType()=='user')
            {   
                if(!empty($d))
                {
                    $action  .= '<a href="javascript:void(0);" onclick="showData('.$value->id.',\'training\')" class="btn btn-primary btn-sm m-1">View</a>';
                }
                $action  .= '<a href="'.route('user.certificate.trainings.log', $value->master_id).'" class="btn btn-warning btn-sm m-1">Log</a>';
            }else{
                $action  = '<a href="'.route('app.pilot.licenses', $value->user_id).'" class="btn btn-primary btn-sm m-1">View</a>';
            }
            
            $status='Active';
            $sub_array = array();
			$sub_array[] = ++$key;
            $sub_array[] = $value->salutation.' '.$value->user_name;
            $sub_array[] = !empty($d)?$d->name:'';
            $sub_array[] = !empty($d)?'<b>' . $d->renewed_on . '</b>':'';
            $sub_array[] = !empty($d)?$d->extended_date:'';
            $sub_array[] = !empty($d)?$d->next_due:'';
            if(!empty($d)&&strtotime($d->next_due) > strtotime($dates))
            {
                $day=\Carbon\Carbon::parse( $dates )->diffInDays($d->next_due );  
                $bt='style="background-color: #1e24dd;color: white;"';
                if($day<=60)
                {
                    $bt='style="background-color: yellow;color: #161515;"';
                }
                if($day<=30)
                {
                   $bt='style="background-color: orange;color: #161515;"';
                }
                $next_due='<button '.$bt.' type="button" class="btn btn-sm position-relative">'.$day.'<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">Valid<span class="visually-hidden">unread messages</span></span></button>';
            }else{
                $next_due='<span class="btn btn-sm btn-danger">Lapsed</span>';
            }
            
            $sub_array[] = $next_due; // strtotime($value->next_due) > strtotime($dates)?'<button type="button" class="btn btn-primary btn-sm position-relative">'.\Carbon\Carbon::parse($dates)->diffInDays($value->next_due ).'<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">Valid<span class="visually-hidden">unread messages</span></span></button>':'<span class="btn btn-sm btn-danger">Lapsed</span>';
            $sub_array[] = $status;
            $sub_array[] = $action;
            $data[] = $sub_array;
        }
        $output = array(
			"draw"   =>  intval($_POST["draw"]),
			"recordsTotal"   =>  $total_row,
			"recordsFiltered"  =>  $filter_row,
			"data"   =>  $data
		);
		echo json_encode($output);
    }
    
    public function monitoringMedicalList(Request $request)
    {
        //$column=['pilot_medicals.id','pilot_medicals.id','users.salutation','users.name','masters.name','pilot_medicals.planned_renewal_date','pilot_medicals.extended_date','pilot_medicals.next_due','pilot_medicals.status','pilot_medicals.created_at','pilot_medicals.id','pilot_medicals.id'];
        $column=['user_certificates.id','user_certificates.id','users.salutation','users.name','user_certificates.id','user_certificates.id','user_certificates.id','user_certificates.id','user_certificates.id','user_certificates.id','user_certificates.id','user_certificates.id'];
       
        $users = DB::table('user_certificates')
            //->leftjoin('pilot_medicals', 'user_certificates.master_id', '=', 'pilot_medicals.medical_id')
            ->leftjoin('masters', 'user_certificates.master_id', '=', 'masters.id')
            ->leftjoin('users', 'user_certificates.user_id', '=', 'users.id')
            ->select(
                'masters.id as master_id', 
                'masters.name as name',
                'users.salutation', 
                'users.id as user_id', 
                'users.name as user_name' 
                //'pilot_medicals.id',
                //'pilot_medicals.extended_date',
                //'pilot_medicals.next_due',
                //'pilot_medicals.status',
                //'pilot_medicals.created_at',
                //'pilot_medicals.planned_renewal_date'
            )
            ->where('user_certificates.certificate_type', 'medical');
            //->groupBy('pilot_medicals.medical_id')
            //->orderBy('pilot_medicals.id', 'DESC');
            
            
        if(!empty($_POST['user_id']))
        {
            $users->where('users.user_id',$_POST['user_id']);
        }
        
        $dates=date('Y-m-d');
        if(!empty($_POST['dates']))
        {
            $dates=$_POST['dates'];
        }
        
        $total_row=$users->get()->count();
        if (!empty($_POST['search']['value'])) {
            $search = $_POST['search']['value'];
            $users->where(function ($q) use($search){
                // $q->where('users.salutation', 'LIKE', '%' . $search . '%');
                // $q->orWhere('users.name', 'LIKE', '%' . $search . '%');
                // $q->orWhere('medical.name', 'LIKE', '%' . $search . '%');
            });
		}
		if (isset($_POST['order'])) {
            $users->orderBy($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else {
            $users->orderBy('user_certificates.id', 'desc');
        }
		$filter_row =$users->get()->count();
        if (isset($_POST["length"]) && $_POST["length"] != -1) {
            $users->skip($_POST["start"])->take($_POST["length"]);
		}
        $result =$users->get();
        $data = array();
		foreach($result as $key => $value) {
		    $action  ='';
           $d=PilotMedical::where('user_id',$value->user_id)->where('medical_id',$value->master_id)->orderBy('id','DESC')->first();
            if(getUserType()=='user')
            {
                if(!empty($d))
                {
                $action  .= '<a href="javascript:void(0);" onclick="showData('.$d->id.',\'medical\')" class="btn btn-primary btn-sm m-1">View</a>';
                }
                $action  .= '<a href="'.route('user.certificate.medicals.log', $value->master_id).'" class="btn btn-warning btn-sm m-1">Log</a>';
            }else{
                $action  = '<a href="'.route('app.pilot.licenses', $value->user_id).'" class="btn btn-primary btn-sm m-1">View</a>';
            }
            $status='Active';
            $sub_array = array();
			$sub_array[] = ++$key;
            $sub_array[] = $value->salutation.' '.$value->user_name;
            $sub_array[] = !empty($d)?$d->name:'';
            $sub_array[] = !empty($d)?'<b>' . $d->planned_renewal_date . '</b>':'';
            $sub_array[] = !empty($d)?$d->extended_date:'';
            $sub_array[] = !empty($d)?$d->next_due:'';
            if(!empty($d)&&strtotime($d->next_due) > strtotime($dates))
            {
                $day=\Carbon\Carbon::parse( $dates )->diffInDays($d->next_due );  
                $bt='style="background-color: #1e24dd;color: white;"';
                if($day<=60)
                {
                    $bt='style="background-color: yellow;color: #161515;"';
                }
                if($day<=30)
                {
                   $bt='style="background-color: orange;color: #161515;"';
                }
                $next_due='<button '.$bt.' type="button" class="btn btn-sm position-relative">'.$day.'<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">Valid<span class="visually-hidden">unread messages</span></span></button>';
            }else{
                $next_due='<span class="btn btn-sm btn-danger">Lapsed</span>';
            }
            
            $sub_array[] = $next_due; // strtotime($value->next_due) > strtotime($dates)?'<button type="button" class="btn btn-primary btn-sm position-relative">'.\Carbon\Carbon::parse($dates)->diffInDays($value->next_due ).'<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">Valid<span class="visually-hidden">unread messages</span></span></button>':'<span class="btn btn-sm btn-danger">Lapsed</span>';
            $sub_array[] = $status;
            $sub_array[] = $action;
            $data[] = $sub_array;
        }
        $output = array(
			"draw"   =>  intval($_POST["draw"]),
			"recordsTotal"   =>  $total_row,
			"recordsFiltered"  =>  $filter_row,
			"data"   =>  $data
		);
		echo json_encode($output);
    }
    
    public function monitoringQualificationList(Request $request)
    {
        //$column=['pilot_qualifications.id','pilot_qualifications.id','users.salutation','users.name','qualification.name','pilot_qualifications.renewed_on','pilot_qualifications.extended_date','pilot_qualifications.next_due','pilot_qualifications.status','pilot_qualifications.created_at','pilot_qualifications.id','pilot_qualifications.id'];
        $column=['user_certificates.id','user_certificates.id','users.salutation','users.name','user_certificates.id','user_certificates.id','user_certificates.id','user_certificates.id','user_certificates.id','user_certificates.id','user_certificates.id','user_certificates.id'];
        
         $users = DB::table('user_certificates')
            //->leftjoin('pilot_qualifications', 'user_certificates.master_id', '=', 'pilot_qualifications.qualification_id')
            ->leftjoin('masters', 'user_certificates.master_id', '=', 'masters.id')
            ->leftjoin('users', 'user_certificates.user_id', '=', 'users.id')
            ->select(
                'masters.id as master_id', 
                'masters.name as name',
                'users.salutation', 
                'users.id as user_id', 
                'users.name as user_name' 
                //'pilot_qualifications.id',
                //'pilot_qualifications.extended_date',
                //'pilot_qualifications.next_due',
                //'pilot_qualifications.status',
                //'pilot_qualifications.created_at',
                //'pilot_qualifications.renewed_on'
            )
            ->where('user_certificates.certificate_type', 'qualification');
            //->groupBy('pilot_qualifications.qualification_id')
            //->orderBy('pilot_qualifications.id', 'DESC');
        
        if(!empty($_POST['user_id']))
        {
            $users->where('users.user_id',$_POST['user_id']);
        }
        $dates=date('Y-m-d');
        if(!empty($_POST['dates']))
        {
            $dates=$_POST['dates'];
        }
        
        $total_row=$users->get()->count();
        if (!empty($_POST['search']['value'])) {
            $search = $_POST['search']['value'];
            $users->where(function ($q) use($search){
                // $q->where('users.salutation', 'LIKE', '%' . $search . '%');
                // $q->orWhere('users.name', 'LIKE', '%' . $search . '%');
                // $q->orWhere('qualification.name', 'LIKE', '%' . $search . '%');
            });    
		}

		if (isset($_POST['order'])) {
            $users->orderBy($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else {
            $users->orderBy('user_certificates.id', 'desc');
        }
		$filter_row =$users->get()->count();
        if (isset($_POST["length"]) && $_POST["length"] != -1) {
            $users->skip($_POST["start"])->take($_POST["length"]);
		}
        $result =$users->get();
        $data = array();
		foreach($result as $key => $value) {
           $action  ='';
            $d=PilotQualification::where('user_id',$value->user_id)->where('qualification_id',$value->master_id)->orderBy('id','DESC')->first();
            if(getUserType()=='user')
            {   
                if(!empty($d))
                {
                $action  .= '<a href="javascript:void(0);" onclick="showData('.$d->id.',\'qualification\')" class="btn btn-primary btn-sm m-1">View</a>';
                }
                $action  .= '<a href="'.route('user.certificate.qualifications.log', $value->master_id).'" class="btn btn-warning btn-sm m-1">Log</a>';
            }else{
                $action  = '<a href="'.route('app.pilot.licenses', $value->user_id).'" class="btn btn-primary btn-sm m-1">View</a>';
            }
            $status='Active';
            $sub_array = array();
			$sub_array[] = ++$key;
            $sub_array[] = $value->salutation.' '.$value->user_name;
            $sub_array[] = !empty($d)?$d->name:'';
            $sub_array[] = !empty($d)?'<b>' . $d->renewed_on . '</b>':'';
            $sub_array[] = !empty($d)?$d->extended_date:'';
            $sub_array[] = !empty($d)?$d->next_due:'';
            if(!empty($d)&&strtotime($d->next_due) > strtotime($dates))
            {
                $day=\Carbon\Carbon::parse( $dates )->diffInDays($d->next_due );  
                $bt='style="background-color: #1e24dd;color: white;"';
                if($day<=60)
                {
                    $bt='style="background-color: yellow;color: #161515;"';
                }
                if($day<=30)
                {
                   $bt='style="background-color: orange;color: #161515;"';
                }
                $next_due='<button '.$bt.' type="button" class="btn btn-sm position-relative">'.$day.'<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">Valid<span class="visually-hidden">unread messages</span></span></button>';
            }else{
                $next_due='<span class="btn btn-sm btn-danger">Lapsed</span>';
            }
            
            $sub_array[] = $next_due; //strtotime($value->next_due) > strtotime($dates)?'<button type="button" class="btn btn-primary btn-sm position-relative">'.\Carbon\Carbon::parse($dates)->diffInDays($value->next_due ).'<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">Valid<span class="visually-hidden">unread messages</span></span></button>':'<span class="btn btn-sm btn-danger">Lapsed</span>';
            $sub_array[] = $status;
            $sub_array[] = $action;
            $data[] = $sub_array;
        }
        $output = array(
			"draw"   =>  intval($_POST["draw"]),
			"recordsTotal"   =>  $total_row,
			"recordsFiltered"  =>  $filter_row,
			"data"   =>  $data
		);
		echo json_encode($output);
    }
    
    public function monitoringGroundTrainingList(Request $request)
    {
        //$column=['pilot_ground_trainings.id','pilot_ground_trainings.id','users.salutation','users.name','masters.name','pilot_ground_trainings.renewed_on','pilot_ground_trainings.extended_date','pilot_ground_trainings.next_due','pilot_ground_trainings.status','pilot_ground_trainings.created_at','pilot_ground_trainings.id','pilot_ground_trainings.id'];
        $column=['user_certificates.id','user_certificates.id','users.salutation','users.name','user_certificates.id','user_certificates.id','user_certificates.id','user_certificates.id','user_certificates.id','user_certificates.id','user_certificates.id','user_certificates.id'];
        
        $users = DB::table('user_certificates')
            ->leftjoin('masters', 'user_certificates.master_id', '=', 'masters.id')
            ->leftjoin('users', 'user_certificates.user_id', '=', 'users.id')
            ->select(
                'masters.id as master_id', 
                'masters.name as name',
                'users.salutation', 
                'users.id as user_id', 
                'users.name as user_name' 
            )
            ->where('user_certificates.certificate_type', 'ground_training');
            
        if(!empty($_POST['user_id']))
        {
            $users->where('users.user_id',$_POST['user_id']);
        }
        $dates=date('Y-m-d');
        if(!empty($_POST['dates']))
        {
            $dates=$_POST['dates'];
        }
        
        $total_row=$users->get()->count();
        if (!empty($_POST['search']['value'])) {
            $search = $_POST['search']['value'];
            $users->where(function ($q) use($search){
                // $q->where('users.salutation', 'LIKE', '%' . $search . '%');
                // $q->orWhere('users.name', 'LIKE', '%' . $search . '%');
                // $q->orWhere('training.name', 'LIKE', '%' . $search . '%');
            });    
		}
 
		if (isset($_POST['order'])) {
            $users->orderBy($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else {
            $users->orderBy('user_certificates.id', 'desc');
        }
		$filter_row =$users->get()->count();
        if (isset($_POST["length"]) && $_POST["length"] != -1) {
            $users->skip($_POST["start"])->take($_POST["length"]);
		}
        $result =$users->get();
        $data = array();
		foreach($result as $key => $value) {
		    
            $d=PilotGroundTraining::where('user_id',$value->user_id)->where('training_id',$value->master_id)->orderBy('id','DESC')->first();
             $action  = '';
            if(getUserType()=='user')
            {   
                if(!empty($d))
                {
                $action  .= '<a href="javascript:void(0);" onclick="showData('.$d->id.',\'groundtraining\')" class="btn btn-primary btn-sm m-1">View</a>';
                }
                $action  .= '<a href="'.route('user.certificate.groundTrainings.log', $value->master_id).'" class="btn btn-warning btn-sm m-1">Log</a>';
            }else{
                $action  = '<a href="'.route('app.pilot.licenses', $value->user_id).'" class="btn btn-primary btn-sm m-1">View</a>';
            }
            $status='Active';
            $sub_array = array();
			$sub_array[] = ++$key;
            $sub_array[] = $value->salutation.' '.$value->user_name;
            $sub_array[] = $value->name;
            $sub_array[] = !empty($d)?'<b>' . $d->renewed_on . '</b>':'';
            $sub_array[] = !empty($d)?$d->extended_date:'';
            $sub_array[] = !empty($d)?$d->next_due:'';
            if(!empty($d)&&strtotime($d->next_due) > strtotime($dates))
            {
                $day=\Carbon\Carbon::parse( $dates )->diffInDays($d->next_due );  
                $bt='style="background-color: #1e24dd;color: white;"';
                if($day<=60)
                {
                    $bt='style="background-color: yellow;color: #161515;"';
                }
                if($day<=30)
                {
                   $bt='style="background-color: orange;color: #161515;"';
                }
                $next_due='<button '.$bt.' type="button" class="btn btn-sm position-relative">'.$day.'<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">Valid<span class="visually-hidden">unread messages</span></span></button>';
            }else{
                $next_due='<span class="btn btn-sm btn-danger">Lapsed</span>';
            }
            
            $sub_array[] = $next_due; //strtotime($value->next_due) > strtotime($dates)?'<button type="button" class="btn btn-primary btn-sm position-relative">'.\Carbon\Carbon::parse($dates)->diffInDays($value->next_due ).'<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">Valid<span class="visually-hidden">unread messages</span></span></button>':'<span class="btn btn-sm btn-danger">Lapsed</span>';
            $sub_array[] = $status;
            $sub_array[] = $action;
            $data[] = $sub_array;
        }
        $output = array(
			"draw"   =>  intval($_POST["draw"]),
			"recordsTotal"   =>  $total_row,
			"recordsFiltered"  =>  $filter_row,
			"data"   =>  $data
		);
		echo json_encode($output);
    }
    
    public function history()
    {
        return view('ltm.history');
    }
    
    public function historyLicenseList(Request $request)
    {
        $column=['id','users.salutation','masters.sub_type','masters.name','renewed_on','extended_date','next_due','id','status','id'];
        $users=PilotLicense::with(['user','license'])->where('id','>','0');

       
        $total_row=$users->count();
        if (!empty($_POST['search']['value'])) {
            $search = $_POST['search']['value'];
            $users->where(function ($q) use($search){
                // $q->where('users.salutation', 'LIKE', '%' . $search . '%');
                // $q->orWhere('masters.sub_type', 'LIKE', '%' . $search . '%');
                // $q->orWhere('masters.name', 'LIKE', '%' . $search . '%');
            });
		}

		if (isset($_POST['order'])) {
            $users->orderBy($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else {
            $users->orderBy('id', 'desc');
        }
		$filter_row =$users->count();
        if (isset($_POST["length"]) && $_POST["length"] != -1) {
            $users->skip($_POST["start"])->take($_POST["length"]);
		}
        $result =$users->get();
        
        $data = array();
		foreach($result as $key => $value) {
       
            $action  = '<a href="'.route('app.pilot.licenses', $value->user->id).'" class="btn btn-primary btn-sm m-1">View</a>';
            
            $sub_array = array();
			$sub_array[] = ++$key;
            $sub_array[] = $value->user->salutation.' '.$value->user->name;
            $sub_array[] = $value->license->sub_type;
            $sub_array[] = $value->license->name;
            $sub_array[] = $value->renewed_on;
            $sub_array[] = $value->extended_date;
            $sub_array[] = $value->next_due;
            $sub_array[] = $value->next_due;
            $sub_array[] = $value->status;
            $sub_array[] = $action;
            $data[] = $sub_array;
        }
        $output = array(
			"draw"   =>  intval($_POST["draw"]),
			"recordsTotal"   =>  $total_row,
			"recordsFiltered"  =>  $filter_row,
			"data"   =>  $data
		);
		echo json_encode($output);
    }
    
    public function historyTrainingList(Request $request)
    {
        $column=['id','id','emp_id','name','email','phone','designation','status','created_at','id','id'];
        $users=User::with('designation')->where('designation','1')->where('is_delete','0');

        if (isset($_POST['parent_id'])) {
            $users->where('parent_id', '=', $_POST['parent_id']);
		}

        $total_row=$users->count();
        if (!empty($_POST['search']['value'])) {
            $search = $_POST['search']['value'];
            $users->where(function ($q) use($search){
                $q->where('salutation', 'LIKE', '%' . $search . '%');
                $q->orWhere('name', 'LIKE', '%' . $search . '%');
                $q->orWhere('email', 'LIKE', '%' . $search . '%');
                $q->orWhere('phone', 'LIKE', '%' . $search . '%');
            });
		}

		if (isset($_POST['order'])) {
            $users->orderBy($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else {
            $users->orderBy('id', 'desc');
        }
		$filter_row =$users->count();
        if (isset($_POST["length"]) && $_POST["length"] != -1) {
            $users->skip($_POST["start"])->take($_POST["length"]);
		}
        $result =$users->get();
        $data = array();
		foreach($result as $key => $value) {
           
            $action  = '<a href="'.route('app.pilot.licenses', $value->id).'" class="btn btn-primary btn-sm m-1">View</a>';
            //$action .= '<a href="'.route('app.pilot.licenses', $value->id).'" class="btn btn-success btn-sm m-1">License Traning & Medical</a>';
            
            $status='<select class="form-control" style="width: auto;" onchange="changeStatus('.$value->id.',this.value);">';
                $status.='<option '.($value->status=='active'?'selected':'').' value="active">Active</option>';
                $status.='<option '.($value->status=='inactive'?'selected':'').' value="inactive">Inactive</option>';
            $status.='</select>';
            $sub_array = array();
			$sub_array[] = ++$key;
            $sub_array[] = $value->salutation.' '.$value->name;
            $sub_array[] = $this->checkLicense($value->id,$this->getCetificateIds($value->id,'license'));
            $sub_array[] = $this->checkTraining($value->id,$this->getCetificateIds($value->id,'training'));
            $sub_array[] = $this->checkMedical($value->id,$this->getCetificateIds($value->id,'medical'));
            $sub_array[] = $action;
            $data[] = $sub_array;
        }
        $output = array(
			"draw"   =>  intval($_POST["draw"]),
			"recordsTotal"   =>  $total_row,
			"recordsFiltered"  =>  $filter_row,
			"data"   =>  $data
		);
		echo json_encode($output);
    }
    
    public function historyMedicalList(Request $request)
    {
        $column=['id','id','emp_id','name','email','phone','designation','status','created_at','id','id'];
        $users=User::with('designation')->where('designation','1')->where('is_delete','0');

        if (isset($_POST['parent_id'])) {
            $users->where('parent_id', '=', $_POST['parent_id']);
		}

        $total_row=$users->count();
        if (!empty($_POST['search']['value'])) {
            $search = $_POST['search']['value'];
            $users->where(function ($q) use($search){
                $q->where('salutation', 'LIKE', '%' . $search . '%');
                $q->orWhere('name', 'LIKE', '%' . $search . '%');
                $q->orWhere('email', 'LIKE', '%' . $search . '%');
                $q->orWhere('phone', 'LIKE', '%' . $search . '%');
            });
		}

		if (isset($_POST['order'])) {
            $users->orderBy($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else {
            $users->orderBy('id', 'desc');
        }
		$filter_row =$users->count();
        if (isset($_POST["length"]) && $_POST["length"] != -1) {
            $users->skip($_POST["start"])->take($_POST["length"]);
		}
        $result =$users->get();
        $data = array();
		foreach($result as $key => $value) {
           
            $action  = '<a href="'.route('app.pilot.licenses', $value->user_id).'" class="btn btn-primary btn-sm m-1">View</a>';
            //$action .= '<a href="'.route('app.pilot.licenses', $value->id).'" class="btn btn-success btn-sm m-1">License Traning & Medical</a>';
            
            $status='<select class="form-control" onchange="changeStatus('.$value->id.',this.value);">';
                $status.='<option '.($value->status=='active'?'selected':'').' value="active">Active</option>';
                $status.='<option '.($value->status=='inactive'?'selected':'').' value="inactive">Inactive</option>';
            $status.='</select>';
            $sub_array = array();
			$sub_array[] = ++$key;
            $sub_array[] = $value->salutation.' '.$value->name;
            $sub_array[] = $this->checkLicense($value->id,$this->getCetificateIds($value->id,'license'));
            $sub_array[] = $this->checkTraining($value->id,$this->getCetificateIds($value->id,'training'));
            $sub_array[] = $this->checkMedical($value->id,$this->getCetificateIds($value->id,'medical'));
            $sub_array[] = $action;
            $data[] = $sub_array;
        }
        $output = array(
			"draw"   =>  intval($_POST["draw"]),
			"recordsTotal"   =>  $total_row,
			"recordsFiltered"  =>  $filter_row,
			"data"   =>  $data
		);
		echo json_encode($output);
    }

}
