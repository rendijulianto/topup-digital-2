<li class="side-nav-title">Admin</li>
<li class="side-nav-item">
    <a href="{{route('admin.dashboard')}}" class="side-nav-link">
        <i class="ri-dashboard-line"></i>
        <span> Dashboard  </span>
    </a>
</li>
<li class="side-nav-item">
    <a data-bs-toggle="collapse" href="#sidebarKelola" aria-expanded="false" aria-controls="sidebarKelola" class="side-nav-link">
        <i class="ri-briefcase-line"></i>
        <span>Kelola Master</span>
        <span class="menu-arrow"></span>
    </a>
    <div class="collapse" id="sidebarKelola">
        <ul class="side-nav-second-level">
            <li>
                <a href="{{route('admin.users.index')}}">
                    <i class="ri-team-line"></i>
                    <span>Kelola Pengguna</span>
                  </a>
            </li>
            <li>
                <a href="{{route('admin.brands.index')}}">
                    <i class="ri-vip-crown-line"></i>
                    <span>Kelola Brand</span>
                </a>
            </li>
            <li>
                <a href="{{route('admin.prefixes.index')}}"> 
                    <i class="ri-focus-2-line"></i>
                    <span>Kelola Prefix</span>
                  </a>
            </li>
            <li>
                <a href="{{route('admin.categories.index')}}">
                    <i class="ri-list-check-2"></i>
                    <span>Kelola Kategori</span>
                  </a>
            </li>
            <li>
                <a href="{{route('admin.types.index')}}">
                    <i class="ri-list-check-2"></i>
                    <span>Kelola Tipe</span>
                </a>
            </li>
            <li>
                <a href="{{route('admin.suppliers.index')}}">
                    <i class="ri-store-2-line"></i>
                    <span>Kelola Supplier</span>
                </a>
            </li>
            <li>
                <a href="{{route('admin.products.index')}}">
                    <i class="ri-barcode-box-line"></i>
                    <span>Kelola Produk</span>
                </a>
            </li>
            <li>
                <a href="{{route('admin.banners.index')}}">
                    <i class="ri-nft-line"></i>
                    <span>Kelola Banner</span>
                </a>
            </li>
        </ul>
    </div>
</li>
<li class="side-nav-item">
    <a data-bs-toggle="collapse" href="#sidebarLaporan" aria-expanded="false" aria-controls="sidebarLaporan" class="side-nav-link">
        <i class="ri-file-list-3-line"></i>
        <span>Laporan</span>
        <span class="menu-arrow"></span>
    </a>
    <div class="collapse" id="sidebarLaporan">
        <ul class="side-nav-second-level">
            <li>
                <a href="{{route('admin.topups.index')}}">  
                    <i class="ri-receipt-line"></i>
                    <span> Transaksi  </span>
                    </a>
            </li>
            <li>
                <a href="{{route('admin.vouchers.index')}}">
                    <i class="fa fa-ticket"></i>
                    <span> Voucher  </span>
                </a>
            </li>
            <li>
                <a href="{{route('admin.suppliers.performance')}}" >
                    <i class="fa fa-chart-line"></i>
                    <span> Performa Supplier</span>
                </a>
            </li>
        </ul>
    </div>
</li>
<li class="side-nav-item">
    <a href="{{route('admin.logs.index')}}" class="side-nav-link">
        <i class="ri-file-list-3-line"></i>
        <span> Log Aktivitas
    </a>
</li>

<li class="side-nav-item">
    <a href="{{route('admin.website')}}" class="side-nav-link">
        <i class="ri-settings-2-line"></i>
        <span> Pengaturan Web  </span>
    </a>
</li>

