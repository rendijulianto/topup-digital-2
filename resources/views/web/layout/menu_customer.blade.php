<li class="side-nav-title">Topup</li>
<li class="side-nav-item">
    <a href="{{route('dashboard')}}?isCustomer=true"
    class="side-nav-link">
        <i class="ri-dashboard-line"></i>
        <span> Halaman Utama </span>
    </a>
</li>
<li class="side-nav-item">
    <a href="{{route('topup.create', ['category' => 'pulsa'])}}?isCustomer=true"
    class="side-nav-link">
        <i class="ri-smartphone-line"></i>
        <span> Pulsa </span>
    </a>
</li>
<li class="side-nav-item">
    <a href="{{route('topup.create', ['category' => 'e-money'])}}?isCustomer=true"
        class="side-nav-link">
        <i class="ri-wallet-3-line"></i>
        <span> Dompet Digital </span>
    </a>
</li>
<li class="side-nav-item">
    <a href="{{route('topup.create', ['category' => 'pln'])}}?isCustomer=true"
         class="side-nav-link">
        <i class="ri-wireless-charging-line"></i>
        <span> Token Listrik  </span>
    </a>
</li>
<li class="side-nav-item">
    <a href="{{route('topup.create', ['category' => 'data'])}}?isCustomer=true"
         class="side-nav-link">
        <i class="ri-wifi-line"></i>
        <span> Paket Data Internet </span>
    </a>
</li>
<li class="side-nav-item">
    <a href="{{route('topup.create', ['category' => 'masa aktif'])}}?isCustomer=true"
         class="side-nav-link">
        <i class="ri-health-book-line"></i>
        <span> Tambah Masa Aktif  </span>
    </a>
</li>
<li class="side-nav-item">
    <a href="{{route('voucher')}}?isCustomer=true"
    class="side-nav-link">
        <i class="ri-ticket-line"></i>
        <span> Voucher Fisik  </span>
    </a>
</li>
