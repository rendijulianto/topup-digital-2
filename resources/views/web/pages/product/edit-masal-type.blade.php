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
        <a href="{{route('admin.products.edit-masal.brand', ['category' => $category->id, 'brand' => $brand->id])}}"
        class="btn btn-danger btn-sm float-end"><i class="fa fa-arrow-left"></i> Kembali</a>
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
                        <form action="{{route('admin.products.update-masal')}}" method="POST">
                            @method('PUT')
                            @csrf
                            <div class="table-responsive" data-pattern="priority-columns">
                                <table id="tech-companies-1" class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Harga Supplier</th>
                                        <th>Harga Jual</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse ($products as $product)
                                    <tr>
                                        <th>{{ ($loop->index)+1 }}</th>
                                        <td>{{$product->nama}}</td>
                                        <td>
                                            {{-- getHargaTermahalAttribute --}}
                                            Rp. {{number_format($product->harga_termahal,0,',','.')}}
                                        </td>
                                        <td>
                                            {{-- input group --}}
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="hidden" name="id[]" value="{{$product->id}}">
                                                <input type="number" name="harga[]" class="form-control" value="{{$product->harga}}">
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="10" class="text-center">Tidak ada data</td>
                                    </tr>
                                    @endforelse
                                    <tr>
                                        <td colspan="10">
                                            <button class="btn btn-danger w-100" type="submit" id="button-addon2"><i class="fa fa-save"></i> Simpan</button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div> <!-- end .table-responsive -->
                        </form>
                    </div> <!-- end .table-rep-plugin-->
                </div> <!-- end .responsive-table-plugin-->
            </div>
        </div> <!-- end card -->
    </div> <!-- end col -->
</div>
<!-- end row -->
@endsection
