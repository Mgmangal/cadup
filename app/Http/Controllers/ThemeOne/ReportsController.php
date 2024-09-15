<?php

namespace App\Http\Controllers\ThemeOne;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Master;
use App\Models\AirCraft;
use App\Models\PilotLicense;
use App\Models\PilotMedical;
use App\Models\PilotTraining;
use App\Models\ExternalFlyingLog;
use App\Models\PilotQualification;
use App\Models\PilotGroundTraining;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;

class ReportsController extends Controller
{
    public function externalFlying()
    {
        $sub_title = "External Flying";
        $pilots = User::where('designation', '1')->where('status', 'active')->get();
        // $aircrafts = AirCraft::where('status', 'active')->get();
        $flying_types = array( '1' => 'Agriculture minister', '2' => 'Cabinet Minister', '3' => 'CM', '4' => 'CS', '5' => 'DGP', '6' => 'Dy. CM', '7' => 'Governor', '8' => 'Positioning', '9' => 'PPC', '10' => 'RTB', '11' => 'Speaker UP', '12' => 'VIP', '13' => 'VVIP', '14' => 'Home Secretary', '15' => 'Personal Secretary Home', '16' => 'AG', '17' => 'Maintenance', '18' => 'ADG', '19' => 'Standard Check', '20' => 'Civil aviation minister', '21' => 'Special Duty', '22' => 'Other', '23' => 'Water Resources Minister', '24' => 'State Minister', '25' => 'NA', '26' => 'Irrigation Minister', '27' => 'PWD', '28' => 'Local Flying', '29' => 'State election commissioner', '30' => 'Chief election commissioner', '31' => 'DM', '32' => 'APC', '33' => 'Director Aviation', '34' => 'Route Check', '35' => 'Check Flight', '36' => 'Flower Dropping', '37' => 'Central Minister', '38' => 'Forest Minister', '39' => 'Principal Secretary irrigation', '40' => 'Secretary', '41' => 'Assembly Speaker', '42' => 'Health Minister', '43' => 'Power minister', '44' => 'Nager Vikas Minister', '45' => 'Election Commissioner', '46' => 'Urban Minister', '47' => 'Ground Run', '48' => 'Instant Release Check', '49' => 'Sports minister' );
        return view('theme-one.reports.external_flying', compact('sub_title','pilots', 'flying_types'));
    }
    public function externalFlyingList(Request $request)
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
            $sub_array[] = date('H:i',strtotime($value->departure_time)).' /<br>'. date('H:i',strtotime($value->arrival_time));
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
    public function externalFlyingPrint($from_date='',$to_date='',$aircraft='',$flying_type='')
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
        return view('theme-one.reports.external-flying-print', $data)->render();
    }
    public function pilotFlyingHours()
    {
        $sub_title = "Pilot Flying Hours";
        return view('theme-one.reports.pilot-flying-hours', compact('sub_title'));
    }
    public function pilotFlyingHoursPrint($from='',$to='')
    {
        $data['from'] = $from;
        $data['to'] = $to;
        $data['months'] = get_month_list($from, $to);
        $data['fixed_wing_pilots'] = getCategoriesPilots('Fixed Wing');
        $data['rotor_wing_pilots'] = getCategoriesPilots('Rotor Wing');
        return view('theme-one.reports.pilot-flying-hours-print',$data);
    }
    public function aircraftWiseSummaryPrint($from='',$to='')
    {
        $data['from'] = $from;
        $data['to'] = $to;
        $data['aircrafts'] = AirCraft::orderBy('aircraft_cateogry','asc')->get();
        $data['months'] = get_month_list($from, $to);
        $data['fixed_wing_pilots'] = getCategoriesPilots('Fixed Wing');
        $data['rotor_wing_pilots'] = getCategoriesPilots('Rotor Wing');
        return view('theme-one.reports.aircraft-wise-summary-print',$data);
    }
    public function pilotGroundTraining()
    {
        $sub_title = "Pilot Ground Training";
        $pilots = User::where('designation', '1')->where('status', 'active')->get();
        return view('theme-one.reports.pilot-ground-training', compact('sub_title','pilots'));
    }
    public function pilotGroundTrainingPrint($date = '', $aircraft = '')
    {
        $data['date'] = $date;
        $data['aircraft'] = $aircraft;
        $data['pilots'] = getCategoriesPilots($aircraft);
        return view('theme-one.reports.pilot-ground-training-print', $data)->render();
    }
    public function vipRecency()
    {
        $sub_title = "VIP Recency";
        $pilots = User::where('designation', '1')->where('status', 'active')->get();
        return view('theme-one.reports.vip-recency', compact('sub_title','pilots'));
    }
    public function vipRecencyPrint ($date = '',$aircraft_type='')
    {
        $data['date'] = $date;
        $data['ac_types'] = Master::where('type', 'aircraft_type')->where('more_data', $aircraft_type)->where('status', 'active')->where('is_delete','0')->get();
        $data['aircraft_type']=$aircraft_type;
        return view('theme-one.reports.vip-recency-print', $data);
    }
    public function aaiReports()
    {
        $sub_title = "AAI Report";
        return view('theme-one.reports.aai-reports',compact('sub_title'));
    }

    public function flyingCurrency()
    {
        $sub_title = "Pilot Flying Currency";
        return view('theme-one.reports.pilot-flying-currency',compact('sub_title'));
    }

    public function pilotFlyingCurrencyPrint($date = '', $aircraft = '', $report_type = '')
    {
        if (empty($date)) {
            return 'Please select date';
        }

        if (empty($aircraft)) {
            return 'Please select aircraft type';
        }
        if(empty($report_type)){
            return 'Please select report type';
        }

        $data['date'] = $date;
        $data['aircraft'] = $aircraft;
        $data['report_type'] = $report_type;
        $data['pilots'] = getCategoriesPilots($aircraft);
        return view('reports.pilot_flying_currency_print', $data)->render();
    }

    public function trainingChecksPrint($date = '', $aircraft = '')
    {
        if (empty($date)) {
            return 'Please select date';
        }

        if (empty($aircraft)) {
            return 'Please select aircraft type';
        }
        $data['date'] = $date;
        $data['aircraft'] = $aircraft;
        $data['pilots'] = getCategoriesPilots($aircraft);

        return view('reports.training_and_checks_print', $data)->render();
    }

    public function FlyingTestDetailsPrint($date = '')
    {
        if (empty($date)) {
            return 'Please select a date.';
        }
        $startOfMonth = Carbon::parse($date)->startOfMonth()->toDateString();
        $endOfMonth = Carbon::parse($date)->endOfMonth()->toDateString();
        $licence = PilotLicense::whereBetween('renewed_on', [$startOfMonth, $endOfMonth])->latest()->get();
        $medical = PilotMedical::whereBetween('planned_renewal_date', [$startOfMonth, $endOfMonth])->latest()->get();
        $training = PilotTraining::whereBetween('renewed_on', [$startOfMonth, $endOfMonth])->latest()->get();
        $qualification = PilotQualification::whereBetween('renewed_on', [$startOfMonth, $endOfMonth])->latest()->get();
        $groundTraining = PilotGroundTraining::whereBetween('renewed_on', [$startOfMonth, $endOfMonth])->latest()->get();
        $pilots = User::where('designation', '1')->where('status', 'active')->get();

        $data['date'] = $date;
        $data['pilots'] = $pilots;
        $data['startOfMonth'] = $startOfMonth;
        $data['endOfMonth'] = $endOfMonth;
        return view('reports.flying_test_done_print', $data);
    }
}
