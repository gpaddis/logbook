<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <!-- Branding Image -->
  <a class="navbar-brand" href="{{ route('home') }}">
    {{ config('app.name') }}
  </a>

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav mr-auto">
      @auth
      @can('edit logbook')
      <li class="nav-item">
        <a class="nav-link" href="{{ route('livecounter.index') }}">Live Counter</a>
      </li>
      @endcan

      <li class="nav-item">
        <a class="nav-link" href="{{ route('logbook.index') }}">Browse</a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="{{ route('patron-categories.index') }}">Categories</a>
      </li>

      @can('manage users')
      <li class="nav-item">
        <a class="nav-link" href="{{ route('users.index') }}">Users</a>
      </li>
      @endcan
      @endauth
    </ul>

    <ul class="navbar-nav flex-row ml-md-auto d-none d-md-flex">
      @guest
      <!-- Authentication -->
      <li class="nav-item">
        <a class="nav-link" href="{{ route('login') }}">Login</a>
      </li>
      @endguest

      @auth
      <!-- User menu -->
      @hasrole('admin')
      <li class="nav-item">
        <a class="nav-link"><span class="badge badge-pill badge-danger">Admin</span></a>
      </li>
      @endhasrole

      @hasrole('guest')
      <li class="nav-item">
        <a class="nav-link"><span class="badge badge-pill badge-secondary">Guest</span></a>
      </li>
      @endhasrole

      <li class="nav-item dropdown">
        <a class="nav-item nav-link dropdown-toggle mr-md-2" href="#" id="user" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          {{ Auth::user()->first_name }} <span class="caret"></span>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="user">
          <a class="dropdown-item" href="{{ route('logout') }}"
          onclick="event.preventDefault();
          document.getElementById('logout-form').submit();">
          <i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
          </form>
        </div>
      </li>
      @endauth

    </ul>
  </div>
</nav>
