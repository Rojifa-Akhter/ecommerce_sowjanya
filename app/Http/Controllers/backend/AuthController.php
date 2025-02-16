<?php
namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Mail\sendOTP;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        // return $request;

        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|unique:users,email',
            'address'  => 'nullable|string|max:255',
            'contact'  => 'nullable|string|max:15',
            'password' => 'required|string|min:6',
            // 'role' => 'required|in:ADMIN,OWNER,USER',
            'image'    => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 400);
        }

        // $path = null;
        // if ($request->has('image')) {
        //     $image = $request->file('image');
        //     $path = $image->store('profile_images', 'public');
        //     // $imagePath = asset('storage/' . $path);
        // }
        $path = null;
        // if ($request->has('image')) {
        //     $image = $request->file('image');
        //     $extension = $image->getClientOriginalExtension();
        //     $new_image = time() . '.' . $extension;
        //     $path = $image->move(public_path('uploads/profile_images'), $new_image);
        // }
// return $new_image;
        $otp            = rand(1000, 9999);
        $otp_expires_at = now()->addMinutes(10);

        $user = User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'address'        => $request->address,
            'contact'        => $request->contact,
            'password'       => Hash::make($request->password),
            'role'           => 'USER', // Set default role as 'user'
                                        // 'image' => $new_image,
            'otp'            => $otp,
            'otp_expires_at' => $otp_expires_at,
            'status'         => 'inactive',
        ]);
        $user_name = $request->name;
        try {
            Mail::to($user->email)->send(new sendOTP($otp, $user_name));
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        $message = match ($user->role) {
            'ADMIN' => 'Welcome Admin! Please verify your email.',
            default => 'Welcome User! Please verify your email.',
        };

        return response()->json([
            'status'  => 'success',
            'message' => $message], 200);
    }
    // verify email
    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 400);
        }
        $user = User::where('otp', $request->otp)->first();

        if ($user) {
            $user->otp               = null;
            $user->email_verified_at = now();
            $user->status            = 'active';
            $user->save();

            $token = JWTAuth::fromUser($user);

            return response()->json([
                'status'  => 'success',
                'message' => 'OTP verified successfully.',
                // 'access_token' => $token,
                // 'token_type' => 'bearer',
                // 'email_verified_at' => $user->email_verified_at,
            ], 200);
        }

        return response()->json(['error' => 'Invalid OTP.'], 400);
    }
    //login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $user = User::where('email', $request->email)->first();
        if (! $user) {
            return response()->json(['status' => 'error', 'message' => 'Email not found.'], 404);
        }

        if (! $token = Auth::guard('api')->attempt($credentials)) {
            return response()->json(['status' => 'error', 'message' => 'Invalid password.'], 401);
        }

        $user = Auth::guard('api')->user();
        // $imageUrl = $user->image ? asset('storage/' . $user->image) : asset('img/1.webp');

        return response()->json([
            'status'           => 'success',
            'access_token'     => $token,
            'token_type'       => 'bearer',
            'user_information' => [
                'name'              => $user->name,
                'email'             => $user->email,
                'role'              => $user->role,
                'email_verified_at' => $user->email_verified_at,
                'image'             => $user->image,
            ],
        ], 200);
    }

    public function guard()
    {
        return Auth::guard('api');
    }
    // update profile
    public function updateProfile(Request $request)
    {

        $user = Auth::guard('api')->user();

        if (! $user) {
            return response()->json(['status' => 'error', 'message' => 'User not authenticated.'], 401);
        }

        $validator = Validator::make($request->all(), [
            'name'     => 'nullable|string|max:255',
            'address'  => 'nullable|string|max:255',
            'contact'  => 'nullable|string|max:16',
            'password' => 'nullable|string|min:6|confirmed',
            'image'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $validatedData = $validator->validated();

        // Update user data
        $user->name    = $validatedData['name'] ?? $user->name;
        $user->address = $validatedData['address'] ?? $user->address;
        $user->contact = $validatedData['contact'] ?? $user->contact;

        if (! empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }

        if ($request->hasFile('image')) {
            $existingImage = $user->image;

            if ($existingImage) {
                $oldImage = parse_url($existingImage);
                $filePath = ltrim($oldImage['path'], '/');
                if (file_exists($filePath)) {
                    unlink($filePath); // Delete the existing image
                }
            }

            // Upload new image
            $image     = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $newName   = time() . '.' . $extension;
            $image->move(public_path('uploads/profile_images'), $newName);

            $user->image = $newName;
        }

        $user->save();

        // Return the updated user profile
        return response()->json([
            'status'  => 'success',
            'message' => 'Profile updated successfully.',
            'user'    => [
                'id'      => $user->id,
                'name'    => $user->name,
                'email'   => $user->email,
                'address' => $user->address,
                'contact' => $user->contact,
                'image'   => $user->image,
                'role'    => $user->role,
            ],
        ], 200);
    }

    //change password
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (! $user) {
            return response()->json(['error' => 'User not authenticated.'], 401);
        }

        if (! Hash::check($request->current_password, $user->password)) {
            return response()->json(['error' => 'Current password is incorrect.'], 403);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'Password changed successfully']);
    }
    // forgate password
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return response()->json(['error' => 'Email not registered.'], 404);
        }
        $otp = rand(1000, 9999);

        DB::table('users')->updateOrInsert(
            ['email' => $request->email],
            ['otp' => $otp, 'created_at' => now()]
        );
        $user_name = $user->name;
        try {
            Mail::to($request->email)->send(new sendOTP($otp, $user_name));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Failed to send OTP.'], 500);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'OTP sent to your email.'], 200);
    }

    // reset password
    public function resetPassword(Request $request)
    {
        // return $request;
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();
        if (! $user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'Password reset successful.'], 200);
    }
    public function resendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return response()->json(['error' => 'Email not registered.'], 404);
        }

        $otp = rand(100000, 999999);

        DB::table('users')->updateOrInsert(
            ['email' => $request->email],
            ['otp' => $otp, 'created_at' => now()]
        );
        $user_name = $user->name;

        try {
            Mail::to($request->email)->send(new sendOTP($otp, $user_name = $user->name));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Failed to resend OTP.'], 500);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'OTP resent to your email.'], 200);
    }
    public function logout()
    {
        if (! auth('api')->check()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User is not authenticated.',
            ], 401);
        }

        auth('api')->logout();

        return response()->json([
            'status'  => 'success',
            'message' => 'Successfully logged out.',
        ]);
    }

}
