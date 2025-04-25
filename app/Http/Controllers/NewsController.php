<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\NewsNotificationCreated;
use App\Models\Notification;
class NewsController extends Controller
{
    public function index()
    {
        // Fetch all news
        $news = News::latest()->where('is_active', true)->get();
        return view('frontend.news.index', compact('news'));
    }

    public function create()
    {
        return view('frontend.news.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'event_date' => 'required|date',
        ]);
    
        // Store the news
        $news = News::create($validated);
    
        // Create a notification for the news
        $notification = Notification::create([
            'title' => 'New Event: ' . $news->title,
            'message' => 'A new event has been added: ' . $news->content,
        ]);
    
        // Broadcast the event to notify all users
        broadcast(new NewsNotificationCreated($notification));
    
        return redirect()->route('news.index')->with('success', 'News created successfully.');
    }

    public function edit(News $news)
    {
        return view('news.edit', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'event_date' => 'required|date',
        ]);

        $news->update($validated);

        return redirect()->route('news.index')->with('success', 'News updated successfully.');
    }

    public function destroy(News $news)
    {
        $news->delete();

        return redirect()->route('news.index')->with('success', 'News deleted successfully.');
    }
}
