<?php
namespace App\Http\Controllers\ThemeOne;


use App\Models\User;

use App\Models\Master;
use App\Models\PilotLicense;
use App\Models\PilotMedical;
use Illuminate\Http\Request;
use App\Models\PilotTraining;
use App\Models\PilotQualification;
use Illuminate\Support\Facades\DB;
use App\Models\PilotGroundTraining;
use App\Models\UserCertificate;
use App\Http\Controllers\Controller;

class CertificateController extends Controller
{
    public function __construct()
    {
        //$this->middleware(['permission:Certificate Add|Certificate Edit|Certificate Delete|Certificate View']);
    }

    public function licence()
    {
        $sub_title = "Licence";
        $pilots = User::with('designation')->where('is_delete','0')->where('status','active')->get();
        return view('theme-one.certificate.licence',compact('sub_title','pilots'));
    }

    public function myLicence()
    {
        $sub_title = "My Licence";
        $user = User::with('designation')->where('id', Auth()->user()->id)->where('is_delete', '0')->where('status', 'active')->first();
        $pilots = collect([$user]);
        return view('theme-one.certificate.licence',compact('sub_title','pilots'));
    }
    public function licenceList(Request $request)
    {
        $column=['user_certificates.id','user_certificates.id','users.salutation','users.name','masters.name','user_certificates.id','user_certificates.id','user_certificates.id','user_certificates.id','user_certificates.id','user_certificates.id','user_certificates.id'];
      
        $users = DB::table('user_certificates')
            //->leftjoin('pilot_licenses', 'user_certificates.master_id', '=', 'pilot_licenses.license_id')
            ->leftjoin('masters', 'user_certificates.master_id', '=', 'masters.id')
            ->leftjoin('users', 'user_certificates.user_id', '=', 'users.id')
            ->select(
                'masters.id as master_id', 
                'masters.name as name',
                'users.salutation', 
                'users.id as user_id', 
                'users.name as user_name' 
                //'pilot_licenses.id',
                // 'pilot_licenses.extended_date',
                // 'pilot_licenses.next_due',
                // 'pilot_licenses.status',
                // 'pilot_licenses.created_at',
                // 'pilot_licenses.renewed_on'
            )
            ->where('user_certificates.certificate_type', 'license');
            //$users->groupBy('pilot_licenses.license_id');
            
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
                $q->where(DB::raw("CONCAT(users.salutation, ' ', users.name)"), 'LIKE', '%' . $search . '%')
                      ->orWhere('users.name', 'LIKE', '%' . $search . '%')
                      ->orWhere('masters.name', 'LIKE', '%' . $search . '%');
                // $q->orWhere('pilot_licenses.renewed_on', 'LIKE', '%' . $search . '%')
                //   ->orWhere('pilot_licenses.extended_date', 'LIKE', '%' . $search . '%')
                //   ->orWhere('pilot_licenses.next_due', 'LIKE', '%' . $search . '%')
                //   ->orWhere('pilot_licenses.status', 'LIKE', '%' . $search . '%');
                
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
		    
           $d=PilotLicense::where('user_id',$value->user_id)->where('license_id',$value->master_id)->orderBy('id','DESC')->first();
           $action  ='';
            if(!empty($d->id))
            {
                $action  .= '<a href="javascript:void(0);" onclick="showData('.$d->id.',\'license\')" class="btn btn-primary btn-sm m-1">View</a>';
            }
            $action  .= '<a href="' . route('user.certificate.viewLogs', ['type' => 'licence-logs', 'user_id' => $value->user_id, 'id' => $value->master_id]) . '" class="btn btn-warning btn-sm m-1">Log</a>';
            if(!empty($d->documents)){
                $action  .= '<a href="'.asset('uploads/pilot_certificate/'.$d->documents).'" target="_blank" class="btn btn-success m-1">Certificate</a>';
            }
            $status='Active';
            $sub_array = array();
			$sub_array[] = ++$key;
            $sub_array[] = $value->salutation.' '.$value->user_name;
            $sub_array[] = $value->name;
            $sub_array[] = !empty($d->next_due)?'<b>' . $d->renewed_on . '</b>':'';
            $sub_array[] = !empty($d->next_due)?$d->extended_date:'';
            $sub_array[] = !empty($d->next_due)?$d->next_due:'';
            $next_due='';
            if(!empty($d->next_due)&& strtotime($d->next_due) > strtotime($dates))
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

    public function trainings()
    {
        $sub_title = "Training";
        $pilots = User::with('designation')->where('is_delete','0')->where('status','active')->get();
        return view('theme-one.certificate.training',compact('sub_title','pilots'));
    }

    public function myTrainings()
    {
        $sub_title = "My Training";
        $user = User::with('designation')->where('id', Auth()->user()->id)->where('is_delete', '0')->where('status', 'active')->first();
        $pilots = collect([$user]);
        return view('theme-one.certificate.training',compact('sub_title','pilots'));
    }

    public function trainingList(Request $request)
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
                // 'pilot_trainings.id',
                // 'pilot_trainings.extended_date',
                // 'pilot_trainings.next_due',
                // 'pilot_trainings.status',
                // 'pilot_trainings.created_at',
                // 'pilot_trainings.renewed_on'
            )
            ->where('user_certificates.certificate_type', 'training');
            //->groupBy('pilot_trainings.training_id')
            //->orderBy('pilot_trainings.id', 'DESC');
        
        
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
                $q->where(DB::raw("CONCAT(users.salutation, ' ', users.name)"), 'LIKE', '%' . $search . '%')
                      ->orWhere('masters.name', 'LIKE', '%' . $search . '%');
                // $q->orWhere('pilot_trainings.renewed_on', 'LIKE', '%' . $search . '%')
                //   ->orWhere('pilot_trainings.extended_date', 'LIKE', '%' . $search . '%')
                //   ->orWhere('pilot_trainings.next_due', 'LIKE', '%' . $search . '%')
                //   ->orWhere('pilot_trainings.status', 'LIKE', '%' . $search . '%');
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
            
