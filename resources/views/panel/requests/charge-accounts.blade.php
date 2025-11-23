@extends('layout.app')

@section('content')
    @push('css')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    @endpush

    <div class="container mt-5">
        <h2 class="mb-4">لیست حساب‌های شارژ کاربران سازمان</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
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

        @if(empty($accounts))
            <div class="alert alert-info mt-3">هیچ حساب شارژی برای کاربران این سازمان یافت نشد.</div>
        @else
            <div class="card">
                <div class="card-header">اطلاعات حساب شارژ کاربران</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0">
                            <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>نام</th>
                                <th>نام خانوادگی</th>
                                <th>کد ملی</th>
                                <th>شماره کارت</th>
                                <th>مبلغ (ریال)</th>
                                <th>وضعیت</th>
                                <th>تاریخ ایجاد</th>
                                <th>آخرین به‌روزرسانی</th>
                                <th>عملیات</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($accounts as $index => $acc)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $acc['name'] }}</td>
                                    <td>{{ $acc['last_name'] }}</td>
                                    <td>{{ $acc['national_code'] }}</td>
                                    <td>{{ $acc['card_number'] }}</td>
                                    <td>{{ number_format($acc['amount']) }}</td>

                                    <td>
                                        @if($acc['status'] == 0)
                                            <span class="badge bg-warning">در انتظار تایید</span>
                                        @elseif($acc['status'] == 1)
                                            <span class="badge bg-success">تایید شده</span>
                                        @else
                                            <span class="badge bg-danger">رد شده</span>
                                        @endif
                                    </td>

                                    <td>{{ jdate($acc['created_at'])->format('Y/m/d H:i') }}</td>
                                    <td>{{ jdate($acc['updated_at'])->format('Y/m/d H:i') }}</td>

                                    <td>
                                        @if($acc['status'] == 0)
                                            <div class="d-flex gap-2">

                                                {{-- دکمه تایید --}}
                                                <form action="{{ route('requests.charge-accounts.update-status') }}"
                                                      method="POST"
                                                      class="action-form">
                                                    @csrf
                                                    <input type="hidden" name="uid" value="{{ $acc['uid'] }}">
                                                    <input type="hidden" name="type" value="accept">
                                                    <button type="button" class="btn btn-success btn-sm btn-confirm"
                                                            data-type="accept">
                                                        تایید درخواست
                                                    </button>
                                                </form>

                                                {{-- دکمه رد --}}
                                                <form action="{{ route('requests.charge-accounts.update-status') }}"
                                                      method="POST"
                                                      class="action-form">
                                                    @csrf
                                                    <input type="hidden" name="uid" value="{{ $acc['uid'] }}">
                                                    <input type="hidden" name="type" value="reject">
                                                    <button type="button" class="btn btn-danger btn-sm btn-confirm"
                                                            data-type="reject">
                                                        رد درخواست
                                                    </button>
                                                </form>

                                            </div>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
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
@endsection


@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.btn-confirm').forEach(button => {
                button.addEventListener('click', function () {

                    let form = this.closest('form');
                    let type = this.dataset.type;
                    let title = type === 'accept' ? 'تأیید درخواست؟' : 'رد درخواست؟';
                    let text = type === 'accept'
                        ? 'آیا از تایید این درخواست مطمئن هستید؟'
                        : 'آیا از رد کردن این درخواست مطمئن هستید؟';

                    Swal.fire({
                        title: title,
                        text: text,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: type === 'accept' ? '#28a745' : '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'بله، ادامه بده',
                        cancelButtonText: 'انصراف',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });

                });
            });
        });
    </script>
@endpush
