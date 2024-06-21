<?php

namespace App\Http\Controllers;

use App\Models\Master;
use App\Models\LeaveAssign;
use App\Models\File;
use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
class MyLeaveController extends Controller
{
    public function index()
    {
        if(getUserType()=='user')
        {
            return view('theme-one.my-leave.index');
        }else{
            return view('my-leave.index');
        }
    }
    
    public function apply()
    {
        $user = Auth::user();
        $leave_types=LeaveAssign::with('master')->where('designation_id',$user->designation)->get();
        if(getUserType()=='user')
        {
            return view('theme-one.my-leave.create',compact('leave_types'));
        }else{
            return view('my-leave.create',compact('leave_types'));
        }
    }
    
    public function store(Request $request)
    {
        $user = Auth::user();
        $user_id=$user->id;
        $master_id=$request->master_id;
        $leave_dates=$request->leave_dates;
        $reason=$request->reason;
        $date=explode('-',$leave_dates);
        $data=new Leave();
        if($request->hasFile('documnets')) {
            $file = $request->file('documnets');
            $file->move(public_path('uploads/leave'), $file->getClientOriginalName());
            $data->documnets = $file->getClientOriginalName();
            // @unlink(asset('uploads/leave/'.$data->documnets));
        }
        $data->user_id=$user_id;
        $data->master_id=$master_id;
        $data->leave_dates=$leave_dates;
        $data->from_date=date('Y-m-d',strtotime($date[0]));
        $data->to_date=date('Y-m-d',strtotime($date[1]));
        $data->reason=$reason;
        $data->save();
        return redirect()->back()->with('success','Leave created successfully');
    }

    public function list(Request $request)
    {
        $user = Auth::user();
        $column=['id','user_id','master_id','leave_dates','documnets','status','created_at','id'];
        $users=Leave::with(['master','user'])->where('user_id',$user->id);

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
            if($value->status=='applied')
            {
                if(getUserType()=='user')
                {
                    $action  .= '<a href="'.route('user.my.leave.edit', $value->id).'" class="btn btn-primary btn-sm m-1">Edit</a>';
                }else{
                    $action  .= '<a href="'.route('app.my.leave.edit', $value->id).'" class="btn btn-primary btn-sm m-1">Edit</a>';
                }
            }
            if($value->status!='cancelled')
            {
                if(getUserType()=='user')
                {
                    $action .= '<a href="'.route('user.my.leave.cancelled', $value->id).'" class="btn btn-danger btn-sm m-1">Cancel</a>';
                }else{
                    $action .= '<a href="'.route('app.my.leave.cancelled', $value->id).'" class="btn btn-danger btn-sm m-1">Cancel</a>';
                }
            }
            $sub_array = array();
			$sub_array[] = ++$key;
            $sub_array[] =  $value->master->name;
            $sub_array[] =  $value->leave_dates;
            $sub_array[] =  date('d-m-Y',strtotime($value->created_at));
            $sub_array[] = !empty( $value->documnets)?'<a href="'.asset('uploads/leave/'.$value->documnets).'">View</a>':'';
            $sub_array[] =  ucfirst($value->status);
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
        $user = Auth::user();
        $leave_types=LeaveAssign::with('master')->where('designation_id',$user->designation)->get();
        $data=Leave::find($id);
        if(getUserType()=='user')
        {   
            return view('theme-one.my-leave.edit',compact('leave_types','data'));
        }else{
            return view('my-leave.edit',compact('leave_types','data'));
        }
      
    }
    
    public function update(Request $request,$id)
    {
        $user = Auth::user();
        $user_id=$user->id;
        $master_id=$request->master_id;
        $leave_dates=$request->leave_dates;
        $date=explode('-',$leave_dates);
        $reason=$request->reason;
        $data=Leave::find($id);
        if($request->hasFile('documnets')) {
            $file = $request->file('documnets');
            $file->move(public_path('uploads/leave'), $file->getClientOriginalName());
            $data->documnets = $file->getClientOriginalName();
            @unlink(asset('uploads/leave/'.$data->documnets));
        }
        $data->user_id=$user_id;
        $data->master_id=$master_id;
        $data->leave_dates=$leave_dates;
        $data->from_date=date('Y-m-d',strtotime($date[0]));
        $data->to_date=date('Y-m-d',strtotime($date[1]));
        $data->reason=$reason;
        $data->save();
        return redirect()->back()->with('success','Leave updated successfully');
    }

    public function cancelled($id)
    {
        $model = Leave::findOrFail($id);
        $model->status='cancelled';
        $model->save();
        return redirect()->back()->with('success','Leave Cancelled successfully');
    }

}
