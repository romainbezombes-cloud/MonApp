<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class API extends Model
{
    // On autorise Eloquent à remplir ces 3 champs
    protected $fillable = ['name', 'image', 'artist'];
}