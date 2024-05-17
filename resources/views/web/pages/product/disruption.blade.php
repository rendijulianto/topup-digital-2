@extends('web.layout.app')
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
                <h4 class="card-title">
                    <i  class="fa fa-list"></i>
                    Daftar Gangguan Produk 
                    <i class="fa fa-info-circle" 
                    data-bs-toggle="tooltip" data-bs-placement="top" 
                    data-bs-title="Gangguan produk adalah produk yang tidak sesuai dengan kriteria yang telah ditentukan" 
                    style="font-size: 20px"></i>
                </h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 mb-1">
                        <label class="control-label">Kategori</label>
                        <div class="input-group">
                            <select class="form-control select-2" name="category">
                                <option>Semua</option>
                                @foreach ($categories as $category)
                                    <option 
                                        {{ request()->kategori == $category->id ? 'selected' : '' }}
                                    value="{{ $category->id }}">{{ $category->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 mb-1">
                        <label class="control-label">Brand</label>
                        <div class="input-group">
                            <select class="form-control select-2" name="brand">
                                <option>Semua</option>
                                @foreach ($brands as $brand)
                                    <option 
                                        {{ request()->brand == $brand->id ? 'selected' : '' }}
                                    value="{{ $brand->id }}">{{ $brand->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 mb-1">
                        <label class="control-label">Tipe</label>
                        <div class="input-group">
                            <select class="form-control select-2" name="type">
                                <option>Semua</option>
                                @foreach ($types as $type)
                                    <option 
                                        {{ request()->tipe == $type->id ? 'selected' : '' }}
                                    value="{{ $type->id }}">{{ $type->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 mb-3">
                        <label class="control-label">Klik untuk mencari</label>
                        {{-- button --}}
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Cari Produk" name="search" value="{{ request()->search }}">
                            <button class="btn btn-primary" type="button">
                                <i class="ri-search-line"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="responsive-table-plugin">
                    <div class="table-rep-plugin">
                        <div class="table-responsive" data-pattern="priority-columns">
                            <table id="tech-companies-1" class="table table-striped">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Harga</th>
                                    <th>Deskripsi</th>
                                    <th>Aksi</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($products as $product)
                                <tr>
                                    <th>{{ ($loop->index + 1) + ($products->currentPage() - 1) * $products->perPage() }}</th>
                                    <td>
                                        <b>Produk : </b>{{$product->nama}} <br>
                                        <b>Brand : </b>{{$product->brand->nama}} <br>
                                        <b>Tipe : </b>{{$product->tipe->nama}} <br>
                                        <b>Kategori : </b>{{$product->kategori->nama}}
                                    </td>
                                    <td>Rp. {{number_format($product->harga,0,',','.')}} </td>
                                    <td>{{$product->deskripsi}}</td>
                                    <td>
                                        <form action="{{route('admin.products.destroy',$product->id)}}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')"
                                            data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Hapus">

                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                        </form>
                                    </td>
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
<!-- end row -->
@endsection
@section('script')
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select-2').select2();
        const handleSearch = () => {
            const search = document.querySelector('input[type="text"]').value;
            const category = document.querySelector('select[name="category"]').value;
            const brand = document.querySelector('select[name="brand"]').value;
            const type = document.querySelector('select[name="type"]').value;

            window.location.href = `{{ route('admin.products.disruption') }}?search=${search}&kategori=${category}&brand=${brand}&tipe=${type}`;
        }

       $('.select-2').on('change', function() {
           handleSearch();
       });

        // enter / button search
        document.querySelector('input[type="text"]').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                handleSearch();
            }
        });
    });
</script>
@endsection

