<div class="sidebar">
    <!--- SidebarSearch Form-->
    <div class="form-inline mt-2">
      <div class="input-group" data-widget="sidebar-search">
        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-sidebar">
            <i class="fas fa-search fa-fw"></i>
          </button>
        </div>
      </div>
    </div>
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
          <a href="{{ url('/')}}" class="nav-link {{($activeMenu == 'dashboard')? 'active' : ''}}">
            <i class="nav-icon fas fa-diagram-project"></i>
            <p>Dashboard</p>
          </a>
        </li>

        {{-- ADMIN --}}
        @if(auth()->user()->level == "admin")
        <li class="nav-header">Manage Pengguna</li>
          <li class="nav-item">
            <a href="{{ url('admin/user')}}" class="nav-link {{($activeMenu == 'user admin')? 'active' : ''}}">
              <i class="nav-icon fas fa-users"></i>
              <p>Daftar Pengguna</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('admin/jenispengguna')}}" class="nav-link {{($activeMenu == 'user jenis')? 'active' : ''}}">
              <i class="nav-icon fa-solid fa-user-gear"></i>
              <p>Jenis Pengguna</p>
            </a>
          </li>
          <li class="nav-header">Data Kegiatan</li>
          <li class="nav-item">
            <a href="{{ url('admin/kegiatan')}}" class="nav-link {{($activeMenu == 'kegiatan admin')? 'active' : ''}}">
              <i class="nav-icon fa-regular fa-calendar-check"></i>
              <p>Kegiatan</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('admin/jabatan')}}" class="nav-link {{($activeMenu == 'jabatan kegiatan')? 'active' : ''}}">
              <i class="nav-icon fa-regular fa-calendar-check"></i>
              <p>Jabatan Kegiatan</p>
            </a>
          </li>
          <li class="nav-header">Statistik</li>
          <li class="nav-item">
            <a href="{{ url('admin/statistik')}}" class="nav-link {{($activeMenu == 'statistik admin')? 'active' : ''}}">
              <i class="nav-icon fas fa-chart-simple"></i>
              <p>Statistik </p>
            </a>
          </li>
          <li class="nav-header">Repository</li>
          <li class="nav-item">
            <a href="{{ url('admin/file')}}" class="nav-link {{($activeMenu == 'file')? 'active' : ''}}">
              <i class="nav-icon fa-solid fa-cloud"></i>
              <p>Berkas </p>
            </a>
          </li>
        @endif

        {{-- DOSEN --}}
        @if(auth()->user()->level == "dosen" && $activeMenu != "kegiatan anggota" && $activeMenu != "statistik anggota" && $activeMenu != "agenda anggota" && $activeMenu != "kegiatan pic" && $activeMenu != "statistik pic" && $activeMenu != "progres kegiatan pic" && $activeMenu != "agenda kegiatan")
        <li class="nav-header">Data Kegiatan</li>
          <li class="nav-item">
            <a href="{{ url('dosen/kegiatan')}}" class="nav-link {{($activeMenu == 'kegiatan dosen')? 'active' : ''}}">
              <i class="nav-icon fa-regular fa-calendar-check"></i>
              <p>Kegiatan</p>
            </a>
          </li>
            <li class="nav-item">
                <a href="{{ url('dosen/kegiatan/jti')}}" class="nav-link {{($activeMenu == 'kegiatan jti')? 'active' : ''}}">
                    <i class="nav-icon fa-regular fa-calendar-check"></i>
                    <p>Kegiatan JTI</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('dosen/kegiatan/nonjti')}}" class="nav-link {{($activeMenu == 'kegiatan non jti')? 'active' : ''}}">
                    <i class="nav-icon fa-regular fa-calendar-check"></i>
                    <p>Kegiatan Non JTI</p>
                </a>
            </li>
          <li class="nav-header">Statistik</li>
          <li class="nav-item">
            <a href="{{ url('dosen/statistik')}}" class="nav-link {{($activeMenu == 'statistik dosen')? 'active' : ''}}">
              <i class="nav-icon fas fa-chart-simple"></i>
              <p>Statistik </p>
            </a>
          </li>
        @endif
        

        {{-- PIMPINAN --}}
        @if(auth()->user()->level == "pimpinan")
        <li class="nav-header">Manage Pengguna</li>
          <li class="nav-item">
            <a href="{{ url('pimpinan/user')}}" class="nav-link {{($activeMenu == 'user pimpinan')? 'active' : ''}}">
              <i class="nav-icon far fas fa-users"></i>
              <p>Data Dosen</p>
            </a>
          </li>
        <li class="nav-header">Data Kegiatan</li>
          <li class="nav-item">
            <a href="{{ url('pimpinan/kegiatan')}}" class="nav-link {{($activeMenu == 'kegiatan pimpinan')? 'active' : ''}}">
              <i class="nav-icon fa-regular fa-calendar-check"></i>
              <p>Kegiatan</p>
            </a>
          </li>
          <li class="nav-header">Statistik</li>
          <li class="nav-item">
            <a href="{{ url('pimpinan/statistik')}}" class="nav-link {{($activeMenu == 'statistik pimpinan')? 'active' : ''}}">
              <i class="nav-icon fas fa-chart-simple"></i>
              <p>Statistik </p>
            </a>
          </li>
        @endif


        {{-- PIC --}}
        @if($activeMenu  == "kegiatan pic" || $activeMenu == "statistik pic" || $activeMenu == "agenda anggota" || $activeMenu == "progres kegiatan pic")
        <li class="nav-header">Data Kegiatan</li>
          <li class="nav-item">
            <a href="{{ url('dosenPIC/kegiatan')}}" class="nav-link {{($activeMenu == 'kegiatan pic')? 'active' : ''}}">
              <i class="nav-icon fa-regular fa-calendar-check"></i>
              <p>Kegiatan</p>
            </a>
          </li>
            <li class="nav-item">
            <a href="{{ url('dosenPIC/agendaAnggota')}}" class="nav-link {{($activeMenu == 'agenda anggota')? 'active' : ''}}">
              <i class="nav-icon fas fa-list-ol"></i>
              <p>Agenda Anggota </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('dosenPIC/progresKegiatan')}}" class="nav-link {{($activeMenu == 'progres kegiatan pic')? 'active' : ''}}">
              <i class="nav-icon fas fa-chart-line"></i>
              <p>Progres Kegiatan </p>
            </a>
        </li>
          @endif

          {{-- ANGGOTA --}}
          @if($activeMenu  == "kegiatan anggota" || $activeMenu == "statistik anggota" || $activeMenu == "agenda kegiatan") 
        <li class="nav-header">Data Kegiatan</li>
          <li class="nav-item">
            <a href="{{ url('dosenAnggota/kegiatan')}}" class="nav-link {{($activeMenu == 'kegiatan anggota')? 'active' : ''}}">
              <i class="nav-icon fa-regular fa-calendar-check"></i>
              <p>Kegiatan</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('dosenAnggota/agenda')}}" class="nav-link {{($activeMenu == 'agenda kegiatan')? 'active' : ''}}">
              <i class="nav-icon fas fa-list-ol"></i>
              <p>Agenda Kegiatan </p>
            </a>
          </li>
          @endif
      </ul>
    </nav>
  </div>