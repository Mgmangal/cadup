<?php

// use App\Models\Role;
use Carbon\Carbon;
use App\Models\Users;
use App\Models\Setting;
use App\Models\FlyingLog;
use App\Models\NoneFlyingLog;
use App\Models\AirCraft;
use App\Models\Master;
use App\Models\MasterAssign;
use App\Models\User;
use App\Models\Leave;
use App\Models\PilotLicense;
use App\Models\PilotMedical;
use App\Models\PilotTraining;
use App\Models\PilotQualification;
use App\Models\PilotGroundTraining;
use App\Models\ExternalFlyingLog;
use App\Models\PilotFlyingLog;
use App\Models\State;
use App\Models\PilotViolation;
use App\Models\ReceiveBill;
use App\Models\ReceiptBillFlyingLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

if(!function_exists('getUserType'))
{
    function getUserType()
    {
        $user= Auth::user()->user_type;
        return $user;
    }
}
if(!function_exists('getEmpId'))
{
    function getEmpId($user_id)
    {
        $user=User::find($user_id);
        return !empty($user)?$user->emp_id:'';
    }
}
if(!function_exists('getEmpName'))
{
    function getEmpName($user_id)
    {
        $user=User::find($user_id);
        return !empty($user)?$user->name:'';
    }
}
if(!function_exists('getAirCraft'))
{
    function getAirCraft($user_id)
    {
        $user=AirCraft::find($user_id);
        return !empty($user)?$user:'';
    }
}
if(!function_exists('getEmpFullName'))
{
    function getEmpFullName($user_id)
    {
        $user=User::find($user_id);
        return !empty($user)?($user->salutation.' '.$user->name):'';
    }
}
if(!function_exists('changeSpaceInUnderscore'))
{
    function changeSpaceInUnderscore($value)
    {
        // Replaces all spaces with hyphens.
        $string = str_replace(' ', '-', $value);
        // Removes special chars.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);
        $string=str_replace(' ', '_', $string);
        // Replaces multiple hyphens with single one.
        $string = preg_replace('/-+/', '-', $string);
        return strtolower($string);
    }
}
if(!function_exists('getMasterName'))
{
    function getMasterName($id)
    {
        $data=Master::find($id);
        return !empty($data)?$data->name:'';
    }
}
if(!function_exists('getMaster'))
{
    function getMaster($id)
    {
        $data=Master::find($id);
        return !empty($data)?$data:'';
    }
}
if (!function_exists('getStateName')) {
    function getStateName($id)
    {
        $data = State::find($id);
        return !empty($data) ? $data->name : '';
    }
}

if (!function_exists('is_setting')) {
    function is_setting($field)
    {
        return Setting::select($field)->first()->toArray()[$field];
    }
}
if (!function_exists('minutes')) {
    function minutes($time){
        $time = explode(':', $time);
        // return ($time[0]*60) + ($time[1]) + ($time[2]/60);
        return ($time[0]*60) + ($time[1]);
    }
}

if (!function_exists('getRoleNode')) {
    function getRoleNode($user, $roles)
    {
        $html = '';
        // foreach ($roles as $key => $role) {
        //     $html .= '<div class="row p-2" style="margin-left: 10px;">
        //                     <div class="col-md-12 p-1 border">
        //                         <div class="form-check">
        //                             <input class="form-check-input" type="checkbox" value="' . $role->id . '" id="role' . $role->id . '" name="roles[]" ' . ($user->hasRole($role->slug) ? 'checked' : '') . '/>
        //                             <label class="form-check-label" for="role' . $role->id . '">' . $role->name . '</label>
        //                         </div>
        //                     </div>';
        //     $checChild = Role::where('parent_id', $role->id)->get();
        //     if (count($checChild) > 0) {
        //         $html .= getRoleNode($user, $checChild);
        //     }
        //     $html .= '</div>';
        // }
        return $html;
    }
}

if (!function_exists('encrypter')) {
    function encrypter($action, $string) {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = '';
        $secret_iv = '';

        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if ($action == 'encrypt') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }
}


if (!function_exists('is_image')) {
    function is_image($path)
    {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif']) && file_exists($path)) {
            return asset($path);
        }
        return asset('images/no-image.png');
    }
}

if (!function_exists('is_set_date_format')) {
    function is_set_date_format($date)
    {
        return \Carbon\Carbon::parse($date)->format('Y-m-d');
    }
}

if (!function_exists('is_get_date_format')) {
    function is_get_date_format($date)
    {
        return \Carbon\Carbon::parse($date)->format('d-m-Y');
    }
}

if (!function_exists('is_set_date_time_format')) {
    function is_set_date_time_format($date)
    {
        return \Carbon\Carbon::parse($date)->format('Y-m-d H:i:s');
    }
}

if (!function_exists('is_get_date_time_format')) {
    function is_get_date_time_format($date)
    {
        return \Carbon\Carbon::parse($date)->format('d-m-Y H:i');
    }
}

if (!function_exists('is_time_defrence')) {
    function is_time_defrence($date_start, $date_end)
    {
        // Assuming you have two date strings
        $dateString1 = $date_start;
        $dateString2 = $date_end;
        
        // Create Carbon instances from the date strings
        $date1 = \Carbon\Carbon::parse($dateString1);
        $date2 = \Carbon\Carbon::parse($dateString2);

        // Calculate the time difference
        return $timeDifference = $date1->diff($date2)->format('%H:%I');
       
    }
}

if (!function_exists('is_time_defrence_in_mintes')) {
    function is_time_defrence_in_mintes($date_start, $date_end)
    {
        // Assuming you have two date strings
        $start = strtotime($date_start);
        $end = strtotime($date_end);
        $mins = ($end - $start) / 60;
        return  $mins;
    }
}

