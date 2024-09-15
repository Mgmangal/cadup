<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AirCraft;
use App\Models\ExternalFlyingLog;
use App\Models\User;
use App\Models\Master;
use Illuminate\Support\Facades\Validator;

class ExternalFlyingLogController extends Controller
{
    public function index()
    {
        $pilots = User::where('designation', '1')->where('status', 'active')->get();
        $aircrafts = AirCraft::where('status', 'active')->get();
        $flying_types = Master::where('type', 'flying_type')->where('status', 'active')->get();
        $pilot_roles = Master::where('type', 'pilot_role')->where('status', 'active')->get();
        $aircraft_types = Master::where('type', 'aircraft_type')->where('status', 'active')->get();
        return view('external_flying_logs.index', compact('pilots','aircraft_types', 'aircrafts','flying_types','pilot_roles'));
    }

    public function create()
    {
        $pilots = User::where('designation', '1')->where('status', 'active')->get();
        $aircrofts = AirCraft::where('status', 'active')->get();
        $flying_types = Master::where('type', 'flying_type')->where('status', 'active')->get();
        $pilot_roles = Master::where('type', 'pilot_role')->where('status', 'active')->get();
        $aircraft_types = Master::where('type', 'aircraft_type')->where('status', 'active')->get();
        return view('external_flying_logs.create', compact('pilots', 'aircraft_types','aircrofts','flying_types','pilot_roles'));
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
        $aircraft_type = $request->aircraft_type;
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

        foreach ($fron_sector as $key => $fron_sector) {
            ExternalFlyingLog::create([
                'date' => is_set_date_format($date),
                'aircraft_id' => $aircraft_id,
                'aircraft_type' => $aircraft_type,
                'pilot1_id' => $pilot1_id[$key],
                'pilot1_role' => $pilot1_role[$key],
                'pilot2_id' => $pilot2_id[$key],
                'pilot2_role' => $pilot2_role[$key],
                'fron_sector' => $fron_sector,
                'to_sector' => $to_sector[$key],
                'flying_type' => $flying_type[$key],
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
        $aircrofts = AirCraft::where('status', 'active')->get();
        $data = ExternalFlyingLog::find($id);
        $flying_types = Master::where('type', 'flying_type')->where('status', 'active')->get();
        $pilot_roles = Master::where('type', 'pilot_role')->where('status', 'active')->get();
        $aircraft_types = Master::where('type', 'aircraft_type')->where('status', 'active')->get();
        return view('external_flying_logs.edit', compact('data', 'pilots','aircraft_types', 'aircrofts','flying_types','pilot_roles'));
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
        $data = ExternalFlyingLog::find($id);
        $data->update([
            'date' => is_set_date_format($request->date),
            'aircraft_id' => $request->aircraft_id,
            'aircraft_type' => $request->aircraft_type,
            'pilot1_id' => $request->pilot1_id,
            'pilot1_role' => $request->pilot1_role,
            'pilot2_id' => $request->pilot2_id,
            'pilot2_role' => $request->pilot2_role,
            'fron_sector' => $request->fron_sector,
            'to_sector' => $request->to_sector,
            'flying_type' => $request->flying_type,
            'departure_time' => is_set_date_time_format($request->departure_time),
            'arrival_time' => is_set_date_time_format($request->arrival_time),
            'night_time' => !empty($night_time)?$night_time:null,
        ]);

        return response()->json(['success' => true, 'message' => 'Data Updated successfully.']);
    }

    public function destroy($id)
    {
        $data = ExternalFlyingLog::find($id);
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
        $column = ['id', 'pilot1_id', 'date', 'aircraft_id', 'fron_sector', 'to_sector', 'id', 'pilot1_role', 'flying_type', 'id'];
        $users = ExternalFlyingLog::with(['pilot1'])->where('id', '>', '0');

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

        if(!empty($_POST['aircraft']))
        {
            $aircrafts = AirCraft::where('aircraft_cateogry', $_POST['aircraft'])->pluck('aircraft_type');
            $users->whereIn('aircraft_type',$aircrafts)->get();
        }
        if(!empty($_POST['pilot']))
        {
            $pilot=$_POST['pilot'];
            $users->where(function($q) use($pilot){
                $q->where('pilot1_id', $pilot);
                // $q->orWhere('pilot2_id', $pilot);
            });
        }
        if(!empty($_POST['flying_type']))
        {
          $users->where('flying_type', $_POST['flying_type']);
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
        foreach ($result as $key => $value) {
            $action  = '<a href="' . route('app.external.flying-details.edit', $value->id) . '" class="btn btn-warning btn-sm m-1">Edit</a>';
            $action .= '<a href="javascript:void(0);" onclick="deleted(`' . route('app.external.flying-details.destroy', $value->id) . '`);" class="btn btn-danger btn-sm m-1">Delete</a>';

            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = is_get_date_format($value->date);
            $sub_array[] = @$value->aircraft_id;
            $sub_array[] = $value->fron_sector.' /<br>'.$value->to_sector;
            $sub_array[] = is_set_time_format($value->departure_time).' / '.is_set_time_format($value->arrival_time);//date('H:i',strtotime($value->departure_time)).' /<br>'. date('H:i',strtotime($value->arrival_time));
            $sub_array[] = is_time_defrence($value->departure_time, $value->arrival_time);
            $sub_array[] = @$value->pilot1->salutation . ' ' . @$value->pilot1->name.'-'.getMasterName($value->pilot1_role,'pilot_role');
            $sub_array[] = getMasterName($value->flying_type,'flying_type');
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

    function lastLocation(Request $request)
    {
        $aircroft_id=$request->aircroft_id;
        $rw=ExternalFlyingLog::where('aircraft_id',$aircroft_id)->orderBy('id','desc')->first();
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
        // $aircrafts = AirCraft::where('status', 'active')->get();
        $flying_types = array( '1' => 'Agriculture minister', '2' => 'Cabinet Minister', '3' => 'CM', '4' => 'CS', '5' => 'DGP', '6' => 'Dy. CM', '7' => 'Governor', '8' => 'Positioning', '9' => 'PPC', '10' => 'RTB', '11' => 'Speaker UP', '12' => 'VIP', '13' => 'VVIP', '14' => 'Home Secretary', '15' => 'Personal Secretary Home', '16' => 'AG', '17' => 'Maintenance', '18' => 'ADG', '19' => 'Standard Check', '20' => 'Civil aviation minister', '21' => 'Special Duty', '22' => 'Other', '23' => 'Water Resources Minister', '24' => 'State Minister', '25' => 'NA', '26' => 'Irrigation Minister', '27' => 'PWD', '28' => 'Local Flying', '29' => 'State election commissioner', '30' => 'Chief election commissioner', '31' => 'DM', '32' => 'APC', '33' => 'Director Aviation', '34' => 'Route Check', '35' => 'Check Flight', '36' => 'Flower Dropping', '37' => 'Central Minister', '38' => 'Forest Minister', '39' => 'Principal Secretary irrigation', '40' => 'Secretary', '41' => 'Assembly Speaker', '42' => 'Health Minister', '43' => 'Power minister', '44' => 'Nager Vikas Minister', '45' => 'Election Commissioner', '46' => 'Urban Minister', '47' => 'Ground Run', '48' => 'Instant Release Check', '49' => 'Sports minister' );
        return view('external_flying_logs.statistics', compact('pilots', 'flying_types'));
    }

    public function statisticsPrint($from_date='',$to_date='',$aircraft='',$flying_type='')
    {
        if(empty($from_date)||empty($to_date))
        {
            return 'Please select from date or to date';
        }

        $from = $from_date;
        $to = $to_date;
        $data['from'] = $from;
        $data['to'] = $to;
        $data['flyingType'] = $flying_type;
        $external_flying_logs = ExternalFlyingLog::with(['pilot1'])->where('id', '>', '0');

        if(!empty($from)&&empty($to))
        {
            $external_flying_logs->where('date','>=',date('Y-m-d',strtotime($from)));
        }
        if(empty($from)&&!empty($to))
        {
            $external_flying_logs->where('date','<=',date('Y-m-d',strtotime($to)));
        }
        if(!empty($from)&&!empty($to))
        {
            $external_flying_logs->where(function($q) use($from, $to){
                $q->whereBetween('date', [date('Y-m-d',strtotime($from)), date('Y-m-d',strtotime($to))]);
            });
        }

        if(!empty($aircraft))
        {
            $aircrafts = AirCraft::where('aircraft_cateogry', $aircraft)->pluck('aircraft_type');
            $external_flying_logs->whereIn('aircraft_type',$aircrafts)->get();
        }
        if(!empty($_POST['flying_type']))
        {
          $external_flying_logs->where('flying_type', $_POST['flying_type']);
        }

        $data['results'] = $external_flying_logs->orderBy('id', 'desc')->get();

        return view('external_flying_logs.print-statistics', $data)->render();
    }
}