            if(!empty($d->id))
            {
                $action  .= '<a href="javascript:void(0);" onclick="showData('.$d->id.',\'training\')" class="btn btn-primary btn-sm m-1">View</a>';
            }
            $action  .= '<a href="'.route('user.certificate.viewLogs', ['type' => 'training-logs', 'user_id' => $value->user_id, 'id' => $value->master_id]).'" class="btn btn-warning btn-sm m-1">Log</a>';
            if(!empty($d->documents)){
                $action  .= '<a href="'.asset('uploads/pilot_certificate/'.$d->documents).'" target="_blank" class="btn btn-success m-1">Certificate</a>';
            }
            
            $status='Active';
            $sub_array = array();
			$sub_array[] = ++$key;
            $sub_array[] = $value->salutation.' '.$value->user_name;
            $sub_array[] = !empty($value->name)?$value->name:'';
            $sub_array[] = !empty($d->renewed_on)?'<b>' . $d->renewed_on . '</b>':'';
            $sub_array[] = !empty($d->extended_date)?$d->extended_date:'';
            $sub_array[] = !empty($d->next_due)?$d->next_due:'';
            if(!empty($d->next_due)&&strtotime($d->next_due) > strtotime($dates))
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

    public function medicals(Request $request)
    {
        $sub_title = "Medical";
        $pilots = User::with('designation')->where('is_delete','0')->where('status','active')->get();
        return view('theme-one.certificate.medical',compact('sub_title','pilots'));
    }

    public function myMedicals(Request $request)
    {
        $sub_title = "My Medical";
        $user = User::with('designation')->where('id', Auth()->user()->id)->where('is_delete', '0')->where('status', 'active')->first();
        $pilots = collect([$user]);
        return view('theme-one.certificate.medical',compact('sub_title','pilots'));
    }

