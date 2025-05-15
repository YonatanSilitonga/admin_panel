<aside class="sidebar" id="sidebar">
    {{-- Sidebar Header --}}
    <header class="sidebar-header">
        <a href="{{ url('/admin/beranda') }}" class="header-logo">
            <img src="{{ asset('images/HadirIn.jpg') }}" alt="HadirIn Logo" class="logo-img">
        </a>
        <button class="toggler" type="button" aria-label="Toggle Sidebar" id="sidebar-toggler">
            <span class="material-symbols-rounded">menu</span>
        </button>
    </header>

    {{-- Sidebar Navigation --}}
    <nav class="sidebar-nav">
        {{-- Primary Navigation --}}
        <ul class="nav-list primary-nav">
            @php
                $navItems = [
                    [
                        'url' => '/admin/beranda',
                        'pattern' => 'admin/beranda',
                        'icon' => 'dashboard',
                        'label' => 'Beranda',
                        'tooltip' => 'Beranda'
                    ],
                    [
                        'url' => '/rekapitulasi',
                        'pattern' => ['rekapitulasi*', 'admin/rekapitulasi*'],
                        'icon' => 'edit_note',
                        'label' => 'Rekapitulasi',
                        'tooltip' => 'Rekapitulasi'
                    ],
                    [
                        'url' => '/guru',
                        'pattern' => 'guru*',
                        'icon' => 'school',
                        'label' => 'Manajemen Data Guru',
                        'tooltip' => 'Data Guru'
                    ],
                    [
                        'url' => '/orang-tua',
                        'pattern' => ['orang-tua*', 'admin/orang_tua*'],
                        'icon' => 'family_restroom',
                        'label' => 'Manajemen Data Orang Tua',
                        'tooltip' => 'Data Orang Tua'
                    ],
                    [
                        'url' => '/siswa',
                        'pattern' => ['siswa*', 'admin/siswa*'],
                        'icon' => 'person_pin',
                        'label' => 'Manajemen Data Siswa',
                        'tooltip' => 'Data Siswa'
                    ],
                    [
                        'url' => '/users',
                        'pattern' => ['users*', 'admin/users*'],
                        'icon' => 'manage_accounts',
                        'label' => 'Manajemen Akun Pengguna',
                        'tooltip' => 'Akun Pengguna'
                    ],
                    [
                        'url' => '/kelas',
                        'pattern' => 'kelas*',
                        'icon' => 'class',
                        'label' => 'Manajemen Data Kelas',
                        'tooltip' => 'Data Kelas'
                    ],
                    [
                        'url' => '/mata-pelajaran',
                        'pattern' => 'mata-pelajaran*',
                        'icon' => 'menu_book',
                        'label' => 'Manajemen Mata Pelajaran',
                        'tooltip' => 'Mata Pelajaran'
                    ],
                    [
                        'url' => '/jadwal-pelajaran',
                        'pattern' => 'jadwal-pelajaran*',
                        'icon' => 'event',
                        'label' => 'Jadwal Pelajaran',
                        'tooltip' => 'Jadwal'
                    ],
                    [
                        'url' => '/tahun-ajaran',
                        'pattern' => 'tahun-ajaran*',
                        'icon' => 'date_range',
                        'label' => 'Manajemen Tahun Ajaran',
                        'tooltip' => 'Tahun Ajaran'
                    ],
                ];
            @endphp

            @foreach($navItems as $item)
                <li class="nav-item">
                    @php
                        $isActive = false;
                        if (is_array($item['pattern'])) {
                            foreach ($item['pattern'] as $pattern) {
                                if (Request::is($pattern)) {
                                    $isActive = true;
                                    break;
                                }
                            }
                        } else {
                            $isActive = Request::is($item['pattern']);
                        }
                    @endphp
                    <a href="{{ url($item['url']) }}" class="nav-link {{ $isActive ? 'active' : '' }}">
                        <span class="nav-icon material-symbols-rounded">{{ $item['icon'] }}</span>
                        <span class="nav-label">{{ $item['label'] }}</span>
                        <span class="nav-tooltip">{{ $item['tooltip'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>

        {{-- Secondary Navigation --}}
        <ul class="nav-list secondary-nav">
            <li class="nav-item">
                <form action="{{ route('logout') }}" method="POST" id="logout-form">
                    @csrf
                    <button type="submit" class="nav-link logout-btn" aria-label="Logout">
                        <span class="nav-icon material-symbols-rounded">logout</span>
                        <span class="nav-label">Logout</span>
                        <span class="nav-tooltip">Keluar</span>
                    </button>
                </form>
            </li>
        </ul>
    </nav>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const sidebarToggler = document.getElementById('sidebar-toggler');
        
        // Restore sidebar state from localStorage
        if (localStorage.getItem('sidebarCollapsed') === 'true') {
            sidebar.classList.add('collapsed');
        }
        
        // Toggle sidebar with animation
        sidebarToggler.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
        });
        
        // Close sidebar on mobile when clicking outside
        document.addEventListener('click', function(event) {
            const isMobile = window.innerWidth < 768;
            const isClickInsideSidebar = sidebar.contains(event.target);
            const isClickOnToggler = sidebarToggler.contains(event.target);
            
            if (isMobile && !isClickInsideSidebar && !isClickOnToggler && !sidebar.classList.contains('collapsed')) {
                sidebar.classList.add('collapsed');
                localStorage.setItem('sidebarCollapsed', true);
            }
        });
        
        // Update sidebar state on window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth < 768 && !sidebar.classList.contains('collapsed')) {
                sidebar.classList.add('collapsed');
                localStorage.setItem('sidebarCollapsed', true);
            }
        });
    });
</script>