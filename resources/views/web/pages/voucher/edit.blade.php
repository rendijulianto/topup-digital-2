@extends('web.layout.app')
@section('breadcrumb')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">{{config('app.name')}}</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Halaman</a></li>
                    <li class="breadcrumb-item"><a href="{{route('vouchers.index')}}">Kelola Voucher</a></li>
                    <li class="breadcrumb-item active">Ubah</li>
                </ol>
            </div>
            <h4 class="page-title">Kelola Voucher</h4>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4 class="card-title">Ubah Voucher</h4>
                <a href="{{route('vouchers.index')}}" class="btn btn-primary btn-sm"><i class="mdi mdi-arrow-left"></i> Kembali</a>
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
                <form action="{{route('vouchers.update', $voucher->id)}}"
                    method="POST" enctype="multipart/form-data" class="row">
                    @csrf
                    @method('PUT')
                    <div class="mb-3 col-6">
                        <label for="brand_id" class="form-label">Brand</label>
                        <select name="brand_id" id="brand_id" class="form-control">
                            <option value=""
                                disabled selected
                            >-- Pilih Brand --</option>
                            @foreach ($brands as $brand)
                                <option value="{{$brand->brand_id}}" {{old('brand_id')??$voucher->brand_id == $brand->brand_id ? 'selected' : ''}}>
                                    {{$brand->name}}
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('brand_id'))
                            <small class="text-danger">{{$errors->first('brand_id')}}</small>
                        @endif
                    </div>
                    <div class="mb-3 col-6">
                        <label for="expired_at" class="form-label">Tanggal Expired</label>
                        <input type="date" name="expired_at" id="expired_at" class="form-control" placeholder="Tanggal Expired" value="{{old('expired_at') ?? $voucher->expired_at->format('Y-m-d')}}">
                        @if ($errors->has('expired_at'))
                            <small class="text-danger">{{$errors->first('expired_at')}}</small>
                        @endif
                    </div>
                    <div class="mb-3 col-6">
                        <label for="price" class="form-label">Harga</label>
                        <input type="text" name="price" id="price" class="form-control" placeholder="Harga voucher" value="{{old('price') ?? $voucher->price}}">
                        @if ($errors->has('price'))
                            <small class="text-danger">{{$errors->first('price')}}</small>
                        @endif
                    </div>
                    <div class="mb-3 col-6">
                        <label for="stock" class="form-label">Stok</label>
                        <input type="text" name="stock" id="stock" class="form-control" placeholder="Stok" value="{{old('stock') ?? $voucher->stock}}">
                        @if ($errors->has('stock'))
                            <small class="text-danger">{{$errors->first('stock')}}</small>
                        @endif
                    </div>
                    <div class="mb-3  col-12">
                        <button type="submit" class="btn btn-primary"><i class="mdi mdi-content-save"></i> Simpan</button>
                    </div>
                </form>
            </div>
        </div> <!-- end card -->
    </div> <!-- end col -->
</div>
@endsection
