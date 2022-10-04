<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectTo()
    { 
        $role = Auth::user()->role_id;

        if ($role == 1 ||  $role == 4) {
            return route('admin.dashboard');
        } elseif ($role == 6 ) {
            return route('agent.dashboard');
        }
        // }else{
        //     return route('home');
        // }

        // if(Auth::user()->role_id == 1) {
        //     return route('admin.dashboard');
           
        //     // if(Auth::user()->two_step_auth == 1) {
        //     //     return $this->redirectTo = route('profile.otp');
        //     // } 
            
         
        // } elseif(Auth::check() && Auth::user()->role_id == 6 || Auth::user()->role_id == 7) {
        //     return route('agent.dashboard');

        //     //  return $this->redirectTo = route('');
        //     // if(Auth::user()->two_step_auth == 1) {
        //     //    return $this->redirectTo = route('user.otp');
        //     // } else {
        //         // if (Session::has('withdraw_method_id')) 
        //         // {
        //         //     $user = User::findOrFail(Auth::id());
        //         //     session([
        //         //         'account_number' => $user->account_number,
        //         //     ]);
        //         //     return $this->redirectTo = route('user.transfer.ecurrency.confirm');
        //         // }else {
        //         //     return $this->redirectTo = route('user.dashboard');
        //         // }
            
        // }
        // else {
        //     return $this->redirectTo = route('login');
        // }
    }

    public function logout(Request $request) {
        Auth::logout();
        Session::flush();
        return redirect()->route('login');
    }
}
