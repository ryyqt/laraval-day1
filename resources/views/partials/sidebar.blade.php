<aside class="sidebar" id="sidebar" aria-label="Sidebar navigation">

    <!-- Brand -->
    <div class="sidebar-brand">
        <div class="brand-logo" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
        </div>
        <div class="brand-text"><span>Nexus</span></div>
    </div>

    <!-- Navigation Body -->
    <nav class="sidebar-body" aria-label="Main navigation">

        <!-- Main -->
        <span class="nav-section-label">Main</span>

        <a id="nav-dashboard"
           class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
           href="{{ route('dashboard') }}"
           aria-current="{{ request()->routeIs('dashboard') ? 'page' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 8.25 20.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z"/>
            </svg>
            <span class="nav-label">Dashboard</span>
        </a>

        <!-- Management -->
        <span class="nav-section-label">Management</span>

        <a id="nav-categories"
           class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}"
           href="{{ route('categories.index') }}"
           aria-current="{{ request()->routeIs('categories.*') ? 'page' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z"/>
            </svg>
            <span class="nav-label">Categories</span>
        </a>

        <a id="nav-products"
           class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}"
           href="{{ route('products.index') }}"
           aria-current="{{ request()->routeIs('products.*') ? 'page' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375v11.25a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 17.625V6.375m17.25 0A2.25 2.25 0 0 0 18 4.125H6a2.25 2.25 0 0 0-2.25 2.25m17.25 0v.75A2.25 2.25 0 0 1 18 9.375H6a2.25 2.25 0 0 1-2.25-2.25v-.75"/>
            </svg>
            <span class="nav-label">Products</span>
        </a>

        <a id="nav-customers"
           class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}"
           href="{{ route('customers.index') }}"
           aria-current="{{ request()->routeIs('customers.*') ? 'page' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
            </svg>
            <span class="nav-label">Customers</span>
        </a>

        <!-- Day 9 -->
        <span class="nav-section-label">Day 9</span>

        <a id="nav-ajax-products"
           class="nav-link {{ request()->routeIs('ajax-products.*') ? 'active' : '' }}"
           href="{{ route('ajax-products.index') }}"
           aria-current="{{ request()->routeIs('ajax-products.*') ? 'page' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5"/>
            </svg>
            <span class="nav-label">AJAX Products</span>
            <span class="nav-badge">API</span>
        </a>


    </nav>

    <!-- Sidebar Footer -->
    <div class="sidebar-footer">
        <div class="sidebar-footer-link" id="logout-btn" tabindex="0" role="button" aria-label="Log out">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/>
            </svg>
            Log out
        </div>
    </div>

</aside>
