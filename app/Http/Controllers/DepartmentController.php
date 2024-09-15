<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Master;

class DepartmentController extends Controller
{
    public function __construct()
    {
        //$this->middleware(['permission:Department Add|Department Edit|Department Delete|Department View']);
    }

    public function index()
    {
        return view('settings.department.index');
    }

    public function store(Request $request)
    {
        $name=$request->name;
        $id=$request->edit_id;
        if(empty($name)){
            return response()->json([
                'success'=>false,
                'message'=>'Department Name is required'
            ]);
        }
        try{
            if(!empty($id)){
                $master=Master::find($id);
                $master->name=$name;
                $master->save();
            }else{
                $master=new Master();
                $master->name=$name;
                $master->type='department';
                $master->status='active';
                $master->is_delete='0';
                $master->save();
            }
            return response()->json([
                'success'=>true,
                'message'=>'Department Added Successfully'
            ]);
        }catch (\Exception $e){
            return response()->json([
                'success'=>false,
                'message'=>$e->getMessage()
            ]);
        }
    }

    public function list(Request $request)
    {
        $column=['id','name','created_at','id'];
        $masters=Master::where('type','=','department')->where('is_delete','0');

        $total_row=$masters->count();
        if (isset($_POST['search'])) {
            $masters->where('name', 'LIKE', '%' . $_POST['search']['value'] . '%');
		}

		if (isset($_POST['order'])) {
            $masters->orderBy($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else {
            $masters->orderBy('id', 'desc');
        }
		$filter_row =$masters->count();
        if (isset($_POST["length"]) && $_POST["length"] != -1) {
            $masters->skip($_POST["start"])->take($_POST["length"]);
		}
        $result =$masters->get();
        $data = array();
		foreach ($result as $key => $value) {
            
            $action = '';
           
            $action .= '<a href="javascript:void(0);" onclick="editRole(`'.route('app.settings.departments.edit', $value->id).'`);" class="btn btn-warning btn-sm m-1">Edit</a>';
           
            // $action = '<a href="javascript:void(0);" onclick="editRole(`'.route('app.settings.departments.edit', $value->id).'`);" class="btn btn-warning btn-sm m-1">Edit</a>';
           
            $action .= '<a href="javascript:void(0);" onclick="deleted(`'.route('app.settings.departments.destroy', $value->id).'`);" class="btn btn-danger btn-sm m-1">Delete</a>';
            
            // $action .= '<a href="javascript:void(0);" onclick="deleted(`'.route('app.settings.departments.destroy', $value->id).'`);" class="btn btn-danger btn-sm m-1">Delete</a>';
            $sub_array = array();
			$sub_array[] = ++$key;
            $sub_array[] = $value->name;
            $sub_array[] = date('d-m-Y',strtotime($value->created_at));
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
        $role=Master::find($id);
        return response()->json([
            'success'=>true,
            'data'=>$role
        ]);
    }
    public function destroy($id)
    {
        $data=Master::find($id);
        $data->is_delete='1';
        $data->status='inactive';
        $data->save();
        return response()->json([
            'success'=>true,
            'message'=>'Master Deleted Successfully'
        ]);
    }

}