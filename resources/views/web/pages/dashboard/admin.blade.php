@extends('web.layout.app')
@section('breadcrumb')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Dashboard</h4>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <label class="control-label">Periode</label>
                <div id="reportrange" class="form-control">
                    <i class="fa fa-calendar"></i>&nbsp;
                    <span></span> <i class="fa fa-caret-down"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    <i class="ri-file-list-3-line"></i>
                    Laporan Transaksi - {{Carbon\Carbon::parse(request()->get('start'))->format('d M Y')}} s/d {{Carbon\Carbon::parse(request()->get('end'))->format('d M Y')}}</h4>
                </h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card widget-flat text-bg-purple">
                            <div class="card-body">
                                <div class="float-end">
                                    <i class="ri-wallet-2-line widget-icon"></i>
                                </div>
                                <h6 class="text-uppercase mt-0" title="Customers">
                                    Transaksi
                                </h6>
                                <h2 class="my-2">
                                    Rp {{number_format($reportAdmin['total_topup_sum'], 0, ',', '.')}}
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card widget-flat bg-primary text-white">
                            <div class="card-body">
                                <div class="float-end">
                                    <i class="ri-shopping-basket-line widget-icon"></i>
                                </div>
                                <h6 class="text-uppercase mt-0" title="Customers">
                                    Total
                                </h6>
                                <h2 class="my-2">
                                    {{number_format($reportAdmin['topup_count'], 0, ',', '.')}}
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card widget-flat bg-success text-white">
                            <div class="card-body">
                                <div class="float-end">
                                    <i class="ri-signal-tower-line widget-icon"></i>
                                </div>
                                <h6 class="text-uppercase mt-0" title="Customers">
                                    Profit
                                </h6>
                                <h2 class="my-2">
                                    Rp {{number_format($reportAdmin['profit'], 0, ',', '.')}}
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    <i class="ri-bar-chart-fill"></i>
                    &nbsp; "Top 10 Produk Terlaris "</h4>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-centered table-nowrap mb-0">
                        <thead>
                            <tr class="bg-light">
                                <th width="10%">No</th>
                                <th>Nama Produk</th>
                                <th>Terjual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $key => $product)
                            <tr>
                                <td>{{$key + 1}} </td>
                                <td>{{$product->name}}</td>
                                <td>{{$product->topup_count}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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
        window.location.href = "{{route('admin.dashboard')}}?start=" + start + "&end=" + end;
    });
});
</script>
@endsection
