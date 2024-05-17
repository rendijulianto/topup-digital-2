@extends('web.layout.app')
@section('style')
<style>

</style>
@endsection
@section('breadcrumb')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">{{config('app.name')}}</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Halaman</a></li>
                    <li class="breadcrumb-item active"><a href="{{route('admin.banners.index')}}">Kelola Banner</a></li>
                </ol>
            </div>
            <h4 class="page-title">Kelola Banner</h4>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        @if (session('success'))
            <div class="alert alert-success">
                {{session('success')}}
            </div>
        @elseif (session('error'))
            <div class="alert alert-danger">
                {{session('error')}}
            </div>
        @endif
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4 class="card-title"> <i class="ri-vip-crown-line"></i> Daftar Banner</h4>
                <a href="{{route('admin.banners.create')}}" class="btn btn-danger btn-sm"><i class="fa fa-plus"></i> Tambah</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="responsive-table-plugin">
                            <div class="table-rep-plugin">
                                <div class="table-responsive" data-pattern="priority-columns">
                                    <table id="tech-companies-1" class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Judul</th>
                                            <th>Banner</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse ($banners as $banner)
                                        <tr>
                                            <th>{{ ($loop->index + 1) + ($banners->currentPage() - 1) * $banners->perPage() }}</th>
                                            <td>{{$banner->judul}}</td>
                                            <td>
                                                <img src="{{asset('banners/'.$banner->gambar)}}" alt="" style="width: 300px">
                                            </td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" class="form-check-input" id="customSwitch1-{{$banner->id}}"
                                                    onClick="handleStatus('{{$banner->id}}', '{{$banner->status}}')"
                                                     {{$banner->status == 1 ? 'checked' : ''}} >
                                                 
                                                   
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{route('admin.banners.edit', $banner->id)}}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
                                                <form action="{{route('admin.banners.destroy', $banner->id)}}" method="post" class="d-inline">
                                                    @csrf
                                                    @method('delete')
                                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')"><i class="fa fa-trash"></i></button>
                                                </form>
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
        
                                {{$banners->withQueryString()->links()}}
        
                            </div> <!-- end .table-rep-plugin-->
                        </div> <!-- end .responsive-table-plugin-->
                    </div>
                </div>
            </div>
        </div> <!-- end card -->
    </div> <!-- end col -->
</div>
<!-- end row -->
@endsection
@section('script')
<script>
     const handleStatus = (id, status) => {
        let url = "{{route('admin.banners.updateStatus', ':id')}}"
        url = url.replace(':id', id)

        $.ajax({
            url: url,
            type: "POST",
            data: {
                id: id,
                status: status,
                _method: 'PUT'
            },
            headers: {
                'X-CSRF-TOKEN': `{{csrf_token()}}`
            },
            success: function(response) {
                if(response.status) {
                    Swal.fire(
                        'Berhasil!',
                        response.message,
                        'success'
                    );
                } else {
                    Swal.fire(
                        'Gagal!',
                        response.message,
                        'error'
                    );
                }
            },
            error: function(err) {
                Swal.fire(
                    'Gagal!',
                    'Terjadi kesalahan',
                    'error'
                );
            }
        })
    }  
</script>    
@endsection

