<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Order;
use App\Models\Package;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

    public function users(Request $request): JsonResponse
    {
        $query = User::query()
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->input('search');
                $q->where(function ($qq) use ($search) {
                    $qq->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('role') && $request->input('role') !== 'all', function ($q) use ($request) {
                $q->where('role', $request->input('role'));
            })
            ->latest();

        $perPage = max(1, min(50, (int) $request->input('per_page', 10)));
        $users = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $users->through(fn ($user) => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'whatsapp' => $user->whatsapp,
                'username' => $user->username,
                'role' => $user->role,
                'is_active' => $user->is_active,
                'email_verified_at' => $user->email_verified_at,
                'created_at' => $user->created_at?->diffForHumans(),
                'created_at_raw' => $user->created_at?->toDateTimeString(),
            ]),
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ],
        ]);
    }

    public function updateUser(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'is_active' => ['sometimes', 'boolean'],
            'role' => ['sometimes', 'in:admin,teacher,client'],
        ]);

        if (array_key_exists('is_active', $validated)) {
            $user->is_active = $validated['is_active'];
        }

        if (array_key_exists('role', $validated)) {
            $user->role = $validated['role'];
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Data user berhasil diperbarui.',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'is_active' => $user->is_active,
            ],
        ]);
    }

    public function packages(Request $request): JsonResponse
    {
        $packages = Package::query()
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->input('search');
                $q->where(function ($qq) use ($search) {
                    $qq->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            })
            ->withCount(['orders as orders_count', 'enrollments as enrollments_count'])
            ->orderByDesc('is_active')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $packages->map(fn ($package) => [
                'id' => $package->id,
                'name' => $package->name,
                'slug' => $package->slug,
                'type' => $package->type,
                'level' => $package->level,
                'price' => $package->price,
                'price_formatted' => $package->price_formatted,
                'duration_days' => $package->duration_days,
                'duration_label' => $package->duration_label,
                'is_active' => $package->is_active,
                'orders_count' => $package->orders_count,
                'enrollments_count' => $package->enrollments_count,
                'created_at' => $package->created_at?->diffForHumans(),
            ]),
        ]);
    }

    public function togglePackage(Package $package): JsonResponse
    {
        $package->is_active = ! $package->is_active;
        $package->save();

        return response()->json([
            'success' => true,
            'message' => 'Status paket berhasil diperbarui.',
            'data' => [
                'id' => $package->id,
                'is_active' => $package->is_active,
            ],
        ]);
    }

    public function orders(Request $request): JsonResponse
    {
        $query = Order::query()
            ->with(['user', 'package'])
            ->when($request->filled('status') && $request->input('status') !== 'all', function ($q) use ($request) {
                $q->where('status', $request->input('status'));
            })
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->input('search');
                $q->where(function ($qq) use ($search) {
                    $qq->where('order_number', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($u) use ($search) {
                            $u->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->latest();

        $perPage = max(1, min(50, (int) $request->input('per_page', 10)));
        $orders = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $orders->through(fn ($order) => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'user_name' => $order->user?->name,
                'user_email' => $order->user?->email,
                'package_name' => $order->package?->name,
                'amount' => $order->amount,
                'amount_formatted' => 'Rp' . number_format((float) $order->amount, 0, ',', '.'),
                'status' => $order->status,
                'paid_at' => $order->paid_at?->toDateTimeString(),
                'created_at' => $order->created_at->diffForHumans(),
                'created_at_raw' => $order->created_at->toDateTimeString(),
            ]),
            'meta' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
            ],
        ]);
    }

    public function profile(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'whatsapp' => $user->whatsapp,
                'username' => $user->username,
                'role' => $user->role,
                'created_at' => $user->created_at?->toDateTimeString(),
            ],
        ]);
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'min:3', 'max:100'],
            'whatsapp' => ['sometimes', 'nullable', 'regex:/^(\+62|62|0)8[0-9]{8,13}$/'],
            'current_password' => ['required_with:password', 'string'],
            'password' => ['sometimes', 'nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'whatsapp.regex' => 'Nomor WhatsApp harus memakai format Indonesia yang valid, contoh 081234567890.',
        ]);

        if (isset($validated['name'])) {
            $user->name = $validated['name'];
        }

        if (array_key_exists('whatsapp', $validated)) {
            $user->whatsapp = $validated['whatsapp'];
        }

        if (! empty($validated['password'])) {
            if (! Hash::check($validated['current_password'], $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password saat ini tidak sesuai.',
                    'errors' => ['current_password' => ['Password saat ini tidak sesuai.']],
                ], 422);
            }

            $user->password = $validated['password'];
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui.',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'whatsapp' => $user->whatsapp,
                'username' => $user->username,
                'role' => $user->role,
            ],
        ]);
    }
}
