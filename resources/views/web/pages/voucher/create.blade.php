@extends('web.layout.app')
@section('breadcrumb')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Aktivasi Voucher</h4>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4 class="card-title">Tambah Voucher</h4>
                <a href="{{route('vouchers.index')}}" class="btn btn-primary btn-sm"><i class="mdi mdi-arrow-left"></i> Kembali</a>
            </div>
            <div class="card-body">
                <form action="{{route('vouchers.store')}}" method="POST" enctype="multipart/form-data" class="row">
                    @csrf
                    <div class="mb-3 col-6">
                        <label for="brand_id" class="form-label">Brand</label>
                        <select name="brand_id" id="brand_id" class="form-control">
                            <option value=""
                                disabled selected
                            >-- Pilih Brand --</option>
                            @foreach ($brands as $brand)
                                <option value="{{$brand->brand_id}}" {{old('brand_id') == $brand->brand_id ? 'selected' : ''}}>{{$brand->name}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('brand_id'))
                            <small class="text-danger">{{$errors->first('brand_id')}}</small>
                        @endif
                    </div>
                    <div class="mb-3 col-6">
                        <label for="expired_at" class="form-label">Tanggal Expired</label>
                        <input type="date" name="expired_at" id="expired_at" class="form-control" placeholder="Tanggal Expired" value="{{old('expired_at')}}">
                        @if ($errors->has('expired_at'))
                            <small class="text-danger">{{$errors->first('expired_at')}}</small>
                        @endif
                    </div>
                    <div class="mb-3 col-6">
                        <label for="price" class="form-label">Harga</label>
                        <input type="text" name="price" id="price" class="form-control" placeholder="Harga voucher" value="{{old('price')}}">
                        @if ($errors->has('price'))
                            <small class="text-danger">{{$errors->first('price')}}</small>
                        @endif
                    </div>
                    <div class="mb-3 col-6">
                        <label for="stock" class="form-label">Stok</label>
                        <input type="text" name="stock" id="stock" class="form-control" placeholder="Stok" value="{{old('stock')}}">
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
