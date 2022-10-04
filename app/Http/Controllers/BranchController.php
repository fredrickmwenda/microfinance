<?php

namespace App\Http\Controllers;

use App\Models\branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $branches = branch::all();
        return view('admin.branch.index', compact('branches'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.branch.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        // $request->validate([
        //     'branch_name'   => 'required',
        //     'phone'  => 'required',
        //     'email' => 'required|email',
        //     'address'       => 'required',
        //     'postal_code'   => 'required',
        //     'city'          => 'required',       
        //     'status'        => 'required'
        // ]);

        $request->validate([
            'branch_name'   => 'required',
            'phone'  => 'required',
            'email_address' => 'required|email',
            'address'       => 'required',
            'postal_code'   => 'required',
            'city'          => 'required',
            'status'        => 'required'
        ]);
       
        $branch = new branch();
        $branch->name = $request->input('branch_name');
        $branch->phone = $request->input('phone');
        $branch->email = $request->input('email_address');
        $branch->address = $request->input('address');
        $branch->postal_code = $request->input('postal_code');
        $branch->city = $request->input('city');
        $branch->description = $request->input('description');
        $branch->status = $request->input('status');
        $branch->save();
        return redirect()->route('admin.branch.index')->with('success', 'Branch Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function show(branch $branch)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $branch = branch::find($id);
        return view('admin.branch.edit', compact('branch'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate([
            'branch_name'   => 'required',
            'phone'  => 'required',
            'email' => 'required|email',
            'address'       => 'required',
            'postal_code'   => 'required',
            'city'          => 'required',       
            'status'        => 'required'
        ]);

        $branch = branch::find($id);
        $branch->name = $request->input('branch_name');
        $branch->phone = $request->input('phone');
        $branch->email = $request->input('email');
        $branch->address = $request->input('address');
        $branch->postal_code = $request->input('postal_code');
        $branch->city = $request->input('city');
        $branch->description = $request->input('description');
        $branch->status = $request->input('status');
        
        $branch->save();
        return redirect('/admin/branch')->with('success', 'Branch Updated Successfully');
        // return redirect()->route('admin.branch.index')->with('success', 'Branch Updated Successfully');
       // return redirect('/admin/branch')->with('success', 'Branch Updated');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
                // Branch Delete
        $branch_delete = branch::findOrFail($id);
        $branch_delete->delete();
        return redirect()->back()->with('success', 'Successfully Deleted'); 
    }
}
