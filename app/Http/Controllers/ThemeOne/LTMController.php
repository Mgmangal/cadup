<?php
namespace App\Http\Controllers\ThemeOne;


use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PilotLicense;

class LTMController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['permission:Staff Add|Staff Edit|Staff Delete|Staff View']);
    }
    public function index()
    {
        return view('theme-one.ltm.index');
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

            $action  = '<a href="'.route('user.pilot.licenses', $value->id).'" class="btn btn-primary btn-sm m-1">View</a>';
            //$action .= '<a href="'.route('app.pilot.licenses', $value->id).'" class="btn btn-success btn-sm m-1">License Traning & Medical</a>';

            $status='<select class="form-control" onchange="changeStatus('.$value->id.',this.value);">';
                $status.='<option '.($value->status=='active'?'selected':'').' value="active">Active</option>';
                $status.='<option '.($value->status=='inactive'?'selected':'').' value="inactive">Inactive</option>';
            $status.='</select>';
            $sub_array = array();
			$sub_array[] = ++$key;
            $sub_array[] = $value->salutation.' '.$value->name;
            $sub_array[] = checkCrewLicenses($value->id,getCetificateIds($value->id,'license'));
            $sub_array[] = checkCrewTrainings($value->id,getCetificateIds($value->id,'training'));
            $sub_array[] = checkCrewMedicals($value->id,getCetificateIds($value->id,'medical'));
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
        return view('theme-one.ltm.monitoring');
    }


    public function history()
    {
        return view('theme-one.ltm.history');
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

            $action  = '<a href="'.route('user.pilot.licenses', $value->id).'" class="btn btn-primary btn-sm m-1">View</a>';

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

            $action  = '<a href="'.route('user.pilot.licenses', $value->id).'" class="btn btn-primary btn-sm m-1">View</a>';
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

            $action  = '<a href="'.route('user.pilot.licenses', $value->id).'" class="btn btn-primary btn-sm m-1">View</a>';
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
