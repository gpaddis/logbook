<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <!-- Branding Image -->
  <a class="navbar-brand" href="{{ route('home') }}">
    {{ config('app.name') }}
  </a>

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="{{ route('livecounter.index') }}">Live Counter</a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="{{ route('logbook.index') }}">Browse</a>
      </li>

      <li class="nav-item dropdown">
        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" id="navbarDropdownMenuLink" aria-haspopup="true" aria-expanded="false">
          Admin<span class="caret"></span>
        </a>
        <div class="dropdown-menu">
          <a href="{{ route('patron-categories.index') }}" class="dropdown-item">Patron Categories</a>
          <a href="#" class="dropdown-item">App Settings</a>
        </div>
      </li>
    </ul>

    <ul class="navbar-nav flex-row ml-md-auto d-none d-md-flex">
      @if(Auth::guest())
      <!-- Authentication Links -->
      <li class="nav-item">
        <a class="nav-link" href="{{ route('login') }}">Login</a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="{{ route('register') }}">Register</a>
      </li>
      @else

      <!-- User menu -->
      <li class="nav-item dropdown">
        <a class="nav-item nav-link dropdown-toggle mr-md-2" href="#" id="user" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          {{ Auth::user()->first_name }} <span class="caret"></span>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="user">
          <a class="dropdown-item" href="{{ route('logout') }}"
          onclick="event.preventDefault();
          document.getElementById('logout-form').submit();">
          Logout</a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
          </form>
        </div>
      </li>
      @endif

    </ul>
  </div>
</nav>
