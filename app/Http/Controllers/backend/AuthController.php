<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Mail\sendOTP;
use App\Models\Order;
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'address' => 'nullable|string|max:255',
            'contact' => 'nullable|string|max:15',
            'password' => 'required|string|min:6',
            // 'role' => 'required|in:ADMIN,OWNER,USER',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:10240',
        ]);

        $imagePath = null;
        if ($request->has('image')) {
            $image = $request->file('image');
            $path = $image->store('profile_images', 'public');
            $imagePath = asset('storage/' . $path);
        }

        $otp = rand(1000, 9999);
        $otp_expires_at = now()->addMinutes(10);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'address' => $validated['address'] ?? null,
            'contact' => $validated['contact'] ?? null,
            'password' => bcrypt($validated['password']),
            'role' => 'USER', // Set default role as 'user'
            'image' => $imagePath,
            'otp' => $otp,
            'otp_expires_at' => $otp_expires_at,
            'status' => 'inactive',
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

        return response()->json([
            'status' => 'success',
            'message' => $message], 200);
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
            $user->status = 'active';
            $user->save();

            $token = JWTAuth::fromUser($user);

            return response()->json([
                'status' => 'success',
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

        if ($token = Auth::guard('api')->attempt($credentials)) {
            $user = Auth::guard('api')->user();

            $user->image = $user->image ?? asset('img/default-user.webp');

            return response()->json([
                'status' => 'success',
                'access_token' => $token,
                'token_type' => 'bearer',
                'user_information' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'email_verified_at' => $user->email_verified_at,
                    'image' => $user->image,
                ],
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
            'status' => 'success',
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

        return response()->json([
            'status' => 'success',
            'message' => 'Password changed successfully']);
    }
    // forgate password
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'Email not registered.'], 404);
        }
        $otp = rand(1000, 9999);

        DB::table('users')->updateOrInsert(
            ['email' => $request->email],
            ['otp' => $otp, 'created_at' => now()]
        );

        try {
            Mail::to($request->email)->send(new sendOTP($otp));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Failed to send OTP.'], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'OTP sent to your email.'], 200);
    }

    // this otp verify for forgot pass
    // public function verifyOtp(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'otp' => 'required|numeric',
    //     ]);

    //     $tokenData = DB::table('users')
    //         ->where('email', $request->email)
    //         ->where('token', $request->otp)
    //         ->where('created_at', '>=', now()->subMinutes(15))
    //         ->first();

    //     if (!$tokenData) {
    //         return response()->json(['error' => 'Invalid or expired OTP.'], 400);
    //     }

    //     $user = User::where('email', $request->email)->first();
    //     if (!$user) {
    //         return response()->json(['error' => 'User not found.'], 404);
    //     }

    //     $resetToken = JWTAuth::fromUser($user);

    //     return response()->json([
    //         'message' => 'OTP verified successfully.',
    //         'reset_token' => $resetToken,
    //     ], 200);
    // }
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

        return response()->json([
            'status' => 'success',
            'message' => 'Password reset successful.'], 200);
    }
    public function resendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'Email not registered.'], 404);
        }

        $otp = rand(100000, 999999);

        DB::table('users')->updateOrInsert(
            ['email' => $request->email],
            ['otp' => $otp, 'created_at' => now()]
        );

        try {
            Mail::to($request->email)->send(new sendOTP($otp));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Failed to resend OTP.'], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'OTP resent to your email.'], 200);
    }
    public function logout()
    {
        auth('api')->logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out']);
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
        $usersQuery = User::where('role', 'USER')->where('id', '!=', $admin->id); //admin info dont show

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

        return response()->json([
            'status' => 'success',
            'users' => $users], 200);
    }

    //dashboard for admin
    public function getDashboardStatistics(Request $request)
    {
        $period = $request->input('period', 'weekly');

        // Set the date range based on the selected period
        switch ($period) {
            case 'weekly':
                $startOfPeriod = now()->startOfWeek();
                $endOfPeriod = now()->endOfWeek();
                break;
            case 'monthly':
                $startOfPeriod = now()->startOfMonth();
                $endOfPeriod = now()->endOfMonth();
                break;
            case 'yearly':
                $startOfPeriod = now()->startOfYear();
                $endOfPeriod = now()->endOfYear();
                break;
            default:
                $startOfPeriod = now()->startOfWeek();
                $endOfPeriod = now()->endOfWeek();
                break;
        }

        // Calculate previous period start and end dates
        if ($period == 'weekly') {
            $previousStartOfPeriod = now()->subWeek()->startOfWeek();
            $previousEndOfPeriod = now()->subWeek()->endOfWeek();
        } elseif ($period == 'monthly') {
            $previousStartOfPeriod = now()->subMonth()->startOfMonth();
            $previousEndOfPeriod = now()->subMonth()->endOfMonth();
        } elseif ($period == 'yearly') {
            $previousStartOfPeriod = now()->subYear()->startOfYear();
            $previousEndOfPeriod = now()->subYear()->endOfYear();
        }

        // Get statistics for the current period
        $totalUsers = User::whereBetween('created_at', [$startOfPeriod, $endOfPeriod])->count();
        $totalOrders = Order::whereBetween('created_at', [$startOfPeriod, $endOfPeriod])->count();
        $totalEarning = Order::whereBetween('created_at', [$startOfPeriod, $endOfPeriod])->sum('amount');

        // Get statistics for the previous period
        $previousUsers = User::whereBetween('created_at', [$previousStartOfPeriod, $previousEndOfPeriod])->count();
        $previousOrders = Order::whereBetween('created_at', [$previousStartOfPeriod, $previousEndOfPeriod])->count();
        $previousEarning = Order::whereBetween('created_at', [$previousStartOfPeriod, $previousEndOfPeriod])->sum('amount');

        // Calculate the growth percentage for each statistic
        $userGrowthPercentage = $previousUsers ? (($totalUsers - $previousUsers) / $previousUsers) * 100 : 0;
        $orderGrowthPercentage = $previousOrders ? (($totalOrders - $previousOrders) / $previousOrders) * 100 : 0;
        $earningGrowthPercentage = $previousEarning ? (($totalEarning - $previousEarning) / $previousEarning) * 100 : 0;

        // Return the statistics
        return response()->json([
            'status' => 'success',
            'data' => [
                'total_users' => $totalUsers,
                'total_orders' => $totalOrders,
                'total_earning' => $totalEarning ?? 0,
                'user_growth_percentage' => round($userGrowthPercentage, 2),
                'order_growth_percentage' => round($orderGrowthPercentage, 2),
                'earning_growth_percentage' => round($earningGrowthPercentage, 2),
            ],

        ], 200);
    }
    //website visitor
    public function websiteVisitor(Request $request)
    {
        $year = $request->query('year', date('Y'));

        $monthlyStatistics = user::selectRaw('MONTH(created_at) as month, COUNT(*) as total_user')
            ->whereYear('created_at', $year) // Filter year
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $statistics = [];
        for ($i = 1; $i <= 12; $i++) {
            $statistics[] = [
                'month_name' => date('F', mktime(0, 0, 0, $i, 1)),
                'total_users' => $monthlyStatistics->get($i)->total_user ?? 0,
            ];
        }

        return response()->json(['status'=> 'success',
            $statistics],  200
            );
    }

}
