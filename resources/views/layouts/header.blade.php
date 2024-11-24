<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{route('dashboard')}}" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{route('contact.index')}}" class="nav-link">Contact</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <!-- Using PHP strtoupper function -->
        <span class="nav-link border border-primary text-primary rounded px-3 py-2">
            {{ strtoupper(auth()->user()->level) }}
        </span>
        <!-- Using CSS text-transform property -->
        <!-- <span class="nav-link border border-primary text-primary rounded px-3 py-2 text-uppercase">
            {{ auth()->user()->level }}
        </span> -->
    </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li>

      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">15 Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> 4 new messages
            <span class="float-right text-muted text-sm">3 mins</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> 8 friend requests
            <span class="float-right text-muted text-sm">12 hours</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-file mr-2"></i> 3 new reports
            <span class="float-right text-muted text-sm">2 days</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>

      @if(auth()->user()->level == "dosen")
        <li class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" id="profileDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Edit Profil">
                <i class="fas fa-user"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right animate__animated animate__fadeIn" aria-labelledby="profileDropdown">
                <a href="{{ route('profil.index') }}" class="dropdown-item">
                    <i class="fas fa-edit mr-2"></i> Edit Profil
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ url('dosenPIC/kegiatan') }}" class="dropdown-item">
                    <i class="fas fa-user-tie mr-2"></i> Masuk sebagai PIC
                </a>
                <a href="{{ url('dosenAnggota/kegiatan') }}" class="dropdown-item">
                    <i class="fas fa-users mr-2"></i> Masuk sebagai Anggota
                </a>
                @if($activeMenu == "kegiatan pic" || $activeMenu == "kegiatan anggota" || $activeMenu == "statistik pic")
                <div class="dropdown-divider"></div>
                <a href="{{ url('dosen/kegiatan') }}" class="dropdown-item">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dosen
                </a>
                @endif
            </div>
        </li>
      @endif

      @if(auth()->user()->level == "admin" || auth()->user()->level == "pimpinan")
      <!-- Profile Menu -->
      <li class="nav-item">
        <a href="{{ route('profil.index') }}" class="nav-link" title="Edit Profil">
          <i class="fas fa-user"></i>
        </a>
      </li>


      @endif

    <!-- Logout Menu -->
      <li class="nav-item">
        <form action="{{ url('logout') }}" method="GET" role="button">
          @csrf
          <button type="submit" class="btn btn-danger">
            <i class="fas fa-sign-out-alt"></i>
          </button>
        </form>
      </li>
    </ul>
  </nav>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
  