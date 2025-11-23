<nav class="sidebar">
    <div class="sidebar-header">
        <a href="#" class="sidebar-brand">
            Noble<span>UI</span>
        </a>
        <div class="sidebar-toggler">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>

    <div class="sidebar-body">
        @php
            $onUsers      = request()->routeIs('users.*');
            $onRequests   = request()->routeIs('requests.*');
            $onDeductions = request()->is('deductions*');
        @endphp

        <ul class="nav" id="sidebarNav">

            <li class="nav-item nav-category">اصلی</li>

            <li class="nav-item">
                <a href="{{ route('dashboard') }}"
                   class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="link-icon" data-feather="box"></i>
                    <span class="link-title">داشبورد</span>
                </a>
            </li>

            <li class="nav-item nav-category">کاربران</li>

            <li class="nav-item">
                <a class="nav-link {{ $onUsers ? 'active' : 'collapsed' }}"
                   data-bs-toggle="collapse"
                   href="#usersMenu"
                   role="button"
                   aria-expanded="{{ $onUsers ? 'true' : 'false' }}"
                   aria-controls="usersMenu">
                    <i class="link-icon" data-feather="users"></i>
                    <span class="link-title">کاربران</span>
                    <svg xmlns="http://www.w3.org/2000/svg"
                         width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round"
                         stroke-linejoin="round" class="feather feather-chevron-down link-arrow">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </a>
                <div class="collapse {{ $onUsers ? 'show' : '' }}" data-bs-parent="#sidebarNav" id="usersMenu">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}"
                               class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}">
                                لیست کاربران
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('users.create') }}"
                               class="nav-link {{ request()->routeIs('users.create') ? 'active' : '' }}">
                                افزودن کاربر جدید
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item nav-category">درخواست‌ها</li>

            <li class="nav-item">
                <a class="nav-link {{ $onRequests ? 'active' : 'collapsed' }}"
                   data-bs-toggle="collapse"
                   href="#requestsMenu"
                   role="button"
                   aria-expanded="{{ $onRequests ? 'true' : 'false' }}"
                   aria-controls="requestsMenu">
                    <i class="link-icon" data-feather="file-text"></i>
                    <span class="link-title">درخواست‌ها</span>
                    <svg xmlns="http://www.w3.org/2000/svg"
                         width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round"
                         stroke-linejoin="round" class="feather feather-chevron-down link-arrow">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </a>

                <div class="collapse {{ $onRequests ? 'show' : '' }}" data-bs-parent="#sidebarNav" id="requestsMenu">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a href="{{ route('requests.charge-account') }}"
                               class="nav-link {{ request()->routeIs('requests.charge-account') ? 'active' : '' }}">
                                درخواست شارژ حساب
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('requests.reissue-card-requests') }}"
                               class="nav-link {{ request()->routeIs('requests.reissue-card-requests') ? 'active' : '' }}">
                                درخواست صدور مجدد کارت
                            </a>
                        </li>
                    </ul>
                </div>
            </li>


            @php
                use App\Enum\UserRoleEnum;
            @endphp

            @if(auth()->user()->role->name === UserRoleEnum::OPERATOR->value || auth()->user()->role->name === UserRoleEnum::ORGANIZATION_ADMIN->value)
                <li class="nav-item nav-category">کسورات</li>
                <li class="nav-item">
                    <a class="nav-link {{ $onDeductions ? 'active' : 'collapsed' }}"
                       data-bs-toggle="collapse"
                       href="#deductionsMenu"
                       role="button"
                       aria-expanded="{{ $onDeductions ? 'true' : 'false' }}"
                       aria-controls="deductionsMenu">
                        <i class="link-icon" data-feather="file"></i>
                        <span class="link-title">کسورات</span>
                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round"
                             stroke-linejoin="round" class="feather feather-chevron-down link-arrow">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </a>

                    <div class="collapse {{ $onDeductions ? 'show' : '' }}" data-bs-parent="#sidebarNav" id="deductionsMenu">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('deductions.files.index') }}" class="nav-link">لیست فایل‌ها</a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif


        </ul>
    </div>
</nav>
