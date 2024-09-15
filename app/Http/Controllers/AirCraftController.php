<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AirCraft;
use App\Models\User;
use App\Models\Master;
use App\Models\MasterAssign;
use Illuminate\Support\Facades\Validator;
class AirCraftController extends Controller
{
    public function index()
    {
        return view('air_crafts.index');
    }
    public function create()
    {
        $pilots=User::where('designation','=','1')->where('status','active')->get();
        $masters=Master::where('type','=','aircraft_type')->where('is_delete','0')->get();
        return view('air_crafts.create',compact('pilots','masters'));
    }

    public function store(Request $request)
    {
        $validation=Validator::make($request->all(),[
            'aircraft_cateogry' => 'required',
            'manufacturer' => 'required',
            'type_model' => 'required',
            'call_sign' => 'required',
            'me_se' => 'required',
            'manufacturing_year' => 'required',
            // 'operation_start_date' => 'required',
            // 'operation_end_date' => 'required',
        ]);
        if ($validation->fails()) {
            return response()->json(['error'=>$validation->errors()]);
        }

        $aircraft = new AirCraft();
        $aircraft->aircraft_type = $request->aircraft_type;
        $aircraft->aircraft_cateogry = $request->aircraft_cateogry;
        $aircraft->manufacturer = $request->manufacturer;
        $aircraft->type_model = $request->type_model;
        $aircraft->call_sign = $request->call_sign;
        $aircraft->me_se = $request->me_se;
        $aircraft->pilots = $request->pilots;
        $aircraft->manufacturing_year = $request->manufacturing_year;
        // $aircraft->operation_start_date = is_set_date_format($request->operation_start_date);
        // $aircraft->operation_end_date = is_set_date_format($request->operation_end_date);
        $aircraft->save();

        return response()->json(['success'=>true,'message'=>'Aircraft Added Successfully']);
    }

