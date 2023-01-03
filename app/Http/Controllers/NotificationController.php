<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    //
    public function index()
    {
        // all notifications
        $notifications = auth()->user()->notifications;
        return view('notifications.index', compact('notifications'));
    }

    public function show($id){
        $notification = auth()->user()->notifications()->where('id', $id)->first();
        return view('notifications.show', compact('notification'));
    }

    public static function all()
    {
        # code...
        return auth()->user()->notifications;
    }

    //destroy notification
    public function destroy($id){
        $notification = auth()->user()->notifications()->where('id', $id)->first();
        $notification->delete();
        return redirect()->back()->with('success', 'Notification deleted successfully');
    }

    //mark all notifications as read
    public function markAllAsRead(){
        auth()->user()->unreadNotifications->markAsRead();
        return redirect()->back()->with('success', 'All notifications marked as read');
    }

    //mark single notification as read
    public function markAsRead($id){
        $notification = auth()->user()->notifications()->where('id', $id)->first();
        $notification->markAsRead();
        return redirect()->back()->with('success', 'Notification marked as read');
    }


}
