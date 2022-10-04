<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(FacadesAuth::user()->can('role.list')){
            // $roles = Role::where('id', '!=', 1)->get();
            $roles = Role::all();
            return view('admin.roles.index', compact('roles'));
       }
      

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        if (FacadesAuth::user()->can('role.create')) {
            $permisions = Permission::all();
            $permission_groups = User::getPermissionGroup();
            return view('admin.roles.create', compact('permisions', 'permission_groups'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:roles|max:100',
        ]);

        // $request->validate([
        //     'name' => 'unique:roles|max:100',
        // ]);
        //dd($request->name);
        $role = Role::create(['name' => $request->name]);
        $permissions = $request->input('permissions');
        
        //store permission id
        // foreach ($permissions as $permission) {
        //     $role->givePermissionTo($permission);
        //     //assign permission to role
        //     $permission = Permission::findById($permission);
        //     $permission->assignRole($role);
        //     //sync permission to role
        //     $role->syncPermissions($permissions);
        // }
        
        if (!empty($permissions)) {
            $role->syncPermissions($permissions);
        }

        //redirect to role list with success message
        return redirect()->route('admin.role.index')
            ->with('success', 'Role created successfully.');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (FacadesAuth::user()->can('role.edit')) {
            $role = Role::find($id);
            $permisions = Permission::all();
     
            $permission_groups = User::getPermissionGroup();
            // foreach ($permission_groups as $key => $value) {
            //     dd($value->group_name);
            //     $permission_groups[$key]['permissions'] = $role->permissions()->where('group', $value['group'])->get();

            // }
            return view('admin.roles.edit', compact('role', 'permisions', 'permission_groups'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:100|unique:roles,name,' . $id
        ], [
            'name.requried' => 'Please give a role name'
        ]);
        $role = Role::find($id);
        $role->name = $request->name;
        $role->save();
        $permissions = $request->input('permissions');
        if (!empty($permissions)) {
            $role->syncPermissions($permissions);
        }
        return redirect()->route('admin.role.index')
        ->with('success', 'Role created successfully.');
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function destroy(Request $request)
    {
        // dd($request->all());
        if (Auth()->user()->can('role.delete')) {
            
            if ($request->status == 'delete') {
                if ($request->ids) {
                    foreach ($request->ids as $id) {
                        //detach all permissions
                        $role = Role::find($id);
                        // dd($role);
                        $role->permissions()->detach();
                        //delete role
                        $role->delete();
                        // Role::destroy($id);
                    }
                }
            }

            return redirect()->route('admin.role.index')
                ->with('success', 'Role deleted successfully.');

            
        }
    }

}
