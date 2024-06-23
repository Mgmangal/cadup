<?php

namespace App\Http\Controllers\ThemeOne;

use App\Models\User;
use App\Models\Master;
use App\Models\AirCraft;
use App\Models\PilotLog;
use App\Models\AaiReport;
use App\Models\FlyingLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FlyingController extends Controller
{
    public function index()
    {
        $pilots = User::where('designation', '1')->where('status', 'active')->get();
        $aircrafts = AirCraft::where('status', 'active')->get();
        $flying_types = Master::where('type', 'flying_type')->where('status', 'active')->get();
        $pilot_roles = Master::where('type', 'pilot_role')->where('status', 'active')->get();
        $passengers = Master::where('type', 'passenger')->where('status', 'active')->get();
        $pilot_role = Master::where('type', 'pilot_role')->where('status', 'active')->get();
        return view('theme-one.flying.index', compact('pilots', 'aircrafts','flying_types','pilot_roles','passengers','pilot_role'));
    }
    public function list(Request $request)
    {
        $column = ['id', 'departure_time', 'aircraft_id', 'fron_sector','departure_time', 'departure_time', 'user_id', 'flying_type', 'id'];
        $users = PilotLog::with(['pilot', 'aircraft'])->where('id', '>', '0');

        if(!empty($_POST['pilot']))
        {
            $pilot=$_POST['pilot'];
            $users->where(function($q) use($pilot){
                $q->where('user_id', $pilot);
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
            $aai_report_exist = AaiReport::where('flying_log_id', $value->id)->first();
            if($aai_report_exist){
                $action  .= '<a href="javascript:void(0);" class="btn btn-success btn-sm m-1">Generated</a>';
            } else {
                $action  .='<a href="'.route('app.flying.generateAaiReport', $value->id).'" class="btn btn-warning btn-sm m-1 text-white">Generate AAI Report</a>';
            }

            $times[] = is_time_defrence($value->departure_time, $value->arrival_time);
            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = is_get_date_format($value->date);
            $sub_array[] = @$value->aircraft->call_sign;
            $sub_array[] = $value->fron_sector.' /<br>'.$value->to_sector;
            $sub_array[] = date('H:i',strtotime($value->departure_time)).' / '. date('H:i',strtotime($value->arrival_time));
            $sub_array[] = is_time_defrence($value->departure_time, $value->arrival_time);
            $sub_array[] = @$value->pilot->salutation . ' ' . @$value->pilot->name;
            $sub_array[] = getMasterName($value->flying_type,'flying_type');
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
    public function myFlying()
    {
        $pilots = User::where('designation', '1')->where('status', 'active')->get();
        $aircrafts = AirCraft::where('status', 'active')->get();
        $flying_types = Master::where('type', 'flying_type')->where('status', 'active')->get();
        $pilot_roles = Master::where('type', 'pilot_role')->where('status', 'active')->get();
        $passengers = Master::where('type', 'passenger')->where('status', 'active')->get();
        $pilot_role = Master::where('type', 'pilot_role')->where('status', 'active')->get();
        return view('theme-one.flying.my-flying', compact('pilots', 'aircrafts','flying_types','pilot_roles','passengers','pilot_role'));
    }
    public function myFlyingList(Request $request)
    {
        $column = ['id', 'departure_time', 'aircraft_id', 'fron_sector','departure_time', 'departure_time', 'user_id', 'flying_type','passenger', 'id'];
        $users = PilotLog::with(['pilot', 'aircraft'])->where('id', '>', '0');

        if(!empty($_POST['pilot']))
        {
            $pilot=$_POST['pilot'];
            $users->where(function($q) use($pilot){
                $q->where('user_id', $pilot);
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
            $aai_report_exist = AaiReport::where('flying_log_id', $value->id)->first();
            if($aai_report_exist){
                $action  .= '<a href="javascript:void(0);" class="btn btn-success btn-sm m-1">Generated</a>';
            } else {
                $action  .='<a href="'.route('app.flying.generateAaiReport', $value->id).'" class="btn btn-warning btn-sm m-1 text-white">Generate AAI Report</a>';
            }

            $times[] = is_time_defrence($value->departure_time, $value->arrival_time);
            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = is_get_date_format($value->date);
            $sub_array[] = @$value->aircraft->call_sign;
            $sub_array[] = $value->fron_sector.' /<br>'.$value->to_sector;
            $sub_array[] = date('H:i',strtotime($value->departure_time)).' / '. date('H:i',strtotime($value->arrival_time));
            $sub_array[] = is_time_defrence($value->departure_time, $value->arrival_time);
            $sub_array[] = @$value->pilot->salutation . ' ' . @$value->pilot->name;
            $sub_array[] = getMasterName($value->flying_type,'flying_type');
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
    public function shortie()
    {
        $pilots = User::where('designation', '1')->where('status', 'active')->get();
        $aircrafts = AirCraft::where('status', 'active')->get();
        $flying_types = Master::where('type', 'flying_type')->where('status', 'active')->get();
        $pilot_roles = Master::where('type', 'pilot_role')->where('status', 'active')->get();
        $passengers = Master::where('type', 'passenger')->where('status', 'active')->get();
        $pilot_role = Master::where('type', 'pilot_role')->where('status', 'active')->get();
        return view('theme-one.flying.shortie', compact('pilots', 'aircrafts','flying_types','pilot_roles','passengers','pilot_role'));
    }
    public function shortieList(Request $request)
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
            $aai_report_exist = AaiReport::where('flying_log_id', $value->id)->first();
            if($aai_report_exist){
                $action  .= '<a href="javascript:void(0);" class="btn btn-success btn-sm m-1">Generated</a>';
            } else {
                $action  .='<a href="'.route('app.flying.generateAaiReport', $value->id).'" class="btn btn-warning btn-sm m-1 text-white">Generate AAI Report</a>';
            }

            $times[] = is_time_defrence($value->departure_time, $value->arrival_time);
            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = is_get_date_format($value->date);
            $sub_array[] = @$value->aircraft->call_sign;
            $sub_array[] = $value->fron_sector.' /<br>'.$value->to_sector;
            $sub_array[] = date('H:i',strtotime($value->departure_time)).' / '. date('H:i',strtotime($value->arrival_time));
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
    public function myShortie()
    {
        $pilots = User::where('designation', '1')->where('status', 'active')->get();
        $aircrafts = AirCraft::where('status', 'active')->get();
        $flying_types = Master::where('type', 'flying_type')->where('status', 'active')->get();
        $pilot_roles = Master::where('type', 'pilot_role')->where('status', 'active')->get();
        $passengers = Master::where('type', 'passenger')->where('status', 'active')->get();
        $pilot_role = Master::where('type', 'pilot_role')->where('status', 'active')->get();
        return view('theme-one.flying.my-shortie', compact('pilots', 'aircrafts','flying_types','pilot_roles','passengers','pilot_role'));
    }
    public function myShortieList(Request $request)
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
            $aai_report_exist = AaiReport::where('flying_log_id', $value->id)->first();
            if($aai_report_exist){
                $action  .= '<a href="javascript:void(0);" class="btn btn-success btn-sm m-1">Generated</a>';
            } else {
                $action  .='<a href="'.route('app.flying.generateAaiReport', $value->id).'" class="btn btn-warning btn-sm m-1 text-white">Generate AAI Report</a>';
            }

            $times[] = is_time_defrence($value->departure_time, $value->arrival_time);
            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = is_get_date_format($value->date);
            $sub_array[] = @$value->aircraft->call_sign;
            $sub_array[] = $value->fron_sector.' /<br>'.$value->to_sector;
            $sub_array[] = date('H:i',strtotime($value->departure_time)).' / '. date('H:i',strtotime($value->arrival_time));
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


}
