<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar">
  <form class="form-inline mr-auto">
    <ul class="navbar-nav mr-3">
      <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
      <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li>
    </ul>
  </form>
  <ul class="navbar-nav navbar-right">
    <!-- notifications with its icon -->
    @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 3)
    <li class="dropdown dropdown-list-toggle"><a href="#" data-toggle="dropdown" class="nav-link nav-link-lg message-toggle beep"><i class="far fa-bell"></i></a>
      <div class="dropdown-menu dropdown-list dropdown-menu-right">
        <div class="dropdown-header">Notifications
          <div class="float-right">
            <a href="{{ route('admin.notifications.mark_all_as_read') }}">Mark All as Read</a>
          </div>
        </div>
        <div class="dropdown-list-content dropdown-list-icons">
          <!--only take 5 notifications-->
          @foreach (Auth::user()->unreadNotifications->take(5) as $notification)
          <a href="#" class="dropdown-item dropdown-item-unread">
            <div class="dropdown-item-icon bg-primary text-white">
              <i class="fas fa-code"></i>
            </div>
            <div class="dropdown-item-desc">
              {{ $notification->data['customer_name'] }} has made a payment of {{ $notification->data['amount'] }} on loan {{ $notification->data['loan_id'] }} at {{ $notification->data['transaction_date'] }}. The transaction reference is {{ $notification->data['transaction_reference'] }}. The remaining balance is {{ $notification->data['balance'] }}
              <div class="time text-primary">{{ $notification->created_at->diffForHumans() }}</div>
            </div>
          </a>
          @endforeach
        </div>
        <div class="dropdown-footer text-center">
          <a href="{{ route('admin.notifications.index') }}">View All <i class="fas fa-chevron-right"></i></a>
        </div>
      </div>
    </li>
    @endif
    <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
      @if(Auth::user()->avatar)
      <img alt="image" src="{{ asset('assets/images/profile/'. Auth::user()->avatar) }}" class="rounded-circle mr-1">
      @else
      <img alt="image" src="https://ui-avatars.com/api/?size=30&background=random&name={{ Auth::User()->name }}" class="rounded-circle mr-1">
      @endif
      <div class="d-sm-none d-lg-inline-block">{{ __('Hi') }}, {{ Auth::user()->first_name }}</div></a>
      <div class="dropdown-menu dropdown-menu-right">
        <a href="{{ route('profile') }}" class="dropdown-item has-icon">
           
          <i class="far fa-user"></i> {{ __('Profile') }}
        </a>
        <div class="dropdown-divider"></div>
        <a href="{{ Auth::user()->role_id == 1 ? route('admin.logout') : route('logout') }}" class="dropdown-item has-icon text-danger">
          <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
        </a>
      </div>
    </li>
  </ul>
</nav>

