<?php 


namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        // Fetch notifications for the logged-in user
        $notifications = Notification::where('is_new', true)
                                     ->get();
                                    //  where('user_id', Auth::id())
                                    //  ->
        return view('frontend.notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['is_read' => true, 'is_new' => false]);
        
        return redirect()->route('notifications.index');
    }
    

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'user_id' => 'required|exists:users,id', // Example user_id
        ]);

        // Store the notification as "new"
        Notification::create(array_merge($data, ['is_new' => true, 'is_read' => false]));

        return back()->with('success', 'Notification created successfully');
    }
}
