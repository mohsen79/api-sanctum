<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'trend'];

    public function posts()
    {
        return $this->morphedByMany(Post::class, 'taggable');
    }

    public function trend()
    {
        return $this->hasMany(Trend::class);
    }
}
