<aside class="sidebar">
    <h3>Navigation</h3>
    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
    <a class="nav-link" href="#">Reports</a>
    <a class="nav-link" href="#">Settings</a>
</aside>
