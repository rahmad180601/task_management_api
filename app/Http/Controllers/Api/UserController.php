<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $user = User::latest()->paginate(5);
        return UserResource::collection($user);
    }
    public function register(Request $request)
    {
        Request()->validate([
            'name' => ['string', 'required', 'min:3'],
            'email' => ['email', 'required', 'unique:users,email'],
            'password' => ['required', 'min:6'],
            'number_phone' => ['required'],
            'photo_profile' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $nama = $request->name;
        $path = null;

        if ($request->hasFile('photo_profile')) {
            $imageName = uniqid($nama . '_') . '.' . $request->photo_profile->getClientOriginalExtension();
            $path = $request->photo_profile->storeAs('images/profile', $imageName, 'public');
            if (!$path) {
                return response()->json(['error' => 'Failed to upload image'], 500);
            }
        }

        $data = User::create([
            'name' => $nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'number_phone' => $request->number_phone,
            'roles' => $request->roles,
            'photo_profile' => $path,
        ]);

        return response([
            'message' => 'Thanks, you are registered',
            'data' => $data
        ]);

    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if(!$token = auth()->attempt($request->only('email', 'password'))){
            return response(null, 401);
        }

        return response()->json(['token' => $token]);
    }

    public function logout(Request $request)
    {
        auth()->logout();
        return response([
            'message' => 'User, Logout Succesfully',
        ]);

    }

    public function updateprofile(Request $request, User $user)
    {
        Request()->validate([
            'name' => ['string', 'sometimes', 'min:3'],
            'email' => ['email', 'sometimes', 'unique:users,email,' . $user->id],
            'password' => ['sometimes', 'min:6'],
            'number_phone' => ['sometimes'],
            'photo_profile' => ['sometimes', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $nama = $request->name;
        $path = $user->photo_profile;

        if ($request->hasFile('photo_profile')) {
            $imageName = uniqid($nama . '_') . '.' . $request->photo_profile->getClientOriginalExtension();
            $path = $request->photo_profile->storeAs('images/profile', $imageName, 'public');
            if (!$path) {
                return response()->json(['error' => 'Failed to upload image'], 500);
            }
            if ($user->photo_profile) {
                Storage::disk('public')->delete($user->photo_profile);
            }
        }

        $user->update([
            'name' =>$nama ?? $user->name,
            'email' => $request->email ?? $user->email,
            'password' => $user->password,
            'number_phone' => $request->number_phone ?? $user->number_phone,
            'roles' => $request->roles ?? $user->roles,
            'photo_profile' => $path,
        ]);
    
        return response([
            'message' => 'User, Updated Succesfully',
            'data' => new UserResource($user),
        ]);

    }

    public function delete(User $user)
    {
        $user->delete();
        if ($user->photo_profile) {
            Storage::disk('public')->delete($user->photo_profile);
        }
        return response('User, Deleted Succsesfully');
    }
}
