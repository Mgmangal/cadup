<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:Role Add|Role Edit|Role Delete|Role View']);
    }
    public function index()
    {
        $parentId = '';
        $role = '';
        return view('settings.roles.index', compact('parentId', 'role'));
    }

    public function store(Request $request)
    {
        $name = $request->name;
        $parent_id = $request->parent_id;
        $id = $request->edit_id;
        if (empty($name)) {
            return response()->json([
                'success' => false,
                'message' => 'Role Name is required'
            ]);
        }
        try {
            if (!empty($id)) {
                $role = Role::find($id);
                $role->name = $name;
                $role->save();
            } else {
                $role = new Role();
                $role->name = $name;
                $role->save();
            }
            return response()->json([
                'success' => true,
                'message' => 'Role Added Successfully'
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
        $column = ['id', 'name', 'created_at', 'id'];
        $roles = Role::where('id', '>', '0');
        $total_row = $roles->count();
        if (isset($_POST['search'])) {
            $roles->where('name', 'LIKE', '%' . $_POST['search']['value'] . '%');
        }

        if (isset($_POST['order'])) {
            $roles->orderBy($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $roles->orderBy('id', 'desc');
        }
        $filter_row = $roles->count();
        if (isset($_POST["length"]) && $_POST["length"] != -1) {
            $roles->skip($_POST["start"])->take($_POST["length"]);
        }
        $result = $roles->get();
        $data = array();
        foreach ($result as $key => $value) {
            $action = '';
            if (auth()->user()->can('Role Edit')) {
                $action .= '<a href="javascript:void(0);" onclick="editRole(`' . route('app.settings.roles.edit', $value->id) . '`);" class="btn btn-warning btn-sm m-1">Edit</a>';
            }
            // $action = '<a href="javascript:void(0);" onclick="editRole(`'.route('app.settings.roles.edit', $value->id).'`);" class="btn btn-warning btn-sm m-1">Edit</a>';
            $action .= '<a href="' . route('app.settings.permissions', $value->id) . '" class="btn btn-success btn-sm m-1">Permission</a>';
            if ($value->id != 1 && auth()->user()->can('Role Delete')) {
                $action .= '<a href="javascript:void(0);" onclick="deleted(`' . route('app.settings.roles.destroy', $value->id) . '`);" class="btn btn-danger btn-sm m-1">Delete</a>';
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
    public function edit($id)
    {
        $role = Role::find($id);
        return response()->json([
            'success' => true,
            'data' => $role
        ]);
    }

    public function destroy($id)
    {
        $role = Role::find($id);
        $role->delete();
        $role->permissions()->detach();
        return response()->json([
            'success' => true,
            'message' => 'Role Deleted Successfully'
        ]);
    }

    public function subroles($id)
    {
        $parentId = $id;
        $role = Role::find($id);
        return view('settings.roles.index', compact('parentId', 'role'));
    }

    public function permissions($id)
    {
        $role = Role::with('permissions')->find($id);
        if ($role->parent_id != 0) {
            $permissions = Role::with('permissions')->find($role->parent_id)->permissions()->get();
        } else {
            $permissions = Permission::all();
        }
        return view('settings.roles.permissions', compact('role', 'permissions'));
    }

    public function permissionsStore($id, Request $request)
    {
        $role = Role::find($id);
        $role->permissions()->sync($request->permissions);
        return response()->json([
            'success' => true,
            'message' => 'Permissions Updated Successfully'
        ]);
    }
}
