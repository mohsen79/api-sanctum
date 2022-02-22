<?php

namespace App\Http\Controllers\Api\v1;

use App\Exceptions\UserNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResourece;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Nette\Utils\Json;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(UserResourece::collection(User::all()), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate(
            [
                'name' => 'required|string|min:3|max:30',
                'family' => 'required|string|min:3|max:30',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8|max:30|confirmed',
            ]
        );
        $data["password"] = Hash::make($data["password"]);
        $user = User::create($data);
        $token = $user->createToken('user-token')->plainTextToken;
        $message = ['message' => 'you have registerd', 'token' => $token];
        return response()->json($message, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        // ^ for model binding UserNotFoundException registered
        return response()->json(new UserResourece($user), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        // ^ for model binding UserNotFoundException registered
        if ($request->password) {
            $data = $request->validate([
                'name' => 'required|min:3|max:30',
                'family' => 'required|min:3|max:30',
                'password' => 'required|min:8|max:30|confirmed'
            ]);
            $data["password"] = Hash::make($data["password"]);
        } else {
            $data = $request->validate([
                'name' => 'required|min:3|max:30',
                'family' => 'required|min:3|max:30',
            ]);
        }
        $user->update($data);
        return response()->json(new UserResourece($user), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //TODO :make sure if the user in the owner of account or is an admin
        // ^ for model binding UserNotFoundException registered
        $user->tokens()->delete();
        $user->delete();
        return response()->json(['message' => 'the user number ' . $user->id . ' deleted'], 200);
    }
}
