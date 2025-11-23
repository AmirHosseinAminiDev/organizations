@extends('layout.app')

@php
    use Morilog\Jalali\Jalalian;
@endphp

@section('content')
    <div class="container mt-5">
        <h2 class="mb-4">لیست فایل‌های کسورات</h2>

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

        <div class="mb-3">
            <a href="{{ route('deductions.files.create') }}" class="btn btn-primary">
                آپلود فایل کسورات جدید
            </a>
        </div>

        @if($files->isEmpty())
            <div class="alert alert-info">
                هنوز هیچ فایل کسوراتی ثبت نشده است.
            </div>
        @else
            <div class="card">
                <div class="card-header">
                    فایل‌های کسورات
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0 text-center">
                            <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>سازمان</th>
                                <th>عنوان</th>
                                <th>سال</th>
                                <th>ماه</th>
                                <th>نام فایل</th>
                                <th>تاریخ ایجاد</th>
                                <th>تعداد رکوردها</th>
                                <th>عملیات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($files as $index => $file)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $file->organization->name ?? '-' }}</td>
                                    <td>{{ $file->title ?? '-' }}</td>
                                    <td>{{ $file->year }}</td>
                                    <td>{{ $file->month }}</td>
                                    <td>{{ $file->original_name }}</td>
                                    <td>{{ Jalalian::fromDateTime($file->created_at)->format('Y/m/d H:i') }}</td>
                                    <td>{{ $file->items_count }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('deductions.files.show', $file) }}" class="btn btn-sm btn-info">
                                                مشاهده جزئیات
                                            </a>

                                            <a href="{{ route('deductions.files.export', $file) }}" class="btn btn-sm btn-success">
                                                خروجی اکسل
                                            </a>
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