if (!function_exists('colculate_days_hours_mints')) {
    function colculate_days_hours_mints($mins)
    {
        $hours = str_pad(floor($mins / 60), 2, "0", STR_PAD_LEFT);
        $mins  = str_pad($mins % 60, 2, "0", STR_PAD_LEFT);
        if ((int)$hours > 24) {
            $days = str_pad(floor($hours / 24), 2, "0", STR_PAD_LEFT);
            $hours = str_pad($hours % 24, 2, "0", STR_PAD_LEFT);
        }
        if (isset($days)) {
            $days = $days . "D, ";
        } else {
            $days = '';
        }
        return $days . $hours . ":" . $mins . "";
    }
}

if (!function_exists('get_Rest')) {
    function get_Rest($user_id, $date,$id='')
    {
        
        $curent_data = FlyingLog::where(function ($q) use ($user_id) {
            $q->where('pilot1_id', $user_id)->orWhere('pilot2_id', $user_id);
        })->where('date','=', $date)->orderBy('id', 'asc')->first();
        

        $last_data = FlyingLog::where(function ($q) use ($user_id) {
            $q->where('pilot1_id', $user_id)->orWhere('pilot2_id', $user_id);
        })->where('date', '<', $date)->orderBy('arrival_time', 'desc')->first();
 
        if (!empty($last_data)) {
            
            $arrival_time = date('Y-m-d H:i', strtotime($last_data->arrival_time . ' + 15 minute'));
            // $arrival_time = date('Y-m-d H:i', strtotime($last_data->arrival_time ));
            $departure_time = date('Y-m-d H:i', strtotime($curent_data->departure_time . ' - 45 minute'));
            // $departure_time = date('Y-m-d H:i', strtotime($curent_data->departure_time ));
            // return $arrival_time.'=='.$departure_time.'====>'.colculate_days_hours_mints(is_time_defrence_in_mintes($arrival_time,$departure_time));
            //return is_time_defrence_in_mintes($arrival_time, $departure_time);
            return colculate_days_hours_mints(is_time_defrence_in_mintes($arrival_time, $departure_time));
            // return colculate_days_hours_mints(is_time_defrence_in_mintes($departure_time, $arrival_time));
        } else {
            return '';
        }
    }
}

if (!function_exists('get_do_tt')) {
    function get_do_tt($user_id, $date)
    {
        $data = FlyingLog::where(function ($q) use ($user_id) {
            $q->where('pilot1_id', $user_id)->orWhere('pilot2_id', $user_id);
        })->where('date', $date)->orderBy('id', 'asc')->first();
        return date('H:i', strtotime("-75 minutes", strtotime($data->departure_time)));
    }
}

