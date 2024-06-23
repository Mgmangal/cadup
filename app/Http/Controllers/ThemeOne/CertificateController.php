<?php
namespace App\Http\Controllers\ThemeOne;


use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Master;
use App\Models\User;
use App\Models\PilotLicense;
use App\Models\PilotMedical;
use App\Models\PilotTraining;
use App\Models\PilotQualification;
use App\Models\PilotGroundTraining;
use Illuminate\Support\Facades\Validator;
class CertificateController extends Controller
{
    public function __construct()
    {
        //$this->middleware(['permission:Certificate Add|Certificate Edit|Certificate Delete|Certificate View']);
    }
    public function index()
    {
        return view('theme-one.certificate.licence');
    }
    public function trainings()
    {
        return view('theme-one.certificate.training');
    }
    public function medicals(Request $request)
    {
        return view('theme-one.certificate.medical');
    }
    public function qualifications(Request $request)
    {
        return view('theme-one.certificate.qualification');
    }
    public function groundTrainings(Request $request)
    {
        return view('theme-one.certificate.ground-training');
    }

    public function licenceLog(Request $request)
    {
        return view('theme-one.certificate.licence-log');
    }

    public function trainingsLog(Request $request)
    {
        return view('theme-one.certificate.training-log');
    }

    public function medicalsLog(Request $request)
    {
        return view('theme-one.certificate.medical-log');
    }

    public function qualificationsLog(Request $request)
    {
        return view('theme-one.certificate.qualification-log');
    }
    public function groundTrainingsLog(Request $request)
    {
        return view('theme-one.certificate.ground-training-log');
    }

