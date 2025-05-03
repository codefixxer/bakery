<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the logged‑in user’s new notifications.
     */
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
                                     ->where('is_new', true)
                                     ->get();

        return view('frontend.notifications.index', compact('notifications'));
    }

    /**
     * Mark a single notification as read.
     */
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);

        // Only allow the owner to mark it
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->update([
            'is_read' => true,
            'is_new'  => false,
        ]);

        return redirect()->route('notifications.index');
    }

    /**
     * Store a newly created notification for the current user.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'   => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // stamp with the authenticated user's ID
        $data['user_id'] = Auth::id();

        // store as new and unread
        Notification::create(array_merge($data, [
            'is_new'  => true,
            'is_read' => false,
        ]));

        return back()->with('success', 'Notification created successfully');
    }
}
