<?php
namespace App\Http\Controllers\ThemeOne;


use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Master;
use App\Models\AirCraft;
use App\Models\PilotLicense;
use App\Models\PilotMedical;
use Illuminate\Http\Request;
use App\Models\PilotTraining;
use App\Models\ExternalFlyingLog;
use App\Models\PilotQualification;
use App\Models\PilotGroundTraining;

class ReportController extends Controller
{
    public function pilotGroundTraining()
    {
        $pilots = User::where('designation', '1')->where('status', 'active')->get();
        return view('reports.pilot_ground_training', compact('pilots'));
    }

    public function pilotGroundTrainingPrint($date = '', $aircraft = '')
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

        return view('reports.pilot_ground_training_print', $data)->render();
    }

    public function pilotFlyingCurrency()
    {
        $pilots = User::where('designation', '1')->where('status', 'active')->get();
        if(getUserType()=='user')
        {
            return view('theme-one.reports.pilot_flying_currency', compact('pilots'));
        }else{
            return view('reports.pilot_flying_currency', compact('pilots'));
        }
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

    public function printAircraftWiseSummary($from='',$to='')
    {
        if(empty($from)||empty($to))
        {
            return 'Please select from date or to date';
        }

        $data['from'] = $from;
        $data['to'] = $to;
        $data['aircrafts'] = AirCraft::orderBy('aircraft_cateogry','asc')->get();
        $data['months'] = get_month_list($from, $to);
        $data['fixed_wing_pilots'] = getCategoriesPilots('Fixed Wing');
        $data['rotor_wing_pilots'] = getCategoriesPilots('Rotor Wing');
        return view('reports.print-aircraft-wise-summary',$data);
    }

    public function vipRecency()
    {
        $pilots = User::where('designation', '1')->where('status', 'active')->get();
        return view('reports.vip_recency', compact('pilots'));
    }
    public function printVipRecency($date = '',$aircraft_type='')
    {
        $data['date'] = $date;
        $data['ac_types']=Master::where('type', 'aircraft_type')->where('more_data', $aircraft_type)->where('status', 'active')->where('is_delete','0')->get();
        $data['aircraft_type']=$aircraft_type;
        return view('reports.vip_recency_print', $data);
    }
}
