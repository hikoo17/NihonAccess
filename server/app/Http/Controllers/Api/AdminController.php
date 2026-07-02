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
use Illuminate\Support\Str;

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

        $data = $users->getCollection()->map(fn ($user) => [
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
        ])->values();

        return response()->json([
            'success' => true,
            'data' => $data,
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
            ->with(['features' => function ($q) {
                $q->orderBy('sort_order');
            }])
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
                'icon' => $package->icon,
                'level' => $package->level,
                'description' => $package->description,
                'price' => $package->price,
                'price_formatted' => $package->price_formatted,
                'duration_days' => $package->duration_days,
                'duration_label' => $package->duration_label,
                'is_active' => $package->is_active,
                'orders_count' => $package->orders_count,
                'enrollments_count' => $package->enrollments_count,
                'features' => $package->features->map(fn ($f) => [
                    'id' => $f->id,
                    'name' => $f->name,
                ]),
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

    public function storePackage(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:120'],
            'slug' => ['nullable', 'string', 'max:160', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'type' => ['required', 'in:online,onsite'],
            'icon' => ['nullable', 'string', 'max:255'],
            'level' => ['nullable', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration_days' => ['required', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'features' => ['nullable', 'array'],
            'features.*' => ['string', 'max:255'],
            'highlights' => ['nullable', 'array'],
            'highlights.*.label' => ['required', 'string', 'max:120'],
            'highlights.*.value' => ['required', 'string', 'max:120'],
            'modules' => ['nullable', 'array'],
            'modules.*.name' => ['required', 'string', 'max:160'],
            'modules.*.description' => ['nullable', 'string', 'max:500'],
            'suitable_for' => ['nullable', 'array'],
            'suitable_for.*' => ['string', 'max:300'],
        ], [
            'slug.regex' => 'Slug hanya boleh berisi huruf kecil, angka, dan tanda hubung.',
        ]);

        $typeDefaults = [
            'online' => [
                'badge' => 'Online Course',
                'format' => 'Belajar Daring',
                'price_note' => 'Akses pembelajaran sepenuhnya online',
            ],
            'onsite' => [
                'badge' => 'Kelas Tatap Muka',
                'format' => 'Belajar Luring',
                'price_note' => 'Termasuk sesi belajar langsung di lokasi',
            ],
        ];
        $defaults = $typeDefaults[$validated['type']] ?? ['badge' => null, 'format' => null, 'price_note' => null];

        $slug = $validated['slug'] ?? Str::slug($validated['name']);
        $original = $slug;
        $counter = 1;
        while (Package::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $counter++;
        }

        $package = Package::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'type' => $validated['type'],
            'icon' => $validated['icon'] ?? null,
            'badge' => $defaults['badge'],
            'format' => $defaults['format'],
            'level' => $validated['level'] ?? null,
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'price_note' => $defaults['price_note'],
            'duration_days' => $validated['duration_days'],
            'highlights' => $validated['highlights'] ?? null,
            'modules' => $validated['modules'] ?? null,
            'suitable_for' => $validated['suitable_for'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        $features = collect($validated['features'] ?? [])
            ->map(fn ($name) => trim((string) $name))
            ->filter(fn ($name) => $name !== '')
            ->values();

        foreach ($features as $index => $name) {
            $package->features()->create([
                'name' => $name,
                'sort_order' => $index,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Paket berhasil ditambahkan.',
            'data' => [
                'id' => $package->id,
                'name' => $package->name,
                'slug' => $package->slug,
                'type' => $package->type,
                'icon' => $package->icon,
                'badge' => $package->badge,
                'format' => $package->format,
                'level' => $package->level,
                'description' => $package->description,
                'price' => $package->price,
                'price_formatted' => $package->price_formatted,
                'price_note' => $package->price_note,
                'duration_days' => $package->duration_days,
                'duration_label' => $package->duration_label,
                'highlights' => $package->highlights,
                'modules' => $package->modules,
                'suitable_for' => $package->suitable_for,
                'is_active' => $package->is_active,
                'features' => $package->features()->orderBy('sort_order')->get()->map(fn ($f) => [
                    'id' => $f->id,
                    'name' => $f->name,
                ]),
            ],
        ], 201);
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

        $data = $orders->getCollection()->map(fn ($order) => [
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
        ])->values();

        return response()->json([
            'success' => true,
            'data' => $data,
            'meta' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
            ],
        ]);
    }

    public function chartData(Request $request): JsonResponse
    {
        $period = $request->query('period', 'month');

        if ($period === 'day') {
            $periods = collect(range(6, 0))->map(fn ($i) => now()->subDays($i)->startOfDay());
            $labelFormat = 'd M';
        } elseif ($period === 'week') {
            $periods = collect(range(5, 0))->map(fn ($i) => now()->subWeeks($i)->startOfWeek());
            $labelFormat = 'd M';
        } else {
            $periods = collect(range(5, 0))->map(fn ($i) => now()->subMonths($i)->startOfMonth());
            $labelFormat = 'M';
        }

        $ordersSeries = $periods->map(function ($p) use ($period, $labelFormat) {
            $count = $period === 'day'
                ? Order::whereDate('created_at', $p->toDateString())->count()
                : Order::whereBetween('created_at', [$p, $period === 'week' ? $p->copy()->endOfWeek() : $p->copy()->endOfMonth()])->count();

            return [
                'label' => $p->format($labelFormat),
                'orders' => $count,
            ];
        });

        $revenueSeries = $periods->map(function ($p) use ($period, $labelFormat) {
            $query = Order::where('status', 'paid');

            if ($period === 'day') {
                $query->whereDate('paid_at', $p->toDateString());
            } else {
                $query->whereBetween('paid_at', [$p, $period === 'week' ? $p->copy()->endOfWeek() : $p->copy()->endOfMonth()]);
            }

            return [
                'label' => $p->format($labelFormat),
                'revenue' => (float) $query->sum('amount'),
            ];
        });

        $roleDistribution = [
            ['label' => 'Client', 'value' => User::where('role', 'client')->count()],
            ['label' => 'Teacher', 'value' => User::where('role', 'teacher')->count()],
            ['label' => 'Admin', 'value' => User::where('role', 'admin')->count()],
        ];

        $statusDistribution = [
            ['label' => 'Dibayar', 'value' => Order::where('status', 'paid')->count()],
            ['label' => 'Pending', 'value' => Order::where('status', 'pending')->count()],
            ['label' => 'Expired', 'value' => Order::where('status', 'expired')->count()],
            ['label' => 'Lainnya', 'value' => Order::whereIn('status', ['failed', 'cancelled'])->count()],
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'months' => $ordersSeries->pluck('label'),
                'orders' => $ordersSeries->pluck('orders'),
                'revenue' => $revenueSeries->pluck('revenue'),
                'role_distribution' => $roleDistribution,
                'status_distribution' => $statusDistribution,
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