    public function medicalList(Request $request)
    {
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
                // 'pilot_medicals.id',
                // 'pilot_medicals.extended_date',
                // 'pilot_medicals.next_due',
                // 'pilot_medicals.status',
                // 'pilot_medicals.created_at',
                // 'pilot_medicals.planned_renewal_date'
            )
            ->where('user_certificates.certificate_type', 'medical');
            //->groupBy('pilot_medicals.medical_id')
            //->orderBy('pilot_medicals.id', 'DESC');
            
            
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
                $q->where(DB::raw("CONCAT(users.salutation, ' ', users.name)"), 'LIKE', '%' . $search . '%')
                      ->orWhere('masters.name', 'LIKE', '%' . $search . '%');
                // $q->orWhere('pilot_medicals.planned_renewal_date', 'LIKE', '%' . $search . '%')
                //   ->orWhere('pilot_medicals.extended_date', 'LIKE', '%' . $search . '%')
                //   ->orWhere('pilot_medicals.next_due', 'LIKE', '%' . $search . '%')
                //   ->orWhere('pilot_medicals.status', 'LIKE', '%' . $search . '%');
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
		    
		    $d=PilotMedical::where('user_id',$value->user_id)->where('medical_id',$value->master_id)->orderBy('id','DESC')->first();
		    $action  ='';
            if(!empty($d->id))
            {
                $action  .= '<a href="javascript:void(0);" onclick="showData('.$d->id.',\'medical\')" class="btn btn-primary btn-sm m-1">View</a>';
            }
            $action  .= '<a href="'.route('user.certificate.viewLogs', ['type' => 'medical-logs', 'user_id' => $value->user_id, 'id' => $value->master_id]).'" class="btn btn-warning btn-sm m-1">Log</a>';
            if(!empty($d->documents)){
                $action  .= '<a href="'.asset('uploads/pilot_certificate/'.$d->documents).'" target="_blank" class="btn btn-success m-1">Certificate</a>';
            }
            $status='Active';
            $sub_array = array();
			$sub_array[] = ++$key;
            $sub_array[] = $value->salutation.' '.$value->user_name;
            $sub_array[] = !empty($value->name)?$value->name:'';
            $sub_array[] = !empty($d->planned_renewal_date)?'<b>' . $d->planned_renewal_date . '</b>':'';
            $sub_array[] = !empty($d->extended_date)?$d->extended_date:'';
            $sub_array[] = !empty($d->next_due)?$d->next_due:'';
            if(!empty($d->next_due)&&strtotime($d->next_due) > strtotime($dates))
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

    public function qualifications(Request $request)
    {
        $sub_title = "Qualification";
        $pilots = User::with('designation')->where('is_delete','0')->where('status','active')->get();
        return view('theme-one.certificate.qualification',compact('sub_title','pilots'));
    }

    public function myQualifications(Request $request)
    {
        $sub_title = "My Qualification";
        $user = User::with('designation')->where('id', Auth()->user()->id)->where('is_delete', '0')->where('status', 'active')->first();
        $pilots = collect([$user]);
        return view('theme-one.certificate.qualification',compact('sub_title','pilots'));
    }

    public function qualificationList(Request $request)
    {
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
                // 'pilot_qualifications.id',
                // 'pilot_qualifications.extended_date',
                // 'pilot_qualifications.next_due',
                // 'pilot_qualifications.status',
                // 'pilot_qualifications.created_at',
                // 'pilot_qualifications.renewed_on'
            )
            ->where('user_certificates.certificate_type', 'qualification');
            //->groupBy('pilot_qualifications.qualification_id')
            //->orderBy('pilot_qualifications.id', 'DESC');
        
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
                $q->where(DB::raw("CONCAT(salutation, ' ', name)"), 'LIKE', '%' . $search . '%')
                      ->orWhere('name', 'LIKE', '%' . $search . '%');
                      
