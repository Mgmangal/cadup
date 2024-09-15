<?php

namespace App\Http\Controllers\ThemeOne;


use App\Models\Ata;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AtaController extends Controller
{
    public function __construct()
    {
        
    }
    public function category()
    {
        $sub_title = 'Ata Category List';
        return view('theme-one.ata.category', compact('sub_title'));
    }

    public function category_store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|unique:atas,name',
            'status' => 'required',
        ],[
            'name.required' => 'Please Enter Name',
            'name.unique' => 'Name Already Exists',
            'status.required' => 'Please Select Status',
        ]);
        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validation->errors()
            ]);
        }
        $name = $request->name;
        $description = $request->description;
        $status = $request->status;
        $id = $request->edit_id;
        try {
            if (!empty($id)) {
                // return $id; die;
                $master = Ata::find($id);
                $master->name = $name;
                $master->description = $description;
                $master->status = $status;
                $master->save();
                $message = 'Ata Category Updated Successfully';
            } else {
            // return 'hallo'; die;
                $master = new Ata();
                $master->name = $name;
                $master->description = $description;
                $master->status = $status;
                $master->save();
                $message = 'Ata Category Added Successfully';
            }
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function category_list(Request $request)
    {
        $column = ['id', 'name', 'description', 'created_at', 'status', 'id'];
        $masters = Ata::whereNull('parent_id')->where('is_delete', '0');

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
            if (auth()->user()->can('ATA Category Edit')) {
                $action = '<a href="javascript:void(0);" onclick="editRole(`' . route('user.ata.category_edit', $value->id) . '`);" class="btn btn-warning btn-sm m-1">Edit</a>';
            }
            if (auth()->user()->can('ATA Category Delete')) {
                $action .= '<a href="javascript:void(0);" onclick="deleted(`' . route('user.ata.category_destroy', $value->id) . '`);" class="btn btn-danger btn-sm m-1">Delete</a>';
            }
            $status = '<select class="form-control" onchange="changeStatus(' . $value->id . ',this.value);">';
            $status .= '<option ' . ($value->status == 'active' ? 'selected' : '') . ' value="active">Active</option>';
            $status .= '<option ' . ($value->status == 'inactive' ? 'selected' : '') . ' value="inactive">Inactive</option>';
            $status .= '</select>';
            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = $value->name;
            $sub_array[] = $value->description;
            $sub_array[] = date('d-m-Y', strtotime($value->created_at));
            $sub_array[] = $status;
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

    public function category_edit($id)
    {
        $role = Ata::find($id);
        return response()->json([
            'success' => true,
            'data' => $role
        ]);
    }

    public function category_destroy($id)
    {
        $data = Ata::find($id);
        $data->is_delete='1';
        $data->status='inactive';
        $data->save();
        return response()->json([
            'success' => true,
            'message' => 'Ata Category Deleted Successfully'
        ]);
    }

    public function ata()
    {
        $sub_title = 'List';
        $ata_categories = Ata::whereNull('parent_id')->where('is_delete', '0')->get();
        return view('theme-one.ata.index', compact('sub_title','ata_categories'));
    }
    public function ata_store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'category_id' => 'required',
            'code' => 'required|unique:atas,code',
            'name' => 'required',
        ],[
            'category_id.required' => 'Please Select ATA Category',
            'code.required' => 'Please Enter ATA Code',
            'code.unique' => 'ATA Code Already Exist',
            'name.required' => 'Please Enter Chapter name',
        ]);
        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validation->errors()
            ]);
        }
        $code = $request->code;
        $name = $request->name;
        $category_id = $request->category_id;
        // $description = $request->description;
        $status = $request->status;
        $id = $request->edit_id;
        try {
            if (!empty($id)) {
                $master = Ata::find($id);
                $master->name = $name;
                $master->code = $code;
                $master->parent_id = $category_id;
                // $master->description = $description;
                $master->status = $status;
                $master->save();
                $massage = 'ATA Updated Successfully';
            } else {
                $master = new Ata();
                $master->parent_id = $category_id;
                $master->code = $code;
                $master->name = $name;
                // $master->description = $description;
                $master->status = $status;
                $master->save();
                $massage = 'ATA Added Successfully';
            }
            return response()->json([
                'success' => true,
                'message' => $massage
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    public function ata_list(Request $request)
    {
        $column = ['id', 'code', 'name','parent_id', 'created_at','status', 'id'];
        $masters = Ata::whereNotNull('parent_id')->where('is_delete', '0');

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
            if (auth()->user()->can('ATA Edit')) {
                $action .= '<a href="javascript:void(0);" onclick="editRole(`' . route('user.ata.ata_edit', $value->id) . '`);" class="btn btn-warning btn-sm m-1 text-white">Edit</a>';
            }
            if (auth()->user()->can('ATA Delete')) {
                $action .= '<a href="javascript:void(0);" onclick="deleted(`' . route('user.ata.ata_destroy', $value->id) . '`);" class="btn btn-danger btn-sm m-1">Delete</a>';
            }
            $status = '<select class="form-control" onchange="changeStatus(' . $value->id . ',this.value);">';
            $status .= '<option ' . ($value->status == 'active' ? 'selected' : '') . ' value="active">Active</option>';
            $status .= '<option ' . ($value->status == 'inactive' ? 'selected' : '') . ' value="inactive">Inactive</option>';
            $status .= '</select>';

            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = $value->code;;
            $sub_array[] = $value->name;
            $sub_array[] = getValueByColumn('atas','name',$value->parent_id);
            // $sub_array[] = $value->description;
            $sub_array[] = date('d-m-Y', strtotime($value->created_at));
            $sub_array[] = $status;
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
    public function ata_edit($id)
    {
        $role = Ata::find($id);
        return response()->json([
            'success' => true,
            'data' => $role
        ]);
    }
    public function ata_status(Request $request)
    {
        try {
            $id = $request->id;
            $status = $request->status;
            $user = Ata::find($id);
            $user->status = $status;
            $user->save();
            return response()->json(['success' => true, 'message' => 'Status updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    public function ata_destroy($id)
    {
        $data = Ata::find($id);
        $data->is_delete = '1';
        $data->status = 'inactive';
        $data->save();
        return response()->json([
            'success' => true,
            'message' => 'ATA Deleted Successfully'
        ]);
    }
}
