<?php

namespace App\Http\Controllers;

use App\Models\FavoriteMovie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteMovieController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'movie_name' => 'required|string|max:255',
            'release_year' => 'required|string|max:4',
            'rating' => 'required|integer|min:1|max:5',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('movies', 'public');
        }

        FavoriteMovie::create([
            'user_id' => Auth::id(),
            'movie_name' => $request->movie_name,
            'release_year' => $request->release_year,
            'rating' => $request->rating,
            'image' => $imagePath,
        ]);

        return redirect('/')->with('success', 'Movie added to your favorites!');
    }

    public function update(Request $request, FavoriteMovie $movie)
    {
        if ($movie->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'movie_name' => 'required|string|max:255',
            'release_year' => 'required|string|max:4',
            'rating' => 'required|integer|min:1|max:5',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['movie_name', 'release_year', 'rating']);

        if ($request->hasFile('image')) {
            // Optionally delete the old image here if needed
            $imagePath = $request->file('image')->store('movies', 'public');
            $data['image'] = $imagePath;
        }

        $movie->update($data);

        return redirect('/')->with('success', 'Movie updated successfully!');
    }
}
