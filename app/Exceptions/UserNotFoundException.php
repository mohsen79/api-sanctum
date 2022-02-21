<?php

namespace App\Exceptions;

use Exception;

class UserNotFoundException extends Exception
{
    public static function message($id)
    {
        return response()->json(['message' => 'the user number ' . $id . ' not found'], 404);
    }
}
