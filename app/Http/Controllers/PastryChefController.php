<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PastryChef;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PastryChefController extends Controller
{
    public function index()
    {
        $user = Auth::user();
    
        // Build the visible user IDs
        if (is_null($user->created_by)) {
            // ROOT user: self + any users they created
            $visibleUserIds = \App\Models\User::where('created_by', $user->id)
                                    ->pluck('id')
                                    ->push($user->id)
                                    ->unique();
        } else {
            // CHILD user: self + their creator
            $visibleUserIds = collect([$user->id, $user->created_by])->unique();
        }
    
        // Fetch chefs in your group OR with status = 'Default'
        $pastryChefs = \App\Models\PastryChef::with('user')
            ->where(function($q) use ($visibleUserIds) {
                $q->whereIn('user_id', $visibleUserIds)
                  ->orWhere('status', 'Default');
            })
            ->latest()
            ->get();
    
        return view('frontend.pastry-chefs.index', compact('pastryChefs'));
    }
    
    
    
    
    /**
     * Show the form for creating a new chef.
     */
    public function create()
    {
        return view('frontend.pastry-chefs.create');
    }

    /**
     * Store a newly created chef for this user.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        // stamp with the current user's ID
        $data['user_id'] = Auth::id();

        PastryChef::create($data);

        return redirect()
            ->route('pastry-chefs.index')
            ->with('success', 'Chef added successfully!');
    }

    /**
     * Show the form for editing the specified chef (only if it belongs to the user).
     */
    public function edit(PastryChef $pastryChef)
    {
        if ($pastryChef->user_id !== Auth::id()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        return view('frontend.pastry-chefs.create', compact('pastryChef'));
    }
    
    /**
     * Update the specified chef (only if it belongs to the user).
     */
    public function update(Request $request, PastryChef $pastryChef)
    {
        if ($pastryChef->user_id !== Auth::id()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $pastryChef->update($data);

        return redirect()
            ->route('pastry-chefs.index')
            ->with('success', 'Chef updated successfully!');
    }
    public function show(PastryChef $pastryChef)
    {
        return view('frontend.pastry-chefs.show', compact('pastryChef'));
    }
    /**
     * Remove the specified chef (only if it belongs to the user).
     */
    public function destroy(PastryChef $pastryChef)
    {
        if ($pastryChef->user_id !== Auth::id()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $pastryChef->delete();

        return redirect()
            ->route('pastry-chefs.index')
            ->with('success', 'Chef deleted successfully!');
    }
}
