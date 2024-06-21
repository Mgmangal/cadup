<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\NoneFlyingLog;
use Illuminate\Support\Facades\Validator;
class NoneFlyingLogController extends Controller
{
    public function index()
    {
        return view('none_flying_logs.index');
    }

    public function create()
    {
        $pilots = User::where('designation', '1')->where('status', 'active')->get();
        return view('none_flying_logs.create', compact('pilots'));
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'user_id' => 'required',
            'from_dates' => 'required',
            'to_dates' => 'required',
        ]);
        if ($validation->fails()) {
            return response()->json(['success' => false, 'message' => $validation->errors()]);
        }
       
        $data=new NoneFlyingLog;
        $data->user_id=$request->user_id;
        $data->from_dates  = is_set_date_time_format($request->from_dates);
        $data->to_dates= is_set_date_time_format($request->to_dates);
        $data->reason= $request->reason;
        $data->save();
        return response()->json(['success' => true, 'message' => 'Data Added successfully.']);
    }

    public function edit($id)
    {
        $pilots = User::where('designation', '1')->where('status', 'active')->get();
        $data = NoneFlyingLog::find($id);
        return view('none_flying_logs.edit', compact('data', 'pilots'));
    }

    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'user_id' => 'required',
            'from_dates' => 'required',
            'to_dates' => 'required',
        ]);
        if ($validation->fails()) {
            return response()->json(['success' => false, 'message' => $validation->errors()]);
        }

        $data = NoneFlyingLog::find($id);
        $data->user_id=$request->user_id;
        $data->from_dates  = is_set_date_time_format($request->from_dates);
        $data->to_dates= is_set_date_time_format($request->to_dates);
        $data->reason= $request->reason;
        $data->save();

        return response()->json(['success' => true, 'message' => 'Data Updated successfully.']);
    }

    public function destroy($id)
    {
        $data = NoneFlyingLog::find($id);
        $data->delete();
        return response()->json(['success' => true, 'message' => 'Data Deleted successfully.']);
    }

    public function list(Request $request)
    {
        $column = ['id', 'user_id', 'from_dates', 'to_dates', 'id', 'id'];
        $users = NoneFlyingLog::with(['user'])->where('id', '>', '0');

        $total_row = $users->get()->count();
        if (isset($_POST['search'])) {
            $users->where('from_dates', 'LIKE', '%' . $_POST['search']['value'] . '%');
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
        $role = array('1' => 'AME', '2' => 'P1', '3' => 'P2', '4' => 'P1 (US)', '5' => 'Examiner / Instructor');
        $flying_type = array('1' => 'VIP', '2' => 'Other', '3' => 'Training', '4' => 'Ground Run', '5' => 'Flying Check');
        foreach ($result as $key => $value) {
            $action  = '<a href="' . route('app.none-flying-details.edit', $value->id) . '" class="btn btn-warning btn-sm m-1">Edit</a>';
            $action .= '<a href="javascript:void(0);" onclick="deleted(`' . route('app.none-flying-details.destroy', $value->id) . '`);" class="btn btn-danger btn-sm m-1">Delete</a>';
            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = @$value->user->salutation . ' ' . @$value->user->name;
            $sub_array[] = is_get_date_time_format($value->from_dates);
            $sub_array[] = is_get_date_time_format($value->to_dates);
            $sub_array[] = is_time_defrence($value->departure_time, $value->to_dates);
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
}
