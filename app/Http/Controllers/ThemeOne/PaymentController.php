<?php

namespace App\Http\Controllers\ThemeOne;

use App\Models\User;
use App\Models\Master;
use App\Models\Payment;
use App\Models\Receive;
use App\Models\PilotSfa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    public function sfa()
    {
        $pilots = User::where('designation', '1')->where('status', 'active')->get();
        $sections = Master::where('type', 'section')->where('status', 'active')->where('is_delete', '0')->get();
        return view('theme-one.payment.sfa', compact('pilots','sections'));
    }

    public function sfa_list()
    {
        // $column = ['id', 'user_id','from_date', 'to_date', 'amount', 'status', 'id'];
        // $users = PilotSfa::where('id', '>', 0);
        $column = ['pilot_sfas.id', 'pilot_sfas.user_id','pilot_sfas.from_date', 'pilot_sfas.to_date', 'pilot_sfas.amount', 'pilot_sfas.status', 'pilot_sfas.id', 'pilot_sfas.id'];
        $users = PilotSfa::select('pilot_sfas.id', 'pilot_sfas.user_id','pilot_sfas.from_date', 'pilot_sfas.to_date', 'pilot_sfas.amount', 'pilot_sfas.status','data_transfers.from_user','data_transfers.to_user','data_transfers.description')
                ->rightJoin('data_transfers', 'data_transfers.data_id', '=', 'pilot_sfas.id')->where('data_transfers.data_type', '=', 'sfa')->where(function($q){
                    $q->where('data_transfers.from_user',  auth()->user()->id);
                    $q->orWhere('data_transfers.to_user', auth()->user()->id);
                });
        $users = PilotSfa::with(['dataTransfers' => function($query) {
            $query->where('data_transfers.data_type', 'sfa')
                    ->where(function($subquery) {
                        $subquery->where('data_transfers.from_user', auth()->user()->id)
                                ->orWhere('data_transfers.to_user', auth()->user()->id);
                    })->orderBy('data_transfers.id', 'desc')->limit(1);
        }]);

        if(!empty($_POST['pilot'])){
            $users->where('pilot_sfas.user_id', '=', $_POST['pilot']);
        }
        $total_row = $users->get()->count();
        if(!empty($_POST['from_date'])&&empty($_POST['to_date']))
        {
            $from=$_POST['from_date'];
            $users->where('pilot_sfas.from_date','>=',date('Y-m-d',strtotime($from)));
        }
        if(empty($_POST['from_date'])&&!empty($_POST['to_date']))
        {
            $to=$_POST['to_date'];
            $users->where('pilot_sfas.from_date','<=',date('Y-m-d',strtotime($to)));
        }
        if(!empty($_POST['from_date'])&&!empty($_POST['to_date']))
        {
            $from=$_POST['from_date'];
            $to=$_POST['to_date'];
            $users->where(function($q) use($from, $to){
                $q->whereBetween('pilot_sfas.from_date', [date('Y-m-d',strtotime($from)), date('Y-m-d',strtotime($to))]);
            });
        }

        if (isset($_POST['search'])&&!empty($_POST['search']['value'])) {
            $users->where('pilot_sfas.fron_sector', 'LIKE', '%' . $_POST['search']['value'] . '%');
        }
        if (isset($_POST['order'])) {
            $users->orderBy($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $users->orderBy('pilot_sfas.id', 'asc');
        }
        $filter_row = $users->get()->count();
        if (isset($_POST["length"]) && $_POST["length"] != -1) {
            $users->skip($_POST["start"])->take($_POST["length"]);
        }
        $result = $users->get();
        $data = array();
        foreach ($result as $key => $value) {
            $sub_array = array();
            $action ='';
            $action .='<a href="'.route('user.sfa.view',encrypter('encrypt', $value->id)).'" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a>';
            if($value->status=='Generated')
            {
                $action .=' <a href="'.route('user.sfa.deleted',encrypter('encrypt', $value->id)).'" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>';
            }   
            $action .=' <a href="javascript:void(0);" onclick="forwords('.$value->id.')" class="btn btn-success btn-sm"><i class="fa fa-arrow-right"></i></a>';
        
            $sub_array[] = ++$key;
            $sub_array[] = getEmpFullName($value->user_id);
            $sub_array[] = is_get_date_format($value->from_date);
            $sub_array[] = is_get_date_format($value->to_date);
            $sub_array[] = $value->amount;
            $sub_array[] = $value->status;
            $sub_array[] = $value->dataTransfers[0]->description;
            $sub_array[] = $action;
            $data[] = $sub_array;
        }
        $output = array(
            "draw"       =>  intval($_POST["draw"]),
            "recordsTotal"   =>  $total_row,
            "recordsFiltered"  =>  $filter_row,
            "data"       =>  $data,
        );
        echo json_encode($output);
    }

    public function bill()
    {
        return view('theme-one.payment.bill');
    }

    public function bill_list()
    {
        $column = ['id', 'date', 'letter_number', 'subject', 'receive_from', 'receive_to', 'source', 'other_source', 'letter_type', 'other_letter_type', 'id'];
        $users = Receive::where('is_delete', '0')->where('letter_type','Bill');
        $total_row = $users->get()->count();
        if(!empty($_POST['reference_no']))
        {
            $reference_no=$_POST['reference_no'];
            $users->where('letter_number',$reference_no);
        }
        if(!empty($_POST['letter_type']))
        {
            $letter_type=$_POST['letter_type'];
            $users->where('letter_type',$letter_type);
        }

        if(!empty($_POST['from_date'])&&empty($_POST['to_date']))
        {
            $from=$_POST['from_date'];
            $users->where('date','>=',date('Y-m-d',strtotime($from)));
        }
        if(empty($_POST['from_date'])&&!empty($_POST['to_date']))
        {
            $to=$_POST['to_date'];
            $users->where('date','<=',date('Y-m-d',strtotime($to)));
        }
        if(!empty($_POST['from_date'])&&!empty($_POST['to_date']))
        {
            $from=$_POST['from_date'];
            $to=$_POST['to_date'];
            $users->where(function($q) use($from, $to){
                $q->whereBetween('date', [date('Y-m-d',strtotime($from)), date('Y-m-d',strtotime($to))]);
            });
        }

        if (isset($_POST['search'])&&!empty($_POST['search']['value'])) {

            $users->where('date', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $users->orWhere('letter_number', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $users->orWhere('source', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $users->orWhere('other_source', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $users->orWhere('letter_type', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $users->orWhere('other_letter_type', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $users->orWhere('receive_from', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $users->orWhere('receive_from', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $users->orWhere('subject', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $users->orWhere('receive_to', 'LIKE', '%' . $_POST['search']['value'] . '%');
        }

        if (isset($_POST['order'])) {
            $users->orderBy($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $users->orderBy('id', 'desc');
        }
        $filter_row = $users->get()->count();
        if (isset($_POST["length"]) && $_POST["length"] != -1) {
            $users->skip($_POST["start"])->take($_POST["length"]);
        }
        $result = $users->get();
        $data = array();
        foreach ($result as $key => $value) {
            $action  ='';
            if(!empty($value->document))
            {
               $action  .='<a target="blank" href="'.asset('uploads/receive-dispatch/'.$value->document).'" class="btn btn-sm btn-success m-1"><i class="fas fa-lg fa-fw me-2 fa-eye"></i></a>';
            }
            //$action  .= '<a href="'.route('app.receive.edit', $value->id).'" class="btn btn-primary btn-sm m-1"><i class="fas fa-edit"></i></a>';
            $action  .= '<a href="javascript:void(0);" onclick="addFile(`'.$value->id.'`,`receipt`);" class="btn btn-dark btn-sm m-1"><i class="fas fa-folder-plus"></i></a>';
            if($value->letter_type=='Bill')
            {
                $action .= '<a href="'.route('app.receive.bill', $value->id).'" class="btn btn-success btn-sm m-1"><i class="fas fa-file-alt"></i></a>';
            }
            if($value->letter_type=='Leave Application')
            {
               $action .= '<a href="'.route('app.pilot.leave.create').'" class="btn btn-success btn-sm m-1"><i class="fas fa-calendar-plus"></i></a>';
            }
            //$action .= '<a href="javascript:void(0);" onclick="deleted(`'.route('app.receive.destroy', $value->id).'`);" class="btn btn-danger btn-sm m-1"><i class="fas fa-trash"></i></a>';
            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = $value->date;
            $sub_array[] = $value->letter_number;
            $sub_array[] = wordwrap($value->subject, 30, "<br>\n");
            $sub_array[] = $value->receive_from;
            $sub_array[] = $value->receive_to;
            if($value->source == 'Section'){
                $sub_array[] = getMasterName($value->section);
            } else if($value->source == 'Other'){
                $sub_array[] = $value->other_source;
            } else {
                $sub_array[] = $value->source;
            }
            $sub_array[] = ($value->letter_type == 'Other') ? $value->other_letter_type : $value->letter_type;
            $sub_array[] = checkBillStatus($value->id)?'<span class="btn btn-sm btn-success">Verified</span>':'<span class="btn btn-sm btn-info">Non Verified</span>';
            $sub_array[] = $action;
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

    public function history()
    {
        $pilots = User::where('designation', '1')->where('status', 'active')->get();
        return view('theme-one.payment.history', compact('pilots'));
    }

    public function history_list()
    {
        $column=['id','payment_for','reference_id','payment_method','payment_details','amount','created_at','status','id'];
        $users = Payment::where('id', '>', '0');
        $total_row = $users->get()->count();
        if (isset($_POST['order'])) {
            $users->orderBy($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $users->orderBy('id', 'desc');
        }
        $filter_row = $users->get()->count();
        if (isset($_POST["length"]) && $_POST["length"] != -1) {
            $users->skip($_POST["start"])->take($_POST["length"]);
        }
        $result = $users->get();
        $data = array();
        foreach ($result as $key => $value) {
            $sub_array = array();
            $action='<a href="'.route('user.payment.edit', $value->id).'" class="btn btn-primary btn-sm m-1"><i class="fas fa-edit"></i></a>';
            $action .='<a href="javascript:void(0);" onclick="deleted(`'.route('user.payment.delete', $value->id).'`);" class="btn btn-danger btn-sm m-1"><i class="fas fa-trash"></i></a>';
            $sub_array[] = ++$key;
            $sub_array[] = $value->payment_for;
            $sub_array[] = $value->reference_id;
            $sub_array[] = $value->payment_method;
            $sub_array[] = $value->payment_details;
            $sub_array[] = $value->amount;
            $sub_array[] = date('d-m-Y', strtotime($value->created_at));
            $sub_array[] = $value->status;
            $sub_array[] = $action;
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

    public function create()
    {
        $pilots = User::where('designation', '1')->where('status', 'active')->get();
        return view('theme-one.payment.create', compact('pilots'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'payment_for' => 'required',
            'reference_id' => 'required',
            'payment_method' => 'required',
            'payment_details' => 'required',
            'amount' => 'required',
        ]);
        $input = $request->all();
        $data=new Payment();
        $data->payment_for=$input['payment_for'];
        $data->reference_id=$input['reference_id'];
        $data->payment_method=$input['payment_method'];
        $data->payment_details=$input['payment_details'];
        $data->amount=$input['amount'];
        $data->status=$input['status'];
        //$data->user_id=Auth::user()->id;
        $data->save();
        return redirect()->route('user.payment.history')->with('success', 'Payment created successfully.');
    }

    public function edit($id)
    {
        $payment = Payment::find($id);
        $pilots = User::where('designation', '1')->where('status', 'active')->get();
        return view('theme-one.payment.edit', compact('payment', 'pilots'));
    }

    public function update(Request $request, $id)
    {
        try {
            $payment = Payment::find($id);
            $validated = $request->validate([
                'user_id' => 'nullable|exists:users,id',
                'payment_method' => 'required|string|max:255',
                'status' => 'required|string|max:50',
                'payment_details' => 'required|string',
                'amount' => 'required|numeric|min:0',
                'payment_for' => 'required|string|max:255',
            ]);
            //print_r($validated);die;
            // Update the payment
            $payment->update($validated);
            return redirect()->route('user.payment.history')->with('success', 'Payment updated successfully.');
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        Payment::find($id)->delete();
        return response()->json(['status' => 'success','message' => 'Payment deleted successfully.'], 200, array('Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'));
    }
}