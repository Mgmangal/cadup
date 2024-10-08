<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Master;
use Illuminate\Support\Facades\Validator;

class JobFunctionController extends Controller
{
    public function __construct()
    {
        //$this->middleware(['permission:Job Function Add|Job Function Edit|Job Function Delete|Job Function View']);
    }
    public function index()
    {
        $sections = Master::where('type', 'section')->get();
        return view('settings.job_function.index', compact('sections'));
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'section_id' => 'required',
            'name' => 'required',
        ]);
        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validation->errors()
            ]);
        }
        $name = $request->name;
        $section_id = $request->section_id;
        $id = $request->edit_id;
        try {
            if (!empty($id)) {
                $master = Master::find($id);
                $master->name = $name;
                $master->parent_id = $section_id;
                $master->save();
            } else {
                $master = new Master();
                $master->name = $name;
                $master->type = 'job_function';
                $master->parent_id = $section_id;
                $master->status='active';
                $master->is_delete='0';
                $master->save();
            }
            return response()->json([
                'success' => true,
                'message' => 'Job Function Added Successfully'
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
        $column = ['id', 'parent_id', 'name', 'created_at', 'id'];
        $masters = Master::where('type', '=', 'job_function')->where('is_delete','0');

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
            
            $action .= '<a href="javascript:void(0);" onclick="editRole(`' . route('app.settings.jobfunctions.edit', $value->id) . '`);" class="btn btn-warning btn-sm m-1">Edit</a>';
            $action .= '<a href="javascript:void(0);" onclick="license(`' . $value->id . '`);" class="btn btn-success btn-sm m-1">License</a>';
            
            $action .= '<a href="javascript:void(0);" onclick="deleted(`' . route('app.settings.jobfunctions.destroy', $value->id) . '`);" class="btn btn-danger btn-sm m-1">Delete</a>';
           

            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = Master::find($value->parent_id)->name;
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
            'message' => 'Job Function Deleted Successfully'
        ]);
    }
}
