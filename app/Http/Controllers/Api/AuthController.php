<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Http\Resources\PostResource;

class AuthController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => 'required|string|min:8',
            'role'      => 'required|string|max:5',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $photo = $request->file('photo');
        // $photo->storeAs('public/attendance', $photo->hashName());

        if ($photo) {
            $fileName = time().'_'.$photo->getClientOriginalName();
            $filePath = $photo->storeAs('public/users', $fileName);
        }

        $phone_number = $request['phone_number'];
        if ($request['phone_number'][0] == "0") {
            $phone_number = substr($phone_number, 1);
        }

        if ($phone_number[0] == "8") {
            $phone_number = "62" . $phone_number;
        }

        $user = User::create([
            // 'photo'         => $filePath ?? null,
            'photo'         => $fileName ?? null,
            'name'          => $request->name,
            'email'         => $request->email,
            'phone_number'  => $phone_number,
            'password'      => Hash::make($request->password),
            'role'          => $request->role,
        ]);

        // $user->notify(new WelcomeEmailNotification($user));

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['data' => $user,'access_token' => $token, 'token_type' => 'Bearer', ]);
    }
    public function login(Request $request){
        if(!Auth::attempt($request->only('email', 'password'))){
            return response()->json(['message' => 'Unauthorized', 'access_token' => null, 'token_type' => null, 'data' => null], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['message' => 'Hay '.$user->name.', welcome to application', 'access_token' => $token, 'token_type' => 'Bearer', 'data' => $user]);
    }
    public function profile(){
        return new PostResource(true, 'Your profile', Auth::user());
        // return response()->json(['message' => 'Your profile', 'data' => Auth::user()]);
    }
    public function update(Request $request){
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'password'  => 'nullable|string|min:8'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $photo = $request->file('photo');

        if ($photo) {
            Storage::delete('public/'.$user->photo);

            $fileName = time().'_'.$photo->getClientOriginalName();
            $filePath = $photo->storeAs('public/users', $fileName);
        }

        $phone_number = $request['phone_number'];
        if ($request['phone_number'][0] == "0") {
            $phone_number = substr($phone_number, 1);
        }

        if ($phone_number[0] == "8") {
            $phone_number = "62" . $phone_number;
        }

        $user->update([
            'photo'         => $filePath ?? $user->photo,
            'name'          => $request->name,
            'email'         => $request->email,
            'phone_number'  => $phone_number,
            'password'      => $request->password ? Hash::make($request->password) : $user->password
         ]);

         return new PostResource(true, 'User Updated!', $user);
    }
    public function logout(){
        auth()->user()->tokens()->delete();
        return new PostResource(true, 'Logout Successul', null);
    }
}
