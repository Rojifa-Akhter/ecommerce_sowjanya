<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class adminController extends Controller
{
    //admin show user data
    public function viewUserInfo(Request $request)
    {
        $perPage = $request->query('per_page', 10);

        // if (!is_numeric($perPage) || $perPage <= 0) {
        //     return response()->json(['message' => "'per_page' must be a positive number."], 400);
        // }

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

        $users = $usersQuery->paginate($perPage ?? 10);

        if ($users->isEmpty()) {
            return response()->json(['message' => 'No users found.'], 200);
        }
        $defaultImageUrl = url(Storage::url('profile_images/default_user.png'));

        $users->getCollection()->transform(function ($user) use ($defaultImageUrl) {
            $user->image = $user->image ?? $defaultImageUrl;
            return $user;
        });

        return response()->json([
            'status' => 'success',
            'users' => $users,
        ], 200);
    }
    public function deleteUser($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'User deleted successfully.',
                'data' => $user,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'success',
                'message' => 'User not found',
            ], 200);
        }
    }
    public function viewAdminProfile()
    {
        // Get the authenticated user (admin)
        $admin = auth()->user();

        if (!$admin || $admin->role !== 'ADMIN') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access. Only admins can view their profile.',
            ], 403);
        }

        $adminProfile = $admin->only(['name', 'email', 'contact', 'address']);

        $adminProfile['image'] = $admin->image ?? url(Storage::url('profile_images/default_user.png'));

        return response()->json([
            'status' => 'success',
            'user' => $adminProfile,
        ], 200);
    }

    //dashboard for admin
    public function getDashboardStatistics(Request $request)
    {
        $period = $request->input('period', 'weekly');

        // Set date range based on period (weekly, monthly, yearly)
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
                // Default to weekly if an invalid period is passed
                $startOfPeriod = now()->startOfWeek();
                $endOfPeriod = now()->endOfWeek();
                break;
        }

        if ($period == 'weekly') {
            // Previous week's start and end dates
            $previousStartOfPeriod = now()->subWeek()->startOfWeek();
            $previousEndOfPeriod = now()->subWeek()->endOfWeek();
        } elseif ($period == 'monthly') {

            $previousStartOfPeriod = now()->subMonth()->startOfMonth();
            $previousEndOfPeriod = now()->subMonth()->endOfMonth();
        } elseif ($period == 'yearly') {

            $previousStartOfPeriod = now()->subYear()->startOfYear();
            $previousEndOfPeriod = now()->subYear()->endOfYear();
        }

        //  (total users, total orders, total earnings)
        $totalUsers = User::whereBetween('created_at', [$startOfPeriod, $endOfPeriod])->count();
        $totalOrders = Order::whereBetween('created_at', [$startOfPeriod, $endOfPeriod])->count();
        $totalEarning = Order::whereBetween('created_at', [$startOfPeriod, $endOfPeriod])->sum('amount');

        //  (total users, total orders, total earnings)
        $previousUsers = User::whereBetween('created_at', [$previousStartOfPeriod, $previousEndOfPeriod])->count();
        $previousOrders = Order::whereBetween('created_at', [$previousStartOfPeriod, $previousEndOfPeriod])->count();
        $previousEarning = Order::whereBetween('created_at', [$previousStartOfPeriod, $previousEndOfPeriod])->sum('amount');

        // Calculate growth percentages for users, orders, and earnings
        $userGrowthPercentage = $previousUsers ? (($totalUsers - $previousUsers) / $previousUsers) * 100 : 0;
        $orderGrowthPercentage = $previousOrders ? (($totalOrders - $previousOrders) / $previousOrders) * 100 : 0;
        $earningGrowthPercentage = $previousEarning ? (($totalEarning - $previousEarning) / $previousEarning) * 100 : 0;

        // Determine the status (up/down) based on the growth percentage
        $userStatus = $userGrowthPercentage >= 1 ? 'up' : 'down';
        $orderStatus = $orderGrowthPercentage >= 1 ? 'up' : 'down';
        $earningStatus = $earningGrowthPercentage >= 1 ? 'up' : 'down';


        return response()->json([
            'status' => 'success',
            'data' => [
                'period' => $period,
                'users' => [
                    'total_users' => $totalUsers,
                    'growth_percentage' => round($userGrowthPercentage, 2),
                    'status' => $userStatus,
                ],
                'orders' => [
                    'total_orders' => $totalOrders,
                    'growth_percentage' => round($orderGrowthPercentage, 2),
                    'status' => $orderStatus,
                ],
                'total_earning' => [
                    'total_earning' => $totalEarning ?? 0,
                    'growth_percentage' => round($earningGrowthPercentage, 2),
                    'status' => $earningStatus,
                ],
            ],
        ], 200);
    }
    //website visitor

    public function analytics(Request $request)
    {
        // Get filter type, default to weekly
        $filter = $request->query('filter', 'weekly');
        $year = $request->query('year', date('Y'));

        // Initialize variables to hold statistics
        $statistics = [];

        // Fetch all distinct years available in the database
        $years = User::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->get()
            ->pluck('year');

        if ($filter == 'weekly') {
            // Get weekly user data
            $weeklyStatistics = User::selectRaw('WEEK(created_at, 1) as week, COUNT(*) as total_user')
                ->whereYear('created_at', $year)
                ->groupBy('week')
                ->pluck('total_user', 'week');

            // Get weekly order data
            $weeklyOrders = Order::selectRaw('WEEK(created_at, 1) as week, COUNT(*) as total_orders')
                ->whereYear('created_at', $year)
                ->groupBy('week')
                ->pluck('total_orders', 'week');

            // Loop through all weeks and prepare data
            $statistics = [];
            for ($i = 1; $i <= 52; $i++) {
                $startOfWeek = \Carbon\Carbon::now()->setISODate($year, $i)->startOfWeek();
                $month_name = $startOfWeek->format('F'); // Get the month name

                $statistics[] = [
                    'week' => $i,
                    'month_name' => $month_name,
                    'total_users' => $weeklyStatistics[$i] ?? 0,
                    'total_orders' => $weeklyOrders[$i] ?? 0,
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

        // Get total earnings by month for the given year
        $data = Order::select(
            DB::raw("SUM(amount) as total_earnings"),
            DB::raw("MONTHNAME(created_at) as month_name"),
            DB::raw("MONTH(created_at) as month")
        )
            ->whereYear('created_at', $year) // Filter by year
            ->groupBy('month_name', 'month')
            ->orderByRaw('FIELD(month_name, "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December")')
            ->get();

        // Sort data by total earnings in descending order
        $sortedData = $data->sortByDesc('total_earnings');

        $topMonths = $sortedData->take(4)->map(function ($month) {
            return [
                'month_name' => $month->month_name,
                'total_earnings' => (float) $month->total_earnings,
            ];
        })->values();
        $mostEarningMonth = $sortedData->first();

        $totalYearEarnings = $data->sum('total_earnings');

        $mostEarningMonthPercentage = $totalYearEarnings ? ($mostEarningMonth->total_earnings / $totalYearEarnings) * 100 : 0;

        return response()->json([
            'status' => 'success',
            'year' => $year,
            'top_months' => $topMonths, // Return as array with indexed keys
            'most_earning_month' => $mostEarningMonth ? [
                'month_name' => $mostEarningMonth->month_name,
                'total_earnings' => (float) $mostEarningMonth->total_earnings,
                'percentage' => round($mostEarningMonthPercentage, 2),
            ] : null,
        ]);
    }

}
