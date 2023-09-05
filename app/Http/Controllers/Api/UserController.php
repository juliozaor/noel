<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
   
    public function store(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'unique:users', 'email', 'max:100', 'string'],
            'password' => ['required', 'string', 'min:8'],
            'document' => ['integer'],
            'cell' => ['integer'],
            'address' => ['string'],
            'neighborhood' => ['string'],
            'birth' => ['date'],
        ];



        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $user = new User([
            "name" => $request->name,
            "email" => strtolower($request->email),
            "password" => Hash::make($request->password)
        ]);
        $user->save();

        $profile = new Profile([
            'document' =>$request->document,
            'cell' =>$request->cell,
            'address' => $request->address,
            'neighborhood' => $request->neighborhood,
            'birth' => $request->birth,
            'eps' => $request->eps??'',
            'reference' => $request->reference??'',
            'experience2022' => $request->experience2022??false
        ]);
        $user->profile()->save($profile);

        return response()->json([
            'status' => true,
            'message' => 'User created successfully',
            'data' =>$user,
            'token' => $user->createToken('API TOKEN')->plainTextToken
        ], 200);
    }

   

    public function login(Request $request)
    {
        $rules = [
            'email' => ['required', 'email', 'string'],
            'password' => ['required', 'string']
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => false,
                'errors' => ['Unauthorized']
            ], 401);
        }
        
       // $user = User::where('email', $request->email)->first();
        $user = User::where('email', $request->email)
    ->with('profile')
    ->first();


        return response()->json([
            'status' => true,
            'message' => 'User logged in successfully',
            'data' => $user,
            'token' => $user->createToken('API TOKEN')->plainTextToken
        ], 200);
    }

    public function logout()
    {
        auth()->user()->tokens->delete();
        return response()->json([
            'status' => true,
            'message' => 'User logged out successfully'
        ], 200);
    }

    
}
