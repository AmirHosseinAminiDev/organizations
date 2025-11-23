<?php

namespace App\Http\Controllers\Panel\Deductions;

use App\Enum\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Models\DeductionFile;
use App\Models\DeductionItem;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromArray;


class DeductionController extends Controller
{
    public function index()
    {
        $authUser = Auth::user();

//        if ($authUser->role->name !== UserRoleEnum::OPERATOR->value) {
//            abort(403, 'فقط کاربر اپراتور به این بخش دسترسی دارد.');
//        }

        $files = DeductionFile::query()
            ->with('organization')
            ->withCount('items')
            ->latest()
            ->get();

        return view('panel.deductions.index', compact('files'));
    }


    public function create()
    {
        $authUser = Auth::user();

        if ($authUser->role->name !== UserRoleEnum::OPERATOR->value) {
            abort(403, 'فقط کاربر اپراتور به این بخش دسترسی دارد.');
        }

        $organizations = Organization::orderBy('name')->get();

        return view('panel.deductions.create', compact('organizations'));
    }

    public function store(Request $request)
    {
        $authUser = Auth::user();

        if ($authUser->role->name !== UserRoleEnum::OPERATOR->value) {
            abort(403, 'فقط کاربر اپراتور به این بخش دسترسی دارد.');
        }

        $data = $request->validate([
            'organization_id' => 'required|exists:organizations,id',
            'year'  => 'required|integer|min:1400|max:1500',
            'month' => 'required|integer|min:1|max:12',
            'file'  => 'required|file|mimes:xlsx,xls,csv|max:20480',
        ]);

        $organizationId = (int) $data['organization_id'];

        if (
            DeductionFile::where('organization_id', $organizationId)
                ->where('year', $data['year'])
                ->where('month', $data['month'])
                ->exists()
        ) {
            return back()->withErrors([
                'file' => 'برای این سازمان و این ماه قبلاً فایلی ثبت شده است.',
            ])->withInput();
        }

        $uploadedFile = $request->file('file');
        $storedPath   = $uploadedFile->store('deductions', 'public');

        $file = DeductionFile::create([
            'organization_id' => $organizationId,
            'title'           => 'کسورات ' . $data['year'] . '/' . $data['month'],
            'year'            => $data['year'],
            'month'           => $data['month'],
            'original_name'   => $uploadedFile->getClientOriginalName(),
            'stored_path'     => $storedPath,
        ]);

        $rows = Excel::toArray([], $uploadedFile)[0] ?? [];

        if (empty($rows)) {
            $file->delete();
            Storage::disk('public')->delete($storedPath);

            return back()->withErrors([
                'file' => 'فایل اکسل خالی است.',
            ]);
        }

        $header           = array_map('trim', $rows[0]);
        $requiredColumns  = ['national_code', 'personnel_code', 'amount'];
        $normalizedHeader = array_map(fn($col) => strtolower(trim($col)), $header);

        $missing = array_diff($requiredColumns, $normalizedHeader);

        if (! empty($missing)) {
            $file->delete();
            Storage::disk('public')->delete($storedPath);

            return back()->withErrors([
                'file' => 'ساختار فایل اکسل اشتباه است. ستون‌های لازم: national_code, personnel_code, amount',
            ]);
        }

        unset($rows[0]);

        $allowedColumns = ['national_code', 'personnel_code', 'amount'];
        $itemsToInsert  = [];

        foreach ($rows as $row) {
            if (empty(array_filter($row))) {
                continue;
            }

            $rowData = [];

            foreach ($header as $index => $columnName) {
                $columnName = strtolower(trim($columnName));

                if ($columnName && in_array($columnName, $allowedColumns, true)) {
                    $rowData[$columnName] = $row[$index] ?? null;
                }
            }

            if (empty($rowData['national_code'] ?? null) || empty($rowData['amount'] ?? null)) {
                continue;
            }

            $itemsToInsert[] = [
                'deduction_file_id' => $file->id,
                'national_code'     => trim($rowData['national_code']),
                'personnel_code'    => $rowData['personnel_code'] ?? null,
                'amount'            => (int) $rowData['amount'],
                'created_at'        => now(),
                'updated_at'        => now(),
            ];
        }

        if (count($itemsToInsert)) {
            DeductionItem::insert($itemsToInsert);
        }

        return redirect()
            ->route('deductions.files.index')
            ->with('success', 'فایل کسورات با موفقیت آپلود و ردیف‌ها ثبت شدند.');
    }

