<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ auth()->user()->role === 'staff' ? route('staff.dashboard') : route('admin.dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <svg width="25" viewBox="0 0 25 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <defs>
                        <path d="M13.7918663,0.358365126 L3.39788168,7.44174259 C0.566865006,9.69408886 -0.379795268,12.4788597 0.557900856,15.7960551 C0.68998853,16.2305145 1.09562888,17.7872135 3.12357076,19.2293357 C3.8146334,19.7207684 5.32369333,20.3834223 7.65075054,21.2172976 L7.59773219,21.2525164 L2.63468769,24.5493413 C0.445452254,26.3002124 0.0884951797,28.5083815 1.56381646,31.1738486 C2.83770406,32.8170431 5.20850219,33.2640127 7.09180128,32.5391577 C8.347334,32.0559211 11.4559176,30.0011079 16.4175519,26.3747182 C18.0338572,24.4997857 18.6973423,22.4544883 18.4080071,20.2388261 C17.963753,17.5346866 16.1776345,15.5799961 13.0496516,14.3747546 L10.9194936,13.4715819 L18.6192054,7.984237 L13.7918663,0.358365126 Z" id="path-1"></path>
                        <path d="M5.47320593,6.00457225 C4.05321814,8.216144 4.36334763,10.0722806 6.40359441,11.5729822 C8.61520715,12.571656 10.0999176,13.2171421 10.8577257,13.5094407 L15.5088241,14.433041 L18.6192054,7.984237 C15.5364148,3.11535317 13.9273018,0.573395879 13.7918663,0.358365126 C13.5790555,0.511491653 10.8061687,2.3935607 5.47320593,6.00457225 Z" id="path-3"></path>
                        <path d="M7.50063644,21.2294429 L12.3234468,23.3159332 C14.1688022,24.7579751 14.397098,26.4880487 13.008334,28.506154 C11.6195701,30.5242593 10.3099883,31.790241 9.07958868,32.3040991 C5.78142938,33.4346997 4.13234973,34 4.13234973,34 C4.13234973,34 2.75489982,33.0538207 2.37032616e-14,31.1614621 C-0.55822714,27.8186216 -0.55822714,26.0572515 -4.05231404e-15,25.8773518 C0.83734071,25.6075023 2.77988457,22.8248993 3.3049379,22.52991 C3.65497346,22.3332504 5.05353963,21.8997614 7.50063644,21.2294429 Z" id="path-4"></path>
                        <path d="M20.6,7.13333333 L25.6,13.8 C26.2627417,14.6836556 26.0836556,15.9372583 25.2,16.6 C24.8538077,16.8596443 24.4327404,17 24,17 L14,17 C12.8954305,17 12,16.1045695 12,15 C12,14.5672596 12.1403557,14.1461923 12.4,13.8 L17.4,7.13333333 C18.0627417,6.24967773 19.3163444,6.07059163 20.2,6.73333333 C20.3516113,6.84704183 20.4862915,6.981722 20.6,7.13333333 Z" id="path-5"></path>
                    </defs>
                    <g id="g-app-brand" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <g id="Brand-Logo" transform="translate(-27.000000, -15.000000)">
                            <g id="Icon" transform="translate(27.000000, 15.000000)">
                                <g id="Mask" transform="translate(0.000000, 8.000000)">
                                    <mask id="mask-2" fill="white"><use xlink:href="#path-1"></use></mask>
                                    <use fill="#696cff" xlink:href="#path-1"></use>
                                    <g id="Path-3" mask="url(#mask-2)">
                                        <use fill="#696cff" xlink:href="#path-3"></use>
                                        <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-3"></use>
                                    </g>
                                    <g id="Path-4" mask="url(#mask-2)">
                                        <use fill="#696cff" xlink:href="#path-4"></use>
                                        <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-4"></use>
                                    </g>
                                </g>
                                <g id="Triangle" transform="translate(19.000000, 11.000000) rotate(-300.000000) translate(-19.000000, -11.000000)">
                                    <use fill="#696cff" xlink:href="#path-5"></use>
                                    <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-5"></use>
                                </g>
                            </g>
                        </g>
                    </g>
                </svg>
            </span>
            <span class="app-brand-text demo menu-text fw-bolder ms-2">Commitment Note</span>
        </a>
    </div>

    {{-- User Info Card --}}
    <div class="cn-user-card mx-3 my-2">
        <div class="cn-user-avatar">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <div class="cn-user-info">
            <div class="cn-user-name">{{ auth()->user()->name }}</div>
            <div class="cn-user-role">
                <span class="cn-role-badge">{{ ucfirst(auth()->user()->role) }}</span>
            </div>
        </div>
        <div class="cn-user-status"></div>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        {{-- Dashboard --}}
        <li class="menu-item {{ (Request::routeIs('admin.dashboard') || Request::routeIs('staff.dashboard')) ? 'active' : '' }}">
            <a href="{{ auth()->user()->role === 'staff' ? route('staff.dashboard') : route('admin.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Dashboard">Dashboard</div>
            </a>
        </li>

        {{-- My Tasks (staff only) --}}
        @if(auth()->user()->role === 'staff')
        <li class="menu-item {{ Request::routeIs('staff.my-tasks*') ? 'active' : '' }}">
            <a href="{{ route('staff.my-tasks') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-task"></i>
                <div data-i18n="My Tasks">My Tasks</div>
            </a>
        </li>
        @endif

        @if(in_array(auth()->user()->role, ['admin', 'staff']))

            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">
                    <i class="bx bx-capsule me-1" style="font-size:0.7rem;"></i>Medicine Management
                </span>
            </li>

            <li class="menu-item {{ Request::routeIs('admin.products.import.form') ? 'active' : '' }}">
                <a href="{{ route('admin.products.import.form') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-import"></i>
                    <div data-i18n="Medicines">Medicines</div>
                </a>
            </li>

            <li class="menu-item {{ Request::routeIs('admin.products*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-package"></i>
                    <div data-i18n="Products">Products</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ Request::routeIs('admin.products.index') ? 'active' : '' }}">
                        <a href="{{ route('admin.products.index') }}" class="menu-link">
                            <div data-i18n="All Products">All Products</div>
                        </a>
                    </li>
                    <li class="menu-item {{ Request::routeIs('admin.products.create') ? 'active' : '' }}">
                        <a href="{{ route('admin.products.create') }}" class="menu-link">
                            <div data-i18n="Add Product">Add Product</div>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">
                    <i class="bx bx-store me-1" style="font-size:0.7rem;"></i>Supplier Management
                </span>
            </li>

            <li class="menu-item {{ Request::routeIs('admin.suppliers*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-truck"></i>
                    <div data-i18n="Suppliers">Suppliers</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ Request::routeIs('admin.suppliers.index') ? 'active' : '' }}">
                        <a href="{{ route('admin.suppliers.index') }}" class="menu-link">
                            <div data-i18n="All Suppliers">All Suppliers</div>
                        </a>
                    </li>
                    <li class="menu-item {{ Request::routeIs('admin.suppliers.create') ? 'active' : '' }}">
                        <a href="{{ route('admin.suppliers.create') }}" class="menu-link">
                            <div data-i18n="Add Supplier">Add Supplier</div>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">
                    <i class="bx bx-cog me-1" style="font-size:0.7rem;"></i>Operations
                </span>
            </li>

            <li class="menu-item {{ Request::routeIs('admin.commitment-notes*') || Request::routeIs('admin.reports*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-note"></i>
                    <div data-i18n="Commitment Notes">Commitment Notes</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ Request::routeIs('admin.commitment-notes.index') ? 'active' : '' }}">
                        <a href="{{ route('admin.commitment-notes.index') }}" class="menu-link">
                            <div data-i18n="All Notes">All Commitment</div>
                        </a>
                    </li>
                    <li class="menu-item {{ Request::routeIs('admin.commitment-notes.create') ? 'active' : '' }}">
                        <a href="{{ route('admin.commitment-notes.create') }}" class="menu-link">
                            <div data-i18n="Add Note">Add Commitment</div>
                        </a>
                    </li>
                    <li class="menu-item {{ Request::routeIs('admin.reports*') ? 'active open' : '' }}">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
                            <div data-i18n="Reports">Reports</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item {{ Request::routeIs('admin.reports.cs-sales') ? 'active' : '' }}">
                                <a href="{{ route('admin.reports.cs-sales') }}" class="menu-link">
                                    <div data-i18n="CS Sales Report">CS Sales Report</div>
                                </a>
                            </li>
                            <li class="menu-item {{ Request::routeIs('admin.reports.cs-return') ? 'active' : '' }}">
                                <a href="{{ route('admin.reports.cs-return') }}" class="menu-link">
                                    <div data-i18n="CS Return Report">CS Return Report</div>
                                </a>
                            </li>
                            <li class="menu-item {{ Request::routeIs('admin.reports.cs-nil-stock') ? 'active' : '' }}">
                                <a href="{{ route('admin.reports.cs-nil-stock') }}" class="menu-link">
                                    <div data-i18n="CS Nil-Stock Report">CS Nil-Stock Report</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>

            <li class="menu-item {{ Request::routeIs('admin.column-settings*') ? 'active' : '' }}">
                <a href="{{ route('admin.column-settings.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-slider-alt"></i>
                    <div data-i18n="Column Settings">Column Settings</div>
                </a>
            </li>

        @endif

        @if(auth()->user()->role === 'admin')

            <li class="menu-item {{ Request::routeIs('admin.users*') ? 'active' : '' }}">
                <a href="{{ route('admin.users.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-shield-quarter"></i>
                    <div data-i18n="Users">Users</div>
                </a>
            </li>

            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">
                    <i class="bx bx-group me-1" style="font-size:0.7rem;"></i>Staff Management
                </span>
            </li>

            <li class="menu-item {{ Request::routeIs('admin.staffs*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-id-card"></i>
                    <div data-i18n="Staff">Staff</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ Request::routeIs('admin.staffs.index') ? 'active' : '' }}">
                        <a href="{{ route('admin.staffs.index') }}" class="menu-link">
                            <div data-i18n="All Staff">All Staff</div>
                        </a>
                    </li>
                    <li class="menu-item {{ Request::routeIs('admin.staffs.create') ? 'active' : '' }}">
                        <a href="{{ route('admin.staffs.create') }}" class="menu-link">
                            <div data-i18n="Add Staff">Add Staff</div>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="menu-item {{ Request::routeIs('admin.tasks*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-task"></i>
                    <div data-i18n="Tasks">Tasks</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ Request::routeIs('admin.tasks.index') ? 'active' : '' }}">
                        <a href="{{ route('admin.tasks.index') }}" class="menu-link">
                            <div data-i18n="All Tasks">All Tasks</div>
                        </a>
                    </li>
                    <li class="menu-item {{ Request::routeIs('admin.tasks.create') ? 'active' : '' }}">
                        <a href="{{ route('admin.tasks.create') }}" class="menu-link">
                            <div data-i18n="Add Task">Add Task</div>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="menu-item {{ Request::routeIs('admin.task-assignments*') ? 'active' : '' }}">
                <a href="{{ route('admin.task-assignments.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-transfer"></i>
                    <div data-i18n="Task Assignment">Task Assignment</div>
                </a>
            </li>

            <li class="menu-item {{ Request::routeIs('admin.staff-tasks*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-check-square"></i>
                    <div data-i18n="Staff Tasks">Staff Tasks</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ Request::routeIs('admin.staff-tasks.index') ? 'active' : '' }}">
                        <a href="{{ route('admin.staff-tasks.index') }}" class="menu-link">
                            <div data-i18n="All Assignments">All Assignments</div>
                        </a>
                    </li>
                </ul>
            </li>

        @endif

        {{-- Divider before logout --}}
        <li class="cn-logout-divider"></li>

        {{-- Logout --}}
        <li class="menu-item cn-logout-item">
            <a href="{{ route('logout') }}" class="menu-link cn-logout-link"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="menu-icon tf-icons bx bx-power-off"></i>
                <div data-i18n="Logout">Logout</div>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>

    </ul>
