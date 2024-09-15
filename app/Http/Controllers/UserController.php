<?php

namespace App\Http\Controllers;

use App\Models\AirCraft;
use App\Models\Master;
use App\Models\MasterAssign;
use Illuminate\Http\Request;
use App\Models\City;
use App\Models\User;
use App\Models\State;
use App\Models\UserCertificate;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    public function __construct()
    {
        //$this->middleware(['permission:Employee Add|Employee Edit|Employee Delete|Employee View']);
    }
    public function index()
    {
        return view('users.index');
    }

    public function create()
    {
        $departments =Master::where('type','department')->where('status','active')->get();
        $designations =Master::where('type','designation')->where('status','active')->get();
        return view('users.create',compact('departments','designations'));
    }

    public function getSection(Request $request)
    {
        $id = $request->id;
        $user_id = $request->user_id;
        $section = [];
        $html = '';

        // Check if $id is an array and not empty
        if (!empty($id) && is_array($id)) {
            if (!empty($user_id)) {
                $user = User::find($user_id);
                $section = $user->section ?? [];
            }

            foreach ($id as $key => $value) {
                $row = Master::find($value);
                if ($row) {
                    $html .= '<optgroup label="' . $row->name . '">';
                    $data = Master::where('parent_id', $row->id)->where('status', 'active')->get();
                    foreach ($data as $item) {
                        $selected = !empty($section) && in_array($item->id, $section) ? 'selected' : '';
                        $html .= '<option value="' . $item->id . '" ' . $selected . '>' . $item->name . '</option>';
                    }
                    $html .= '</optgroup>';
                }
            }
        }

        return response()->json([
            'success' => true,
            'data' => $html
        ]);
    }

    public function getJobFunction(Request $request)
    {
        $id = $request->id;
        $user_id = $request->user_id;
        $jobfunction = [];
        $html = '';

        // Check if $id is an array and not empty
        if (!empty($id) && is_array($id)) {
            if (!empty($user_id)) {
                $user = User::find($user_id);
                $jobfunction = $user->jobfunction ?? [];
            }

            foreach ($id as $key => $value) {
                $row = Master::find($value);
                if ($row) {
                    $html .= '<optgroup label="' . $row->name . '">';
                    $data = Master::where('parent_id', $row->id)->where('status', 'active')->get();
                    foreach ($data as $item) {
                        $selected = !empty($jobfunction) && in_array($item->id, $jobfunction) ? 'selected' : '';
                        $html .= '<option value="' . $item->id . '" ' . $selected . '>' . $item->name . '</option>';
                    }
                    $html .= '</optgroup>';
                }
            }
        }

        return response()->json([
            'success' => true,
            'data' => $html
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
            return redirect()->route('app.users')->with('success','User created successfully');
        } catch (\Exception $e) {
            print_r( $e->getMessage());die;
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function list(Request $request)
    {
        $column=['id','id','emp_id','name','email','phone','designation','status','created_at','id','id'];
        $users=User::with('designation')->where('is_delete','0');

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
        if(!empty($_POST['status'])){
            $users->where('status',$_POST['status']);
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
            
            $action  .= '<a href="'.route('app.users.edit', $value->id).'" class="btn btn-warning btn-sm m-1">Edit</a>';
            $action .= '<a href="'.route('app.users.roles', $value->id).'" class="btn btn-success btn-sm m-1">Assign Role</a>';
            $action .= '<a href="'.route('app.users.licenses', $value->id).'" class="btn btn-success btn-sm m-1">Manage License</a>';

            
            // $action  = '<a href="'.route('app.users.edit', $value->id).'" class="btn btn-warning btn-sm m-1">Edit</a>';

            if($value->id!=1)
            {
                $action .= '<a href="javascript:void(0);" onclick="deleted(`'.route('app.users.destroy', $value->id).'`);" class="btn btn-danger btn-sm m-1">Delete</a>';
            }
             $status='NA';
            if($value->id!=1)
            {
                $status='<select class="form-control" onchange="changeStatus('.$value->id.',this.value);">';
                $status.='<option '.($value->status=='active'?'selected':'').' value="active">Active</option>';
                $status.='<option '.($value->status=='inactive'?'selected':'').' value="inactive">Inactive</option>';
                $status.='</select>';
            }

            $sub_array = array();
			$sub_array[] = ++$key;
            $sub_array[] = '<img src="'.is_image('uploads/'.$value->profile).'" width="50" height="50" class="img-thumbnail" />';
            $sub_array[] = $value->emp_id;
            $sub_array[] = $value->salutation.' '.$value->name;
            $sub_array[] = $value->email;
            $sub_array[] = $value->phone;
            $sub_array[] = $value->designation()->first()->name??'';
            $sub_array[] = $status;
            $sub_array[] = date('d-m-Y',strtotime($value->created_at));
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
        $states = State::where('country_id','101')->where('is_delete','0')->get();
        $per_city = City::where('state_id',$user->per_state)->where('is_delete','0')->get();
        $tem_city = City::where('state_id',$user->tem_state)->where('is_delete','0')->get();
        return view('users.edit',compact('user','departments','designations','states','per_city','tem_city'));
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
            return redirect()->route('app.users')->with('success','User updated successfully');
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
            return response()->json(['success' => true, 'message' => 'User Deleted successfully.']);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function role($id)
    {
        $user = User::find($id);
        $userRoles = $user->roles->pluck('id')->toArray();
        $roles=Role::all();
        return view('users.role',compact('user','roles','userRoles'));
    }

    public function roleStore(Request $request,$id)
    {
        try {
            $user = User::find($id);

            $user->roles()->sync($request->roles);
            return redirect()->route('app.users')->with('success','User role updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function licenses($id)
    {
        $user = User::find($id);
        $designation[]=$user->designation??'0';
        $section=$user->section??[];
        $jobfunction=$user->jobfunction??[];
        $a=AirCraft::whereJsonContains('pilots',$id)->pluck('id')->toArray();
        $d=array_merge($designation,$section,$jobfunction);
        $license1=MasterAssign::whereIn('master_id',$d)->where('is_for','user')->pluck('certificate_id')->toArray();
        $license2=MasterAssign::whereIn('master_id',$a)->where('is_for','aircraft')->pluck('certificate_id')->toArray();
        $license=array_merge($license1,$license2);
        
        //$license=Master::whereIn('id',$license)->get();
        
        $licenses=Master::whereIn('id',$license)->where('sub_type','license')->where('status','active')->get();
        $trainings=Master::whereIn('id',$license)->where('sub_type','training')->where('status','active')->get();
        $qualifications=Master::whereIn('id',$license)->where('sub_type','qualification')->where('status','active')->get();
        $medicals=Master::whereIn('id',$license)->where('sub_type','medical')->where('status','active')->get();
        $ground_trainings=Master::whereIn('id',$license)->where('sub_type','ground_training')->where('status','active')->get();
        return view('users.licenses',compact('user','license','licenses','trainings','qualifications','medicals','ground_trainings')); 
    }

    public function licensesStore(Request $request,$id)
    {
        //echo $id;die;
        try {
            $master_id=$request->master_id;
            $id_current_for_flying=$request->id_current_for_flying;
            $is_mandatory=$request->is_mandatory;
            $is_lifetime=$request->is_lifetime;
            $certificate_type=$request->certificate_type;
            UserCertificate::where('user_id',$id)->delete();
            
            $licenses=array();
            foreach($master_id as $key => $value)
            {
                $data =new UserCertificate;
                $data->user_id=$id;
                $data->master_id=$value;
                $data->id_current_for_flying=isset($id_current_for_flying[$key])?$id_current_for_flying[$key]:'no';
                $data->is_mandatory=isset($is_mandatory[$key])?$is_mandatory[$key]:'no';
                $data->is_lifetime=isset($is_lifetime[$key])?$is_lifetime[$key]:'no';
                $data->certificate_type=isset($certificate_type[$key])?$certificate_type[$key]:'no';
                $data->save();
                //$licenses[]=array(
                //                'master_id'=>$value,
                //                'id_current_for_flying'=>$id_current_for_flying[$key]??'no',
                //                'is_mandatory'=>$is_mandatory[$key]??'no',
                //                'is_lifetime'=>$is_lifetime[$key]??'no',
                //                'certificate_type'=>$certificate_type[$key]
                //                );
            }
            //$user = User::find($id);
            //$user->certificates()->sync($licenses);
            return redirect()->route('app.users')->with('success','User licenses updated successfully');
        } catch (\Exception $e) {

        return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function profile(Request $request)
    {
        $user = User::find(Auth::user()->id);
        if(getUserType()=='admin')
        {
            return view('users.profile',compact('user'));
        }else{
            return view('theme-one.users.profile',compact('user'));
        }
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
            return redirect()->route('user.profile')->with('success','User profile updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function password(Request $request)
    {
        $user = User::find(Auth::user()->id);
        if(getUserType()=='admin')
        {
            return view('users.password',compact('user'));
        }else{
            return view('theme-one.users.password',compact('user'));
        }
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
            return redirect()->route('user.password')->with('success','Password updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function getUserBySection(Request $request)
    {
        $users = User::whereJsonContains('section', $request->id)->where('status','active')->where('is_delete','0')->get();
        $html='';
        $html = '<option value="">Please Select</option>';
        foreach ($users as $user) {
            $html .= '<option value="' . $user->fullName() . '">' . $user->fullName() . '</option>';
        }
        return response()->json($html);
    }

}
