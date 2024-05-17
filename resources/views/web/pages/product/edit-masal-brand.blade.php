@extends('web.layout.app')
@section('style')
<style>
    .img-e-wallet{
        height: 50px;
    }
    .w-100{
        width: 100%;
    }
</style>
@endsection
@section('breadcrumb')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">{{config('app.name')}}</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Halaman</a></li>
                    <li class="breadcrumb-item active"><a href="{{route('admin.products.index')}}">Kelola Produk</a></li>
                </ol>
            </div>
            <h4 class="page-title">Kelola Produk</h4>
        </div>
    </div>
    
</div>
@endsection
@section('content')
<div class="row">
    <div class="col-12 mb-3">
        <a href="{{route('admin.products.edit-masal.category', $category->id)}}"
        class="btn btn-danger btn-sm float-end"><i class="fa fa-arrow-left"></i> Kembali</a>
    </div>
    @foreach ($types as $type)
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5>{{$type->nama}}</h5>
                </div>
                <div>
                    <a href="{{route('admin.products.edit-masal.type',['category' => $category->id, 'type' => $type->id, 'brand' => $brand->id])}}"
                    class="btn btn-danger btn-sm"> Kelola Harga <i  class="fa fa-chevron-right"></i></a>
                </div>
            </div>
        </div> <!-- end card -->
    @endforeach
</div>
<!-- end row -->
@endsection
