@extends('web.layout.app')
@section('style')
<style>

</style>
@endsection
@section('breadcrumb')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Kelola Tipe</h4>
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
                    <i class="ri-list"></i>
                    Daftar Tipe</h4>

                    <button id="btnModalTambah" class="btn btn-secondary btn-sm"><i class="fa fa-plus"></i> Tambah</button>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="col-lg-6 mb-3 float-end">
                            <label class="control-label">Pencarian</label>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Masukkan kata kunci" aria-label="Masukkan kata kunci" 
                                aria-describedby="button-addon2" name="search" value="{{request()->search}}">
                                <button class="btn btn-primary" type="button">
                                    <i class="ri-search-line"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="responsive-table-plugin">
                            <div class="table-rep-plugin">
                                <div class="table-responsive" data-pattern="priority-columns">
                                    <table id="tech-companies-1" class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                       
                                            <th>Aksi</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse ($types as $type)
                                        <tr>
                                            <th>{{ ($loop->index + 1) + ($types->currentPage() - 1) * $types->perPage() }}</th>
                                            <td>{{$type->nama}}</td>
                                            <td>
                                                <a 
                                                onclick="handleEdit({{$type->id}})"
                                                class="btn btn-warning btn-sm"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Ubah"
                                                    ><i class="fa fa-edit"></i></a>
                                             
                                                <button
                                                    onclick="handleDelete({{$type->id}})"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Hapus"
                                                class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                            
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Tidak ada data</td>
                                        </tr>
                                        @endforelse
        
                                        </tbody>
                                    </table>
                                </div> <!-- end .table-responsive -->
        
                                {{$types->withQueryString()->links()}}
        
                            </div> <!-- end .table-rep-plugin-->
                        </div> <!-- end .responsive-table-plugin-->
                    </div>
                </div>
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
<script>
    const handleSearch = () => {
        const search = document.querySelector('input[name="search"]').value;
       
        window.location.href = `{{route('admin.types.index')}}?search=${search}`;
    }

    document.querySelector('input[name="search"]').addEventListener('keyup', (e) => {
        if (e.key === 'Enter') {
            handleSearch();
        }
    });

    $('#btnModalTambah').on('click', function() {
        $.get("{{route('admin.types.create')}}", function(data) {
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
        let url = `{{route('admin.types.edit', 'id')}}`;
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
                let url = `{{route('admin.types.destroy', 'id')}}`;
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
