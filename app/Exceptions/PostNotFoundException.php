<?php

namespace App\Exceptions;

use Exception;

class PostNotFoundException extends Exception
{
    public static function message($id)
    {
        return response()->json(['message' => 'the post number ' . $id . ' not found'], 404);
    }
}
