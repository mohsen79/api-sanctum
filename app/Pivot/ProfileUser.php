<?php

namespace App\Pivot;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProfileUser extends Pivot
{
    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}
