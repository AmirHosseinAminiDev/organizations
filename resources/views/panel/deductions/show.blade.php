@extends('layout.app')

@php
    use Morilog\Jalali\Jalalian;
    use App\Enum\UserRoleEnum;

    $authUser   = auth()->user();
    $isOrgAdmin = $authUser->role->name === UserRoleEnum::ORGANIZATION_ADMIN->value;
@endphp

@section('content')
    <div class="container mt-5">
        <h2 class="mb-4">جزئیات فایل کسورات</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-header">
                اطلاعات کلی فایل
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-3 mb-2">
                        <strong>سازمان:</strong>
                        <span class="ms-1">{{ $file->organization->name ?? '-' }}</span>
                    </div>
                    <div class="col-md-3 mb-2">
                        <strong>سال:</strong>
                        <span class="ms-1">{{ $file->year }}</span>
                    </div>
                    <div class="col-md-3 mb-2">
                        <strong>ماه:</strong>
                        <span class="ms-1">{{ $file->month }}</span>
                    </div>
                    <div class="col-md-3 mb-2">
                        <strong>وضعیت فایل:</strong>
                        <span class="ms-1">
                            @if($file->status == 1)
                                <span class="badge bg-success">فعال</span>
                            @else
                                <span class="badge bg-secondary">غیرفعال</span>
                            @endif
                        </span>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-4 mb-2">
                        <strong>نام فایل:</strong>
                        <span class="ms-1">{{ $file->original_name }}</span>
                    </div>
                    <div class="col-md-4 mb-2">
                        <strong>تاریخ ایجاد:</strong>
                        <span class="ms-1">
                            {{ Jalalian::fromDateTime($file->created_at)->format('Y/m/d H:i') }}
                        </span>
                    </div>
                    <div class="col-md-4 mb-2">
                        <strong>تعداد رکوردها:</strong>
                        <span class="ms-1">{{ $items->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        @if($items->isEmpty())
            <div class="alert alert-info">
                هیچ رکورد کسوراتی در این فایل ثبت نشده است.
            </div>
        @else
            <div class="card">
                <div class="card-header">
                    ردیف‌های کسورات
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0 text-center">
                            <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>نام</th>
                                <th>نام خانوادگی</th>
                                <th>کد ملی</th>
                                <th>کد پرسنلی</th>
                                <th>مبلغ (ریال)</th>
                                <th>وضعیت کاربر</th>
                                <th>توضیحات وضعیت</th>
                                @if($isOrgAdmin)
                                    <th>عملیات</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($items as $index => $item)
                                @php
                                    $user = $item->user;
                                    $status = $user->employment_status ?? null;
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $user->name ?? '-' }}</td>
                                    <td>{{ $user->last_name ?? '-' }}</td>
                                    <td>{{ $item->national_code }}</td>
                                    <td>{{ $item->personnel_code ?? '-' }}</td>
                                    <td>{{ number_format($item->amount) }}</td>
                                    <td>
                                        @if(!$status || $status == 0)
                                            <span class="badge bg-success">فعال</span>
                                        @elseif($status == 1)
                                            <span class="badge bg-warning text-dark">ترک کار</span>
                                        @elseif($status == 2)
                                            <span class="badge bg-dark">فوت</span>
                                        @elseif($status == 3)
                                            <span class="badge bg-info text-dark">انتقالی</span>
                                        @elseif($status == 4)
                                            <span class="badge bg-primary">بازنشسته</span>
                                        @else
                                            <span class="badge bg-secondary">نامشخص</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->employment_status_description ?? '-' }}</td>

                                    @if($isOrgAdmin)
                                        <td>
                                            @if($user)
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                            type="button" data-bs-toggle="dropdown">
                                                        عملیات
                                                    </button>
                                                    <ul class="dropdown-menu">

                                                        <li>
                                                            <form method="POST"
                                                                  action="{{ route('deductions.users.employment-status.update') }}"
                                                                  class="px-3 py-1">
                                                                @csrf
                                                                <input type="hidden" name="user_id"
                                                                       value="{{ $user->id }}">
                                                                <input type="hidden" name="status"
                                                                       value="left">
                                                                <button type="submit" class="btn btn-link p-0">
                                                                    ترک کار
                                                                </button>
                                                            </form>
                                                        </li>

                                                        <li>
                                                            <form method="POST"
                                                                  action="{{ route('deductions.users.employment-status.update') }}"
                                                                  class="px-3 py-1">
                                                                @csrf
                                                                <input type="hidden" name="user_id"
                                                                       value="{{ $user->id }}">
                                                                <input type="hidden" name="status"
                                                                       value="dead">
                                                                <button type="submit" class="btn btn-link p-0">
                                                                    فوت
                                                                </button>
                                                            </form>
                                                        </li>

                                                        <li>
                                                            <form method="POST"
                                                                  action="{{ route('deductions.users.employment-status.update') }}"
                                                                  class="px-3 py-1">
                                                                @csrf
                                                                <input type="hidden" name="user_id"
                                                                       value="{{ $user->id }}">
                                                                <input type="hidden" name="status"
                                                                       value="retired">
                                                                <button type="submit" class="btn btn-link p-0">
                                                                    بازنشسته
                                                                </button>
                                                            </form>
                                                        </li>

                                                        <li><hr class="dropdown-divider"></li>

                                                        <li>
                                                            <button type="button"
                                                                    class="dropdown-item btn-transfer-user"
                                                                    data-user-id="{{ $user->id }}">
                                                                انتقالی (با توضیحات)
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            @else
                                                <span class="text-muted">کاربر یافت نشد</span>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <div class="mt-3">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">بازگشت</a>
        </div>
    </div>

    @if($isOrgAdmin)
        <form id="transferUserForm" method="POST"
              action="{{ route('deductions.users.employment-status.update') }}"
              style="display:none;">
            @csrf
            <input type="hidden" name="user_id">
            <input type="hidden" name="status" value="transferred">
            <input type="hidden" name="description">
        </form>
    @endif
@endsection

@push('js')
    @if($isOrgAdmin)
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.btn-transfer-user').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        const userId = this.getAttribute('data-user-id');

                        Swal.fire({
                            title: 'ثبت وضعیت انتقالی',
                            input: 'textarea',
                            inputLabel: 'توضیحات',
                            inputPlaceholder: 'توضیحات انتقال را وارد کنید...',
                            inputAttributes: {
                                'aria-label': 'توضیحات انتقال'
                            },
                            showCancelButton: true,
                            confirmButtonText: 'ثبت',
                            cancelButtonText: 'انصراف',
                            reverseButtons: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const form = document.getElementById('transferUserForm');
                                form.querySelector('input[name="user_id"]').value = userId;
                                form.querySelector('input[name="description"]').value = result.value || '';
                                form.submit();
                            }
                        });
                    });
                });
            });
        </script>
    @endif
@endpush
