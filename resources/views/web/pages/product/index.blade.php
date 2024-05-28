@extends('web.layout.app')
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection
@section('breadcrumb')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Kelola Produk</h4>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4 class="card-title">
                <i class="fa fa-barcode"></i>
                Daftar Produk</h4>
                <div>
                    <a href="{{route('admin.products.edit-masal')}}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Ubah Harga</a>
                    <button id="btnModalTambah" class="btn btn-secondary btn-sm"><i class="fa fa-plus"></i> Tambah</button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-2 mb-1">
                                <label class="control-label">Brand</label>
                                <div class="input-group">
                                    <select class="form-control select-2" name="brand">
                                        <option>Semua</option>
                                        @foreach ($brands as $brand)
                                            <option 
                                                {{ request()->brand == $brand->id ? 'selected' : '' }}
                                            value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 mb-1">
                                <label class="control-label">Kategori</label>
                                <div class="input-group">
                                    <select class="form-control select-2" name="category">
                                        <option>Semua</option>
                                        @foreach ($categories as $category)
                                            <option 
                                                {{ request()->category == $category->id ? 'selected' : '' }}
                                            value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 mb-1">
                                <label class="control-label">Tipe</label>
                                <div class="input-group">
                                    <select class="form-control select-2" name="type">
                                        <option>Semua</option>
                                        @foreach ($types as $type)
                                            <option 
                                                {{ request()->type == $type->id ? 'selected' : '' }}
                                            value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 mb-1">
                                <label class="control-label">Supplier</label>
                                <div class="input-group">
                                    <select class="form-control select-2" name="supplier">
                                        <option>Semua</option>
                                        @foreach ($suppliers as $supplier)
                                            <option
                                                {{ request()->supplier == $supplier->id ? 'selected' : '' }}
                                            value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>
                            <div class="col-lg-2 mb-1">
                                <label class="control-label">Status Produk</label>
                                {{-- input-group --}}
                                <div class="input-group">
                                    <select class="form-control select-2" name="status">
                                        <option>Semua</option>
                                        <option 
                                            {{ request()->status == 1 ? 'selected' : '' }}
                                        value="1">Aktif</option>
                                        <option 
                                            {{ request()->status == "0" ? 'selected' : '' }}
                                        value="0">Tidak Aktif</option>
                                       
                                    </select>

                                </div>
                            </div>
                            <div class="col-lg-2 mb-3">
                                <label class="control-label">Klik untuk mencari</label>
                                {{-- button --}}
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Cari Transaksi" name="search" value="{{ request()->search }}">
                                    <button class="btn btn-primary" type="button">
                                        <i class="ri-search-line"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="responsive-table-plugin">
                            <div class="table-rep-plugin">
                                <div class="table-responsive" data-pattern="priority-columns">
                                    <table id="tech-companies-1" class="table table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <th>No</th>
                                            <th width="300px">Produk</th>
                                            <th>Harga</th>
                                            <th width="50px">Deskripsi</th>
                                            <th>Supplier</th>
                                            <th>Aksi</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse ($products as $product)
                                        <tr>
                                            <th>{{ ($loop->index + 1) + ($products->currentPage() - 1) * $products->perPage() }}</th>
                                            <td class="d-flex align-items-center">
                                                <img src="{{$product->brand->logo_url}}" alt="" width="50px" class="me-2"> <br>
                                                <div>
                                                    Nama : {{$product->name}} <br>
                                                    Brand : {{$product->brand->name}} <br>
                                                    Kategori : {{$product->category->name}} <br>
                                                    Tipe : {{$product->type->name}} <br>
                                                </div>
                                            </td>
                                            <td>Rp. {{number_format($product->price,0,',','.')}}</td>
                                            <td>
                                                {{$product->description}}
                                            </td>
                                            <td>
                                                <ul class="">
                                            
                                                    @foreach ($product->product_suppliers as $detail)
                                                        <li>
                                                            <span class="badge bg-{{$detail->status == 1 ? 'success' : 'danger' }} rounded-pill">
                                                                {{$detail->supplier->name}}</span>
                                                        
                                                         
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td>
                                           
                                                <a href="{{route('admin.products.supplier.index',$product->id)}}" class="btn btn-success btn-sm"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Kelola Supplier Produk"
                                                    ><i class="ri-store-2-line"></i>
                                                    </a>

                                                    <a 
                                                onclick="handleEdit({{$product->id}})"
                                                class="btn btn-warning btn-sm"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Ubah"
                                                    ><i class="fa fa-edit"></i></a>
                                             
                                                <button
                                                    onclick="handleDelete({{$product->id}})"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Hapus"
                                                class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                            
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
                        </div> 
                    </div>
                </div>
                <!-- end .responsive-table-plugin-->
            </div>
        </div> <!-- end card -->
    </div> <!-- end col -->
