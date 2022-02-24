<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = ['like', 'user_id', 'likeable_id', 'likeable_type'];

    public function user()
    {
        return $this->morphTo(User::class);
    }

    public function post()
    {
        return $this->morphTo(Post::class);
    }

    public function comment()
    {
        return $this->morphTo(Comment::class);
    }

    public function likeable()
    {
        return $this->morphTo();
    }
}
