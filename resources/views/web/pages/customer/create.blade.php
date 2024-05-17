@extends('web.layout.app')
@section('breadcrumb')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">{{config('app.name')}}</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Halaman</a></li>
                    <li class="breadcrumb-item"><a href="{{route('admin.customers.index')}}">Kelola Pelanggan</a></li>
                    <li class="breadcrumb-item active">Tambah</li>
                </ol>
            </div>
            <h4 class="page-title">Kelola Pelanggan</h4>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4 class="card-title">Tambah Pelanggan</h4>
                <a href="{{route('admin.customers.index')}}" class="btn btn-danger btn-sm"><i class="fa fa-arrow-left"></i> Kembali</a>
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

                <form action="{{route('admin.customers.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" name="nama" id="nama" class="form-control" placeholder="Nama" value="{{old('nama')}}">
                                @if ($errors->has('nama'))
                                    <small class="text-danger">{{$errors->first('nama')}}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="nomor" class="form-label">Nomor</label>
                                <input type="text" name="nomor" id="nomor" class="form-control" placeholder="Nomor" value="{{old('nomor')}}">
                                @if ($errors->has('nomor'))
                                    <small class="text-danger">{{$errors->first('nomor')}}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="brand_id" class="form-label">Brand</label>
                                <select name="brand_id" id="brand_id" class="form-control">
                                    <option value="">Pilih Brand</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{$brand->id}}" {{old('brand_id') == $brand->id ? 'selected' : ''}}>{{$brand->nama}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('brand_id'))
                                    <small class="text-danger">{{$errors->first('brand_id')}}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <button type="submit" class="btn btn-danger"><i class="fa fa-save"></i> Simpan</button>
                    </div>
                </form>
            </div>
        </div> <!-- end card -->
    </div> <!-- end col -->
</div>
@endsection
