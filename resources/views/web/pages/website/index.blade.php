@extends('web.layout.app')
@section('breadcrumb')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Pengaturan Website</h4>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="row">
    <div class="card">
        <div class="card-header">
            <h4>
            <i class="fa fa-cog"></i> 
            Pengaturan Website</h4>
        </div>
        <div class="card-body">
            <form action="{{route('admin.website.update', $website->id)}}" method="post">
                @method('put')
                @csrf
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Toko</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="{{$website->nama}}" required>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" required>{{$website->alamat}}</textarea>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="nomor_telepon" class="form-label">No. Telp</label>
                            <input type="text" class="form-control" id="nomor_telepon" name="nomor_telepon" value="{{$website->nomor_telepon}}" required>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="logo_website" class="form-label">Logo Sidebar</label>
                            <input class="form-control" type="file" id="logo_website" name="logo_website">
                            <img src="{{asset('storage/'.$website->logo_website)}}" alt="logo" class="img-thumbnail mt-2" style="width: 100px">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="logo_printer" class="form-label">Logo Printer</label>
                            <input class="form-control" type="file" id="logo_printer" name="logo_printer">
                            <img src="{{asset('storage/'.$website->logo_printer)}}" alt="logo printer" class="img-thumbnail mt-2" style="width: 100px">
                        </div>
                    </div>
                </div>
                
                
                <!-- button kanan -->
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- end row -->
@endsection
@section('script')
<script>
    $(document).ready(function() {
        $('form').submit(function(e) {
            e.preventDefault();
            let form = $(this);
            let url = form.attr('action');
            let method = form.attr('method');
            let data = new FormData(form[0]);

            $.ajax({
                url: url,
                method: method,
                data: data,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.status == true) {
                        Swal.fire({
                            title: 'Berhasil',
                            text: response.message,
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'OK'
                        });
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        Swal.fire({
                            title: 'Gagal',
                            text: response.message,
                            icon: 'error',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    //cek 422 returnnya array
                    // 401 message
                    if (xhr.status == 422) {
                        let errors = xhr.responseJSON.errors;
                        let message = '';
                        $.each(errors, function(index, value) {
                            message += value + '<br>';
                        });
                        Swal.fire({
                            title: 'Gagal',
                            html: message,
                            icon: 'error',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'OK'
                        });
                    } else {
                        Swal.fire({
                            title: 'Gagal',
                            text: xhr.responseJSON.message,
                            icon: 'error',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'OK'
                        });
                    }
                }
            });

        });
    });
</script>
@endsection