@extends('web.layout.app')
@section('breadcrumb')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Ubah Profil</h4>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="row">
    <div class="card">
        <div class="card-header">
            <h4>Ubah Profil</h4>
        </div>
        <div class="card-body">
            <form action="{{route('profile.update')}}" method="post">
                @method('put')
                @csrf
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="nama" name="nama" value="{{$user->nama}}" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{$user->email}}" disabled>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="kata_sandi_baru" class="form-label">Kata sandi baru</label>
                            <input type="password" class="form-control" id="kata_sandi_baru" name="kata_sandi_baru" placeholder="Kosongkan jika tidak ingin mengganti kata sandi">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="kata_sandi" class="form-label">Kata Sandi Lama</label>
                            <input type="password" class="form-control" id="kata_sandi" name="kata_sandi" placeholder="Kosongkan jika tidak ingin mengganti kata sandi">
                            <small class="text-danger">*Kosongkan jika tidak ingin mengganti kata sandi</small>
                        </div>
                    </div>
                </div>
                @if ($user->jabatan != 'admin')
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="pin_baru" class="form-label">Pin Baru</label>
                            <input type="password"
                            maxlength="6"
                            onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                            class="form-control" id="pin_baru" name="pin_baru" placeholder="Kosongkan jika tidak ingin mengganti pin">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="pin" class="form-label">Pin Lama</label>
                            <input type="password" class="form-control"
                            maxlength="6"
                            onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                             id="pin" name="pin" placeholder="Kosongkan jika tidak ingin mengganti pin">
                            <small class="text-danger">*Kosongkan jika tidak ingin mengganti pin</small>
                        </div>
                    </div>
                </div>
                @endif
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
            let data = form.serialize();

            $.ajax({
                url: url,
                method: method,
                data: data,
                success: function(response) {
                    if (response.status == 'success') {
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