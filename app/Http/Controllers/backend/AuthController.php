<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Mail\sendOTP;
use App\Models\Order;
use App\Models\User;
use Carbon\Traits\Week;
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'address' => 'required|string|max:255',
            'contact' => 'required|string|max:15',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:ADMIN,OWNER,USER',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:10240',
        ]);

        $imagePath = null;
        if ($request->has('image')) {
            $image = $request->file('image');
            $path = $image->store('profile_images', 'public');
            $imagePath = asset('storage/' . $path);
        }

        $otp = rand(100000, 999999);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'address' => $validated['address'],
            'contact' => $validated['contact'],
            'password' => bcrypt($validated['password']),
            'role' => $validated['role'],
            'image' => $imagePath,
            'otp' => $otp,
        ]);

        try {
            Mail::to($user->email)->send(new sendOTP($otp));
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        $message = match ($user->role) {
            'ADMIN' => 'Welcome Admin! Please verify your email.',
            default => 'Welcome User! Please verify your email.',
        };

        return response()->json(['message' => $message], 200);
    }
    // verify email
    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response($validator->messages(), 200);
        }

        $user = User::where('otp', $request->otp)->first();

        if ($user) {
            $user->otp = null;
            $user->email_verified_at = now();
            $user->save();

            $token = JWTAuth::fromUser($user);

            return response()->json([
                'message' => 'Email is verified',
                'access_token' => $token,
                'token_type' => 'bearer',
                'email_verified_at' => $user->email_verified_at,
            ], 200);
        }

        return response()->json(['error' => 'Invalid OTP.'], 400);
    }
    //login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if ($token = Auth::guard('api')->attempt($credentials)) {
            $user = Auth::guard('api')->user();

            if ($user->role !== 'OWNER' && !$user->hasVerifiedEmail()) {
                return response()->json(['error' => 'Email not verified. Please check your email.'], 403);
            }

            return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'email_verified_at' => $user->email_verified_at,
            ]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }
    public function guard()
    {
        return Auth::guard('api');
    }
    // update profile
    public function updateProfile(Request $request)
    {
        $user = Auth::guard('api')->user();

        if (!$user) {
            return response()->json(['error' => 'User not authenticated.'], 401);
        }

        $validatedData = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'address' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6|confirmed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:10240',
        ]);

        if ($request->has('name')) {
            $user->name = $validatedData['name'];
        }
        if ($request->has('email')) {
            $user->email = $validatedData['email'];
        }
        if ($request->has('address')) {
            $user->address = $validatedData['address'];
        }
        if ($request->has('password')) {
            $user->password = Hash::make($validatedData['password']);
        }

        if ($request->has('image')) {
            $image = $request->file('image');

            if ($image->isValid()) {
                $path = $image->store('profile_images', 'public');
                $imagePath = asset('storage/' . $path);

                $user->image = $imagePath;
            } else {
                return response()->json(['error' => 'The image failed to upload.'], 400);
            }
        }

        $user->save();

        return response()->json([
            'message' => 'Profile updated successfully.',
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'address' => $user->address,
                'image' => $user->image,
            ],
        ], 200);
    }
    //change password
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'User not authenticated.'], 401);
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['error' => 'Current password is incorrect.'], 403);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password changed successfully']);
    }
    // forgate password
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'Email not registered.'], 404);
        }
        $otp = rand(100000, 999999);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $otp, 'created_at' => now()]
        );

        try {
            Mail::to($request->email)->send(new sendOTP($otp));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Failed to send OTP.'], 500);
        }

        return response()->json(['message' => 'OTP sent to your email.'], 200);
    }

    // this otp verify for forgot pass
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric',
        ]);

        $tokenData = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->otp)
            ->where('created_at', '>=', now()->subMinutes(15))
            ->first();

        if (!$tokenData) {
            return response()->json(['error' => 'Invalid or expired OTP.'], 400);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        $resetToken = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'OTP verified successfully.',
            'reset_token' => $resetToken,
        ], 200);
    }
    // reset password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json(['message' => 'Password reset successful.'], 200);
    }
    public function resendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'Email not registered.'], 404);
        }

        $otp = rand(100000, 999999);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $otp, 'created_at' => now()]
        );

        try {
            Mail::to($request->email)->send(new sendOTP($otp));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Failed to resend OTP.'], 500);
        }

        return response()->json(['message' => 'OTP resent to your email.'], 200);
    }
    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }
    //admin show user data
    public function viewUserInfo(Request $request)
    {
        $perPage = $request->query('per_page', 10);

        if ($perPage <= 0) {
            return response()->json(['message' => "'per_page' must be a positive number."], 400);
        }

        $search = $request->input('search');
        $filter = $request->input('filter');
        $admin = auth()->user();

        //admin info dont show
        $usersQuery = User::where('role', 'USER')->where('id', '!=', $admin->id);//admin info dont show

        if ($search) {
            $usersQuery->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('id', 'LIKE', "%{$search}%");
            });
        }

        if ($filter === 'name') {
            $usersQuery->orderBy('name', 'asc');
        } elseif ($filter === 'email') {
            $usersQuery->orderBy('email', 'asc');
        } elseif ($filter === 'userid') {
            $usersQuery->orderBy('id', 'asc');
        }

        $users = $usersQuery->with(['orders.product:id,title'])
            ->select('id', 'name', 'email', 'image', 'created_at')
            ->paginate($perPage);

        $defaultAvatar = asset('img/1.webp');

        $users->getCollection()->transform(function ($user) use ($defaultAvatar) {
            $user->image = $user->image ?: $defaultAvatar;
            $user->bought_product = $user->orders->count(); // Count total orders

            unset($user->orders); // Remove raw orders data
            return $user;
        });

        // Return response
        if ($users->isEmpty()) {
            return response()->json(['users_message' => "There is no one by this search criteria."], 200);
        }

        return response()->json(['users' => $users], 200);
    }

    //dashboard for admin

    public function getDashboardStatistics()
    {

        $totalUsers = User::count();

        $totalOrders = Order::count();

        // $totalEarning = Order::selectRaw(Week('created_at'));

        return response()->json([
            'total_users' => $totalUsers,
            'total_orders' => $totalOrders,
            // 'total_earning' => $totalEarning,
        ], 200);
    }

}
