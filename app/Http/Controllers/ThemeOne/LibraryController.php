<?php

namespace App\Http\Controllers\ThemeOne;

use App\Http\Controllers\Controller;
use App\Models\Master;
use App\Models\HrLibrary;
use Illuminate\Http\Request;
use App\Models\ManageLibrary;
use Illuminate\Support\Facades\Validator;

class LibraryController extends Controller
{
    public function hr()
    {
        return view('theme-one.library.hr_library.index');
    }
    public function hr_create()
    {
        $resource_types = Master::where('type', 'resource_type')->where('status', 'active')->where('is_delete', '0')->get();
        return view('theme-one.library.hr_library.create', compact('resource_types'));
    }

    public function hr_store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'title' => 'required',
            'resource_type' => 'required',
        ]);
        if ($validation->fails()) {
            return response()->json(['error' => $validation->errors()]);
        }

        // echo "<pre>"; print_r($request->all()); die();
        $modal = new HrLibrary();
        $modal->title = $request->title;
        $modal->resource_type = $request->resource_type;
        $modal->author = $request->author;
        $modal->publisher = $request->publisher;
        $modal->description = $request->description;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $ext = $file->getClientOriginalExtension();
            $FileName = str_replace(' ', '_', $request->title) . '_' . date('y-m-d-h-i') . '.' . $ext;
            $file->move(public_path('uploads/library'), $FileName);
            $modal->file = $FileName;
        }
        $modal->save();

        return response()->json(['success' => true, 'message' => 'hr Library Added Successfully']);
    }

    public function hr_list(Request $request)
    {
        $column = ['id', 'title', 'resource_type', 'author', 'publisher', 'file', 'created_at', 'id'];
        $users = HrLibrary::where('is_delete', '0');

        $total_row = $users->get()->count();
        if (!empty($_POST['search']['value'])) {
            $users->where('title', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $users->orWhere('author', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $users->orWhere('publisher', 'LIKE', '%' . $_POST['search']['value'] . '%');
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
            $action  = '<a href="' . route('user.library.hr_edit', $value->id) . '" class="btn btn-warning btn-sm m-1">Edit</a>';
            $action .= '<a href="javascript:void(0);" onclick="deleted(`' . route('user.library.hr_delete', $value->id) . '`);" class="btn btn-danger btn-sm m-1">Delete</a>';
            $file = '<a target="blank" href="' . asset('uploads/library/' . $value->file) . '" class="btn btn-sm btn-primary"><i class="fas fa-lg fa-fw fa-file"></i></a>';

            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = $value->title;
            $sub_array[] = getMasterName($value->resource_type);
            $sub_array[] = $value->author ?? 'N/A';
            $sub_array[] = $value->publisher ?? 'N/A';
            $sub_array[] = !empty($value->file) ? $file : 'N/A';
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

    public function hr_edit($id)
    {
        $hr = HrLibrary::find($id);
        $resource_types = Master::where('type', 'resource_type')->where('status', 'active')->where('is_delete', '0')->get();
        return view('theme-one.library.hr_library.edit', compact('hr', 'resource_types'));
    }
    public function hr_update(Request $request, $id)
    {

        $validation = Validator::make($request->all(), [
            'title' => 'required',
            'resource_type' => 'required',
        ]);
        if ($validation->fails()) {
            return response()->json(['error' => $validation->errors()]);
        }
        $modal = HrLibrary::find($id);
        $modal->title = $request->title;
        $modal->resource_type = $request->resource_type;
        $modal->author = $request->author;
        $modal->publisher = $request->publisher;
        $modal->description = $request->description;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $ext = $file->getClientOriginalExtension();
            $FileName = str_replace(' ', '_', $request->title) . '_' . date('y-m-d-h-i') . '.' . $ext;
            $file->move(public_path('uploads/library'), $FileName);
            $modal->file = $FileName;
        }
        $modal->save();
        return response()->json(['success' => true, 'message' => 'HR Library Updated Successfully']);
    }

    public function hr_delete($id)
    {
        $modal = HrLibrary::find($id);
        $modal->is_delete = '1';
        $modal->status = 'inactive';
        $modal->save();
        return response()->json(['success' => true, 'message' => 'HR Library Deleted Successfully']);
    }


    public function car()
    {
        return view('theme-one.library.car_library.index');
    }
    public function car_create()
    {
        $libraries = ManageLibrary::where('type', 'car')->where('status', 'active')->where('is_delete', '0')->get();
        // echo "<pre>"; print_r($libraries); die();
        return view('theme-one.library.car_library.create', compact('libraries'));
    }

    public function car_store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'title' => 'required',
        ]);
        if ($validation->fails()) {
            return response()->json(['error' => $validation->errors()]);
        }

        // echo "<pre>"; print_r($request->all()); die();
        $modal = new ManageLibrary();
        $modal->title = $request->title;
        $modal->description = $request->description;
        $modal->parent_id = $request->parent_id;
        $modal->type = 'car';
        $modal->status = 'active';
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $ext = $file->getClientOriginalExtension();
            $FileName = str_replace(' ', '_', $request->title) . '_' . date('y-m-d-h-i') . '.' . $ext;
            $file->move(public_path('uploads/library'), $FileName);
            $modal->file = $FileName;
        }
        $modal->save();

        return response()->json(['success' => true, 'message' => 'Car Library Added Successfully']);
    }

    public function car_list(Request $request)
    {
        $column = ['id', 'title', 'parent_id', 'file', 'created_at', 'id'];
        $users = ManageLibrary::where('type', 'car')->where('is_delete', '0');

        $total_row = $users->get()->count();
        if (!empty($_POST['search']['value'])) {
            $users->where('title', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $users->orWhere('parent_id', 'LIKE', '%' . $_POST['search']['value'] . '%');
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
            $action  = '<a href="' . route('user.library.car_edit', $value->id) . '" class="btn btn-warning btn-sm m-1">Edit</a>';
            $action .= '<a href="javascript:void(0);" onclick="deleted(`' . route('user.library.car_delete', $value->id) . '`);" class="btn btn-danger btn-sm m-1">Delete</a>';
            $file = '<a target="blank" href="' . asset('uploads/library/' . $value->file) . '" class="btn btn-sm btn-primary"><i class="fas fa-lg fa-fw fa-file"></i></a>';
            $title = '<a href="' . route('user.library.library_view', ['type' => 'car', 'id' => $value->id]) . '">' . $value->title . '</a>';
            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = $title;
            // $sub_array[] = $value->parent->title ?? 'N/A';
            $sub_array[] = !empty($value->file) ? $file : 'N/A';
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

    public function car_edit($id)
    {
        $car = ManageLibrary::find($id);
        $libraries = ManageLibrary::where('type', 'car')->where('id', '!=', $id)->where('status', 'active')->where('is_delete', '0')->get();
        return view('theme-one.library.car_library.edit', compact('car', 'libraries'));
    }
    public function car_update(Request $request, $id)
    {

        $validation = Validator::make($request->all(), [
            'title' => 'required',
        ]);
        if ($validation->fails()) {
            return response()->json(['error' => $validation->errors()]);
        }
        $modal = ManageLibrary::find($id);
        $modal->title = $request->title;
        $modal->description = $request->description;
        $modal->parent_id = $request->parent_id;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $ext = $file->getClientOriginalExtension();
            $FileName = str_replace(' ', '_', $request->title) . '_' . date('y-m-d-h-i') . '.' . $ext;
            $file->move(public_path('uploads/library'), $FileName);
            $modal->file = $FileName;
        }
        $modal->save();
        return response()->json(['success' => true, 'message' => 'Car Library Updated Successfully']);
    }

    public function car_delete($id)
    {
        $modal = ManageLibrary::find($id);
        $modal->is_delete = '1';
        $modal->status = 'inactive';
        $modal->save();
        return response()->json(['success' => true, 'message' => 'Car Library Deleted Successfully']);
    }

    public function fsdms()
    {
        return view('theme-one.library.fsdms_library.index');
    }
    public function fsdms_create()
    {
        $libraries = ManageLibrary::where('type', 'fsdms')->where('status', 'active')->where('is_delete', '0')->get();
        // echo "<pre>"; print_r($libraries); die();
        return view('theme-one.library.fsdms_library.create', compact('libraries'));
    }

    public function fsdms_store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'title' => 'required',
        ]);
        if ($validation->fails()) {
            return response()->json(['error' => $validation->errors()]);
        }

        // echo "<pre>"; print_r($request->all()); die();
        $modal = new ManageLibrary();
        $modal->title = $request->title;
        $modal->description = $request->description;
        $modal->parent_id = $request->parent_id;
        $modal->type = 'fsdms';
        $modal->status = 'active';
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $ext = $file->getClientOriginalExtension();
            $FileName = str_replace(' ', '_', $request->title) . '_' . date('y-m-d-h-i') . '.' . $ext;
            $file->move(public_path('uploads/library'), $FileName);
            $modal->file = $FileName;
        }
        $modal->save();

        return response()->json(['success' => true, 'message' => 'fsdms Library Added Successfully']);
    }

    public function fsdms_list(Request $request)
    {
        $column = ['id', 'title', 'parent_id', 'file', 'created_at', 'id'];
        $users = ManageLibrary::where('type', 'fsdms')->where('is_delete', '0');

        $total_row = $users->get()->count();
        if (!empty($_POST['search']['value'])) {
            $users->where('title', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $users->orWhere('parent_id', 'LIKE', '%' . $_POST['search']['value'] . '%');
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
            $action  = '<a href="' . route('user.library.fsdms_edit', $value->id) . '" class="btn btn-warning btn-sm m-1">Edit</a>';
            $action .= '<a href="javascript:void(0);" onclick="deleted(`' . route('user.library.fsdms_delete', $value->id) . '`);" class="btn btn-danger btn-sm m-1">Delete</a>';
            $file = '<a target="blank" href="' . asset('uploads/library/' . $value->file) . '" class="btn btn-sm btn-primary"><i class="fas fa-lg fa-fw fa-file"></i></a>';
            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = $value->title;
            $sub_array[] = $value->parent->title ?? 'N/A';
            $sub_array[] = !empty($value->file) ? $file : 'N/A';
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

    public function fsdms_edit($id)
    {
        $fsdms = ManageLibrary::find($id);
        $libraries = ManageLibrary::where('type', 'fsdms')->where('id', '!=', $id)->where('status', 'active')->where('is_delete', '0')->get();
        return view('theme-one.library.fsdms_library.edit', compact('fsdms', 'libraries'));
    }
    public function fsdms_update(Request $request, $id)
    {

        $validation = Validator::make($request->all(), [
            'title' => 'required',
        ]);
        if ($validation->fails()) {
            return response()->json(['error' => $validation->errors()]);
        }
        $modal = ManageLibrary::find($id);
        $modal->title = $request->title;
        $modal->description = $request->description;
        $modal->parent_id = $request->parent_id;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $ext = $file->getClientOriginalExtension();
            $FileName = str_replace(' ', '_', $request->title) . '_' . date('y-m-d-h-i') . '.' . $ext;
            $file->move(public_path('uploads/library'), $FileName);
            $modal->file = $FileName;
        }
        $modal->save();
        return response()->json(['success' => true, 'message' => 'FSDMS Library Updated Successfully']);
    }

    public function fsdms_delete($id)
    {
        $modal = ManageLibrary::find($id);
        $modal->is_delete = '1';
        $modal->status = 'inactive';
        $modal->save();
        return response()->json(['success' => true, 'message' => 'FSDMS Library Deleted Successfully']);
    }


    public function generic()
    {
        return view('theme-one.library.generic_library.index');
    }
    public function generic_create()
    {
        $libraries = ManageLibrary::where('type', 'generic')->where('status', 'active')->where('is_delete', '0')->get();
        // echo "<pre>"; print_r($libraries); die();
        return view('theme-one.library.generic_library.create', compact('libraries'));
    }

    public function generic_store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'title' => 'required',
        ]);
        if ($validation->fails()) {
            return response()->json(['error' => $validation->errors()]);
        }

        // echo "<pre>"; print_r($request->all()); die();
        $modal = new ManageLibrary();
        $modal->title = $request->title;
        $modal->description = $request->description;
        $modal->parent_id = $request->parent_id;
        $modal->type = 'generic';
        $modal->status = 'active';
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $ext = $file->getClientOriginalExtension();
            $FileName = str_replace(' ', '_', $request->title) . '_' . date('y-m-d-h-i') . '.' . $ext;
            $file->move(public_path('uploads/library'), $FileName);
            $modal->file = $FileName;
        }
        $modal->save();

        return response()->json(['success' => true, 'message' => 'Generic Library Added Successfully']);
    }

    public function generic_list(Request $request)
    {
        $column = ['id', 'title', 'parent_id', 'file', 'created_at', 'id'];
        $users = ManageLibrary::where('type', 'generic')->where('is_delete', '0');

        $total_row = $users->get()->count();
        if (!empty($_POST['search']['value'])) {
            $users->where('title', 'LIKE', '%' . $_POST['search']['value'] . '%');
            $users->orWhere('parent_id', 'LIKE', '%' . $_POST['search']['value'] . '%');
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
            $action  = '<a href="' . route('user.library.generic_edit', $value->id) . '" class="btn btn-warning btn-sm m-1">Edit</a>';
            $action .= '<a href="javascript:void(0);" onclick="deleted(`' . route('user.library.generic_delete', $value->id) . '`);" class="btn btn-danger btn-sm m-1">Delete</a>';
            $file = '<a target="blank" href="' . asset('uploads/library/' . $value->file) . '" class="btn btn-sm btn-primary"><i class="fas fa-lg fa-fw fa-file"></i></a>';
            $sub_array = array();
            $sub_array[] = ++$key;
            $sub_array[] = $value->title;
            $sub_array[] = $value->parent->title ?? 'N/A';
            $sub_array[] = !empty($value->file) ? $file : 'N/A';
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

    public function generic_edit($id)
    {
        $generic = ManageLibrary::find($id);
        $libraries = ManageLibrary::where('type', 'generic')->where('id', '!=', $id)->where('status', 'active')->where('is_delete', '0')->get();
        return view('theme-one.library.generic_library.edit', compact('generic', 'libraries'));
    }
    public function generic_update(Request $request, $id)
    {

        $validation = Validator::make($request->all(), [
            'title' => 'required',
        ]);
        if ($validation->fails()) {
            return response()->json(['error' => $validation->errors()]);
        }
        $modal = ManageLibrary::find($id);
        $modal->title = $request->title;
        $modal->description = $request->description;
        $modal->parent_id = $request->parent_id;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $ext = $file->getClientOriginalExtension();
            $FileName = str_replace(' ', '_', $request->title) . '_' . date('y-m-d-h-i') . '.' . $ext;
            $file->move(public_path('uploads/library'), $FileName);
            $modal->file = $FileName;
        }
        $modal->save();
        return response()->json(['success' => true, 'message' => 'Generic Library Updated Successfully']);
    }

    public function generic_delete($id)
    {
        $modal = ManageLibrary::find($id);
        $modal->is_delete = '1';
        $modal->status = 'inactive';
        $modal->save();
        return response()->json(['success' => true, 'message' => 'Generic Library Deleted Successfully']);
    }

    public function library_view($type, $id = null)
    {
        return view('theme-one.library.car_library.index');
    }
}
