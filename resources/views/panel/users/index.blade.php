@extends('layout.app')
@section('content')
    @push('css')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    @endpush
    <div class="container mt-5">
        @if(session('success'))
            <div class="alert alert-success">{{ session()->get('success') }}</div>
        @endif

        {{-- نمایش خطاها (از جمله خطای حذف مدیر سازمان) --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h2 class="mb-4">لیست کاربران</h2>

        <form method="GET" class="row g-3 mb-4">
            @if(auth()->user()->role_id == 1)
                <div class="col-md-3">
                    <label for="role" class="form-label">فیلتر بر اساس نقش</label>
                    <select name="role" id="role" class="form-select">
                        <option value="">همه نقش‌ها</option>
                        <option value="super_admin" {{ request('role')=='super_admin' ? 'selected' : '' }}>سوپر ادمین
                        </option>
                        <option value="organization_admin" {{ request('role')=='organization_admin' ? 'selected' : '' }}>
                            مدیر سازمان
                        </option>
                        <option value="organization_user" {{ request('role')=='organization_user' ? 'selected' : '' }}>
                            کاربر سازمان
                        </option>
                    </select>
                </div>
            @endif

            @if(auth()->user()->role->name == \App\Enum\UserRoleEnum::SUPER_ADMIN->value)
                <div class="col-md-3">
                    <label for="organization" class="form-label">فیلتر بر اساس سازمان</label>
                    <select name="organization" id="organization" class="form-select w-100">
                        <option></option>
                        @foreach(\App\Models\Organization::all() as $org)
                            <option
                                value="{{ $org->id }}" {{ request('organization')==$org->id ? 'selected' : '' }}>
                                {{ $org->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="col-md-4">
                <label for="search" class="form-label">جستجو در کاربران</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" class="form-control"
                       placeholder="نام - نام خانوادگی - کد ملی - شماره تلفن">
            </div>

            <div class="col-md-2 align-self-end">
                <button type="submit" class="btn btn-primary w-100">اعمال فیلتر</button>
            </div>

            {{-- دکمه پاک کردن فیلتر در صورت وجود سرچ --}}
            @if(request('search'))
                <div class="col-md-2 align-self-end">
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary w-100">
                        پاک کردن فیلتر
                    </a>
                </div>
            @endif
        </form>

        @if($users->isEmpty())
            <div class="alert alert-info">هیچ کاربری برای نمایش وجود ندارد.</div>
        @else
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>نام</th>
                    <th>نام خانوادگی</th>
                    <th>موبایل</th>
                    <th>نقش</th>
                    <th>سازمان</th>
                    <th>عملیات</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $index => $user)
                    <tr>
                        <td>{{ $users->firstItem() + $index }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->last_name ?? '-' }}</td>
                        <td>{{ $user->phone ?? '-' }}</td>
                        <td>
                            @php
                                $roleBadges = [
                                    'super_admin' => ['label' => 'سوپر ادمین', 'class' => 'badge bg-danger'],
                                    'organization_admin' => ['label' => 'مدیر سازمان', 'class' => 'badge bg-primary'],
                                    'organization_user' => ['label' => 'کاربر سازمان', 'class' => 'badge bg-success'],
                                ];
                                $roleName = $user->role->name ?? 'unknown';
                                $badge = $roleBadges[$roleName] ?? ['label' => $roleName, 'class' => 'badge bg-secondary'];
                            @endphp
                            <span class="{{ $badge['class'] }}">{{ $badge['label'] }}</span>
                        </td>
                        <td>{{ $user->organization->name ?? '-' }}</td>
                        <td>
                            <a href="{{ route('users.show',$user) }}" class="btn btn-success">نمایش</a>
                            <a href="{{ route('users.edit',$user) }}" class="btn btn-warning">ویرایش</a>
                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline delete-user-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">حذف</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-center mt-3">
                {{ $users->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#organization').select2({
                placeholder: "انتخاب سازمان",
                allowClear: true,
                width: '100%'
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#organization').select2({
                placeholder: "انتخاب سازمان",
                allowClear: true,
                width: '100%'
            });

            $('.delete-user-form').on('submit', function(e) {
                e.preventDefault();

                let form = this;

                Swal.fire({
                    title: 'آیا از حذف مطمئن هستید؟',
                    text: "این عملیات غیرقابل بازگشت است!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'بله، حذف کن',
                    cancelButtonText: 'انصراف'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>

@endpush
