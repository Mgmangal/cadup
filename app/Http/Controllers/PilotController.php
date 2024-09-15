<?php
namespace App\Http\Controllers;

use App\Models\City;
use App\Models\User;
use App\Models\Leave;
use App\Models\State;
use App\Models\Master;
use App\Models\AirCraft;
use App\Models\MasterAssign;
use App\Models\PilotLicense;
use App\Models\PilotMedical;
use App\Models\UserDocument;
use App\Models\UserCertificate;
use Illuminate\Http\Request;
use App\Models\PilotTraining;
use Spatie\Permission\Models\Role;
use App\Models\PilotGroundTraining;
use App\Models\PilotQualification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class PilotController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['permission:Staff Add|Staff Edit|Staff Delete|Staff View']);
    }

    public function index()
    {
        return view('pilot.index');
    }

    public function create()
    {
        $departments =Master::where('type','department')->where('status','active')->get();
        $designations =Master::where('type','designation')->where('status','active')->get();
        return view('pilot.create',compact('departments','designations'));
    }

    public function getSection(Request $request)
    {
        $id=$request->id;
        $user_id=$request->user_id;
        $section=array();
        if(!empty($user_id))
        {
            $user=User::find($user_id);
            $section=$user->section;
        }
        $html='';
        foreach ($id as $key => $value) {
            $row=Master::find($value);
            $html.= '<optgroup label="'.$row->name.'">';
            $data=Master::where('parent_id',$row->id)->where('status','active')->get();
            foreach ($data as $key => $value) {
                $html.= '<option value="'.$value->id.'" '.(!empty($section)&&in_array($value->id,$section)?'selected':'').'  >'.$value->name.'</option>';
            }
            $html.= '</optgroup>';
        }
        return response()->json([
            'success'=>true,
            'data'=>$html
        ]);
    }

    public function getJobFunction(Request $request)
    {
        $id=$request->id;
        $user_id=$request->user_id;
        $jobfunction=array();
        if(!empty($user_id))
        {
            $user=User::find($user_id);
            $jobfunction=$user->jobfunction;
        }
        $html='';
        foreach ($id as $key => $value) {
            $row=Master::find($value);
            $html.= '<optgroup label="'.$row->name.'">';
            $data=Master::where('parent_id',$row->id)->where('status','active')->get();
            foreach ($data as $key => $value) {
                $html.= '<option value="'.$value->id.'" '.(!empty($jobfunction)&&in_array($value->id,$jobfunction)?'selected':'').'>'.$value->name.'</option>';
            }
            $html.= '</optgroup>';
        }
        return response()->json([
            'success'=>true,
            'data'=>$html
        ]);
    }

    public function store(Request $request)
    {
        $validation = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'phone' => 'required',
        ]);
        try {
            $user = new User();
            if($request->hasFile('profile')) {
                $file = $request->file('profile');
                $file->move(asset('uploads'), $file->getClientOriginalName());
                $user->profile = $file->getClientOriginalName();
            }
            $user->emp_id = $request->emp_id;
            $user->salutation=$request->salutation;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->address = $request->address;
            $user->phone = $request->phone;
            $user->user_type = 'user';
            $user->section = $request->section;
            $user->department = $request->department;
            $user->designation =$request->designation;
            $user->jobfunction = $request->jobfunction;
            $user->password = bcrypt('password');
            $user->save();
            return redirect()->route('app.pilot')->with('success','Pilot created successfully');
        } catch (\Exception $e) {
            print_r( $e->getMessage());die;
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function list(Request $request)
    {
        $column=['id','id','emp_id','name','email','phone','designation','created_at','id','id'];
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
		foreach ($result as $key => $value) {
            $action = '';
            // if(auth()->user()->can('Staff Edit'))
            // {
                $action  .= '<a href="'.route('app.pilot.edit', $value->id).'" class="btn btn-primary btn-sm m-1">Profile</a>';
                $action .= '<a href="'.route('app.pilot.licenses', $value->id).'" class="btn btn-success btn-sm m-1">License Training & Medical</a>';
                $action .= '<a href="'.route('app.pilot.documents', $value->id).'" class="btn btn-primary btn-sm m-1">Doc</a>';
                // $action .= '<a href="'.route('app.pilot.authorization', $value->id).'" class="btn btn-success btn-sm m-1">Manage Authorization</a>';
                // $action .= '<a href="javascript:void(0);" class="btn btn-warning btn-sm m-1">Add Limitation</a>';
                // $action .= '<a href="javascript:void(0);" class="btn  btn-outline-success btn-sm m-1">Checklist</a>';
                // $action .= '<a href="javascript:void(0);" class="btn  btn-outline-success btn-sm m-1">Competency Checklist</a>';
                // $action .= '<a href="javascript:void(0);" class="btn  btn-outline-success btn-sm m-1">Application Form</a>';
                // $action .= '<a href="'.route('app.pilot.roles', $value->id).'" class="btn btn-success btn-sm m-1">Print Authorization Certificate</a>';
                // $action .= '<a href="'.route('app.pilot.roles', $value->id).'" class="btn btn-success btn-sm m-1">Print Task Based Authorization Certificate</a>';

            // }

            // if($value->id!=1&&auth()->user()->can('User Delete'))
            // {
                // $action .= '<a href="javascript:void(0);" onclick="deleted(`'.route('app.pilot.destroy', $value->id).'`);" class="btn btn-danger btn-sm m-1">Delete</a>';
            // }

            $status='<select class="form-control" style="width: auto;" onchange="changeStatus('.$value->id.',this.value);">';
                $status.='<option '.($value->status=='active'?'selected':'').' value="active">Active</option>';
                $status.='<option '.($value->status=='inactive'?'selected':'').' value="inactive">Inactive</option>';
            $status.='</select>';
            $sub_array = array();
			$sub_array[] = ++$key;
            // $sub_array[] = '<img src="'.is_image('uploads/'.$value->profile).'" width="50" height="50" class="img-thumbnail" />';
            // $sub_array[] = $value->emp_id;
            $sub_array[] = $value->salutation.' '.$value->name;
            $sub_array[] = $value->email;
            $sub_array[] = $value->phone;
            $sub_array[] = $value->designation()->first()->name??'';
            //$sub_array[] = $status;
            // $sub_array[] = date('d-m-Y',strtotime($value->created_at));
            $sub_array[] =  $action;
            $data[] = $sub_array;
        }
        $output = array(
			"draw"       =>  intval($_POST["draw"]),
			"recordsTotal"   =>  $total_row,
			"recordsFiltered"  =>  $filter_row,
			"data"       =>  $data
		);

		echo json_encode($output);
    }

    public function edit($id)
    {
        $user = User::find($id);
        $departments =Master::where('type','department')->where('status','active')->get();
        $designations =Master::where('type','designation')->where('status','active')->get();
        $states = State::where('country_id', '101')->where('is_delete', '0')->get();
        $per_city = City::where('state_id', $user->per_state)->where('is_delete', '0')->get();
        $tem_city = City::where('state_id', $user->tem_state)->where('is_delete', '0')->get();
        return view('pilot.edit',compact('user','departments','designations','states','per_city','tem_city'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,'.$id,
            'phone' => 'required',
            'mobile' => 'required',
        ]);
        try {
            $user = User::find($id);
            if($request->hasFile('profile')) {
                $file = $request->file('profile');
                $file->move(asset('uploads'), $file->getClientOriginalName());
                $user->profile = $file->getClientOriginalName();
                @unlink(asset('uploads/'.$user->profile));
            }
            $degree = $request->degree;
            $year = $request->year;
            $institute = $request->institute;
            $education = [];
            foreach ($degree as $key => $value) {
                $education[$key]['degree'] = $value;
                $education[$key]['year'] = $year[$key];
                $education[$key]['institute'] = $institute[$key];
            }

            $user->qualification = $education;
            $user->emp_id=$request->emp_id;
            $user->salutation=$request->salutation;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->homestation = $request->homestation;
            $user->phone = $request->phone;
            $user->mobile = $request->mobile;
            $user->kin_name = $request->kin_name;
            $user->kin_phone = $request->kin_phone;
            $user->kin_relation = $request->kin_relation;
            $user->aadhaar_number = $request->aadhaar_number;
            $user->pan_number = $request->pan_number;
            $user->section = $request->section;
            $user->department = $request->department;
            $user->designation =$request->designation;
            $user->jobfunction = $request->jobfunction;
            $user->doj = $request->doj;
            $user->joining_type = $request->joining_type;
            $user->pre_contract_renewal_date = $request->pre_contract_renewal_date;
            $user->pre_valid_up_to = $request->pre_valid_up_to;
            $user->contract_renewal_date = $request->contract_renewal_date;
            $user->valid_up_to = $request->valid_up_to;
            $user->aep_type = $request->aep_type;
            $user->aep_number = $request->aep_number;
            $user->aep_expiring_on = $request->aep_expiring_on;
            $user->police_verification = $request->police_verification;
            $user->passport_number = $request->passport_number;
            $user->passport_validity = $request->passport_validity;
            $user->aircraft_authorisation_no = $request->aircraft_authorisation_no;
            $user->per_state = $request->per_state;
            $user->per_city = $request->per_city;
            $user->per_pincode = $request->per_pincode;
            $user->tem_state = $request->tem_state;
            $user->tem_city = $request->tem_city;
            $user->tem_pincode = $request->tem_pincode;
            $user->save();
            return redirect()->route('app.pilot')->with('success','Pilot updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function updateStatus(Request $request)
    {
        try {
            $id=$request->id;
            $status=$request->status;
            $user = User::find($id);
            $user->status=$status;
            $user->save();
            return response()->json(['success' => true, 'message' => 'Status updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::find($id);
            // if(!empty($user->profile))
            // {
            //     @unlink(asset('uploads/'.$user->profile));
            // }
            // $user->delete();
            $user->is_delete='1';
            $user->status='inactive';
            $user->save();
            return redirect()->route('app.users')->with('success','Pilot deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function role($id)
    {
        $user = User::find($id);
        $userRoles = $user->roles->pluck('id')->toArray();
        $roles=Role::all();
        return view('pilot.role',compact('user','roles','userRoles'));
    }

    public function roleStore(Request $request,$id)
    {
        try {
            $user = User::find($id);

            $user->roles()->sync($request->roles);
            return redirect()->route('users.index')->with('success','Pilot role updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function licenses($id)
    {
        $user = User::find($id);
        
        $certificates=UserCertificate::with('licenses')->where('user_id',$id)->groupBy('certificate_type')->get();
        //dd($certificates);
        //print_r($certificates);die;
        // $designation[]=$user->designation??'0';
        // $section=$user->section??[];
        // $jobfunction=$user->jobfunction??[];
        // $a=AirCraft::whereJsonContains('pilots',$id)->pluck('id')->toArray();
        // $d=array_merge($designation,$section,$jobfunction);
        // $license1=MasterAssign::whereIn('master_id',$d)->where('is_for','user')->pluck('certificate_id')->toArray();
        // $license2=MasterAssign::whereIn('master_id',$a)->where('is_for','aircraft')->pluck('certificate_id')->toArray();
        // $license=array_merge($license1,$license2);
        // $licenses=Master::whereIn('id',$license)->where('type','certificate')->where('sub_type','license')->where('is_delete','0')->get();
        // $trainings=Master::whereIn('id',$license)->where('type','certificate')->where('sub_type','training')->where('is_delete','0')->get();
        // $medicals=Master::whereIn('id',$license)->where('type','certificate')->where('sub_type','medical')->where('is_delete','0')->get();
        // $qualifications=Master::whereIn('id',$license)->where('type','certificate')->where('sub_type','qualification')->where('is_delete','0')->get();
        // $groundtrainings=Master::whereIn('id',$license)->where('type','certificate')->where('sub_type','ground_training')->where('is_delete','0')->get();
        //dd($certificates);
        $ac_types=Master::where('type','aircraft_type')->where('is_delete','0')->get();
        
        return view('pilot.licenses',compact('user','ac_types','certificates'));
    }

    public function licensesStore(Request $request)
    {
        $user_id=$request->user_id;
        $license_id=$request->license_id;
        $renewed_on=$request->renewed_on;
        $number=$request->number;
        $planned_renewal_date=$request->plannedRenewalDate;
        $issued_on=$request->issued_on;
        $extended_date=$request->extended_date;
        $renewal_office=$request->renewal_office;
        $next_due=$request->next_due;
        $status=$request->status;
        $remarks=$request->remarks;
        $data=new PilotLicense;
        $data->user_id=$user_id;
        $data->license_id=$license_id;

        if($request->hasFile('documnets')) {
            $file = $request->file('documnets');
            $ext= $file->getClientOriginalExtension();
            $file_name=changeSpaceInUnderscore(getEmpName($user_id).'-'.getMasterName($license_id).'-'.date('d-m-Y',strtotime($next_due))).'.'.$ext;
            // $file_name=changeSpaceInUnderscore(getEmpName($user_id)).'-'.changeSpaceInUnderscore(getMasterName($license_id)).'-'.date('d-m-Y-h-i').'.'.$ext;
            $file->move(public_path('uploads/pilot_certificate'), $file_name);
            $data->documents = $file_name;
        }

        $data->renewed_on= !empty($renewed_on)?date('Y-m-d',strtotime($renewed_on)):null;
        $data->number=$number;
        $data->planned_ren_date= !empty($planned_renewal_date)?date('Y-m-d',strtotime($planned_renewal_date)):null;
        $data->issued_on= !empty($issued_on)?date('Y-m-d',strtotime($issued_on)):null;
        $data->extended_date= !empty($extended_date)?date('Y-m-d',strtotime($extended_date)):null;
        $data->renewal_office=$renewal_office;
        $data->next_due=!empty($next_due)?date('Y-m-d',strtotime($next_due)):null;
        $data->status=$status;
        $data->remarks=$remarks;
        $data->save();
        return redirect()->route('app.pilot.licenses',$user_id)->with('success','License added successfully');
    }

    public function licensesEdit(Request $request)
    {
       $data= checkLicense($request->user_id,$request->license_id);
       return response()->json($data);
    }

    public function licensesUpdate(Request $request)
    {
        $edit_id=$request->edit_id;
        $user_id=$request->user_id;
        $license_id=$request->license_id;
        $renewed_on=$request->renewed_on;
        $number=$request->number;
        $planned_renewal_date=$request->plannedRenewalDate;
        $issued_on=$request->issued_on;
        $extended_date=$request->extended_date;
        $renewal_office=$request->renewal_office;
        $next_due=$request->next_due;
        $status=$request->status;
        $remarks=$request->remarks;
        $data= PilotLicense::find($edit_id);
        $data->user_id=$user_id;
        $data->license_id=$license_id;

        if($request->hasFile('documnets')) {
            $file = $request->file('documnets');
            $ext= $file->getClientOriginalExtension();
            $file_name=changeSpaceInUnderscore(getEmpName($user_id).'-'.getMasterName($license_id).'-'.date('d-m-Y',strtotime($next_due))).'.'.$ext;
            // $file_name=changeSpaceInUnderscore(getEmpName($user_id)).'-'.changeSpaceInUnderscore(getMasterName($license_id)).'-'.date('d-m-Y-h-i').'.'.$ext;
            $file->move(public_path('uploads/pilot_certificate'), $file_name);
            $data->documents = $file_name;
        }

        $data->renewed_on=(!empty($renewed_on)?date('Y-m-d',strtotime($renewed_on)):null);
        $data->number=$number;
        $data->planned_ren_date=(!empty($planned_renewal_date)?date('Y-m-d',strtotime($planned_renewal_date)):null);
        $data->issued_on=!empty($issued_on)?date('Y-m-d',strtotime($issued_on)):null;
        $data->extended_date=(!empty($extended_date)?date('Y-m-d',strtotime($extended_date)):null);
        $data->renewal_office=$renewal_office;
        $data->next_due=(!empty($next_due)?date('Y-m-d',strtotime($next_due)):null);
        $data->status=$status;
        $data->remarks=$remarks;
        $data->save();
        return redirect()->route('app.pilot.licenses',$user_id)->with('success','License added successfully');
    }

    public function trainingStore(Request $request)
    {
        $user_id=$request->user_id;
        $training_id=$request->training_id;
        $renewed_on=$request->renewed_on;
        $seat_occupied=$request->seat_occupied;
        $planned_renewal_date=$request->planned_renewal_date;
        $examiner=$request->examiner;
        $extended_date=$request->extended_date;
        $day_night=$request->day_night;
        $next_due=$request->next_due;
        $test_on=$request->test_on;
        $status=$request->status;
        $simulator_level=$request->simulator_level;
        $remarks=$request->remarks;
        $aircroft_registration=$request->aircroft_registration;
        $aircroft_type=$request->aircroft_type;
        $aircroft_model=$request->aircroft_model;
        $P1_hours=$request->P1_hours;
        $P2_hours=$request->P2_hours;
        $renewal_office=$request->renewal_office;
        $place_of_test=$request->place_of_test;
        $approach_details=$request->approach_details;
        $data=new PilotTraining;
        $data->user_id=$user_id;
        $data->training_id=$training_id;

        if($request->hasFile('documnets')) {
            $file = $request->file('documnets');
            $ext= $file->getClientOriginalExtension();
            $file_name=changeSpaceInUnderscore(getEmpName($user_id).'-'.getMasterName($training_id).'-'.date('d-m-Y',strtotime($next_due))).'.'.$ext;
            // $file_name=changeSpaceInUnderscore(getEmpName($user_id)).'-'.changeSpaceInUnderscore(getMasterName($training_id)).'-'.date('d-m-Y-h-i').'.'.$ext;
            $file->move(public_path('uploads/pilot_certificate'), $file_name);
            $data->documents = $file_name;
        }

        $data->renewed_on=!empty($renewed_on)?date('Y-m-d',strtotime($renewed_on)):null;
        $data->seat_occupied=$seat_occupied;
        $data->planned_renewal_date=!empty($planned_renewal_date)?date('Y-m-d',strtotime($planned_renewal_date)):null;
        $data->examiner=$examiner;
        $data->extended_date=!empty($extended_date)?date('Y-m-d',strtotime($extended_date)):null;
        $data->day_night=$day_night;
        $data->next_due=!empty($next_due)?date('Y-m-d',strtotime($next_due)):null;
        $data->test_on=$test_on;
        $data->status=$status;
        $data->simulator_level=$simulator_level;
        $data->remarks=$remarks;
        $data->aircroft_registration=$aircroft_registration;
        $data->aircroft_type=$aircroft_type;
        $data->aircroft_model=$aircroft_model;
        $data->P1_hours=$P1_hours;
        $data->P2_hours=$P2_hours;
        $data->renewal_office=$renewal_office;
        $data->place_of_test=$place_of_test;
        $data->approach_details=$approach_details;
        $data->created_by=Auth::user()->id;
        $data->updated_by=Auth::user()->id;
        $data->save();
        return redirect()->route('app.pilot.licenses',$user_id)->with('success','Training added successfully');
    }

    public function trainingEdit(Request $request)
    {
       $user_id=$request->user_id;
       $training_id=$request->training_id;
       $data=checkTraining($user_id,$training_id);
       return response()->json($data);
    }

    public function trainingUpdate(Request $request)
    {
        $edit_id=$request->edit_id;
        $user_id=$request->user_id;
        $training_id=$request->training_id;
        $renewed_on=$request->renewed_on;
        $seat_occupied=$request->seat_occupied;
        $planned_renewal_date=$request->planned_renewal_date;
        $examiner=$request->examiner;
        $extended_date=$request->extended_date;
        $day_night=$request->day_night;
        $next_due=$request->next_due;
        $test_on=$request->test_on;
        $status=$request->status;
        $simulator_level=$request->simulator_level;
        $remarks=$request->remarks;
        $aircroft_registration=$request->aircroft_registration;
        $aircroft_type=$request->aircroft_type;
        $aircroft_model=$request->aircroft_model;
        $P1_hours=$request->P1_hours;
        $P2_hours=$request->P2_hours;
        $renewal_office=$request->renewal_office;
        $place_of_test=$request->place_of_test;
        $approach_details=$request->approach_details;
        $data=PilotTraining::find($edit_id);
        $data->user_id=$user_id;
        $data->training_id=$training_id;
        if($request->hasFile('documnets')) {
            $file = $request->file('documnets');
            $ext= $file->getClientOriginalExtension();
            $file_name=changeSpaceInUnderscore(getEmpName($user_id).'-'.getMasterName($training_id).'-'.date('d-m-Y',strtotime($next_due))).'.'.$ext;

            // $file_name=changeSpaceInUnderscore(getEmpName($user_id)).'-'.changeSpaceInUnderscore(getMasterName($training_id)).'-'.date('d-m-Y-h-i').'.'.$ext;
            $file->move(public_path('uploads/pilot_certificate'), $file_name);
            $data->documents = $file_name;
        }

        $data->renewed_on=!empty($renewed_on)?date('Y-m-d',strtotime($renewed_on)):null;
        $data->seat_occupied=$seat_occupied;
        $data->planned_renewal_date=!empty($planned_renewal_date)?date('Y-m-d',strtotime($planned_renewal_date)):null;
        $data->examiner=$examiner;
        $data->extended_date=!empty($extended_date)?date('Y-m-d',strtotime($extended_date)):null;
        $data->day_night=$day_night;
        $data->next_due=!empty($next_due)?date('Y-m-d',strtotime($next_due)):null;
        $data->test_on=$test_on;
        $data->status=$status;
        $data->simulator_level=$simulator_level;
        $data->remarks=$remarks;
        $data->aircroft_registration=$aircroft_registration;
        $data->aircroft_type=$aircroft_type;
        $data->aircroft_model=$aircroft_model;
        $data->P1_hours=$P1_hours;
        $data->P2_hours=$P2_hours;
        $data->renewal_office=$renewal_office;
        $data->place_of_test=$place_of_test;
        $data->approach_details=$approach_details;
        $data->updated_by=Auth::user()->id;
        $data->save();
        return redirect()->route('app.pilot.licenses',$user_id)->with('success','Training updated successfully');

    }

    public function medicalStore(Request $request)
    {
        $user_id=$request->user_id;
        $medical_id=$request->medical_id;
        $medical_done_on=$request->medical_done_on;
        $medical_done_at=$request->medical_done_at;
        $medical_result=$request->medical_result;
        $planned_renewal_date=$request->planned_renewal_date;
        $extended_date=$request->extended_date;
        $mandatory_medical_center_count=$request->mandatory_medical_center_count;
        $next_due=$request->next_due;
        $status=$request->status;
        $remarks=$request->remarks;
        $limitations=$request->limitations;
        $data=new PilotMedical;
        $data->user_id=$user_id;
        $data->medical_id=$medical_id;
        if($request->hasFile('documnets')) {
            $file = $request->file('documnets');
            $ext= $file->getClientOriginalExtension();
            $file_name=changeSpaceInUnderscore(getEmpName($user_id).'-'.getMasterName($medical_id).'-'.date('d-m-Y',strtotime($next_due))).'.'.$ext;

            // $file_name=changeSpaceInUnderscore(getEmpName($user_id)).'-'.changeSpaceInUnderscore(getMasterName($medical_id)).'-'.date('d-m-Y-h-i').'.'.$ext;
            $file->move(public_path('uploads/pilot_certificate'), $file_name);
            $data->documents = $file_name;
        }

        $data->medical_done_on=!empty($medical_done_on)?date('Y-m-d',strtotime($medical_done_on)):null;
        $data->medical_done_at=$medical_done_at;
        $data->medical_result=$medical_result;
        $data->planned_renewal_date=!empty($planned_renewal_date)?date('Y-m-d',strtotime($planned_renewal_date)):null;
        $data->extended_date=!empty($extended_date)?date('Y-m-d',strtotime($extended_date)):null;
        $data->mandatory_medical_center_count=$mandatory_medical_center_count;
        $data->next_due=!empty($next_due)?date('Y-m-d',strtotime($next_due)):null;
        $data->status=$status;
        $data->remarks=$remarks;
        $data->limitations=$limitations;
        $data->created_by=Auth::user()->id;
        $data->updated_by=Auth::user()->id;
        $data->save();
        return redirect()->route('app.pilot.licenses',$user_id)->with('success','Medical updated successfully');
    }

    public function medicalEdit(Request $request)
    {
        $user_id=$request->user_id;
        $medical_id=$request->medical_id;
        $data=checkMedical($user_id,$medical_id);
        return response()->json($data);
    }

    public function medicalUpdate(Request $request)
    {
        $edit_id=$request->edit_id;
        $user_id=$request->user_id;
        $medical_id=$request->medical_id;
        $medical_done_on=$request->medical_done_on;
        $medical_done_at=$request->medical_done_at;
        $medical_result=$request->medical_result;
        $planned_renewal_date=$request->planned_renewal_date;
        $extended_date=$request->extended_date;
        $mandatory_medical_center_count=$request->mandatory_medical_center_count;
        $next_due=$request->next_due;
        $status=$request->status;
        $remarks=$request->remarks;
        $limitations=$request->limitations;
        $data= PilotMedical::find($edit_id);
        $data->user_id=$user_id;
        $data->medical_id=$medical_id;

        if($request->hasFile('documnets')) {
            $file = $request->file('documnets');
            $ext= $file->getClientOriginalExtension();
            $file_name=changeSpaceInUnderscore(getEmpName($user_id).'-'.getMasterName($medical_id).'-'.date('d-m-Y',strtotime($next_due))).'.'.$ext;

            // $file_name=changeSpaceInUnderscore(getEmpName($user_id)).'-'.changeSpaceInUnderscore(getMasterName($medical_id)).'-'.date('d-m-Y-h-i').'.'.$ext;
            $file->move(public_path('uploads/pilot_certificate'), $file_name);
            $data->documents = $file_name;
        }

        $data->medical_done_on=!empty($medical_done_on)?date('Y-m-d',strtotime($medical_done_on)):null;
        $data->medical_done_at=$medical_done_at;
        $data->medical_result=$medical_result;
        $data->planned_renewal_date=!empty($planned_renewal_date)?date('Y-m-d',strtotime($planned_renewal_date)):null;
        $data->extended_date=!empty($extended_date)?date('Y-m-d',strtotime($extended_date)):null;
        $data->mandatory_medical_center_count=$mandatory_medical_center_count;
        $data->next_due=!empty($next_due)?date('Y-m-d',strtotime($next_due)):null;
        $data->status=$status;
        $data->remarks=$remarks;
        $data->limitations=$limitations;
        $data->created_by=Auth::user()->id;
        $data->updated_by=Auth::user()->id;
        $data->save();
        return redirect()->route('app.pilot.licenses',$user_id)->with('success','Medical updated successfully');
    }

    public function qualificationStore(Request $request)
    {
        $user_id=$request->user_id;
        $qualification_id=$request->qualification_id;
        $renewed_on=$request->renewed_on;
        $number=$request->number;
        $planned_renewal_date=$request->plannedRenewalDate;
        $issued_on=$request->issued_on;
        $extended_date=$request->extended_date;
        $renewal_office=$request->renewal_office;
        $next_due=$request->next_due;
        $status=$request->status;
        $remarks=$request->remarks;
        $data=new PilotQualification;
        $data->user_id=$user_id;
        $data->qualification_id=$qualification_id;

        if($request->hasFile('documnets')) {
            $file = $request->file('documnets');
            $ext= $file->getClientOriginalExtension();
            $file_name=changeSpaceInUnderscore(getEmpName($user_id).'-'.getMasterName($qualification_id).'-'.date('d-m-Y',strtotime($next_due))).'.'.$ext;
            // $file_name=changeSpaceInUnderscore(getEmpName($user_id)).'-'.changeSpaceInUnderscore(getMasterName($qualification_id)).'-'.date('d-m-Y-h-i').'.'.$ext;
            $file->move(public_path('uploads/pilot_certificate'), $file_name);
            $data->documents = $file_name;
        }

        $data->renewed_on= !empty($renewed_on)?date('Y-m-d',strtotime($renewed_on)):null;
        $data->number=$number;
        $data->planned_ren_date= !empty($planned_renewal_date)?date('Y-m-d',strtotime($planned_renewal_date)):null;
        $data->issued_on= !empty($issued_on)?date('Y-m-d',strtotime($issued_on)):null;
        $data->extended_date= !empty($extended_date)?date('Y-m-d',strtotime($extended_date)):null;
        $data->renewal_office=$renewal_office;
        $data->next_due=!empty($next_due)?date('Y-m-d',strtotime($next_due)):null;
        $data->status=$status;
        $data->remarks=$remarks;
        $data->save();
       // die;
        return redirect()->route('app.pilot.licenses',$user_id)->with('success','Qualification added successfully');
    }

    public function qualificationEdit(Request $request)
    {
       $data= checkQualification($request->user_id,$request->qualification_id);
       return response()->json($data);
    }

    public function qualificationUpdate(Request $request)
    {
        $edit_id=$request->edit_id;
        $user_id=$request->user_id;
        $qualification_id=$request->qualification_id;
        $renewed_on=$request->renewed_on;
        $number=$request->number;
        $planned_renewal_date=$request->plannedRenewalDate;
        $issued_on=$request->issued_on;
        $extended_date=$request->extended_date;
        $renewal_office=$request->renewal_office;
        $next_due=$request->next_due;
        $status=$request->status;
        $remarks=$request->remarks;
        $data= PilotQualification::find($edit_id);
        $data->user_id=$user_id;
        $data->qualification_id=$qualification_id;
        // print_r($next_due);die();

        if($request->hasFile('documnets')) {
            $file = $request->file('documnets');
            $ext= $file->getClientOriginalExtension();
            $file_name=changeSpaceInUnderscore(getEmpName($user_id).'-'.getMasterName($qualification_id).'-'.date('d-m-Y',strtotime($next_due))).'.'.$ext;

            // $file_name=changeSpaceInUnderscore(getEmpName($user_id)).'-'.changeSpaceInUnderscore(getMasterName($qualification_id)).'-'.date('d-m-Y-h-i').'.'.$ext;
            $file->move(public_path('uploads/pilot_certificate'), $file_name);
            $data->documents = $file_name;
        }

        $data->renewed_on=(!empty($renewed_on)?date('Y-m-d',strtotime($renewed_on)):null);
        $data->number=$number;
        $data->planned_ren_date=(!empty($planned_renewal_date)?date('Y-m-d',strtotime($planned_renewal_date)):null);
        $data->issued_on=!empty($issued_on)?date('Y-m-d',strtotime($issued_on)):null;
        $data->extended_date=(!empty($extended_date)?date('Y-m-d',strtotime($extended_date)):null);
        $data->renewal_office=$renewal_office;
        $data->next_due=(!empty($next_due)?date('Y-m-d',strtotime($next_due)):null);
        $data->status=$status;
        $data->remarks=$remarks;
        $data->save();
        return redirect()->route('app.pilot.licenses',$user_id)->with('success','Qualification updated successfully');
    }

    public function groundTrainingStore(Request $request)
    {
        $user_id=$request->user_id;
        $training_id=$request->training_id;
        $renewed_on=$request->renewed_on;
        $seat_occupied=$request->seat_occupied;
        $planned_renewal_date=$request->planned_renewal_date;
        $examiner=$request->examiner;
        $extended_date=$request->extended_date;
        $day_night=$request->day_night;
        $next_due=$request->next_due;
        $test_on=$request->test_on;
        $status=$request->status;
        $simulator_level=$request->simulator_level;
        $remarks=$request->remarks;
        $aircroft_registration=$request->aircroft_registration;
        $aircroft_type=$request->aircroft_type;
        $aircroft_model=$request->aircroft_model;
        $P1_hours=$request->P1_hours;
        $P2_hours=$request->P2_hours;
        $renewal_office=$request->renewal_office;
        $place_of_test=$request->place_of_test;
        $approach_details=$request->approach_details;
        $data=new PilotGroundTraining;
        $data->user_id=$user_id;
        $data->training_id=$training_id;
        // print_r($next_due);die();

        if($request->hasFile('documnets')) {
            $file = $request->file('documnets');
            $ext= $file->getClientOriginalExtension();
            $file_name = changeSpaceInUnderscore(getEmpName($user_id) . '-' . getMasterName($training_id) . '-' . date('d-m-Y',strtotime($next_due))).'.'.$ext;
            // $file_name=changeSpaceInUnderscore(getEmpName($user_id)).'-'.changeSpaceInUnderscore(getMasterName($training_id)).'-'.date('d-m-Y-h-i').'.'.$ext;
            $file->move(public_path('uploads/pilot_certificate'), $file_name);
            $data->documents = $file_name;
        }

        $data->renewed_on=!empty($renewed_on)?date('Y-m-d',strtotime($renewed_on)):null;
        $data->seat_occupied=$seat_occupied;
        $data->planned_renewal_date=!empty($planned_renewal_date)?date('Y-m-d',strtotime($planned_renewal_date)):null;
        $data->examiner=$examiner;
        $data->extended_date=!empty($extended_date)?date('Y-m-d',strtotime($extended_date)):null;
        $data->day_night=$day_night;
        $data->next_due=!empty($next_due)?date('Y-m-d',strtotime($next_due)):null;
        $data->test_on=$test_on;
        $data->status=$status;
        $data->simulator_level=$simulator_level;
        $data->remarks=$remarks;
        $data->aircroft_registration=$aircroft_registration;
        $data->aircroft_type=$aircroft_type;
        $data->aircroft_model=$aircroft_model;
        $data->P1_hours=$P1_hours;
        $data->P2_hours=$P2_hours;
        $data->renewal_office=$renewal_office;
        $data->place_of_test=$place_of_test;
        $data->approach_details=$approach_details;
        $data->created_by=Auth::user()->id;
        $data->updated_by=Auth::user()->id;
        $data->save();
        return redirect()->route('app.pilot.licenses',$user_id)->with('success','Ground Training added successfully');
    }

    public function groundTrainingEdit(Request $request)
    {
       $user_id=$request->user_id;
       $training_id=$request->training_id;
       $data=checkGroundTraining($user_id,$training_id);
       return response()->json($data);
    }

    public function groundTrainingUpdate(Request $request)
    {
        $edit_id=$request->edit_id;
        $user_id=$request->user_id;
        $training_id=$request->training_id;
        $renewed_on=$request->renewed_on;
        $seat_occupied=$request->seat_occupied;
        $planned_renewal_date=$request->planned_renewal_date;
        $examiner=$request->examiner;
        $extended_date=$request->extended_date;
        $day_night=$request->day_night;
        $next_due=$request->next_due;
        $test_on=$request->test_on;
        $status=$request->status;
        $simulator_level=$request->simulator_level;
        $remarks=$request->remarks;
        $aircroft_registration=$request->aircroft_registration;
        $aircroft_type=$request->aircroft_type;
        $aircroft_model=$request->aircroft_model;
        $P1_hours=$request->P1_hours;
        $P2_hours=$request->P2_hours;
        $renewal_office=$request->renewal_office;
        $place_of_test=$request->place_of_test;
        $approach_details=$request->approach_details;
        $data=PilotGroundTraining::find($edit_id);
        $data->user_id=$user_id;
        $data->training_id=$training_id;
        // print_r($next_due);die();
        
        if($request->hasFile('documnets')) {
            $file = $request->file('documnets');
            $ext= $file->getClientOriginalExtension();
            $file_name = changeSpaceInUnderscore(getEmpName($user_id) . '-' . getMasterName($training_id) . '-' . date('d-m-Y',strtotime($next_due))).'.'.$ext;

            // $file_name=changeSpaceInUnderscore(getEmpName($user_id)).'-'.changeSpaceInUnderscore(getMasterName($training_id)).'-'.date('d-m-Y-h-i').'.'.$ext;
            $file->move(public_path('uploads/pilot_certificate'), $file_name);
            $data->documents = $file_name;
        }

        $data->renewed_on=!empty($renewed_on)?date('Y-m-d',strtotime($renewed_on)):null;
        $data->seat_occupied=$seat_occupied;
        $data->planned_renewal_date=!empty($planned_renewal_date)?date('Y-m-d',strtotime($planned_renewal_date)):null;
        $data->examiner=$examiner;
        $data->extended_date=!empty($extended_date)?date('Y-m-d',strtotime($extended_date)):null;
        $data->day_night=$day_night;
        $data->next_due=!empty($next_due)?date('Y-m-d',strtotime($next_due)):null;
        $data->test_on=$test_on;
        $data->status=$status;
        $data->simulator_level=$simulator_level;
        $data->remarks=$remarks;
        $data->aircroft_registration=$aircroft_registration;
        $data->aircroft_type=$aircroft_type;
        $data->aircroft_model=$aircroft_model;
        $data->P1_hours=$P1_hours;
        $data->P2_hours=$P2_hours;
        $data->renewal_office=$renewal_office;
        $data->place_of_test=$place_of_test;
        $data->approach_details=$approach_details;
        $data->updated_by=Auth::user()->id;
        $data->save();
        return redirect()->route('app.pilot.licenses',$user_id)->with('success','Ground Training updated successfully');

    }

    public function authorization($id)
    {
        $user = User::find($id);
        return view('pilot.authorization',compact('user'));
    }

    public function profile(Request $request)
    {
        $user = User::find(Auth::user()->id);
        return view('pilot.profile',compact('user'));
    }

    public function profileUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,'.Auth::user()->id,
            'phone' => 'required',
        ]);
        try {
            $user = User::find(Auth::user()->id);
            if($request->hasFile('profile')) {
                $file = $request->file('profile');
                $file->move(asset('uploads'), $file->getClientOriginalName());
                $user->profile = $file->getClientOriginalName();
                @unlink(asset('uploads/'.$user->profile));
            }
            $user->emp_id=$request->emp_id;
            $user->salutation=$request->salutation;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->address = $request->address;
            $user->phone = $request->phone;
            $user->save();
            return redirect()->route('pilot.profile')->with('success','User profile updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function password(Request $request)
    {
        $user = User::find(Auth::user()->id);
        return view('pilot.password',compact('user'));
    }

    public function passwordUpdate(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password',
        ]);
        try {
            $user = User::find(Auth::user()->id);
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()->with('error', 'Current password does not match');
            }
            $user->password = Hash::make($request->new_password);
            $user->save();
            return redirect()->route('pilot.password')->with('success','Password updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function leave()
    {
        return view('pilot.leave');
    }

    public function leaveList(Request $request)
    {
        $column=['id','user_id','master_id','leave_dates','documnets','status','created_at','id'];
        $users=Leave::with(['master','user'])->where('id','>',0);

        $total_row=$users->count();
        if (isset($_POST['search'])) {
            $users->where('leave_dates', 'LIKE', '%' . $_POST['search']['value'] . '%');
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
		foreach ($result as $key => $value) {
            $action = '';
            if($value->status=='applied'||$value->status=='inprocess')
            {
            $action  .= '<a href="'.route('app.pilot.leave.edit', $value->id).'" class="btn btn-primary btn-sm m-1">Edit</a>';
            }
            $action .= '<a href="javascript:void(0);" onclick="show(`'.route('app.pilot.leave.view', $value->id).'`);" class="btn btn-danger btn-sm m-1">View</a>';

            $status='<select class="form-control" onchange="changeStatus('.$value->id.',this.value);">';
            $status.='<option '.($value->status=='applied'?'selected':'').' value="applied">Applied</option>';
            $status.='<option '.($value->status=='inprocess'?'selected':'').' value="inprocess">Inprocess</option>';
            $status.='<option '.($value->status=='approved'?'selected':'').' value="approved">Approved</option>';
            $status.='<option '.($value->status=='cancelled'?'selected':'').' value="cancelled">Cancelled</option>';
            $status.='<option '.($value->status=='rejected'?'selected':'').' value="rejected">Rejected</option>';
            $status.='</select>';

            $sub_array = array();
			$sub_array[] = ++$key;
            $sub_array[] =  $value->user->salutation.' '.$value->user->name;
            $sub_array[] =  $value->master->name;
            $sub_array[] =  $value->leave_dates;
            $sub_array[] = !empty( $value->documnets)?'<a href="'.asset('uploads/leave/'.$value->documnets).'">View</a>':'';
            $sub_array[] =  $status;
            $sub_array[] =  $action;
            $data[] = $sub_array;
        }
        $output = array(
			"draw"       =>  intval($_POST["draw"]),
			"recordsTotal"   =>  $total_row,
			"recordsFiltered"  =>  $filter_row,
			"data"       =>  $data
		);
		echo json_encode($output);
    }

    public function leaveCreate()
    {
        $users=User::where('designation','=',1)->get();
        $leave_types=Master::where('type','=','leave_type')->get();
        return view('pilot.leave-create',compact('users','leave_types'));
    }

    
    public function leaveStore(Request $request)
    {
        $user_id=$request->user_id;
        $master_id=$request->master_id;
        $leave_dates=$request->leave_dates;
        $status=$request->status;
        $remarks=$request->remark; 
        $no_of_days=$request->no_of_days; 
        $date=explode('-',$leave_dates);
        $data=new Leave();
        if($request->hasFile('documnets')) {
            $file = $request->file('documnets');
            $file->move(public_path('uploads/leave'), $file->getClientOriginalName());
            $data->documnets = $file->getClientOriginalName();
            // @unlink(asset('uploads/leave/'.$data->documnets));
        }if($request->hasFile('other_doc')) {
            $file = $request->file('other_doc');
            $file->move(public_path('uploads/leave'), $file->getClientOriginalName());
            $data->other_doc = $file->getClientOriginalName();
            // @unlink(asset('uploads/leave/'.$data->other_doc));
        }
        $data->user_id=$user_id;
        $data->master_id=$master_id;
        $data->leave_dates=$leave_dates;
        $data->remark=$remarks;
        $data->no_of_days=$no_of_days;
        $data->from_date=date('Y-m-d',strtotime($date[0]));
        $data->to_date=date('Y-m-d',strtotime($date[1]));
        $data->status=$status;
        $data->save();
        return redirect()->route('app.pilot.leave')->with('success','Leave created successfully');
    }

    public function leaveEdit ($id)
    {
        $users=User::where('designation','=',1)->get();
        $leave_types=Master::where('type','=','leave_type')->get();
        $data=Leave::find($id);
        return view('pilot.leave-edit',compact('users','leave_types','data'));
    }

    public function leaveUpdate(Request $request,$id)
    {
        $user_id=$request->user_id;
        $master_id=$request->master_id;
        $leave_dates=$request->leave_dates;
        $remarks=$request->remark;
        $no_of_days=$request->no_of_days;
        $date=explode('-',$leave_dates);
        $status=$request->status;
        $data=Leave::find($id);
        if($request->hasFile('documnets')) {
            $file = $request->file('documnets');
            $file->move(public_path('uploads/leave'), $file->getClientOriginalName());
            $data->documnets = $file->getClientOriginalName();
            @unlink(asset('uploads/leave/'.$data->documnets));
        }
        if($request->hasFile('other_doc')) {
            $file = $request->file('other_doc');
            $file->move(public_path('uploads/leave'), $file->getClientOriginalName());
            $data->other_doc = $file->getClientOriginalName();
            @unlink(asset('uploads/leave/'.$data->other_doc));
        }
        $data->user_id=$user_id;
        $data->master_id=$master_id;
        $data->leave_dates=$leave_dates;
        $data->remark=$remarks;
        $data->no_of_days=$no_of_days;
        $data->from_date=date('Y-m-d',strtotime($date[0]));
        $data->to_date=date('Y-m-d',strtotime($date[1]));
        $data->status=$status;
        $data->save();
        return redirect()->route('app.pilot.leave')->with('success','Leave updated successfully');
    }
    
    public function updateLeaveStatus(Request $request)
    {
         try {
            $id=$request->id;
            $status=$request->status;
            $user = Leave::find($id);
            $user->status=$status;
            $user->save();
            return response()->json(['success' => true, 'message' => 'Status updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    public function checkValidLeave(Request $request)
    {
        $leave_dates=$request->leave_dates;
        $user_id=$request->user_id;
        $no_of_days=$request->no_of_days;
        if(empty($leave_dates)){
            return response()->json(['status' => false, 'message' => 'Please select leave dates']);
        }
        // if(empty($user_id)){
        //     return response()->json(['status' => false, 'message' => 'Please select user']);
        // }
        $date=explode('-',$leave_dates);
        $date_from=date('Y-m-d',strtotime($date[0]));
        $date_to=date('Y-m-d',strtotime($date[1]));
        
        $fromDate = \Carbon\Carbon::parse($date_from)->startOfDay();
        $toDate = \Carbon\Carbon::parse($date_to)->endOfDay();

        // $data = Leave::whereBetween('from_date', [$fromDate, $toDate])
        //               ->orWhereBetween('to_date', [$fromDate, $toDate])
        //               ->orWhere(function($q) use($fromDate,$toDate){
        //                     $q->where('from_date','>=',$fromDate);
        //                     $q->where('to_date','<=',$toDate);
        //                 })
        //               ->get();
        $data=Leave::where('id','>',0);   
        if(!empty($user_id))
        {
            $data->where('user_id',$user_id);
        }
        $data->where(function($m) use($date_from,$date_to){
                $m->where(function($q) use($date_from,$date_to){
                    $q->whereBetween('from_date',[$date_from,$date_to]);
                })->orWhere(function($q) use($date_from,$date_to){
                    $q->whereBetween('to_date',[$date_from,$date_to]);
                });
        });
        
        $data_=$data->get();
        
        $html='<table class="table table-striped" ><thead><tr><th>SN</th><th>User</th><th>From Date</th><th>To Date</th><th>Remark</th><th>Status</th></tr></thead><tbody >';
        foreach ($data_ as $key => $value) {
            $aircraft_cateogry='';
            $aircrafts = AirCraft::whereJsonContains("pilots", "$value->user_id")->get();
            foreach($aircrafts as $aircraft)
            {
                 $aircraft_cateogry=$aircraft->aircraft_cateogry;
            }
            $html.='<tr><td>'.++$key.'</td><td>'.getEmpFullName($value->user_id).' ('.$aircraft_cateogry.') </td><td>'.date('d-m-Y',strtotime($value->from_date)).' </td><td>'.date('d-m-Y',strtotime($value->to_date)).'</td><td>'.$value->remark.'</td><td>'.ucfirst($value->status).'</td></tr>';
        }
        $html.='</tbody></table>';
        
        $data=Leave::where('user_id',$user_id)->get();
        $total_leave='34';
        $apply_leave=0;
        $consumed_leave=0;
        $remaining_leave=0;
        foreach ($data as $key => $value) {
            $from_date=$value->from_date;
            $to_date=$value->to_date;
            $from_date=date('Y-m-d',strtotime($from_date));
            $to_date=date('Y-m-d',strtotime($to_date));
            $diff = date_diff(date_create($from_date), date_create($to_date));
            $apply_leave+=$value->no_of_days;//$diff->format('%a');
            if($value->status=='approved'){
                $consumed_leave+=$value->no_of_days;//$diff->format('%a');
            }
        }
        
        $remaining_leave=$total_leave-$apply_leave;
        if(!empty($no_of_days))
        {
           $remaining_leave= $remaining_leave-$no_of_days;
        }

        return response()->json(['status' => true, 'message' => $html,'total_leave'=>$total_leave,'apply_leave'=>$apply_leave,'consumed_leave'=>$consumed_leave,'remaining_leave'=>$remaining_leave]);
       
    }
    public function leaveShow($id)
    {
        $data=Leave::with(['master','user'])->find($id);
        $html='<table class="table">';
            $html.='<tr>';
                $html.='<th>Name</th>';
                $html.='<td>'. $data->user->salutation.' '.$data->user->name.'</td>';
            $html.='</tr>';
            $html.='<tr>';
                $html.='<th>Leave Type</th>';
                $html.='<td>'. $data->master->name.'</td>';
            $html.='</tr>';
            $html.='<tr>';
                $html.='<th>Leave Duration</th>';
                $html.='<td>'. $data->leave_dates.'</td>';
            $html.='</tr>';
            $html.='<tr>';
                $html.='<th>Reason</th>';
                $html.='<td>'. $data->reason.'</td>';
            $html.='</tr>';
            $html.='<tr>';
                $html.='<th>Status</th>';
                $html.='<td>'. ucfirst($data->status).'</td>';
            $html.='</tr>';
            
            $html.='<tr>';
                $html.='<th>Apply Date</th>';
                $html.='<td>'. date('d-m-Y',strtotime($data->created_at)).'</td>';
            $html.='</tr>';
        $html.='</table>';
        return response()->json([
            'success' => true,
            'data' => $html
        ]);
    }

    public function availability()
    {
        return view('pilot.availability');
    }

    public function availabilityList(Request $request)
    {
        $column=['id','name','email','phone','designation','status','id'];
        $users=User::with('designation')->where('designation','1')->where('status','active')->where('is_delete','0');

        $from_date=date('Y-m-d');
        if(!empty($_POST['from_date'])){
            $from_date = date('Y-m-d', strtotime($_POST['from_date']));
        }

        $total_row=$users->count();
        if (isset($_POST['search'])) {
            $users->where('name', 'LIKE', '%' . $_POST['search']['value'] . '%');
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
		foreach ($result as $key => $value) {

            $status = '<select class="form-control" disabled>';
            $status .= '<option ' . ($value->status == 'active' ? 'selected' : '') . ' value="active">Active</option>';
            $status .= '<option ' . ($value->status == 'inactive' ? 'selected' : '') . ' value="inactive">Inactive</option>';
            $status .= '</select>';
            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = $value->salutation . ' ' . $value->name;
            $sub_array[] = getBalanceFDTL($value->id, $from_date);

            $hours_last_six_months = getLastSixMonth($value->id, '578');
            list($hours, $minutes) = explode(':', $hours_last_six_months);
            $last_six_months_minutes = $hours * 60 + $minutes;

            $hours_last_30_days = getLast30Days($value->id, '578');
            list($hour, $minute) = explode(':', $hours_last_30_days);
            $last_30_days_minutes = $hour * 60 + $minute;

            if ($last_six_months_minutes >= 30 * 60) { // 30 hours minimum required for last 6 months
                if ($last_30_days_minutes >= 5 * 60) { // 5 hours minimum required for last 30 days
                    $availability = '<span class="btn btn-sm btn-success">Valid</span>';
                } else {
                    $availability = '<span class="btn btn-sm btn-warning">Not Valid (Last 30 Days)</span>';
                }
            } else {
                $availability = '<span class="btn btn-sm btn-danger">Not Valid (Last 6 Months)</span>';
            }

            $sub_array[] = $availability;
           
            $sub_array[] = checkUserLapsedCertificate($value->id,'training');//checkCrewTrainings($value->id,getCetificateIds($value->id,'training'));
            $sub_array[] = checkUserLapsedCertificate($value->id,'license');//checkCrewLicenses($value->id,getCetificateIds($value->id,'license'));
            $sub_array[] = checkUserLapsedCertificate($value->id,'medical');//checkCrewMedicals($value->id,getCetificateIds($value->id,'medical'));
            
            // $sub_array[] = checkCrewTrainings($value->id, getCetificateIds($value->id, 'training'), $from_date);
            // $sub_array[] = checkCrewLicenses($value->id, getCetificateIds($value->id, 'license'), $from_date);
            // $sub_array[] = checkCrewMedicals($value->id, getCetificateIds($value->id, 'medical'), $from_date);
            $sub_array[] = checkCrewLeaveStatus($value->id, $from_date);
            $sub_array[] = $status;
            $data[] = $sub_array;
        }
        $output = array(
            "draw"       =>  intval($_POST["draw"]),
            "recordsTotal"   =>  $total_row,
            "recordsFiltered"  =>  $filter_row,
            "data"       =>  $data
        );

		echo json_encode($output);
    }

    public function monitoring()
    {
       return view('pilot.monitoring');
    }

    public function monitoringList(Request $request)
    {
        $column=['id','name','email','phone','designation','status','id'];
        $users=User::with('designation')->where('designation','1')->where('status','active');

        $from_date=date('Y-m-d');
        if(!empty($_POST['from_date'])){
            $from_date = date('Y-m-d', strtotime($_POST['from_date']));
        }

        $total_row=$users->count();
        if (isset($_POST['search'])) {
            $users->where('name', 'LIKE', '%' . $_POST['search']['value'] . '%');
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
		foreach ($result as $key => $value) {

            $status='<select class="form-control" disabled>';
            $status.='<option '.($value->status=='active'?'selected':'').' value="active">Active</option>';
            $status.='<option '.($value->status=='inactive'?'selected':'').' value="inactive">Inactive</option>';
            $status.='</select>';
            $sub_array = array();
			$sub_array[] = ++$key;
            $sub_array[] = $value->salutation.' '.$value->name;
            $sub_array[] = '';
            $sub_array[] = '';
            $sub_array[] = checkUserLapsedCertificate($value->id,'training');//checkCrewTrainings($value->id,getCetificateIds($value->id,'training'));
            $sub_array[] = checkUserLapsedCertificate($value->id,'license');//checkCrewLicenses($value->id,getCetificateIds($value->id,'license'));
            $sub_array[] = checkUserLapsedCertificate($value->id,'medical');//checkCrewMedicals($value->id,getCetificateIds($value->id,'medical'));
            
            // $sub_array[] = checkCrewTrainings($value->id,getCetificateIds($value->id,'training'),$from_date);
            // $sub_array[] = checkCrewLicenses($value->id,getCetificateIds($value->id,'license'),$from_date);
            // $sub_array[] = checkCrewMedicals($value->id,getCetificateIds($value->id,'medical'),$from_date);
            $sub_array[] = '';
            $sub_array[] = $status;
            $data[] = $sub_array;
        }
        $output = array(
			"draw"       =>  intval($_POST["draw"]),
			"recordsTotal"   =>  $total_row,
			"recordsFiltered"  =>  $filter_row,
			"data"       =>  $data
		);

		echo json_encode($output);
    }

    public function certificatDelete(Request $request)
    {
       $docType=$request->docType;
       $id=$request->id;
       $data='';
       if($docType=='license')
       {
          $data= PilotLicense::find($id);
       }
       if($docType=='training')
       {
           $data=PilotTraining::find($id);
       }
       if($docType=='medical')
       {
          $data= PilotMedical::find($id);
       }
       if($docType=='ground_training')
       {
          $data= PilotGroundTraining::find($id);
       }
       if($docType=='qualification')
        {
            $data= PilotQualification::find($id);

        }
       $result['status']=true;
       if(!empty($data)&&!empty($data->documents))
       {
           @unlink(public_path('uploads/pilot_certificate/'.$data->documents));
           $data->documents=null;
           $data->save();
           $result['status']=true;
       }

       return response()->json($result);
    }

    public function certificatApplicable(Request $request)
    {
        $user_id=$request->user_id;
        $docType=$request->type;
        $id=$request->id;
        $status=$request->status;
        $data='';
        if($docType=='license')
        {
            $data= PilotLicense::where('user_id',$user_id)->where('license_id',$id)->first();
            if(empty($data))
            {
                $data=new PilotLicense;
                $data->license_id=$id;
                $data->user_id=$user_id;
                $data->is_applicable=$status;
                $data->save();
            }else{
                $data= PilotLicense::where('user_id',$user_id)->where('license_id',$id);
                $data->update(['is_applicable'=>$status]);
            }

        }
        if($docType=='training')
        {
            $data=PilotTraining::where('user_id',$user_id)->where('training_id',$id)->first();
            if(empty($data))
            {
                $data=new PilotTraining;
                $data->training_id=$id;
                $data->user_id=$user_id;
                $data->is_applicable=$status;
                $data->save();
            }else{
                $data= PilotTraining::where('user_id',$user_id)->where('training_id',$id);
                $data->update(['is_applicable'=>$status]);
            }

        }
        if($docType=='medical')
        {
            $data= PilotMedical::where('user_id',$user_id)->where('medical_id',$id)->first();
            if(empty($data))
            {
                $data=new PilotMedical;
                $data->medical_id=$id;
                $data->user_id=$user_id;
                $data->is_applicable=$status;
                $data->save();
            }else{
                $data= PilotMedical::where('user_id',$user_id)->where('medical_id',$id);
                $data->update(['is_applicable'=>$status]);
            }

        }
        if($docType=='ground_training')
        {
            $data= PilotGroundTraining::where('user_id',$user_id)->where('training_id',$id)->first();
            if(empty($data))
            {
                $data=new PilotGroundTraining;
                $data->training_id=$id;
                $data->user_id=$user_id;
                $data->is_applicable=$status;
                $data->save();
            }else{
                $data= PilotGroundTraining::where('user_id',$user_id)->where('training_id',$id);
                $data->update(['is_applicable'=>$status]);
            }

        }
        if($docType=='qualification')
        {
            $data= PilotQualification::where('user_id',$user_id)->where('qualification_id',$id)->first();
            if(empty($data))
            {
                $data=new PilotQualification;
                $data->qualification_id=$id;
                $data->user_id=$user_id;
                $data->is_applicable=$status;
                $data->save();
            }else{
                $data= PilotQualification::where('user_id',$user_id)->where('qualification_id',$id);
                $data->update(['is_applicable'=>$status]);
            }
        }
        $result['status']=true;

       return response()->json($result);
    }

    public function flyingHourMonthly()
    {
        return view('pilot.flying_hours_monthly');
    }

    public function pilotFlyingHoursMonthlyPrint($from='',$to='')
    {
        if(empty($from)||empty($to))
        {
            return 'Please select from date or to date';
        }
        $data['from'] = $from;
        $data['to'] = $to;
        $data['months'] = get_month_list($from, $to);
        // $users = User::with('designation')->where('designation','1')->where('is_delete','0')->where('status','active')->get();
        $data['fixed_wing_pilots'] = getCategoriesPilots('Fixed Wing');
        $data['rotor_wing_pilots'] = getCategoriesPilots('Rotor Wing');
        return view('pilot.print-flying-hours-monthly',$data);
    }

    public function documents($user_id)
    {
         return view('pilot.documents',compact('user_id'));
    }

    public function documentsList(Request $request)
    {
        $column=['id','title','document','created_at','id'];
        $users = UserDocument::where('user_id',$request->user_id)->where('is_delete','0');

        $total_row=$users->count();
        if (isset($_POST['search'])) {
            $users->where('title', 'LIKE', '%' . $_POST['search']['value'] . '%');
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
		foreach ($result as $key => $value) {
            if(!empty($value->document)){
                $view = '<a href="'.asset('uploads/documents/'.$value->document).'" target="_blank" class="btn btn-info btn-sm m-1"><i class="fa fa-eye" aria-hidden="true"></i> </a>';
                $view .= '<a href="'.asset('uploads/documents/'.$value->document).'" class="btn btn-primary btn-sm m-1" download><i class="fa fa-download" aria-hidden="true"></i> </a>';
            }else{
                $view = '';
            }
            $action = '<a href="javascript:void(0);" onclick="editRole(`' . route('app.pilot.documents.edit', $value->id) . '`);" class="btn btn-warning btn-sm m-1">Edit</a>';
            $action .= '<a href="javascript:void(0);" onclick="deleted(`' . route('app.pilot.documents.delete', $value->id) . '`);" class="btn btn-danger btn-sm m-1">Delete</a>';

            $sub_array = array();
			$sub_array[] = ++$key;
            $sub_array[] = ucWords($value->title);
            $sub_array[] = $view;
            $sub_array[] = date('d-m-Y',strtotime($value->created_at));
            $sub_array[] = $action;
            $data[] = $sub_array;
        }
        $output = array(
			"draw"       =>  intval($_POST["draw"]),
			"recordsTotal"   =>  $total_row,
			"recordsFiltered"  =>  $filter_row,
			"data"       =>  $data
		);

		echo json_encode($output);
    }

    public function documentsStore(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'title' => 'required',
            'document' => ($request->edit_id ? 'nullable' : 'required'),
        ]);
        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validation->errors()
            ]);
        }
        $edit_id = $request->edit_id;
        try {
            if (!empty($edit_id)) {
                $master = UserDocument::find($edit_id);
                $message = 'Document Updated Successfully';
            } else {
                $master = new UserDocument();
                $message = 'Document Added Successfully';
            }
            if ($request->hasFile('document')) {
                $file = $request->file('document');
                $ext = $file->getClientOriginalExtension();
                $FileName = str_replace(' ', '_', $request->title). '_' . date('y-m-d-h-i') . '.' . $ext;
                $file->move(public_path('uploads/documents'), $FileName);
                $master->document = $FileName;
            }

            $master->title = $request->title;
            $master->user_id = $request->user_id;
            $master->save();
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    public function documentsEdit($id)
    {
        $role = UserDocument::find($id);
        return response()->json([
            'success' => true,
            'data' => $role
        ]);
    }

    public function documentsDelete($id)
    {
        $data = UserDocument::find($id);
        $data->is_delete='1';
        $data->save();
        return response()->json([
            'success' => true,
            'message' => 'Document Deleted Successfully'
        ]);
    }
    
    public function viewCertificateLogs($type, $user_id, $id)
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
        return view('pilot.view-certificate-logs',compact('sub_title','type','user_id','id'));
    }
    
    public function getCertificateLogList(Request $request)
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
                $action  = '<a href="javascript:void(0)" class="text-red" style="text-decoration: none !important;">Not Available</a>';
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
    
}
