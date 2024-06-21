<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Master;
use App\Models\SfaRate;
use Illuminate\Support\Facades\Validator;
class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:Setting Edit']);
    }
    
    public function index(){
        $setting=Setting::first();
        return view('settings.index',compact('setting'));
    }
    
    
    public function passengerIndex()
    {
        return view('settings.passenger');
    }

    public function passengerStore(Request $request)
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
        $more_data = $request->more_data;
        $id = $request->edit_id;
        try {
            if (!empty($id)) {
                $master = Master::find($id);
                $master->name = $name;
                $master->more_data = !empty($more_data)?trim($more_data):null;
                $master->save();
            } else {
                $master = new Master();
                $master->name = $name;
                $master->more_data = !empty($more_data)?trim($more_data):null;
                $master->type = 'passenger';
                $master->status = 'active';
                $master->is_delete = '0';
                $master->save();
            }
            return response()->json([
                'success' => true,
                'message' => 'Passenger Submited Successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function passengerList(Request $request)
    {
        $column = ['id', 'name','more_data', 'created_at', 'id'];
        $masters = Master::where('type', '=', 'passenger')->where('is_delete','0');

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
            $action = '<a href="javascript:void(0);" onclick="editRole(`' . route('app.settings.passenger.edit', $value->id) . '`);" class="btn btn-warning btn-sm m-1">Edit</a>';
            $action .= '<a href="javascript:void(0);" onclick="deleted(`' . route('app.settings.passenger.destroy', $value->id) . '`);" class="btn btn-danger btn-sm m-1">Delete</a>';
            
            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = $value->name;
            $sub_array[] = $value->more_data;
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

    public function passengerEdit($id)
    {
        $role = Master::find($id);
        return response()->json([
            'success' => true,
            'data' => $role
        ]);
    }
    
    public function passengerDestroy($id)
    {
        $data = Master::find($id);
        $data->is_delete='1';
        $data->status='inactive';
        $data->save();
        return response()->json([
            'success' => true,
            'message' => 'Passenger Deleted Successfully'
        ]);
    }
    
    public function sfarate()
    {
       
        return view('settings.sfa-rate');
    }
    
    public function sfarateStore(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'apply_date' => 'required',
            'fixed_wing_rate' => 'required',
            'roator_wing_rate' => 'required',
        ]);
        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validation->errors()
            ]);
        }
        $apply_date = $request->apply_date;
        $fixed_wing_rate = $request->fixed_wing_rate;
        $roator_wing_rate = $request->roator_wing_rate;
        $end_date = $request->end_date;
        $id = $request->edit_id;
        try {
            if (!empty($id)) {
                $master = SfaRate::find($id);
            } else {
                $master = new SfaRate();
            }
            $master->apply_date=is_set_date_format($request->apply_date);
            $master->fixed_wing_rate=$request->fixed_wing_rate;
            $master->roator_wing_rate=$request->roator_wing_rate;
            $master->end_date=is_set_date_format($request->end_date);
            $master->save();
            return response()->json([
                'success' => true,
                'message' => 'Flying Type Added Successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        return redirect()->back()->with('success','Settings updated successfully');
    }
    
    public function sfarateList(Request $request)
    {
        $column = ['id', 'apply_date', 'fixed_wing_rate','roator_wing_rate','end_date', 'id'];
        $masters = SfaRate::where('is_delete','0');

        $total_row = $masters->count();
        if (!empty($_POST['search']['value'])) {
            $masters->where('fixed_wing_rate', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $masters->orWhere('roator_wing_rate', 'LIKE', '%' . $_POST['search']['value'] . '%');
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
            $action = '<a href="javascript:void(0);" onclick="editRole(`' . route('app.settings.sfarate.edit', $value->id) . '`);" class="btn btn-warning btn-sm m-1">Edit</a>';
            $action .= '<a href="javascript:void(0);" onclick="deleted(`' . route('app.settings.sfarate.destroy', $value->id) . '`);" class="btn btn-danger btn-sm m-1">Delete</a>';
            
            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = is_get_date_format($value->apply_date);
            $sub_array[] = $value->fixed_wing_rate;
            $sub_array[] = $value->roator_wing_rate;
            $sub_array[] = is_get_date_format($value->end_date);
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
    
    public function sfarateEdit($id)
    {
        $role = SfaRate::find($id);
        return response()->json([
            'success' => true,
            'data' => $role
        ]);
    }
    
    public function sfarateDestroy($id)
    {
        $data = SfaRate::find($id);
        $data->is_delete='1';
        $data->save();
        return response()->json([
            'success' => true,
            'message' => 'Data Deleted Successfully'
        ]);
    }


    public function update(Request $request){
        $setting=Setting::first();
        if($request->hasFile('logo')){
            $file = $request->file('logo');
            $filename = 'logo-'.time() . '.' . $file->getClientOriginalExtension();
            $file->move('public/uploads/', $filename);
            if($setting->app_logo){
                @unlink('public/uploads/'.$setting->app_logo);
            }
            $setting->app_logo = $filename;
        }
        if($request->hasFile('favicon')){
            $file = $request->file('favicon');
            $filename = 'favicon-'.time() . '.' . $file->getClientOriginalExtension();
            $file->move('public/uploads/', $filename);
            if($setting->app_favicon){
                @unlink('public/uploads/'.$setting->app_favicon);
            }
            $setting->app_favicon = $filename;
        }
        $setting->app_name=$request->app_name;
        $setting->app_phone=$request->app_phone;
        $setting->app_email=$request->app_email;
        $setting->app_address=$request->app_address;
        $setting->app_copyright=$request->app_copyright;
        $setting->app_timezone=$request->app_timezone;
        $setting->save();
        return redirect()->back()->with('success','Settings updated successfully');
    }
    
    public function flyingtypeIndex()
    {
        return view('settings.flying-type');
    }

    public function flyingtypeStore(Request $request)
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
                $master->type = 'flying_type';
                $master->status = 'active';
                $master->is_delete = '0';
                $master->save();
            }
            return response()->json([
                'success' => true,
                'message' => 'Flying Type Added Successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function flyingtypeList(Request $request)
    {
        $column = ['id', 'name', 'created_at', 'id'];
        $masters = Master::where('type', '=', 'flying_type')->where('is_delete','0');

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
            $action = '<a href="javascript:void(0);" onclick="editRole(`' . route('app.settings.flyingtype.edit', $value->id) . '`);" class="btn btn-warning btn-sm m-1">Edit</a>';
            //$action .= '<a href="javascript:void(0);" onclick="license(`' . $value->id . '`);" class="btn btn-success btn-sm m-1">License</a>';
            // $action = '<a href="javascript:void(0);" onclick="editRole(`'.route('app.settings.designations.edit', $value->id).'`);" class="btn btn-warning btn-sm m-1">Edit</a>';
            $action .= '<a href="javascript:void(0);" onclick="deleted(`' . route('app.settings.flyingtype.destroy', $value->id) . '`);" class="btn btn-danger btn-sm m-1">Delete</a>';
            
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

    public function flyingtypeEdit($id)
    {
        $role = Master::find($id);
        return response()->json([
            'success' => true,
            'data' => $role
        ]);
    }
    
    public function flyingtypeDestroy($id)
    {
        $data = Master::find($id);
        $data->is_delete='1';
        $data->status='inactive';
        $data->save();
        return response()->json([
            'success' => true,
            'message' => 'Flying Type Deleted Successfully'
        ]);
    }
    
    public function pilotroleIndex()
    {
        return view('settings.pilot-role');
    }

    public function pilotroleStore(Request $request)
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
                $master->type = 'pilot_role';
                $master->status = 'active';
                $master->is_delete = '0';
                $master->save();
            }
            return response()->json([
                'success' => true,
                'message' => 'Pilot Role Added Successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function pilotroleList(Request $request)
    {
        $column = ['id', 'name', 'created_at', 'id'];
        $masters = Master::where('type', '=', 'pilot_role')->where('is_delete','0');

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
            $action = '<a href="javascript:void(0);" onclick="editRole(`' . route('app.settings.pilotrole.edit', $value->id) . '`);" class="btn btn-warning btn-sm m-1">Edit</a>';
            //$action .= '<a href="javascript:void(0);" onclick="license(`' . $value->id . '`);" class="btn btn-success btn-sm m-1">License</a>';
            // $action = '<a href="javascript:void(0);" onclick="editRole(`'.route('app.settings.designations.edit', $value->id).'`);" class="btn btn-warning btn-sm m-1">Edit</a>';
            $action .= '<a href="javascript:void(0);" onclick="deleted(`' . route('app.settings.pilotrole.destroy', $value->id) . '`);" class="btn btn-danger btn-sm m-1">Delete</a>';
            
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

    public function pilotroleEdit($id)
    {
        $role = Master::find($id);
        return response()->json([
            'success' => true,
            'data' => $role
        ]);
    }
    
    public function pilotroleDestroy($id)
    {
        $data = Master::find($id);
        $data->is_delete='1';
        $data->status='inactive';
        $data->save();
        return response()->json([
            'success' => true,
            'message' => 'Pilot Role Deleted Successfully'
        ]);
    }
    
    public function sectors()
    {
        return view('settings.sectors');
    }

    public function sectorsStore(Request $request)
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
                $master->type = 'sectors';
                $master->status = 'active';
                $master->is_delete = '0';
                $master->save();
            }
            return response()->json([
                'success' => true,
                'message' => 'Sector Added Successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function sectorsList(Request $request)
    {
        $column = ['id', 'name', 'created_at', 'id'];
        $masters = Master::where('type', '=', 'sectors')->where('is_delete','0');

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
            $action = '<a href="javascript:void(0);" onclick="editRole(`' . route('app.settings.sectors.edit', $value->id) . '`);" class="btn btn-warning btn-sm m-1">Edit</a>';
            //$action .= '<a href="javascript:void(0);" onclick="license(`' . $value->id . '`);" class="btn btn-success btn-sm m-1">License</a>';
            // $action = '<a href="javascript:void(0);" onclick="editRole(`'.route('app.settings.designations.edit', $value->id).'`);" class="btn btn-warning btn-sm m-1">Edit</a>';
            $action .= '<a href="javascript:void(0);" onclick="deleted(`' . route('app.settings.sectors.destroy', $value->id) . '`);" class="btn btn-danger btn-sm m-1">Delete</a>';
            
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

    public function sectorsEdit($id)
    {
        $role = Master::find($id);
        return response()->json([
            'success' => true,
            'data' => $role
        ]);
    }
    
    public function sectorsDestroy($id)
    {
        $data = Master::find($id);
        $data->is_delete='1';
        $data->status='inactive';
        $data->save();
        return response()->json([
            'success' => true,
            'message' => 'Sector Deleted Successfully'
        ]);
    }
    
    public function aircraftTypeIndex()
    {
        return view('settings.aircraft-type');
    }

    public function aircraftTypeStore(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'aircraft_cateogry' => 'required',
        ]);
        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validation->errors()
            ]);
        }
        $name = $request->name;
        $aircraft_cateogry = $request->aircraft_cateogry;
        $id = $request->edit_id;
        try {
            if (!empty($id)) {
                $master = Master::find($id);
                $master->name = $name;
                $master->more_data = $aircraft_cateogry;
                $master->save();
            } else {
                $master = new Master();
                $master->name = $name;
                $master->more_data = $aircraft_cateogry;
                $master->type = 'aircraft_type';
                $master->status = 'active';
                $master->is_delete = '0';
                $master->save();
            }
            return response()->json([
                'success' => true,
                'message' => 'Aircraft Type Added Successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function aircraftTypeList(Request $request)
    {
        $column = ['id', 'name','more_data', 'created_at', 'id'];
        $masters = Master::where('type', '=', 'aircraft_type')->where('is_delete','0');

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
            $action = '<a href="javascript:void(0);" onclick="editRole(`' . route('app.settings.aircraftType.edit', $value->id) . '`);" class="btn btn-warning btn-sm m-1">Edit</a>';
            //$action .= '<a href="javascript:void(0);" onclick="license(`' . $value->id . '`);" class="btn btn-success btn-sm m-1">License</a>';
            // $action = '<a href="javascript:void(0);" onclick="editRole(`'.route('app.settings.designations.edit', $value->id).'`);" class="btn btn-warning btn-sm m-1">Edit</a>';
            $action .= '<a href="javascript:void(0);" onclick="deleted(`' . route('app.settings.aircraftType.destroy', $value->id) . '`);" class="btn btn-danger btn-sm m-1">Delete</a>';
            
            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = $value->name;
            $sub_array[] = $value->more_data;
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

    public function aircraftTypeEdit($id)
    {
        $role = Master::find($id);
        return response()->json([
            'success' => true,
            'data' => $role
        ]);
    }
    
    public function aircraftTypeDestroy($id)
    {
        $data = Master::find($id);
        $data->is_delete='1';
        $data->status='inactive';
        $data->save();
        return response()->json([
            'success' => true,
            'message' => 'Aircraft Type Deleted Successfully'
        ]);
    }
    
    public function expensesType()
    {
        return view('settings.expenses-type');
    }
    
    public function expensesTypeStore(Request $request)
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
                $master->status = $request->status;
                $master->save();
                $massage = 'Expenses Type Updated Successfully';
            } else {
                $master = new Master();
                $master->name = $name;
                $master->type = 'expenses_type';
                $master->status = $request->status;
                $master->is_delete = '0';
                $master->save();
                $massage = 'Expenses Type Added Successfully';
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

    public function expensesTypeList(Request $request)
    {
        $column = ['id', 'name', 'created_at', 'id'];
        $model = Master::where('type', 'expenses_type')->where('status', 'active')->where('is_delete', '0');

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
            $action = '<a href="javascript:void(0);" onclick="edit(`' . route('app.settings.expensesTypeEdit', $value->id) . '`);" class="btn btn-warning btn-sm m-1">Edit</a>';
            $action .= '<a href="javascript:void(0);" onclick="deleted(`' . route('app.settings.expensesTypeDelete', $value->id) . '`);" class="btn btn-danger btn-sm m-1">Delete</a>';
            if ($value->status == 'active') {
                $status = ucfirst($value->status);
            } else {
                $status = ucfirst($value->status);
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

    public function expensesTypeEdit($id)
    {
        $model = Master::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $model
        ]);
    }

    public function expensesTypeDelete($id)
    {
        $model = Master::findOrFail($id);
        $model->is_delete = '1';
        $model->status='inactive';
        $model->save();
        return response()->json([
            'success' => true,
            'message' => 'Expenses Type Deleted Successfully'
        ]);
    }

    public function leaveType()
    {
        return view('settings.leave-type');
    }
    
    public function leaveTypeStore(Request $request)
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
                //$master->more_data = $request->more_data;
                $master->status = $request->status;
                $master->save();
                $massage = 'Leave Type Updated Successfully';
            } else {
                $master = new Master();
                $master->name = $name;
                $master->type = 'leave_type';
                //$master->more_data = $request->more_data;
                $master->status = $request->status;
                $master->is_delete = '0';
                $master->save();
                $massage = 'Leave Type Added Successfully';
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

    public function leaveTypeList(Request $request)
    {
        $column = ['id', 'name', 'created_at', 'id'];
        $model = Master::where('type', 'leave_type')->where('status', 'active')->where('is_delete', '0');

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
            $action = '<a href="javascript:void(0);" onclick="edit(`' . route('app.settings.leaveTypeEdit', $value->id) . '`);" class="btn btn-warning btn-sm m-1">Edit</a>';
            $action .= '<a href="javascript:void(0);" onclick="deleted(`' . route('app.settings.leaveTypeDelete', $value->id) . '`);" class="btn btn-danger btn-sm m-1">Delete</a>';
            if ($value->status == 'active') {
                $status = ucfirst($value->status);
            } else {
                $status = ucfirst($value->status);
            }


            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = $value->name;
            //$sub_array[] = $value->more_data;
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

    public function leaveTypeEdit($id)
    {
        $model = Master::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $model
        ]);
    }

    public function leaveTypeDelete($id)
    {
        $model = Master::findOrFail($id);
        $model->is_delete = '1';
        $model->status='inactive';
        $model->save();
        return response()->json([
            'success' => true,
            'message' => 'Leave Type Deleted Successfully'
        ]);
    }
    
    
    public function postFlightDoc()
    {
        return view('settings.post-flight-doc');
    }
    
    public function postFlightDocStore(Request $request)
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
                //$master->more_data = $request->more_data;
                $master->status = $request->status;
                $master->save();
                $massage = 'Leave Type Updated Successfully';
            } else {
                $master = new Master();
                $master->name = $name;
                $master->type = 'post_flight_doc';
                //$master->more_data = $request->more_data;
                $master->status = $request->status;
                $master->is_delete = '0';
                $master->save();
                $massage = 'Post Flight Doc Added Successfully';
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

    public function postFlightDocList(Request $request)
    {
        $column = ['id', 'name', 'created_at', 'id'];
        $model = Master::where('type', 'post_flight_doc')->where('status', 'active')->where('is_delete', '0');

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
            $action = '<a href="javascript:void(0);" onclick="edit(`' . route('app.settings.postFlightDocEdit', $value->id) . '`);" class="btn btn-warning btn-sm m-1">Edit</a>';
            $action .= '<a href="javascript:void(0);" onclick="deleted(`' . route('app.settings.postFlightDocDelete', $value->id) . '`);" class="btn btn-danger btn-sm m-1">Delete</a>';
            if ($value->status == 'active') {
                $status = ucfirst($value->status);
            } else {
                $status = ucfirst($value->status);
            }


            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = $value->name;
            //$sub_array[] = $value->more_data;
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

    public function postFlightDocEdit($id)
    {
        $model = Master::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $model
        ]);
    }

    public function postFlightDocDelete($id)
    {
        $model = Master::findOrFail($id);
        $model->is_delete = '1';
        $model->status='inactive';
        $model->save();
        return response()->json([
            'success' => true,
            'message' => 'Post Flight Doc Deleted Successfully'
        ]);
    }
    
    public function expenditure()
    {
        return view('settings.expenditure');
    }
    
    public function expenditureStore(Request $request)
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
                //$master->more_data = $request->more_data;
                $master->status = $request->status;
                $master->save();
                $massage = 'Expenditure Updated Successfully';
            } else {
                $master = new Master();
                $master->name = $name;
                $master->type = 'expenditure';
                //$master->more_data = $request->more_data;
                $master->status = $request->status;
                $master->is_delete = '0';
                $master->save();
                $massage = 'Expenditure Added Successfully';
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

    public function expenditureList(Request $request)
    {
        $column = ['id', 'name', 'created_at', 'id'];
        $model = Master::where('type', 'expenditure')->where('status', 'active')->where('is_delete', '0');

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
            $action = '<a href="javascript:void(0);" onclick="edit(`' . route('app.settings.expenditureEdit', $value->id) . '`);" class="btn btn-warning btn-sm m-1">Edit</a>';
            $action .= '<a href="javascript:void(0);" onclick="deleted(`' . route('app.settings.expenditureDelete', $value->id) . '`);" class="btn btn-danger btn-sm m-1">Delete</a>';
            if ($value->status == 'active') {
                $status = ucfirst($value->status);
            } else {
                $status = ucfirst($value->status);
            }


            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = $value->name;
            //$sub_array[] = $value->more_data;
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

    public function expenditureEdit($id)
    {
        $model = Master::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $model
        ]);
    }

    public function expenditureDelete($id)
    {
        $model = Master::findOrFail($id);
        $model->is_delete = '1';
        $model->status='inactive';
        $model->save();
        return response()->json([
            'success' => true,
            'message' => 'Expenditure Deleted Successfully'
        ]);
    }

    public function certificate()
    {
        return view('settings.certificate.index');
    }

    public function certificateStore(Request $request)
    {
        $validation=Validator::make($request->all(),[
            'name'=>'required',
        ]);
        if ($validation->fails()) {
            return response()->json([
                'success'=>false,
                'message'=>$validation->errors()
            ]);
        }
        $name=$request->name;
        $sub_type=$request->sub_type;
        $short_name=$request->short_name;
        $id=$request->edit_id;
        $lifetime=$request->is_valid??null;
        try{
            if(!empty($id)){
                $master=Master::find($id);
                $master->name=$name;
                $master->other_data=$short_name;
                $master->sub_type=$sub_type;
                $master->more_data=$lifetime;
                $master->save();
            }else{
                $master=new Master();
                $master->name=$name;
                $master->other_data=$short_name;
                $master->sub_type=$sub_type;
                $master->more_data=$lifetime;
                $master->type='certificate';
                $master->save();
            }
            return response()->json([
                'success'=>true,
                'message'=>'Certificate Added Successfully'
            ]);
        }
        catch (\Exception $e){
            return response()->json([
                'success'=>false,
                'message'=>$e->getMessage()
            ]);
        }
    }

    public function certificateList(Request $request)
    {
        $column=['id','other_data','name','sub_type','more_data','created_at','id'];
        $masters=Master::where('type','=','certificate')->where('is_delete','0');

        $total_row=$masters->count();
        if (isset($_POST['search'])) {
             $searchValue = $_POST['search']['value'];
            $masters->where(function($query) use ($searchValue) {
                $query->where('other_data', 'LIKE', '%' . $searchValue . '%')
                      ->orWhere('name', 'LIKE', '%' . $searchValue . '%')
                      ->orWhere('more_data', 'LIKE', '%' . $searchValue . '%')
                      ->orWhere('type', 'LIKE', '%' . $searchValue . '%');
            });
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
            if(auth()->user()->can('Certificate Edit')){
            $action .= '<a href="javascript:void(0);" onclick="editRole(`'.route('app.settings.certificates.edit', $value->id).'`);" class="btn btn-warning btn-sm m-1">Edit</a>';
            }
            if(auth()->user()->can('Certificate Delete')){
            $action .= '<a href="javascript:void(0);" onclick="deleted(`'.route('app.settings.certificates.destroy', $value->id).'`);" class="btn btn-danger btn-sm m-1">Delete</a>';
            }
            $sub_array = array();
			$sub_array[] = ++$key;
            $sub_array[] = $value->other_data;
            $sub_array[] = $value->name;
            $sub_array[] = ucwords(str_replace('_', ' ', $value->sub_type));
            $sub_array[] = $value->more_data;
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
    public function certificateEdit($id)
    {
        $role=Master::find($id);
        return response()->json([
            'success'=>true,
            'data'=>$role
        ]);
    }
    public function certificateDestroy($id)
    {
        $data=Master::find($id);
        $data->is_delete='1';
        $data->status='inactive';
        $data->save();
        return response()->json([
            'success'=>true,
            'message'=>'Certificate Deleted Successfully'
        ]);
    }

    public function contractType()
    {
        return view('settings.contract-type');
    }

    public function contractTypeStore(Request $request)
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
                $master->type = 'contract_type';
                $master->status = 'active';
                $master->is_delete = '0';
                $master->save();
            }
            return response()->json([
                'success' => true,
                'message' => 'Contract Added Successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function contractTypeList(Request $request)
    {
        $column = ['id', 'name', 'created_at', 'id'];
        $masters = Master::where('type', '=', 'contract_type')->where('is_delete','0');

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
            $action = '<a href="javascript:void(0);" onclick="editRole(`' . route('app.settings.contract.type.edit', $value->id) . '`);" class="btn btn-warning btn-sm m-1">Edit</a>';
            $action .= '<a href="javascript:void(0);" onclick="deleted(`' . route('app.settings.contract.type.destroy', $value->id) . '`);" class="btn btn-danger btn-sm m-1">Delete</a>';
            
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

    public function contractTypeEdit($id)
    {
        $role = Master::find($id);
        return response()->json([
            'success' => true,
            'data' => $role
        ]);
    }
    
    public function contractTypeDestroy($id)
    {
        $data = Master::find($id);
        $data->is_delete='1';
        $data->status='inactive';
        $data->save();
        return response()->json([
            'success' => true,
            'message' => 'Contract Deleted Successfully'
        ]);
    }
}