if (!function_exists('get_STD_ATD_STA_ATA')) {
    function get_STD_ATD_STA_ATA($user_id, $date)
    {
        $data = FlyingLog::where(function ($q) use ($user_id) {
            $q->where('pilot1_id', $user_id)->orWhere('pilot2_id', $user_id);
        })->where('date', $date)->get();
        $html = '<table class="table table-bordered p-0 m-0">';
        foreach ($data as $key => $value) {
            $html .= '<tr>';
            $html .= '<td>' . date('H:i', strtotime($value->departure_time)) . '</td>';
            $html .= '<td>' . date('H:i', strtotime($value->departure_time)) . '</td>';
            $html .= '<td>' . date('H:i', strtotime($value->arrival_time)) . '</td>';
            $html .= '<td>' . date('H:i', strtotime($value->arrival_time)) . '</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';
        return $html;
    }
}

if (!function_exists('get_on_duty')) {
    function get_on_duty($user_id, $date)
    {
        $data = FlyingLog::where(function ($q) use ($user_id) {
            $q->where('pilot1_id', $user_id)->orWhere('pilot2_id', $user_id);
        })->where('date', $date)->orderBy('id', 'asc')->first();
        return date('H:i', strtotime("-45 minutes", strtotime($data->departure_time)));
    }
}

if (!function_exists('get_off_duty')) {
    function get_off_duty($user_id, $date)
    {
        $data = FlyingLog::where(function ($q) use ($user_id) {
            $q->where('pilot1_id', $user_id)->orWhere('pilot2_id', $user_id);
        })->where('date', $date)->orderBy('id', 'desc')->first();
        return date('H:i', strtotime("+15 minutes", strtotime($data->arrival_time)));
    }
}

if (!function_exists('get_DP_Sector')) {
    function get_DP_Sector($user_id, $date)
    {
        $date_end = get_off_duty($user_id, $date);
        $date_start = get_on_duty($user_id, $date);
        return is_time_defrence($date_start, $date_end);
    }
}

if (!function_exists('get_DP_24Hours')) {
    function get_DP_24Hours($user_id, $date)
    {
        $data = FlyingLog::where(function ($q) use ($user_id) {
            $q->where('pilot1_id', $user_id)->orWhere('pilot2_id', $user_id);
        })->where('date', $date)->orderBy('id', 'desc')->first();
        $ft = date('Y-m-d H:i:s', strtotime("+15 minutes", strtotime($data->arrival_time)));

        // $departure_time=$data->arrival_time;
        $ftb = \Carbon\Carbon::parse($ft)->subDay()->toDateTimeString();
        $datas = FlyingLog::where(function ($q) use ($user_id) {
            $q->where('pilot1_id', $user_id)->orWhere('pilot2_id', $user_id);
        })->where('arrival_time', '<=', $ft)->where('arrival_time', '>', $ftb)->orderBy('date', 'asc')->get();
        $times = array();
        $diffTime = array();
        $arrival_time = '00:00';
        $last_arrival_time = '00:00';
        $total = $datas->count();
        $i = 1;
        foreach ($datas as $k => $datas) {
            if ($k > 0) {
                $departure_time = $datas->departure_time;
                $diffTimes = is_time_defrence_in_mintes($arrival_time, $departure_time);
                // if($diffTimes<=720)
                // {
                //     $times[]=$diffTimes>=180?date('H:i',strtotime($diffTimes)/2):is_time_defrence($arrival_time,$departure_time);
                // }
                if ($diffTimes > 0 && $diffTimes <= 180) {
                    $times[] = is_time_defrence($arrival_time, $departure_time);
                }
            }
            $arrival_time = $datas->arrival_time;
            if ($total > $i) {
                $last_arrival_time = $datas->arrival_time;
            }
            if ($total == $i) {
                $departure_time = $datas->departure_time;
                $diffTimes = is_time_defrence_in_mintes($departure_time, $last_arrival_time);
                if ($diffTimes > 0 && $diffTimes <= 180) {
                    $times[] = is_time_defrence($departure_time, $last_arrival_time);
                }
            }

            $firstRow = FlyingLog::where(function ($q) use ($user_id) {
                $q->where('pilot1_id', $user_id)->orWhere('pilot2_id', $user_id);
            })->where('date', $datas->date)->orderBy('id', 'asc')->first();
            if ($firstRow->id == $datas->id) {

                $times[] = '00:45';
            }


            $lastRow = FlyingLog::where(function ($q) use ($user_id) {
                $q->where('pilot1_id', $user_id)->orWhere('pilot2_id', $user_id);
            })->where('date', $datas->date)->orderBy('id', 'desc')->first();
            if ($lastRow->id == $datas->id) {

                $times[] = '00:15';
            }

            $date_start = $datas->departure_time;
            $date_end = $datas->arrival_time;
            // if($date_start < $departureTime)
            // {
            //     $diffTimes[]=is_time_defrence($date_start,$departureTime);
            // }
            $times[] = is_time_defrence($date_start, $date_end);
            $i++;
        }
        //   print_r($times);die;
        $totalTime = AddPlayTime($times);

        // foreach($diffTimes as  $diffTime)
        // {
        //     $totalTime= timeDiff($diffTime,$totalTime); 
        // }
        // print_r($times);
        return $totalTime;
    }
}

if (!function_exists('timeDiff')) {
    function timeDiff($firstTime, $lastTime)
    {
        $firstTime = strtotime($firstTime);
        $lastTime = strtotime($lastTime);
        $timeDiff = $lastTime - $firstTime;
        return date('H:i', $timeDiff);
    }
}

if (!function_exists('AddPlayTime')) {
    function AddPlayTime($times)
    {
        $minutes = 0; //declare minutes either it gives Notice: Undefined variable
        // loop throught all the times
        foreach ($times as $time) {
            list($hour, $minute) = explode(':', $time);
            $minutes += $hour * 60;
            $minutes += $minute;
        }

        $hours = floor($minutes / 60);
        $minutes -= $hours * 60;

        // returns the time already formatted
        return sprintf('%02d:%02d', $hours, $minutes);
    }
}

if (!function_exists('get_FDP_Sector')) {
    function get_FDP_Sector($user_id, $date)
    {
        $date_end = get_off_duty($user_id, $date);
        $date_start = get_on_duty($user_id, $date);
        $lastTime = is_time_defrence($date_start, $date_end);
        return timeDiff('00:15', $lastTime);
    }
}

if (!function_exists('get_FDP_24Hours')) {
    function get_FDP_24Hours($user_id, $date)
    {
        $lastTime = get_DP_24Hours($user_id, $date);
        return timeDiff('00:15', $lastTime);
    }
}

if (!function_exists('get_FT_Sector')) {
    function get_FT_Sector($user_id, $date)
    {
        $datas = FlyingLog::where(function ($q) use ($user_id) {
            $q->where('pilot1_id', $user_id)->orWhere('pilot2_id', $user_id);
        })->where('date', $date)->orderBy('id', 'desc')->get();
        $times = array();
        foreach ($datas as $k => $datas) {
            $date_start = $datas->departure_time;
            $date_end = $datas->arrival_time;
            $times[] = is_time_defrence($date_start, $date_end);
        }
        $totalTime = AddPlayTime($times);
        return $totalTime;
    }
}

if (!function_exists('get_FT_24Hours')) {
    function get_FT_24Hours($user_id, $date)
    {
        $data = FlyingLog::where(function ($q) use ($user_id) {
            $q->where('pilot1_id', $user_id)->orWhere('pilot2_id', $user_id);
        })->where('date', $date)->orderBy('id', 'desc')->first();
        // echo $departure_time=date('Y-m-d H:i:s', strtotime("-24 hours",strtotime($data->arrival_time)));
        $departure_time = $data->arrival_time;
        $departureTime = \Carbon\Carbon::parse($departure_time)->subDay()->toDateTimeString();
        $datas = FlyingLog::where(function ($q) use ($user_id) {
            $q->where('pilot1_id', $user_id)->orWhere('pilot2_id', $user_id);
        })->where('arrival_time', '<=', $departure_time)->where('arrival_time', '>', $departureTime)->get();
        $times = array();
        foreach ($datas as $k => $datas) {
            $date_start = $datas->departure_time;
            $date_end = $datas->arrival_time;
            $times[] = is_time_defrence($date_start, $date_end);
        }
        $totalTime = AddPlayTime($times);
        return $totalTime;
    }
}

if (!function_exists('get_FT_IN_Days')) {
    function get_FT_IN_Days($user_id, $date, $day)
    {
        $data = FlyingLog::where(function ($q) use ($user_id) {
            $q->where('pilot1_id', $user_id)->orWhere('pilot2_id', $user_id);
        })->where('date', $date)->orderBy('id', 'desc')->first();
        // echo $departure_time=date('Y-m-d H:i:s', strtotime("-24 hours",strtotime($data->arrival_time)));
        $departure_time = $data->arrival_time;
        $departureTime = \Carbon\Carbon::parse($departure_time)->subDay($day)->toDateTimeString();
        $datas = FlyingLog::where(function ($q) use ($user_id) {
            $q->where('pilot1_id', $user_id)->orWhere('pilot2_id', $user_id);
        })->where('arrival_time', '<=', $departure_time)->where('arrival_time', '>', $departureTime)->get();
        $times = array();
        foreach ($datas as $k => $datas) {
            $date_start = $datas->departure_time;
            $date_end = $datas->arrival_time;
            $times[] = is_time_defrence($date_start, $date_end);
        }
        $totalTime = AddPlayTime($times);
        return $totalTime;
    }
}

if (!function_exists('get_Landings_Sector')) {
    function get_Landings_Sector($user_id, $date)
    {
        $data = FlyingLog::where(function ($q) use ($user_id) {
            $q->where('pilot1_id', $user_id)->orWhere('pilot2_id', $user_id);
        })->where('date', $date)->get();
        return $data->count();
    }
}

if (!function_exists('get_Landings_24Hours')) {
    function get_Landings_24Hours($user_id, $date)
    {
        $data = FlyingLog::where(function ($q) use ($user_id) {
            $q->where('pilot1_id', $user_id)->orWhere('pilot2_id', $user_id);
        })->where('date', $date)->orderBy('id', 'desc')->first();
        // echo $departure_time=date('Y-m-d H:i:s', strtotime("-24 hours",strtotime($data->arrival_time)));
        $departure_time = $data->arrival_time;
        $departureTime = \Carbon\Carbon::parse($departure_time)->subDay()->toDateTimeString();
        $datas = FlyingLog::where(function ($q) use ($user_id) {
            $q->where('pilot1_id', $user_id)->orWhere('pilot2_id', $user_id);
        })->where('arrival_time', '<=', $departure_time)->where('arrival_time', '>', $departureTime)->get();

        return $datas->count();
    }
}

if (!function_exists('get_Break')) {
    function get_Break($user_id, $date)
    {
        $data = FlyingLog::where(function ($q) use ($user_id) {
            $q->where('pilot1_id', $user_id)->orWhere('pilot2_id', $user_id);
        })->where('date', $date)->orderBy('id', 'desc')->first();
        // echo $departure_time=date('Y-m-d H:i:s', strtotime("-24 hours",strtotime($data->arrival_time)));
        $departure_time = $data->arrival_time;
        $departureTime = \Carbon\Carbon::parse($departure_time)->subDay()->toDateTimeString();
        $datas = FlyingLog::where(function ($q) use ($user_id) {
            $q->where('pilot1_id', $user_id)->orWhere('pilot2_id', $user_id);
        })->where('arrival_time', '<=', $departure_time)->where('arrival_time', '>', $departureTime)->get();
        $times = array();
        $diffTimes = array();
        $arrival_time = '00:00';
        foreach ($datas as $k => $datas) {
            if ($k > 0) {
                $departure_time = $datas->departure_time;
                $diffTimes = is_time_defrence_in_mintes($arrival_time, $departure_time);
                if ($diffTimes > 180 && $diffTimes < 720) {
                    $times[] = date('H:i', strtotime($diffTimes) / 2);
                }
            }
            $arrival_time = $datas->arrival_time;
        }
        $totalTime = AddPlayTime($times);
        return $totalTime;
    }
}

if (!function_exists('checkLicense')) {
    function checkLicense($user_id, $license_id)
    {
        $license = PilotLicense::where('user_id', $user_id)->where('license_id', $license_id)->orderBy('id', 'desc')->first();
        return $license;
    }
}

if (!function_exists('checkTraining')) {
    function checkTraining($user_id, $training_id)
    {
        $training = PilotTraining::where('user_id', $user_id)->where('training_id', $training_id)->orderBy('id', 'desc')->first();
        return $training;
    }
}

if (!function_exists('checkTrainingValidity')) {
    function checkTrainingValidity($user_id, $training_id)
    {
        $training = PilotTraining::where('user_id', $user_id)->where('training_id', $training_id)->latest('id')->first();
        if ($training) {
            $nextDue = Carbon::parse($training->next_due)->format('d-m-Y');
            return $nextDue;
        } else {
            return 'N/A';
        }
    }
}
if (!function_exists('checkTrainingValidityByType')) {
    function checkTrainingValidityByType($user_id, $aircroft_type,$training_id)
    {
        $training = PilotTraining::where('user_id', $user_id)->where('aircroft_type', $aircroft_type)->where('training_id', $training_id)->latest('id')->first();
        if ($training) {
            $nextDue = Carbon::parse($training->next_due)->format('d-m-Y');
            return $nextDue;
        } else {
            return 'N/A';
        }
    }
}

if (!function_exists('checkGroundTraining')) {
    function checkGroundTraining($user_id, $training_id)
    {
        $training = PilotGroundTraining::where('user_id', $user_id)->where('training_id', $training_id)->orderBy('id', 'desc')->first();
        return $training;
    }
}

if (!function_exists('checkGroundTrainingValidity')) {
    function checkGroundTrainingValidity($user_id, $training_id)
    {
        $training = PilotGroundTraining::where('user_id', $user_id)->where('training_id', $training_id)->latest('id')->first();
        if ($training) {
            $nextDue = Carbon::parse($training->next_due)->format('d-m-Y');
            return $nextDue;
        } else {
            return 'N/A';
        }
    }
}

if (!function_exists('checkMedical')) {
    function checkMedical($user_id, $medical_id)
    {
        $medical = PilotMedical::where('user_id', $user_id)->where('medical_id', $medical_id)->orderBy('id', 'desc')->first();
        return $medical;
    }
}

if (!function_exists('checkMedicalValidity')) {
    function checkMedicalValidity($user_id, $medical_id)
    {
        $training = PilotMedical::where('user_id', $user_id)->where('medical_id', $medical_id)->latest('id')->first();
        if ($training) {
            $nextDue = Carbon::parse($training->next_due)->format('d-m-Y');
            return $nextDue;
        } else {
            return 'N/A';
        }
    }
}

if (!function_exists('checkQualification')) {
    function checkQualification($user_id, $qualification_id)
    {
        $qualification = PilotQualification::where('user_id', $user_id)->where('qualification_id', $qualification_id)->orderBy('id', 'desc')->first();
        return $qualification;
    }
}

if (!function_exists('checkCrewLicenses')) {
    function checkCrewLicenses($user_id, $ids, $currentDate = null)
    {
        $currentDate = empty($currentDate) ? date('Y-m-d') : $currentDate;
        $result = '<span class="btn btn-sm btn-success">Valid</span>';

        foreach ($ids as $value) {
            $check = MasterAssign::whereIn('certificate_id', [$value])->pluck('is_mendatory')->toArray();

            if (!empty($check) && in_array('yes', $check)) {
                $data = Master::find($value);

                if ($data && $data->more_data != 'lifetime') {
                    $licenseData = checkLicense($user_id, $value);
                    if(empty($licenseData))
                    {
                        return '<span class="btn btn-sm btn-danger">Lapsed</span>';
                    }
                    elseif ((!empty($licenseData->next_due)&&!empty($currentDate)&& strtotime($licenseData->next_due) < strtotime($currentDate))) {
                        return '<span class="btn btn-sm btn-danger">Lapsed</span>';
                    }
                }
            }
        }
        return $result;
    }
}

if (!function_exists('checkCrewTrainings')) {
    function checkCrewTrainings($user_id, $ids, $currentDate = null)
    {
        $currentDate = empty($currentDate) ? date('Y-m-d') : $currentDate;
        $result = '<span class="btn btn-sm btn-success">Valid</span>';

        foreach ($ids as $value) {
            $check = MasterAssign::whereIn('certificate_id', [$value])->pluck('is_mendatory')->toArray();

            if (!empty($check) && in_array('yes', $check)) {
                $data = Master::find($value);

                if (!empty($data) && $data->more_data != 'lifetime') {
                    $trainingData = checkTraining($user_id, $value);
                
                    if (empty($trainingData) || strtotime($trainingData->next_due) < strtotime($currentDate)) {
                        //return $data->id;
                         return '<span class="btn btn-sm btn-danger">Lapsed</span>';
                    }
                }
            }
        }

        return $result;
    }
}

if (!function_exists('checkCrewMedicals')) {
    function checkCrewMedicals($user_id, $ids, $currentDate = null)
    {
        $currentDate = empty($currentDate) ? date('Y-m-d') : $currentDate;
        $result = '<span class="btn btn-sm btn-success">Valid</span>';
        foreach ($ids as $value) {
            $check = MasterAssign::whereIn('certificate_id', [$value])->pluck('is_mendatory')->toArray();
            if (!empty($check) && in_array('yes', $check)) {
                $data = Master::find($value);
                if ($data && $data->more_data != 'lifetime') {
                    $medicalData = checkMedical($user_id, $value);
                    if (empty($medicalData) || strtotime($medicalData->next_due) < strtotime($currentDate)) {
                        return '<span class="btn btn-sm btn-danger">Lapsed</span>';
                    }
                }
            }
        }
        return $result;
    }

}

if (!function_exists('checkCrewLeaveStatus')) {
    function checkCrewLeaveStatus($user_id, $date)
    {
        $data = Leave::where('user_id', $user_id)->where('from_date', '<=', $date)->where('to_date', '>=', $date)->where('status','approved')->first();
        if (!empty($data)) {
            return '<span class="btn btn-sm btn-danger">On Leave</span>';
        }
        return '<span class="btn btn-sm btn-success">Available</span>';
    }
}

if (!function_exists('getCetificateIds')) {
    function getCetificateIds($user_id, $type)
    {
        $user = User::find($user_id);
        $designation[] = $user->designation ?? '0';
        $section = $user->section ?? [];
        $jobfunction = $user->jobfunction ?? [];
        $a = AirCraft::whereJsonContains('pilots', $user_id)->pluck('id')->toArray();
        $d = array_merge($designation, $section, $jobfunction);
        $license1 = MasterAssign::whereIn('master_id', $d)->where('is_for', 'user')->pluck('certificate_id')->toArray();
        $license2 = MasterAssign::whereIn('master_id', $a)->where('is_for', 'aircraft')->pluck('certificate_id')->toArray();
        $license = array_merge($license1, $license2);

        $licenses = Master::whereIn('id', $license)->where('type', 'certificate')->where('sub_type', 'license')->pluck('id');
        $trainings = Master::whereIn('id', $license)->where('type', 'certificate')->where('sub_type', 'training')->pluck('id');
        $medicals = Master::whereIn('id', $license)->where('type', 'certificate')->where('sub_type', 'medical')->pluck('id');
        if ($type == 'license') {
            return $licenses;
        }
        if ($type == 'training') {
            return $trainings;
        }
        if ($type == 'medical') {
            return $medicals;
        }
    }
}

function getLastSixMonth($user_id, $role)
{
    $date = date('Y-m-d', strtotime('-6 months'));
    $data = FlyingLog::where(function ($q) use ($user_id, $role) {
        $q->where('pilot1_id', $user_id)->where('pilot1_role', $role)->orWhere('pilot2_id', $user_id)->where('pilot2_role', $role);
    })->where('date', '<=', $date)->orderBy('id', 'desc')->get();
    $times = [];
    foreach ($data as $k => $data) {
        $date_start = $data->departure_time;
        $date_end = $data->arrival_time;
        $times[] = is_time_defrence($date_start, $date_end);
    }
    $totalTime = AddPlayTime($times);
    return $totalTime;
}

function getLast30Days($user_id, $role)
{
    $date = date('Y-m-d', strtotime('-30 days'));
    $data = FlyingLog::where(function ($q) use ($user_id, $role) {
        $q->where('pilot1_id', $user_id)->where('pilot1_role', $role)->orWhere('pilot2_id', $user_id)->where('pilot2_role', $role);
    })->where('date', '<=', $date)->orderBy('id', 'desc')->get();
    $times = [];
    foreach ($data as $k => $data) {
        $date_start = $data->departure_time;
        $date_end = $data->arrival_time;
        $times[] = is_time_defrence($date_start, $date_end);
    }
    $totalTime = AddPlayTime($times);
    return $totalTime;
}

if (!function_exists('get_month_list')) {
    function get_month_list($from_date, $to_date)
    {
        $monthList = [];
        $fromDateTime = new DateTime($from_date);
        $toDateTime = new DateTime($to_date);
        $startDate = $fromDateTime->format('Y-m-01');
        $endDate = $toDateTime->format('Y-m-t');

        $currentDate = new DateTime($startDate);
        while ($currentDate->format('Y-m') <= $endDate) {
            $monthList[] = $currentDate->format('F Y');
            $currentDate->modify('+1 month');
        }
        return $monthList;
    }
}

if (!function_exists('get_Pilot_month_Flying_Hours')) {
    function get_Pilot_month_Flying_Hours($user_id, $month)
    {
        $year =date('Y',strtotime($month));
        $month=date('m',strtotime($month));
        $startDate = \Carbon\Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = \Carbon\Carbon::createFromDate($year, $month, 1)->endOfMonth();

        $data = FlyingLog::where(function ($q) use ($user_id) {
            $q->where('pilot1_id', $user_id)->orWhere('pilot2_id', $user_id);
        })->whereBetween('date', [$startDate, $endDate])->orderBy('id', 'desc')->get();

        $times = [];
        foreach ($data as $datas) {
            $date_start = $datas->departure_time;
            $date_end = $datas->arrival_time;
            $times[] = is_time_defrence($date_start, $date_end);
        }

        // $externalData = ExternalFlyingLog::where('pilot1_id', $user_id)
        //     ->whereBetween('date', [$startDate, $endDate])->orderBy('id', 'desc')->get();

        // foreach ($externalData as $datas) {
        //     $date_start = $datas->departure_time;
        //     $date_end = $datas->arrival_time;
        //     $times[] = is_time_defrence($date_start, $date_end);
        // }

        $totalTime = AddPlayTime($times);
        return $totalTime;
    }
}

if (!function_exists('getCategoriesPilots')) {
    function getCategoriesPilots($aircraft_type)
    {
        $fixedWingPilots = AirCraft::where('aircraft_cateogry', $aircraft_type)->pluck('pilots')->toArray();
        $fixedWingPilotsIds = collect($fixedWingPilots)->flatten()->unique()->toArray();
        $pilots = User::where('designation', '1')->whereIn('id', $fixedWingPilotsIds)->where('status', 'active')->get();
        return $pilots;
    }
}

if (!function_exists('getCategoriesAllPilots')) {
    function getCategoriesAllPilots($aircraft_type)
    {
        $fixedWingPilots = AirCraft::where('aircraft_cateogry', $aircraft_type)->pluck('pilots')->toArray();
        $fixedWingPilotsIds = collect($fixedWingPilots)->flatten()->unique()->toArray();
        $pilots = User::where('designation', '1')->whereIn('id', $fixedWingPilotsIds)->get();
        return $pilots;
    }
}

if (!function_exists('getACTypePilots')) {
    function getACTypePilots($ac_type_id)
    {
        $Pilots = AirCraft::where('aircraft_type', $ac_type_id)->where('status', 'active')->where('is_delete','0')->pluck('pilots')->toArray();
        $PilotsIds = collect($Pilots)->flatten()->unique()->toArray();
        $pilots = User::where('designation', '1')->whereIn('id', $PilotsIds)->where('status', 'active')->get();
        return $pilots;
    }
}

if (!function_exists('getAirCraftTypePilots')) {
    function getAirCraftTypePilots($aircraft_type, $type_model)
    {
        if($type_model == 'Bell412'){
            $aircrafts = AirCraft::where('aircraft_cateogry', $aircraft_type)->where('aircraft_type','659')->where('status', 'active')->where('is_delete','0')->get();
        }else if($type_model == 'Agusta109s'){
            $aircrafts = AirCraft::where('aircraft_cateogry', $aircraft_type)->where('aircraft_type', '661')->where('status', 'active')->where('is_delete','0')->get();
        }else if($type_model == 'Hawker'){
            $aircrafts = AirCraft::where('aircraft_cateogry', $aircraft_type)->where('aircraft_type', '658')->where('status', 'active')->where('is_delete','0')->get();
        }else if($type_model == 'B200'){
            $aircrafts = AirCraft::where('aircraft_cateogry', $aircraft_type)->where('aircraft_type', '664')->where('status', 'active')->where('is_delete','0')->get();
        }
        
        
        $pilotIds = [];
        foreach ($aircrafts as $aircraft) {
            if (is_array($aircraft->pilots)) {
                $pilotIds = array_merge($pilotIds, $aircraft->pilots);
            }
        }
        $pilotIds = array_unique($pilotIds);
        $pilots = User::where('designation', '1')->whereIn('id', $pilotIds)->where('status', 'active')->where('is_delete','0')->get();

        return $pilots;
    }
}

if (!function_exists('get_call_Sign')) {
    function get_call_Sign($user_id)
    {
        $latestAircraft = AirCraft::whereJsonContains('pilots', [$user_id])->latest()->first();

        return $latestAircraft ? $latestAircraft->call_sign : '';
    }
}


function getPilotRoleHours($from, $to, $user_id, $role)
{

    $data = FlyingLog::where(function ($q) use ($user_id, $role) {
        $q->where('pilot1_id', $user_id)->where('pilot1_role', $role)->orWhere('pilot2_id', $user_id)->where('pilot2_role', $role);
    })->whereBetween('date', [$from, $to])->orderBy('id', 'desc')->get();

    $times = [];
    foreach ($data as $datas) {
        $date_start = $datas->departure_time;
        $date_end = $datas->arrival_time;
        $times[] = is_time_defrence($date_start, $date_end);
    }
    $totalTime = AddPlayTime($times);
    return $totalTime;
}

function PilotsAircraftWiseFlyingSummary($user_id,$role,$aircroft,$from,$to)
{
    if($role=='579')
    {  //p2
        $data = FlyingLog::where(function ($q) use ($user_id, $role) {
            $q->where('pilot1_id', $user_id)->where('pilot1_role', $role)->orWhere('pilot2_id', $user_id)->where('pilot2_role', $role);
        })->where('aircraft_id',$aircroft)->whereBetween('date', [$from, $to])->orderBy('id', 'desc')->get();
    }else{
         //p1
       $data = FlyingLog::where(function ($q) use ($user_id, $role) {
            $q->where('pilot1_id', $user_id)->where('pilot1_role', $role)->orWhere('pilot2_id', $user_id)->where(function($q) use($role){
                $q->where('pilot2_role', $role)->orWhere('pilot2_role','580')->orWhere('pilot2_role','581')->orWhere('pilot2_role', '582')->orWhere('pilot2_role', '583');
            });
        })->where('aircraft_id',$aircroft)->whereBetween('date', [$from, $to])->orderBy('id', 'desc')->get(); 
    }
    $times = [];
    foreach ($data as $k => $data) {
        $date_start = $data->departure_time;
        $date_end = $data->arrival_time;
        $times[] = is_time_defrence($date_start, $date_end);
    }
    
    $totalTime = AddPlayTime($times);
    return $totalTime;
}

function getBlockTimePilotDateWise($user_id,$date)
{
    $data = FlyingLog::where(function ($q) use ($user_id) {
            $q->where('pilot1_id', $user_id)->where(function($q){
                $q->where('pilot1_role', '578')->orWhere('pilot1_role','580')->orWhere('pilot1_role','581')->orWhere('pilot1_role', '582')->orWhere('pilot1_role', '583');
            })->orWhere('pilot2_id', $user_id)->where(function($q){
                $q->where('pilot2_role', '578')->orWhere('pilot2_role','580')->orWhere('pilot2_role','581')->orWhere('pilot2_role', '582')->orWhere('pilot2_role', '583');
            });
        })->where('date', $date)->orderBy('id', 'desc')->get();
    $times = [];
    foreach ($data as $k => $data) {
        $date_start = $data->departure_time;
        $date_end = $data->arrival_time;
        $times[] = is_time_defrence($date_start, $date_end);
    }
    
    $totalTime = AddPlayTime($times);
    return $totalTime;
}

function getRequiredBlockTimePilotDateWise($user_id,$from,$to)
{
    $data = FlyingLog::where(function ($q) use ($user_id) {
            $q->where('pilot1_id', $user_id)->where(function($q){
                $q->where('pilot1_role', '578')->orWhere('pilot1_role','580')->orWhere('pilot1_role','581')->orWhere('pilot1_role', '582')->orWhere('pilot1_role', '583');
            })->orWhere('pilot2_id', $user_id)->where(function($q){
                $q->where('pilot2_role', '578')->orWhere('pilot2_role','580')->orWhere('pilot2_role','581')->orWhere('pilot2_role', '582')->orWhere('pilot2_role', '583');
            });
        })->whereBetween('date', [$from, $to])->orderBy('id', 'desc')->get();
    $times = [];
    foreach ($data as $k => $data) {
        $date_start = $data->departure_time;
        $date_end = $data->arrival_time;
        $times[] = is_time_defrence($date_start, $date_end);
    }
    //print_r($times);
    $totalTime = AddPlayTime($times);
    return $totalTime;
}


function getVipRecencyLast30Days($user_id, $date)
{
    $role = '578';
    $dateto = date('Y-m-d', strtotime($date));
    $dateLimit = date('Y-m-d', strtotime('-30 days', strtotime($dateto)));

    $data = FlyingLog::where('pilot1_id', $user_id)
        ->where('pilot1_role', $role)
        ->whereBetween('date', [$dateLimit, $dateto])
        ->orderBy('id', 'desc')
        ->get();

    $dateWiseTimes = [];

    foreach ($data as $log) {
        $flightTimeInSeconds = is_time_defrence($log->departure_time, $log->arrival_time);
        // Perform any necessary error handling or validation here
        $dateWiseTimes[$log->date][$log->pilot1_id] = $flightTimeInSeconds;
    }

    return $dateWiseTimes;
}

function getPilotFlyingLog($user_id,$date)
{
   return PilotFlyingLog::where('user_id', $user_id)->orderBy('arrival_time', 'desc')->first();  
}

function getPilotLog($user_id,$date)
{
   return DB::table('pilot_logs')->select('*')->where('user_id',$user_id)->where('arrival_time','<=',date('Y-m-d H:i',strtotime($date)))->orderBy('arrival_time', 'desc')->first();
}

function getPoiletSameDayFirstEntry($user_id,$data)
{
   return FlyingLog::where(function ($q) use ($user_id) {
            $q->where('pilot1_id', $user_id)->orWhere('pilot2_id', $user_id);
        })->whereDate('date', date('Y-m-d',strtotime($data)))->first();
}

function checkFDTLViolation($user_id,$from,$to)
{
    $data = FlyingLog::where(function ($q) use ($user_id) {
            $q->where('pilot1_id', $user_id)->where(function($q){
                $q->where('pilot1_role', '578')->orWhere('pilot1_role','580')->orWhere('pilot1_role','581')->orWhere('pilot1_role', '582')->orWhere('pilot1_role', '583');
            })->orWhere('pilot2_id', $user_id)->where(function($q){
                $q->where('pilot2_role', '578')->orWhere('pilot2_role','580')->orWhere('pilot2_role','581')->orWhere('pilot2_role', '582')->orWhere('pilot2_role', '583');
            });
        })->whereBetween('date', [$from, $to])->orderBy('id', 'desc')->get();
    $times = [];
    foreach ($data as $k => $data) {
        $date_start = $data->departure_time;
        $date_end = $data->arrival_time;
        $times[] = is_time_defrence($date_start, $date_end);
    }
    
    $totalTime = AddPlayTime($times);
    return $totalTime;
}

function calculateNumberOfNights($startDateTime, $endDateTime) {
    // Convert string date times to DateTime objects
    $startDate = new DateTime($startDateTime);
    $endDate = new DateTime($endDateTime);

    // Calculate the interval between the dates
    $interval = $startDate->diff($endDate);

    // Get the number of days and nights
    $days = $interval->days;
    $nights = $days;

    // Adjust days and nights based on day and night hours
    $dayStart = 8;
    $dayEnd = 22;

    // Check if the start time is within day hours
    if ($startDate->format('H') >= $dayStart && $startDate->format('H') < $dayEnd) {
        $nights--;
    }

    // Check if the end time is within day hours
    if ($endDate->format('H') >= $dayStart && $endDate->format('H') < $dayEnd) {
        $nights--;
    }

    // Display the result
    // echo "Days: $days, Nights: $nights";
    return array('days' => $days, 'nights' => $nights);
}

//new create by surendra
if (!function_exists('countViolationType')) {
    function countViolationType($from, $to, $violation_type)
    {
        if($from == $to){
            $data = PilotViolation::where('violation_type', $violation_type)->count();
        }else{
            $data = PilotViolation::where('violation_type', $violation_type)->whereBetween('dates', [$from, $to])->count();
        }
        return ($data > 0) ? $data : 'N/A';
    }
}

if (!function_exists('countTotalViolationType')) {
    function countTotalViolationType($from, $to)
    {
        if($from == $to){
            $data = PilotViolation::count();
        }else{
            $data = PilotViolation::whereBetween('dates', [$from, $to])->count();
        }
        return ($data > 0) ? $data : 'N/A';
    }
}


if (!function_exists('getBalanceFDTL')) {
    function getBalanceFDTL($user_id, $date)
    {
        $end_date = date('Y-m-d',strtotime($date));
        $rtime=getRequiredBlockTimePilotDateWise($user_id,date('Y-m-d',strtotime($end_date.'-30 days')),date('Y-m-d',strtotime($end_date)));
        $mints=300-minutes($rtime);
        $time= $mints>0?colculate_days_hours_mints($mints):'';
        return $time;
    }
}

if(!function_exists('checkBillStatus')) {
    function checkBillStatus($bill_id)
    {
        $status=true;
        $bills = ReceiveBill::where('is_delete', '0')->where('receives_id',$bill_id)->get();
        foreach($bills as $bill)
        {
            $receiptBills=ReceiptBillFlyingLog::where('bill_id',$bill->id)->where('receives_id',$bill_id)->get();
            if($receiptBills->count()>0)
            {
                foreach($receiptBills as $receiptBill)
                {
                    if(empty($receiptBill->expenses))
                    {
                        $status=false;
                    }
                }
            }else{
                $status=false;
            }
        }
        return $status;
    }
}

if(!function_exists('checkReceiptBillStatus')) {
    function checkReceiptBillStatus($bill_id,$receives_id)
    {
        $status=true;
        $receiptBills=ReceiptBillFlyingLog::where('bill_id',$bill_id)->where('receives_id',$receives_id)->get();
        if($receiptBills->count()>0)
        {
            foreach($receiptBills as $receiptBill)
            {
                if(empty($receiptBill->expenses))
                {
                    $status=false;
                }
            }
        }else{
            $status=false;
        }
        return $status;
    }
}


