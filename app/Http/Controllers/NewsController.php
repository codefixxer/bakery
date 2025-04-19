<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
{
    $newsList = News::latest()->get();
    return view('frontend.news.index', compact('newsList'));
}

public function create()
{
    return view('frontend.news.create');
}

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        News::create($data);

        return redirect()->route('newss.index')->with('success', 'News published!');
    }

    public function edit(News $newss)
    {
        return view('frontend.news.create', compact('newss'));
    }

public function update(Request $request, News $newss)
{
    $data = $request->validate([
        'title'   => 'required|string|max:255',
        'content' => 'required|string',
    ]);

    $newss->update($data);

    return redirect()->route('newss.index')->with('success', 'News updated!');
}


    public function destroy(\App\Models\News $newss)
    {
        $newss->delete();
        return back()->with('success', 'News deleted!');
    }
}
