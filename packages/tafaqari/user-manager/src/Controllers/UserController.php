<?php

namespace Tafaqari\UserManager\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\SendDeactivationEmail;
use Illuminate\Http\Request;
use App\User;
use Hash;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use DB;
use Config;
use App\Models\UserGroup;
use App\Jobs\SendPasswordResetEmail;
use App\Models\User as ModelsUser;
use App\Models\UserGroupRole;

class UserController extends Controller
{


    public function __construct()
    {
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $users = ModelsUser::orderByDesc('id')->paginate();

        $roles = Role::get();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $users,
            ]);
        }
    


        return view('user-manager::users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Get all roles and pass it to the view
        $roles = Role::get();
        return view('user-manager::users.create', ['roles' => $roles]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {



        if (empty($request->roles)) {
            # code...
            return back()->with('error', 'Please select at least one role');
        }



       // Validate name, email and password fields
        $this->validate($request, [
            'name' => 'required|max:1200',
            'email' => 'required|email|unique:users',
            'personal_number' => 'required|unique:users',
            'password' => 'sometimes|min:6|confirmed',
            'national_id' => 'required|unique:users',
            'phone_number' => 'sometimes|max:10|min:10|starts_with:0',
            'branch_id' => 'required',
        ]);

        $request->merge(['status' => 'active']);

        $user = ModelsUser::create($request->except('roles')); //Create user
        $user->assignRole($request->roles); //Assign role to user
        $user->save();




        // User::create($request->all()); //Retrieving only the email and password data

        //Add roles belonging to that user group

        //Assign user to groups
        // foreach ($request->user_groups as $group) {
        //     # code...
        //     $insert = DB::table('user_groups_pivot')->insert(
        //         ['user_id' => $user->id, 'group_id' => $group]
        //     );

        //     $user_group_roles = UserGroup::with('roles.role')->where('id', $group)->first()->roles;

        //     foreach ($user_group_roles as $role) {
        //         # code...
        //         // dd($role->role->id);
        //         $role_id = $role->role->id;
        //         //check if user already has the role
        //         $rolee = DB::table('model_has_roles')->where('model_id', $user->id)->where('role_id', $role_id)->first();

        //         if (is_null($rolee)) {
        //             # role doesnt exist, assign
        //             $user->assignRole($role->role);
        //             $roleAssignment = DB::table('model_has_roles')
        //                 ->where('role_id', $role_id)
        //                 ->where('model_id', $user->id)
        //                 ->update(['assigned_by' => Auth::user()->id, 'assigned_at' => now()]);
        //         }
        //     }
        // }


        /**
         * Log event
         */

        $model = new ModelsUser();

        activity()
            ->performedOn($model)
            ->causedBy(Auth::user())
            ->log('New User Created; Personal Number: ' . $user->personal_number);

        /**
         * End event log
         */


        //Notify the user through their email

        dispatch(new SendPasswordResetEmail($request->email));

        //Redirect to the users.index view and display message
        return redirect()->route('users.index')
            ->with(
                'flash_message',
                'User successfully added.'
            );
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $user,
            ]);
        }

        return view('user-manager::users.show', compact('user'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        $user = User::find(Auth::id());

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $user,
            ]);
        }

        return view('user-manager::users.profile', compact('user'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @param UserTypeRepository $typeRepository
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $user = User::findOrFail($id); //Get user with specified id

        $roles = Role::get(); //Get all roles

        $pivots = [];

        $user_group_pivots = DB::table('user_groups_pivot')->where('user_id', $id)->get();

        foreach ($user_group_pivots as $p_group) {
            # code...
            $pivots[] = $p_group->group_id;
        }

        return view('user-manager::users.edit', compact('user', 'roles', 'pivots')); //pass user and roles data to view

    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {

        if (empty($request->user_groups)) {
            # code...
            return back()->with('error', 'Please select at least one group');
        }

        $user = User::findOrFail($id);
        $u = User::findOrFail($id);

        // dd($request->all());

        /**
         * Log event
         */

        $model = new User();

        activity()
            ->performedOn($model)
            ->causedBy(Auth::user())
            ->log('User updated; Personal Number: ' . $user->personal_number);

        /**
         * End event log
         */

        //Validate name, email and password fields
        $this->validate($request, [
            'name' => 'required|max:120',
            'email' => 'required|email',
            'user_type_id' => 'sometimes|nullable|string',
            'phone_number' => 'sometimes|max:10|min:10|starts_with:0'
        ]);

        $input = $request->all();

        $user->fill($input)->save();

        $user_current_groups_array = [];

        $request_groups_array = $request->user_groups;
        // dd($request_groups_array);

        //get all user_group_ids that the user is in


        $user_current_groups = DB::table('user_groups_pivot')->where('user_id', $user->id)->get();
        // dd($user_current_groups);
        //pluck the group_ids from the pivot table and put them in an array

        //dd($user_current_groups);
        // dd($user_current_groups->group_id);

        foreach ($user_current_groups as $group) {
            # code...
            $user_current_groups_array[] = $group->group_id;
        }
        

        $unassigned_groups = [];

        $new_groups = [];

        //common elements in both


        //selected 1,2,3,5

        //current 1,2,3,4

        // foreach ($user_current_groups_array as $v) {
        //     # code...
        //     //if the group id is current users groups and not in the request groups array, then unassign the group. amenyanganywa
        //     if (!in_array($v, $request_groups_array)) {
        //         # code...
        //         $unassigned_groups[] = $v;
        //     }
        // }

        $unassigned_groups = array_diff($user_current_groups_array, $request_groups_array);

        $groups_to_delete = array_diff($user_current_groups_array, $request_groups_array);
        

        //Assign user to groups
        // foreach ($request_groups_array as $group) {
        //     # code...

        //     //if the user group id is in the request groups array and not in the current user groups array, then assign the group.
        //     //its a new group
        //     if (!in_array($group, $user_current_groups_array)) {
        //         # code...
        //         $new_groups[] = $group;
        //     }


        // }

        $new_groups = array_diff($request_groups_array, $user_current_groups_array);
        

        $not_new_not_removed = array_intersect($request_groups_array, $user_current_groups_array);
        // dd($groups_to_delete, $new_groups, 'Groups to Delete', 'new', $user_current_groups_array, $request_groups_array, 'current', 'requested', $not_new_not_removed);

        //remove groups from pivot table
        foreach ($groups_to_delete as $group) {
            # code...
            $user_group_roles = UserGroup::with('roles.role')->where('id', $group)->first()->roles;

            foreach ($user_group_roles as $role) {
                # code...

                $role_id = $role->role->id;
                // dd($role_id);
                //check if user already has the role

               //check if role is in the remaining/ not touched user groups
               //if it is there. do not remove the role from the user
               //else unassign the role from the user

               $exists_group = false;
               //was seleted previous and is selected current
               foreach ($not_new_not_removed as $group_iid) {
                $rolee = UserGroupRole::where('usergroup_id', $group_iid)->where('role_id', $role_id)->first();
                //if rolee is empty, then the role is not in the group and can be removed
                $exists_group = false;
                if (empty($rolee)) {
                    #remove role from user
                    // $user->detachRole($role_id);
                    $user->removeRole($role_id);
                }
                else {
                    $exists_group = true;
                    break;
                }
   


          

                
                # code...
                //   $roleee = UserGroupRole::where('usergroup_id', $group_iid)->where('role_id', $role_id)->first();
                //   dd($roleee);
                //   if ($roleee) {
                //     # code...
                //     //role is assigned to the group
                //     $exists_group = true;

                //     return;
                //   }
                //   else{
                //     //role is not assigned to the group
                //     //so unassign the role from the user
                //     $user->removeRole($role_id);
                //     //re
                //   }
                }
               

               if (!$exists_group) 
               {
                // dd('remove');
                # code...
                //unassign role from the user
                $user->removeRole($role_id);
               }

            }

            // dd('group');
    
            $user_group = DB::table('user_groups_pivot')->where('user_id', $user->id)->where('group_id', $group);

           // dd($user_group);

            $user_group->delete();

        }

    

       

        //remove groups that have been unassigned
        foreach ($new_groups as $group) {
            # code...
            DB::table('user_groups_pivot')->insert(
                ['user_id' => $user->id, 'group_id' => $group]
            );

            $user_group_roles = UserGroup::with('roles.role')->where('id', $group)->first()->roles;

            foreach ($user_group_roles as $role) {
                # code...

                $role_id = $role->role->id;
                //check if user already has the role

                # role doesnt exist, assign
                $user->assignRole($role->role);
                $roleAssignment = DB::table('model_has_roles')
                    ->where('role_id', $role_id)
                    ->where('model_id', $user->id)
                    ->update(['assigned_by' => Auth::user()->id, 'assigned_at' => now()]);
            }
        }

        $currentUser = Auth::user();

        switch ($request->status) {

            case 'active':
                # code...

                // $config = array(
                //     'driver'     => 'smtp',
                //     'host'       => "smtp.gmail.com",
                //     'port'       => "587",
                //     'from'       => array('address' =>"ambkyusya@gmail.com", 'name' => "KRA SMM"),
                //     'encryption' => 'tls',
                //     'username'   =>  "ambkyusya@gmail.com",
                //     'password'   => "voqpjjctahhhlnti"
                //   );

                //   //check if smtp credentials are okay before proceeding

                //   Config::set('mail', $config);

                (new \Illuminate\Mail\MailServiceProvider(app()))->register();

                if ($u->status == 'disabled' || $u->status == 'permanently disabled') {

                    /**
                     * Log event
                     */

                    $model = new User();

                    activity()
                        ->performedOn($model)
                        ->causedBy(Auth::user())
                        ->log('User account activated; Personal Number: ' . $user->personal_number);

                    /**
                     * End event log
                     */

                    //Account had been disabled before. Send activation email
                    dispatch(new SendDeactivationEmail('activate', $user->email));
                }


                break;

            case 'disabled':
                # code...

                /**
                 * Log event
                 */

                $model = new User();

                activity()
                    ->performedOn($model)
                    ->causedBy(Auth::user())
                    ->log('User account disabled; Personal Number: ' . $user->personal_number);

                /**
                 * End event log
                 */

                dispatch(new SendDeactivationEmail('disabled', $user->email));
                break;


            case 'permanently disabled':
                # code...

                /**
                 * Log event
                 */

                $model = new User();

                activity()
                    ->performedOn($model)
                    ->causedBy(Auth::user())
                    ->log('User account permanently disabled; Personal Number: ' . $user->personal_number);

                /**
                 * End event log
                 */

                dispatch(new SendDeactivationEmail('permanently disabled', $user->email));

                break;

            default:
                # code...
                break;
        }

        Auth::setUser($currentUser);

        return redirect()->route('users.index')
            ->with(
                'flash_message',
                'User successfully edited.'
            );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = User::destroy($id);

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'User deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'User deleted.');
    }
}
