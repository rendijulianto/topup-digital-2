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
                    <li class="breadcrumb-item active">Kelola Pelanggan</li>
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
                <h4 class="card-title">Daftar Pelanggan</h4>
                <a href="{{route('admin.customers.create')}}" class="btn btn-danger btn-sm"><i class="fa fa-plus"></i> Tambah</a>
            </div>
            <div class="card-body">
                <div class="responsive-table-plugin">
                    <div class="table-rep-plugin">
                        <div class="table-responsive" data-pattern="priority-columns">
                            <table id="tech-companies-1" class="table table-striped">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Nomor</th>
                                    <th>Brand</th>
                                    <th>Aksi</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($customers as $customer)
                                <tr>
                                    <th>{{ ($loop->index + 1) + ($customers->currentPage() - 1) * $customers->perPage() }}</th>
                                    <td>{{$customer->nama}}</td>
                                    <td>{{$customer->nomor}}</td>
                                    <td>{{$customer->brand->nama}}</td>
                                    <td>
                                        <a href="{{route('admin.customers.edit',$customer->id)}}" class="btn btn-warning btn-sm"
                                            data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Ubah"
                                            ><i class="fa fa-edit"></i></a>
                                        <form action="{{route('admin.customers.destroy',$customer->id)}}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')"
                                            data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Hapus">

                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
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

                        {{$customers->withQueryString()->links()}}

                    </div> <!-- end .table-rep-plugin-->
                </div> <!-- end .responsive-table-plugin-->
            </div>
        </div> <!-- end card -->
    </div> <!-- end col -->
</div>
<!-- end row -->
@endsection
