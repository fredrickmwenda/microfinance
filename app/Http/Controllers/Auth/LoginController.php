<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            //delete all session from database, if user is admin
            // DB::table('sessions')->where('user_id', Auth::user()->id)->delete();
            return route('admin.dashboard');
        } elseif ($role == 2 || $role == 7) {
            //delete all session from database, if user is agent
            // DB::table('sessions')->where('user_id', Auth::user()->id)->delete();
            return route('agent.dashboard');
        }

    }

    public function logout(Request $request) {
        Auth::logout();
        Session::flush();
        return redirect()->route('login');
    }
}
