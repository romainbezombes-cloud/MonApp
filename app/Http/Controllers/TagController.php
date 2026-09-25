<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::with('albums')->withCount('albums')->get();
        return view('tags', compact('tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:20',
        ]);

        Tag::create([
            'name' => $request->input('name'),
            'color' => $request->input('color'),
        ]);

        return redirect()->route('tags.index');
    }

    public function update(Request $request, $id)
    {
        $tag = Tag::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:20',
        ]);

        $tag->update([
            'name' => $request->input('name'),
            'color' => $request->input('color'),
        ]);

        return redirect()->route('tags.index');
    }

    public function destroy($id)
    {
        $tag = Tag::findOrFail($id);
        $tag->albums()->detach();
        $tag->delete();

        return redirect()->route('tags.index');
    }
}
