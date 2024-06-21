<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
{
    public function index()
    {
        $states = State::where('country_id', '=', '101')->get();
        return view('settings.cities', compact('states'));
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
        $state_id = $request->state_id;
        $id = $request->edit_id;
        try {
            if (!empty($id)) {
                $model = City::find($id);
                $model->name = $name;
                $model->state_id;
                $model->save();
            } else {
                $model = new City();
                $model->name = $name;
                $model->state_id = $state_id;
                $model->save();
            }
            return response()->json([
                'success' => true,
                'message' => 'City Added Successfully'
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
        $column = ['id', 'name', 'state_id', 'created_at', 'id'];
        $model = City::where('is_delete', '=', '0');

        $total_row = $model->count();
        if (isset($_POST['search'])) {
            $model->where('name', 'LIKE', '%' . $_POST['search']['value'] . '%');
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
            $action = '<a href="javascript:void(0);" onclick="edit(`' . route('app.cities.edit', $value->id) . '`);" class="btn btn-warning btn-sm m-1">Edit</a>';
            // $action .= '<a href="javascript:void(0);" onclick="deleted(`' . route('app.cities.delete', $value->id) . '`);" class="btn btn-danger btn-sm m-1">Delete</a>';

            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = $value->name;
            $sub_array[] = getStateName($value->state_id);
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
        $model = City::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $model
        ]);
    }

    public function delete($id)
    {
        $model = City::findOrFail($id);
        $model->is_delete = '1';
        $model->save();
        return response()->json([
            'success' => true,
            'message' => 'City Deleted Successfully'
        ]);
    }
}
