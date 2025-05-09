<?php

    namespace App\Http\Controllers;

    use App\Models\News;
    use App\Models\User;
    use App\Models\Notification;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use App\Events\NewsNotificationCreated;

    class NewsController extends Controller
    {


        public function show($id)
        {
            // Find the news by its ID, or fail if not found
            $news = News::findOrFail($id);
        
            // Pass the news data to the 'frontend.news.show' view
            return view('frontend.news.show', compact('news'));
        }
        // public function __construct()
        // {
        //     $this->middleware('role:super');
        // }
        public function index()
        {
            // Fetch all active news
            $news = News::latest()
                        ->where('is_active', true)
                        ->get();

            return view('frontend.news.index', compact('news'));
        }
        public function blogs()
        {
         
            $news = News::latest()
            ->where('is_active', true)
            ->get();

            return view('frontend.news.blogs', compact('news'));
        }

        public function create()
        {
            return view('frontend.news.create');
        }

     // In the NewsController (store method)
public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
    ]);

    $news = News::create([
        'title' => $request->title,
        'content' => $request->content,
    ]);

    // Create notifications for all users
    $users = User::all();
    foreach ($users as $user) {
        Notification::create([
            'user_id' => $user->id,  // Store the user_id for each notification
            'news_id' => $news->id,
            'is_read' => false,
        ]);
    }

    return redirect()->route('news.index');
}


        public function edit(News $news)
        {
            return view('frontend.news.edit', compact('news'));
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


