<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Order;
use App\Models\Package;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AdminController extends Controller
{
    public function dashboard(): JsonResponse
    {
        $totalUsers = User::count();
        $totalClients = User::where('role', 'client')->count();
        $totalTeachers = User::where('role', 'teacher')->count();

        $totalOrders = Order::count();
        $paidOrders = Order::where('status', 'paid')->count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $expiredOrders = Order::where('status', 'expired')->count();

        $totalRevenue = Order::where('status', 'paid')->sum('amount');

        $activeEnrollments = Enrollment::where('status', 'active')
            ->where('end_date', '>=', now()->toDateString())
            ->count();

        $totalPackages = Package::count();

        $recentOrders = Order::with(['user', 'package'])
            ->latest('id')
            ->limit(8)
            ->get()
            ->map(fn($order) => [
                'order_number' => $order->order_number,
                'user_name' => $order->user?->name,
                'user_email' => $order->user?->email,
                'package_name' => $order->package?->name,
                'amount' => $order->amount,
                'status' => $order->status,
                'created_at' => $order->created_at->diffForHumans(),
            ]);

        $recentUsers = User::latest('id')
            ->limit(6)
            ->get(['id', 'name', 'email', 'role', 'is_active', 'created_at'])
            ->map(fn($user) => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'is_active' => $user->is_active,
                'created_at' => $user->created_at->diffForHumans(),
            ]);

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => [
                    ['label' => 'Total User', 'value' => $totalUsers],
                    ['label' => 'Pendapatan', 'value' => 'Rp' . number_format((float) $totalRevenue, 0, ',', '.')],
                    ['label' => 'Pesanan Dibayar', 'value' => $paidOrders],
                    ['label' => 'Enrollment Aktif', 'value' => $activeEnrollments],
                ],
                'order_summary' => [
                    'total' => $totalOrders,
                    'paid' => $paidOrders,
                    'pending' => $pendingOrders,
                    'expired' => $expiredOrders,
                ],
                'user_summary' => [
                    'total' => $totalUsers,
                    'client' => $totalClients,
                    'teacher' => $totalTeachers,
                ],
                'total_packages' => $totalPackages,
                'recent_orders' => $recentOrders,
                'recent_users' => $recentUsers,
            ],
        ]);
    }
}
