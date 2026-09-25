<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name', 'color'];

    // Un tag peut être associé à plusieurs albums
    public function albums()
    {
        return $this->belongsToMany(Album::class);
    }
}
