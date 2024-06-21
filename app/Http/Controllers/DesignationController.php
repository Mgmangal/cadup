<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Master;
use Illuminate\Support\Facades\Validator;

class DesignationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:Designation Add|Designation Edit|Designation Delete|Designation View']);
    }

    public function index()
    {
        return view('settings.designation.index');
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validation->errors()
            ]);
        }
        $name = $request->name;
        $id = $request->edit_id;
        try {
            if (!empty($id)) {
                $master = Master::find($id);
                $master->name = $name;
                $master->save();
            } else {
                $master = new Master();
                $master->name = $name;
                $master->type = 'designation';
                $master->save();
            }
            return response()->json([
                'success' => true,
                'message' => 'Designation Added Successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function list(Request $request)
    {
        $column = ['id', 'name', 'created_at', 'id'];
        $masters = Master::where('type', '=', 'designation')->where('is_delete','0');

        $total_row = $masters->count();
        if (isset($_POST['search'])) {
            $masters->where('name', 'LIKE', '%' . $_POST['search']['value'] . '%');
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
            if (auth()->user()->can('Designation Edit')) {
                $action = '<a href="javascript:void(0);" onclick="editRole(`' . route('app.settings.designations.edit', $value->id) . '`);" class="btn btn-warning btn-sm m-1">Edit</a>';
                $action .= '<a href="javascript:void(0);" onclick="license(`' . $value->id . '`);" class="btn btn-success btn-sm m-1">License</a>';
                $action .= '<a href="javascript:void(0);" onclick="mapLeave(`' . $value->id . '`);" class="btn btn-success btn-sm m-1">Assign Leave</a>';
            }
            // $action = '<a href="javascript:void(0);" onclick="editRole(`'.route('app.settings.designations.edit', $value->id).'`);" class="btn btn-warning btn-sm m-1">Edit</a>';
            if (auth()->user()->can('Designation Delete')) {
                $action .= '<a href="javascript:void(0);" onclick="deleted(`' . route('app.settings.designations.destroy', $value->id) . '`);" class="btn btn-danger btn-sm m-1">Delete</a>';
            }
            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = $value->name;
            $sub_array[] = date('d-m-Y', strtotime($value->created_at));
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
        $role = Master::find($id);
        return response()->json([
            'success' => true,
            'data' => $role
        ]);
    }
    public function destroy($id)
    {
        $data = Master::find($id);
        $data->is_delete='1';
        $data->status='inactive';
        $data->save();
        return response()->json([
            'success' => true,
            'message' => 'Designation Deleted Successfully'
        ]);
    }
}
