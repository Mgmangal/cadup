<?php

namespace App\Http\Controllers;

use App\Models\CvrFdr;
use App\Models\AirCraft;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CvrFdrController extends Controller
{
    public function cvr()
    {
        $aircrafts = AirCraft::where('status', 'active')->where('is_delete', '0')->get();
        return view('cvr_fdr.cvr', compact('aircrafts'));
    }
    public function store_cvr(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'aircraft_id'  => 'required',
            'receive_date'  => 'required',
            'read_out_date' => 'required',
            'analyzed_date' => 'required',

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
                $master = CvrFdr::find($id);
                $message = 'CVR Updated Successfully';
            } else {
                $master = new CvrFdr();
                $message = 'CVR Added Successfully';
            }
            $master->type = 'cvr';
            $master->aircraft_id = $request->aircraft_id;
            $master->receive_date = $request->receive_date;
            $master->read_out_date = $request->read_out_date;
            $master->analyzed_date = $request->analyzed_date;
            $master->cfs_verified_status = $request->cfs_verified_status;
            $master->save();

            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function list_cvr(Request $request)
    {
        $column = ['id', 'aircraft_id', 'receive_date', 'read_out_date', 'analyzed_date','cfs_verified_status', 'created_at', 'id'];
        $masters = CvrFdr::where('type', 'cvr')->where('status', 'active')->where('is_delete','0');

        $total_row = $masters->count();
        if (isset($_POST['search'])) {
            $masters->where('aircraft_id', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $masters->where('receive_date', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $masters->where('read_out_date', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $masters->where('analyzed_date', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $masters->where('cfs_verified_status', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $masters->where('created_at', 'LIKE', '%' . $_POST['search']['value'] . '%');
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

            $action = '<a href="javascript:void(0);" onclick="editRole(`' . route('app.cvr.edit', $value->id) . '`);" class="btn btn-warning btn-sm m-1">Edit</a>';
            $action .= '<a href="javascript:void(0);" onclick="deleted(`' . route('app.cvr.destroy', $value->id) . '`);" class="btn btn-danger btn-sm m-1">Delete</a>';

            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = $value->aircraft->call_sign;
            $sub_array[] = $value->receive_date;
            $sub_array[] = $value->read_out_date;
            $sub_array[] = $value->analyzed_date;
            $sub_array[] = ucwords($value->cfs_verified_status);
            $sub_array[] = date('d-m-Y', strtotime($value->created_at));
            $sub_array[] = ucwords($value->status);
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

    public function edit_cvr($id)
    {
        $role = CvrFdr::find($id);
        return response()->json([
            'success' => true,
            'data' => $role
        ]);
    }
    public function destroy_cvr($id)
    {
        $data = CvrFdr::find($id);
        $data->is_delete='1';
        $data->status='inactive';
        $data->save();
        return response()->json([
            'success' => true,
            'message' => 'CVR Deleted Successfully'
        ]);
    }
    public function fdr()
    {
        $aircrafts = AirCraft::where('status', 'active')->where('is_delete', '0')->get();
        return view('cvr_fdr.fdr', compact('aircrafts'));
    }
    public function store_fdr(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'aircraft_id'  => 'required',
            'receive_date'  => 'required',
            'read_out_date' => 'required',
            'analyzed_date' => 'required',

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
                $master = CvrFdr::find($id);
                $message = 'FDR Updated Successfully';
            } else {
                $master = new CvrFdr();
                $message = 'FDR Added Successfully';
            }
            $master->type = 'fdr';
            $master->aircraft_id = $request->aircraft_id;
            $master->receive_date = $request->receive_date;
            $master->read_out_date = $request->read_out_date;
            $master->analyzed_date = $request->analyzed_date;
            $master->cfs_verified_status = $request->cfs_verified_status;
            $master->save();

            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function list_fdr(Request $request)
    {
        $column = ['id', 'aircraft_id', 'receive_date', 'read_out_date', 'analyzed_date','cfs_verified_status', 'created_at', 'id'];
        $masters = CvrFdr::where('type', 'fdr')->where('status', 'active')->where('is_delete','0');

        $total_row = $masters->count();
        if (isset($_POST['search'])) {
            $masters->where('aircraft_id', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $masters->where('receive_date', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $masters->where('read_out_date', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $masters->where('analyzed_date', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $masters->where('cfs_verified_status', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $masters->where('created_at', 'LIKE', '%' . $_POST['search']['value'] . '%');
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

            $action = '<a href="javascript:void(0);" onclick="editRole(`' . route('app.fdr.edit', $value->id) . '`);" class="btn btn-warning btn-sm m-1">Edit</a>';
            $action .= '<a href="javascript:void(0);" onclick="deleted(`' . route('app.fdr.destroy', $value->id) . '`);" class="btn btn-danger btn-sm m-1">Delete</a>';

            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = $value->aircraft->call_sign;
            $sub_array[] = $value->receive_date;
            $sub_array[] = $value->read_out_date;
            $sub_array[] = $value->analyzed_date;
            $sub_array[] = ucwords($value->cfs_verified_status);
            $sub_array[] = date('d-m-Y', strtotime($value->created_at));
            $sub_array[] = ucwords($value->status);
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

    public function edit_fdr($id)
    {
        $role = CvrFdr::find($id);
        return response()->json([
            'success' => true,
            'data' => $role
        ]);
    }
    public function destroy_fdr($id)
    {
        $data = CvrFdr::find($id);
        $data->is_delete='1';
        $data->status='inactive';
        $data->save();
        return response()->json([
            'success' => true,
            'message' => 'FDR Deleted Successfully'
        ]);
    }
}
