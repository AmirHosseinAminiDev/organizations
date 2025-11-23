@extends('layout.app')

@section('content')
    @push('css')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    @endpush

    <div class="container mt-5">
        <h2 class="mb-4">لیست درخواست‌های صدور مجدد کارت کاربران سازمان</h2>

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

        @if(empty($requests) || count($requests) === 0)
            <div class="alert alert-info mt-3">
                هیچ درخواست صدور مجدد کارتی برای کاربران این سازمان یافت نشد.
            </div>
        @else
            <div class="card">
                <div class="card-header">
                    اطلاعات درخواست‌های صدور مجدد کارت
                </div>
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
                                <th>توضیحات</th>
                                <th>وضعیت</th>
                                <th>تاریخ ایجاد</th>
                                <th>آخرین به‌روزرسانی</th>
                                <th>عملیات</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($requests as $index => $req)
                                @php $status = $req['status'] ?? null; @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $req['name'] ?? '-' }}</td>
                                    <td>{{ $req['last_name'] ?? '-' }}</td>
                                    <td>{{ $req['national_code'] ?? '-' }}</td>
                                    <td>{{ $req['card_number'] ?? '-' }}</td>
                                    <td>{{ $req['description'] ?? '-' }}</td>

                                    <td>
                                        @if($status == 0)
                                            <span class="badge bg-warning">در انتظار تایید سازمان</span>
                                        @elseif($status == 1)
                                            <span class="badge bg-success">تایید شده توسط سازمان</span>
                                        @elseif($status == -1)
                                            <span class="badge bg-danger">رد شده توسط سازمان</span>
                                        @else
                                            <span class="badge bg-secondary">نامشخص</span>
                                        @endif
                                    </td>

                                    <td>
                                        {{ isset($req['created_at']) ? jdate($req['created_at'])->format('Y/m/d H:i') : '-' }}
                                    </td>

                                    <td>
                                        {{ isset($req['updated_at']) ? jdate($req['updated_at'])->format('Y/m/d H:i') : '-' }}
                                    </td>

                                    {{-- ستون عملیات --}}
                                    <td>
                                        @if($status == 0)
                                            <div class="d-flex gap-2">

                                                {{-- دکمه تایید --}}
                                                <form action="{{ route('requests.reissue-card-requests.update-status') }}"
                                                      method="POST"
                                                      class="reissue-status-form">
                                                    @csrf
                                                    <input type="hidden" name="uid" value="{{ $req['uid'] }}">
                                                    <input type="hidden" name="type" value="accept">
                                                    <button type="submit" class="btn btn-success btn-sm">
                                                        تایید درخواست
                                                    </button>
                                                </form>

                                                {{-- دکمه رد --}}
                                                <form action="{{ route('requests.reissue-card-requests.update-status') }}"
                                                      method="POST"
                                                      class="reissue-status-form">
                                                    @csrf
                                                    <input type="hidden" name="uid" value="{{ $req['uid'] }}">
                                                    <input type="hidden" name="type" value="reject">
                                                    <button type="submit" class="btn btn-danger btn-sm">
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
            const forms = document.querySelectorAll('.reissue-status-form');

            forms.forEach(function (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    const typeInput = form.querySelector('input[name="type"]');
                    const type = typeInput ? typeInput.value : 'accept';

                    const title = type === 'accept'
                        ? 'تایید درخواست صدور مجدد کارت'
                        : 'رد درخواست صدور مجدد کارت';

                    const text = type === 'accept'
                        ? 'آیا از تایید این درخواست مطمئن هستید؟'
                        : 'آیا از رد این درخواست مطمئن هستید؟';

                    Swal.fire({
                        title: title,
                        text: text,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#aaa',
                        confirmButtonText: 'بله، انجام بده',
                        cancelButtonText: 'انصراف'
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
