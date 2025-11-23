@extends('layout.app')

@section('content')

    @php
        use Morilog\Jalali\Jalalian;

        $now = Jalalian::now();
        $currentJalaliYear  = $now->getYear();
        $currentJalaliMonth = $now->getMonth();

        $years = range($currentJalaliYear - 10, $currentJalaliYear + 10);

        $monthNames = [
            1  => 'فروردین',
            2  => 'اردیبهشت',
            3  => 'خرداد',
            4  => 'تیر',
            5  => 'مرداد',
            6  => 'شهریور',
            7  => 'مهر',
            8  => 'آبان',
            9  => 'آذر',
            10 => 'دی',
            11 => 'بهمن',
            12 => 'اسفند',
        ];
    @endphp

    <div class="container mt-5">
        <h2 class="mb-4">آپلود فایل کسورات جدید</h2>

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

        <div class="card">
            <div class="card-header">
                آپلود فایل کسورات ماهانه
            </div>

            <div class="card-body">
                <form action="{{ route('deductions.files.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">

                        {{-- سازمان --}}
                        <div class="col-md-4">
                            <label for="organization_id" class="form-label">سازمان</label>
                            <select name="organization_id" id="organization_id" class="form-select" required>
                                <option value="">انتخاب سازمان</option>
                                @foreach($organizations as $org)
                                    <option
                                        value="{{ $org->id }}"
                                        {{ (int)old('organization_id') === $org->id ? 'selected' : '' }}
                                    >
                                        {{ $org->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- سال (جلالی) --}}
                        <div class="col-md-4">
                            <label for="year" class="form-label">سال</label>
                            <select name="year" id="year" class="form-select" required>
                                @foreach($years as $year)
                                    <option value="{{ $year }}"
                                        {{ (int)old('year', $currentJalaliYear) === $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- ماه --}}
                        <div class="col-md-4">
                            <label for="month" class="form-label">ماه</label>
                            <select name="month" id="month" class="form-select" required>
                                <option value="">انتخاب ماه</option>
                                @foreach($monthNames as $num => $label)
                                    <option value="{{ $num }}"
                                        {{
                                            (int)old('month', $currentJalaliMonth) === $num
                                                ? 'selected'
                                                : ''
                                        }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- فایل --}}
                        <div class="col-md-12">
                            <label for="file" class="form-label">فایل اکسل کسورات</label>
                            <input
                                type="file"
                                name="file"
                                id="file"
                                class="form-control"
                                accept=".xlsx,.xls,.csv"
                                required
                            >
                            <small class="text-muted">
                                فرمت‌های مجاز: xlsx, xls, csv — حداکثر ۲۰ مگابایت.
                                ستون‌های لازم: <code>national_code</code>، <code>personnel_code</code>، <code>amount</code>.
                            </small>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-success">آپلود و ثبت</button>
                        <a href="{{ route('deductions.files.index') }}" class="btn btn-secondary">بازگشت به لیست</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