                // $q->orWhere('renewed_on', 'LIKE', '%' . $search . '%')
                //   ->orWhere('extended_date', 'LIKE', '%' . $search . '%')
                //   ->orWhere('next_due', 'LIKE', '%' . $search . '%')
                //   ->orWhere('status', 'LIKE', '%' . $search . '%');      
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
            if(!empty($d->id))
            {
                $action  .= '<a href="javascript:void(0);" onclick="showData('.$d->id.',\'qualification\')" class="btn btn-primary btn-sm m-1">View</a>';
            }
            $action  .= '<a href="'.route('user.certificate.viewLogs', ['type' => 'qualification-logs', 'user_id' => $value->user_id, 'id' => $value->master_id]).'" class="btn btn-warning btn-sm m-1">Log</a>';
            if(!empty($d->documents)){
                $action  .= '<a href="'.asset('uploads/pilot_certificate/'.$d->documents).'" target="_blank" class="btn btn-success m-1">Certificate</a>';
            }
            $status='Active';
            $sub_array = array();
			$sub_array[] = ++$key;
            $sub_array[] = $value->salutation.' '.$value->user_name;
            $sub_array[] = !empty($value->name)?$value->name:'';
            $sub_array[] = !empty($d->renewed_on)?'<b>' . $d->renewed_on . '</b>':'';
            $sub_array[] = !empty($d->extended_date)?$d->extended_date:'';
            $sub_array[] = !empty($d->next_due)?$d->next_due:'';
            if(!empty($d->next_due)&&strtotime($d->next_due) > strtotime($dates))
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

    public function groundTrainings(Request $request)
    {
        $sub_title = "Ground Training";
        $pilots = User::with('designation')->where('is_delete','0')->where('status','active')->get();
        return view('theme-one.certificate.ground-training',compact('sub_title','pilots'));
    }

    public function myGroundTrainings(Request $request)
    {
        $sub_title = "My Ground Training";
        $user = User::with('designation')->where('id', Auth()->user()->id)->where('is_delete', '0')->where('status', 'active')->first();
        $pilots = collect([$user]);
        return view('theme-one.certificate.ground-training',compact('sub_title','pilots'));
    }

