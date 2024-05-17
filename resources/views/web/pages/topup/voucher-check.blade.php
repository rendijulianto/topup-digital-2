@extends('web.layout.app')
@section('title', 'Voucher')
@section('breadcrumb')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">{{config('app.name')}}</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Halaman</a></li>
                    <li class="breadcrumb-item active"><a href="">Daftar Produk</a></li>
                </ol>
            </div>
            <h4 class="page-title">Daftar Produk</h4>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="row">
    @forelse($brands as $brand)
    <div class="col-lg-4">
        <a class="card" href="{{route('voucher.brand', $brand->slug)}}">
            <div class="card-body d-flex flex-column justify-content-center align-items-center">
                <div class="mb-3">
                    <img src="{{$brand->logo_url}}"
                    height="50"
                    width="50"
                    alt="" class="img-e-wallet">
                </div>
                <label for="" class="mb-2">{{$brand->nama}}</label>
                <div class="rounded w-100">
                    <button class="btn btn-danger
                    w-100"><i class="fa fa-eye"></i> Lihat Produk</button>

            
                </div>
            </div>
        </a>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-warning" role="alert">
            <strong>Maaf!</strong> Tidak ada produk yang tersedia.
        </div>
    @endforelse
</div>
@endsection
