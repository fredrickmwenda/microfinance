<?php

namespace App\Http\Controllers;

use App\Helpers\LoanHelper;
use Illuminate\Http\Request;
use App\Jobs\SendPasswordResetEmail;
use App\Models\branch;
use App\Models\User as ModelsUser;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    //
    public function index()
    {
        if (Auth()->user()->can('admin.list')) {
            //get all users
            $users =User::all();

            return view('admin.admin.index', compact('users'));
        }
    }
    //create new user
    public function create()
    {
        //
        $roles = Role::all();
        $branches = branch::where('status', 'active')->get();
        return view('admin.admin.create', compact('roles', 'branches'));
    }
    //store new user
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|unique:users',
            'national_id' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role_id' => 'required|integer|exists:roles,id',
            'branch_id' => 'required|integer|exists:branches,id',
            // 'is_active' => 'required|integer|max:1',
        ]);
        if(!LoanHelper::checkPhoneNumber($request->phone)){
            Session::flash('error', 'Phone number is not valid');
            \Log::info('Phone number is not valid');
            return redirect()->back()->with('error', 'Phone number is not valid');
        }


        $user = new ModelsUser;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        //phone
        $user->phone = $request->phone;
        $user->national_id = $request->national_id;
        $user->password = Hash::make($request->password);
        $user->role_id = $request->role_id;
        $user->role = "user";
        $user->branch_id = $request->branch_id;
        $user->email_verified = "no";
        
        $user->save();
        //get thre role of the user
        $role = Role::find($request->role_id);
        $user->assignRole($role->name);
        Session::flash('message', 'User created successfully!');
        return redirect('/admin/users');
        // return redirect()->route('admin.admin.index');
    }

    public function show($id)
    {
        //
        $user = User::find($id);
        return view('admin.admin.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $user = ModelsUser::find($id);
        return view('admin.edit', compact('user'));
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
        //
        $this->validate($request, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
            'password' => 'sometimes|string|min:6|confirmed',
            'role_id' => 'required|integer|exists:roles,id',
            'branch_id' => 'required|integer|exists:branches,id',
            // 'is_active' => 'required|integer|max:1',
        ]);
        $user = ModelsUser::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->save();
        Session::flash('message', 'User updated successfully!');
        return redirect()->route('admin.index');
    }

