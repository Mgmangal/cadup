<?php

namespace App\Http\Controllers\ThemeOne;


use App\Models\User;

use App\Models\FlyingLog;
use App\Models\PilotFlyingLog;
use App\Models\PilotViolation;
use App\Models\ExternalFlyingLog;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request; 

class FDTLController extends Controller
{
    public function index(Request $request)
    {
        $sub_title = "FDTL REPORT";
        $users = User::with('designation')->where('is_delete','0')->where('status','active')->get();
        return view('theme-one.fdtl.index',compact('users','sub_title'));
    }
    public function myFdtlReport(Request $request)
    {
        $sub_title = "MY FDTL REPORT";
        $user = User::with('designation')->where('id', Auth()->user()->id)->where('is_delete', '0')->where('status', 'active')->first();
        $users = collect([$user]);
        return view('theme-one.fdtl.index', compact('users', 'sub_title'));
    }
    public function getReport(Request $request)
    {
        $user_id = $request['user_id'];
        $from = date('Y-m-d', strtotime($request->from_date));
        $to = date('Y-m-d', strtotime($request->to_date));
        $data['data'] = FlyingLog::where(function ($q) use ($user_id) {
            $q->where('pilot1_id', $user_id)->orWhere('pilot2_id', $user_id);
        })->whereBetween('date', [$from, $to])->groupBy('date')->orderBy('date','asc')->get();
        $data['user_id'] =$user_id;
        return view('theme-one.fdtl.get-report', $data)->render();
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
        return view('theme-one.fdtl.print-report', $data)->render();
    }
    public function voilations()
    {
        $title = "Violations";
        $pilots = User::with('designation')->where('is_delete','0')->where('status','active')->get();
        return view('theme-one.fdtl.voilations',compact('title','pilots'));
    }
    public function MyVoilations()
    {
        $title = "My Violations";
        $user = User::with('designation')->where('id', Auth()->user()->id)->where('is_delete', '0')->where('status', 'active')->first();
        $pilots = collect([$user]);
        return view('theme-one.fdtl.voilations',compact('title','pilots'));
    }
    public function voilationsList(Request $request)
    {
        $column=['id','start_log_id','dates','user_id','messages','id'];
        $users=PilotViolation::where('id','>','0');
        $total_row=$users->count();

        if (!empty($_POST['pilot'])) {
            $users->where('user_id', $_POST['pilot']);
        }

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
                $action .= '';
                // $action .= '<a onclick="updateException('.$value->id.', \'yes\')" href="javascript:void(0);" class="btn btn-sm btn-success">Apply Exception</a>';
            } else {
                $action .= '<a href="javascript:void(0);" class="btn btn-sm btn-primary">Exception</a>';
                // $action .= '<a href="javascript:void(0);" onclick="reUpdateException('.$value->id.');" class="btn btn-sm btn-primary">Exception</a>';
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
    
    public function voilationsReport()
    {
        $title = "Violations Report";
        $pilots = User::with('designation')->where('is_delete','0')->where('status','active')->get();
        return view('theme-one.fdtl.voilations-report',compact('title','pilots'));
    }
    
    public function MyVoilationsReport()
    {
        $title = "My Violations Report";
        $user = User::with('designation')->where('id', Auth()->user()->id)->where('is_delete', '0')->where('status', 'active')->first();
        $pilots = collect([$user]);
        return view('theme-one.fdtl.voilations-report',compact('title','pilots'));
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
