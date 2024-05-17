@extends('web.layout.app')
@section('style')
<style>

</style>
@endsection
@section('breadcrumb')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Kelola Supplier</h4>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4 class="card-title">Daftar Supplier</h4>
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
                                    <th>Total Topup</th>
                                    <th>Total Sukses</th>
                                    <th>Persentase Sukses</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($suppliers as $supplier)
                                <tr>
                                    <th>{{$loop->iteration}}</th>
                                    <td>{{$supplier->nama}}</td>
                                    <td>{{number_format($supplier->total_transaksi)}}</td>
                                    <td>{{number_format($supplier->total_transaksi_berhasil)}}</td>
                                    <td>{{number_format($supplier->total_transaksi > 0 ? ($supplier->total_transaksi_berhasil / $supplier->total_transaksi) * 100 : 0, 2)}}%</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data</td>
                                </tr>
                                @endforelse

                                </tbody>
                            </table>
                        </div> <!-- end .table-responsive -->


                    </div> <!-- end .table-rep-plugin-->
                </div> <!-- end .responsive-table-plugin-->
            </div>
        </div> <!-- end card -->
    </div> <!-- end col -->
</div>
<!-- end row -->
@endsection
