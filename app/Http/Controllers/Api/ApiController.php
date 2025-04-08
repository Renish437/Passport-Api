<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Storage;

class ApiController extends Controller
{
    // Register api[name,email,profile_image, password,password_confirmation]
    public function register(Request $request){
        
       $data= $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
            'profile_image' => 'nullable|image'
        ]);

        if ($request->hasFile('profile_image')) {
            // $profile_image = $request->file('profile_image');
            // $profile_image_name = time() . '.' . $profile_image->getClientOriginalExtension();
            // $profile_image->move(public_path('profile_images'), $profile_image_name);
            $data['profile_image'] = $request->file('profile_image')->store("users",'public');
        }
        User::create($data);

        return response()->json([
            'status'=> true,
            'message' => 'User created successfully'], 
            201);
    }

    public function login(Request $request){
        $data=$request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $user=User::where('email','=',$request->email)->first(); 
        if(!$user || !Hash::check($request->password,$user->password)){
            return response()->json([
                'status'=> false,
                'message' => 'Either email or password is wrong'
            ], 
                401);

        }
        $token=$user->createToken('auth_token')->accessToken;
        return response()->json([
            'status'=> true,
            'message' => 'User logged in successfully',
            'token' => $token
        ]);
    }
    public function profile(Request $request){
        $user=Auth::user();
        return response()->json([
            'status'=> true,
            'message' => 'Profile Page accessed successfully',
            'user' => $user,
            'profile_image'=>asset(Storage::url($user->profile_image))

        ]);
    }
    public function refreshToken(Request $request){
        
        Auth::user()->tokens()->delete();
        $token=Auth::user()->createToken('auth_token')->accessToken;
        return response()->json([
            'status'=> true,
            'message' => 'Token refreshed successfully',
            'token' => $token
        ]);
    }

    public function logout(Request $request){
        Auth::user()->tokens()->delete();
        return response()->json([
            'status'=> true,
            'message' => 'User logged out successfully'
        ]);
    }


}
