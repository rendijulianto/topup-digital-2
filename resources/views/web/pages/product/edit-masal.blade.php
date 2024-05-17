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
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Edit Produk</h4>
            <a href="{{route('admin.products.index')}}" class="btn btn-primary btn-sm"><i class="mdi mdi-arrow-left"></i> Kembali</a>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">
                    {{session('success')}}
                </div>
            @elseif (session('error'))
                <div class="alert alert-danger">
                    {{session('error')}}
                </div>
            @endif
            <form action="#" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="category_id" class="form-label"><span class="circle-icon">1</span> Kategori</label>
                            <select name="category_id" id="category_id" class="form-control select-2">
                                <option value="" disabled selected>-- Pilih Kategori --</option>
                                @foreach ($categories as $category)
                                    <option value="{{$category->nama}}" {{old('category_id') == $category->id ? 'selected' : ''}}>{{$category->nama}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('name'))
                                <small class="text-danger">{{$errors->first('name')}}</small>
                            @endif
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="brand_id" class="form-label"><span class="circle-icon">2</span> Brand</label>
                            <select name="brand_id" id="brand_id" class="form-control select-2">
                                <option value="" disabled selected>-- Pilih Kategori --</option>
                                @foreach ($categories as $category)
                                    <option value="{{$category->id}}" {{old('brand_id') == $category->id ? 'selected' : ''}}>{{$category->nama}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('name'))
                                <small class="text-danger">{{$errors->first('name')}}</small>
                            @endif
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="type_id" class="form-label"><span class="circle-icon">3</span> Tipe</label>
                            <select name="type_id" id="type_id" class="form-control select-2">
                                <option value="" disabled selected>-- Pilih Kategori --</option>
                                @foreach ($categories as $category)
                                    <option value="{{$category->id}}" {{old('type_id') == $category->id ? 'selected' : ''}}>{{$category->nama}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('name'))
                                <small class="text-danger">{{$errors->first('name')}}</small>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="table-responsive" data-pattern="priority-columns">
                            <table id="form" class="table table-striped">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Harga Supplier</th>
                                    <th>Harga Jual</th>
                                </tr>
                                </thead>
                                <tbody>
                             
                               
                                </tbody>
                            </table>
                        </div> <!-- end .table-responsive -->
                    </div>
                </div>
            </form>
        </div>
    </div>
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
        // jika category_id
        $('#category_id').on('change', function() {
            var category_id = $(this).val();
            $.ajax({
                url: "{{route('api.brands.category')}}",
                type: "GET",
                data: {
                    kategori: category_id
                },
                success: function(data) {
                    if (data.status == true) {
                        var html = '<option value="" disabled selected>-- Pilih Brand --</option>';
                        data.data.forEach(function(brand) {
                            html += '<option value="' + brand.nama + '">' + brand.nama + '</option>';
                        });
                        $('#brand_id').html(html);
                    }
                },
                error: function(err) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan!',
                    });
                },
                complete: function() {
                    $('.select-2').select2();
                }
            });
        });

        // jika brand_id
        $('#brand_id').on('change', function() {
            var brand_id = $(this).val();
            let category_id = $('#category_id').val();
            $.ajax({
                url: "{{route('api.types.category-brand')}}",
                type: "GET",
                data: {
                    brand: brand_id,
                    kategori: category_id
                },
                success: function(data) {
                    if (data.status == true) {
                        var html = '<option value="" disabled selected>-- Pilih Tipe --</option>';
                        data.data.types.forEach(function(type) {
                            html += '<option value="' + type.nama + '">' + type.nama + '</option>';
                        });
                        $('#type_id').html(html);
                    }
                },
                error: function(err) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan!',
                    });
                },
                complete: function() {
                    $('.select-2').select2();
                }
            });
        });

        // jika type_id
        $('#type_id').on('change', function() {
            var type_id = $(this).val();
            let category_id = $('#category_id').val();
            let brand_id = $('#brand_id').val();
            $.ajax({
                url: "{{route('api.products.category-brand-type')}}",
                type: "GET",
                data: {
                    brand: brand_id,
                    kategori: category_id,
                    tipe: type_id
                },
                success: function(data) {
                    if (data.status == true) {
                        var html = '';
                        data.data.forEach(function(product, index) {
                            html += '<tr>';
                            html += '<td>' + (index + 1) + '</td>';
                            html += '<td>' + product.nama + '</td>';
                            html += '<td><input type="hidden" name="id[]" value="' + product.id + '"><input type="text" name="harga_supplier[]" class="form-control" value="' + product.harga_supplier + '"></td>';
                            html += '<td><input type="text" name="harga[]" class="form-control" value="' + product.harga + '"></td>';
                            html += '</tr>';
                        });
                        html += '<tr>';
                        html += '<td colspan="10">';
                        html += '<button class="btn btn-danger w-100" type="submit" id="button-addon2"><i class="fa fa-save"></i> Simpan</button>';
                        html += '</td>';
                        html += '</tr>';
                        $('#form tbody').html(html);
                    }
                },
                error: function(err) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan!',
                    });
                },
                complete: function() {
                    $('.select-2').select2();
                }
            });
        });
        $('form').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data: $(this).serialize(),
                success: function(data) {
                    if (data.status == true) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: data.message,
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: data.message,
                        });
                    }
                },
                error: function(err) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan!',
                    });
                }
            });
        });
    });
</script>
@endsection