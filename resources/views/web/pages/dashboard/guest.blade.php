@extends('web.layout.app')
@section('title', 'Home')
@section('style')
<style>

</style>
@endsection
@section('breadcrumb')

@endsection
@section('content')
<div class="row">
    <div class="col-lg-8 mt-2">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach ($banners as $key => $banner)
                                <div class="carousel-item active" data-bs-interval="2000">
                                    <img src="{{asset('banners/'.$banner->gambar)}}"
                                    style="max-height: 250px"
                                    class="img-fluid d-block w-100" alt='{{$banner->judul}}'>
                                </div>
                                @endforeach
                               
                               
                            </div>
                            <a class="carousel-control-prev" href="#carouselExampleInterval" role="button"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Sebelumnya</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselExampleInterval" role="button"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Lanjut</span>
                            </a>
                        </div>
        
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="alert alert-info">
                    <marquee onmouseover="this.stop();" onmouseout="this.start();">
                        <span
                        style="font-size: 20px; font-weight: bold;">Selamat datang di Warungku, Silahkan sentuh untuk memilih produk topup.</span>
                     </marquee>
                </div>
            </div>
        </div>
    </div> <!-- end col -->
    <div class="col-lg-4 mt-2">
        <div class="card">
            <div class="card-body">
                <h4>Produk Terlaris</h4>
                <div class="table-responsive">
                    <table class="table table-borderless table-striped table-nowrap table-centered m-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">Nama Produk</th>
                            
                            </tr>
                        </thead>
                        <tbody id="produk-best-seller">
                          
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-3">
                <a href="{{route('topup.create', ['category' => 'pulsa'])}}?isGuest={{request()->isGuest}}"
                    >
                    <div class="card kategori">
                        <div class="card-body d-flex flex-column align-items-center">
                            <i class="ri-smartphone-line fs-24 text-primary"></i>
                            <h4 class="mt-2"> Pulsa </h4>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3">
                <a href="{{route('topup.create', ['category' => 'e-money'])}}?isGuest={{request()->isGuest}}">
                    <div class="card kategori">
                        <div class="card-body d-flex flex-column align-items-center">
                            <i class="ri-wallet-3-line fs-24 text-success"></i>
                            <h4 class="mt-2"> Dompet Digital </h4>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3">
                <a href="{{route('topup.create', ['category' => 'pln'])}}?isGuest={{request()->isGuest}}">
                    <div class="card kategori">
                        <div class="card-body d-flex flex-column align-items-center">
                            <i class="ri-wireless-charging-line fs-24 text-primary"></i>
                            <h4 class="mt-2"> Token Listrik </h4>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3">
                <a href="{{route('topup.create', ['category' => 'data'])}}?isGuest={{request()->isGuest}}">
                    <div class="card kategori">
                        <div class="card-body d-flex flex-column align-items-center">
                            <i class="ri-smartphone-line fs-24 text-info"></i>
                            <h4 class="mt-2"> Paket Data Internet </h4>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3">
                <a href="{{route('topup.create', ['category' => 'Paket SMS & Telpon'])}}?isGuest={{request()->isGuest}}">
                    <div class="card kategori">
                        <div class="card-body d-flex flex-column align-items-center">
                            <i class="ri-phone-line fs-24 text-primary"></i>
                            <h4 class="mt-2"> Pulsa Sms & Telepon </h4>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3">
                <a href="{{route('topup.create', ['category' => 'masa aktif'])}}?isGuest={{request()->isGuest}}">
                    <div class="card kategori">
                        <div class="card-body d-flex flex-column align-items-center">
                            <i class="ri-health-book-line fs-24 text-primary"></i>
                            <h4 class="mt-2"> Tambah Masa Aktif </h4>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3">
                <a href="{{route('voucher')}}?isGuest={{request()->isGuest}}">
                    <div class="card kategori">
                        <div class="card-body d-flex flex-column align-items-center">
                            <i class="ri-ticket-line fs-24 text-primary"></i>
                            <h4 class="mt-2"> Voucher Fisik </h4>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
<!-- end row -->
</div>
@endsection
@section('script')
<script>
      function getBestseller(event) {
        $.ajax({
            url: `{{route('api.products.best-seller')}}?jumlah=5`,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                $('#produk-best-seller').empty();
                response.data.forEach((voucher, index) => {
                    $('#produk-best-seller').append(`
                        <tr>
                            <td>${voucher.nama}</td>
                        </tr>
                    `);
                });
            },
            error: function(error) {
                console.log(error);
            }
        });
    }

    $(document).ready(function() {
        getBestseller();
    });
</script>
@endsection
