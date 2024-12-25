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

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'address' => 'nullable|string|max:255',
            'contact' => 'nullable|string|max:15',
            'password' => 'required|string|min:6',
            // 'role' => 'required|in:ADMIN,OWNER,USER',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 400);
        }

        $imagePath = null;
        if ($request->has('image')) {
            $image = $request->file('image');
            $path = $image->store('profile_images', 'public');
            $imagePath = asset('storage/' . $path);
        }

        $otp = rand(1000, 9999);
        $otp_expires_at = now()->addMinutes(10);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'contact' => $request->contact,
            'password' => Hash::make($request->password),
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
            return response()->json(['status' => false, 'message' => $validator->errors()], 400);
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

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Email not found.'], 404);
        }

        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return response()->json(['status' => 'error', 'message' => 'Invalid password.'], 401);
        }

        $user = Auth::guard('api')->user();
        $user->image = $user->image ?? asset('img/1.webp');

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

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not authenticated.'], 401);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'address' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6|confirmed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validatedData = $validator->validated();

        $user->name = $validatedData['name'] ?? $user->name;
        $user->email = $validatedData['email'] ?? $user->email;
        $user->address = $validatedData['address'] ?? $user->address;

        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('profile_images', 'public');
            $user->image = asset('storage/' . $path);
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
                'role' => $user->role,
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
        // return $request;
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
        if (!auth('api')->check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'User is not authenticated.',
            ], 401);
        }

        auth('api')->logout();

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out.',
        ]);
    }

    //admin show user data
    public function viewUserInfo(Request $request)
    {
        $perPage = $request->query('per_page', 10);

        if (!is_numeric($perPage) || $perPage <= 0) {
            return response()->json(['message' => "'per_page' must be a positive number."], 400);
        }

        $search = $request->input('search');
        $filter = $request->input('filter');
        $admin = auth()->user();

        if (!$admin) {
            return response()->json(['message' => 'User not authenticated.'], 401);
        }

        $usersQuery = User::where('role', 'USER')->where('id', '!=', $admin->id);

        if ($search) {
            $usersQuery->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('id', 'LIKE', "%{$search}%");
            });
        }

        if ($filter) {
            $validFilters = ['name', 'email', 'userid'];
            if (in_array($filter, $validFilters)) {
                $usersQuery->orderBy($filter === 'userid' ? 'id' : $filter, 'asc');
            }
        }

        $users = $usersQuery->paginate($perPage);

        if ($users->isEmpty()) {
            return response()->json(['message' => 'No users found.'], 404);
        }

        return response()->json([
            'status' => 'success',
            'users' => $users,
        ], 200);
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
    public function analytics(Request $request)
{
    // Get filter type, default to weekly
    $filter = $request->query('filter', 'weekly');
    $year = $request->query('year', date('Y')); // Default to the current year if not provided

    // Initialize variables to hold statistics
    $statistics = [];

    // Fetch all distinct years available in the database
    $years = User::selectRaw('YEAR(created_at) as year')
        ->distinct()
        ->orderBy('year', 'desc')
        ->get()
        ->pluck('year');

    if ($filter == 'weekly') {
        // Weekly data processing for a specific year
        $weeklyStatistics = User::selectRaw('WEEK(created_at) as week, COUNT(*) as total_user')
            ->whereYear('created_at', $year)
            ->groupBy('week')
            ->orderBy('week')
            ->get()
            ->keyBy('week');

        $weeklyOrders = Order::selectRaw('WEEK(created_at) as week, COUNT(*) as total_orders')
            ->whereYear('created_at', $year)
            ->groupBy('week')
            ->orderBy('week')
            ->get()
            ->keyBy('week');

        for ($i = 1; $i <= 52; $i++) {
            $statistics[] = [
                'week' => $i,
                'total_users' => $weeklyStatistics->get($i)->total_user ?? 0,
                'total_orders' => $weeklyOrders->get($i)->total_orders ?? 0,
            ];
        }
    } elseif ($filter == 'monthly') {
        // Monthly data processing for a specific year
        $monthlyStatistics = User::selectRaw('MONTH(created_at) as month, COUNT(*) as total_user')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $monthlyOrders = Order::selectRaw('MONTH(created_at) as month, COUNT(*) as total_orders')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        for ($i = 1; $i <= 12; $i++) {
            $statistics[] = [
                'month_name' => date('F', mktime(0, 0, 0, $i, 1)),
                'total_users' => $monthlyStatistics->get($i)->total_user ?? 0,
                'total_orders' => $monthlyOrders->get($i)->total_orders ?? 0,
            ];
        }
    } elseif ($filter == 'yearly') {
        // Yearly data processing for all available years
        foreach ($years as $year) {
            $yearlyStatistics = User::selectRaw('YEAR(created_at) as year, COUNT(*) as total_user')
                ->whereYear('created_at', $year)
                ->groupBy('year')
                ->orderBy('year')
                ->get()
                ->keyBy('year');

            $yearlyOrders = Order::selectRaw('YEAR(created_at) as year, COUNT(*) as total_orders')
                ->whereYear('created_at', $year)
                ->groupBy('year')
                ->orderBy('year')
                ->get()
                ->keyBy('year');

            $statistics[] = [
                'year' => $year,
                'total_users' => $yearlyStatistics->get($year)->total_user ?? 0,
                'total_orders' => $yearlyOrders->get($year)->total_orders ?? 0,
            ];
        }
    }

    // Return the response
    return response()->json([
        'status' => 'success',
        'filter' => $filter,
        'data' => $statistics,
    ], 200);
}


    public function earningChart(Request $request)
    {
        $year = $request->query('year', date('Y'));

        $data = Order::select(
            DB::raw("SUM(amount) as total_earnings"),
            DB::raw("MONTHNAME(created_at) as month_name"),
            DB::raw("MONTH(created_at) as month")
        )
            ->whereYear('created_at', $year) // Filter by year
            ->groupBy('month_name', 'month')
            ->orderBy('month')
            ->get();

        $sortedData = $data->sortByDesc('total_earnings');

        $topMonths = $sortedData->take(4)->map(function ($month) {
            return [
                'month_name' => $month->month_name,
                'total_earnings' => $month->total_earnings,
            ];
        });

        $mostEarningMonth = $sortedData->first();

        return response()->json([
            'status' => 'success',
            'year' => $year,
            'top_months' => $topMonths,
            'most_earning_month' => $mostEarningMonth ? [
                'month_name' => $mostEarningMonth->month_name,
                'total_earnings' => $mostEarningMonth->total_earnings,
            ] : null,
        ]);
    }

}
