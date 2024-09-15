<?php

namespace App\Http\Controllers\ThemeOne;

use App\Models\Ata;
use App\Models\Tbo;
use App\Models\AirCraft;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TboController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['permission:TBO Add|TBO Edit|TBO Delete|TBO View']);

    }
    public function index()
    {
        $aircrafts = AirCraft::where('status', 'active')->get();
        return view('theme-one.tbo.index', compact('aircrafts'));
    }
    public function add()
    {
        $aircrafts = AirCraft::where('status', 'active')->get();
        $atas = Ata::whereNotNull('parent_id')->where('is_delete', '0')->get();
        return view('theme-one.tbo.create',compact('aircrafts','atas'));
    }
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'aircraft_call_sign' => 'required',
            'tbo_type' => 'required',
            'ata_code' => 'required',
            'tbo_requirement' => 'required',
            'fitting_date' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        }
        $data = new Tbo();
        $data->name = $request->name;
        $data->aircraft_call_sign = $request->aircraft_call_sign;
        $data->tbo_type	 = $request->tbo_type;
        $data->ata_code	 = $request->ata_code;
        $data->part_number = $request->part_number;
        $data->serial_number = $request->serial_number;
        $data->tbo_requirement = $request->tbo_requirement;
        $data->fitting_date = date('Y-m-d',strtotime($request->fitting_date));
        $data->status = $request->status;
        $data->save();
        return redirect()->route('user.tbo')->with('success','Add TBO successfully');

    }
    public function list(Request $request)
    {
        $column = ['id', 'name', 'aircraft_call_sign', 'tbo_type', 'ata_code', 'part_number', 'serial_number', 'tbo_requirement', 'fitting_date', 'created_at', 'id'];
        $masters = Tbo::where('is_delete','0');

        $total_row = $masters->count();
        if (isset($_POST['search'])) {
            $masters->where('name', 'LIKE', '%' . $_POST['search']['value'] . '%');
        }
        if (isset($_POST['search'])) {
            $masters->where('fitting_date', 'LIKE', '%' . $_POST['search']['value'] . '%');
        }

        if (isset($_POST['order'])) {
            $masters->orderBy($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $masters->orderBy('id', 'desc');
        }
        $filter_row = $masters->count();
        if (isset($_POST["length"]) && $_POST["length"] != -1) {
            $masters->skip($_POST["start"])->take($_POST["length"]);
        }
        $result = $masters->get();
        $data = array();
        foreach ($result as $key => $value) {

            $action = '';
            // if (auth()->user()->can('TBO Edit')) {
                $action .= '<a href="' . route('user.tbo.edit', $value->id) . '" class="btn btn-warning btn-sm m-1">Edit</a>';
            // }
            // if (auth()->user()->can('TBO Delete')) {
                $action .= '<a href="javascript:void(0);" onclick="deleted(`' . route('user.tbo.destroy', $value->id) . '`);" class="btn btn-danger btn-sm m-1">Delete</a>';
            // }
            $status = '<select class="form-control" onchange="changeStatus(' . $value->id . ',this.value);">';
            $status .= '<option ' . ($value->status == 'active' ? 'selected' : '') . ' value="active">Active</option>';
            $status .= '<option ' . ($value->status == 'inactive' ? 'selected' : '') . ' value="inactive">Inactive</option>';
            $status .= '</select>';
            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = $value->name;
            $sub_array[] = $value->aircraft_call_sign;
            $sub_array[] = $value->tbo_type;
            $sub_array[] = $value->ata_code;
            $sub_array[] = $value->part_number;
            $sub_array[] = $value->serial_number;
            $sub_array[] = $value->tbo_requirement;
            $sub_array[] = date('d-m-Y', strtotime($value->fitting_date));
            $sub_array[] = date('d-m-Y', strtotime($value->created_at));
            $sub_array[] = $status;
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
    public function edit($id)
    {
        $tboData = Tbo::find($id);
        $aircrafts = AirCraft::where('status', 'active')->get();
        $atas = Ata::whereNotNull('parent_id')->where('is_delete', '0')->get();
        return view('theme-one.tbo.edit', compact('tboData','aircrafts','atas'));
    }
    public function update(Request $request,$id)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'aircraft_call_sign' => 'required',
            'tbo_type' => 'required',
            'ata_code' => 'required',
            'tbo_requirement' => 'required',
            'fitting_date' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        }
        $data=Tbo::find($id);
        $data->name = $request->name;
        $data->aircraft_call_sign = $request->aircraft_call_sign;
        $data->tbo_type	 = $request->tbo_type;
        $data->ata_code	 = $request->ata_code;
        $data->part_number = $request->part_number;
        $data->serial_number = $request->serial_number;
        $data->tbo_requirement = $request->tbo_requirement;
        $data->fitting_date = date('Y-m-d',strtotime($request->fitting_date));
        $data->status = $request->status;
        $data->save();
        return redirect()->route('user.tbo')->with('success','Update TBO successfully');
    }
    public function status(Request $request)
    {
        try {
            $id = $request->id;
            $status = $request->status;
            $user = Tbo::find($id);
            $user->status = $status;
            $user->save();
            return response()->json(['success' => true, 'message' => 'Status updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    public function destroy($id)
    {
        $data = Tbo::find($id);
        $data->is_delete='1';
        $data->status='inactive';
        $data->save();
        return response()->json([
            'success' => true,
            'message' => 'TBO Deleted Successfully'
        ]);
    }

}
