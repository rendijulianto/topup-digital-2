@extends('web.layout.app')
@section('breadcrumb')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">{{config('app.name')}}</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Halaman</a></li>
                    <li class="breadcrumb-item"><a href="{{route('admin.banners.index')}}">Kelola Brand</a></li>
                    <li class="breadcrumb-item active">Tambah</li>
                </ol>
            </div>
            <h4 class="page-title">Kelola Brand</h4>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4 class="card-title">Tambah Brand</h4>
                <a href="{{route('admin.banners.index')}}" class="btn btn-danger btn-sm"><i class="fa fa-arrow-left"></i> Kembali</a>
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

                <form action="{{route('admin.banners.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="judul" class="form-label">Nama</label>
                        <input type="text" name="judul" id="judul" class="form-control @error('judul') is-invalid @enderror" placeholder="Masukkan judul"
                        value="{{old('judul')}}">
                        @if ($errors->has('judul'))
                            <small class="text-danger">{{$errors->first('judul')}}</small>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="gambar" class="form-label">Logo</label>
                        <input type="file" name="gambar" id="gambar" class="form-control @error('gambar') is-invalid @enderror" placeholder="Masukkan gambar" accept="image/*" />
                        @if ($errors->has('gambar'))
                            <small class="text-danger">{{$errors->first('gambar')}}</small>
                        @endif
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
