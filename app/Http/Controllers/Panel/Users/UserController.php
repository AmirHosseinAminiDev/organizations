<?php

namespace App\Http\Controllers\Panel\Users;

use App\Enum\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $authUser = auth()->user();

        $query = User::with(['organization', 'role'])
            ->where('id', '!=', $authUser->id)
            ->where('role_id', '!=', 1)->orderBy('id', 'desc');

        if ($authUser->role->name === UserRoleEnum::ORGANIZATION_ADMIN->value) {
            $query->where('organization_id', $authUser->organization_id);
        }

        if ($request->filled('role')) {
            $query->whereHas('role', fn($q) => $q->where('name', $request->role));
        }

        if ($request->filled('organization')) {
            $query->where('organization_id', $request->organization);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhere('national_code', 'like', "%{$search}%")
            );
        }

        $users = $query->paginate(10);

        return view('panel.users.index', compact('users'));
    }

    public function create()
    {
        $this->ensureCanManageUsers();

        return view('panel.users.create');
    }

    public function store(Request $request)
    {
        $this->ensureCanManageUsers();
        $authUser = auth()->user();

        $rules = [
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:users,phone',
            'national_code' => 'required|string|max:50|unique:users,national_code',
            'role' => 'required|in:super_admin,organization_admin,organization_user',
        ];

        if ($authUser->role->name === UserRoleEnum::SUPER_ADMIN->value) {
            $rules['organization_id'] = 'required|exists:organizations,id';
        } else {
            $rules['organization_id'] = 'nullable|exists:organizations,id';
        }

        $request->validate($rules);

        $role = Role::query()
            ->where('name', $request->role)
            ->firstOrFail();

        // ست کردن organization_id
        $organizationId = $request->organization_id;

        if ($authUser->role->name === UserRoleEnum::ORGANIZATION_ADMIN->value) {
            $organizationId = $authUser->organization_id;
        }

        User::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'email' => null,
            'password' => Hash::make('12345678'),
            'national_code' => $request->national_code,
            'role_id' => $role->id,
            'organization_id' => $organizationId,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'کاربر با موفقیت ایجاد شد.');
    }


    public function show(User $user)
    {
        $this->checkAccess($user);

        return view('panel.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $this->checkAccess($user);

        $roles = Role::pluck('label', 'id')->toArray();
        $organizations = Organization::all();

        return view('panel.users.edit', compact('user', 'roles', 'organizations'));
    }

    public function update(Request $request, User $user)
    {
        $this->checkAccess($user);

        $authUser = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20|unique:users,phone,' . $user->id,
            'national_code' => 'nullable|string|max:50|unique:users,national_code,' . $user->id,
            'role_id' => 'required|exists:roles,id',
            'organization_id' => 'nullable|exists:organizations,id',
        ]);

        // تعیین سازمان
        $organizationId = $request->organization_id;

        // اگر ادمین سازمان است، همیشه سازمان خودش را ست می‌کنیم
        if ($authUser->role->name === UserRoleEnum::ORGANIZATION_ADMIN->value) {
            $organizationId = $authUser->organization_id;
        }

        // اگر از فرم چیزی نیامده ولی خود کاربر قبلاً سازمان داشته، همان را نگه دار
        if (!$organizationId && $user->organization_id) {
            $organizationId = $user->organization_id;
        }

        // نقش جدید از فرم
        $newRoleId = (int)$request->role_id;

        $isSuperAdmin = $authUser->role->name === UserRoleEnum::SUPER_ADMIN->value;

        // اگر سوپر ادمین نیست، اجازه تغییر نقش ندارد
        if (!$isSuperAdmin) {
            $newRoleId = $user->role_id;
        }

        // نقش‌های مربوط به "مدیر سازمان" و "کاربر سازمان"
        $orgAdminRole = Role::where('name', UserRoleEnum::ORGANIZATION_ADMIN->value)->first();
        $orgUserRole = Role::where('name', UserRoleEnum::ORGANIZATION_USER->value)->first();

        // اگر سوپر ادمین است و قرار است این کاربر مدیر سازمان شود
        if (
            $isSuperAdmin &&
            $orgAdminRole &&
            $orgUserRole &&
            $newRoleId === (int)$orgAdminRole->id
        ) {
            if ($organizationId) {
                // مدیر فعلی این سازمان را پیدا کن (به جز همین کاربر)
                $currentAdmin = User::where('organization_id', $organizationId)
                    ->where('role_id', $orgAdminRole->id)
                    ->where('id', '!=', $user->id)
                    ->first();

                // اگر مدیر فعلی وجود دارد → نقش او را کاربر سازمان کن
                if ($currentAdmin) {
                    $currentAdmin->update([
                        'role_id' => $orgUserRole->id,
                    ]);
                }
            }
        }

        // در نهایت کاربر را آپدیت می‌کنیم
        $user->update([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'national_code' => $request->national_code,
            'role_id' => $newRoleId,
            'organization_id' => $organizationId,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'کاربر با موفقیت به‌روزرسانی شد.');
    }

    public function delete(User $user)
    {
        $this->checkAccess($user);

        $authUser = auth()->user();

        $orgAdminRole = Role::where('name', UserRoleEnum::ORGANIZATION_ADMIN->value)->first();

        if (
            $authUser->role->name === UserRoleEnum::SUPER_ADMIN->value &&
            $orgAdminRole &&
            $user->role_id === $orgAdminRole->id &&
            $user->organization_id
        ) {
            $hasAnotherAdmin = User::where('organization_id', $user->organization_id)
                ->where('role_id', $orgAdminRole->id)
                ->where('id', '!=', $user->id)
                ->exists();

            if (! $hasAnotherAdmin) {
                return redirect()->route('users.index')
                    ->withErrors([
                        'delete' => 'این کاربر مدیر فعلی سازمان است. ابتدا یک مدیر جایگزین برای این سازمان تعیین کنید، سپس دوباره برای حذف اقدام کنید.',
                    ]);
            }
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'کاربر با موفقیت حذف شد.');
    }


    public function import(Request $request)
    {
        $this->ensureCanManageUsers();
        $authUser = auth()->user();

        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $file = $request->file('excel_file');

        $rows = Excel::toArray([], $file)[0] ?? [];

        if (empty($rows)) {
            return back()->withErrors(['excel_file' => 'فایل اکسل خالی است.']);
        }

        // سطر اول هدر
        $header = array_map('trim', $rows[0]);
        unset($rows[0]);

        $createdCount = 0;
        $failed = [];

        // نقش پیش‌فرض برای همه کاربران ایمپورت‌شده: کاربر سازمان
        $defaultRole = Role::where('name', UserRoleEnum::ORGANIZATION_USER->value)->firstOrFail();

        foreach ($rows as $index => $row) {
            if (empty(array_filter($row))) {
                continue; // سطر کامل خالی
            }

            $data = [];

            // فقط ستون‌های مورد نیاز
            $allowedColumns = ['name', 'last_name', 'national_code', 'phone'];

            foreach ($header as $keyIndex => $columnName) {
                $columnName = trim($columnName);
                if ($columnName && in_array($columnName, $allowedColumns)) {
                    $data[$columnName] = $row[$keyIndex] ?? null;
                }
            }

            try {
                // اعتبارسنجی ساده در هر ردیف
                if (empty($data['name'] ?? null)) {
                    throw new \Exception('ستون name خالی است.');
                }
                if (empty($data['last_name'] ?? null)) {
                    throw new \Exception('ستون last_name خالی است.');
                }
                if (empty($data['national_code'] ?? null)) {
                    throw new \Exception('ستون national_code خالی است.');
                }
                if (empty($data['phone'] ?? null)) {
                    throw new \Exception('ستون phone خالی است.');
                }

                // یکتا بودن کد ملی
                if (User::where('national_code', $data['national_code'])->exists()) {
                    throw new \Exception('کاربری با این national_code قبلاً ثبت شده است.');
                }

                // یکتا بودن موبایل
                if (User::where('phone', $data['phone'])->exists()) {
                    throw new \Exception('شماره موبایل ' . $data['phone'] . ' قبلاً استفاده شده است.');
                }

                // تعیین سازمان
                $organizationId = $authUser->organization_id;
                if ($authUser->role->name !== UserRoleEnum::ORGANIZATION_ADMIN->value) {
                    // برای سوپر ادمین اگر خودش سازمانی نداشته باشد می‌تواند null بماند
                    $organizationId = $authUser->organization_id ?? null;
                }

                User::create([
                    'name' => $data['name'],
                    'last_name' => $data['last_name'],
                    'phone' => $data['phone'],
                    'email' => null, // در ایمپورت استفاده نمی‌شود
                    'password' => Hash::make('12345678'),
                    'national_code' => $data['national_code'],
                    'role_id' => $defaultRole->id,
                    'organization_id' => $organizationId,
                ]);

                $createdCount++;
            } catch (\Throwable $e) {
                $failed[] = 'ردیف ' . ($index + 1) . ': ' . $e->getMessage();
            }
        }

        $message = "تعداد {$createdCount} کاربر با موفقیت ایجاد شد.";

        if (!empty($failed)) {
            return redirect()->route('users.index')
                ->with('success', $message)
                ->with('import_errors', $failed);
        }

        return redirect()->route('users.index')
            ->with('success', $message);
    }

    /**
     * چک کردن دسترسی ادمین سازمان به کاربران خودش
     */
    protected function checkAccess(User $user): void
    {
        $authUser = auth()->user();

        if (
            $authUser->role->name === UserRoleEnum::ORGANIZATION_ADMIN->value &&
            $user->organization_id !== $authUser->organization_id
        ) {
            abort(403, 'شما اجازه دسترسی به این کاربر را ندارید.');
        }
    }

    /**
     * فقط سوپر ادمین و مدیر سازمان اجازه ایجاد/ایمپورت کاربر داشته باشند
     */
    protected function ensureCanManageUsers(): void
    {
        $authUser = auth()->user();

        if (!in_array($authUser->role->name, [
            UserRoleEnum::SUPER_ADMIN->value,
            UserRoleEnum::ORGANIZATION_ADMIN->value,
        ], true)) {
            abort(403, 'شما اجازه دسترسی به این بخش را ندارید.');
        }
    }
}
