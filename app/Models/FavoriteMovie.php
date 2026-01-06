<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavoriteMovie extends Model
{
    protected $fillable = [
        'user_id',
        'movie_name',
        'release_year',
        'rating',
        'image',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
