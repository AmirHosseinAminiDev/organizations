@extends('layout.app')

@php
    use Morilog\Jalali\Jalalian;
@endphp

@section('content')
    <div class="container mt-5">
        <h2 class="mb-4">
            جزئیات فایل کسورات
            @if($file->title)
                ({{ $file->title }})
            @endif
        </h2>

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

                <dl class="row mb-2">
                    <dt class="col-md-2 text-md-end text-start mb-2 mb-md-0">سازمان:</dt>
                    <dd class="col-md-4 text-start mb-2 mb-md-0">
                        {{ $file->organization->name ?? '-' }}
                    </dd>

                    <dt class="col-md-2 text-md-end text-start mb-2 mb-md-0">نام فایل:</dt>
                    <dd class="col-md-4 text-start mb-2 mb-md-0">
                        {{ $file->original_name }}
                    </dd>
                </dl>

                <hr class="my-3">

                <dl class="row mb-2">
                    <dt class="col-md-2 text-md-end text-start mb-2 mb-md-0">سال:</dt>
                    <dd class="col-md-4 text-start mb-2 mb-md-0">
                        {{ $file->year }}
                    </dd>

                    <dt class="col-md-2 text-md-end text-start mb-2 mb-md-0">ماه:</dt>
                    <dd class="col-md-4 text-start mb-2 mb-md-0">
                        {{ $file->month }}
                    </dd>
                </dl>

                <hr class="my-3">

                <dl class="row mb-0">
                    <dt class="col-md-2 text-md-end text-start mb-2 mb-md-0">تاریخ ایجاد:</dt>
                    <dd class="col-md-4 text-start mb-2 mb-md-0">
                        {{ Jalalian::fromDateTime($file->created_at)->format('Y/m/d H:i') }}
                    </dd>

                    <dt class="col-md-2 text-md-end text-start mb-2 mb-md-0">تعداد رکوردها:</dt>
                    <dd class="col-md-4 text-start mb-2 mb-md-0">
                        {{ $items->count() }}
                    </dd>
                </dl>

            </div>
        </div>


    @if($items->isEmpty())
            <div class="alert alert-info">
                هیچ رکوردی برای این فایل ثبت نشده است.
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
                                <th>کد ملی</th>
                                <th>کد پرسنلی</th>
                                <th>مبلغ (ریال)</th>
                                <th>تاریخ ثبت</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($items as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->national_code }}</td>
                                    <td>{{ $item->personnel_code ?? '-' }}</td>
                                    <td>{{ number_format($item->amount) }}</td>
                                    <td>{{ Jalalian::fromDateTime($item->created_at)->format('Y/m/d') }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <div class="mt-3">
            <a href="{{ route('deductions.files.index') }}" class="btn btn-secondary">بازگشت به لیست فایل‌ها</a>
        </div>
    </div>
@endsection
