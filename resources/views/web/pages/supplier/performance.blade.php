@extends('web.layout.app')
@section('style')
<style>

</style>
@endsection
@section('breadcrumb')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Laporan Performa Supplier</h4>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4 class="card-title">  <i class="fa fa-chart-line"></i> Daftar Supplier</h4>
            </div>
            <div class="card-body">
            <div class="row">
                <div class="col-lg-4 mb-1">
                <label class="control-label">Periode</label>
                        <div class="input-group">
                            <div id="reportrange" class="form-control">
                                <i class="fa fa-calendar"></i>&nbsp;
                                <span></span> <i class="fa fa-caret-down"></i>
                            </div>
                        </div>
                    </div>
                </div>
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
                                    <td>{{$supplier->name}}</td>
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
@section('script')
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select-2').select2();
    });
</script>
<script type="text/javascript">

    $(function() {
    
        var start = moment("{{$start}}",
         'YYYY-MM-DD');
        var end = moment("{{$end}}",
         'YYYY-MM-DD');
    
    
        function cb(start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
    
        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
               'Hari Ini': [moment(), moment()],
               'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               '7 Hari Lalu': [moment().subtract(6, 'days'), moment()],
               '30 Hari Lalu': [moment().subtract(29, 'days'), moment()],
               'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
               'Bulan Kemarin': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);
    
        cb(start, end);
        // onchange
        $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
            var start = picker.startDate.format('YYYY-MM-DD');
            var end = picker.endDate.format('YYYY-MM-DD');
            handleSearch();
        });


        const handleSearch = () => {
        
            const start = $('#reportrange').data('daterangepicker').startDate.format('YYYY-MM-DD');	
            const end = $('#reportrange').data('daterangepicker').endDate.format('YYYY-MM-DD');
        
            window.location.href = `{{ route('admin.suppliers.performance') }}?start=${start}&end=${end}`;
        }

       $('.select-2').on('change', function() {
           handleSearch();
       });

        // enter / button search
        document.querySelector('input[type="text"]').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                handleSearch();
            }
        });
         
    });
    </script>
@endsection