    public function show(DeductionFile $file)
    {
        $authUser = Auth::user();
        $roleName = $authUser->role->name;

        if ($roleName === UserRoleEnum::OPERATOR->value) {
        } elseif (
            $roleName === UserRoleEnum::ORGANIZATION_ADMIN->value &&
            $file->organization_id == $authUser->organization_id
        ) {

        } else {
            abort(403, 'شما به این فایل دسترسی ندارید.');
        }

        $file->load('organization');

        $items = $file->items()
            ->with('user')
            ->orderBy('id')
            ->get();

        return view('panel.deductions.show', compact('file', 'items'));
    }


    public function export(DeductionFile $file)
    {
        $authUser = Auth::user();

        if ($authUser->role->name !== UserRoleEnum::OPERATOR->value) {
            abort(403, 'فقط کاربر اپراتور به این بخش دسترسی دارد.');
        }

        $items = $file->items()
            ->select('national_code', 'personnel_code', 'amount')
            ->orderBy('id')
            ->get();

        $rows = [];
        $rows[] = ['national_code', 'personnel_code', 'amount'];

        foreach ($items as $item) {
            $rows[] = [
                $item->national_code,
                $item->personnel_code,
                $item->amount,
            ];
        }

        $fileName = 'deductions_file_' . $file->id . '.xlsx';

        $export = new class($rows) implements FromArray {
            protected array $rows;
            public function __construct(array $rows)
            {
                $this->rows = $rows;
            }
            public function array(): array
            {
                return $this->rows;
            }
        };

        return Excel::download($export, $fileName);
    }

    public function updateUserEmploymentStatus(Request $request)
    {
        $authUser = Auth::user();

        if ($authUser->role->name !== UserRoleEnum::ORGANIZATION_ADMIN->value) {
            abort(403, 'فقط مدیر سازمان مجاز به تغییر وضعیت پرسنل است.');
        }

        $data = $request->validate([
            'user_id'     => 'required|exists:users,id',
            'status'      => 'required|in:left,dead,transferred,retired',
            'description' => 'nullable|string|max:1000',
        ]);

        $user = User::query()->where('id', $data['user_id'])
            ->where('organization_id', $authUser->organization_id)
            ->firstOrFail();

        $map = [
            'left'        => 1, // ترک کار
            'dead'        => 2, // فوت
            'transferred' => 3, // انتقالی
            'retired'     => 4, // بازنشسته
        ];

        $user->employment_status = $map[$data['status']];
        $user->employment_status_description =
            $data['status'] === 'transferred'
                ? ($data['description'] ?? null)
                : ($data['description'] ?? $user->employment_status_description);

        $user->save();

        return back()->with('success', 'وضعیت کاربر با موفقیت به‌روزرسانی شد.');
    }

    public function toggleStatus(DeductionFile $file)
    {
        $authUser = Auth::user();

        if ($authUser->role->name !== UserRoleEnum::OPERATOR->value) {
            abort(403, 'فقط کاربر اپراتور به این بخش دسترسی دارد.');
        }

        $file->status = $file->status ? 0 : 1;
        $file->save();

        $message = $file->status
            ? 'فایل کسورات به حالت فعال تغییر کرد.'
            : 'فایل کسورات به حالت غیرفعال تغییر کرد.';

        return back()->with('success', $message);
    }


}
