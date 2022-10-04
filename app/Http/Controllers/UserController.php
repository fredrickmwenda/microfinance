<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\SendPasswordResetEmail;
use App\Models\User as ModelsUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $users = ModelsUser::orderByDesc('id')->paginate();
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        //
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
    }
    //profile
    public function profile()
    {
        //
        return view('users.profile');
    }
    //update profile

    public function updateProfile(Request $request)
    {
        //
        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        Session::flash('message', 'Profile updated successfully!');
        return redirect()->route('user.profile');
    }
    //change password
    public function changePassword()
    {
        //
        return view('users.change-password');
    }
    //update password
    public function updatePassword(Request $request)
    {
        //
        $user = Auth::user();
        $this->validate($request, [
            'old_password' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);
        $hashedPassword = $user->password;
        if (Hash::check($request->old_password, $hashedPassword)) {
            //Change the password
            $user->fill([
                'password' => Hash::make($request->password)
            ])->save();
            Session::flash('message', 'Password changed successfully!');
            return redirect()->route('user.change-password');
        } else {
            Session::flash('error', 'Current password is incorrect!');
            return redirect()->route('user.change-password');
        }
    }

    //user performance graph according to loans created in a week, month, year
    public function userPerformance()
    {
        //
        $user = Auth::user();
        $user_id = $user->id;
        $user_loans = ModelsUser::find($user_id)->loans;
        $user_loans_count = $user_loans->count();
        $user_loans_amount = $user_loans->sum('amount');
        $user_loans_amount = number_format($user_loans_amount, 2);
        $user_loans_amount = str_replace(',', '', $user_loans_amount);
        $user_loans_amount = (float)$user_loans_amount;
        //plot graph for loans created in a week
        $user_loans_week = $user_loans->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        $user_loans_week_count = $user_loans_week->count();
        $user_loans_week_amount = $user_loans_week->sum('amount');
        $user_loans_week_amount = number_format($user_loans_week_amount, 2);
        $user_loans_week_amount = str_replace(',', '', $user_loans_week_amount);
        $user_loans_week_amount = (float)$user_loans_week_amount;
        //plot graph for loans created in a month
        $user_loans_month = $user_loans->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
        $user_loans_month_count = $user_loans_month->count();
        $user_loans_month_amount = $user_loans_month->sum('amount');
        $user_loans_month_amount = number_format($user_loans_month_amount, 2);
        $user_loans_month_amount = str_replace(',', '', $user_loans_month_amount);
        $user_loans_month_amount = (float)$user_loans_month_amount;
        //plot graph for loans created in a year
        $user_loans_year = $user_loans->whereBetween('created_at', [now()->startOfYear(), now()->endOfYear()]);
        $user_loans_year_count = $user_loans_year->count();
        $user_loans_year_amount = $user_loans_year->sum('amount');
        $user_loans_year_amount = number_format($user_loans_year_amount, 2);
        $user_loans_year_amount = str_replace(',', '', $user_loans_year_amount);
        $user_loans_year_amount = (float)$user_loans_year_amount;
        return view('users.performance', compact('user_loans_count', 'user_loans_amount', 'user_loans_week_count', 'user_loans_week_amount', 'user_loans_month_count', 'user_loans_month_amount', 'user_loans_year_count', 'user_loans_year_amount'));

    }

    
}
