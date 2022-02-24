<?php

namespace App\Exceptions;

use Exception;

class CommentNotFoundException extends Exception
{
    public static function message($id)
    {
        return response()->json(['message' => 'the comment number ' . $id . ' not found'], 404);
    }
}
