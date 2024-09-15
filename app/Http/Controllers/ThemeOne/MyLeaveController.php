<?php

namespace App\Http\Controllers\ThemeOne;


use App\Http\Controllers\Controller;

use App\Models\Master;
use App\Models\LeaveAssign;
use App\Models\File;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
class MyLeaveController extends Controller
{
    public function index()
    {
        return view('theme-one.my-leave.index');
    }

    public function apply()
    {
        $user = Auth::user();
        $leave_types=LeaveAssign::with('master')->where('designation_id',$user->designation)->get();
        return view('theme-one.my-leave.create',compact('leave_types'));
        
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
        
        return view('theme-one.my-leave.edit',compact('leave_types','data'));
        

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
    
    public function leave()
    {
        return view('theme-one.users.leave');
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
            $action  .= '<a href="'.route('user.leave.edit', $value->id).'" class="btn btn-primary btn-sm m-1">Edit</a>';
            }
            $action .= '<a href="javascript:void(0);" onclick="show(`'.route('user.leave.view', $value->id).'`);" class="btn btn-danger btn-sm m-1">View</a>';

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
            $sub_array[] = !empty( $value->documnets)?'<a href="'.asset('uploads/leave/'.$value->documnets).'" target="blank">View</a>':'';
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
        return view('theme-one.users.leave-create',compact('users','leave_types'));
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
        return redirect()->route('user.leave')->with('success','Leave created successfully');
    }
    
    public function leaveEdit ($id)
    {
        $users=User::where('designation','=',1)->get();
        $leave_types=Master::where('type','=','leave_type')->get();
        $data=Leave::find($id);
        return view('theme-one.users.leave-edit',compact('users','leave_types','data'));
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
        return redirect()->route('user.leave')->with('success','Leave updated successfully');
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
    
     public function leaveReport()
    {
        return view('theme-one.users.leave-report');
    }

    public function leaveReportList(Request $request)
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
            $status=ucfirst($value->status);
            
            $sub_array = array();
			$sub_array[] = ++$key;
            $sub_array[] =  $value->user->salutation.' '.$value->user->name;
            $sub_array[] =  $value->master->name;
            $sub_array[] =  $value->leave_dates;
            $sub_array[] = !empty( $value->documnets)?'<a href="'.asset('uploads/leave/'.$value->documnets).'" target="blank">View</a>':'';
            $sub_array[] =  $status;
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
}
