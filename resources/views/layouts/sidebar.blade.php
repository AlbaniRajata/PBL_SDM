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
        <li class="nav header">admin</li>
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
<li class="nav header">Pimpinan</li>
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
<li class="nav header">Dosen PIC</li>
        <li class="nav-header">Data Kegiatan</li>
          <li class="nav-item">
            <a href="{{ url('dosenPIC/kegiatan')}}" class="nav-link {{($activeMenu == 'kegiatan pic')? 'active' : ''}}">
              <i class="nav-icon fa-regular fa-calendar-check"></i>
              <p>Kegiatan</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/progress')}}" class="nav-link {{($activeMenu == 'progress')? 'active' : ''}}">
              <i class="nav-icon fas fa-solid fa-list-check"></i>
              <p>Progress Kegiatan </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/agenda')}}" class="nav-link {{($activeMenu == 'agenda')? 'active' : ''}}">
              <i class="nav-icon fas fa-list-ol"></i>
              <p>Agenda Anggota </p>
            </a>
          </li>
          <li class="nav-header">Statistik</li>
          <li class="nav-item">
            <a href="{{ url('dosenPIC/statistik')}}" class="nav-link {{($activeMenu == 'statistik pic')? 'active' : ''}}">
              <i class="nav-icon fas fa-chart-simple"></i>
              <p>Statistik </p>
            </a>
          </li>
<li class="nav header">Dosen Anggota</li>
        <li class="nav-header">Data Kegiatan</li>
          <li class="nav-item">
            <a href="{{ url('dosenAnggota/kegiatan')}}" class="nav-link {{($activeMenu == 'kegiatan anggota')? 'active' : ''}}">
              <i class="nav-icon fa-regular fa-calendar-check"></i>
              <p>Kegiatan</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/agenda')}}" class="nav-link {{($activeMenu == 'agenda')? 'active' : ''}}">
              <i class="nav-icon fas fa-list-ol"></i>
              <p>Agenda Kegiatan </p>
            </a>
          </li>
          <li class="nav-header">Statistik</li>
          <li class="nav-item">
            <a href="{{ url('dosenAnggota/statistik')}}" class="nav-link {{($activeMenu == 'statistik anggota')? 'active' : ''}}">
              <i class="nav-icon fa-solid fa-chart-simple"></i>
              <p>Statistik</p>
            </a>
          </li>
      </ul>
    </nav>
  </div>