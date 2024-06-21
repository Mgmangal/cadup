<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Master;
use App\Models\StampTicket;
use Illuminate\Http\Request;
use App\Models\StampTicketType;
use Illuminate\Support\Facades\Validator;

class StampticketController extends Controller
{
    public function index()
    {
        return view('stamp_tickets.index');
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            // 'stamp_ticket_id.*' => 'required|exists:stamp_ticket_types,id',
            'transaction_type' => 'required|string',
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
            // print_r($request->all()); die();
            $stamp_ticket = $request->stamp_ticket_id;
            $quantity = $request->quantity;
            $transaction_type = $request->transaction_type;
            $comment = $request->comment;
            // print_r($request->all());die;
            foreach ($stamp_ticket as $key => $stampId) {
                if(!empty($quantity[$key]))
                {
                    $ticket = new StampTicket();
                    $ticket->stamp_ticket_id = $stampId;
                    $ticket->transaction_type = $transaction_type;
                    if ($transaction_type == 'Credit') {
                        $ticket->quantity =  '+' . $quantity[$key];
                    } else {
                        $ticket->quantity =  '-' . $quantity[$key];
                    }
                    $ticket->comment = $comment;
                    $ticket->user_id = '1';
                    $ticket->save();
                }
                
            }

            return response()->json([
                'success' => true,
                'message' => 'Stamp Ticket Added Successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function assign(Request $request)
    {
        $validation = Validator::make($request->all(), [
            // 'stamp_ticket_id.*' => 'required|exists:stamp_ticket_types,id',
            'user_id' => 'required|numeric',
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
            $quantity = $request->quantity;
            $comment = $request->comment;
            $user_id = $request->user_id;
            //print_r($request->all());die;
            foreach ($stamp_ticket as $key => $stampId) {
                if(!empty($quantity[$key]))
                {
                    $ticket = new StampTicket();
                    $ticket->stamp_ticket_id = $stampId;
                    $ticket->transaction_type = 'Debit';
                    $ticket->quantity = '-'.$quantity[$key];
                    $ticket->comment = $comment;
                    $ticket->user_id = Auth()->user()->id;
                    $ticket->save();
    
    
                    $ticket2 = new StampTicket();
                    $ticket2->stamp_ticket_id = $stampId;
                    $ticket2->transaction_type = 'Credit';
                    $ticket2->quantity = '+' . $quantity[$key];
                    $ticket2->comment = $comment;
                    $ticket2->user_id = $user_id;
                    $ticket2->save();
                }
                
            }

            return response()->json([
                'success' => true,
                'message' => 'Stamp Ticket Added Successfully',
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
        $column = ['id', 'user_id', 'stamp_ticket_id', 'transaction_type', 'quantity', 'comment', 'id'];
        $model = StampTicket::where('is_delete', '0');

        $total_row = $model->count();
        if (isset($_POST['search'])) {
            $searchValue = $_POST['search']['value'];

            $model->where(function ($query) use ($searchValue) {
                $query->where('stamp_ticket_id', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('quantity', 'LIKE', '%' . $searchValue . '%');
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
            // $action = '<a href="javascript:void(0);" onclick="edit(`' . route('app.stamp_ticket.edit', $value->id) . '`);" class="btn btn-warning btn-sm m-1">Edit</a>';
            $action = '<a href="javascript:void(0);" onclick="deleted(`' . route('app.stamp_ticket.delete', $value->id) . '`);" class="btn btn-danger btn-sm m-1">Delete</a>';

            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = !empty($value->user)?$value->user->salutation . ' ' . $value->user->name:'';
            $sub_array[] = $value->stampticket->name;
            $sub_array[] = $value->transaction_type;
            $sub_array[] = $value->quantity;
            $sub_array[] = $value->comment;
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

    public function delete($id)
    {
        $model = StampTicket::findOrFail($id);
        $model->is_delete = '1';
        $model->save();
        return response()->json([
            'success' => true,
            'message' => 'Stamp Ticket Deleted Successfully'
        ]);
    }

    public function getAddTicketForm(Request $request)
    {
        $html = '';

        $stampTickets = StampTicketType::where('status', 'active')->where('is_delete', '0')->get();

        $html .= '<div class="col-md-12"><label class="form-label">Stamp Type</label></div>';

        foreach ($stampTickets as $value) {
            $html .= '<div class="row">';
            $html .= '<div class="col-md-4">';
            $html .= '<div class="form-check my-2">';
            $html .= '<input class="form-check-input" type="checkbox" name="stamp_ticket_id['. $value->id.']" value="' . $value->id . '" id="stamp_ticket_id' . $value->id . '">';
            $html .= '<label class="form-check-label" for="stamp_ticket_id' . $value->id . '">' . htmlspecialchars($value->name) . '</label>';
            $html .= '</div></div>';
            $html .= '<div class="col-md-8 mb-3">';
            $html .= '<input type="number" class="form-control" name="quantity['. $value->id.']" placeholder="Enter Quantity" />';
            $html .= '</div></div>';
        }

        $html .= '<div class="mb-3"><label class="form-label">Transaction Type</label>';
        $html .= '<select name="transaction_type" class="form-control">';
        $html .= '<option value="">Select</option>';
        $html .= '<option value="Credit">Credit</option>';
        $html .= '<option value="Debit">Debit</option>';
        $html .= '</select></div>';
        $html .= '<div class="mb-3"><label class="form-label">Description</label>';
        $html .= '<textarea name="comment" class="form-control" rows="3" placeholder="Enter Description"></textarea></div>';

        // Prepare response data
        $data = ['status' => 'ok', 'html' => $html];

        return response()->json($data);
    }
    public function getAssignTicketForm(Request $request)
    {
        $html = '';

        $users = User::where('id', '!=', auth()->id())->where('status', 'active')->where('is_delete', '0')->get();

        $html .= '<div class="mb-3">
                <label class="form-label">User</label>
                <select name="user_id" class="form-control">
                <option value="">Select</option>';

        foreach ($users as $user) {
            $html .= '<option value="' . $user->id . '">' . htmlspecialchars($user->fullName()) . '</option>';
        }

        $html .= '</select></div>';

        $stampTickets = StampTicketType::where('status', 'active')->where('is_delete', '0')->get();

        $html .= '<div class="col-md-12"><label class="form-label">Stamp Type</label></div>';

        foreach ($stampTickets as $value) {
            $userId = auth()->id();
            $stampTicketId = $value->id;
            $userStampCount = StampTicket::where('user_id', $userId)->where('stamp_ticket_id', $stampTicketId)->where('is_delete', '0')->sum('quantity');

            $html .= '<div class="row">
                  <div class="col-md-4">
                  <div class="form-check my-2">
                  <input class="form-check-input" type="checkbox" name="stamp_ticket_id[' . $value->id . ']" value="' . $value->id . '" id="stamp_ticket_id' . $value->id . '">
                  <label class="form-check-label" for="stamp_ticket_id' . $value->id . '">' . htmlspecialchars($value->name) . ' (' . $userStampCount . ')</label>
                  </div></div>
                  <div class="col-md-8 mb-3">
                  <input type="number" class="form-control" name="quantity[' . $value->id . ']" placeholder="Enter Quantity" max="' . $userStampCount . '" />
                  </div></div>';
        }

        $html .= '<div class="mb-3">
              <label class="form-label">Description</label>
              <textarea name="comment" class="form-control" rows="3" placeholder="Enter Description"></textarea>
              </div>';

        $data = ['status' => 'ok', 'html' => $html];

        return response()->json($data);
    }
}
