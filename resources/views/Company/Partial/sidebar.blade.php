<div id="sidebar">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo">
                    <a href="Admin">
                        <span class="fw-bold fs-4">
                            <span class="text-warning">Sakti</span><span class="text-secondary">Job</span>
                        </span>
                    </a>
                </div>
                <div class="theme-toggle d-flex gap-2 align-items-center mt-2">
                    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="20"
                        height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 21 21">
                        <g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path
                                d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2"
                                opacity=".3"></path>
                            <g transform="translate(-210 -1)">
                                <path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path>
                                <circle cx="220.5" cy="11.5" r="4"></circle>
                                <path d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2"></path>
                            </g>
                        </g>
                    </svg>
                    <div class="form-check form-switch fs-6">
                        <input class="form-check-input me-0" type="checkbox" id="toggle-dark" style="cursor: pointer">
                        <label class="form-check-label" for="toggle-dark"></label>
                    </div>
                </div>
                <div class="sidebar-toggler x">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>

        <div class="sidebar-menu">
            <ul class="menu">

                <li class="sidebar-title">Menu</li>

                <li class="sidebar-item">
                    <a href="{{ url('/dashboard-company') }}" class="sidebar-link">
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ url('/lengkapi-profile') }}" class="sidebar-link">
                        <i class="bi bi-building"></i>
                        <span>Profile Usaha</span>
                    </a>
                </li>

                @if (Auth::guard('company')->user()->status === 'verified')
                    <li class="sidebar-item">
                        <a href="{{ url('company-lowongan') }}" class="sidebar-link">
                            <i class="bi bi-briefcase-fill"></i>
                            <span>Lowongan</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{ url('company-applyjob') }}" class="sidebar-link">
                            <i class="bi bi-person-workspace"></i>
                            <span>Lamaran Masuk</span>
                        </a>
                    </li>
                @endif

                <li class="sidebar-item">
                    <form action="{{ url('logout') }}" method="POST" class="sidebar-link">
                        @csrf
                        <i class="bi bi-box-arrow-left me-3"></i>
                        <button type="submit" class="btn btn-danger"><span>Logout</span></button>
                    </form>
                </li>

            </ul>


        </div>

    </div>
</div>