//admin profile
    public function profile()
    {
        $user_profile = Auth::user();
        return view('admin.admin.profile', compact('user_profile'));
    }

    public function update_profile(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,'.Auth::user()->id,
            'phone' => 'unique:users,phone,'.Auth::user()->id,
            'national_id' => 'string|max:255|unique:users,national_id,'.Auth::user()->id,
            'password' => 'sometimes|string|min:6|confirmed',
            'bio' => 'sometimes|string|max:255',
            // 'role_id' => 'required|integer|exists:roles,id',
            

            // 'is_active' => 'required|integer|max:1',
        ]);
        // dd($request->all());
        $user = ModelsUser::find(Auth::user()->id);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->national_id = $request->national_id;
        if($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->bio = $request->bio;

        if ($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture');
            $name = time().'.'.$image->getClientOriginalExtension();
            //save image to public folder
            $destinationPath = public_path('/assets/images/profile');
            $image->move($destinationPath, $name);
            //check if there is an old image
            if (!empty(Auth::user()->avatar)) {
                //delete old image
                $old_image = public_path('/assets/images/profile/'.Auth::user()->avatar);
                if (file_exists($old_image)) {
                    unlink($old_image);
                }

            }
 

            $user->avatar = $name;
        }

       // check if the user has a prprofile_pictureofile image
        // if ($request->hasFile('profile_picture')) {
  
        //     $fileNameWithExt = $request->file('profile_picture')->getClientOriginalName();
        //     $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
        //     $extension = $request->file('profile_picture')->getClientOriginalExtension();
        //     $fileNameToStore = $filename . '_' . time() . '.' . $extension;
       
        //     $path = $request->file('profile_picture')->storeAs('public/assets/profile_picture', $fileNameToStore);

            
        //     // \Storage::delete('public/assets/profile_picture/' . Auth::user()->avatar);
           
        //     $user->avatar = $fileNameToStore;

           


        // } 
        // else {
        //     $fileNameToStore = 'noimage.jpg';
        // }
        // $user->avatar = $fileNameToStore;

        $user->save();
        Session::flash('message', 'User updated successfully!');
        return redirect()->route('profile');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $user = ModelsUser::find($id);
        $user->delete();
        Session::flash('message', 'User deleted successfully!');
        return redirect()->route('admin.index');
    }


    public function delete_users(Request $request)
    {

        if (Auth()->user()->can('admin.delete')) {
            
                if ($request->status == 'delete') {
                    if ($request->ids) {
                        foreach ($request->ids as $id) {
                            User::destroy($id);
                        }
                    }
                }
                else{
                   
                    if ($request->ids) {
                        foreach ($request->ids as $id) {
                            $post = User::find($id);
                            $post->status = $request->status;
                            $post->save();
                        }
                    }
                }
            
        }

        return response()->json('Success');
    }




    /**
     * Restore the specified resource from storage.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        //
        $user = ModelsUser::withTrashed()->find($id);
        $user->restore();
        Session::flash('message', 'User restored successfully!');
        return redirect()->route('admin.index');
    }
    /**
     * Permanently delete the specified resource from storage.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function forceDelete($id)
    {
        //
        $user = ModelsUser::withTrashed()->find($id);
        $user->forceDelete();
        Session::flash('message', 'User deleted permanently!');
        return redirect()->route('admin.index');
    }

    public function sendPasswordResetLink(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string|email|max:255',
        ]);
        $user = ModelsUser::where('email', $request->email)->first();
        if (!$user) {
            Session::flash('message', 'We can\'t find a user with that e-mail address.');
            return redirect()->route('admin.index');
        }
        $this->dispatch(new SendPasswordResetEmail($user));
        Session::flash('message', 'We have e-mailed your password reset link!');
        return redirect()->route('admin.index');
    }

    public function passwordReset(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);
        $user = ModelsUser::where('email', $request->email)->first();
        if (!$user) {
            Session::flash('message', 'We can\'t find a user with that e-mail address.');
            return redirect()->route('admin.index');
        }
        $user->password = Hash::make($request->password);
        $user->save();
        Session::flash('message', 'Password changed successfully!');
        return redirect()->route('admin.index');
    }

    public function changePassword(Request $request)
    {
      
      $this->validate($request, [
        'old_password' => 'required',
        'new_password' => 'required|min:6',
      ]);
  
      $user = User::find(Auth::user()->id);
      if (Hash::check($request->old_password, $user->password)) {
        # code...
        $user->password = Hash::make($request->new_password);
        $user->save();
        //return redirect()->back()->with('success', 'Password changed successfully.');
        return response()->json(['success' => 'Password changed successfully.']);
      } else {
        return response()->json(['error' => 'Old password is incorrect.']);
      }
  
    }

    //settings page

    public function settingsPage(){
        return view ('admin.settings.env');
    }


    public function updateSettings(Request $request){
        //update env
        $env_update = $this->changeEnv([
            'APP_NAME' => $request->app_name,
            'APP_URL' => $request->app_url,
            'MAIL_FROM_ADDRESS' => $request->mail_from_address,
            'MAIL_FROM_NAME' => $request->mail_from_name,
            'MAIL_DRIVER' => $request->mail_driver,
            'MAIL_HOST' => $request->mail_host,
            'MAIL_PORT' => $request->mail_port,
            'MAIL_USERNAME' => $request->mail_username,
            'MAIL_PASSWORD' => $request->mail_password,
            'MAIL_ENCRYPTION' => $request->mail_encryption,
            'MAILGUN_DOMAIN' => $request->mailgun_domain,
            'MAILGUN_SECRET' => $request->mailgun_secret,
            'PUSHER_APP_ID' => $request->pusher_app_id,
            'PUSHER_APP_KEY' => $request->pusher_app_key,
            'PUSHER_APP_SECRET' => $request->pusher_app_secret,
            'PUSHER_APP_CLUSTER' => $request->pusher_app_cluster,
            'MIX_PUSHER_APP_KEY' => $request->mix_pusher_app_key,
            'MIX_PUSHER_APP_CLUSTER' => $request->mix_pusher_app_cluster,
            'DB_CONNECTION' => $request->db_connection,
            'DB_HOST' => $request->db_host,
            'DB_PORT' => $request->db_port,
            'DB_DATABASE' => $request->db_database,
            'DB_USERNAME' => $request->db_username,
            'DB_PASSWORD' => $request->db_password,
        ]);
        return redirect()->back()->with('success', 'Settings updated successfully.');
    }

    //logout admin
    // public function logout()
    // {
    //     Auth::logout();
    //     //clear session
    //     Session::flush();
        
    //     return redirect()->route('auth.login');
    // }

}
