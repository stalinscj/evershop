<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">{{ config('app.name') }}</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSC"
            aria-controls="navbarSC" aria-expanded="false" aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSC">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a href="/" class="nav-link {{ Request::is('/') ? 'active' : '' }}">Home</a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('orders.create') }}"
                        class="nav-link {{ request()->routeIs('orders.create') ? 'active' : '' }}"
                    >Generate Order</a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('order.status') }}"
                        class="nav-link {{ request()->routeIs('order.status') ? 'active' : '' }}"
                    >Check Order status</a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('orders.index') }}"
                        class="nav-link {{ request()->routeIs('orders.index') ? 'active' : '' }}"
                    >Orders List</a>
                </li>

            </ul>
        </div>
    </div>
</nav>
