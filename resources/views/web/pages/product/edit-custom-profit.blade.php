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
                    <li class="breadcrumb-item"><a href="{{route('admin.products.index')}}">Kelola Produk</a></li>
                    <li class="breadcrumb-item active">Ubah</li>
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
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4 class="card-title">Ubah Produk</h4>
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
                
                <form action="{{route('admin.products.custom-profit.update', $product->id)}}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                     <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Batas Bawah</th>
                                <th>Batas Atas</th>
                                <th>Profit</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="profit">
                            @foreach ($profits as $profit)
                            <tr>
                               
                                <td>
                                    <input type="text" name="batas_bawah[{{$profit->id}}]" class="form-control" value="{{$profit->batas_bawah}}">
                                </td>
                                <td>
                                    <input type="text" name="batas_atas[{{$profit->id}}]" class="form-control" value="{{$profit->batas_atas}}">
                                </td>
                                <td>
                                    <input type="text" name="profit[{{$profit->id}}]" class="form-control" value="{{$profit->profit}}">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger"
                                    onclick="hapusProfit(`{{$profit->id}}`)"><i class="fa fa-trash"></i></button>
                                 

                                </td>
                            </tr>
                            @endforeach
                            <tr>
                                <td>
                                    <input type="text" name="batas_bawah[]" class="form-control">
                                </td>
                                <td>
                                    <input type="text" name="batas_atas[]" class="form-control">
                                </td>
                                <td>
                                    <input type="text" name="profit[]" class="form-control">
                                </td>
                                <td>
                                    
                                </td>
                            </tr>


                            {{-- <tr>
                                <td colspan="1">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-save
                                    "></i> Simpan</button>
                                </td>
                                <td colspan="4" class="text-right">
                                     <button type="button" onclick="tambahInput()" class="btn btn-success"><i class="fa fa-plus"></i> Tambah</button>
                                </td>
                            </tr> --}}
                        </tbody>
                    </table>   
                        {{-- tombolsimpan + tambah --}}
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>
                                Simpan</button> 
                            <button type="button" onclick="tambahInput()" class="btn btn-success"><i class="fa fa-plus"></i> Tambah</button>
                        </div>

                    </div>
                </form>
            </div>
        </div> <!-- end card -->
    </div> <!-- end col -->
</div>
@endsection
@section('script')
{{-- <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    function tambahInput() {
            $('#profit').append(`
                <tr>
                    <td>
                        <input type="text" name="batas_bawah[]" class="form-control">
                    </td>
                    <td>
                        <input type="text" name="batas_atas[]" class="form-control">
                    </td>
                    <td>
                        <input type="text" name="profit[]" class="form-control">
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
            `);
        }

        function hapusProfit(id) {
                let url = `{{route('admin.products.custom-profit.destroy', ':id')}}`;
                $.ajax({
                    url: url.replace(':id', id),
                    type: 'DELETE',
                    data: {
                        _token: `{{csrf_token()}}`
                    },
                    success: function(response) {
                        if (response.status == true) {
                            window.location.reload();
                        } else {
                            alert('Gagal menghapus data');
                        }
                    }
                });
        }
    $(document).ready(function() {
        $('#harga').mask('000.000.000', {reverse: true});
        $('#stok').mask('000.000.000', {reverse: true});
        $('.select-2').select2();
        $('#multi').on('change', function() {
            if ($(this).val() == 1) {
                $('#stok').val(999999999);
            } else {
                $('#stok').val(0);
            }
        });
        // tambahInput

    });
</script>
@endsection