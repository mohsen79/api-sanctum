<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trend extends Model
{
    use HasFactory;
    protected $fillable = ['tag_id', 'trend'];

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
}
