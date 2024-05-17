@extends('web.layout.app')
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
    <div class="col-12">
        <form action="" method="GET" class="row">
            <div class="col-3">
                <label for="category">Filter berdasarkan kategori</label>
                <div class="input-group mb-3">
                    <select name="category" id="category" class="form-select">
                        <option value="" disabled selected>Pilih kategori</option>
                        <option value="" {{request()->category == '' ? 'selected' : ''}}>Semua</option>
                        @foreach ($categories as $category)
                        <option value="{{$category->slug}}" {{request()->category == $category->slug ? 'selected' : ''}}>{{$category->nama}}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-danger" type="submit" id="button-addon2"><i class="fa fa-search"></i> Cari</button>
                </div>
            </div>
            <div class="col-3">
                <label for="brand">Filter berdasarkan brand</label>
                <div class="input-group mb-3">
                    <select name="brand" id="brand" class="form-select">
                        <option value="" disabled selected>Pilih brand</option>
                        <option value="" {{request()->brand == '' ? 'selected' : ''}}>Semua</option>
                        @foreach ($brands as $brand)
                        <option value="{{$brand->slug}}" {{request()->brand == $brand->slug ? 'selected' : ''}}>{{$brand->nama}}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-danger" type="submit" id="button-addon2"><i class="fa fa-search"></i> Cari</button>
                </div>
            </div>
            <div class="col-3">
                <label for="type">Filter berdasarkan tipe</label>
                <div class="input-group mb-3">
                    <select name="type" id="type" class="form-select">
                        <option value="" disabled selected>Pilih tipe</option>
                        <option value="" {{request()->type == '' ? 'selected' : ''}}>Semua</option>
                        @foreach ($types as $type)
                        <option value="{{$type->slug}}" {{request()->type == $type->slug ? 'selected' : ''}}>{{$type->nama}}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-danger" type="submit" id="button-addon2"><i class="fa fa-search"></i> Cari</button>
                </div>
            </div>
            <div class="col-3">
                <label for="search">Cari produk</label>
                <div class="input-group mb-3">
                    <input type="text" name="search" class="form-control" placeholder="Cari produk" value="{{request()->search}}">
                    <button class="btn btn-danger" type="submit" id="button-addon2"><i class="fa fa-search"></i> Cari</button>
                </div>
            </div>
        </form>
    </div>
    <div class="col-12">
        @if (session('success'))
            <div class="alert alert-success">
                {{session('success')}}
            </div>
        @elseif (session('error'))
            <div class="alert alert-danger">
                {{session('error')}}
            </div>
        @endif
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4 class="card-title">Daftar Produk</h4>
            </div>
            <div class="card-body">
                <div class="responsive-table-plugin">
                    <div class="table-rep-plugin">
                        <div class="table-responsive" data-pattern="priority-columns">
                            <table id="tech-companies-1" class="table table-striped">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Kategori</th>
                                    <th>Brand</th>
                                    <th>Tipe</th>
                                    <th>Harga</th>
                                    <th>Deskripsi</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($products as $product)
                                <tr>
                                    <th>{{ ($loop->index + 1) + ($products->currentPage() - 1) * $products->perPage() }}</th>
                                    <td>{{$product->nama}}</td>
                                    <td>{{$product->kategori->nama}}</td>
                                    <td>
                                        {{$product->brand->nama}}
                                    </td>
                                    <td>{{$product->tipe->nama}}</td>
                                    <td>Rp. {{number_format($product->harga,0,',','.')}} 
                                    </td>
                                    <td>{{$product->deskripsi}}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center">Tidak ada data</td>
                                </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div> <!-- end .table-responsive -->

                        {{$products->withQueryString()->links()}}

                    </div> <!-- end .table-rep-plugin-->
                </div> <!-- end .responsive-table-plugin-->
            </div>
        </div> <!-- end card -->
    </div> <!-- end col -->
</div>
@endsection