    public function groundTrainingList(Request $request)
    {
        $column=['user_certificates.id','user_certificates.id','users.salutation','users.name','user_certificates.id','user_certificates.id','user_certificates.id','user_certificates.id','user_certificates.id','user_certificates.id','user_certificates.id','user_certificates.id'];
        
        // $users = UserCertificate::with(['groundTrainings'=>function($q){
        //          $q->leftjoin('users', 'pilot_ground_trainings.user_id', '=', 'users.id');
        //         $q->orderBy('pilot_ground_trainings.id', 'DESC')->limit(1);
        //     }]);
        $users = UserCertificate::leftjoin('masters', 'user_certificates.master_id', '=', 'masters.id')
            ->leftjoin('users', 'user_certificates.user_id', '=', 'users.id')
            //->leftjoin('pilot_ground_trainings', 'pilot_ground_trainings.training_id', '=', 'user_certificates.master_id')
            ->select(
                'masters.id as master_id', 
                'masters.name as name',
                'users.salutation', 
                'users.id as user_id', 
                'users.name as user_name'
            )
            ->where('user_certificates.certificate_type', 'ground_training');
            //->groupBy('pilot_ground_trainings.training_id')
            
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
                $q->where(DB::raw("CONCAT(users.salutation, ' ', users.name)"), 'LIKE', '%' . $search . '%')
                      ->orWhere('masters.name', 'LIKE', '%' . $search . '%');
                      
                // $q->orWhere('pilot_ground_trainings.renewed_on', 'LIKE', '%' . $search . '%')
                //   ->orWhere('pilot_ground_trainings.extended_date', 'LIKE', '%' . $search . '%')
                //   ->orWhere('pilot_ground_trainings.next_due', 'LIKE', '%' . $search . '%')
                //   ->orWhere('pilot_ground_trainings.status', 'LIKE', '%' . $search . '%');      
                      
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
		  //   $groundTrainings=!empty($value->groundTrainings[0])?$value->groundTrainings[0]:array();
            $d=PilotGroundTraining::where('user_id',$value->user_id)->where('training_id',$value->master_id)->orderBy('id','DESC')->first();
            $action  = '';
            if(!empty($d->id))
            {
                $action  .= '<a href="javascript:void(0);" onclick="showData('.$d->id.',\'groundtraining\')" class="btn btn-primary btn-sm m-1">View </a>';
            }
            $action  .= '<a href="'.route('user.certificate.viewLogs', ['type' => 'ground-training-logs', 'user_id' => $value->user_id, 'id' => $value->master_id]).'" class="btn btn-warning btn-sm m-1">Log</a>';
            if(!empty($d->documents)){
                $action  .= '<a href="'.asset('uploads/pilot_certificate/'.$d->documents).'" target="_blank" class="btn btn-success m-1">Certificate</a>';
            }
            $status='Active';
            $sub_array = array();
			$sub_array[] = ++$key;
            $sub_array[] = $value->salutation.' '.$value->user_name;
            $sub_array[] = !empty($value->name)?$value->name:'';
            $sub_array[] = !empty($d->renewed_on)?'<b>' . $d->renewed_on . '</b>':'';
            $sub_array[] = !empty($d->extended_date)?$d->extended_date:'';
            $sub_array[] = !empty($d->next_due)?$d->next_due:'';
            if(!empty($d->next_due)&&strtotime($d->next_due) > strtotime($dates))
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
    public function viewLogs($type, $user_id, $id)
    {
        if($type=='licence-logs'){
            $sub_title = "Licence Logs";
        } else if($type=='training-logs'){
            $sub_title = "Training Logs";
        } else if($type=='medical-logs'){
            $sub_title = "Medical Logs";
        } else if($type=='qualification-logs'){
            $sub_title = "Qualification Logs";
        } else if($type=='ground-training-logs'){
            $sub_title = "Ground Training Logs";
        }
        return view('theme-one.certificate.view-logs',compact('sub_title','type','user_id','id'));
    }
    public function getLogList(Request $request)
    {
        $column = [
            'id', 'id', 'users.salutation', 'users.name', 'license.name',
            'renewed_on', 'extended_date', 'next_due', 'status', 'created_at', 'id', 'id'
        ];

        $type = $request->input('type');
        $user_id = $request->input('user_id');
        $id = $request->input('id');
        $model = null;
        $table = null;
        $where['user_id'] = $user_id;
        switch ($type) {
            case 'licence-logs':
                $model = PilotLicense::class;
                $where['license_id'] = $id;
                $table = "license";
                break;
            case 'training-logs':
                $model = PilotTraining::class;
                $where['training_id'] = $id;
                $table = "training";
                break;
            case 'medical-logs':
                $model = PilotMedical::class;
                $where['medical_id'] = $id;
                $table = "medical";
                break;
            case 'qualification-logs':
                $model = PilotQualification::class;
                $where['qualification_id'] = $id;
                $table = "qualification";
                break;
            case 'ground-training-logs':
                $model = PilotGroundTraining::class;
                $where['training_id'] = $id;
                $table = "training";
                break;
            default:
                return response()->json(['error' => 'Invalid log type'], 400);
        }

        if (!$model) {
            return response()->json(['error' => 'Invalid log type'], 400);
        }

        $users = $model::with(['user'])->where($where);

        if ($request->has('search.value') && $search = $request->input('search.value')) {
            $users->where(function ($q) use ($search, $table) {
                $q->whereHas('user', function ($q) use ($search) {
                    $q->where(DB::raw("CONCAT(salutation, ' ', name)"), 'LIKE', '%' . $search . '%')
                      ->orWhere('name', 'LIKE', '%' . $search . '%');
                });
                $q->orWhereHas($table, function ($q) use ($search) {
                    $q->where('name', 'LIKE', '%' . $search . '%');
                });
                $q->orWhere('renewed_on', 'LIKE', '%' . $search . '%')
                  ->orWhere('extended_date', 'LIKE', '%' . $search . '%')
                  ->orWhere('next_due', 'LIKE', '%' . $search . '%')
                  ->orWhere('status', 'LIKE', '%' . $search . '%');
            });
        }

        if ($request->has('order')) {
            $users->orderBy($column[$request->input('order.0.column')], $request->input('order.0.dir'));
        } else {
            $users->orderBy('id', 'desc');
        }

        $total_row = $users->count();

        if ($request->has('length') && $request->input('length') != -1) {
            $users->skip($request->input('start'))->take($request->input('length'));
        }

        $result = $users->get();

        $data = array();
        foreach ($result as $key => $value) {
            if($value->documents){
                $action  = '<a href="' . asset('uploads/pilot_certificate/' . $value->documents) . '" target="_blank" class="btn btn-info btn-sm py-1 text-white">View</a>';
            } else {
                $action  = '<a href="javascript:void(0)" class="text-red">Not Available</a>';
            }

            $status = 'Active';
            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = ($value->user->salutation ?? '') . ' ' . ($value->user->name ?? '');
            $sub_array[] = $value->$table->name ?? '';
            $sub_array[] = '<b>' . $value->renewed_on . '</b>';
            $sub_array[] = $value->extended_date;
            $sub_array[] = $value->next_due;

            $next_due = '';
            $dates = now();  // Assuming $dates is current date. Adjust if needed.
            if (strtotime($value->next_due) > strtotime($dates)) {
                $day = \Carbon\Carbon::parse($dates)->diffInDays($value->next_due);
                $bt = 'style="background-color: #1e24dd;color: white;"';
                if ($day <= 60) {
                    $bt = 'style="background-color: yellow;color: #161515;"';
                }
                if ($day <= 30) {
                    $bt = 'style="background-color: orange;color: #161515;"';
                }
                $next_due = '<button ' . $bt . ' type="button" class="btn btn-sm position-relative">' . $day . '<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">Valid<span class="visually-hidden">unread messages</span></span></button>';
            } else {
                $next_due = '<span class="btn btn-sm btn-danger">Lapsed</span>';
            }

            $sub_array[] = $next_due;
            $sub_array[] = $status;
            $sub_array[] = $action;
            $data[] = $sub_array;
        }

        $output = array(
            "draw" => intval($request->input("draw")),
            "recordsTotal" => $total_row,
            "recordsFiltered" => $users->count(),
            "data" => $data
        );

        return response()->json($output);
    }

    public function viewData(Request $request)
    {
        $id =$request->id;
        $type =$request->type;
        if($type=='license')
        {
            $data=PilotLicense::with(['user'])->where('id',$id)->first();
            $html=view('theme-one.certificate.view-license',compact('data'))->render();
        }if($type=='training')
        {
            $data=PilotTraining::with(['user'])->where('id',$id)->first();
            $ac_types=Master::where('type','aircraft_type')->where('is_delete','0')->get();
            $html=view('theme-one.certificate.view-training',compact('data','ac_types'))->render();
        }if($type=='medical')
        {
            $data=PilotMedical::with(['user'])->where('id',$id)->first();
            $html=view('theme-one.certificate.view-medical',compact('data'))->render();
        }if($type=='qualification')
        {
            $data=PilotQualification::with(['user'])->where('id',$id)->first();
            $html=view('theme-one.certificate.view-qualification',compact('data'))->render();
        }if($type=='groundtraining')
        {
            $data=PilotGroundTraining::with(['user'])->where('id',$id)->first();
            $html=view('theme-one.certificate.view-groundtraining',compact('data'))->render();
        }
        echo $html;
    }

}
