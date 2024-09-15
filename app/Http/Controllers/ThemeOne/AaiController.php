<?php

namespace App\Http\Controllers\ThemeOne;

use Illuminate\Http\Request;
use App\Models\AirCraft;
use App\Models\FlyingLog;
use App\Models\AaiReport;
use App\Models\User;
use App\Models\Master;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
class AaiController extends Controller
{
    public function flyingLogs()
    {
        $pilots = User::where('designation', '1')->where('status', 'active')->get();
        $aircrafts = AirCraft::where('status', 'active')->get();
        $flying_types = Master::where('type', 'flying_type')->where('status', 'active')->get();
        $pilot_roles = Master::where('type', 'pilot_role')->where('status', 'active')->get();
        $passengers = Master::where('type', 'passenger')->where('status', 'active')->get();
        $pilot_role = Master::where('type', 'pilot_role')->where('status', 'active')->get();
        return view('theme-one.flying_logs.index', compact('pilots', 'aircrafts','flying_types','pilot_roles','passengers','pilot_role'));
    }

    public function flyingLogList(Request $request)
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
            $aai_report_exist = AaiReport::where('flying_log_id', $value->id)->first();
            if($aai_report_exist){
                $action  .= '<a href="javascript:void(0);" class="btn btn-success btn-sm m-1">Generated</a>';
                $action .= '<a href="'.route('user.aai_report.edit', $aai_report_exist->id).'" class="btn btn-primary btn-sm m-1">Edit</a>';
                $action .= '<a href="javascript:void(0);" onclick="deleted(`' . route('user.aai_report.destroy', $aai_report_exist->id).'`);" class="btn btn-danger btn-sm m-1">Delete</a>';
            } else {
                $action  .='<a href="'.route('user.aai_report.generate', $value->id).'" class="btn btn-warning btn-sm m-1 text-white">Generate AAI Report</a>';
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

    public function generate($id)
    {
        $data = FlyingLog::with('aircraft')->find($id);
        return view('theme-one.aai_reports.generate', compact('data'));
    }

    public function store(Request $request)
    {
        $edit_id = $request->edit_id;
        if ($edit_id) {
            $model = AaiReport::find($edit_id);
            $message = 'AAI Report Updated Successfully';
        } else {
            $model = new AaiReport();
            $message = 'AAI Report Generated Successfully';
        }
        $model->flying_log_id = $request->flying_log_id;
        $model->d_i_ind = $request->d_i_ind;
        $model->rcs_ind = $request->rcs_ind;
        $model->booking_date = $request->booking_date;
        $model->modification_date = $request->modification_date;
        $model->original_pnr = $request->original_pnr;
        $model->parent_pnr = $request->parent_pnr;
        $model->tail_number = $request->tail_number;
        $model->departure_date = $request->departure_date;
        $model->departure_date_utc = $request->departure_date_utc;
        $model->departure_date_local = $request->departure_date_local;
        $model->flight_number = $request->flight_number;
        $model->pnr_actual_departure_station = $request->pnr_actual_departure_station;
        $model->departure_station = $request->departure_station;
        $model->arrival_station = $request->arrival_station;
        $model->final_station = $request->final_station;
        $model->nationality = $request->nationality;
        $model->carrier_code = $request->carrier_code;
        $model->total_pax = $request->total_pax;
        $model->adult_count = $request->adult_count;
        $model->child_count = $request->child_count;
        $model->infant_count = $request->infant_count;
        $model->sky_marshall_count = $request->sky_marshall_count;
        $model->embarkation_connection_status = $request->embarkation_connection_status;
        $model->disembarkation_connection_status = $request->disembarkation_connection_status;
        $model->flight_status = $request->flight_status;
        $model->pnr_status = $request->pnr_status;
        $model->save();
        return response()->json(['success' => true, 'message' => $message]);
    }

    public function list(Request $request)
    {
        $column = ['id', 'd_i_ind', 'rcs_ind','booking_date', 'modification_date', 'original_pnr', 'parent_pnr','tail_number', 'departure_date', 'departure_date_utc', 'departure_date_local', 'flight_number','pnr_actual_departure_station', 'departure_station', 'arrival_station', 'final_station','nationality', 'carrier_code','total_pax', 'adult_count', 'child_count', 'infant_count', 'sky_marshall_count','embarkation_connection_status', 'disembarkation_connection_status', 'flight_status', 'pnr_status','id'];
        $users = AaiReport::where('id', '>', '0');

        $total_row = $users->get()->count();

        if (!empty($_POST['from_date']) && empty($_POST['to_date'])) {
            $from = $_POST['from_date'];
            $users->whereRaw('DATE(departure_date) >= ?', [date('Y-m-d', strtotime($from))]);
        }

        if (empty($_POST['from_date']) && !empty($_POST['to_date'])) {
            $to = $_POST['to_date'];
            $users->whereRaw('DATE(departure_date) <= ?', [date('Y-m-d', strtotime($to))]);
        }

        if (!empty($_POST['from_date']) && !empty($_POST['to_date'])) {
            $from = $_POST['from_date'];
            $to = $_POST['to_date'];
            $users->where(function($q) use ($from, $to) {
                $q->whereBetween(DB::raw('DATE(departure_date)'), [
                    date('Y-m-d', strtotime($from)),
                    date('Y-m-d', strtotime($to))
                ]);
            });
        }

        if(!empty($_POST['from_sector']))
        {
          $users->where('departure_station',$_POST['from_sector']);
        }
        if (isset($_POST['search'])&&!empty($_POST['search']['value'])) {
            $users->where('departure_date', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $users->orWhere('departure_station', 'LIKE', '%' . $_POST['search']['value'] . '%');
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
            // $action = '<a href="'.route('app.aai_report.edit', $value->id).'" class="btn btn-primary btn-sm m-1">Edit</a>';
            // $action = '<a href="javascript:void(0);" onclick="deleted(`' . route('app.aai_report.destroy', $value->id).'`);" class="btn btn-danger btn-sm m-1">Delete</a>';

            $sub_array = array();
            $sub_array[] = ++$key;
            // $sub_array[] = $value->flying_log_id;
            $sub_array[] = $value->d_i_ind;
            $sub_array[] = $value->rcs_ind;
            $sub_array[] = $value->booking_date ? is_get_date_time_format($value->booking_date) : '';
            $sub_array[] = is_get_date_time_format($value->modification_date);
            $sub_array[] = $value->original_pnr;
            $sub_array[] = $value->parent_pnr;
            $sub_array[] = $value->tail_number;
            $sub_array[] = $value->departure_date ? is_get_date_time_format($value->departure_date) : '';
            $sub_array[] = $value->departure_date_utc ? is_get_date_time_format($value->departure_date_utc) : '';
            $sub_array[] = $value->departure_date_local ? is_get_date_time_format($value->departure_date_local) : '';
            $sub_array[] = $value->flight_number;
            $sub_array[] = $value->pnr_actual_departure_station;
            $sub_array[] = $value->departure_station;
            $sub_array[] = $value->arrival_station;
            $sub_array[] = $value->final_station;
            $sub_array[] = $value->nationality;
            $sub_array[] = $value->carrier_code;
            $sub_array[] = $value->total_pax;
            $sub_array[] = $value->adult_count;
            $sub_array[] = $value->child_count;
            $sub_array[] = $value->infant_count;
            $sub_array[] = $value->sky_marshall_count;
            $sub_array[] = $value->embarkation_connection_status;
            $sub_array[] = $value->disembarkation_connection_status;
            $sub_array[] = $value->flight_status;
            $sub_array[] = $value->pnr_status;
            // $sub_array[] =  $action;
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

    public function edit($id)
    {
        $data = AaiReport::find($id);
        // return $data;
        return view('theme-one.aai_reports.edit', compact('data'));
    }

    public function destroy($id)
    {
        $data = AaiReport::find($id);
        $data->delete();
        return response()->json(['success' => true, 'message' => 'Data Deleted successfully.']);
    }

    public function bulkStore(Request $request)
    {
        $logs = FlyingLog::with(['pilot1', 'pilot2', 'aircraft'])->where('id', '>', '0');
        if(!empty($_POST['pilot']))
        {
            $pilot=$_POST['pilot'];
            $logs->where(function($q) use($pilot){
                $q->where('pilot1_id', $pilot);
                $q->orWhere('pilot2_id', $pilot);
            });
        }
        if(!empty($_POST['from_date'])&&empty($_POST['to_date']))
        {
            $from=$_POST['from_date'];
            $logs->where('date','>=',date('Y-m-d',strtotime($from)));
        }
        if(empty($_POST['from_date'])&&!empty($_POST['to_date']))
        {
            $to=$_POST['to_date'];
            $logs->where('date','<=',date('Y-m-d',strtotime($to)));
        }
        if(!empty($_POST['from_date'])&&!empty($_POST['to_date']))
        {
            $from=$_POST['from_date'];
            $to=$_POST['to_date'];
            $logs->where(function($q) use($from, $to){
                $q->whereBetween('date', [date('Y-m-d',strtotime($from)), date('Y-m-d',strtotime($to))]);
            });
        }
        if(!empty($_POST['from_sector']))
        {
          $logs->where('fron_sector',$_POST['from_sector']);
        }
        if(!empty($_POST['to_sector']))
        {
          $logs->where('to_sector',$_POST['to_sector']);
        }
        if(!empty($_POST['aircraft']))
        {
          $logs->where('aircraft_id',$_POST['aircraft']);
        }
        if(!empty($_POST['flying_type']))
        {
          $logs->where('flying_type', $_POST['flying_type']);
        }
        if(!empty($_POST['passenger']))
        {
          $logs->whereJsonContains('passenger',$_POST['passenger']);
        }
        if (isset($_POST['search'])&&!empty($_POST['search']['value'])) {
            $logs->where('comment', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $logs->orWhere('fron_sector', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $logs->orWhere('to_sector', 'LIKE', '%' . $_POST['search']['value'] . '%');
        }
        $flyingLogs = $logs->get();
        $aaiReports = AaiReport::all();
        $existingFlyingLogIds = $aaiReports->pluck('flying_log_id')->toArray();

        $flyingLogsWithoutReports = $flyingLogs->reject(function ($flyingLog) use ($existingFlyingLogIds) {
            return in_array($flyingLog->id, $existingFlyingLogIds);
        });

        if ($flyingLogsWithoutReports->isNotEmpty())
        {
            foreach ($flyingLogsWithoutReports as $flyingLog)
            {
                $model = new AaiReport();
                $model->flying_log_id = $flyingLog->id;
                $model->d_i_ind = 'D';
                $model->rcs_ind = 'Non RCS';
                $model->booking_date = $flyingLog->departure_time;
                $model->modification_date = $flyingLog->departure_time;
                $model->original_pnr = date('dmY', strtotime($flyingLog->departure_time)) . str_replace('-', '', $flyingLog->aircraft->call_sign) . date('Hi', strtotime($flyingLog->arrival_time));
                $model->parent_pnr = date('dmY', strtotime($flyingLog->departure_time)) . str_replace('-', '', $flyingLog->aircraft->call_sign) . date('Hi', strtotime($flyingLog->arrival_time));
                $model->tail_number = $flyingLog->aircraft->call_sign;
                $model->departure_date = $flyingLog->departure_time;
                $model->departure_date_utc = $flyingLog->departure_time;
                $model->departure_date_local = $flyingLog->departure_time;
                $model->flight_number = $flyingLog->aircraft->call_sign;
                $model->pnr_actual_departure_station = $flyingLog->fron_sector;
                $model->departure_station = $flyingLog->fron_sector;
                $model->arrival_station = $flyingLog->to_sector;
                $model->final_station = $flyingLog->to_sector;
                $model->nationality = 'India';
                $model->carrier_code = 'FBO';
                $model->total_pax = '0';
                $model->adult_count = '0';
                $model->child_count = '0';
                $model->infant_count = '0';
                $model->sky_marshall_count = '0';
                $model->embarkation_connection_status = 'No Connection';
                $model->disembarkation_connection_status = 'No Connection';
                $model->flight_status = 'Close';
                $model->pnr_status = 'Borded';
                $model->save();
            }
        }
        return response()->json(['success' => true, 'message' => 'Reports Generated Successfully']);
    }


}
