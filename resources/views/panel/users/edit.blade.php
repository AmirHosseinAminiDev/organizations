@extends('layout.app')
@section('content')
    @push('css')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    @endpush

    <div class="container mt-5">
        <h2 class="mb-4"><i class="bi bi-pencil-square me-2"></i>ویرایش کاربر</h2>

        @if(session()->has('success'))
            <div class="alert alert-success">{{ session()->get('success') }}</div>
        @endif

        @if($errors->any())
            @foreach($errors->all() as $error)
                <div class="alert alert-danger">{{ $error }}</div>
            @endforeach
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0">
                    <i class="bi bi-person-circle me-2"></i>{{ $user->name }} {{ $user->last_name ?? '' }}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">نام</label>
                            <input type="text" name="name" id="name"
                                   value="{{ old('name', $user->name) }}"
                                   class="form-control">
                            @error('name')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="last_name" class="form-label">نام خانوادگی</label>
                            <input type="text" name="last_name" id="last_name"
                                   value="{{ old('last_name', $user->last_name) }}"
                                   class="form-control">
                            @error('last_name')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="national_code" class="form-label">کد ملی</label>
                            <input type="text" name="national_code" id="national_code"
                                   value="{{ old('national_code', $user->national_code) }}"
                                   class="form-control">
                            @error('national_code')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label">موبایل</label>
                            <input type="text" name="phone" id="phone"
                                   value="{{ old('phone', $user->phone) }}"
                                   class="form-control">
                            @error('phone')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="organization_id" class="form-label">سازمان</label>
                            <select name="organization_id" id="organization_id" class="form-select w-100">
                                <option value="">انتخاب سازمان</option>
                                @foreach($organizations as $org)
                                    <option
                                        value="{{ $org->id }}"
                                        {{ old('organization_id', $user->organization_id) == $org->id ? 'selected' : '' }}>
                                        {{ $org->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('organization_id')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="role_id" class="form-label">نقش</label>

                            @if(auth()->user()->role->name == \App\Enum\UserRoleEnum::SUPER_ADMIN->value)
                                {{-- فقط سوپر ادمین می‌تواند نقش را تغییر دهد --}}
                                <select name="role_id" id="role_id" class="form-select">
                                    @foreach($roles as $id => $label)
                                        <option value="{{ $id }}"
                                            {{ old('role_id', $user->role_id) == $id ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <input type="text" class="form-control"
                                       value="{{ $user->role->label ?? 'بدون نقش' }}" disabled>
                                <input type="hidden" name="role_id" value="{{ $user->role_id }}">
                            @endif

                            @error('role_id')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> ذخیره تغییرات
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-1"></i> لغو
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#organization_id').select2({
                    placeholder: "انتخاب سازمان",
                    allowClear: true,
                    width: '100%'
                });
            });
        </script>
    @endpush
@endsection