</aside>

<style>
/* =============================================
   COMMITMENT NOTE — POLISHED SIDEBAR STYLES
   ============================================= */

/* Brand text */
.app-brand-text.demo {
    font-size: 1.15rem !important;
    letter-spacing: -0.3px;
    font-weight: 800 !important;
    background: linear-gradient(135deg, #696cff 0%, #9b59f5 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* App brand area - subtle bottom border */
.app-brand.demo {
    border-bottom: 1px solid rgba(105, 108, 255, 0.1);
    padding-bottom: 0.75rem !important;
    margin-bottom: 0 !important;
}

/* ── User Info Card ─────────────────────────── */
.cn-user-card {
    display: flex;
    align-items: center;
    gap: 10px;
    background: linear-gradient(135deg, rgba(105,108,255,0.08) 0%, rgba(155,89,245,0.06) 100%);
    border: 1px solid rgba(105, 108, 255, 0.15);
    border-radius: 12px;
    padding: 10px 12px;
    margin-top: 0.5rem !important;
    position: relative;
    overflow: hidden;
}

.cn-user-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0;
    width: 3px; height: 100%;
    background: linear-gradient(180deg, #696cff, #9b59f5);
    border-radius: 12px 0 0 12px;
}

.cn-user-avatar {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: linear-gradient(135deg, #696cff, #9b59f5);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.95rem;
    flex-shrink: 0;
    box-shadow: 0 3px 8px rgba(105,108,255,0.35);
}

.cn-user-name {
    font-size: 0.82rem;
    font-weight: 600;
    color: #384551;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 120px;
}

.cn-role-badge {
    display: inline-block;
    font-size: 0.65rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #696cff;
    background: rgba(105, 108, 255, 0.12);
    border-radius: 4px;
    padding: 1px 6px;
}

.cn-user-status {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #28c76f;
    box-shadow: 0 0 0 2px rgba(40, 199, 111, 0.25);
    margin-left: auto;
    flex-shrink: 0;
    animation: cn-pulse 2s infinite;
}

@keyframes cn-pulse {
    0%, 100% { box-shadow: 0 0 0 2px rgba(40,199,111,0.25); }
    50%       { box-shadow: 0 0 0 5px rgba(40,199,111,0.1); }
}

/* ── Section Headers ────────────────────────── */
.menu-header {
    margin-top: 0.4rem !important;
    padding-top: 0.5rem !important;
}

.menu-header-text {
    font-size: 0.65rem !important;
    font-weight: 700 !important;
    letter-spacing: 0.7px !important;
    color: #a5afc0 !important;
    display: flex;
    align-items: center;
    gap: 3px;
}

/* ── Menu Links ─────────────────────────────── */
.menu-inner > .menu-item > .menu-link {
    border-radius: 8px !important;
    margin: 1px 8px !important;
    padding-left: 14px !important;
    transition: background 0.18s ease, color 0.18s ease, transform 0.15s ease !important;
    position: relative;
}

.menu-inner > .menu-item > .menu-link:hover {
    background: rgba(105, 108, 255, 0.07) !important;
    transform: translateX(2px);
}

/* Active item accent bar */
.menu-item.active > .menu-link::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 3px;
    height: 60%;
    background: linear-gradient(180deg, #696cff, #9b59f5);
    border-radius: 0 3px 3px 0;
}

/* Icon refinement */
.menu-icon {
    font-size: 1.1rem !important;
    opacity: 0.85;
    transition: opacity 0.15s, transform 0.15s;
}

.menu-link:hover .menu-icon,
.menu-item.active .menu-icon {
    opacity: 1 !important;
    transform: scale(1.08);
}

/* Menu text size */
.menu-link div[data-i18n] {
    font-size: 0.84rem !important;
    font-weight: 500;
    letter-spacing: 0.1px;
}

/* Sub-menu dots - style upgrade */
.menu-sub .menu-item .menu-link::before {
    content: '';
    width: 5px !important;
    height: 5px !important;
    border-radius: 50% !important;
    background: rgba(105, 108, 255, 0.4) !important;
    border: none !important;
    transition: background 0.15s, transform 0.15s;
    flex-shrink: 0;
    margin-right: 4px;
}

.menu-sub .menu-item.active .menu-link::before,
.menu-sub .menu-item .menu-link:hover::before {
    background: #696cff !important;
    transform: scale(1.4);
}

/* ── Logout Divider ─────────────────────────── */
.cn-logout-divider {
    list-style: none;
    border-top: 1px solid rgba(105, 108, 255, 0.1);
    margin: 8px 16px !important;
}

/* Logout link */
.cn-logout-link {
    border-radius: 8px !important;
    margin: 1px 8px !important;
    padding-left: 14px !important;
    transition: background 0.18s ease, color 0.18s ease !important;
}

.cn-logout-link:hover {
    background: rgba(255, 75, 75, 0.08) !important;
    color: #ff4b4b !important;
}

.cn-logout-link:hover .menu-icon {
    color: #ff4b4b !important;
}

/* ── Scrollbar ──────────────────────────────── */
.layout-menu::-webkit-scrollbar {
    width: 3px;
}
.layout-menu::-webkit-scrollbar-thumb {
    background: rgba(105, 108, 255, 0.25);
    border-radius: 3px;
}
.layout-menu::-webkit-scrollbar-track {
    background: transparent;
}

/* Dark mode support */
[data-theme="dark"] .cn-user-name,
.dark-style .cn-user-name {
    color: #cfd3ec;
}
[data-theme="dark"] .cn-user-card,
.dark-style .cn-user-card {
    background: rgba(105,108,255,0.1);
    border-color: rgba(105,108,255,0.2);
}
</style>
