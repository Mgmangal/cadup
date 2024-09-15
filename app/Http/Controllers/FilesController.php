<?php

namespace App\Http\Controllers;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FilesController extends Controller
{
    public function index()
    {
        return view('files.index');
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'section' => 'required',
        ]);
        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validation->errors()
            ]);
        }
        $id = $request->edit_id;
        try {
            if (!empty($id)) {
                $modal = File::find($id);
                $modal->name = $request->name;
                $modal->number = strtoupper($request->number);
                $modal->section = $request->section;
                $modal->status = $request->status;
                $modal->save();
                $massage = 'File Updated Successfully';
            } else {
                $modal = new File();
                $modal->name = $request->name;
                $modal->number = strtoupper($request->number);
                $modal->section = $request->section;
                $modal->status = $request->status;
                $modal->is_delete = '0';
                $modal->save();
                $massage = 'File Added Successfully';
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

    public function list(Request $request)
    {
        $column = ['id', 'name','number','section', 'status','created_at', 'id'];
        $model = File::where('is_delete', '0');

        $total_row = $model->count();
        if (isset($_POST['search'])) {
            $searchValue = $_POST['search']['value'];

            $model->where(function ($query) use ($searchValue) {
                $query->where('name', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('number', 'LIKE', '%' . $searchValue . '%');
            });
        }


        if (isset($_POST['order'])) {
            $model->orderBy($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $model->orderBy('id', 'desc');
        }
        $filter_row = $model->count();
        if (isset($_POST["length"]) && $_POST["length"] != -1) {
            $model->skip($_POST["start"])->take($_POST["length"]);
        }
        $result = $model->get();
        $data = array();
        foreach ($result as $key => $value) {

            $action = '';
            $action = '<a href="javascript:void(0);" onclick="edit(`' . route('app.file.edit', $value->id) . '`);" class="btn btn-warning btn-sm m-1">Edit</a>';
            $action .= '<a href="javascript:void(0);" onclick="deleted(`' . route('app.file.delete', $value->id) . '`);" class="btn btn-danger btn-sm m-1">Delete</a>';

            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = $value->name;
            $sub_array[] = $value->number;
            $sub_array[] = getMasterName($value->section);
            $sub_array[] = ucfirst($value->status);
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
        $model = File::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $model
        ]);
    }

    public function delete($id)
    {
        $model = File::findOrFail($id);
        $model->is_delete = '1';
        $model->status='inactive';
        $model->save();
        return response()->json([
            'success' => true,
            'message' => 'File Deleted Successfully'
        ]);
    }

}
