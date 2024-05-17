<li class="side-nav-title">Topup</li>
<li class="side-nav-item">
    <a href="{{route('dashboard')}}?isGuest=true"
    class="side-nav-link">
        <i class="ri-dashboard-line"></i>
        <span> Halaman Utama </span>
    </a>
</li>
<li class="side-nav-item">
    <a href="{{route('topup.create', ['category' => 'pulsa'])}}?isGuest=true"
    class="side-nav-link">
        <i class="ri-smartphone-line"></i>
        <span> Pulsa </span>
    </a>
</li>
<li class="side-nav-item">
    <a href="{{route('topup.create', ['category' => 'e-money'])}}?isGuest=true"
        class="side-nav-link">
        <i class="ri-wallet-3-line"></i>
        <span> Dompet Digital </span>
    </a>
</li>
<li class="side-nav-item">
    <a href="{{route('topup.create', ['category' => 'pln'])}}?isGuest=true"
         class="side-nav-link">
        <i class="ri-wireless-charging-line"></i>
        <span> Token Listrik  </span>
    </a>
</li>
<li class="side-nav-item">
    <a href="{{route('topup.create', ['category' => 'data'])}}?isGuest=true"
         class="side-nav-link">
        <i class="ri-wifi-line"></i>
        <span> Paket Data Internet </span>
    </a>
</li>
<li class="side-nav-item">
    <a href="{{route('topup.create', ['category' => 'Paket SMS & Telpon'])}}?isGuest=true"
         class="side-nav-link">
        <i class="ri-phone-line"></i>
        <span> Paket Sms & Telpon </span>
    </a>
</li>
<li class="side-nav-item">
    <a href="{{route('topup.create', ['category' => 'masa aktif'])}}?isGuest=true"
         class="side-nav-link">
        <i class="ri-health-book-line"></i>
        <span> Tambah Masa Aktif  </span>
    </a>
</li>
<li class="side-nav-item">
    <a href="{{route('voucher')}}?isGuest=true"
    class="side-nav-link">
        <i class="ri-ticket-line"></i>
        <span> Voucher Fisik  </span>
    </a>
</li>
