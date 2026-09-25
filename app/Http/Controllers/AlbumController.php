<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Album;
use App\Models\Tag;
use Illuminate\Support\Facades\Http;
class AlbumController extends Controller
{
    public function list(){
        $albums = Album::all();
        // On récupère tous les tags qui ont des albums, en chargeant la relation
        $tags = Tag::whereHas('albums')->with('albums')->get();
        return view('bibliotheque', compact('albums', 'tags'));
    }

    public function show($id){
        $album = Album::findOrFail($id);
        $tags = Tag::all();
        return view('album-detail', compact('album', 'tags'));
    }

    public function toggleTag($id, $tagId){
        $album = Album::findOrFail($id);
        $album->tags()->toggle($tagId);
        return redirect()->route('bibliotheque.show', $id);
    }

    public function store(Request $request){
        Album::create([
            'name' => $request->input('name'),
            'artist' => $request->input('artist'),
            'cover' => $request->input('image'),
            'year' => date('Y'),
        ]);

        return redirect()->route('bibliotheque.list');
    }


    public function search_all(Request $request)
    {
        $albums = Album::all();
        $apiResults = [];
        $searchQuery = '';

        // Si une recherche est lancée (artiste ou album)
        if ($request->filled('search')) {
            $searchQuery = $request->search;

            // Appel à l'API Deezer
            $response = Http::withoutVerifying()->get('https://api.deezer.com/search/album', [
                'q' => $searchQuery,
                'limit' => 20
            ]);

            if ($response->successful()) {
                $apiResults = $response->json()['data'] ?? [];
            }
        }

        return view('search', [
            'albums' => $albums,
            'apiResults' => $apiResults,
            'searchQuery' => $searchQuery
        ]);
    }

    public function delete($id) {
        $album = Album::findOrFail($id);
        $album->delete();
        return redirect()->route('bibliotheque.list');
    }
}