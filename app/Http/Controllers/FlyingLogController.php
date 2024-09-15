<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AirCraft;
use App\Models\FlyingLog;
use App\Models\AaiReport;
use App\Models\PilotFlyingLog;
use App\Models\ExternalFlyingLog;
use App\Models\User;
use App\Models\Master;
use App\Models\PilotViolation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\FlightDocAssign;
use App\Models\PilotLog;
use Carbon\Carbon; 
class FlyingLogController extends Controller
{
    public function index()
    {
        $pilots = User::where('designation', '1')->where('status', 'active')->get();
        $aircrafts = AirCraft::where('status', 'active')->get();
        $flying_types = Master::where('type', 'flying_type')->where('status', 'active')->get();
        $pilot_roles = Master::where('type', 'pilot_role')->where('status', 'active')->get();
        $passengers = Master::where('type', 'passenger')->where('status', 'active')->get();
        $pilot_role = Master::where('type', 'pilot_role')->where('status', 'active')->get();
        return view('flying_logs.index', compact('pilots', 'aircrafts','flying_types','pilot_roles','passengers'));

    }

    public function create()
    {
        $pilots = User::where('designation', '1')->where('status', 'active')->get();
        // $aircrofts = AirCraft::where('status', 'active')->get();
        $aircrofts = AirCraft::where('status', 'active')->get()->sortBy('type_model')->values();
        $flying_types = Master::where('type', 'flying_type')->where('status', 'active')->get();
        $pilot_roles = Master::where('type', 'pilot_role')->where('status', 'active')->get();
        $passengers = Master::where('type', 'passenger')->where('status', 'active')->get();
        return view('flying_logs.create', compact('pilots', 'aircrofts','flying_types','pilot_roles','passengers'));
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'date' => 'required',
            'aircraft_id' => 'required',
        ]);
        if ($validation->fails()) {
            return response()->json(['success' => false, 'message' => $validation->errors()]);
        }

        $date = $request->date;
        $aircraft_id = $request->aircraft_id;
        $pilot1_id = $request->pilot1_id;
        $pilot1_role = $request->pilot1_role;
        $pilot2_id = $request->pilot2_id;
        $pilot2_role = $request->pilot2_role;
        $fron_sector = $request->fron_sector;
        $to_sector = $request->to_sector;
        $flying_type = $request->flying_type;
        $departure_time =$request->departure_time;
        $arrival_time =$request->arrival_time;
        $night_time = $request->night_time;
        $comment = $request->comment;
        $passenger = $request->passenger;

        foreach ($fron_sector as $key => $fron_sector) {
            $da=FlyingLog::create([
                'date' => is_set_date_format($departure_time[$key]),
                'aircraft_id' => $aircraft_id,
                'pilot1_id' => $pilot1_id[$key],
                'pilot1_role' => $pilot1_role[$key],
                'pilot2_id' => $pilot2_id[$key],
                'pilot2_role' => $pilot2_role[$key],
                'fron_sector' => $fron_sector,
                'to_sector' => $to_sector[$key],
                'flying_type' => $flying_type[$key],
                'comment' => $comment[$key],
                'passenger' => 0,
                'departure_time' => is_set_date_time_format($departure_time[$key]),
                'arrival_time' => is_set_date_time_format($arrival_time[$key]),
                'night_time' => !empty($night_time[$key])?$night_time[$key]:null,
            ]);
        }
        return response()->json(['success' => true, 'message' => 'Data Added successfully.']);
    }

    public function edit($id)
    {
        $pilots = User::where('designation', '1')->where('status', 'active')->get();
        // $aircrofts = AirCraft::where('status', 'active')->get();
        $aircrofts = AirCraft::where('status', 'active')->get()->sortBy('type_model')->values();
        $data = FlyingLog::find($id);
        $flying_types = Master::where('type', 'flying_type')->where('status', 'active')->get();
        $pilot_roles = Master::where('type', 'pilot_role')->where('status', 'active')->get();
        $passengers = Master::where('type', 'passenger')->where('status', 'active')->get();
        return view('flying_logs.edit', compact('data', 'pilots', 'aircrofts','flying_types','pilot_roles','passengers'));
    }

    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'date' => 'required',
            'aircraft_id' => 'required',
            'pilot1_id' => 'required',
            'pilot1_role' => 'required',
            'pilot2_id' => 'required',
            'pilot2_role' => 'required',
            'fron_sector' => 'required',
            'to_sector' => 'required',
            'flying_type' => 'required',
            'departure_time' => 'required',
            'arrival_time' => 'required'
        ]);
        if ($validation->fails()) {
            return response()->json(['success' => false, 'message' => $validation->errors()]);
        }
        $night_time = $request->night_time;
        $data = FlyingLog::find($id);
        $data->update([
            'date' => is_set_date_format($request->date),
            'aircraft_id' => $request->aircraft_id,
            'pilot1_id' => $request->pilot1_id,
            'pilot1_role' => $request->pilot1_role,
            'pilot2_id' => $request->pilot2_id,
            'pilot2_role' => $request->pilot2_role,
            'fron_sector' => $request->fron_sector,
            'to_sector' => $request->to_sector,
            'flying_type' => $request->flying_type,
            'comment' => $request->comment,
            'departure_time' => is_set_date_time_format($request->departure_time),
            'arrival_time' => is_set_date_time_format($request->arrival_time),
            'night_time' => !empty($night_time)?$night_time:null,
        ]);

        return response()->json(['success' => true, 'message' => 'Data Updated successfully.']);
    }

    public function destroy($id)
    {
        $data = FlyingLog::find($id);
        $data->delete();
        return response()->json(['success' => true, 'message' => 'Data Deleted successfully.']);
    }

    public function getMasterName($id,$type)
    {
        $data=Master::where('id',$id)->where('type',$type)->first();
        return !empty($data)?$data->name:'';
    }

    public function list(Request $request)
    {
        $column = ['id', 'departure_time', 'aircraft_id', 'fron_sector','departure_time', 'departure_time', 'pilot1_id', 'flying_type','passenger', 'id'];
        $users = FlyingLog::with(['pilot1', 'pilot2', 'aircraft'])->where('id', '>', '0');

        if(!empty($_POST['pilot']))
        {
            $pilot=$_POST['pilot'];
            $users->where(function($q) use($pilot){
                $q->where('pilot1_id', $pilot);
                $q->orWhere('pilot2_id', $pilot);
            });
        }
        $total_row = $users->get()->count();

        if(!empty($_POST['from_date'])&&empty($_POST['to_date']))
        {
            $from=$_POST['from_date'];
            $users->where('date','>=',date('Y-m-d',strtotime($from)));
        }
        if(empty($_POST['from_date'])&&!empty($_POST['to_date']))
        {
            $to=$_POST['to_date'];
            $users->where('date','<=',date('Y-m-d',strtotime($to)));
        }
        if(!empty($_POST['from_date'])&&!empty($_POST['to_date']))
        {
            $from=$_POST['from_date'];
            $to=$_POST['to_date'];
            $users->where(function($q) use($from, $to){
                $q->whereBetween('date', [date('Y-m-d',strtotime($from)), date('Y-m-d',strtotime($to))]);
            });
        }

        if(!empty($_POST['from_sector']))
        {
          $users->where('fron_sector',$_POST['from_sector']);
        }
        if(!empty($_POST['to_sector']))
        {
          $users->where('to_sector',$_POST['to_sector']);
        }
        if(!empty($_POST['aircraft']))
        {
          $users->where('aircraft_id',$_POST['aircraft']);
        }
        if(!empty($_POST['flying_type']))
        {
          $users->where('flying_type', $_POST['flying_type']);
        }
        if(!empty($_POST['passenger']))
        {
          $users->whereJsonContains('passenger',$_POST['passenger']);
        }

        if (isset($_POST['search'])&&!empty($_POST['search']['value'])) {
            $users->where('comment', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $users->orWhere('fron_sector', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $users->orWhere('to_sector', 'LIKE', '%' . $_POST['search']['value'] . '%');
        }
        if (isset($_POST['order'])) {
            $users->orderBy($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $users->orderBy('id', 'desc');
        }
        $filter_row = $users->get()->count();
        if (isset($_POST["length"]) && $_POST["length"] != -1) {
            $users->skip($_POST["start"])->take($_POST["length"]);
        }
        $result = $users->get();

        $data = array();
        $times = array();
        foreach ($result as $key => $value) {
            $action  ='';
            if($value->is_process=='no')
            {
                if(getUserType()=='user')
                {
                    $action  .='<a href="'.route('user.flying.verify',encrypter('encrypt', $value->id)).'" class="btn btn-warning btn-sm m-1">Verify</a>';
                }else{
                    $action  .= '<a href="'.route('app.flying-details.edit', $value->id).'" class="btn btn-warning btn-sm m-1">Edit</a>';
                    $action .= '<a href="javascript:void(0);" onclick="deleted(`' . route('app.flying-details.destroy', $value->id).'`);" class="btn btn-danger btn-sm m-1">Delete</a>';
                }
            }else{
                $action  .='<a href="javascript:void(0);" class="btn btn-success btn-sm m-1">Verified</a>';
            }

            $times[] = is_time_defrence($value->departure_time, $value->arrival_time);
            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = is_get_date_format($value->date);
            $sub_array[] = @$value->aircraft->call_sign;
            $sub_array[] = $value->fron_sector.' /<br>'.$value->to_sector;
            $sub_array[] = is_set_time_format($value->departure_time).' / '.is_set_time_format($value->arrival_time);//date('H:i',strtotime($value->departure_time)).' / '. date('H:i',strtotime($value->arrival_time));
            $sub_array[] = is_time_defrence($value->departure_time, $value->arrival_time);
            $sub_array[] = @$value->pilot1->salutation . ' ' . @$value->pilot1->name.'-'.getMasterName($value->pilot1_role,'pilot_role').' /<br>'.@$value->pilot2->salutation . ' ' . @$value->pilot2->name.'-'.getMasterName($value->pilot2_role,'pilot_role');
            $sub_array[] = getMasterName($value->flying_type,'flying_type');
            // $sub_array[] = !empty($value->passenger)?implode(', ',$value->passenger):'';
            $sub_array[] =  $action;
            $data[] = $sub_array;
        }
        $totalTime = AddPlayTime($times);
        $output = array(
            "draw"       =>  intval($_POST["draw"]),
            "recordsTotal"   =>  $total_row,
            "recordsFiltered"  =>  $filter_row,
            "data"       =>  $data,
            "totalTime"       =>  $totalTime,
        );

        echo json_encode($output);
    }

    function lastLocation(Request $request)
    {
        $aircroft_id=$request->aircroft_id;
        $rw=FlyingLog::where('aircraft_id',$aircroft_id)->orderBy('id','desc')->first();
        $data='';
        $last_arrival_time='';
        if(!empty($rw))
        {
            $data= $rw->to_sector;
            $last_arrival_time= date('d-m-Y H:i',strtotime($rw->arrival_time));
        }
        $aircaft=AirCraft::find($aircroft_id);
        $html='<option value="">Select</option>';
        if(!empty($aircaft)&&!empty($aircaft->pilots))
        {
            $pilots=$aircaft->pilots;
            foreach($pilots as $pilot)
            {
                $user=User::findOrFail($pilot);
                if($user->status=='active')
                {
                    $html.='<option value="'.$user->id.'">'.$user->name.'</option>';
                }
            }
        }
        return response()->json(['data' => $data,'pilots'=>$html,'last_arrival_time'=>$last_arrival_time]);
    }

    public function statistics ()
    {
        $pilots = User::where('designation', '1')->where('status', 'active')->get();
        $aircrafts = AirCraft::where('status', 'active')->get();
        $flying_types =Master::where('type', 'flying_type')->where('status', 'active')->get();// array( '1' => 'Agriculture minister', '2' => 'Cabinet Minister', '3' => 'CM', '4' => 'CS', '5' => 'DGP', '6' => 'Dy. CM', '7' => 'Governor', '8' => 'Positioning', '9' => 'PPC', '10' => 'RTB', '11' => 'Speaker UP', '12' => 'VIP', '13' => 'VVIP', '14' => 'Home Secretary', '15' => 'Personal Secretary Home', '16' => 'AG', '17' => 'Maintenance', '18' => 'ADG', '19' => 'Standard Check', '20' => 'Civil aviation minister', '21' => 'Special Duty', '22' => 'Other', '23' => 'Water Resources Minister', '24' => 'State Minister', '25' => 'NA', '26' => 'Irrigation Minister', '27' => 'PWD', '28' => 'Local Flying', '29' => 'State election commissioner', '30' => 'Chief election commissioner', '31' => 'DM', '32' => 'APC', '33' => 'Director Aviation', '34' => 'Route Check', '35' => 'Check Flight', '36' => 'Flower Dropping', '37' => 'Central Minister', '38' => 'Forest Minister', '39' => 'Principal Secretary irrigation', '40' => 'Secretary', '41' => 'Assembly Speaker', '42' => 'Health Minister', '43' => 'Power minister', '44' => 'Nager Vikas Minister', '45' => 'Election Commissioner', '46' => 'Urban Minister', '47' => 'Ground Run', '48' => 'Instant Release Check', '49' => 'Sports minister' );
        return view('flying_logs.statistics', compact('pilots', 'aircrafts','flying_types'));
    }

    public function statisticsPrint($from_date='',$to_date='',$aircraft='',$flying_type='')
    {
        if(empty($from_date)||empty($to_date))
        {
            return 'Please select fromt or to date';
        }
        if(!empty($aircraft))
        {
            $aircrafts = AirCraft::where('status', 'active')->where('id',$aircraft)->get();
        }else{
           $aircrafts = AirCraft::where('status', 'active')->orderByDesc('aircraft_cateogry')
           ->orderBy('call_sign')->get();
        }

        $from = $from_date;
        $to = $to_date;
        $data['from'] = $from;
        $data['to'] = $to;
        $data['flyingType'] = $flying_type;
        $data['aircrafts'] = $aircrafts;
        return view('flying_logs.print-statistics', $data)->render();
    }

    public function processFlyingLog()
    {
        $pilots = User::where('designation', '1')->where('status', 'active')->get();
        $aircrafts = AirCraft::where('status', 'active')->get();
        $flying_types = Master::where('type', 'flying_type')->where('status', 'active')->get();
        $pilot_roles = Master::where('type', 'pilot_role')->where('status', 'active')->get();
        return view('flying_logs.process-flying-log', compact('pilots', 'aircrafts','flying_types','pilot_roles'));
    }

    public function processSave(Request $request)
    {
        $types=$request->types;
        $dates=$request->dates;
        if($types=='process')
        {
            $pilots = User::where('designation', '1')->get();
            foreach($pilots as $pilot)
            {
                $pilotId=$pilot->id;
                $results = DB::table('flying_logs')->select(
                                'id',
                                'pilot1_id',
                                'pilot1_role',
                                'pilot2_id',
                                'pilot2_role',
                                'aircraft_id',
                                'date',
                                'fron_sector',
                                'to_sector',
                                'departure_time',
                                'arrival_time',
                                'night_time',
                                'flying_type',
                                'is_process',
                                DB::raw("'internal' as demo_column")
                            );

                $results=$results->where(function($query) use ($pilotId) {
                    $query->where('pilot1_id', $pilotId)
                          ->orWhere('pilot2_id', $pilotId);
                })->where('date','<=',date('Y-m-d',strtotime($dates)))->where('is_process','no')->orderBy('departure_time','DESC');

                $externalResults = DB::table('external_flying_logs')->select(
                                'id',
                                'pilot1_id',
                                'pilot1_role',
                                DB::raw("'00' as pilot2_id"),
                                DB::raw("'00' as pilot2_role"),
                                DB::raw("'00' as aircraft_id"),
                                'date',
                                'fron_sector',
                                'to_sector',
                                'departure_time',
                                'arrival_time',
                                'night_time',
                                'flying_type',
                                'is_process',
                                DB::raw("'external' as demo_column")
                            );
                $externalResults = $externalResults->where(function($query) use ($pilotId) {
                    $query->where('pilot1_id', $pilotId)
                          ->orWhere('pilot2_id', $pilotId);
                })->where('date','<=',date('Y-m-d',strtotime($dates)))->where('is_process','no')->orderBy('departure_time','DESC');
                $data= $results->union($externalResults)->get();
                if(!empty($data))
                {
                    foreach($data as $value)
                    {
                        if($value->demo_column=='external')
                        {
                            $do=ExternalFlyingLog::find($value->id);
                            $this->savePilotLog($value->id,$value->aircraft_id,is_set_date_format($value->departure_time),$value->pilot1_id,$value->pilot1_role,$value->flying_type,$value->fron_sector,$value->to_sector,$value->departure_time,$value->arrival_time,(!empty($value->night_time)?$value->night_time:null),$value->demo_column);
                        }else{
                            $do=FlyingLog::find($value->id);
                            $this->savePilotLog($value->id,$value->aircraft_id,is_set_date_format($value->departure_time),$value->pilot1_id,$value->pilot1_role,$value->flying_type,$value->fron_sector,$value->to_sector,$value->departure_time,$value->arrival_time,(!empty($value->night_time)?$value->night_time:null),$value->demo_column);
                            $this->savePilotLog($value->id,$value->aircraft_id,is_set_date_format($value->departure_time),$value->pilot2_id,$value->pilot2_role,$value->flying_type,$value->fron_sector,$value->to_sector,$value->departure_time,$value->arrival_time,(!empty($value->night_time)?$value->night_time:null),$value->demo_column);
                        }
                        $do->is_process='yes';
                        $do->save();
                    }
                }
            }
           return $this->manageProcess();
        }
        if($types=='unprocess')
        {
            $results = DB::table('flying_logs')->select(
                            'id',
                            'pilot1_id',
                            'pilot1_role',
                            'pilot2_id',
                            'pilot2_role',
                            'aircraft_id',
                            'date',
                            'fron_sector',
                            'to_sector',
                            'departure_time',
                            'arrival_time',
                            'night_time',
                            'flying_type',
                            'is_process',
                            DB::raw("'internal' as demo_column")
                        );

            $results=$results->where('date','>=',date('Y-m-d',strtotime($dates)))->where('is_process','yes')->orderBy('departure_time','desc');

            $externalResults = DB::table('external_flying_logs')->select(
                            'id',
                            'pilot1_id',
                            'pilot1_role',
                            DB::raw("'00' as pilot2_id"),
                            DB::raw("'00' as pilot2_role"),
                            DB::raw("'00' as aircraft_id"),
                            'date',
                            'fron_sector',
                            'to_sector',
                            'departure_time',
                            'arrival_time',
                            'night_time',
                            'flying_type',
                            'is_process',
                            DB::raw("'external' as demo_column")
                        );
            $externalResults = $externalResults->where('date','>=',date('Y-m-d',strtotime($dates)))->where('is_process','yes')->orderBy('departure_time','desc');
            $data= $results->union($externalResults)->get();

            if(!empty($data))
            {
                foreach($data as $value)
                {
                    if($value->demo_column=='external')
                    {
                        $do=ExternalFlyingLog::find($value->id);
                    }else{
                        $do=FlyingLog::find($value->id);
                    }
                    DB::table('pilot_logs')->where('flying_log_id', $value->id)->where('log_type',$value->demo_column)->delete();
                    PilotFlyingLog::where('flying_log_id', $value->id)->where('log_type',$value->demo_column)->delete();
                    $do->is_process='no';
                    $do->save();
                }
            }
        }
        return response()->json([
                'success'=>true,
                'message'=>'Successfully'
            ]);
    }

    public function savePilotLog($flying_log_id,$aircraft_id,$date,$pilot_id,$pilot_role,$flying_type,$from_sector,$to_sector,$departure_time,$arrival_time,$night_time,$log_type)
    {
       $data = [
            'flying_log_id' => $flying_log_id,
            'user_id' => $pilot_id,
            'user_role' => $pilot_role,
            'aircraft_id' => $aircraft_id,
            'date' => $date,
            'fron_sector' => $from_sector,
            'to_sector' => $to_sector,
            'departure_time' => $departure_time,
            'arrival_time' => $arrival_time,
            'night_time' => $night_time,
            'flying_type' => $flying_type,
            'log_type' => $log_type,
            'is_process' => 'no',
        ];
        $inserted = DB::table('pilot_logs')->insert($data);
        return $inserted;
    }

    public function manageProcess()
    {
        //DB::table('pilot_flying_logs')->truncate();
        $pilots = User::where('designation', '1')->get();
        foreach($pilots as $pilot)
        {
            $results = DB::table('pilot_logs')->select(
                            'id',
                            'flying_log_id',
                            'user_id',
                            'user_role',
                            'aircraft_id',
                            'date',
                            'fron_sector',
                            'to_sector',
                            'departure_time',
                            'arrival_time',
                            'night_time',
                            'flying_type',
                            'is_process',
                            'log_type'
                        )->where('user_id',$pilot->id)->where('is_process','no')->orderBy('arrival_time', 'asc')->get();
            foreach($results as $value)
            {
                $this->pilot_log($value->flying_log_id,$value->aircraft_id,is_set_date_format($value->departure_time),$value->user_id,$value->user_role,$value->flying_type,$value->fron_sector,$value->to_sector,$value->departure_time,$value->arrival_time,(!empty($value->night_time)?$value->night_time:null),$value->log_type);
                DB::table('pilot_logs')->where('id', $value->id)->update(['is_process' => 'yes']);
            }
        }
        return response()->json([
                'success'=>true,
                'message'=>'Successfully'
            ]);

    }

    public function pilot_log($flying_log_id,$aircraft_id,$date,$pilot_id,$pilot_role,$flying_type,$from_sector,$to_sector,$departure_time,$arrival_time,$night_time,$log_type)
    {
        $flying_log_id	                = $flying_log_id;
        $aircroft_id                    = $aircraft_id;
        $user_id	                    = $pilot_id;

        $local_night=0;
        $comments='';
        $last_row= getPilotFlyingLog($user_id,$departure_time);
        if(!empty($last_row))
        {
            //print_r($last_row);die;
            $last_arrival=$last_row->arrival_time;
            $d=is_time_defrence_in_mintes($last_arrival,$departure_time);
            $local_night=calculateNumberOfNights($last_arrival,$departure_time);
            //print_r( $d);die;
        }else{
            $d=890;
        }

        $week_start=0;
        $break_start_time=null;
        //echo $d.'==='.$departure_time;
        if($d >= 840)//14 hours brabr ya jyada hai to
        {

            $flight_duty_period_start_time	= date('Y-m-d H:i:s',strtotime('-45 minutes',strtotime($departure_time)));
            $flight_duty_period_end_time    = date('Y-m-d H:i:s',strtotime('+15 minutes',strtotime($arrival_time)));
            $travel_time_start_before_flying= date('Y-m-d H:i:s',strtotime('-30 minutes',strtotime($flight_duty_period_start_time)));

            $travel_time_end_after_flying   =date('Y-m-d H:i:s',strtotime('+30 minutes',strtotime($arrival_time)));
            $reporting_time	                = $flight_duty_period_start_time;
            $post_flight_document_end_time  = $flight_duty_period_end_time;
            $chocks_off	                    = date('Y-m-d H:i:s',strtotime($departure_time));
            $chocks_on                      = date('Y-m-d H:i:s',strtotime($arrival_time));
            $departure_time                 = date('Y-m-d H:i:s',strtotime($departure_time));
            $arrival_time                   = date('Y-m-d H:i:s',strtotime($arrival_time));
            $night_flying_start_time=null;
            $night_flying_end_time=null;

            $rest_hours_start_time          = date('Y-m-d H:i:s',strtotime('+45 minutes',strtotime($arrival_time)));
            $rest_hours_end_time	        = date('Y-m-d H:i:s',strtotime('-75 minutes',strtotime($departure_time)));

            $break_start_time=null;
            $break_hours=null;
            $flying_time=is_time_defrence($arrival_time, $departure_time);;
            $last_home_station_date=($from_sector==''?'':null);
            $start_24_hours=1;

            if($d>=2280)
            {
                $local_night=calculateNumberOfNights($last_arrival,$departure_time);
                if($local_night['nights']>=2)
                {

                    $last_row->week_end=1;
                    $last_row->save();
                    $week_start=1;
                }
                $comments = $d.'='.$last_arrival.'-'.$departure_time.'='.$local_night['days'].'='.$local_night['nights'];
            }
        }else{

            $flight_duty_period_start_time	= null;

            $last_row->flight_duty_period_end_time=null;

            $flight_duty_period_end_time    = date('Y-m-d H:i:s',strtotime('+15 minutes',strtotime($arrival_time)));

            $travel_time_start_before_flying= null;

            $last_row->travel_time_end_after_flying=null;
            $travel_time_end_after_flying=date('Y-m-d H:i:s',strtotime('+30 minutes',strtotime($arrival_time)));

            $reporting_time	                = null;

            $last_row->post_flight_document_end_time=null;
            $post_flight_document_end_time  = $flight_duty_period_end_time;

            $chocks_off	                    = null;

            $last_row->chocks_on=null;
            $chocks_on                      = date('Y-m-d H:i:s',strtotime($arrival_time));

            $departure_time                 = date('Y-m-d H:i:s',strtotime($departure_time)) ;
            $arrival_time                   = date('Y-m-d H:i:s',strtotime($arrival_time));

            $night_flying_start_time=null;
            $night_flying_end_time=null;

            $last_row->rest_hours_start_time=null;
            $rest_hours_start_time          = date('Y-m-d H:i:s',strtotime('+45 minutes',strtotime($arrival_time)));

            $rest_hours_end_time	        = null;

            $last_row->break_start_time=$last_row->arrival_time;
            $last_row->break_hours=is_time_defrence($departure_time, $last_row->arrival_time);
            $break_start_time=null;
            $break_hours=null;
            $flying_time=is_time_defrence($arrival_time, $departure_time);;
            $last_home_station_date=($from_sector==''?'':null);
            $start_24_hours=0;
            $week_start=0;
            $last_row->save();
        }
        $data = new PilotFlyingLog;
        $data->user_id=$user_id;
        $data->aircroft_id=$aircroft_id;
        $data->date=$date;
        $data->flying_log_id=$flying_log_id;
        $data->flight_duty_period_start_time=$flight_duty_period_start_time;
        $data->flight_duty_period_end_time=$flight_duty_period_end_time;
        $data->travel_time_start_before_flying=$travel_time_start_before_flying;
        $data->travel_time_end_after_flying=$travel_time_end_after_flying;
        $data->reporting_time=$reporting_time;
        $data->post_flight_document_end_time=$post_flight_document_end_time;
        $data->chocks_off=$chocks_off;
        $data->chocks_on=$chocks_on;
        $data->departure_time=$departure_time;
        $data->arrival_time=$arrival_time;
        $data->night_flying_start_time=$night_flying_start_time;
        $data->night_flying_end_time=$night_flying_end_time;
        $data->rest_hours_start_time=$rest_hours_start_time;
        $data->rest_hours_end_time=$rest_hours_end_time;
        $data->break_start_time=$break_start_time;
        $data->break_hours=$break_hours;
        $data->flying_time=$flying_time;
        $data->last_home_station_date=$last_home_station_date;
        $data->start_24_hours=$start_24_hours;
        $data->week_start=$week_start;
        $data->log_type=$log_type;
        $data->comments=$comments;
        return $data->save();
    }

    public function analyzeViolation(Request $request)
    {
        $fixedWingPilots=getCategoriesAllPilots('Fixed Wing');
        $rotorWingPilots=getCategoriesAllPilots('Rotor Wing');
        DB::table('pilot_violations')->truncate();
        //$pilots = User::where('designation', '1')->where('status', 'active')->get();
        foreach($fixedWingPilots as $pilot)
        {
            $flying_hours=[];
            $none_flying_duty_in_mint=0;
            $landing=0;
            $start_log_id=null;
            $data=PilotFlyingLog::where('user_id',$pilot->id)->orderBy('id', 'asc')->get();
            foreach($data as $value)
            {
                if(!empty($value->flight_duty_period_start_time))
                {
                    $flight_duty_period_start_time=$value->flight_duty_period_start_time;
                    $start_log_id=$value->flying_log_id;
                    $flying_hours=[];
                    $total_duty_hours_in_mint=0;
                }
                $landing++;
                $departure_time=$value->departure_time;
                $arrival_time=$value->arrival_time;
                $flying_hours[]=is_time_defrence($arrival_time,$departure_time);

                if(!empty($value->break_hours))
                {
                    $time=$value->break_hours;
                    $totalMinutes = Carbon::parse($time)->hour * 60 + Carbon::parse($time)->minute;
                    if($totalMinutes>180)
                    {
                        // $d=$totalMinutes-180;
                        $d=$totalMinutes;
                        $none_flying_duty_in_mint +=$d>0?($d/2):0;
                    }
                }

                $total_landing=$landing;
                $total_flying_in_mint= minutes(AddPlayTime($flying_hours));


                if($total_flying_in_mint>600)  // > 10
                {
                    $messages='As per DGCA CAR section 7- SERIES-J Part 4, Issue-1,Rev-1 Dated 19 January,2023 Para 6.1, Sub Para 6.1.2 Maximum Flight time of '.getEmpFullName($value->user_id).' is '.colculate_days_hours_mints($total_flying_in_mint);

                    // $messages='Pilot has violated  DGCA CAR guideline having flying time above 10 hours: violated rule DGCA section 7J Para 6.1';
                    $violation_type='Flight_Time';
                    $flying_log_id=$value->flying_log_id;
                    $aircfat_id=$value->aircroft_id;
                    $user_id=$value->user_id;
                    $dates=$value->date;
                    $this->insertPilotViolation($user_id,$aircfat_id,$start_log_id,$flying_log_id,$violation_type,$messages,$dates);
                }else{
                    // <= 10 && 8 >
                    if($total_flying_in_mint<=600&&$total_flying_in_mint > 480)
                    {
                        //  > 12:30 &&  < 13:30
                        if($total_duty_hours_in_mint > 750 &&$total_duty_hours_in_mint < 810 )
                        {
                            if($total_landing>2)
                            {
                                $messages='As per DGCA CAR section 7- SERIES-J Part 4, Issue-1,Rev-1 Dated 19 January,2023 Para 6.1, Sub Para 6.1.1 Maximum Landings of '.getEmpFullName($value->user_id).' is '.$total_landing;
                                //$messages='Pilot has violated  DGCA CAR guideline not more then 6 landing allowed in 8 hours flying : violated rule DGCA section 7J Para 6.1 sub para 6.1.1 maximum  no. of landing';
                                $violation_type='Landings';
                                $flying_log_id=$value->flying_log_id;
                                $aircfat_id=$value->aircroft_id;
                                $user_id=$value->user_id;
                                $dates=$value->date;
                                $this->insertPilotViolation($user_id,$aircfat_id,$start_log_id,$flying_log_id,$violation_type,$messages,$dates);
                            }
                        }
                    }
                    // >0 && < 8
                    if($total_flying_in_mint > 0 &&$total_flying_in_mint <= 480)
                    {
                        //  > 12 &&  < 12:30
                        if($total_duty_hours_in_mint > 720 &&$total_duty_hours_in_mint < 750 )
                        {
                            if($total_landing>3)
                            {
                                $messages='As per DGCA CAR section 7- SERIES-J Part 4, Issue-1,Rev-1 Dated 19 January,2023 Para 6.1, Sub Para 6.1.1 Maximum Landings of '.getEmpFullName($value->user_id).' is '.$total_landing;
                                //$messages='Pilot has violated  DGCA CAR guideline not more then 6 landing allowed in 8 hours flying : violated rule DGCA section 7J Para 6.1 sub para 6.1.1 maximum  no. of landing';
                                $violation_type='Landings';
                                $flying_log_id=$value->flying_log_id;
                                $aircfat_id=$value->aircroft_id;
                                $user_id=$value->user_id;
                                $dates=$value->date;
                                $this->insertPilotViolation($user_id,$aircfat_id,$start_log_id,$flying_log_id,$violation_type,$messages,$dates);
                            }
                        }else if($total_duty_hours_in_mint > 690 &&$total_duty_hours_in_mint <= 720 ){
                            if($total_landing>4)
                            {
                                $messages='As per DGCA CAR section 7- SERIES-J Part 4, Issue-1,Rev-1 Dated 19 January,2023 Para 6.1, Sub Para 6.1.1 Maximum Landings of '.getEmpFullName($value->user_id).' is '.$total_landing;
                                //$messages='Pilot has violated  DGCA CAR guideline not more then 6 landing allowed in 8 hours flying : violated rule DGCA section 7J Para 6.1 sub para 6.1.1 maximum  no. of landing';
                                $violation_type='Landings';
                                $flying_log_id=$value->flying_log_id;
                                $aircfat_id=$value->aircroft_id;
                                $user_id=$value->user_id;
                                $dates=$value->date;
                                $this->insertPilotViolation($user_id,$aircfat_id,$start_log_id,$flying_log_id,$violation_type,$messages,$dates);
                            }

                        }else if($total_duty_hours_in_mint > 660 &&$total_duty_hours_in_mint <= 690 ){
                            if($total_landing>5)
                            {
                                $messages='As per DGCA CAR section 7- SERIES-J Part 4, Issue-1,Rev-1 Dated 19 January,2023 Para 6.1, Sub Para 6.1.1 Maximum Landings of '.getEmpFullName($value->user_id).' is '.$total_landing;
                                //$messages='Pilot has violated  DGCA CAR guideline not more then 6 landing allowed in 8 hours flying : violated rule DGCA section 7J Para 6.1 sub para 6.1.1 maximum  no. of landing';
                                $violation_type='Landings';
                                $flying_log_id=$value->flying_log_id;
                                $aircfat_id=$value->aircroft_id;
                                $user_id=$value->user_id;
                                $dates=$value->date;
                                $this->insertPilotViolation($user_id,$aircfat_id,$start_log_id,$flying_log_id,$violation_type,$messages,$dates);
                            }
                        }else if($total_duty_hours_in_mint <= 660){
                            if($total_landing>6)
                            {
                                $messages='As per DGCA CAR section 7- SERIES-J Part 4, Issue-1,Rev-1 Dated 19 January,2023 Para 6.1, Sub Para 6.1.1 Maximum Landings of '.getEmpFullName($value->user_id).' is '.$total_landing;
                                //$messages='Pilot has violated  DGCA CAR guideline not more then 6 landing allowed in 8 hours flying : violated rule DGCA section 7J Para 6.1 sub para 6.1.1 maximum  no. of landing';
                                $violation_type='Landings';
                                $flying_log_id=$value->flying_log_id;
                                $aircfat_id=$value->aircroft_id;
                                $user_id=$value->user_id;
                                $dates=$value->date;
                                $this->insertPilotViolation($user_id,$aircfat_id,$start_log_id,$flying_log_id,$violation_type,$messages,$dates);
                            }
                        }else{
                            $messages='As per DGCA CAR section 7- SERIES-J Part 4, Issue-1,Rev-1 Dated 19 January,2023 Para 6.1, Sub Para 6.1.2 Maximum Flight Duty Period of '.getEmpFullName($value->user_id).' is '.colculate_days_hours_mints($total_duty_hours_in_mint);
                            // $messages='Pilot has violated  DGCA CAR guideline having flying duty period 13:30 hours: violated rule DGCA section 7J Para 6.1 maximum flying duty period sub para 6.1.2';
                            $violation_type='Flight_Duty_Period';
                            $flying_log_id=$value->flying_log_id;
                            $aircfat_id=$value->aircroft_id;
                            $user_id=$value->user_id;
                            $dates=$value->date;
                            $this->insertPilotViolation($user_id,$aircfat_id,$start_log_id,$flying_log_id,$violation_type,$messages,$dates);
                        }
                    }

                }

                if(!empty($value->flight_duty_period_end_time))
                {
                    $fling_duty_time_mint=is_time_defrence_in_mintes($flight_duty_period_start_time,$value->flight_duty_period_end_time);//minutes(is_time_defrence($value->flight_duty_period_end_time,$flight_duty_period_start_time));
                    $total_duty_hours_in_mint= $fling_duty_time_mint-$none_flying_duty_in_mint;
                    //echo $fling_duty_time_mint.'='.$landing.'=='.$value->date.'==='.$value->user_id.'=='.$value->flight_duty_period_end_time.'<=>'.$flight_duty_period_start_time.'<br>';
                    $flight_duty_period_start_time='';
                    $flying_hours=[];
                    $landing=0;
                    $none_flying_duty_in_mint=0;
                }

                if($total_duty_hours_in_mint > 810)
                {
                    $messages=' As per DGCA CAR section 7- SERIES-J Part 4, Issue-1,Rev-1 Dated 19 January,2023 Para 6.1, Sub Para 6.1.2 Maximum Flight Duty Period of '.getEmpFullName($value->user_id).' is '.colculate_days_hours_mints($total_duty_hours_in_mint);

                    // $messages='Pilot has violated  DGCA CAR guideline having flying duty period 13:30 hours: violated rule DGCA section 7J Para 6.1 maximum flying duty period sub para 6.1.2';
                    $violation_type='Flight_Duty_Period';
                    $flying_log_id=$value->flying_log_id;
                    $aircfat_id=$value->aircroft_id;
                    $user_id=$value->user_id;

                    $dates=$value->date;
                    $this->insertPilotViolation($user_id,$aircfat_id,$start_log_id,$flying_log_id,$violation_type,$messages,$dates);

                }

                $m=PilotFlyingLog::find($value->id);
                $m->is_analyze='yes';
                $m->save();
            }
            //

            $none_flying_duty_in_mint=0;
            $lastRow = PilotFlyingLog::where('user_id', $pilot->id)->orderBy('departure_time', 'desc')->first();
            $toDate = Carbon::parse($lastRow->arrival_time);
            $fromDate = $toDate->copy()->subDays(7);
            $data7days = PilotFlyingLog::where('user_id', $pilot->id)->whereBetween('departure_time', [$fromDate->toDateString(), $toDate->toDateString()])->get();
            $total_flying_hours=0;
            $total_duty_hours_in_mint=0;
            $flight_duty_period_start_time='';
            $landing=0;
            foreach($data7days as $value)
            {
                $landing=$landing+1;
                $departure_time=$value->departure_time;
                $arrival_time=$value->arrival_time;
                $total_flying_hours+=minutes(is_time_defrence($arrival_time,$departure_time));

                if(!empty($value->flight_duty_period_start_time))
                {
                    $flight_duty_period_start_time=$value->flight_duty_period_start_time;
                }
                if(!empty($value->break_hours))
                {
                    $time=$value->break_hours;
                    $totalMinutes = Carbon::parse($time)->hour * 60 + Carbon::parse($time)->minute;
                    if($totalMinutes>180)
                    {
                        $d=$totalMinutes-180;
                        $none_flying_duty_in_mint +=$d>0?($d/2):0;
                    }
                }
                if(!empty($value->flight_duty_period_end_time)&&!empty($flight_duty_period_start_time))
                {
                    $fling_duty_time_mint=minutes(is_time_defrence($value->flight_duty_period_end_time,$flight_duty_period_start_time));
                    $total_duty_hours_in_mint+= $fling_duty_time_mint-$none_flying_duty_in_mint;
                    $none_flying_duty_in_mint=0;
                    $flight_duty_period_start_time='';
                }

                if($total_flying_hours > 2100)//35 hours
                {
                    $messages='As per DGCA CAR section 7- SERIES-J Part 4, Issue-1,Rev-1 Dated 19 January,2023 Para 9, Sub Para 9.1 Maximum Flight time of '.getEmpFullName($value->user_id).' is '.colculate_days_hours_mints($total_flying_hours);
                    $violation_type='Flight_Time';
                    $flying_log_id=$value->flying_log_id;
                    $aircfat_id=$value->aircroft_id;
                    $user_id=$value->user_id;
                    $dates=$value->date;
                    $this->insertPilotViolation($user_id,$aircfat_id,null,$flying_log_id,$violation_type,$messages,$dates);
                }

                if($total_duty_hours_in_mint > 3600)//60 hours
                {
                    $messages='As per DGCA CAR section 7- SERIES-J Part 4, Issue-1,Rev-1 Dated 19 January,2023 Para 9, Sub Para 9.1 Maximum Flight Duty Period of '.getEmpFullName($value->user_id).' is '.colculate_days_hours_mints($total_duty_hours_in_mint);
                    $violation_type='Flight_Duty_Period';
                    $flying_log_id=$value->flying_log_id;
                    $aircfat_id=$value->aircroft_id;
                    $user_id=$value->user_id;
                    $dates=$value->date;
                    $this->insertPilotViolation($user_id,$aircfat_id,null,$flying_log_id,$violation_type,$messages,$dates);
                }

            }

            $none_flying_duty_in_mint=0;
            $lastRow = PilotFlyingLog::where('user_id', $pilot->id)->orderBy('departure_time', 'desc')->first();
            $toDate = Carbon::parse($lastRow->arrival_time);
            $fromDate = $toDate->copy()->subDays(14);
            $data14days = PilotFlyingLog::where('user_id', $pilot->id)->whereBetween('departure_time', [$fromDate->toDateString(), $toDate->toDateString()])->get();
            $total_flying_hours=0;
            $total_duty_hours_in_mint=0;
            $flight_duty_period_start_time='';
            $landing=0;
            foreach($data14days as $value)
            {
                $landing=$landing+1;
                $departure_time=$value->departure_time;
                $arrival_time=$value->arrival_time;
                $total_flying_hours+=minutes(is_time_defrence($arrival_time,$departure_time));

                if(!empty($value->flight_duty_period_start_time))
                {
                    $flight_duty_period_start_time=$value->flight_duty_period_start_time;
                }
                if(!empty($value->break_hours))
                {
                    $time=$value->break_hours;
                    $totalMinutes = Carbon::parse($time)->hour * 60 + Carbon::parse($time)->minute;
                    if($totalMinutes>180)
                    {
                        $d=$totalMinutes-180;
                        $none_flying_duty_in_mint +=$d>0?($d/2):0;
                    }
                }
                if(!empty($value->flight_duty_period_end_time)&&!empty($flight_duty_period_start_time))
                {
                    $fling_duty_time_mint=minutes(is_time_defrence($value->flight_duty_period_end_time,$flight_duty_period_start_time));
                    $total_duty_hours_in_mint+= $fling_duty_time_mint-$none_flying_duty_in_mint;
                    $none_flying_duty_in_mint=0;
                    $flight_duty_period_start_time='';
                }

                if($total_flying_hours > 3900)//65 hours
                {
                    $messages='As per DGCA CAR section 7- SERIES-J Part 4, Issue-1,Rev-1 Dated 19 January,2023 Para 9, Sub Para 9.2 Maximum Flight time of '.getEmpFullName($value->user_id).' is '.colculate_days_hours_mints($total_flying_hours);
                    $violation_type='Flight_Time';
                    $flying_log_id=$value->flying_log_id;
                    $aircfat_id=$value->aircroft_id;
                    $user_id=$value->user_id;
                    $dates=$value->date;

                    $this->insertPilotViolation($user_id,$aircfat_id,null,$flying_log_id,$violation_type,$messages,$dates);
                }

                if($total_duty_hours_in_mint > 6000)//100 hours
                {
                    $messages='As per DGCA CAR section 7- SERIES-J Part 4, Issue-1,Rev-1 Dated 19 January,2023 Para 9, Sub Para 9.2 Maximum Flight Duty Period of '.getEmpFullName($value->user_id).' is '.colculate_days_hours_mints($total_duty_hours_in_mint);
                    $violation_type='Flight_Duty_Period';
                    $flying_log_id=$value->flying_log_id;
                    $aircfat_id=$value->aircroft_id;
                    $user_id=$value->user_id;
                    $dates=$value->date;
                    $this->insertPilotViolation($user_id,$aircfat_id,null,$flying_log_id,$violation_type,$messages,$dates);
                }

            }


            $none_flying_duty_in_mint=0;
            $lastRow = PilotFlyingLog::where('user_id', $pilot->id)->orderBy('departure_time', 'desc')->first();
            $toDate = Carbon::parse($lastRow->arrival_time);
            $fromDate = $toDate->copy()->subDays(28);
            $data28days = PilotFlyingLog::where('user_id', $pilot->id)->whereBetween('departure_time', [$fromDate->toDateString(), $toDate->toDateString()])->get();
            $total_flying_hours=0;
            $total_duty_hours_in_mint=0;
            $flight_duty_period_start_time='';
            $landing=0;
            foreach($data28days as $value)
            {
                $landing=$landing+1;
                $departure_time=$value->departure_time;
                $arrival_time=$value->arrival_time;
                $total_flying_hours+=minutes(is_time_defrence($arrival_time,$departure_time));

                if(!empty($value->flight_duty_period_start_time))
                {
                    $flight_duty_period_start_time=$value->flight_duty_period_start_time;
                }
                if(!empty($value->break_hours))
                {
                    $time=$value->break_hours;
                    $totalMinutes = Carbon::parse($time)->hour * 60 + Carbon::parse($time)->minute;
                    if($totalMinutes>180)
                    {
                        // $d=$totalMinutes-180;
                        $d=$totalMinutes;
                        $none_flying_duty_in_mint +=$d>0?($d/2):0;
                    }
                }
                if(!empty($value->flight_duty_period_end_time)&&!empty($flight_duty_period_start_time))
                {
                    $fling_duty_time_mint=minutes(is_time_defrence($value->flight_duty_period_end_time,$flight_duty_period_start_time));
                    $total_duty_hours_in_mint+= $fling_duty_time_mint-$none_flying_duty_in_mint;
                    $none_flying_duty_in_mint=0;
                    $flight_duty_period_start_time='';
                }

                if($total_flying_hours > 6000)//100 hours
                {
                    $messages='As per DGCA CAR section 7- SERIES-J Part 4, Issue-1,Rev-1 Dated 19 January,2023 Para 9, Sub Para 9.3 Maximum Flight time of '.getEmpFullName($value->user_id).' is '.colculate_days_hours_mints($total_flying_hours);
                    $violation_type='Flight_Time';
                    $flying_log_id=$value->flying_log_id;
                    $aircfat_id=$value->aircroft_id;
                    $user_id=$value->user_id;
                    $dates=$value->date;
                    $this->insertPilotViolation($user_id,$aircfat_id,null,$flying_log_id,$violation_type,$messages,$dates);
                }

                if($total_duty_hours_in_mint > 11400)//190 hours
                {
                    $messages='As per DGCA CAR section 7- SERIES-J Part 4, Issue-1,Rev-1 Dated 19 January,2023 Para 9, Sub Para 9.3 Maximum Flight Duty Period of '.getEmpFullName($value->user_id).' is '.colculate_days_hours_mints($total_duty_hours_in_mint);
                    $violation_type='Flight_Duty_Period';
                    $flying_log_id=$value->flying_log_id;
                    $aircfat_id=$value->aircroft_id;
                    $user_id=$value->user_id;
                    $dates=$value->date;
                    $this->insertPilotViolation($user_id,$aircfat_id,null,$flying_log_id,$violation_type,$messages,$dates);
                }
            }

            $none_flying_duty_in_mint=0;
            $lastRow = PilotFlyingLog::where('user_id', $pilot->id)->orderBy('departure_time', 'desc')->first();
            $toDate = Carbon::parse($lastRow->arrival_time);
            $fromDate = $toDate->copy()->subDays(90);
            $data90days = PilotFlyingLog::where('user_id', $pilot->id)->whereBetween('departure_time', [$fromDate->toDateString(), $toDate->toDateString()])->get();
            $total_flying_hours=0;
            $total_duty_hours_in_mint=0;
            $flight_duty_period_start_time='';
            $landing=0;
            foreach($data90days as $value)
            {
                $landing=$landing+1;
                $departure_time=$value->departure_time;
                $arrival_time=$value->arrival_time;
                $total_flying_hours+=minutes(is_time_defrence($arrival_time,$departure_time));

                if(!empty($value->flight_duty_period_start_time))
                {
                    $flight_duty_period_start_time=$value->flight_duty_period_start_time;
                }
                if(!empty($value->break_hours))
                {
                    $time=$value->break_hours;
                    $totalMinutes = Carbon::parse($time)->hour * 60 + Carbon::parse($time)->minute;
                    if($totalMinutes>180)
                    {
                        // $d=$totalMinutes-180;
                        $d=$totalMinutes;
                        $none_flying_duty_in_mint +=$d>0?($d/2):0;
                    }
                }
                if(!empty($value->flight_duty_period_end_time)&&!empty($flight_duty_period_start_time))
                {
                    $fling_duty_time_mint=minutes(is_time_defrence($value->flight_duty_period_end_time,$flight_duty_period_start_time));
                    $total_duty_hours_in_mint+= $fling_duty_time_mint-$none_flying_duty_in_mint;
                    $none_flying_duty_in_mint=0;
                    $flight_duty_period_start_time='';
                }

                if($total_flying_hours > 18000)//300 hours
                {
                    $messages='As per DGCA CAR section 7- SERIES-J Part 4, Issue-1,Rev-1 Dated 19 January,2023 Para 9, Sub Para 9.4 Maximum Flight time of '.getEmpFullName($value->user_id).' is '.colculate_days_hours_mints($total_flying_hours);
                    $violation_type='Flight_Time';
                    $flying_log_id=$value->flying_log_id;
                    $aircfat_id=$value->aircroft_id;
                    $user_id=$value->user_id;
                    $dates=$value->date;
                    $this->insertPilotViolation($user_id,$aircfat_id,null,$flying_log_id,$violation_type,$messages,$dates);
                }

                if($total_duty_hours_in_mint > 36000)//600 hours
                {
                    $messages='As per DGCA CAR section 7- SERIES-J Part 4, Issue-1,Rev-1 Dated 19 January,2023 Para 9, Sub Para 9.4 Maximum Flight Duty Period of '.getEmpFullName($value->user_id).' is '.colculate_days_hours_mints($total_duty_hours_in_mint);
                    $violation_type='Flight_Duty_Period';
                    $flying_log_id=$value->flying_log_id;
                    $aircfat_id=$value->aircroft_id;
                    $user_id=$value->user_id;
                    $dates=$value->date;
                    $this->insertPilotViolation($user_id,$aircfat_id,null,$flying_log_id,$violation_type,$messages,$dates);
                }
            }

            $none_flying_duty_in_mint=0;
            $lastRow = PilotFlyingLog::where('user_id', $pilot->id)->orderBy('departure_time', 'desc')->first();
            $toDate = Carbon::parse($lastRow->arrival_time);
            $fromDate = $toDate->copy()->subDays(365);
            $data365days = PilotFlyingLog::where('user_id', $pilot->id)->whereBetween('departure_time', [$fromDate->toDateString(), $toDate->toDateString()])->get();
            $total_flying_hours=0;
            $total_duty_hours_in_mint=0;
            $flight_duty_period_start_time='';
            $landing=0;
            foreach($data365days as $value)
            {
                $landing=$landing+1;
                $departure_time=$value->departure_time;
                $arrival_time=$value->arrival_time;
                $total_flying_hours+=minutes(is_time_defrence($arrival_time,$departure_time));

                if(!empty($value->flight_duty_period_start_time))
                {
                    $flight_duty_period_start_time=$value->flight_duty_period_start_time;
                }
                if(!empty($value->break_hours))
                {
                    $time=$value->break_hours;
                    $totalMinutes = Carbon::parse($time)->hour * 60 + Carbon::parse($time)->minute;
                    if($totalMinutes>180)
                    {
                        // $d=$totalMinutes-180;
                        $d=$totalMinutes;
                        $none_flying_duty_in_mint +=$d>0?($d/2):0;
                    }
                }
                if(!empty($value->flight_duty_period_end_time)&&!empty($flight_duty_period_start_time))
                {
                    $fling_duty_time_mint=minutes(is_time_defrence($value->flight_duty_period_end_time,$flight_duty_period_start_time));
                    $total_duty_hours_in_mint+= $fling_duty_time_mint-$none_flying_duty_in_mint;
                    $none_flying_duty_in_mint=0;
                    $flight_duty_period_start_time='';
                }

                if($total_flying_hours > 60000)//1000 hours
                {
                    $messages='As per DGCA CAR section 7- SERIES-J Part 4, Issue-1,Rev-1 Dated 19 January,2023 Para 9, Sub Para 9.5 Maximum Flight time of '.getEmpFullName($value->user_id).' is '.colculate_days_hours_mints($total_flying_hours);
                    $violation_type='Flight_Time';
                    $flying_log_id=$value->flying_log_id;
                    $aircfat_id=$value->aircroft_id;
                    $user_id=$value->user_id;
                    $dates=$value->date;
                    $this->insertPilotViolation($user_id,$aircfat_id,null,$flying_log_id,$violation_type,$messages,$dates);
                }

                if($total_duty_hours_in_mint > 108000)//1800 hours
                {
                    $messages='As per DGCA CAR section 7- SERIES-J Part 4, Issue-1,Rev-1 Dated 19 January,2023 Para 9, Sub Para 9.5 Maximum Flight Duty Period of '.getEmpFullName($value->user_id).' is '.colculate_days_hours_mints($total_duty_hours_in_mint);
                    $violation_type='Flight_Duty_Period';
                    $flying_log_id=$value->flying_log_id;
                    $aircfat_id=$value->aircroft_id;
                    $user_id=$value->user_id;
                    $dates=$value->date;
                    $this->insertPilotViolation($user_id,$aircfat_id,null,$flying_log_id,$violation_type,$messages,$dates);
                }
            }

        }
        //PilotViolation

        foreach($rotorWingPilots as $pilot)
        {

            $none_flying_duty_in_mint=0;
            $lastRow = PilotFlyingLog::where('user_id', $pilot->id)->orderBy('departure_time', 'desc')->first();
            $toDate = Carbon::parse($lastRow->arrival_time);
            $fromDate = $toDate->copy()->subDays(1);
            $data24hours = PilotFlyingLog::where('user_id', $pilot->id)->whereBetween('departure_time', [$fromDate->toDateString(), $toDate->toDateString()])->get();
            $total_flying_hours=0;
            $total_duty_hours_in_mint=0;
            $flight_duty_period_start_time='';
            $landing=0;
            foreach($data24hours as $value)
            {
                $landing=$landing+1;
                $departure_time=$value->departure_time;
                $arrival_time=$value->arrival_time;
                $total_flying_hours+=minutes(is_time_defrence($arrival_time,$departure_time));

                if(!empty($value->flight_duty_period_start_time))
                {
                    $flight_duty_period_start_time=$value->flight_duty_period_start_time;
                }
                if(!empty($value->break_hours))
                {
                    $time=$value->break_hours;
                    $totalMinutes = Carbon::parse($time)->hour * 60 + Carbon::parse($time)->minute;
                    if($totalMinutes>180)
                    {
                        // $d=$totalMinutes-180;
                        $d=$totalMinutes;
                        $none_flying_duty_in_mint +=$d>0?($d/2):0;
                    }
                }
                if(!empty($value->flight_duty_period_end_time)&&!empty($flight_duty_period_start_time))
                {
                    $fling_duty_time_mint=minutes(is_time_defrence($value->flight_duty_period_end_time,$flight_duty_period_start_time));
                    $total_duty_hours_in_mint+= $fling_duty_time_mint-$none_flying_duty_in_mint;
                    $none_flying_duty_in_mint=0;
                    $flight_duty_period_start_time='';
                }

                if($total_flying_hours > 420)//7 hours
                {
                    $messages='As per DGCA CAR section 7- SERIES-J Part 2, Issue-1,Rev-1 Dated 19 January,2023 Para 6, Sub Para 6.1.2 Maximum Flight time of '.getEmpFullName($value->user_id).' is '.colculate_days_hours_mints($total_flying_hours);
                    $violation_type='Flight_Time';
                    $flying_log_id=$value->flying_log_id;
                    $aircfat_id=$value->aircroft_id;
                    $user_id=$value->user_id;
                    $dates=$value->date;
                    $this->insertPilotViolation($user_id,$aircfat_id,null,$flying_log_id,$violation_type,$messages,$dates);
                }

                if($total_duty_hours_in_mint > 600)//10 hours
                {
                    $messages='As per DGCA CAR section 7- SERIES-J Part 2, Issue-1,Rev-1 Dated 19 January,2023 Para 6, Sub Para 6.1.1 Maximum Flight Duty Period of '.getEmpFullName($value->user_id).' is '.colculate_days_hours_mints($total_duty_hours_in_mint);
                    $violation_type='Flight_Duty_Period';
                    $flying_log_id=$value->flying_log_id;
                    $aircfat_id=$value->aircroft_id;
                    $user_id=$value->user_id;
                    $dates=$value->date;
                    $this->insertPilotViolation($user_id,$aircfat_id,null,$flying_log_id,$violation_type,$messages,$dates);
                }
                if($landing>50)
                {
                    $messages='As per DGCA CAR section 7- SERIES-J Part 2, Issue-1,Rev-1 Dated 19 January,2023 Para 6, Sub Para 6.2 Maximum Landings of '.getEmpFullName($value->user_id).' is '.$landing;
                    $violation_type='Landings';
                    $flying_log_id=$value->flying_log_id;
                    $aircfat_id=$value->aircroft_id;
                    $user_id=$value->user_id;
                    $dates=$value->date;
                    $this->insertPilotViolation($user_id,$aircfat_id,null,$flying_log_id,$violation_type,$messages,$dates);
                }
            }
            $none_flying_duty_in_mint=0;
            $lastRow = PilotFlyingLog::where('user_id', $pilot->id)->orderBy('departure_time', 'desc')->first();
            $toDate = Carbon::parse($lastRow->arrival_time);
            $fromDate = $toDate->copy()->subDays(7);
            $data7days = PilotFlyingLog::where('user_id', $pilot->id)->whereBetween('departure_time', [$fromDate->toDateString(), $toDate->toDateString()])->get();
            $total_flying_hours=0;
            $total_duty_hours_in_mint=0;
            $flight_duty_period_start_time='';
            $landing=0;
            foreach($data7days as $value)
            {
                $landing=$landing+1;
                $departure_time=$value->departure_time;
                $arrival_time=$value->arrival_time;
                $total_flying_hours+=minutes(is_time_defrence($arrival_time,$departure_time));

                if(!empty($value->flight_duty_period_start_time))
                {
                    $flight_duty_period_start_time=$value->flight_duty_period_start_time;
                }
                if(!empty($value->break_hours))
                {
                    $time=$value->break_hours;
                    $totalMinutes = Carbon::parse($time)->hour * 60 + Carbon::parse($time)->minute;
                    if($totalMinutes>180)
                    {
                        $d=$totalMinutes-180;
                        $none_flying_duty_in_mint +=$d>0?($d/2):0;
                    }
                }
                if(!empty($value->flight_duty_period_end_time)&&!empty($flight_duty_period_start_time))
                {
                    $fling_duty_time_mint=minutes(is_time_defrence($value->flight_duty_period_end_time,$flight_duty_period_start_time));
                    $total_duty_hours_in_mint+= $fling_duty_time_mint-$none_flying_duty_in_mint;
                    $none_flying_duty_in_mint=0;
                    $flight_duty_period_start_time='';
                }

                if($total_flying_hours > 1800)//30 hours
                {
                    $messages='As per DGCA CAR section 7- SERIES-J Part 2, Issue-1,Rev-1 Dated 19 January,2023 Para 6, Sub Para 6.1.2 Maximum Flight time of '.getEmpFullName($value->user_id).' is '.colculate_days_hours_mints($total_flying_hours);
                    $violation_type='Flight_Time';
                    $flying_log_id=$value->flying_log_id;
                    $aircfat_id=$value->aircroft_id;
                    $user_id=$value->user_id;
                    $dates=$value->date;
                    $this->insertPilotViolation($user_id,$aircfat_id,null,$flying_log_id,$violation_type,$messages,$dates);
                }

                if($total_duty_hours_in_mint > 3600)//60 hours
                {
                    $messages='As per DGCA CAR section 7- SERIES-J Part 2, Issue-1,Rev-1 Dated 19 January,2023 Para 6, Sub Para 6.1.1 Maximum Flight Duty Period of '.getEmpFullName($value->user_id).' is '.colculate_days_hours_mints($total_duty_hours_in_mint);
                    $violation_type='Flight_Duty_Period';
                    $flying_log_id=$value->flying_log_id;
                    $aircfat_id=$value->aircroft_id;
                    $user_id=$value->user_id;
                    $dates=$value->date;
                    $this->insertPilotViolation($user_id,$aircfat_id,null,$flying_log_id,$violation_type,$messages,$dates);
                }

            }


            $none_flying_duty_in_mint=0;
            $lastRow = PilotFlyingLog::where('user_id', $pilot->id)->orderBy('departure_time', 'desc')->first();
            $toDate = Carbon::parse($lastRow->arrival_time);
            $fromDate = $toDate->copy()->subDays(28);
            $data28days = PilotFlyingLog::where('user_id', $pilot->id)->whereBetween('departure_time', [$fromDate->toDateString(), $toDate->toDateString()])->get();
            $total_flying_hours=0;
            $total_duty_hours_in_mint=0;
            $flight_duty_period_start_time='';
            $landing=0;
            foreach($data28days as $value)
            {
                $landing=$landing+1;
                $departure_time=$value->departure_time;
                $arrival_time=$value->arrival_time;
                $total_flying_hours+=minutes(is_time_defrence($arrival_time,$departure_time));

                if(!empty($value->flight_duty_period_start_time))
                {
                    $flight_duty_period_start_time=$value->flight_duty_period_start_time;
                }
                if(!empty($value->break_hours))
                {
                    $time=$value->break_hours;
                    $totalMinutes = Carbon::parse($time)->hour * 60 + Carbon::parse($time)->minute;
                    if($totalMinutes>180)
                    {
                        // $d=$totalMinutes-180;
                        $d=$totalMinutes;
                        $none_flying_duty_in_mint +=$d>0?($d/2):0;
                    }
                }
                if(!empty($value->flight_duty_period_end_time)&&!empty($flight_duty_period_start_time))
                {
                    $fling_duty_time_mint=minutes(is_time_defrence($value->flight_duty_period_end_time,$flight_duty_period_start_time));
                    $total_duty_hours_in_mint+= $fling_duty_time_mint-$none_flying_duty_in_mint;
                    $none_flying_duty_in_mint=0;
                    $flight_duty_period_start_time='';
                }

                if($total_flying_hours > 6000)//100 hours
                {
                    $messages='As per DGCA CAR section 7- SERIES-J Part 2, Issue-1,Rev-1 Dated 19 January,2023 Para 6, Sub Para 6.1.2 Maximum Flight time of '.getEmpFullName($value->user_id).' is '.colculate_days_hours_mints($total_flying_hours);
                    $violation_type='Flight_Time';
                    $flying_log_id=$value->flying_log_id;
                    $aircfat_id=$value->aircroft_id;
                    $user_id=$value->user_id;
                    $dates=$value->date;
                    $this->insertPilotViolation($user_id,$aircfat_id,null,$flying_log_id,$violation_type,$messages,$dates);
                }

                if($total_duty_hours_in_mint > 12000)//200 hours
                {
                    $messages='As per DGCA CAR section 7- SERIES-J Part 2, Issue-1,Rev-1 Dated 19 January,2023 Para 6, Sub Para 6.1.1 Maximum Flight Duty Period of '.getEmpFullName($value->user_id).' is '.colculate_days_hours_mints($total_duty_hours_in_mint);
                    $violation_type='Flight_Duty_Period';
                    $flying_log_id=$value->flying_log_id;
                    $aircfat_id=$value->aircroft_id;
                    $user_id=$value->user_id;
                    $dates=$value->date;
                    $this->insertPilotViolation($user_id,$aircfat_id,null,$flying_log_id,$violation_type,$messages,$dates);
                }
            }

            $none_flying_duty_in_mint=0;
            $lastRow = PilotFlyingLog::where('user_id', $pilot->id)->orderBy('departure_time', 'desc')->first();
            $toDate = Carbon::parse($lastRow->arrival_time);
            $fromDate = $toDate->copy()->subDays(365);
            $data365days = PilotFlyingLog::where('user_id', $pilot->id)->whereBetween('departure_time', [$fromDate->toDateString(), $toDate->toDateString()])->get();
            $total_flying_hours=0;
            $total_duty_hours_in_mint=0;
            $flight_duty_period_start_time='';
            $landing=0;
            foreach($data365days as $value)
            {
                $landing=$landing+1;
                $departure_time=$value->departure_time;
                $arrival_time=$value->arrival_time;
                $total_flying_hours+=minutes(is_time_defrence($arrival_time,$departure_time));

                if(!empty($value->flight_duty_period_start_time))
                {
                    $flight_duty_period_start_time=$value->flight_duty_period_start_time;
                }
                if(!empty($value->break_hours))
                {
                    $time=$value->break_hours;
                    $totalMinutes = Carbon::parse($time)->hour * 60 + Carbon::parse($time)->minute;
                    if($totalMinutes>180)
                    {
                        // $d=$totalMinutes-180;
                        $d=$totalMinutes;
                        $none_flying_duty_in_mint +=$d>0?($d/2):0;
                    }
                }
                if(!empty($value->flight_duty_period_end_time)&&!empty($flight_duty_period_start_time))
                {
                    $fling_duty_time_mint=minutes(is_time_defrence($value->flight_duty_period_end_time,$flight_duty_period_start_time));
                    $total_duty_hours_in_mint+= $fling_duty_time_mint-$none_flying_duty_in_mint;
                    $none_flying_duty_in_mint=0;
                    $flight_duty_period_start_time='';
                }

                if($total_flying_hours > 60000)//1000 hours
                {
                    $messages='As per DGCA CAR section 7- SERIES-J Part 2, Issue-1,Rev-1 Dated 19 January,2023 Para 6, Sub Para 6.1.2 Maximum Flight time of '.getEmpFullName($value->user_id).' is '.colculate_days_hours_mints($total_flying_hours);
                    $violation_type='Flight_Time';
                    $flying_log_id=$value->flying_log_id;
                    $aircfat_id=$value->aircroft_id;
                    $user_id=$value->user_id;
                    $dates=$value->date;
                    $this->insertPilotViolation($user_id,$aircfat_id,null,$flying_log_id,$violation_type,$messages,$dates);
                }
            }

            $data=PilotFlyingLog::where('user_id',$pilot->id)->orderBy('departure_time', 'desc')->get();

            foreach($data as $value)
            {
                if($value->week_start==1)
                {
                    $mn= PilotFlyingLog::where('user_id',$pilot->id)->where('week_end',1)->whereBetween('date', [date('Y-m-d',strtotime($value->date)), date('Y-m-d',strtotime($value->date.' +6 days'))])->count();
                    if($mn==0)
                    {
                        $messages='As per DGCA CAR section 7- SERIES-J Part 2, Issue-1,Rev-1 Dated 19 January,2023 Para 7, Sub Para 7.1.1 Maximum Days off in station '.getEmpFullName($value->user_id);
                        $violation_type='Flight_Time';
                        $flying_log_id=$value->flying_log_id;
                        $aircfat_id=$value->aircroft_id;
                        $user_id=$value->user_id;
                        $dates=$value->date;
                        $this->insertPilotViolation($user_id,$aircfat_id,null,$flying_log_id,$violation_type,$messages,$dates);
                    }
                }
            }
        }
    }

    public function insertPilotViolation($user_id,$aircfat_id,$start_log_id,$flying_log_id,$violation_type,$messages,$dates)
    {
        $data=new PilotViolation;
        $data->user_id=$user_id;
        $data->aircfat_id=$aircfat_id;
        $data->start_log_id=$start_log_id;
        $data->flying_log_id=$flying_log_id;
        $data->violation_type=$violation_type;
        $data->messages=$messages;
        $data->dates=date('Y-m-d',strtotime($dates));
        $data->save();
    }

    public function receiveFlightDoc()
    {
        $pilots = User::where('designation', '1')->where('status', 'active')->get();
        $aircrafts = AirCraft::where('status', 'active')->get();
        $flying_types = Master::where('type', 'flying_type')->where('status', 'active')->get();
        $pilot_roles = Master::where('type', 'pilot_role')->where('status', 'active')->get();
        $passengers = Master::where('type', 'passenger')->where('status', 'active')->get();
        return view('flying_logs.receive-flight-doc', compact('pilots', 'aircrafts','flying_types','pilot_roles','passengers'));
    }

    public function receiveFlightDocAdd()
    {
        $pilots = User::where('designation', '1')->where('status', 'active')->get();
        $aircrafts = AirCraft::where('status', 'active')->get();
        $flying_types = Master::where('type', 'flying_type')->where('status', 'active')->get();
        $pilot_roles = Master::where('type', 'pilot_role')->where('status', 'active')->get();
        $post_flight_doc = Master::where('type', 'post_flight_doc')->where('status', 'active')->get();
        $passengers = Master::where('type', 'passenger')->where('status', 'active')->get();
        $last_data=FlightDocAssign::latest()->first();
        return view('flying_logs.receive-flight-doc-manage', compact('pilots','passengers','last_data', 'aircrafts','flying_types','pilot_roles','post_flight_doc'));
    }

    public function receiveFlightDocStore(Request $request)
    {
         $validation = Validator::make($request->all(), [
            'bunch_no' => 'required',
            'dates' => 'required',
        ]);
        if ($validation->fails()) {
            return response()->json(['success' => false, 'message' => $validation->errors()]);
        }
        $bunch_no = $request->bunch_no;
        $dates = $request->dates;
        $log_id = $request->log_id;
        $edit_id = $request->edit_id;
        $doc_id = $request->doc_id;
        $document = $request->document;
        $document_dc = $request->document_dc;
        $day_officers = $request->day_officers;
        $remark = $request->remark;
        if(!empty($edit_id))
        {
            $data=FlightDocAssign::find($edit_id);
        }else{
            $data=new FlightDocAssign;
        }
        $data->bunch_no=$bunch_no;
        $data->dates=is_set_date_format($dates);
        $data->flying_logs=$log_id;
        $data->day_officers=$day_officers;
        $data->remark=$remark;
        $documents=[];
        if(!empty($doc_id))
        {
            foreach($doc_id as $docid)
            {
                if(!empty($document[$docid]))
                {
                    $file=$document[$docid];
                    $name = time().rand(1,100).'.'.$file->extension();
                    $file->move(public_path('uploads/flight_doc'), $name);
                    $documents[$docid]=$name;
                }else{
                    if(!empty($document_dc[$docid]))
                    {
                        $documents[$docid]=$document_dc[$docid];
                    }else{
                        $documents[$docid]='';
                    }
                }
                $data->documents=$documents;
            }
        }
        //print_r($documents);die;

        $data->save();

        return response()->json([
            'success' => true,
            'message' => 'Successfully'
        ]);
    }

    public function receiveFlightDocEdit($id)
    {
        $pilots = User::where('designation', '1')->where('status', 'active')->get();
        $aircrafts = AirCraft::where('status', 'active')->get();
        $flying_types = Master::where('type', 'flying_type')->where('status', 'active')->get();
        $pilot_roles = Master::where('type', 'pilot_role')->where('status', 'active')->get();
        $post_flight_doc = Master::where('type', 'post_flight_doc')->where('status', 'active')->get();
        $passengers = Master::where('type', 'passenger')->where('status', 'active')->get();
        $data=FlightDocAssign::find($id);
        return view('flying_logs.receive-flight-doc-manage', compact('pilots','passengers','data','aircrafts','flying_types','pilot_roles','post_flight_doc'));
    }

    public function receiveFlightDocList(Request $request)
    {

        $column = ['id', 'bunch_no', 'dates','day_officers','remark', 'id'];
        $users = FlightDocAssign::where('id', '>', '0');
        $total_row = $users->get()->count();

        if(!empty($_POST['bunch_no']))
        {
            $bunch_no=$_POST['bunch_no'];
            $users->where('bunch_no','=',$bunch_no);
        }
        if(!empty($_POST['passenger']))
        {
            $passenger=$_POST['passenger'];
            $users->where('day_officers','=',$passenger);
        }
        if(!empty($_POST['from_date'])&&empty($_POST['to_date']))
        {
            $from=$_POST['from_date'];
            $users->where('dates','>=',date('Y-m-d',strtotime($from)));
        }

        if(empty($_POST['from_date'])&&!empty($_POST['to_date']))
        {
            $to=$_POST['to_date'];
            $users->where('dates','<=',date('Y-m-d',strtotime($to)));
        }
        if(!empty($_POST['from_date'])&&!empty($_POST['to_date']))
        {
            $from=$_POST['from_date'];
            $to=$_POST['to_date'];
            $users->where(function($q) use($from, $to){
                $q->whereBetween('dates', [date('Y-m-d',strtotime($from)), date('Y-m-d',strtotime($to))]);
            });
        }

        if (isset($_POST['search'])&&!empty($_POST['search']['value'])) {
            $users->where('dates', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $users->orWhere('bunch_no', 'LIKE', '%' . $_POST['search']['value'] . '%');
        }

        if (isset($_POST['order'])) {
            $users->orderBy($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $users->orderBy('id', 'desc');
        }

        $filter_row = $users->get()->count();
        if (isset($_POST["length"]) && $_POST["length"] != -1) {
            $users->skip($_POST["start"])->take($_POST["length"]);
        }
        $result = $users->get();

        $data = array();
        $times = array();
        foreach ($result as $key => $value) {
            $action  ='<a href="'.route('app.flying-details.receiveFlightDoc.edit',$value->id).'" class="btn btn-info btn-sm m-1">Edit</a>';
            $action  .= '<a href="javascript:void(0);" onclick="openFlightDetailModel(\'' . $value->id . '\', \'Flying\');" class="btn btn-success btn-sm m-1">View Flying</a>';
            $action  .= '<a href="javascript:void(0);" onclick="openFlightDetailModel(\'' . $value->id . '\', \'Docs\');" class="btn btn-warning btn-sm m-1">View Doc</a>';

            //$action  .= '<a href="' . route('app.flying-details.edit', $value->id) . '" class="btn btn-warning btn-sm m-1">Edit</a>';
            //$action .= '<a href="javascript:void(0);" onclick="deleted(`' . route('app.flying-details.destroy', $value->id) . '`);" class="btn btn-danger btn-sm m-1">Delete</a>';

            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = $value->bunch_no;
            $sub_array[] = is_get_date_format($value->dates);
            $sub_array[] = getMasterName($value->day_officers);
            $sub_array[] = $value->remark;
            $sub_array[] =  $action;
            $data[] = $sub_array;
        }
        $totalTime = AddPlayTime($times);
        $output = array(
            "draw"       =>  intval($_POST["draw"]),
            "recordsTotal"   =>  $total_row,
            "recordsFiltered"  =>  $filter_row,
            "data"       =>  $data,
        );

        echo json_encode($output);
    }

    public function postFlightDocPrint($from_date = '',$to_date='',$passenger = '',$bunch_no='')
    {

        $users = FlightDocAssign::where('id', '>', '0');

        if (!empty($bunch_no) && $bunch_no !== 'NA') {
            $users->where('bunch_no', '=', $bunch_no);
        }
        if(!empty($passenger) && $passenger !== 'NA')
        {
            $users->where('day_officers','=',$passenger);
        }
        if(!empty($from_date) && $from_date !== 'NA' && $to_date !== 'NA')
        {
            $users->where('dates','>=',date('Y-m-d',strtotime($from_date)));
        }

        if($from_date !== 'NA' && !empty($to_date) && $to_date !== 'NA')
        {
            $users->where('dates','<=',date('Y-m-d',strtotime($to_date)));
        }
        if(!empty($from_date) && $from_date !== 'NA' && !empty($to_date) && $to_date !== 'NA')
        {
            $users->where(function($q) use($from_date, $to_date){
                $q->whereBetween('dates', [date('Y-m-d',strtotime($from_date)), date('Y-m-d',strtotime($to_date))]);
            });
        }
        $users->orderBy('dates', 'DESC');
        $data['from'] = $from_date;
        $data['to'] = $to_date;
        $data['users'] = $users->get();
        // return $data['users'];
        return view('flying_logs.receive-flight-doc-print', $data);
    }

    public function openFlightDetailModel(Request $request)
    {
        $id = $request->id;
        $type = $request->type;
        $row = FlightDocAssign::where('id', $id)->first();
        if ($type == 'Flying') {
            $title = 'Flying Details';
        }else{
            $title = 'Doc Details';
        }
        $html = '';
        if(!empty($row->flying_logs))
        {
            $logs = FlyingLog::whereIn('id', array_unique($row->flying_logs))->with('aircraft')->get();

            if ($type == 'Flying') {

                $html .= '<table class="table table-bordered">';
                $html .= '<thead>';
                $html .= '<tr>';
                $html .= '<th>Date</th>';
                $html .= '<th>Aircraft</th>';
                $html .= '<th>Sector From/To</th>';
                $html .= '<th>Pilots</th>';
                $html .= '</tr>';
                $html .= '</thead>';
                $html .= '<tbody>';
                if(!empty($logs))
                {
                    foreach ($logs as $value) {
                        $html .= '<tr>';
                        $html .= '<td>' . is_get_date_format($value->dates) . '</td>';
                        $html .= '<td>' . $value->aircraft->call_sign . '</td>';
                        $html .= '<td>' . $value->fron_sector . ' / ' . $value->to_sector . '</td>';
                        $html .= '<td>' . $value->pilot1->name . ' / ' . $value->pilot2->name . '</td>';
                        $html .= '</tr>';
                    }
                }
                $html .= '</tbody>';
                $html .= '</table>';
            } elseif ($type == 'Docs') {

                $html .= '<table class="table table-bordered">';
                $html .= '<thead>';
                $html .= '<tr>';
                $html .= '<th>Doc Name</th>';
                $html .= '<th>View</th>';
                $html .= '</tr>';
                $html .= '</thead>';
                $html .= '<tbody>';
                if(!empty($row->documents))
                {
                    foreach ($row->documents as $id => $file) {
                        $html .= '<tr>';
                        $html .= '<td>' . getMasterName($id) . '</td>';
                        $html .= '<td> '.(!empty($file)?'<a href="' . asset('/uploads/flight_doc/' . $file) . '" target="_blank"><i class="fa fa-eye"></i></a>':'').'</td>';
                        $html .= '</tr>';
                    }
                }
                $html .= '</tbody>';
                $html .= '</table>';
            }
        }
        return response()->json(['success' => true, 'html' => $html, 'title' => $title]);
    }

    public function receiveFlightList(Request $request)
    {
        $column = ['id', 'date',  'aircraft_id','fron_sector','departure_time', 'pilot1_id', 'id'];
        $users = FlyingLog::with(['pilot1', 'pilot2', 'aircraft'])->where('id', '>', '0');
        $total_row = $users->get()->count();

        $edit_id=$_POST['edit_id'];
        if(!empty($_POST['from_date'])&&empty($_POST['to_date']))
        {
            $from=$_POST['from_date'];
            $users->where('date','>=',date('Y-m-d',strtotime($from)));
        }
        if(empty($_POST['from_date'])&&!empty($_POST['to_date']))
        {
            $to=$_POST['to_date'];
            $users->where('date','<=',date('Y-m-d',strtotime($to)));
        }
        if(!empty($_POST['from_date'])&&!empty($_POST['to_date']))
        {
            $from=$_POST['from_date'];
            $to=$_POST['to_date'];
            $users->where(function($q) use($from, $to){
                $q->whereBetween('date', [date('Y-m-d',strtotime($from)), date('Y-m-d',strtotime($to))]);
            });
        }

        if(!empty($_POST['aircraft']))
        {
          $users->where('aircraft_id',$_POST['aircraft']);
        }
        if(!empty($_POST['pilot']))
        {
            $pilot=$_POST['pilot'];
            $users->where(function($q) use($pilot){
                $q->where('pilot1_id', $pilot);
                $q->orWhere('pilot2_id', $pilot);
            });
        }
        if (isset($_POST['search'])&&!empty($_POST['search']['value'])) {
            $users->where('fron_sector', 'LIKE', '%' . $_POST['search']['value'] . '%');
        }
        if (isset($_POST['order'])) {
            $users->orderBy($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $users->orderBy('id', 'desc');
        }
        $filter_row = $users->get()->count();
        if (isset($_POST["length"]) && $_POST["length"] != -1) {
            $users->skip($_POST["start"])->take($_POST["length"]);
        }
        $result = $users->get();
        $data = array();
        $row =!empty($edit_id)?FlightDocAssign::find($edit_id)->flying_logs:[];
        //print_r($row);
        foreach ($result as $key => $value) {

            $sub_array = array();
            $sub_array[] = '<input type="checkbox" name="log_id[]"  '.(!empty($row)&&in_array($value->id,$row)?'checked':'').'  value="'.$value->id.'">';
            $sub_array[] = is_get_date_format($value->date);
            $sub_array[] = @$value->aircraft->call_sign;
            $sub_array[] = $value->fron_sector.' / '.$value->to_sector;
            $sub_array[] = date('H:i',strtotime($value->departure_time)).' / '. date('H:i',strtotime($value->arrival_time));
            $sub_array[] = @$value->pilot1->salutation . ' ' . @$value->pilot1->name.'-'.$this->getMasterName($value->pilot1_role,'pilot_role').' /<br> '.@$value->pilot2->salutation . ' ' . @$value->pilot2->name.'-'.$this->getMasterName($value->pilot2_role,'pilot_role');

            $data[] = $sub_array;
        }

        $output = array(
            "draw"       =>  intval($_POST["draw"]),
            "recordsTotal"   =>  $total_row,
            "recordsFiltered"  =>  $filter_row,
            "data"       =>  $data,
        );

        echo json_encode($output);
    }

    public function receiveFlightDocUpdate(Request $request)
    {

    }

    public function lkoheVilkLko(Request $request)
    {
        $pilots = User::where('designation', '1')->where('status', 'active')->get();
        $aircrafts = AirCraft::where('status', 'active')->get();
        $flying_types = Master::where('type', 'flying_type')->where('status', 'active')->get();
        $pilot_roles = Master::where('type', 'pilot_role')->where('status', 'active')->get();
        $passengers = Master::where('type', 'passenger')->where('status', 'active')->get();
        return view('flying_logs.lkohe-vilk-lko', compact('pilots', 'aircrafts','flying_types','pilot_roles','passengers'));
    }

    public function lkoheVilkLkoList(Request $request)
    {
        $column = ['id', 'departure_time', 'aircraft_id', 'fron_sector','departure_time', 'departure_time', 'pilot1_id', 'flying_type','passenger', 'id'];
        $users = FlyingLog::with(['pilot1', 'pilot2', 'aircraft'])->where('id', '>', '0');
        $users->where(function($q){
                $q->where(function($m){
                    $m->where('fron_sector','LKOHE');
                    $m->where('to_sector','VILK');
                });
                $q->orWhere(function($m){
                    $m->where('fron_sector','VILK');
                    $m->where('to_sector','LKOHE');
                });

                $q->orWhere(function($m){
                    $m->where('fron_sector','LKO H/P');
                    $m->where('to_sector','LKOHE');
                });
                $q->orWhere(function($m){
                    $m->where('fron_sector','LKOHE');
                    $m->where('to_sector','LKO H/P');
                });
                $q->orWhere(function($m){
                    $m->where('fron_sector','LKO H/P');
                    $m->where('to_sector','VILK');
                });
                $q->orWhere(function($m){
                    $m->where('fron_sector','VILK');
                    $m->where('to_sector','LKO H/P');
                });

            });

        $total_row = $users->get()->count();

        if(!empty($_POST['from_date'])&&empty($_POST['to_date']))
        {
            $from=$_POST['from_date'];
            $users->where('date','>=',date('Y-m-d',strtotime($from)));
        }
        if(empty($_POST['from_date'])&&!empty($_POST['to_date']))
        {
            $to=$_POST['to_date'];
            $users->where('date','<=',date('Y-m-d',strtotime($to)));
        }
        if(!empty($_POST['from_date'])&&!empty($_POST['to_date']))
        {
            $from=$_POST['from_date'];
            $to=$_POST['to_date'];
            $users->where(function($q) use($from, $to){
                $q->whereBetween('date', [date('Y-m-d',strtotime($from)), date('Y-m-d',strtotime($to))]);
            });
        }

        if(!empty($_POST['from_sector']))
        {
          $users->where('fron_sector',$_POST['from_sector']);
        }
        if(!empty($_POST['to_sector']))
        {
          $users->where('to_sector',$_POST['to_sector']);
        }
        if(!empty($_POST['aircraft']))
        {
          $users->where('aircraft_id',$_POST['aircraft']);
        }
        if(!empty($_POST['pilot']))
        {
            $pilot=$_POST['pilot'];
            $users->where(function($q) use($pilot){
                $q->where('pilot1_id', $pilot);
                $q->orWhere('pilot2_id', $pilot);
            });
        }
        if(!empty($_POST['flying_type']))
        {
          $users->where('flying_type', $_POST['flying_type']);
        }
        if(!empty($_POST['passenger']))
        {
          $users->whereJsonContains('passenger',$_POST['passenger']);
        }

        if (isset($_POST['search'])&&!empty($_POST['search']['value'])) {
            $users->where('comment', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $users->orWhere('fron_sector', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $users->orWhere('to_sector', 'LIKE', '%' . $_POST['search']['value'] . '%');
        }
        if (isset($_POST['order'])) {
            $users->orderBy($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $users->orderBy('id', 'desc');
        }
        $filter_row = $users->get()->count();
        if (isset($_POST["length"]) && $_POST["length"] != -1) {
            $users->skip($_POST["start"])->take($_POST["length"]);
        }
        $result = $users->get();

        $data = array();
        $times = array();
        foreach ($result as $key => $value) {
            $action  ='';
            if($value->is_process=='no')
            {
                $action  .= '<a href="' . route('app.flying-details.edit', $value->id) . '" class="btn btn-warning btn-sm m-1">Edit</a>';
                $action .= '<a href="javascript:void(0);" onclick="deleted(`' . route('app.flying-details.destroy', $value->id) . '`);" class="btn btn-danger btn-sm m-1">Delete</a>';
            }else{
                $action  .='<a href="javascipt:void(0);" class="btn btn-success btn-sm m-1">Processed</a>';
            }
            $times[] = is_time_defrence($value->departure_time, $value->arrival_time);
            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = is_get_date_format($value->date);
            $sub_array[] = @$value->aircraft->call_sign;
            $sub_array[] = $value->fron_sector.' /<br>'.$value->to_sector;
            $sub_array[] = is_set_time_format($value->departure_time).' / '.is_set_time_format($value->arrival_time);//date('H:i',strtotime($value->departure_time)).' /<br>'. date('H:i',strtotime($value->arrival_time));
            $sub_array[] = is_time_defrence($value->departure_time, $value->arrival_time);
            $sub_array[] = @$value->pilot1->salutation . ' ' . @$value->pilot1->name.'-'.$this->getMasterName($value->pilot1_role,'pilot_role').' /<br>'.@$value->pilot2->salutation . ' ' . @$value->pilot2->name.'-'.$this->getMasterName($value->pilot2_role,'pilot_role');
            $sub_array[] = $this->getMasterName($value->flying_type,'flying_type');
            $sub_array[] = !empty($value->passenger)?implode(', ',$value->passenger):'';
            $sub_array[] =  $action;
            $data[] = $sub_array;
        }
        $totalTime = AddPlayTime($times);
        $output = array(
            "draw"       =>  intval($_POST["draw"]),
            "recordsTotal"   =>  $total_row,
            "recordsFiltered"  =>  $filter_row,
            "data"       =>  $data,
            "totalTime"       =>  $totalTime,
        );

        echo json_encode($output);
    }

    public function myShortie()
    {
        $pilots = User::where('designation', '1')->where('status', 'active')->get();
        $aircrafts = AirCraft::where('status', 'active')->get();
        $flying_types = Master::where('type', 'flying_type')->where('status', 'active')->get();
        $pilot_roles = Master::where('type', 'pilot_role')->where('status', 'active')->get();
        $passengers = Master::where('type', 'passenger')->where('status', 'active')->get();
        $pilot_role = Master::where('type', 'pilot_role')->where('status', 'active')->get();
        return view('theme-one.flying_logs.my-shortie', compact('pilots', 'aircrafts','flying_types','pilot_roles','passengers','pilot_role'));
    }
    public function myShortieList(Request $request)
    {
        $column = ['id', 'departure_time', 'aircraft_id', 'fron_sector','departure_time', 'departure_time', 'pilot1_id', 'flying_type','passenger', 'id'];
        $users = PilotLog::with(['pilot', 'aircraft'])->where('id', '>', '0');

        if(!empty($_POST['pilot']))
        {
            $pilot=$_POST['pilot'];
            $users->where('user_id',$pilot);
        }
        $total_row = $users->get()->count();

        if(!empty($_POST['from_date'])&&empty($_POST['to_date']))
        {
            $from=$_POST['from_date'];
            $users->where('date','>=',date('Y-m-d',strtotime($from)));
        }
        if(empty($_POST['from_date'])&&!empty($_POST['to_date']))
        {
            $to=$_POST['to_date'];
            $users->where('date','<=',date('Y-m-d',strtotime($to)));
        }
        if(!empty($_POST['from_date'])&&!empty($_POST['to_date']))
        {
            $from=$_POST['from_date'];
            $to=$_POST['to_date'];
            $users->where(function($q) use($from, $to){
                $q->whereBetween('date', [date('Y-m-d',strtotime($from)), date('Y-m-d',strtotime($to))]);
            });
        }

        if(!empty($_POST['from_sector']))
        {
          $users->where('fron_sector',$_POST['from_sector']);
        }
        if(!empty($_POST['to_sector']))
        {
          $users->where('to_sector',$_POST['to_sector']);
        }
        if(!empty($_POST['aircraft']))
        {
          $users->where('aircraft_id',$_POST['aircraft']);
        }
        if(!empty($_POST['flying_type']))
        {
          $users->where('flying_type', $_POST['flying_type']);
        }

        if (isset($_POST['search'])&&!empty($_POST['search']['value'])) {
            $users->orWhere('fron_sector', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $users->orWhere('to_sector', 'LIKE', '%' . $_POST['search']['value'] . '%');
        }
        if (isset($_POST['order'])) {
            $users->orderBy($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $users->orderBy('id', 'desc');
        }
        $filter_row = $users->get()->count();
        if (isset($_POST["length"]) && $_POST["length"] != -1) {
            $users->skip($_POST["start"])->take($_POST["length"]);
        }
        $result = $users->get();

        $data = array();
        $times = array();
        foreach ($result as $key => $value) {
            $action  ='';

            $action  .='<a href="javascipt:void(0);" class="btn btn-success btn-sm m-1">Verified</a>';

            $times[] = is_time_defrence($value->departure_time, $value->arrival_time);
            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = is_get_date_format($value->date);
            $sub_array[] = @$value->aircraft->call_sign;
            $sub_array[] = $value->fron_sector.' /<br>'.$value->to_sector;
            $sub_array[] = is_set_time_format($value->departure_time).' / '.is_set_time_format($value->arrival_time);//date('H:i',strtotime($value->departure_time)).' /'. date('H:i',strtotime($value->arrival_time));
            $sub_array[] = is_time_defrence($value->departure_time, $value->arrival_time);
            $sub_array[] = $this->getMasterName($value->user_role,'pilot_role');
            $sub_array[] = $this->getMasterName($value->flying_type,'flying_type');
            $sub_array[] =  $action;
            $data[] = $sub_array;
        }
        $totalTime = AddPlayTime($times);
        $output = array(
            "draw"       =>  intval($_POST["draw"]),
            "recordsTotal"   =>  $total_row,
            "recordsFiltered"  =>  $filter_row,
            "data"       =>  $data,
            "totalTime"       =>  $totalTime,
        );

        echo json_encode($output);
    }

    public function verify($id)
    {
        $id = encrypter('decrypt',$id);
        if(empty($id))
        {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
        $result = DB::table('flying_logs')->select(
            'id',
            'pilot1_id',
            'pilot1_role',
            'pilot2_id',
            'pilot2_role',
            'aircraft_id',
            'date',
            'fron_sector',
            'to_sector',
            'departure_time',
            'arrival_time',
            'night_time',
            'flying_type',
            'is_process',
            DB::raw("'internal' as demo_column")
        );
        $result->where('id',$id);
        $result->where('is_process','no')->orderBy('departure_time','DESC');
        $results = $result->get();
        foreach($results as $value)
        {
            if($value->demo_column=='external')
            {
                $do=ExternalFlyingLog::find($value->id);
                $this->savePilotLog($value->id,$value->aircraft_id,is_set_date_format($value->departure_time),$value->pilot1_id,$value->pilot1_role,$value->flying_type,$value->fron_sector,$value->to_sector,$value->departure_time,$value->arrival_time,(!empty($value->night_time)?$value->night_time:null),$value->demo_column);
            }else{
                $do=FlyingLog::find($value->id);
                $this->savePilotLog($value->id,$value->aircraft_id,is_set_date_format($value->departure_time),$value->pilot1_id,$value->pilot1_role,$value->flying_type,$value->fron_sector,$value->to_sector,$value->departure_time,$value->arrival_time,(!empty($value->night_time)?$value->night_time:null),$value->demo_column);
                $this->savePilotLog($value->id,$value->aircraft_id,is_set_date_format($value->departure_time),$value->pilot2_id,$value->pilot2_role,$value->flying_type,$value->fron_sector,$value->to_sector,$value->departure_time,$value->arrival_time,(!empty($value->night_time)?$value->night_time:null),$value->demo_column);
            }
            $do->is_process='yes';
            $do->save();
        }
        return redirect()->back()->with('success', 'Log Verified Successfully');
    }

}
