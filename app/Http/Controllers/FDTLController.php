<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\FlyingLog;
use App\Models\PilotViolation;
use App\Models\PilotFlyingLog;
use App\Models\ExternalFlyingLog;

class FDTLController extends Controller
{
    public function index()
    {
        return view('fdtl.index');
    }
 
    public function list(Request $request)
    {
        $column=['id','id','emp_id','name','email','phone','designation','created_at','id','id'];
        $users=User::with('designation')->where('id','>','0')->where('is_delete','0')->where('status','active');

        $users->where('designation', '=',1);

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
            $action = '';
            $action  .= '<a href="'.route('app.fdtl.report', $value->id).'" class="btn btn-warning btn-sm m-1">Report</a>';
            // $action  = '<a href="'.route('app.users.edit', $value->id).'" class="btn btn-warning btn-sm m-1">Edit</a>';
            $sub_array = array();
			$sub_array[] = ++$key;
            $sub_array[] = '<img src="'.is_image('uploads/'.$value->profile).'" width="50" height="50" class="img-thumbnail" />';
            $sub_array[] = $value->emp_id;
            $sub_array[] = $value->salutation.' '.$value->name;
            $sub_array[] = $value->email;
            $sub_array[] = $value->phone;
            $sub_array[] = $value->designation()->first()->name??'';
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

    public function report($id)
    {
        $user=User::find($id);
        if(getUserType()=='user')
        {
            return view('theme-one.fdtl.report',compact('id','user'));
        }else{
            return view('fdtl.report',compact('id','user'));
        }
    }

    public function getReport(Request $request)
    {
        $user_id = $request['id'];
        $from = date('Y-m-d', strtotime($request->from_date));
        $to = date('Y-m-d', strtotime($request->to_date));
        $data['data'] = FlyingLog::where(function ($q) use ($user_id) {
            $q->where('pilot1_id', $user_id)->orWhere('pilot2_id', $user_id);
        })->whereBetween('date', [$from, $to])->groupBy('date')->orderBy('date','asc')->get();
        $data['user_id'] =$user_id;
        return view('fdtl.get-report', $data)->render();
    }

    public function monitoring()
    {
        return view('fdtl.monitoring');
    }
    
    public function voilations()
    {
        $data['currentDate'] = now();
        $data['last1Days'] = now()->subDay()->startOfDay();
        $data['last7Days'] = now()->subDays(6)->startOfDay();
        $data['monthToDate'] = now()->startOfMonth();
        $data['last30Days'] = now()->subDays(29)->startOfDay();
        $data['yearToDate'] = now()->startOfYear();
        $data['last365Days'] = now()->subDays(364)->startOfDay();
        return view('fdtl.voilations', $data);
    }
    
    public function voilationDetails(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $violation_type = $request->violation_type;
        if($from == $to){
            $data = PilotViolation::where('violation_type', $violation_type)->orderBy('dates', 'desc')->get();
        }else{
            $data = PilotViolation::where('violation_type', $violation_type)->whereBetween('dates', [$from, $to])->orderBy('dates', 'desc')->get();
        }
        $html = '';
        foreach ($data as $key => $value) {
            
            $log_date='';
            $d=PilotFlyingLog::where('flying_log_id',$value->flying_log_id)->where('user_id',$value->user->id)->get();
            foreach($d as $ds)
            {
                if($ds->log_type=='internal')
                {
                    if($violation_type=='Flight_Duty_Period')
                    {
                        $m=FlyingLog::find($value->start_log_id);
                        if(!empty($m))
                        {
                            $log_date.='<b>FDT START</b><br>';
                            $log_date.='Start : '.date('d-m-Y H:i',strtotime($m->departure_time)).' End : '.date('d-m-Y H:i',strtotime($m->arrival_time));
                            $log_date.='<br>From : '.$m->fron_sector.' To : '.$m->to_sector.'<br>'.getAirCraft($m->aircraft_id)->call_sign;
                        }
                        
                        $m=FlyingLog::find($value->flying_log_id);
                        if(!empty($m))
                        {
                            $log_date.='<br><b>FDT END</b><br>';
                            $log_date.='Start : '.date('d-m-Y H:i',strtotime($m->departure_time)).' End : '.date('d-m-Y H:i',strtotime($m->arrival_time));
                            $log_date.='<br>From : '.$m->fron_sector.' To : '.$m->to_sector.'<br>'.getAirCraft($m->aircraft_id)->call_sign;
                        }
                    }else{
                        $m=FlyingLog::find($value->flying_log_id);
                        if(!empty($m))
                        {
                            $log_date.='Start : '.date('d-m-Y H:i',strtotime($m->departure_time)).' End : '.date('d-m-Y H:i',strtotime($m->arrival_time));
                            $log_date.='<br>From : '.$m->fron_sector.' To : '.$m->to_sector.'<br>'.getAirCraft($m->aircraft_id)->call_sign;
                        }
                    }
                }else{
                    
                    if($violation_type=='Flight_Duty_Period')
                    {
                        $m=ExternalFlyingLog::find($value->start_log_id);
                        if(!empty($m))
                        {
                            $log_date.='<b>FDT START</b><br>';
                            $log_date.='Start : '.date('d-m-Y H:i',strtotime($m->departure_time)).' To : '.date('d-m-Y H:i',strtotime($m->arrival_time));
                            $log_date.='<br>From : '.$m->fron_sector.' To : '.$m->to_sector.'<br>'.getAirCraft($m->aircraft_id)->call_sign;
                        }
                        
                        $m=ExternalFlyingLog::find($value->flying_log_id);
                        if(!empty($m))
                        {
                            $log_date.='<br><b>FDT END</b><br>';
                            $log_date.='Start : '.date('d-m-Y H:i',strtotime($m->departure_time)).' To : '.date('d-m-Y H:i',strtotime($m->arrival_time));
                            $log_date.='<br>From : '.$m->fron_sector.' To : '.$m->to_sector.'<br>'.getAirCraft($m->aircraft_id)->call_sign;
                        }
                        
                    }else{
                        $m=ExternalFlyingLog::find($value->flying_log_id);
                        if(!empty($m))
                        {
                            $log_date.='Start : '.date('d-m-Y H:i',strtotime($m->departure_time)).' To : '.date('d-m-Y H:i',strtotime($m->arrival_time));
                            $log_date.='<br>From : '.$m->fron_sector.' To : '.$m->to_sector.'<br>'.getAirCraft($m->aircraft_id)->call_sign;
                        }
                    }
                }
                $log_date.='<br><br>';
            }
            
            $html .= '<tr><td>'.++$key.'</td>
                        <td>'.$log_date.'</td>
                        <td>'.date('d-m-Y', strtotime($value->dates)).'</td>
                        <td>'.$value->user->salutation.' '.$value->user->name.'</td>
                        <td class="text-left '.(!empty($value->violations)?'text-success':'text-danger').'">'.$value->messages.'<br><b>'.$value->violations.'</b><br>'.$value->remark.'</td>';
            $html .= '</tr>';
        }
        return response()->json([
            'success' => true,
            'data' => $html
        ]);

    }
    
    public function updateException(Request $request)
    {
        $model = PilotViolation::findOrFail($request->id);
        $user_id=$model->user_id;
        $is_exception=$model->is_exception;
        $violations=$model->violations;
        
        $check = PilotViolation::where('user_id', $user_id)->where('is_exception', $is_exception)
        ->where('violations', $violations)->whereBetween('dates', [date('Y-m-d', strtotime($model->dates)), date('Y-m-d', strtotime($model->dates.' - 28 day'))])->get();
        if($check->count() > 3)
        {
            return response()->json([
                'success' => false,
                'message' => 'Exception Already Applied For This User Maxmum 3 Exception Allowed'
            ]);
        }
         
        $model->remark = $request->remark;
        $model->is_exception = $request->is_exception;
        $model->violations = $request->violations;
        $model->save();
        return response()->json([
            'success' => true,
            'message' => 'Exception Applied Successfully'
        ]);

    }
    public function voilationUpdate(Request $request)
    {
       $model = PilotViolation::findOrFail($request->id);
       return response()->json([
            'success' => true,
            'message' => '',
            'data'=>$model
        ]);
    }
    
    public function voilationReUpdate(Request $request)
    {
        $model = PilotViolation::findOrFail($request->id);
        $model->comments = $request->comments;
        $model->save();
        return response()->json([
            'success' => true,
            'message' => 'Exception Applied Successfully'
        ]); 
    }
    
    public function printReport($id,$front_date,$to_date)
    {
        $user_id = $id;
        $from = date('Y-m-d', strtotime($front_date));
        $to = date('Y-m-d', strtotime($to_date));
        $data['data'] = FlyingLog::where(function ($q) use ($user_id) {
            $q->where('pilot1_id', $user_id)->orWhere('pilot2_id', $user_id);
        })->whereBetween('date', [$from, $to])->groupBy('date')->orderBy('date','asc')->get();
        $data['user_id'] =$user_id;
        $data['user']=User::find($user_id);
        $data['from'] = $from;
        $data['to'] = $to;
        return view('fdtl.print-report', $data)->render();
    }

    public function voilationsReport()
    {
        if(getUserType()=='user')
        {
            return view('theme-one.fdtl.voilations-report');
        }else{
            return view('fdtl.voilations-report');
        }
    }
    
    public function voilationsReportList(Request $request)
    {
        $column=['id','start_log_id','dates','user_id','messages','id'];
        $users=PilotViolation::where('id','>','0');
        $total_row=$users->count();
        if (!empty($_POST['search']['value'])) {
            $users->where('messages', 'LIKE', '%' . $_POST['search']['value'] . '%');
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
            $fdtl_start='';
            $fdtl_end='';
            $d=PilotFlyingLog::where('flying_log_id',$value->flying_log_id)->where('user_id',$value->user->id)->get();
            foreach($d as $ds)
            {
                if($ds->log_type=='internal')
                {
                    if($value->violation_type=='Flight_Duty_Period')
                    {
                        $m=FlyingLog::find($value->start_log_id);
                        if(!empty($m))
                        {
                            $fdtl_start.='<b>FDT START</b><br>';
                            $fdtl_start.='Start : '.date('d-m-Y H:i',strtotime($m->departure_time)).' <br>End : '.date('d-m-Y H:i',strtotime($m->arrival_time));
                            $fdtl_start.='<br>From : '.$m->fron_sector.' To : '.$m->to_sector.'<br>'.getAirCraft($m->aircraft_id)->call_sign;
                        }
                        
                        $m=FlyingLog::find($value->flying_log_id);
                        if(!empty($m))
                        {
                            $fdtl_end.='<b>FDT END</b><br>';
                            $fdtl_end.='Start : '.date('d-m-Y H:i',strtotime($m->departure_time)).' <br>End : '.date('d-m-Y H:i',strtotime($m->arrival_time));
                            $fdtl_end.='<br>From : '.$m->fron_sector.' To : '.$m->to_sector.'<br>'.getAirCraft($m->aircraft_id)->call_sign;
                        }
                    }else{
                        $m=FlyingLog::find($value->flying_log_id);
                        if(!empty($m))
                        {
                            $fdtl_start.='Start : '.date('d-m-Y H:i',strtotime($m->departure_time)).' <br>End : '.date('d-m-Y H:i',strtotime($m->arrival_time));
                            $fdtl_start.='<br>From : '.$m->fron_sector.' To : '.$m->to_sector.'<br>'.getAirCraft($m->aircraft_id)->call_sign;
                        }
                    }
                }else{
                    
                    if($value->violation_type=='Flight_Duty_Period')
                    {
                        $m=ExternalFlyingLog::find($value->start_log_id);
                        if(!empty($m))
                        {
                            $fdtl_start.='<b>FDT START</b><br>';
                            $fdtl_start.='Start : '.date('d-m-Y H:i',strtotime($m->departure_time)).' <br>To : '.date('d-m-Y H:i',strtotime($m->arrival_time));
                            $fdtl_start.='<br>From : '.$m->fron_sector.' To : '.$m->to_sector.'<br>'.getAirCraft($m->aircraft_id)->call_sign;
                        }
                        
                        $m=ExternalFlyingLog::find($value->flying_log_id);
                        if(!empty($m))
                        {
                            $fdtl_end.='<b>FDT END</b><br>';
                            $fdtl_end.='Start : '.date('d-m-Y H:i',strtotime($m->departure_time)).' <br>To : '.date('d-m-Y H:i',strtotime($m->arrival_time));
                            $fdtl_end.='<br>From : '.$m->fron_sector.' To : '.$m->to_sector.'<br>'.getAirCraft($m->aircraft_id)->call_sign;
                        }
                        
                    }else{
                        $m=ExternalFlyingLog::find($value->flying_log_id);
                        if(!empty($m))
                        {
                            $fdtl_start.='Start : '.date('d-m-Y H:i',strtotime($m->departure_time)).' <br>To : '.date('d-m-Y H:i',strtotime($m->arrival_time));
                            $fdtl_start.='<br>From : '.$m->fron_sector.' To : '.$m->to_sector.'<br>'.getAirCraft($m->aircraft_id)->call_sign;
                        }
                    }
                }
                $fdtl_end.='<br>';
                $fdtl_start.='<br>';
            }
            
            if ($value->is_exception === 'no') {
                $action .= '<a onclick="updateException('.$value->id.', \'yes\')" href="javascript:void(0);" class="btn btn-sm btn-success">Apply Exception</a>';
            } else {
                $action .= '<a href="javascript:void(0);" onclick="reUpdateException('.$value->id.');" class="btn btn-sm btn-primary">Exception</a>';
            }
            
            $sub_array = array();
			$sub_array[] = ++$key;
            $sub_array[] = $fdtl_start;
            $sub_array[] = $fdtl_end;
            $sub_array[] = date('d-m-Y', strtotime($value->dates));
            $sub_array[] =  $value->user->salutation.' '.$value->user->name;
            $sub_array[] =  '<span class="'.(!empty($value->violations)?'text-success':'text-danger').'">'.$value->messages.'<br><b>'.$value->violations.'</b><br>'.$value->remark.'</span>';
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
    
}