</div>
<!-- end row -->
<div class="modal fade" id="modal-tambah" tabindex="-1" role="dialog" aria-labelledby="topupDetail" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="topupDetail">
                    <i class="ri-information-line"></i>
                    Form Tambah Data
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="form_tambah">
         
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" aria-label="Close">Batal</button>
                <button type="submit" id="btnSubmitTambah" class="btn btn-success btn-sm">Simpan</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-ubah" tabindex="-1" role="dialog" aria-labelledby="topupDetail" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="topupDetail">
                    <i class="ri-information-line"></i>
                    Form Ubah Data
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="form_ubah">
         
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" aria-label="Close">Batal</button>
                <button type="submit" id="btnSubmitUbah" class="btn btn-success btn-sm">Simpan</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
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
            const brand = document.querySelector('select[name="brand"]').value;
            const category = document.querySelector('select[name="category"]').value;
            const type = document.querySelector('select[name="type"]').value;
            const supplier = document.querySelector('select[name="supplier"]').value;
            const status = document.querySelector('select[name="status"]').value;
            const search = document.querySelector('input[type="text"]').value;
            

            window.location.href = `{{ route('admin.products.index') }}?brand=${brand}&category=${category}&type=${type}&supplier=${supplier}&status=${status}&search=${search}`;
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
    $('#btnModalTambah').on('click', function() {
        $.get("{{route('admin.products.create')}}", function(data) {
            $('#form_tambah').html(data);
            $('#modal-tambah').modal('show');
        });
    });

    $('#btnSubmitTambah').on('click', function(e) {
        e.preventDefault();
        const url = $('#form_tambah form').attr('action');
        const method = $('#form_tambah form').attr('method');
        const data = $('#form_tambah form').serialize();
        $('#form_tambah input').removeClass('is-invalid');
        $('#form_tambah select').removeClass('is-invalid');
        $.ajax({
            url: url,
            method: method,
            data: data,
            success: function(response) {
               if (response.status == true){
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message
                    }).then(() => {
                        window.location.reload();
                    });
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach((key) => {
                        $(`#form_tambah input[name="${key}"]`).addClass('is-invalid');
                        $(`#form_tambah select[name="${key}"]`).addClass('is-invalid');
                        // berikan pesan error
                        console.log(errors[key][0]);
                        console.log($(`#form_tambah input[name="${key}"]`).next().html('<small class="text-danger">'+errors[key][0]+'</small>'));
                        $(`#form_tambah input[name="${key}"]`).next().html(errors[key][0]);
                        $(`#form_tambah select[name="${key}"]`).next().html(errors[key][0]);
                    });
                } else if (xhr.status === 500) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan pada server!'
                    });
                }
            }
        });
    });


    const handleEdit = (id) => {
        let url = `{{route('admin.products.edit', 'id')}}`;
        url = url.replace('id', id);
        $.get(`${url}`, function(data) {
            $('#form_ubah').html(data);
            $('#modal-ubah').modal('show');
        });
    }

    $('#btnSubmitUbah').on('click', function(e) {
        e.preventDefault();
        const url = $('#form_ubah form').attr('action');
        const method = $('#form_ubah form').attr('method');
        const data = $('#form_ubah form').serialize();
        $('#form_ubah input').removeClass('is-invalid');
        $('#form_ubah select').removeClass('is-invalid');
        $.ajax({
            url: url,
            method: method,
            data: data,
            success: function(response) {
                if (response.status == true){
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message
                    }).then(() => {
                        window.location.reload();
                    });
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach((key) => {
                        $(`#form_ubah input[name="${key}"]`).addClass('is-invalid');
                        $(`#form_ubah select[name="${key}"]`).addClass('is-invalid');
                        // berikan pesan error
                        console.log(errors[key][0]);
                        console.log($(`#form_ubah input[name="${key}"]`).next().html('<small class="text-danger">'+errors[key][0]+'</small>'));
                        $(`#form_ubah input[name="${key}"]`).next().html(errors[key][0]);
                        $(`#form_ubah select[name="${key}"]`).next().html(errors[key][0]);
                    });
                } else if (xhr.status === 500) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan pada server!'
                    });
                }
            }
        });
    });

    const handleDelete = (id) => {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Menghapus data ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                let url = `{{route('admin.products.destroy', 'id')}}`;
                url = url.replace('id', id);
                $.ajax({
                    url: url,
                    method: 'DELETE',
                    data: {
                        '_token': '{{csrf_token()}}'
                    },
                    success: function(response) {
                        if (response.status == true){
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            }).then(() => {
                                window.location.reload();
                            });
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 500) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Terjadi kesalahan pada server!'
                            });
                        }
                    }
                });
            }
        });
    }
</script>
@endsection
