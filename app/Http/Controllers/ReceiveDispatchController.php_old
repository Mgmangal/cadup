<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Master;
use App\Models\Receive;
use App\Models\AirCraft;
use App\Models\Dispatch;
use App\Models\FlyingLog;
use App\Models\ManageFile;
use App\Models\ReceiveBill;
use App\Models\StampTicket;
use Illuminate\Http\Request;
use App\Models\StampTicketType;
use App\Models\ReceiptBillFlyingLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReceiveDispatchController extends Controller
{
    public function receiveIndex()
    {
        $pilots = User::where('designation', '1')->where('status', 'active')->get();
        $sections = Master::where('type', 'section')->where('status', 'active')->where('is_delete', '0')->get();
        return view('receive-dispatch.receive', compact('pilots', 'sections'));
    }

    public function receiveAdd()
    {
        $data = '';
        $sections = Master::where('type', 'section')->where('status', 'active')->where('is_delete', '0')->get();
        return view('receive-dispatch.receive-manage', compact('data', 'sections'));
    }

    public function receiveStore(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'letter_number' => 'required',
            'date' => 'required|date_format:d-m-Y',
            'internal_external' => 'required|in:Internal,External',
            'source' => 'required',
            'other_source' => 'required_if:source,Other',
            'section' => 'required_if:source,Section',
            'to_section' => 'required',
            'letter_type' => 'required',
            'other_letter_type' => 'required_if:letter_type,Other',
            // 'receive_from' => 'required',
            'subject' => 'required',
            'receive_to' => 'required',
            //'address' => 'required_if:internal_external,External',
            // 'document' => 'nullable|file',
        ], [
            'letter_number.required' => 'The letter number field is required.',
            'date.required' => 'The date field is required.',
            'date.date_format' => 'The date must be in the format YYYY-MM-DD.',
            'internal_external.required' => 'The internal external field is required.',
            'internal_external.in' => 'The internal external field must be either Internal or External.',
            'source.required' => 'The source field is required.',
            'other_source.required_if' => 'The other source field is required.',
            'section.required_if' => 'The section field is required.',
            'to_section.required' => 'The to section field is required.',
            'letter_type.required' => 'The letter type field is required.',
            'other_letter_type.required_if' => 'The other letter type field is required.',
            // 'receive_from.required' => 'The receive from field is required.',
            'subject.required' => 'The subject field is required.',
            'receive_to.required' => 'The receive to field is required.',
            //'address.required_if' => 'The address field is required.',
            // 'document.file' => 'The document must be a file.',
        ]);

        if ($validation->fails()) {
            return response()->json(['error' => $validation->errors()]);
        }

        $edit_id= $request->edit_id;
        if(!empty($edit_id)){
            $data = Receive::find($edit_id);
            $message = 'Updated Successfully';
            $data->updated_by=Auth::id();
        }else{
            $data = new Receive;
            $data->created_by=Auth::id();
            $message = 'Added Successfully';
        }
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $ext = $file->getClientOriginalExtension();
            $FileName = $request->letter_number. '_' . date('y-m-d-h-i') . '.' . $ext;
            $file->move(public_path('uploads/receive-dispatch'), $FileName);
            $data->document = $FileName;
        }
        $data->letter_number = $request->letter_number;
        $data->date = $request->date;
        $data->internal_external= $request->internal_external;
        $data->source = $request->source;
        $data->other_source = ($request->source == 'Other') ? $request->other_source : '';
        $data->section = ($request->source == 'Section') ? $request->section : '';
        $data->to_section = $request->to_section;
        $data->letter_type = $request->letter_type;
        $data->other_letter_type = ($request->letter_type == 'Other') ? $request->other_letter_type : '';
        $data->receive_from = $request->receive_from;
        $data->subject = $request->subject;
        $data->receive_to = $request->receive_to;
        $data->address = ($request->internal_external == 'External') ? $request->address : '';
        $data->save();
        return response()->json(['success'=>true,'message' => $message]);
    }

    public function receiveList(Request $request)
    {
        $column = ['id', 'date', 'letter_number', 'subject', 'receive_from', 'receive_to', 'source', 'other_source', 'letter_type', 'other_letter_type','created_by', 'id'];
        $users = Receive::where('is_delete', '0');
        $total_row = $users->get()->count();

        if(!empty($_POST['source']))
        {
            $source=$_POST['source'];
            $users->where('source',$source);
        }
        if(!empty($_POST['section']))
        {
            $section=$_POST['section'];
            $users->where('section',$section);
        }
        if(!empty($_POST['to_section']))
        {
            $to_section=$_POST['to_section'];
            $users->where('to_section',$to_section);
        }

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
            $action  .= '<a href="'.route('app.receive.edit', $value->id).'" class="btn btn-primary btn-sm m-1"><i class="fas fa-edit"></i></a>';
            $action  .= '<a href="javascript:void(0);" onclick="addFile(`'.$value->id.'`,`receipt`);" class="btn btn-dark btn-sm m-1"><i class="fas fa-folder-plus"></i></a>';
            if($value->letter_type=='Bill')
            {
                $action .= '<a href="'.route('app.receive.bill', $value->id).'" class="btn btn-success btn-sm m-1"><i class="fas fa-file-alt"></i></a>';
            }
            if($value->letter_type=='Leave Application')
            {
               $action .= '<a href="'.route('app.pilot.leave.create').'" class="btn btn-success btn-sm m-1"><i class="fas fa-calendar-plus"></i></a>';
            }
            $action .= '<a href="javascript:void(0);" onclick="deleted(`'.route('app.receive.destroy', $value->id).'`);" class="btn btn-danger btn-sm m-1"><i class="fas fa-trash"></i></a>';
            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = $value->date;
            $sub_array[] = $value->letter_number;
            $sub_array[] = wordwrap($value->subject, 25);
            $sub_array[] = $value->receive_to;
            $sub_array[] = $value->receive_from;

            if($value->source == 'Section'){
                $sub_array[] = getMasterName($value->section);
            } else if($value->source == 'Other'){
                $sub_array[] = $value->other_source;
            } else {
                $sub_array[] = $value->source;
            }
            $sub_array[] = ($value->letter_type == 'Other') ? $value->other_letter_type : $value->letter_type;
            $sub_array[] = getEmpFullName($value->created_by);
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

    public function receiveEdit($id)
    {
        $data = Receive::find($id);
        $sections = Master::where('type', 'section')->where('status', 'active')->where('is_delete', '0')->get();
        $from_users = User::whereJsonContains('section', $data->section)->where('status','active')->where('is_delete','0')->get();
        $to_users = User::whereJsonContains('section', $data->to_section)->where('status','active')->where('is_delete','0')->get();
        return view('receive-dispatch.receive-manage', compact('data', 'sections' , 'from_users', 'to_users'));
    }

    public function receiveDestroy($id)
    {
        $aircraft = Receive::find($id);
        $aircraft->is_delete='1';
        $aircraft->save();
        return response()->json(['success'=>true,'message'=>'Deleted Successfully']);
    }

    public function receiveBillIndex()
    {
        $pilots = User::where('designation', '1')->where('status', 'active')->get();
        return view('receive-dispatch.receive-bill-list', compact('pilots'));
    }

    public function receiptBillList(Request $request)
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

    public function receiveBill($id)
    {
        $data=Receive::find($id);
        return view('receive-dispatch.receive-bill', compact('data'));
    }

    public function receiveStoreBill(Request $request)
    {
        // return $request->all();
        $validation = Validator::make($request->all(), [
            'bill_no' => 'required',
            'receive_from' => 'required',
            'dates' => 'required',
            'total_amount' => 'required|numeric',
            // 'expanses_type' => 'required',
            'fly_verify' => 'nullable|in:yes,no',
        ],[
            'bill_no.required' => 'The invoice number field is required.',
            'receive_from.required' => 'The company name field is required.',
            'dates.required' => 'The invoice date field is required.',
            'total_amount.required' => 'The total amount field is required.',
        ]);

        if ($validation->fails()) {
            return response()->json(['error' => $validation->errors()]);
        }
        $edit_id= $request->edit_id;
        if(!empty($edit_id))
        {
            $data=ReceiveBill::find($edit_id);
        }else{
            $data=new ReceiveBill;
        }
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $ext = $file->getClientOriginalExtension();
            $FileName = $request->bill_no. '_' . date('y-m-d-h-i') . '.' . $ext;
            $file->move(public_path('uploads/receive-dispatch'), $FileName);
            $data->document = $FileName;
        }
        $data->receives_id= $request->receives_id;
        $data->bill_no= $request->bill_no;
        $data->receive_from= $request->receive_from;
        $data->dates= $request->dates;
        $data->total_amount= $request->total_amount;
        $data->expenses_type= $request->expenses_type;
        $data->fly_verify= $request->fly_verify=='yes'?'yes':'no';
        $data->created_by= Auth::user()->id;
        $data->updated_by= Auth::user()->id;
        $data->save();
        $message = (!empty($edit_id)) ? 'Receive bill updated successfully' : 'Receive bill added successfully';
        return response()->json(['success'=>true,'message'=>$message]);
    }

    public function receiveBillList (Request $request)
    {
        $column = ['id', 'dates', 'receives_id', 'receive_from', 'total_amount', 'fly_verify', 'id'];
        $users = ReceiveBill::where('is_delete', '0')->where('receives_id',$request->receive_id);
        if(!empty($_POST['reference_no']))
        {
            $letter_type=$_POST['reference_no'];
            $users->where('bill_no',$letter_type);
        }

        if(!empty($_POST['from_date'])&&empty($_POST['to_date']))
        {
            $from=$_POST['from_date'];
            $users->where('dates','>=',date('Y-m-d',strtotime($from)));
        }
        if(empty($_POST['from_date'])&&!empty($_POST['to_date']))
        {
            $to=$_POST['to_date'];
            $users->where('dates','<=',date('Y-m-d',strtotime($to)));
        }
        if(!empty($_POST['from_date'])&&!empty($_POST['to_date']))
        {
            $from=$_POST['from_date'];
            $to=$_POST['to_date'];
            $users->where(function($q) use($from, $to){
                $q->whereBetween('dates', [date('Y-m-d',strtotime($from)), date('Y-m-d',strtotime($to))]);
            });
        }
        $total_row = $users->get()->count();
        if (!empty($_POST['search']['value'])) {
            $users->where('dates', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $users->orWhere('receive_from', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $users->orWhere('total_amount', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $users->orWhere('fly_verify', 'LIKE', '%' . $_POST['search']['value'] . '%');
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
            $action  = '<a href="javascript:void(0);" onclick="receiveBillEdit(`' . route('app.receive.bill.edit', $value->id) . '`);" class="btn btn-primary btn-sm m-1"><i class="fa fa-edit"></i></a>';
            $action  .= '<a href="javascript:void(0);" onclick="addFile(`'.$value->id.'`,`receipt_bill`);" class="btn btn-dark btn-sm m-1"><i class="fas fa-folder-plus"></i></a>';

            $action .= '<a href="javascript:void(0);" onclick="deleted(`' . route('app.receive.bill.destroy', $value->id) . '`);" class="btn btn-danger btn-sm m-1"><i class="fa fa-trash"></i></a>';
            if($value->fly_verify=='yes')
            {
                if(checkReceiptBillStatus($value->id,$value->receives_id))
                {
                    $fly_verify  = '<span class="btn btn-sm btn-success">Verified</span> <span class="btn btn-sm btn-danger" onclick="unverifyReceiptBill('.$value->receives_id.','.$value->id.')">Non-verified</span>';
                }else{
                    $fly_verify  = '<a href="'.route('app.receive.flyingVerifyLogs',['receipt_id'=>$value->receives_id,'bill_id'=>$value->id]).'" class="btn btn-primary btn-sm m-1">Verify Sector</a>';
                }

            }else{
                $fly_verify  = '';
            }
            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = $value->receive_from;
            $sub_array[] = $value->bill_no;
            $sub_array[] = is_get_date_format($value->dates);
            $sub_array[] = $value->total_amount;
            $sub_array[] = $fly_verify;
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

    public function receiveBillEdit($id)
    {
        $row = ReceiveBill::find($id);
        return response()->json([
            'success' => true,
            'data' => $row
        ]);
    }

    public function receiveBillDestroy($id)
    {
        $data = ReceiveBill::find($id);
        $data->is_delete='1';
        $data->save();
        return response()->json(['success' => true, 'message' => 'Receive Bill Deleted Successfully.']);
    }

    public function receiptUnverify(Request $request)
    {
        $receives_id=$request->receives_id;
        $bill_id=$request->bill_no;
        ReceiptBillFlyingLog::where('bill_id', $bill_id)->where('receives_id', $receives_id)->delete();
        return response()->json(['success' => true, 'message' => 'Receive Bill Deleted Successfully.']);
    }
    public function checkFile(Request $request)
    {
        $receive_id=$request->receive_id;
        $file_type=$request->file_type;
        $data=ManageFile::where('types',$file_type)->where('assign_id',$receive_id)->first();
        if(!empty($data))
        {
            return response()->json(['success' => true, 'message' => null,'data'=>$data]);
        }else{
            return response()->json(['success' => false, 'message' => null,'data'=>null]);
        }
    }

    public function fileStore(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'file_id' => 'required',
            'receive_id' => 'required',
        ],[
            'file_id.required' => 'The file field is required.',
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
                $modal = ManageFile::find($id);
                $massage = 'File Updated Successfully';
            } else {
                $modal = new ManageFile();
                $massage = 'File Added Successfully';
            }
            $modal->file_id = $request->file_id;
            $modal->assign_id = $request->receive_id;
            $modal->types = $request->file_type;
            $modal->save();
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

    public function dispatchBill($id)
    {
        $data=Dispatch::find($id);
        return view('receive-dispatch.receive-bill', compact('data'));
    }

    public function flyingVerifyLogs($receipt_id,$bill_id)
    {
        $data = ReceiveBill::find($bill_id);
        $aircrafts = AirCraft::where('status', 'active')->get();
        return view('receive-dispatch.flying-verify-logs', compact('data','aircrafts'));
    }

    public function getFlyingLogs(Request $request)
    {
        $from = $request->from_date;
        $to = $request->to_date;
        if (empty($from) && empty($to)) {
            return response()->json(['success' => false, 'message' => 'Please Select Date.']);
        }

        $users = FlyingLog::with(['pilot1', 'pilot2', 'aircraft'])->where('id', '>', 0);
        if(!empty($_POST['aircraft']))
        {
            $users->where('aircraft_id', '=', $_POST['aircraft']);
        }
        if (!empty($from) && empty($to)) {
            $users->where('date', '>=', date('Y-m-d', strtotime($from)));
        } elseif (empty($from) && !empty($to)) {
            $users->where('date', '<=', date('Y-m-d', strtotime($to)));
        } elseif (!empty($from) && !empty($to)) {
            $users->whereBetween('date', [date('Y-m-d', strtotime($from)), date('Y-m-d', strtotime($to))]);
        }

        $result = $users->get();
        $html = '';
        $bill_id=$request->bill_id;
        $receives_id=$request->receives_id;
        $data = ReceiveBill::find($bill_id);

        foreach ($result as $key => $value) {
            $html .= '<tr>';
            $html .= '<td>' . ++$key . '</td>';
            $html .= '<td>' . is_get_date_format($value->date) . '</td>';
            $html .= '<td>' . @$value->aircraft->call_sign . '</td>';
            $html .= '<td>' . $value->fron_sector.' /<br>'.$value->to_sector . '</td>';
            $html .= '<td>' . date('H:i',strtotime($value->departure_time)).' /<br>'. date('H:i',strtotime($value->arrival_time)) . '</td>';
            $html .= '<td>' . is_time_defrence($value->departure_time, $value->arrival_time) . '</td>';
            $html .= '<td>' . @$value->pilot1->salutation . ' ' . @$value->pilot1->name.'-'.$this->getMasterName($value->pilot1_role,'pilot_role').' /<br>'.@$value->pilot2->salutation . ' ' . @$value->pilot2->name.'-'.$this->getMasterName($value->pilot2_role,'pilot_role') . '</td>';
            $html .= '<td>' . $this->getMasterName($value->flying_type,'flying_type') . '</td>';
            $set = 1;
            $expenseTypes = is_string($data->expenses_type) ? json_decode($data->expenses_type, true) : (is_array($data->expenses_type) ? $data->expenses_type : []);
            foreach ($expenseTypes as $expenses) {
                $check=ReceiptBillFlyingLog::where('expenses',$expenses)->where('flying_log_id',$value->id)->first();
                $checkbox = '<div class="form-check pt-2">';
                $checked = !empty($check)&&$check->receives_id==$receives_id&&$check->bill_id==$bill_id?'checked':'' ;
                $disabled = !empty($check)&&$check->receives_id!=$receives_id||!empty($check)&&$check->bill_id!=$bill_id?'disabled':'' ;
                $background = !empty($check)&&$check->receives_id!=$receives_id||!empty($check)&&$check->bill_id!=$bill_id?'background: green;':'' ;
                $checkbox .= '<input class="form-check-input" style="border: 3px solid green;cursor: pointer ; '.$background .'" type="checkbox" '. $checked .' '. $disabled .' name="expence_id['.$expenses.'][]" value="'.$value->id.'">';
                $checkbox .= '</div>';
                $html .= '<td>' . $checkbox . '</td>';
            }
            $html .= '</tr>';
        }
        if($result->count()>0)
        {
            $btn = '<button type="submit" class="btn btn-sm btn-primary my-4 py-2 px-4">Submit</button>';
        }else{
            $btn = '';
        }


        return response()->json(['success' => true, 'html' => $html, 'btn' => $btn]);
    }


    public function flyingLogsStore(Request $request)
    {
        $bill_id=$request->bill_id;
        $receives_id=$request->receives_id;
        $expence_id=$request->expence_id;
        $data = ReceiveBill::find($bill_id);
        //print_r($expence_id);die;
        $expenseTypes = is_string($data->expenses_type) ? json_decode($data->expenses_type, true) : (is_array($data->expenses_type) ? $data->expenses_type : []);
        foreach ($expenseTypes as $expenses)
        {
            if(!empty($expence_id[$expenses]))
            {
                $d=$expence_id[$expenses];
                foreach($d as $ds)
                {
                    if(!empty($ds))
                    {
                        $check=ReceiptBillFlyingLog::where('bill_id',$bill_id)->where('receives_id',$receives_id)->where('expenses',$expenses)->where('flying_log_id',$ds)->first();
                        if(empty($check))
                        {
                            $saveData=new ReceiptBillFlyingLog;
                            $saveData->bill_id=$bill_id;
                            $saveData->receives_id=$receives_id;
                            $saveData->expenses=$expenses;
                            $saveData->flying_log_id=$ds;
                            $saveData->save();
                        }
                    }
                }
            }
        }
        return redirect()->back()->with('success', 'Save Successfully');
    }


    public function dispatchIndex()
    {
        $pilots = User::where('designation', '1')->where('status', 'active')->get();
        $sections = Master::where('type', 'section')->where('status', 'active')->where('is_delete', '0')->get();
        return view('receive-dispatch.dispatch', compact('pilots', 'sections'));
    }

    public function dispatchAdd()
    {
        $data=array();
        $sections = Master::where('type', 'section')->where('status', 'active')->where('is_delete', '0')->get();
        return view('receive-dispatch.dispatch-manage', compact('data','sections'));
    }

    public function dispatchStore(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'letter_number' => 'required',
            'date' => 'required|date_format:d-m-Y',
            'internal_external' => 'required|in:Internal,External',
            'dispatch_section' => 'required',
            'dispatch_from' => 'required',
            'forwarded_to' => 'required',
            // 'to_section' => 'required',
            // 'letter_type' => 'required',
            // 'other_letter_type' => 'required_if:letter_type,Other',
            // 'receive_from' => 'required',
            // 'subject' => 'required',
            // 'receive_to' => 'required',
            //'address' => 'required_if:internal_external,External',
            // 'document' => 'nullable|file',
        ], [
            'letter_number.required' => 'The dispatch number field is required.',
            'date.required' => 'The date field is required.',
            'date.date_format' => 'The date must be in the format YYYY-MM-DD.',
            'internal_external.required' => 'The internal external field is required.',
            'internal_external.in' => 'The internal external field must be either Internal or External.',
            'dispatch_section.required' => 'The dispatch section field is required.',
            'dispatch_from.required' => 'The dispatch from field is required.',
            'forwarded_to.required' => 'The forwarded to field is required.',
            // 'to_section.required' => 'The to section field is required.',
            // 'letter_type.required' => 'The letter type field is required.',
            // 'other_letter_type.required_if' => 'The other letter type field is required.',
            // 'receive_from.required' => 'The receive from field is required.',
            // 'subject.required' => 'The subject field is required.',
            // 'receive_to.required' => 'The receive to field is required.',
            //'address.required_if' => 'The address field is required.',
            // 'document.file' => 'The document must be a file.',
        ]);

        if ($validation->fails()) {
            return response()->json(['error' => $validation->errors()]);
        }

        $edit_id= $request->edit_id;
        if(!empty($edit_id)){
            $data = Dispatch::find($edit_id);
            $data->updated_by=Auth::id();

            $message = 'Updated Successfully';
        }else{
            $data = new Dispatch;
            $data->created_by=Auth::id();
            $message = 'Added Successfully';
        }
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $ext = $file->getClientOriginalExtension();
            $FileName = $request->letter_number. '_' . date('y-m-d-h-i') . '.' . $ext;
            $file->move(public_path('uploads/receive-dispatch'), $FileName);
            $data->document = $FileName;
        }
        $data->letter_number = $request->letter_number;
        $data->date = date('Y-m-d',strtotime($request->date));
        $data->internal_external= $request->internal_external;
        $data->dispatch_section = $request->dispatch_section;
        $data->dispatch_from = $request->dispatch_from;
        $data->forwarded_to = $request->forwarded_to;
        $data->other_source = ($request->forwarded_to == 'Other') ? $request->other_source : '';
        $data->section = ($request->forwarded_to == 'Section') ? $request->section : '';
        $data->letter_type = $request->letter_type;
        $data->other_letter_type = ($request->letter_type == 'Other') ? $request->other_letter_type : '';
        $data->receiver = $request->receiver;
        $data->subject = $request->subject;

        $data->address = ($request->internal_external == 'External') ? $request->address : '';
        $data->save();
        return response()->json(['success'=>true,'message' => $message]);
    }

    public function dispatchEdit ($id)
    {
        $data = Dispatch::find($id);
        $sections = Master::where('type', 'section')->where('status', 'active')->where('is_delete', '0')->get();
        $from_users = User::whereJsonContains('section', $data->section)->where('status','active')->where('is_delete','0')->get();
        $to_users = User::whereJsonContains('section', $data->dispatch_section)->where('status','active')->where('is_delete','0')->get();

        return view('receive-dispatch.dispatch-manage', compact('data', 'sections' , 'from_users', 'to_users'));
    }

    public function dispatchList(Request $request)
    {

        $column = ['id', 'date', 'letter_number', 'subject', 'dispatch_section', 'dispatch_from', 'forwarded_to', 'receiver', 'letter_type', 'other_letter_type','created_by', 'id'];
        $users = Dispatch::where('is_delete', '0');
        $total_row = $users->get()->count();

        if(!empty($_POST['source']))
        {
            $source=$_POST['source'];
            $users->where('dispatch_section',$source);
        }
        if(!empty($_POST['section']))
        {
            $section=$_POST['section'];
            $users->where('section',$section);
        }
        if(!empty($_POST['to_section']))
        {
            $to_section=$_POST['to_section'];
            $users->where('receiver',$to_section);
        }

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
            $users->orWhere('dispatch_section', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $users->orWhere('dispatch_from', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $users->orWhere('letter_type', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $users->orWhere('other_letter_type', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $users->orWhere('receiver', 'LIKE', '%' . $_POST['search']['value'] . '%');
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
               $action  .='<a target="blank" href="'.asset('uploads/receive-dispatch/'.$value->document).'" title="View Document" class="btn btn-sm btn-success m-1"><i class="fas fa-lg fa-fw fa-eye"></i></a>';
            }
            $action .= '<a href="javascript:void(0);" onclick="assignStampTickets(' . $value->id . ');" title="Assign Ticket" class="btn btn-info btn-sm m-1"><i class="fas fa-ticket-alt"></i></a>';
            $action  .= '<a href="'.route('app.dispatch.edit', $value->id).'" title="Edit" class="btn btn-primary btn-sm m-1"><i class="fas fa-edit"></i></a>';
            $action  .= '<a href="javascript:void(0);" onclick="addFile(`'.$value->id.'`,`dispatch`);" title="Add File" class="btn btn-dark btn-sm m-1"><i class="fas fa-folder-plus"></i></a>';
            $action .= '<a href="javascript:void(0);" onclick="deleted(`'.route('app.dispatch.destroy', $value->id).'`);" title="Delete" class="btn btn-danger btn-sm m-1"><i class="fas fa-trash"></i></a>';
            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = date('d-m-Y',strtotime($value->date));
            $sub_array[] = $value->letter_number;
            $sub_array[] = wordwrap($value->receiver, 25);
            $sub_array[] = getMasterName($value->dispatch_section);
            $sub_array[] = getEmpFullName($value->dispatch_from);
            $sub_array[] = $value->forwarded_to;
            // if($value->dispatch_section == 'Section'){
            //     $sub_array[] = getMasterName($value->section);
            // } else if($value->dispatch_section == 'Other'){
            //     $sub_array[] = $value->other_source;
            // } else {
            //     $sub_array[] = $value->dispatch_section;
            // }
            $sub_array[] = ($value->letter_type == 'Other') ? $value->other_letter_type : $value->letter_type;
            $sub_array[] = getEmpFullName($value->created_by);
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

    public function dispatchDestroy($id)
    {
        $aircraft = Dispatch::find($id);
        $aircraft->is_delete='1';
        $aircraft->save();
        return response()->json(['success'=>true,'message'=>'Deleted Successfully']);
    }

    public function getMasterName($id,$type)
    {
        $data=Master::where('id',$id)->where('type',$type)->first();
        return !empty($data)?$data->name:'';
    }

    public function getStampTicketForm(Request $request)
    {
        $dispatch_id = $request->dispatch_id;
        $row = Dispatch::findOrFail($dispatch_id);
        $html = '';

        $assignedStampTickets = is_string($row->stamp_tickets) ? json_decode($row->stamp_tickets, true) : $row->stamp_tickets;

        $stampTickets = StampTicketType::where('status','active')->where('is_delete', '0')->get();
        foreach ($stampTickets as $value) {
            $userId = auth()->user()->id;
            $stampTicketId = $value->id;

            $isAssigned = isset($assignedStampTickets[$stampTicketId]);
            $assignedQuantity = $isAssigned ? $assignedStampTickets[$stampTicketId] : '';
            if(!empty($assignedQuantity))
            {
                $html .= '<div class="row border m-0 mb-1"">';
                $html .= '<div class="col-md-4">';
                $html .= '<div class="form-check my-2">';
                $html .= '<label class="form-check-label">' . $value->name . '</label>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '<div class="col-md-8 my-2">';
                $html .= '<label class="form-check-label">' .$assignedQuantity. ' Qty</label>';
                $html .= '</div>';
                $html .= '</div>';
            }
        }
        foreach ($stampTickets as $value) {
            $userId = auth()->user()->id;
            $stampTicketId = $value->id;
            $userStampCount = StampTicket::where('user_id', $userId)->where('stamp_ticket_id', $stampTicketId)->where('is_delete', '0')->sum('quantity');

            $isAssigned = isset($assignedStampTickets[$stampTicketId]);
            $assignedQuantity = $isAssigned ? $assignedStampTickets[$stampTicketId] : '';

            $html .= '<div class="row">';
            $html .= '<div class="col-md-4">';
            $html .= '<div class="form-check my-2">';
            $html .= '<input class="form-check-input" type="checkbox" name="stamp_ticket_id[' . $value->id . ']" value="' . $value->id . '" id="stamp_ticket_id' . $value->id . '">';
            $html .= '<label class="form-check-label" for="stamp_ticket_id' . $value->id . '">' . $value->name . '</label> <span>(' . $userStampCount . ')</span>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '<div class="col-md-8 mb-3">';
            $html .= '<input type="number" class="form-control" name="quantity[' . $value->id . ']" placeholder="Enter Quantity" value="" max="' . $userStampCount . '">';
            $html .= '</div>';
            $html .= '</div>';
        }

        if (!empty($row)) {
            $data['status'] = 'ok';
            $data['result'] = $row;
            $data['html'] = $html;
        } else {
            $data['status'] = 'no';
            $data['result'] = 'No Data Found';
        }

        return response()->json($data);
    }

    public function assignTickets(Request $request)
    {
        $validation = Validator::make($request->all(), [
            // 'stamp_ticket_id.*' => 'required|exists:stamp_ticket_types,id',
            // 'quantity.*' => 'required|numeric',
            'comment' => 'nullable|string'
        ]);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validation->errors()->first()
            ]);
        }

        try {
            $stamp_ticket = $request->stamp_ticket_id;
            $dispatch_id = $request->dispatch_id;
            $quantity = $request->quantity;
            $user_id = Auth()->user()->id;
            $ticket2 = Dispatch::find($dispatch_id);
            $ticketdsd = is_string($ticket2->stamp_tickets) ? json_decode($ticket2->stamp_tickets, true) : $ticket2->stamp_tickets;
            //$ticketdsd = [];
            foreach ($stamp_ticket as $key => $stampId) {
                if(!empty($quantity[$key]))
                {
                    $ticket = new StampTicket();
                    $ticket->stamp_ticket_id = $stampId;
                    $ticket->transaction_type = 'Debit';
                    $ticket->quantity = '-'.$quantity[$key];
                    $ticket->comment = 'Dispatch number '.$ticket2->letter_number;
                    $ticket->user_id = $user_id;
                    $ticket->save();
                    if(!empty($ticketdsd[$stampId]))
                    {
                        $ticketdsd[$stampId] += $quantity[$key];
                    }else{
                        $ticketdsd[$stampId] = $quantity[$key];
                    }
                }
            }

            $ticket2->stamp_tickets = $ticketdsd;
            $ticket2->save();

            return response()->json([
                'success' => true,
                'message' => 'Stamp Ticket Assign Successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }











    public function leaveIndex()
    {
        $pilots = User::where('designation', '1')->where('status', 'active')->get();
        return view('receive-dispatch.leave', compact('pilots'));
    }

    public function leaveAdd()
    {
        $data = '';
        $sections = Master::where('type', 'section')->where('status', 'active')->where('is_delete', '0')->get();
        return view('receive-dispatch.leave-manage', compact('data', 'sections'));
    }

    public function leaveStore(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'letter_number' => 'required',
            'date' => 'required|date_format:d-m-Y',
            'internal_external' => 'required|in:Internal,External',
            'source' => 'required',
            'other_source' => 'required_if:source,Other',
            'section' => 'required_if:source,Section',
            'to_section' => 'required',
            'letter_type' => 'required',
            'other_letter_type' => 'required_if:letter_type,Other',
            // 'receive_from' => 'required',
            'subject' => 'required',
            'receive_to' => 'required',
            //'address' => 'required_if:internal_external,External',
            // 'document' => 'nullable|file',
        ], [
            'letter_number.required' => 'The letter number field is required.',
            'date.required' => 'The date field is required.',
            'date.date_format' => 'The date must be in the format YYYY-MM-DD.',
            'internal_external.required' => 'The internal external field is required.',
            'internal_external.in' => 'The internal external field must be either Internal or External.',
            'source.required' => 'The source field is required.',
            'other_source.required_if' => 'The other source field is required.',
            'section.required_if' => 'The section field is required.',
            'to_section.required' => 'The to section field is required.',
            'letter_type.required' => 'The letter type field is required.',
            'other_letter_type.required_if' => 'The other letter type field is required.',
            // 'receive_from.required' => 'The receive from field is required.',
            'subject.required' => 'The subject field is required.',
            'receive_to.required' => 'The receive to field is required.',
            //'address.required_if' => 'The address field is required.',
            // 'document.file' => 'The document must be a file.',
        ]);

        if ($validation->fails()) {
            return response()->json(['error' => $validation->errors()]);
        }

        $edit_id= $request->edit_id;
        if(!empty($edit_id)){
            $data = Receive::find($edit_id);
            $message = 'Updated Successfully';
            $data->updated_by=Auth::id();
        }else{
            $data = new Receive;
            $data->created_by=Auth::id();
            $message = 'Added Successfully';
        }
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $ext = $file->getClientOriginalExtension();
            $FileName = $request->letter_number. '_' . date('y-m-d-h-i') . '.' . $ext;
            $file->move(public_path('uploads/receive-dispatch'), $FileName);
            $data->document = $FileName;
        }
        if ($request->hasFile('other_doc')) {
            $file = $request->file('other_doc');
            $ext = $file->getClientOriginalExtension();
            $FileName = $request->letter_number. '_' . date('y-m-d-h-i') . '.' . $ext;
            $file->move(public_path('uploads/receive-dispatch'), $FileName);
            $data->other_doc = $FileName;
        }
        $data->letter_number = $request->letter_number;
        $data->date = $request->date;
        $data->internal_external= $request->internal_external;
        $data->source = $request->source;
        $data->other_source = ($request->source == 'Other') ? $request->other_source : '';
        $data->section = ($request->source == 'Section') ? $request->section : '';
        $data->to_section = $request->to_section;
        $data->letter_type = $request->letter_type;
        $data->other_letter_type = ($request->letter_type == 'Other') ? $request->other_letter_type : '';
        $data->receive_from = $request->receive_from;
        $data->subject = $request->subject;
        $data->receive_to = $request->receive_to;
        $data->address = ($request->internal_external == 'External') ? $request->address : '';
        $data->save();
        return response()->json(['success'=>true,'message' => $message]);
    }

    public function leaveList(Request $request)
    {
        $column = ['id', 'date', 'letter_number', 'subject', 'receive_from', 'receive_to', 'source', 'other_source', 'letter_type', 'other_letter_type','created_by', 'id'];
        $users = Receive::where('is_delete', '0')->where('letter_type','Leave Application');
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
            $action  .= '<a href="'.route('app.receive.leave.edit', $value->id).'" class="btn btn-primary btn-sm m-1"><i class="fas fa-edit"></i></a>';
            $action  .= '<a href="javascript:void(0);" onclick="addFile(`'.$value->id.'`,`receipt`);" class="btn btn-dark btn-sm m-1"><i class="fas fa-folder-plus"></i></a>';

            if($value->letter_type=='Leave Application')
            {
               $action .= '<a href="'.route('app.pilot.leave.create').'" class="btn btn-success btn-sm m-1"><i class="fas fa-calendar-plus"></i></a>';
            }
            $action .= '<a href="javascript:void(0);" onclick="deleted(`'.route('app.receive.leave.destroy', $value->id).'`);" class="btn btn-danger btn-sm m-1"><i class="fas fa-trash"></i></a>';
            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = $value->date;
            $sub_array[] = $value->letter_number;
            $sub_array[] = wordwrap($value->subject, 20);
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
            $sub_array[] = getEmpFullName($value->created_by);
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

    public function leaveEdit($id)
    {
        $data = Receive::find($id);
        $sections = Master::where('type', 'section')->where('status', 'active')->where('is_delete', '0')->get();
        $from_users = User::whereJsonContains('section', $data->section)->where('status','active')->where('is_delete','0')->get();
        $to_users = User::whereJsonContains('section', $data->to_section)->where('status','active')->where('is_delete','0')->get();
        return view('receive-dispatch.leave-manage', compact('data', 'sections' , 'from_users', 'to_users'));
    }

    public function leaveDestroy($id)
    {
        $aircraft = Receive::find($id);
        $aircraft->is_delete='1';
        $aircraft->save();
        return response()->json(['success'=>true,'message'=>'Deleted Successfully']);
    }


}
