<?php

namespace App\Http\Controllers\ThemeOne;
use App\Http\Controllers\Controller;
use App\Models\LoadTrim;
use App\Models\AirCraft;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class LoadTrimController extends Controller
{
    public function index()
    {
        $aircrafts=AirCraft::all();
        return view('theme-one.load-trim.index',compact('aircrafts'));
    }

    public function apply()
    {
        $user = Auth::user();
        $aircrafts=AirCraft::all();
        return view('theme-one.load-trim.create',compact('aircrafts'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $user_id=$user->id;
        $aircraft=$request->aircraft;
        $dates=$request->dates;
        $reason=$request->remark;

        $data=new LoadTrim();
        if($request->hasFile('documnets')) {
            $file = $request->file('documnets');
            $file->move(public_path('uploads/load_trim'), $file->getClientOriginalName());
            $data->documnets = $file->getClientOriginalName();
            // @unlink(asset('uploads/leave/'.$data->documnets));
        }
        $data->user_id=$user_id;
        $data->aircraft=$aircraft;
        $data->dates=is_set_date_format($dates);
        $data->remark=$reason;
        $data->save();
        return redirect()->route('user.loadTrim')->with('success','Created successfully');
    }

    public function list(Request $request)
    {
        $user = Auth::user();
        $column=['id','aircraft','dates','documnets','id'];
        $users=LoadTrim::with('air_craft')->where('user_id',$user->id)->where('is_delete','0');

        $total_row=$users->count();
        if(!empty($_POST['aircraft']))
        {
            $aircraft=$_POST['aircraft'];
            $users->where('aircraft','=',$aircraft);
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

        if (!empty($_POST['search']['value'])) {
            $users->where('dates', 'LIKE', '%' . $_POST['search']['value'] . '%');
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

            $action  .= '<a href="'.route('user.loadTrim.edit', $value->id).'" class="btn btn-primary btn-sm m-1">Edit</a>';

            $action .= '<a href="'.route('user.loadTrim.cancelled', $value->id).'" class="btn btn-danger btn-sm m-1">Delete</a>';

            $sub_array = array();
			$sub_array[] = ++$key;
            $sub_array[] =  date('d-m-Y',strtotime($value->dates));
            $sub_array[] =  $value->air_craft->call_sign;
            $sub_array[] = !empty( $value->documnets)?'<a href="'.asset('uploads/load_trim/'.$value->documnets).'" target="blank" class="btn btn-sm btn-warning">View</a>':'';
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

    public function edit($id)
    {
        $user = Auth::user();
        $aircrafts=AirCraft::all();
        $data=LoadTrim::find($id);
        return view('theme-one.load-trim.edit',compact('aircrafts','data'));
    }

    public function update(Request $request,$id)
    {
        $user = Auth::user();
        $user_id=$user->id;
        $aircraft=$request->aircraft;
        $dates=$request->dates;
        $reason=$request->remark;
        $data=LoadTrim::find($id);
        if($request->hasFile('documnets')) {
            $file = $request->file('documnets');
            $file->move(public_path('uploads/load_trim'), $file->getClientOriginalName());
            $data->documnets = $file->getClientOriginalName();
            @unlink(asset('uploads/load_trim/'.$data->documnets));
        }
        $data->user_id=$user_id;
        $data->aircraft=$aircraft;
        $data->dates=is_set_date_format($dates);
        $data->remark=$reason;
        $data->save();
        return redirect()->route('user.loadTrim')->with('success','Updated successfully');
    }

    public function cancelled($id)
    {
        $model = LoadTrim::findOrFail($id);
        $model->is_delete='1';
        $model->save();
        return redirect()->route('user.loadTrim')->with('success','Deleted successfully');
    }

}
