<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AdtReport;
use App\Models\AdtGenerateReport;
class AdtController extends Controller
{
    public function index(){
        return view('adt.index');
    }

    public function list(Request $request)
    {
        $column=['id','id','emp_id','name','email','phone','designation','created_at','id','id'];
        $users=User::with('designation')->where('is_adt','=','yes');

        if (isset($_POST['parent_id'])) {
            $users->where('parent_id', '=', $_POST['parent_id']);
		}

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
           
            $sub_array = array();
			$sub_array[] = ++$key;
            $sub_array[] = '<img src="'.is_image('uploads/'.$value->profile).'" width="50" height="50" class="img-thumbnail" />';
            $sub_array[] = $value->emp_id;
            $sub_array[] = $value->salutation.' '.$value->name;
            $sub_array[] = $value->email;
            $sub_array[] = $value->phone;
            $sub_array[] = $value->designation()->first()->name??'';
            $sub_array[] = date('d-m-Y',strtotime($value->created_at));
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

    public function report (Request $request)
    {
        return view('adt.report');
    }

    public function reportList(Request $request)
    {
        $column=['id','name','created_at','id'];
        
        $users=AdtGenerateReport::where('id','>','0')->groupBy('created_for_date');
        $total_row=$users->get()->count();
        if (!empty($_POST['search']['value'])) {
            $users->where('created_for_date', 'LIKE', '%' . $_POST['search']['value'] . '%');
		}
        // $users->groupBy('created_for_date');
		if (isset($_POST['order'])) {
            $users->orderBy($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else {
            $users->orderBy('id', 'desc');
        }
       
		$filter_row =$users->get()->count();
        if (isset($_POST["length"]) && $_POST["length"] != -1) {
            $users->skip($_POST["start"])->take($_POST["length"]);
		}
        $result =$users->get();
        $data = array();
		foreach ($result as $key => $value) {
            $latest_id = AdtGenerateReport::select('id')->orderBy('id','desc')->where('created_for_date',$value->created_for_date)->first();
            $count = AdtGenerateReport::select('id')->orderBy('id','desc')->where('created_for_date',$value->created_for_date)->get()->count();
            $staff_list = '<a class="btn btn-sm btn-info" href="javascript:void(0);" onclick="printData(`'.route('app.adt.staff.download',$latest_id->id).'`);" >Download Employees List</a> &nbsp';
        	$staff_test_list = '<a class="btn btn-sm btn-warning" href="javascript:void(0);" onclick="printData(`'.route('app.adt.test.download',$latest_id->id).'`);">Download Test Report</a>';
            $action='<a class="btn btn-sm btn-success m-2" href="javascript:void(0);" onclick="uploadData(`'.$latest_id->id.'`);">Upload</a>';
            if(!empty($value->report))
            {
                $action .= '<a class="btn btn-sm btn-primary m-2" href="'.asset('uploads/adt/'.$value->report).'" download >Download</a>';
            }
            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = date('d-m-Y',strtotime($value->created_at));
            $sub_array[] = date('d-m-Y',strtotime($value->created_for_date));
            $sub_array[] = $staff_list;
            $sub_array[] = $staff_test_list; 
            $sub_array[] = $count;
            $sub_array[] = $action;
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

    public function upload(Request $request)
    {
        $id=$request->id;
        if($request->hasFile('report'))
        {
            $file = $request->file('report');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('uploads/adt'), $filename);
            $data['report'] = $filename;
            AdtGenerateReport::where('id',$id)->update($data);
            return response()->json([
                'success' => true,
                'message' => 'Report Uploaded Successfully',
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Report Not Uploaded',
            ]);
        }
    }

    public function generate(Request $request)
    {
        return view('adt.generate');
    }

    public function generateReport(Request $request)
    {
        $validation = $request->validate([
            'date' => 'required',
        ]);
        $date = $request->date;
        $genReport=new AdtGenerateReport;
        $genReport->created_for_date = $date;
        $genReport->status = '1';
        $genReport->save();
        $last_id=$genReport->id;
        //check for already entered for same day 
        $get_same_day_entry = AdtReport::select('id')->where('created_for_date',$date)->where('status','1')->first();
        if(!empty($get_same_day_entry->id)){
            $d=AdtReport::find($get_same_day_entry->id);
            $d->status='0';
            $d->save();
        }
        $total=$this->get_percent_emp(10)!=0?$this->get_percent_emp(10):1;
        $get_staff_list=User::select('id')->where('is_adt','=','yes')->where('status','=','active')->inRandomOrder()->take($total)->get()->toArray();
       
        $array = array_column($get_staff_list, 'id');
        $ids = implode (',', $array); 
        $dataInsert =new AdtReport;
        $dataInsert->generate_report_id = $last_id;
        $dataInsert->created_for_date = date('Y-m-d',strtotime($date));
        $dataInsert->emp_ids = $ids;
        $dataInsert->status = '1';
        $dataInsert->save();
        return redirect()->route('app.adt.report.generate')->with('success', 'Report Generated Successfully');
    }
    public function get_percent_emp($percent)
	{
	    $row= User::where('is_adt','=','yes')->where('status','=','active')->count();
	    $total=round(($row*$percent)/100);
	    return $total;
	}

    public function reportAllList(Request $request)
    {
        $column=['id','created_at','created_for_date','id','id'];
        $users=AdtReport::where('id','>','0');
        $total_row=$users->count();
        if (isset($_POST['search'])) {
            $users->where('created_at', 'LIKE', '%' . $_POST['search']['value'] . '%');
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
            $print = '<a class="btn btn-sm btn-danger" target="_blank" href="'.route('app.adt.staff.download',$value->generate_report_id).'" >Print</a> &nbsp';
            $view = '<a class="btn btn-sm btn-info" target="_blank" href="'.route('app.adt.test.download',$value->generate_report_id).'" >View</a> &nbsp';
            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = date('d-m-Y',strtotime($value->created_at));
            $sub_array[] = date('d-m-Y',strtotime($value->created_for_date));
            $sub_array[] = $value->status == '1'?$print:$view; 
            $sub_array[] = $value->status == '1'?'Active':'Inactive';
            //$sub_array[] = date('d-m-Y',strtotime($value->created_on));
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

    public function downloadTestList($id)
    {
        $ids =AdtReport::select('emp_ids','created_for_date')->where('generate_report_id',$id)->first();
        $data['IDs'] = $ids->emp_ids;
        $data['date'] = $ids->created_for_date; 
        return view('adt.test_list',$data);
    }

    public function downloadStaffList($id,$flag='')
    {
        $ids = AdtReport::select('emp_ids','created_for_date')->where('generate_report_id',$id)->first();
        $data['IDs'] = $ids->emp_ids; 
        $data['date'] = $ids->created_for_date; 
        $data['flag'] = $flag; 
        return view('adt.staff_list',$data);
    }
}