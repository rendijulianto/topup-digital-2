@extends('web.layout.app')
@section('title', 'Detail Pesanan')

@section('style')

@endsection
@section('breadcrumb')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">
              Halaman Detail Topup
            </h4>
        </div>
    </div>
</div>
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <h4 class="header-title">Detail Topup #{{$topup->id}}</h4>
              </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 mb-3 d-flex justify-content-between align-items-center">
                            <img
                            src="{{asset('assets/images/logo-dark.png')}}" alt="logo" width="75px" >
                            <div class="text-center">
                                <p>Halaman akan hilang dalam</p>
                                <h3 id="countdown">
                                    2:00
                                </h3>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <h5>Hallo, Pelanggan {{$websiteData->nama}}</h5>
                            <p>
                                Terima kasih telah melakukan top up di {{$websiteData->nama}}.
                        
                            </p>
                            <p>
                                Berikut adalah detail top up Anda:
                            </p>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <div class="form-group">
                                <label for="product">Nama Produk</label>
                                <input type="text" class="form-control form-control-md" value="{{$topup->product->name}}" readonly>
                            </div>
                        </div>           
                        <div class="col-lg-6 mb-3">
                            <div class="form-group">
                                <label for="harga">Harga</label>
                                <input type="text" class="form-control form-control-md" 
                                autofocus="true"
                                value="Rp {{number_format($topup->price_sell, 0, ',', '.')}}" readonly>
                            </div>
                        </div>             
                        @if($topup->tipe == 'e_wallet')
                        <div class="col-lg-6 mb-3">
                            <div class="form-group">
                                <label for="customer_name">Nama Pelanggan</label>
                                <input type="text" class="form-control form-control-md" value="{{$topup->e_wallet->customer_name}}" readonly>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <div class="form-group">
                                <label for="nomor">Nomor</label>
                                <input type="text" class="form-control form-control-md" value="{{$topup->target}}" readonly>
                            </div>
                        </div>
                        @elseif($topup->tipe == 'token_listrik')
                        <div class="col-lg-6 mb-3">
                            <div class="form-group">
                                <label for="id_pelanggan">ID Pelanggan</label>
                                <input type="text" class="form-control form-control-md" value="{{$topup->token_listrik->subscriber_id}}" readonly>
                            </div>
                        </div>
                        {{-- nomor_meter --}}
                        <div class="col-lg-6 mb-3">
                            <div class="form-group">
                                <label for="nomor_meter">Nomor Meter</label>
                                <input type="text" class="form-control form-control-md" value="{{$topup->token_listrik->meter_no}}" readonly>
                            </div>
                        </div>
                        {{-- nama_pelanggan --}}
                        <div class="col-lg-6 mb-3">
                            <div class="form-group">
                                <label for="nama_pelanggan">Nama Pelanggan</label>
                                <input type="text" class="form-control form-control-md" value="{{$topup->token_listrik->customer_name}}" readonly>
                            </div>
                        </div>
                        {{-- segment_power --}}
                        <div class="col-lg-6 mb-3">
                            <div class="form-group">
                                <label for="segment_power">Segment Power</label>
                                <input type="text" class="form-control form-control-md" value="{{$topup->token_listrik->segment_power}}" readonly>
                            </div>
                        </div>
                        @else 
                        <div class="col-lg-6 mb-3">
                            <div class="form-group">
                                <label for="nomor">Nomor</label>
                                <input type="text" class="form-control form-control-md" value="{{$topup->target}}" readonly>
                            </div>
                        </div>
                        @endif
                        <div class="col-lg-6 mb-3">
                            <div class="form-group">
                                <label for="Whatsapp">Whatsapp</label>
                                <input type="text" class="form-control form-control-md" value="{{$topup->whatsapp}}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    {{-- jika sudah load --}}
    <script>
        $(document).ready(function() {
            var count = 2*60;
            var countdown = setInterval(function() {
                // convert to menit
                var minutes = Math.floor(count / 60);
                var seconds = count % 60;
                if (seconds < 10) {
                    seconds = "0" + seconds;
                }

                $("#countdown").html(minutes + ":" + seconds);
                if (count == 0) {
                    clearInterval(countdown);
                    window.location = "{{route('dashboard')}}?isGuest=true";
                }
                count--;
                console.log(count);
            }, 1000);
        });
    </script>
@endsection
