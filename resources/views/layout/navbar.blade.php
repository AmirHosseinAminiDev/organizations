<nav class="navbar">
    <div class="navbar-content">

        <div class="logo-mini-wrapper">
            <img src="{{ asset('assets/images/logo-mini-light.png') }}" class="logo-mini logo-mini-light" alt="logo">
            <img src="{{ asset('assets/images/logo-mini-dark.png') }}" class="logo-mini logo-mini-dark" alt="logo">
        </div>

        <form class="search-form">
            <div class="input-group">
                <div class="input-group-text">
                    <i data-feather="search"></i>
                </div>
                <input type="text" class="form-control" id="navbarForm" placeholder="جستجو ...">
            </div>
        </form>

        <ul class="navbar-nav">
            <li class="theme-switcher-wrapper nav-item">
                <input type="checkbox" value="" id="theme-switcher">
                <label for="theme-switcher">
                    <div class="box">
                        <div class="ball"></div>
                        <div class="icons">
                            <i class="feather icon-sun"></i>
                            <i class="feather icon-moon"></i>
                        </div>
                    </div>
                </label>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex" href="#" id="languageDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="{{ asset('assets/images/flags/ir.svg') }}" class="w-20px" title="us" alt="flag">
                    <span class="ms-2 d-none d-md-inline-block">فارسی</span>
                </a>
                <div class="dropdown-menu" aria-labelledby="languageDropdown">
                    <a href="javascript:;" class="dropdown-item py-2 d-flex"><img src="{{ asset('assets/images/flags/ir.svg') }}" class="w-20px" title="us" alt="us"> <span class="ms-2"> فارسی </span></a>
                    <a href="javascript:;" class="dropdown-item py-2 d-flex"><img src="{{ asset('assets/images/flags/fr.svg') }}" class="w-20px" title="fr" alt="fr"> <span class="ms-2"> انگلیسی </span></a>
                    <a href="javascript:;" class="dropdown-item py-2 d-flex"><img src="{{ asset('assets/images/flags/de.svg') }}" class="w-20px" title="de" alt="de"> <span class="ms-2"> آلمانی </span></a>
                    <a href="javascript:;" class="dropdown-item py-2 d-flex"><img src="{{ asset('assets/images/flags/pt.svg') }}" class="w-20px" title="pt" alt="pt"> <span class="ms-2"> ترکی استانبولی  </span></a>
                    <a href="javascript:;" class="dropdown-item py-2 d-flex"><img src="{{ asset('assets/images/flags/es.svg') }}" class="w-20px" title="es" alt="es"> <span class="ms-2"> اسپانیایی </span></a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="appsDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i data-feather="grid"></i>
                </a>
                <div class="dropdown-menu p-0" aria-labelledby="appsDropdown">
                    <div class="px-3 py-2 d-flex align-items-center justify-content-between border-bottom">
                        <p class="mb-0 fw-bold">برنامه های وب</p>
                        <a href="javascript:;" class="text-secondary">ویرایش</a>
                    </div>
                    <div class="row g-0 p-1">
                        <div class="col-3 text-center">
                            <a href="../apps/chat.html" class="dropdown-item d-flex flex-column align-items-center justify-content-center w-70px h-70px"><i data-feather="message-square" class="icon-lg mb-1"></i><p class="fs-12px">گفتگو</p></a>
                        </div>
                        <div class="col-3 text-center">
                            <a href="../apps/calendar-jalali.html" class="dropdown-item d-flex flex-column align-items-center justify-content-center w-70px h-70px"><i data-feather="calendar" class="icon-lg mb-1"></i><p class="fs-12px">تقویم رویداد</p></a>
                        </div>
                        <div class="col-3 text-center">
                            <a href="../email/inbox.html" class="dropdown-item d-flex flex-column align-items-center justify-content-center w-70px h-70px"><i data-feather="mail" class="icon-lg mb-1"></i><p class="fs-12px">ایمیل</p></a>
                        </div>
                        <div class="col-3 text-center">
                            <a href="profile.html" class="dropdown-item d-flex flex-column align-items-center justify-content-center w-70px h-70px"><i data-feather="instagram" class="icon-lg mb-1"></i><p class="fs-12px">پروفایل</p></a>
                        </div>
                    </div>
                    <div class="px-3 py-2 d-flex align-items-center justify-content-center border-top">
                        <a href="javascript:;">مشاهده همه</a>
                    </div>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="messageDropdown" role="button" data-bs-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">
                    <i data-feather="mail"></i>
                </a>
                <div class="dropdown-menu p-0" aria-labelledby="messageDropdown">
                    <div class="px-3 py-2 d-flex align-items-center justify-content-between border-bottom">
                        <p>9 پیغام جدید</p>
                        <a href="javascript:;" class="text-muted">پاک کردن همه</a>
                    </div>
                    <div class="p-1">
                        <a href="javascript:;" class="dropdown-item d-flex align-items-center py-2">
                            <div class="me-3">
                                <img class="w-30px h-30px ms-1 rounded-circle" src="../../../assets/images/faces/face2.jpg" alt="userr">
                            </div>
                            <div class="d-flex justify-content-between flex-grow-1">
                                <div class="me-4">
                                    <p>رضا افشار</p>
                                    <p class="tx-12 text-muted">وضعیت پروژه</p>
                                </div>
                                <p class="tx-12 text-muted">2 دقیقه پیش</p>
                            </div>
                        </a>
                        <a href="javascript:;" class="dropdown-item d-flex align-items-center py-2">
                            <div class="me-3">
                                <img class="w-30px h-30px ms-1 rounded-circle" src="../../../assets/images/faces/face3.jpg" alt="userr">
                            </div>
                            <div class="d-flex justify-content-between flex-grow-1">
                                <div class="me-4">
                                    <p>پریسا توکلی</p>
                                    <p class="tx-12 text-muted">جلسه با مشتری</p>
                                </div>
                                <p class="tx-12 text-muted">30 دقیقه پیش</p>
                            </div>
                        </a>
                        <a href="javascript:;" class="dropdown-item d-flex align-items-center py-2">
                            <div class="me-3">
                                <img class="w-30px h-30px ms-1 rounded-circle" src="../../../assets/images/faces/face4.jpg" alt="userr">
                            </div>
                            <div class="d-flex justify-content-between flex-grow-1">
                                <div class="me-4">
                                    <p>پدرام شریفی</p>
                                    <p class="tx-12 text-muted">به روز رسانی پروژه</p>
                                </div>
                                <p class="tx-12 text-muted">1 ساعت پیش</p>
                            </div>
                        </a>
                        <a href="javascript:;" class="dropdown-item d-flex align-items-center py-2">
                            <div class="me-3">
                                <img class="w-30px h-30px ms-1 rounded-circle" src="../../../assets/images/faces/face5.jpg" alt="userr">
                            </div>
                            <div class="d-flex justify-content-between flex-grow-1">
                                <div class="me-4">
                                    <p>{{ auth()->user()->name ?? '' }} {{ auth()->user()->last_name ?? '' }}</p>
                                    <p class="tx-12 text-muted">پایان پروژه</p>
                                </div>
                                <p class="tx-12 text-muted">2 ساعت پیش</p>
                            </div>
                        </a>
                        <a href="javascript:;" class="dropdown-item d-flex align-items-center py-2">
                            <div class="me-3">
                                <img class="w-30px h-30px ms-1 rounded-circle" src="../../../assets/images/faces/face6.jpg" alt="userr">
                            </div>
                            <div class="d-flex justify-content-between flex-grow-1">
                                <div class="me-4">
                                    <p>نرگس علیپور</p>
                                    <p class="tx-12 text-muted">رکورد جدید</p>
                                </div>
                                <p class="tx-12 text-muted">5 ساعت پیش</p>
                            </div>
                        </a>
                    </div>
                    <div class="px-3 py-2 d-flex align-items-center justify-content-center border-top">
                        <a href="javascript:;">مشاهده همه</a>
                    </div>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button"
                   data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i data-feather="bell"></i>
                    <div class="indicator">
                        <div class="circle"></div>
                    </div>
                </a>
                <div class="dropdown-menu p-0" aria-labelledby="notificationDropdown">
                    <div class="px-3 py-2 d-flex align-items-center justify-content-between border-bottom">
                        <p>6 اعلان جدید</p>
                        <a href="javascript:;" class="text-muted">پاک کردن همه</a>
                    </div>
                    <div class="p-1">
                        <a href="javascript:;" class="dropdown-item d-flex align-items-center py-2">
                            <div
                                class="w-30px h-30px d-flex align-items-center justify-content-center bg-primary rounded-circle me-3">
                                <i class="icon-sm text-white" data-feather="gift"></i>
                            </div>
                            <div class="flex-grow-1 me-2">
                                <p>سفارش جدید دریافت شد</p>
                                <p class="tx-12 text-muted">30 دقیقه پیش</p>
                            </div>
                        </a>
                        <a href="javascript:;" class="dropdown-item d-flex align-items-center py-2">
                            <div
                                class="w-30px h-30px d-flex align-items-center justify-content-center bg-primary rounded-circle me-3">
                                <i class="icon-sm text-white" data-feather="alert-circle"></i>
                            </div>
                            <div class="flex-grow-1 me-2">
                                <p>محدودیت سرور!</p>
                                <p class="tx-12 text-muted">1 ساعت پیش</p>
                            </div>
                        </a>
                        <a href="javascript:;" class="dropdown-item d-flex align-items-center py-2">
                            <div
                                class="w-30px h-30px d-flex align-items-center justify-content-center bg-primary rounded-circle me-3">
                                <img class="w-30px h-30px ms-1 rounded-circle" src="../../../assets/images/faces/face6.jpg" alt="userr">
                            </div>
                            <div class="flex-grow-1 me-2">
                                <p>کاربر جدید ثبت نام کرد</p>
                                <p class="tx-12 text-muted">2 ثانیه پیش</p>
                            </div>
                        </a>
                        <a href="javascript:;" class="dropdown-item d-flex align-items-center py-2">
                            <div
                                class="w-30px h-30px d-flex align-items-center justify-content-center bg-primary rounded-circle me-3">
                                <i class="icon-sm text-white" data-feather="layers"></i>
                            </div>
                            <div class="flex-grow-1 me-2">
                                <p>برنامه ها نیاز به به روز رسانی دارند</p>
                                <p class="tx-12 text-muted">5 ساعت پیش</p>
                            </div>
                        </a>
                        <a href="javascript:;" class="dropdown-item d-flex align-items-center py-2">
                            <div
                                class="w-30px h-30px d-flex align-items-center justify-content-center bg-primary rounded-circle me-3">
                                <i class="icon-sm text-white" data-feather="download"></i>
                            </div>
                            <div class="flex-grow-1 me-2">
                                <p>دانلود تکمیل شد</p>
                                <p class="tx-12 text-muted">6 ساعت پیش</p>
                            </div>
                        </a>
                    </div>
                    <div class="px-3 py-2 d-flex align-items-center justify-content-center border-top">
                        <a href="javascript:;">مشاهده همه</a>
                    </div>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="w-30px h-30px ms-1 rounded-circle" src="../../../assets/images/faces/face1.jpg" alt="profile">
                </a>
                <div class="dropdown-menu p-0" aria-labelledby="profileDropdown">
                    <div class="d-flex flex-column align-items-center border-bottom px-5 py-3">
                        <div class="mb-3">
                            <img class="w-80px h-80px rounded-circle" src="../../../assets/images/faces/face1.jpg" alt="">
                        </div>
                        <div class="text-center">
                            <p class="fs-16px fw-bolder">{{ auth()->user()->name ?? '' }} {{ auth()->user()->last_name ?? '' }}</p>
                            <p class="fs-12px text-secondary">{{ auth()->user()->role->name }}</p>
                        </div>
                    </div>
                    <ul class="list-unstyled p-1">
                        <li class="dropdown-item py-2">
                            <a href="profile.html" class="text-body ms-0">
                                <i class="me-2 icon-md" data-feather="user"></i>
                                <span>پروفایل</span>
                            </a>
                        </li>
                        <li class="dropdown-item py-2">
                            <a href="javascript:;" class="text-body ms-0">
                                <i class="me-2 icon-md" data-feather="edit"></i>
                                <span>ویرایش پروفایل</span>
                            </a>
                        </li>
                        <li class="dropdown-item py-2">
                            <a href="javascript:;" class="text-body ms-0">
                                <i class="me-2 icon-md" data-feather="repeat"></i>
                                <span>تغییر کاربر</span>
                            </a>
                        </li>
                        <li class="dropdown-item py-2">
                            <a href="javascript:;" class="text-body ms-0">
                                <i class="me-2 icon-md" data-feather="log-out"></i>
                                <span>خروج</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>

        <a href="#" class="sidebar-toggler">
            <i data-feather="menu"></i>
        </a>

    </div>
</nav>
