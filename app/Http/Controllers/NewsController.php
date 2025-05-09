<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\NewsNotificationCreated;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::latest()
                    ->where('is_active', true)
                    ->get();

        return view('frontend.news.index', compact('news'));
    }

    public function create()
    {
        // No $news—fresh form
        return view('frontend.news.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'      => 'required|string|max:255',
            'content'    => 'required|string',
            'event_date' => 'required|date',
        ]);

        $validated['user_id'] = Auth::id();
        $news = News::create($validated);

        $notification = Notification::create([
            'title'   => 'New Event: ' . $news->title,
            'message' => 'A new event has been added: ' . $news->content,
            'user_id' => Auth::id(),
        ]);

        broadcast(new NewsNotificationCreated($notification));

        return redirect()
            ->route('news.index')
            ->with('success', 'News created successfully.');
    }

    public function edit(News $news)
    {
        // Reuse the same view, passing in $news
        return view('frontend.news.create', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title'      => 'required|string|max:255',
            'content'    => 'required|string',
            'event_date' => 'required|date',
        ]);

        $news->update($validated);

        return redirect()
            ->route('news.index')
            ->with('success', 'News updated successfully.');
    }

    public function destroy(News $news)
    {
        $news->delete();

        return redirect()
            ->route('news.index')
            ->with('success', 'News deleted successfully.');
    }
}