    public function list(Request $request)
    {
        $column=['id','aircraft_cateogry','manufacturer','type_model','call_sign','me_se','manufacturing_year','created_at','id'];
        $users=AirCraft::where('is_delete','0');

        $total_row=$users->get()->count();
        if (!empty($_POST['search']['value'])) {
            $users->where('manufacturer', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $users->orWhere('manufacturing_year', 'LIKE', '%' . $_POST['search']['value'] . '%');
		}

		if (isset($_POST['order'])) {
            $users->orderBy($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else {
            $users->orderBy('id', 'desc');
        }
		$filter_row =$users->get()->count();
        if (isset($_POST["length"]) && $_POST["length"] != -1) {
            $users->skip($_POST["start"])->take($_POST["length"]);
		}
        $result =$users->get();
        $data = array();
		foreach ($result as $key => $value) {
            $action  = '<a href="'.route('app.air-crafts.edit', $value->id).'" class="btn btn-warning btn-sm m-1">Edit</a>';
            $action .= '<a href="javascript:void(0);" onclick="deleted(`'.route('app.air-crafts.destroy', $value->id).'`);" class="btn btn-danger btn-sm m-1">Delete</a>';
            $action .= '<a href="javascript:void(0);" onclick="license(`' . $value->id . '`);" class="btn btn-success btn-sm m-1">License</a>';

            $pilots='';
            if(!empty($value->pilots))
            {
                foreach ($value->pilots as $pilot) {
                   $u= User::find($pilot);
                    $pilots .= (!empty($u)?$u->name:'').',';
                }
            }
            $pilots = rtrim($pilots,',');

            $sub_array = array();
			$sub_array[] = ++$key;
            $sub_array[] = $value->aircraft_cateogry;
            $sub_array[] = $value->manufacturer;
            $sub_array[] = !empty($value->aircraft_type)? getMasterName($value->aircraft_type):'';
            $sub_array[] = $value->type_model;
            $sub_array[] = $value->call_sign;
            $sub_array[] = $value->me_se;
            $sub_array[] = $value->manufacturing_year;
            // $sub_array[] = $value->operation_start_date;
            // $sub_array[] = $value->operation_end_date;
            $sub_array[]= $pilots;
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
        $aircraft = AirCraft::find($id);
        $pilots=User::where('designation','=','1')->get();
        $masters=Master::where('type','=','aircraft_type')->where('is_delete','0')->get();
        return view('air_crafts.edit', compact('aircraft','pilots','masters'));
    }
    public function update(Request $request, $id)
    {

        $validation=Validator::make($request->all(),[
            'aircraft_cateogry' => 'required',
            'manufacturer' => 'required',
            'type_model' => 'required',
            'call_sign' => 'required',
            'me_se' => 'required',
            'manufacturing_year' => 'required',

        ]);
        if ($validation->fails()) {
            return response()->json(['error'=>$validation->errors()]);
        }
        $aircraft = AirCraft::find($id);
        $aircraft->aircraft_type = $request->aircraft_type;
        $aircraft->aircraft_cateogry = $request->aircraft_cateogry;
        $aircraft->manufacturer = $request->manufacturer;
        $aircraft->type_model = $request->type_model;
        $aircraft->call_sign = $request->call_sign;
        $aircraft->me_se = $request->me_se;
        $aircraft->pilots = $request->pilots;
        $aircraft->manufacturing_year = $request->manufacturing_year;
        // $aircraft->operation_start_date = is_set_date_format($request->operation_start_date);
        // $aircraft->operation_end_date = is_set_date_format($request->operation_end_date);
        $aircraft->save();
        return response()->json(['success'=>true,'message'=>'Aircraft Updated Successfully']);
    }

    public function destroy($id)
    {
        $aircraft = AirCraft::find($id);
        $aircraft->is_delete='0';
        $aircraft->status='inactive';
        $aircraft->save();
        return response()->json(['success'=>true,'message'=>'Aircraft Deleted Successfully']);
    }

    public function getlicense(Request $request)
    {
        $id = $request->id;
        $html = '<input type="hidden" name="master_id" value="' . $id . '">';
        $licenses =  Master::orderBy('sub_type', 'asc')->where('sub_type', '=', 'license')->orWhere('sub_type', '=', 'training')->get();
        $html .= '<div class="row">';
        $html .= '<table class="table border">';
        $html .= '<tr><th>Name</th><th>Type</th><th>Mendatory </th><th>Current For Flying</th></tr>';
        foreach ($licenses as $key => $value) {
            $d = MasterAssign::where('master_id', $id)->where('is_for','=','aircraft')->where('certificate_id', $value->id)->first();
            $html .= '<tr>';
            $html .= '<td>';
            $html .= '<div class="form-check m-2">
                            <input type="hidden" name="edit_id[' . $key . ']" value="' . (!empty($d->id) ? $d->id : '') . '">
                            <input class="form-check-input" type="checkbox" value="' . $value->id . '" ' . (!empty($d->certificate_id) && $d->certificate_id == $value->id ? 'checked' : '') . ' id="mng' . $value->id . '" name="licenses[' . $key . ']">
                            <label class="form-check-label" for="mng' . $value->id . '">' . $value->name . '</label>
                        </div>';
            $html .='</td>';
            $html .= '<td>' . ucfirst($value->sub_type) . '</td>';
            $html .= '<td>';
            $html .= ' <div class="form-check m-2">
                            <input class="form-check-input" type="checkbox" value="yes" ' . (!empty($d) && $d->is_mendatory == 'yes' ? 'checked' : '') . ' id="mng1' . $value->id . '" name="is_mendatory[' . $key . ']">
                            <label class="form-check-label" for="mng1' . $value->id . '">Yes</label>
                        </div>';
            $html .= '</td>';
            $html .= '<td>';
            $html .= '<div class="form-check m-2">
                            <input class="form-check-input" type="checkbox" value="yes" ' . (!empty($d) && $d->is_active == 'yes' ? 'checked' : '') . ' id="mng2' . $value->id . '" name="is_active[' . $key . ']">
                            <label class="form-check-label" for="mng2' . $value->id . '">Yes</label>
                        </div>';
            $html .= '</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';
        $html .= '</div>';
        return response()->json([
            'success' => true,
            'data' => $html
        ]);
    }

    public function license(Request $request)
    {
        $edit_id = $request->edit_id;
        $master_id = $request->master_id;
        $licenses = $request->licenses;
        $is_mendatory = $request->is_mendatory;
        $is_active = $request->is_active;
        MasterAssign::where('master_id', $master_id)->delete();
        foreach ($licenses as $key => $val) {
            if (!empty($val)) {
                $data = new MasterAssign;
                $data->master_id = $master_id;
                $data->certificate_id = $val;
                $data->is_mendatory = !empty($is_mendatory[$key]) ? $is_mendatory[$key] : 'no';
                $data->is_active = !empty($is_active[$key]) ? $is_active[$key] : 'no';
                $data->is_for='aircraft';
                $data->save();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'License Added Successfully'
        ]);
    }

    public function availability()
    {
        return view('air_crafts.availability');
    }
}
