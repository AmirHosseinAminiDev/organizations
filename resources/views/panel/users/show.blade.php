@extends('layout.app')
@section('content')
    <div class="container mt-5">
        <h2 class="mb-4">نمایش جزئیات کاربر</h2>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="bi bi-person-circle me-2"></i>{{ $user->name }} {{ $user->last_name ?? '' }}</h4>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <p><strong>کد ملی:</strong> {{ $user->national_code ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>موبایل:</strong> {{ $user->phone ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>سازمان:</strong> {{ $user->organization->name ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>نقش:</strong>
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
                        </p>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-start gap-2">
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                        <i class="bi bi-pencil-square me-1"></i> ویرایش
                    </a>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left-circle me-1"></i> بازگشت به لیست
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- برای استفاده از آیکون‌های Bootstrap Icons --}}
    @push('css')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    @endpush
@endsection
