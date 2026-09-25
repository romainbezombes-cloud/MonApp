<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $fillable = [
        'name', 
        'artist',
        'cover',
        'year'
        ];

    // Un album peut avoir plusieurs tags
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
