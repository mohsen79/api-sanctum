<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dislike extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'dislikeable_id', 'dislikeable_type'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->morphTo(Post::class);
    }
    public function comment()
    {
        return $this->morphTo(Comment::class);
    }
}
