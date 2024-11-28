<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function index(Request $request): JsonResponse
    {
        return response()->json([
            "data" => new UserResource($request->user())
        ]);
    }

    public function store(UserRegisterRequest $request): JsonResponse
    {
        $new_user = $request->validated();

        $user = User::create([
            "username"   => $new_user['username'],
            "first_name" => $new_user['first_name'],
            "last_name"  => $new_user['last_name'],
            "email"      => $new_user['email'],
            "password"   => $new_user['password'],  // <----- Hashing throw user Model using setPasswordAttribute method
        ]);

        return response()->json([
            "message" => "You successfully registered",
            "data" => new UserResource($user),
        ]);
    }
}
