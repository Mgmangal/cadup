<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Master;
use App\Models\Contract;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ContractController extends Controller
{
    public function __construct()
    {
    }
    public function index()
    {
        $contract_types = Master::where('type', 'contract_type')->get();
        $users=User::where('designation',1)->where('is_delete','0')->get();
        if(getUserType()=='user'){
            return view('theme-one.contract.index', compact('contract_types','users'));
        }else{
            return view('contract.index', compact('contract_types','users'));
        }
       
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'contract_type_id' => 'required',
            'contract_user' => 'required',
        ]);
        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validation->errors()
            ]);
        }
        $contract_user = $request->contract_user;
        $contract_type_id = $request->contract_type_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $status = $request->status;
        $id = $request->edit_id;
        try {
            if (!empty($id)) {
                $master = Contract::find($id);
                $master->contract_user = $contract_user;
                $master->contract_type_id = $contract_type_id;
                $master->start_date = $start_date;
                $master->end_date = $end_date;
                $master->status = $status;
                $master->save();
            } else {
                $master = new Contract();
                $master->contract_user = $contract_user;
                $master->start_date = $start_date;
                $master->end_date = $end_date;
                $master->contract_type_id = $contract_type_id;
                $master->status = $status;
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
    public function list(Request $request)
    {
        $column = ['id', 'contract_user', 'start_date','end_date','contract_type_id','status', 'id'];
        $masters = Contract::where('is_delete','0');
        if(getUserType()=='user'){
            $masters->where('contract_user',Auth::user()->id);
        }

        $total_row = $masters->count();
        if (isset($_POST['search'])) {
            $masters->where('start_date', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $masters->orWhere('end_date', 'LIKE', '%' . $_POST['search']['value'] . '%');
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
            if(getUserType()=='user'){
                
            }else{
            $action .= '<a href="javascript:void(0);" onclick="editRole(`' . route('app.contract.edit', $value->id) . '`);" class="btn btn-warning btn-sm m-1">Edit</a>';
            $action .= '<a href="javascript:void(0);" onclick="deleted(`' . route('app.contract.delete', $value->id) . '`);" class="btn btn-danger btn-sm m-1">Delete</a>';
            }
            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = User::find($value->contract_user)->name;
            $sub_array[] = date('d-m-Y', strtotime($value->start_date));
            $sub_array[] = date('d-m-Y', strtotime($value->end_date));
            $sub_array[] = Master::find($value->contract_type_id)->name;
            $sub_array[] = $value->status;
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
        $role = Contract::find($id);
        return response()->json([
            'success' => true,
            'data' => $role
        ]);
    }
    public function destroy($id)
    {
        $data = Contract::find($id);
        $data->is_delete='1';
        $data->save();
        return response()->json([
            'success' => true,
            'message' => 'Contract Deleted Successfully'
        ]);
    }

    
}