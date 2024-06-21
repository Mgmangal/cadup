<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 
use App\Models\Master; 
use App\Models\FlyingLog;
use App\Models\SfaRate;
use App\Models\PilotSfa;
use App\Models\SfaFlyingLog;
use App\Models\PilotLog;
use Illuminate\Support\Facades\DB;
class SFAController extends Controller
{
    
    public function index()
    {
        if(getUserType() == 'user')
        {
            return view('theme-one.sfa.index');
        }else{
            $pilots = User::where('designation', '1')->where('status', 'active')->get();
            return view('sfa.index', compact('pilots'));
        }
    }
    public function list(Request $request)
    {
        $column = ['id', 'departure_time', 'user_id', 'aircraft_id', 'fron_sector', 'to_sector', 'id', 'user_role', 'flying_type', 'id'];
        $users = PilotLog::with(['pilot', 'aircraft'])->where('id', '>', '0');
        $total_row = $users->get()->count();
        if(empty($_POST['from_date'])||empty($_POST['to_date']))
        {
            $output = array(
                "draw"       =>  intval($_POST["draw"]),
                "recordsTotal"   =>  $total_row,
                "recordsFiltered"  =>  0,
                "data"       =>  array(),
                "total_payable_amount"=>round(0,2),
                "total_time"=>round(0,2),
                "certified_that"=>is_setting('certified_that')
            );
            echo json_encode($output);
            die;
        }

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
        $pilot=$_POST['pilot'];
        if(!empty($pilot))
        {
            $users->where('user_id', $pilot);
        }
        if(!empty($_POST['flying_type']))
        {
          $users->where('flying_type', $_POST['flying_type']);  
        }
        
        if (isset($_POST['search'])&&!empty($_POST['search']['value'])) {
            $users->where('fron_sector', 'LIKE', '%' . $_POST['search']['value'] . '%');
        }
        $users->orderBy('departure_time', 'asc');
        if (isset($_POST['order'])) {
            $users->orderBy($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $users->orderBy('departure_time', 'asc');
        }
        $filter_row = $users->get()->count();
        if (isset($_POST["length"]) && $_POST["length"] != -1) {
            $users->skip($_POST["start"])->take($_POST["length"]);
        }
        $result = $users->get();
        $data = array();
        $times=array();
        $gtotal='00';
        foreach ($result as $key => $value) {
            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = is_get_date_format($value->date);
            $sub_array[] = @$value->aircraft->call_sign;
            $sub_array[] = @$value->aircraft->aircraft_cateogry;
            $sub_array[] = $value->fron_sector.'/'.date('H:i',strtotime($value->departure_time));
            $sub_array[] = $value->to_sector.'/'.date('H:i',strtotime($value->arrival_time));
            $sub_array[] = is_time_defrence($value->departure_time, $value->arrival_time);

            $check  = DB::table('sfa_flying_logs') ->join('pilot_sfas', 'sfa_flying_logs.pilot_sfa_id', '=', 'pilot_sfas.id')->where('pilot_sfas.user_id', $pilot)->where('sfa_flying_logs.flying_log_id',$value->flying_log_id)->count();
            if($check==0)
            {
                $mint=minutes(is_time_defrence($value->departure_time, $value->arrival_time));
                $times[]=is_time_defrence($value->departure_time, $value->arrival_time);
                $givenDate =  $value->date; // This should be securely validated and sanitized
                $query = "SELECT * FROM sfa_rates WHERE ? BETWEEN apply_date AND end_date";
                $wing_rate = DB::select($query, [$givenDate]);
                if(empty($wing_rate))
                {
                    $wing_rate=SfaRate::orderBy('id', 'desc')->first();
                }else{
                    $wing_rate=$wing_rate[0];
                }
                //$wing_rate= SfaRate::where('apply_date', '<=', $value->date)->where('end_date', ' >=', $value->date)->where('is_delete', '0')->first();
                $hours=$mint/60;
                if($value->aircraft->aircraft_cateogry=='Roator Wing')
                {
                    $wing_rate=$wing_rate->roator_wing_rate;
                }else{
                    $wing_rate=$wing_rate->fixed_wing_rate;
                }
                $paybilAmount=round(($hours*$wing_rate),2);
                $gtotal+=$paybilAmount;
            }
            $sub_array[] = $this->getMasterName($value->user_role,'pilot_role');
            $sub_array[] = $check==0?'<input type="number" required="" form="sfa-form" name="rate_per_unit[]" class="form-control rate_per_unit" onchange="calPrice(this)" id="" value="'.($wing_rate).'" style=" border-radius: 5px !important;" placeholder="Enter Rate/Hours" readonly>':'-';
            $sub_array[] = $check==0?'<input type="number" form="sfa-form" name="amount[]" readonly="" class="form-control amount" id="" value="'.$paybilAmount.'" style=" border-radius: 5px !important;" placeholder="Total Amount" readonly>':'-';
            $sub_array[] = $check==0?'<input type="text" value="'.$this->getMasterName($value->flying_type,'flying_type').'" form="sfa-form" name="remark[]" maxlength="20" class="form-control remark" id="" style="border-radius: 5px !important;" placeholder="Enter Remarks"><input type="hidden" value="'.$value->flying_log_id.'" form="sfa-form" name="flying_id[]" class=" flying_id" >':'Already SFA Generated';
            $data[] = $sub_array;
        }
        $total_time=AddPlayTime($times);
        $output = array(
            "draw"       =>  intval($_POST["draw"]),
            "recordsTotal"   =>  $total_row,
            "recordsFiltered"  =>  $filter_row,
            "data"       =>  $data,
            "total_payable_amount"=>round($gtotal,2),
            "total_time"=>$total_time,
            "certified_that"=>is_setting('certified_that')
        );
        echo json_encode($output);
    }
    
    
    public function previewSfaReport(Request $request)
	{
	    $pilots= $pilots=$request->pilots;
	    $from_date= $request->from_date;
	    $data['to_date'] = $to_date=$request->to_date;
	    $data['to_date'] = $data['to_date'] ? $data['to_date'] : date("d-m-Y");
	    $rate_per_unit = $request->rate_per_unit;
	    $amount = $request->amount;
	    $remark = $request->remark;
	    $flying_id = $request->flying_id;
	    $certified_that = $request->certified_that;
	    
	    $users = FlyingLog::with(['pilot1', 'pilot2', 'aircraft'])->whereIn('id',  $flying_id);
        $total_row = $users->get()->count();
        
        $users->orderBy('departure_time', 'asc');
	    $result =$users->get();;
	    $i=0;
	    $data_array = array();
	    
	    foreach($flying_id as $key=> $values)
      	{
      	    if(!empty($values))
      	    {
          	    $value=FlyingLog::with(['pilot1', 'pilot2', 'aircraft'])->where('id',$values)->first();
          	    
                $sub_array = array();
    	        $sub_array[] = ++$i;
    	        $sub_array[] = is_get_date_format($value->date);
                $sub_array[] = @$value->aircraft->call_sign;
                $sub_array[] = @$value->aircraft->aircraft_cateogry;
                $sub_array[] = $value->fron_sector.'/'.date('H:i',strtotime($value->departure_time));
                $sub_array[] = $value->to_sector.'/'.date('H:i',strtotime($value->arrival_time));
                $sub_array[] = is_time_defrence($value->departure_time, $value->arrival_time);
                
                $mint=minutes(is_time_defrence($value->departure_time, $value->arrival_time));
                $times[]=is_time_defrence($value->departure_time, $value->arrival_time);
                $hours=$mint/60;
                $paybilAmount=$hours*$rate_per_unit[$key];
                $sub_array[] = $pilots==$value->pilot1_id?$this->getMasterName($value->pilot1_role,'pilot_role'):$this->getMasterName($value->pilot2_role,'pilot_role');
                $sub_array[] = $rate_per_unit[$key];
                $sub_array[] = $paybilAmount;
                $sub_array[] = $remark[$key];//.'-'.$hours.'-'.$rate_per_unit[$i];
    	        $data_array[] = $sub_array;
      	    }
      	}
      	$data['pilots'] =$pilots;
      	$data['from_date'] =$from_date;
      	$data['rate_per_unit'] = $rate_per_unit;
      	$data['amount'] = $amount;
      	$data['remark'] = $remark;
      	$data['flying_id'] = $flying_id;
      	$data['certified_that'] = $certified_that ;
      	
      	$user=User::find($pilots);
      	$data['all_flying'] = $data_array;
        
      	$data['pilot_name'] = $user->salutation.' '.$user->name;
      	$data['designation'] = getMasterName($user->designation);
      	$data['certified_that'] = !empty($certified_that)?$certified_that:is_setting('certified_that');
      	$data['from_date'] = date("F d, Y",strtotime($data['from_date']));
      	$data['to_date'] = date("F d, Y",strtotime($data['to_date']));
    
        $html=view('sfa.sfa-report-preview', $data)->render();
        $mpdfConfig = array(
                'mode' => 'utf-8', 
                'format' => 'A4',
                'margin_header' => 15,     // 30mm not pixel
                'margin_footer' => 10,     // 10mm
                'orientation' => 'P',
                'setAutoTopMargin' => 'stretch',
                'autoPageBreak' => false
            );
        ini_set('memory_limit', '-1');
        $mpdf = new \Mpdf\Mpdf($mpdfConfig);
        $mpdf->SetFont('Times New Roman', 'B');
        $space1="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        $space2="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        
        $header = '<header>
                        <table style="width:100%; top:0;position: relative;">
                          <tr>
                            <th>
                              <h4 style="margin: 0; line-height: 17px;font-size:23px">  Special Flying Allowance (SFA)</h4>
                              <h4 style="margin: 0; line-height: 17px;"> Employee : '.$user->salutation.' '.$user->name.'</h4>
                              <h4 style="margin: 0; line-height: 17px;">  '.date("M d, Y",strtotime($data['from_date'])).' - '.date("M d, Y",strtotime($data['to_date'])).'</h4>
                              
                            </th>
                          </tr>
                        </table><br>
                    </header>';
        $footer = '<footer>
            <table style="width:100%;position: relative; bottom: 360px;" class="footer-main11">
              <tr>
                <td class="footer" style="font-size:12px;" >
                    '.(!empty($certified_that)?$certified_that:is_setting('certified_that')).'
                </td>
              </tr>
              <tr>
                <td style="padding-top: 5px;padding-left: 5px;">
                  <p style="display:flex1;font-size: 13px;">
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br><br>
                    <span style="height:40px">HOURS CHECKED</span>'.$space1.'
                    <span style="height:40px; margin-left:50px">HOURS VERIFIED </span>
                  </p><br><br>
                  <p style="display:flex1;font-size: 17px;color: #000;">
                    <span style="font-size: 16px;color: #000;">Claimant\'s signature </span>'.$space2.'
                    <span style="font-size: 16px;color: #000;padding-right: 15px;margin-left: 500px;">Claimant\'s signature </span>
                  </p>
                  <p style="font-size: 13px;color: #000;margin: 0px;"><span style="color:red; font-size: 17px;">A/C Type</span> - Aircraft Type , <span style="color:red; font-size: 17px;">Regn.</span> - Aircraft Registration , <span style="color:red; font-size: 17px;">Sr. No.</span> - Serial Number , <span style="color:red; font-size: 17px;">G.O. </span>- Government Order</p>
                </td>
              </tr>
            </table>
            <p style="display:flex;justify-content: space-between;margin-left: 150mm; margin-top:1mm">
                <span style="">Page {PAGENO} of {nb} </span>
            </p>
            </footer>';
        $footer = '<footer>
            <table style="width:100%;position: relative; bottom: 360px;" class="footer-main11">
              <tr>
                <td class="footer" style="font-size:12px;" >
                    '.(!empty($certified_that)?$certified_that:is_setting('certified_that')).'
                </td>
              </tr>
              <tr>
                <td style="padding-top: 5px;padding-left: 5px;">
                  <p style="display:flex1;font-size: 13px;">
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br><br><br>
                    <span style="height:40px">Claimant\'s signature HOURS CHECKED</span>'.$space1.'
                    <span style="height:40px; margin-left:50px">Claimant\'s signature HOURS VERIFIED </span>
                  </p><br>
                  <p style="font-size: 13px;color: #000;margin: 0px;"><span style="color:red; font-size: 17px;">A/C Type</span> - Aircraft Type , <span style="color:red; font-size: 17px;">Regn.</span> - Aircraft Registration , <span style="color:red; font-size: 17px;">Sr. No.</span> - Serial Number , <span style="color:red; font-size: 17px;">G.O. </span>- Government Order</p>
                </td>
              </tr>
            </table>
            <p style="display:flex;justify-content: space-between;margin-left: 150mm; margin-top:1mm">
                <span style="">Page {PAGENO} of {nb} </span>
            </p>
            </footer>';
        $mpdf->SetHTMLHeader($header);

        $mpdf->SetHTMLFooter($footer);
        // $mpdf->setFooter('Page {PAGENO} of {nb}');
        // $mpdf->defaultfooterline = 0;
        $mpdf->WriteHTML($html);
        $mpdf->Output('SAF-Report.pdf','D'); // opens in browser $mpdf->Output('arjun.pdf','D');
        
	}
    
    public function getMasterName($id,$type)
    {
        $data=Master::where('id',$id)->where('type',$type)->first();
        return !empty($data)?$data->name:'';
    }

    public function getUserSfaList(Request $request)
    {
        $column = ['id', 'user_id','from_date', 'to_date', 'amount', 'status', 'id'];
        $users = PilotSfa::where('id', '>', 0);

        if(!empty($_POST['pilot'])){
            $users->where('user_id', '=', $_POST['pilot']);
        }
        $total_row = $users->get()->count();
        if(!empty($_POST['from_date'])&&empty($_POST['to_date']))
        {
            $from=$_POST['from_date'];
            $users->where('from_date','>=',date('Y-m-d',strtotime($from))); 
        }
        if(empty($_POST['from_date'])&&!empty($_POST['to_date']))
        {
            $to=$_POST['to_date'];
            $users->where('from_date','<=',date('Y-m-d',strtotime($to))); 
        }
        if(!empty($_POST['from_date'])&&!empty($_POST['to_date']))
        {
            $from=$_POST['from_date'];
            $to=$_POST['to_date'];
            $users->where(function($q) use($from, $to){
                $q->whereBetween('from_date', [date('Y-m-d',strtotime($from)), date('Y-m-d',strtotime($to))]);
            }); 
        }

        if (isset($_POST['search'])&&!empty($_POST['search']['value'])) {
            $users->where('fron_sector', 'LIKE', '%' . $_POST['search']['value'] . '%');
        }
        if (isset($_POST['order'])) {
            $users->orderBy($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $users->orderBy('id', 'asc');
        }
        $filter_row = $users->get()->count();
        if (isset($_POST["length"]) && $_POST["length"] != -1) {
            $users->skip($_POST["start"])->take($_POST["length"]);
        }
        $result = $users->get();
        $data = array();
        foreach ($result as $key => $value) {
            $sub_array = array();
            $action ='';
            if(getUserType() == 'user')
            {
                $action .='<a href="' . route('user.sfa.view',encrypter('encrypt', $value->id)) . '" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a>';
                if($value->status=='Generated')
                {
                    $action .=' <a href="' . route('user.sfa.deleted',encrypter('encrypt', $value->id)) . '" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>';
                }
                if($value->user_id==auth()->user()->id && $value->status=='Generated')
                {
                    $action .=' <a href="' . route('user.sfa.verify',encrypter('encrypt', $value->id)) . '" class="btn btn-success btn-sm"><i class="fa fa-check"></i></a>';
                }else{
                    $action .=' <a href="' . route('user.sfa.approved',encrypter('encrypt', $value->id)) . '" class="btn btn-success btn-sm"><i class="far fa-check-circle"></i></a>';
                }
            }else{
                $action .='<a href="' . route('app.sfa.view',encrypter('encrypt', $value->id)) . '" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a>';
                $action .=' <a href="' . route('app.sfa.deleted',encrypter('encrypt', $value->id)) . '" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>';
            }
            $sub_array[] = ++$key;
            $sub_array[] = getEmpFullName($value->user_id);
            $sub_array[] = is_get_date_format($value->from_date);
            $sub_array[] = is_get_date_format($value->to_date);
            $sub_array[] = $value->amount;
            $sub_array[] = $value->status;
            $sub_array[] = $action;
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
    public function generate()
    {
        $pilots = User::where('designation', '1')->where('status', 'active')->get();
        if(getUserType() == 'user')
        {
            return view('theme-one.sfa.generate', compact('pilots'));
        }else{
            return view('sfa.generate', compact('pilots'));
        }
    }

    public function generateSfa(Request $request)
    {
        $pilot = $request->pilots;
        $from = $request->from_date;
        $to = $request->to_date;
        $total_price = $request->total_price;
        $certified_that = $request->certified_that;
        $rate_per_unit=$request->rate_per_unit;
        $amount=$request->amount;
        $remark=$request->remark;
        $flying_id=$request->flying_id;

        $sfa = new PilotSfa();
        $sfa->user_id = $pilot;
        $sfa->from_date = is_set_date_format($from);
        $sfa->to_date = is_set_date_format($to);
        $sfa->amount = $total_price;
        $sfa->certify_that = $certified_that;
        $sfa->status = 'Generated';
        $sfa->save();
        $last_id=$sfa->id;
        foreach($flying_id as $key => $value){
            $rate = new SfaFlyingLog();
            $rate->user_id = $pilot;
            $rate->pilot_sfa_id = $last_id;
            $rate->flying_log_id = $value;
            $rate->rate_per_hour = $rate_per_unit[$key];
            $rate->amount = $amount[$key];
            $rate->remark = $remark[$key];
            $rate->save();
        }
        if(getUserType() == 'user')
        {
            return redirect(route('user.sfa.list'))->with('success', 'SFA Generated Successfully.');
        }else{
            return redirect(route('app.sfa'))->with('success', 'SFA Generated Successfully.');
        }
    }

    public function sfaView($id)
    {
        $id = encrypter('decrypt',$id);
        if(empty($id))
        {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
        $sfa = PilotSfa::with('pilot')->find($id);
        $query=DB::table('sfa_flying_logs')
        ->select('sfa_flying_logs.remark','sfa_flying_logs.amount','sfa_flying_logs.rate_per_hour','pilot_logs.date','pilot_logs.aircraft_id','pilot_logs.user_role', 'pilot_logs.fron_sector', 'pilot_logs.to_sector', 'pilot_logs.departure_time', 'pilot_logs.arrival_time')
        ->join('pilot_logs', function($join) {
            $join->on('pilot_logs.flying_log_id', '=', 'sfa_flying_logs.flying_log_id')
                 ->on('sfa_flying_logs.user_id', '=', 'pilot_logs.user_id');
        });
        $query->where('sfa_flying_logs.pilot_sfa_id', $id);
        $query->orderBy('pilot_logs.departure_time', 'asc');

	    $result =$query->get();;
	    $i=0;
	    $data_array = array();
	    foreach($result as $key=> $value)
      	{
            $sub_array = array();
            $sub_array[] = ++$i;
            $sub_array[] = is_get_date_format($value->date);
            $sub_array[] = getAirCraft($value->aircraft_id)->call_sign;
            $sub_array[] = getAirCraft($value->aircraft_id)->aircraft_cateogry;
            $sub_array[] = $value->fron_sector.'/'.date('H:i',strtotime($value->departure_time));
            $sub_array[] = $value->to_sector.'/'.date('H:i',strtotime($value->arrival_time));
            $sub_array[] = is_time_defrence($value->departure_time, $value->arrival_time);
            
            $sub_array[] = $this->getMasterName($value->user_role,'pilot_role');
            $sub_array[] = $value->rate_per_hour;
            $sub_array[] = $value->amount;
            $sub_array[] = $value->remark;
            $data_array[] = $sub_array;
      	}
        $data['all_flying']=$data_array;
        $data['pilot_name'] = $sfa->pilot->salutation.' '.$sfa->pilot->name;
        $data['designation'] = getMasterName($sfa->pilot->designation);
      	$data['certified_that'] = $sfa->certify_that;
      	$data['from_date'] = date("F d, Y",strtotime($sfa->from_date));
      	$data['to_date'] = date("F d, Y",strtotime($sfa->to_date));
        $data['sfa_id']=$id;
        if(getUserType()=='user'){
            return view('theme-one.sfa.view', $data);
        }else{
            return view('sfa.view', $data);
        }
    }
    
    public function downloadSfaReport(Request $request, $id)
	{
        $id=encrypter('decrypt',$id);
        $sfa = PilotSfa::with('pilot')->find($id);
        $query=DB::table('sfa_flying_logs')
        ->select('sfa_flying_logs.remark','sfa_flying_logs.amount','sfa_flying_logs.rate_per_hour','pilot_logs.date','pilot_logs.aircraft_id','pilot_logs.user_role', 'pilot_logs.fron_sector', 'pilot_logs.to_sector', 'pilot_logs.departure_time', 'pilot_logs.arrival_time')
        ->join('pilot_logs', function($join) {
            $join->on('pilot_logs.flying_log_id', '=', 'sfa_flying_logs.flying_log_id')
                 ->on('sfa_flying_logs.user_id', '=', 'pilot_logs.user_id');
        });
        $query->where('sfa_flying_logs.pilot_sfa_id', $id);
        $query->orderBy('pilot_logs.departure_time', 'asc');

	    $result =$query->get();;
	    $i=0;
	    $data_array = array();
	    foreach($result as $key=> $value)
      	{
            $sub_array = array();
            $sub_array[] = ++$i;
            $sub_array[] = is_get_date_format($value->date);
            $sub_array[] = getAirCraft($value->aircraft_id)->call_sign;
            $sub_array[] = getAirCraft($value->aircraft_id)->aircraft_cateogry;
            $sub_array[] = $value->fron_sector.'/'.date('H:i',strtotime($value->departure_time));
            $sub_array[] = $value->to_sector.'/'.date('H:i',strtotime($value->arrival_time));
            $sub_array[] = is_time_defrence($value->departure_time, $value->arrival_time);
            
            $sub_array[] = $this->getMasterName($value->user_role,'pilot_role');
            $sub_array[] = $value->rate_per_hour;
            $sub_array[] = $value->amount;
            $sub_array[] = $value->remark;
            $data_array[] = $sub_array;
      	}
        $data['all_flying']=$data_array;
        $data['pilot_name'] = $sfa->pilot->salutation.' '.$sfa->pilot->name;
        $data['designation'] = getMasterName($sfa->pilot->designation);
        $certified_that=$sfa->certify_that;
      	$data['certified_that'] = $certified_that;
      	$data['from_date'] = date("F d, Y",strtotime($sfa->from_date));
      	$data['to_date'] = date("F d, Y",strtotime($sfa->to_date));
        $html=view('sfa.sfa-report-preview', $data)->render();
        $mpdfConfig = array(
                'mode' => 'utf-8', 
                'format' => 'A4',
                'margin_header' => 15,     // 30mm not pixel
                'margin_footer' => 10,     // 10mm
                'orientation' => 'P',
                'setAutoTopMargin' => 'stretch',
                'autoPageBreak' => false
            );
        ini_set('memory_limit', '-1');
        $mpdf = new \Mpdf\Mpdf($mpdfConfig);
        $mpdf->SetFont('Times New Roman', 'B');
        $space1="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        $space1="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        
        $space2="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        
        $header = '<header>
                        <table style="width:100%; top:0;position: relative;">
                          <tr>
                            <th>
                              <h4 style="margin: 0; line-height: 17px;font-size:23px">  Special Flying Allowance (SFA)</h4>
                              <h4 style="margin: 0; line-height: 17px;"> Employee : '.$data['pilot_name'].'</h4>
                              <h4 style="margin: 0; line-height: 17px;">  '.date("M d, Y",strtotime($data['from_date'])).' - '.date("M d, Y",strtotime($data['to_date'])).'</h4>
                              
                            </th>
                          </tr>
                        </table><br>
                    </header>';
        $footer = '<footer>
            <table style="width:100%;position: relative; bottom: 360px;" class="footer-main11">
              <tr>
                <td class="footer" style="font-size:12px;" >
                    '.(!empty($certified_that)?$certified_that:is_setting('certified_that')).'
                </td>
              </tr>
              <tr>
                <td style="padding-top: 5px;padding-left: 5px;">
                  <p style="display:flex1;font-size: 13px;">
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br><br>
                    <span style="height:40px">HOURS CHECKED</span>'.$space1.'
                    <span style="height:40px; margin-left:50px">HOURS VERIFIED </span>
                  </p><br><br>
                  <p style="display:flex1;font-size: 17px;color: #000;">
                    <span style="font-size: 16px;color: #000;">Claimant\'s signature </span>'.$space2.'
                    <span style="font-size: 16px;color: #000;padding-right: 15px;margin-left: 500px;">Claimant\'s signature </span>
                  </p>
                  <p style="font-size: 13px;color: #000;margin: 0px;"><span style="color:red; font-size: 17px;">A/C Type</span> - Aircraft Type , <span style="color:red; font-size: 17px;">Regn.</span> - Aircraft Registration , <span style="color:red; font-size: 17px;">Sr. No.</span> - Serial Number , <span style="color:red; font-size: 17px;">G.O. </span>- Government Order</p>
                </td>
              </tr>
            </table>
            <p style="display:flex;justify-content: space-between;margin-left: 150mm; margin-top:1mm">
                <span style="">Page {PAGENO} of {nb} </span>
            </p>
            </footer>';
        $footer = '<footer>
            <table style="width:100%;position: relative; bottom: 360px;" class="footer-main11">
              <tr>
                <td class="footer" style="font-size:12px;" >
                    '.(!empty($certified_that)?$certified_that:is_setting('certified_that')).'
                </td>
              </tr>
              <tr>
                <td style="padding-top: 5px;padding-left: 5px;">
                  <p style="display:flex1;font-size: 13px;">
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br><br><br>
                    <span style="height:40px">Claimant\'s signature HOURS CHECKED</span>'.$space1.'
                    <span style="height:40px; margin-left:50px">Claimant\'s signature HOURS VERIFIED </span>
                  </p><br>
                  <p style="font-size: 13px;color: #000;margin: 0px;"><span style="color:red; font-size: 17px;">A/C Type</span> - Aircraft Type , <span style="color:red; font-size: 17px;">Regn.</span> - Aircraft Registration , <span style="color:red; font-size: 17px;">Sr. No.</span> - Serial Number , <span style="color:red; font-size: 17px;">G.O. </span>- Government Order</p>
                </td>
              </tr>
            </table>
            <p style="display:flex;justify-content: space-between;margin-left: 150mm; margin-top:1mm">
                <span style="">Page {PAGENO} of {nb} </span>
            </p>
            </footer>';
        $mpdf->SetHTMLHeader($header);

        $mpdf->SetHTMLFooter($footer);
        // $mpdf->setFooter('Page {PAGENO} of {nb}');
        // $mpdf->defaultfooterline = 0;
        $mpdf->WriteHTML($html);
        $mpdf->Output('SAF-Report.pdf','D'); // opens in browser $mpdf->Output('arjun.pdf','D');
        
	}

    public function sfaDelete(Request $request, $id)
    {
        $id = encrypter('decrypt',$id);
        if(empty($id))
        {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
        $sfa = PilotSfa::find($id);
        SfaFlyingLog::where('pilot_sfa_id',$sfa->id)->delete();
        $sfa->delete();
        return redirect()->back()->with('success', 'SFA deleted successfully');
    }

    public function verify(Request $request, $id)
    {
        $id = encrypter('decrypt',$id);
        if(empty($id))
        {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
        $sfa = PilotSfa::find($id);
        $sfa->status = 'Pilot Verified';
        $sfa->save();
        return redirect()->back()->with('success', 'SFA verified successfully');
    }

    public function approved(Request $request, $id)
    {
        $id = encrypter('decrypt',$id);
        if(empty($id))
        {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
        $sfa = PilotSfa::find($id);
        $sfa->status = 'Ops Manager Approved';
        $sfa->save();
        return redirect()->back()->with('success', 'SFA verified successfully');
    }
}