    public function monitoringLicenseLogList(Request $request)
    {
        $column=['id','id','users.salutation','users.name','license.name','renewed_on','extended_date','next_due','status','created_at','id','id'];
        $users=PilotLicense::with(['user'=>function($q) {
            $q->where('status','active');
        }])->where('is_applicable','yes');
        $users->orderBy('id', 'desc');

        if(!empty($_POST['user_id']))
        {
            $users->where('user_id',$_POST['user_id']);
        }

        $dates=date('Y-m-d');
        if(!empty($_POST['dates']))
        {
            $dates=$_POST['dates'];
        }

        $total_row=$users->count();
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
            $users->orderBy('id', 'desc');
        }
		$filter_row =$users->count();
        if (isset($_POST["length"]) && $_POST["length"] != -1) {
            $users->skip($_POST["start"])->take($_POST["length"]);
		}
        $result =$users->get();
        $data = array();
		foreach($result as $key => $value) {

            $action  = '<a href="javascript:void(0);" onclick="showData('.$value->id.',\'license\')" class="btn btn-primary btn-sm m-1">View</a>';

            $status='Active';
            $sub_array = array();
			$sub_array[] = ++$key;
            $sub_array[] = $value->user->salutation.' '.$value->user->name;
            $sub_array[] = $value->license->name;
            $sub_array[] = '<b>' . $value->renewed_on . '</b>';
            $sub_array[] = $value->extended_date;
            $sub_array[] = $value->next_due;
            $next_due='';
            if(strtotime($value->next_due) > strtotime($dates))
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

    public function monitoringTrainingLogList(Request $request)
    {
        $column=['id','id','users.salutation','users.name','training.name','renewed_on','extended_date','next_due','status','created_at','id','id'];
        $users=PilotTraining::with(['user'=>function($q) {
            $q->where('status','active');
        }])->where('is_applicable','yes');
        $users->orderBy('id', 'desc');

        if(!empty($_POST['user_id']))
        {
            $users->where('user_id',$_POST['user_id']);
        }

        $dates=date('Y-m-d');
        if(!empty($_POST['dates']))
        {
            $dates=$_POST['dates'];
        }

        $total_row=$users->count();
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
            $users->orderBy('id', 'desc');
        }
		$filter_row =$users->count();
        if (isset($_POST["length"]) && $_POST["length"] != -1) {
            $users->skip($_POST["start"])->take($_POST["length"]);
		}
        $result =$users->get();
        $data = array();
		foreach($result as $key => $value) {
            $action  = '<a href="javascript:void(0);" onclick="showData('.$value->id.',\'training\')" class="btn btn-primary btn-sm m-1">View</a>';

            $status='Active';
            $sub_array = array();
			$sub_array[] = ++$key;
            $sub_array[] = $value->user->salutation.' '.$value->user->name;
            $sub_array[] = $value->training->name;
            $sub_array[] = '<b>' . $value->renewed_on . '</b>';
            $sub_array[] = $value->extended_date;
            $sub_array[] = $value->next_due;
            if(strtotime($value->next_due) > strtotime($dates))
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

    public function monitoringMedicalLogList(Request $request)
    {
        $column=['id','id','users.salutation','users.name','medical.name','renewed_on','extended_date','next_due','status','created_at','id','id'];
        $users=PilotMedical::with(['user'=>function($q) {
            $q->where('status','active');
        }])->where('is_applicable','yes');
        $users->orderBy('id', 'desc');

        if(!empty($_POST['user_id']))
        {
            $users->where('user_id',$_POST['user_id']);
        }

        $dates=date('Y-m-d');
        if(!empty($_POST['dates']))
        {
            $dates=$_POST['dates'];
        }

        $total_row=$users->count();
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
            $users->orderBy('id', 'desc');
        }
		$filter_row =$users->count();
        if (isset($_POST["length"]) && $_POST["length"] != -1) {
            $users->skip($_POST["start"])->take($_POST["length"]);
		}
        $result =$users->get();
        $data = array();
		foreach($result as $key => $value) {

            $action  = '<a href="javascript:void(0);" onclick="showData('.$value->id.',\'medical\')" class="btn btn-primary btn-sm m-1">View</a>';

            $status='Active';
            $sub_array = array();
			$sub_array[] = ++$key;
            $sub_array[] = $value->user->salutation.' '.$value->user->name;
            $sub_array[] = $value->medical->name;
            $sub_array[] = '<b>' . $value->planned_renewal_date . '</b>';
            $sub_array[] = $value->extended_date;
            $sub_array[] = $value->next_due;
            if(strtotime($value->next_due) > strtotime($dates))
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

    public function monitoringQualificationLogList(Request $request)
    {
        $column=['id','id','users.salutation','users.name','qualification.name','renewed_on','extended_date','next_due','status','created_at','id','id'];
        $users=PilotQualification::with(['user'=>function($q) {
            $q->where('status','active');
        }])->where('is_applicable','yes');

        if(!empty($_POST['user_id']))
        {
            $users->where('user_id',$_POST['user_id']);
        }
        $dates=date('Y-m-d');
        if(!empty($_POST['dates']))
        {
            $dates=$_POST['dates'];
        }

        $total_row=$users->count();
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
            $users->orderBy('id', 'desc');
        }
		$filter_row =$users->count();
        if (isset($_POST["length"]) && $_POST["length"] != -1) {
            $users->skip($_POST["start"])->take($_POST["length"]);
		}
        $result =$users->get();
        $data = array();
		foreach($result as $key => $value) {

            $action  = '<a href="javascript:void(0);" onclick="showData('.$value->id.',\'qualification\')" class="btn btn-primary btn-sm m-1">View</a>';

            $status='Active';
            $sub_array = array();
			$sub_array[] = ++$key;
            $sub_array[] = $value->user->salutation.' '.$value->user->name;
            $sub_array[] = $value->qualification->name;
            $sub_array[] = '<b>' . $value->renewed_on . '</b>';
            $sub_array[] = $value->extended_date;
            $sub_array[] = $value->next_due;
            if(strtotime($value->next_due) > strtotime($dates))
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

    public function monitoringGroundTrainingLogList(Request $request)
    {
        $column=['id','id','users.salutation','users.name','training.name','renewed_on','extended_date','next_due','status','created_at','id','id'];
        $users=PilotGroundTraining::with(['user'=>function($q) {
            $q->where('status','active');
        }])->where('is_applicable','yes')->groupBy('training_id')->groupBy('user_id');
        $users->orderBy('id', 'desc');
        if(!empty($_POST['user_id']))
        {
            $users->where('user_id',$_POST['user_id']);
        }
        $dates=date('Y-m-d');
        if(!empty($_POST['dates']))
        {
            $dates=$_POST['dates'];
        }

        $total_row=$users->count();
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
            $users->orderBy('id', 'desc');
        }
		$filter_row =$users->count();
        if (isset($_POST["length"]) && $_POST["length"] != -1) {
            $users->skip($_POST["start"])->take($_POST["length"]);
		}
        $result =$users->get();
        $data = array();
		foreach($result as $key => $value) {

            $action  = '<a href="javascript:void(0);" onclick="showData('.$value->id.',\'groundtraining\')" class="btn btn-primary btn-sm m-1">View</a>';

            $status='Active';
            $sub_array = array();
			$sub_array[] = ++$key;
            $sub_array[] = $value->user->salutation.' '.$value->user->name;
            $sub_array[] = $value->training->name;
            $sub_array[] = '<b>' . $value->renewed_on . '</b>';
            $sub_array[] = $value->extended_date;
            $sub_array[] = $value->next_due;
            if(strtotime($value->next_due) > strtotime($dates))
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

    public function viewData(Request $request)
    {
        $id =$request->id;
        $type =$request->type;
        if($type=='license')
        {
            $data=PilotLicense::with(['user'=>function($q) {
                $q->where('status','active');
            }])->where('is_applicable','yes')->where('id',$id)->first();
            $html=view('theme-one.certificate.view-license',compact('data'))->render();
        }if($type=='training')
        {
            $data=PilotTraining::with(['user'=>function($q) {
                $q->where('status','active');
            }])->where('is_applicable','yes')->where('id',$id)->first();
            $ac_types=Master::where('type','aircraft_type')->where('is_delete','0')->get();
            $html=view('theme-one.certificate.view-training',compact('data','ac_types'))->render();
        }if($type=='medical')
        {
            $data=PilotMedical::with(['user'=>function($q) {
                $q->where('status','active');
            }])->where('is_applicable','yes')->where('id',$id)->first();
            $html=view('theme-one.certificate.view-medical',compact('data'))->render();
        }if($type=='qualification')
        {
            $data=PilotQualification::with(['user'=>function($q) {
                $q->where('status','active');
            }])->where('is_applicable','yes')->where('id',$id)->first();
            $html=view('theme-one.certificate.view-qualification',compact('data'))->render();
        }if($type=='groundtraining')
        {
            $data=PilotGroundTraining::with(['user'=>function($q) {
                $q->where('status','active');
            }])->where('is_applicable','yes')->where('id',$id)->first();
            $html=view('theme-one.certificate.view-groundtraining',compact('data'))->render();
        }
        echo $html;
    }
}
