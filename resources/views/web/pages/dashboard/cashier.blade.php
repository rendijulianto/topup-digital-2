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
                    <li class="breadcrumb-item active"><a href="{{route('cashier.dashboard')}}">Dashboard</a></li>
                </ol>
            </div>
            <h4 class="page-title">Dashboard</h4>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="row">

    <div class="col-sm-6">
        <div class="card widget-flat text-bg-purple">
            <div class="card-body">
                <div class="float-end">
                    <i class="ri-wallet-2-line widget-icon"></i>
                </div>
                <h6 class="text-uppercase mt-0" title="Customers">
                    Transaksi
                </h6>
                <h2 class="my-2">
                    Rp {{number_format($incomeToday, 2, ',', '.')}}
                </h2>
               
            </div>
        </div>
    </div> <!-- end col-->

    <div class="col-sm-6">
        <div class="card widget-flat text-bg-info">
            <div class="card-body">
                <div class="float-end">
                    <i class="ri-shopping-basket-line widget-icon"></i>
                </div>
                <h6 class="text-uppercase mt-0" title="Customers">Total</h6>
                <h2 class="my-2">
                    {{number_format($totalToday, 0, ',', '.')}}
                </h2>
               
            </div>
        </div>
    </div> <!-- end col-->
</div>
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <div class="card-widgets">
                    <a href="javascript:;" data-bs-toggle="reload"><i class="ri-refresh-line"></i></a>
                    <a data-bs-toggle="collapse" href="#weeklysales-collapse" role="button" aria-expanded="false" aria-controls="weeklysales-collapse"><i class="ri-subtract-line"></i></a>
                    <a href="#" data-bs-toggle="remove"><i class="ri-close-line"></i></a>
                </div>
                <h5 class="header-title mb-0">
                    Laporan Transaksi Mingguan
                </h5>

                <div id="weeklysales-collapse" class="collapse pt-3 show">
                    <div dir="ltr">
                        <div id="chart" class="apex-charts" data-colors="#3bc0c3,#1a2942,#d1d7d973"></div>
                    </div>
                </div>

            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <div class="card-widgets">
                    <a href="javascript:;" data-bs-toggle="reload"><i class="ri-refresh-line"></i></a>
                    <a data-bs-toggle="collapse" href="#weeklysales-collapse" role="button" aria-expanded="false" aria-controls="weeklysales-collapse"><i class="ri-subtract-line"></i></a>
                    <a href="#" data-bs-toggle="remove"><i class="ri-close-line"></i></a>
                </div>
                <h5 class="header-title mb-0">
                    Laporan Total Transaksi
                </h5>

                <div id="weeklysales-collapse" class="collapse pt-3 show">
                    <div dir="ltr">
                        <div id="chart-order" class="apex-charts" data-colors="#3bc0c3,#1a2942,#d1d7d973"></div>
                    </div>
                </div>

            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div>  <!-- end col-->
</div>
<!-- end row -->
@endsection
@section('script')
<!-- Apex Charts js -->
<script src="{{asset('assets/vendor/apexcharts/apexcharts.min.js')}}"></script>
<script>
    var options = {
        series: [
          {
            name: 'Pending',
            data: {!! json_encode($reportsSumPending->values()) !!},
        },
        {
            name: 'Berhasil',
            data: {!! json_encode($reportsSumSuccess->values()) !!},
        },
        {
            name: 'Gagal',
            data: {!! json_encode($reportsSumFailed->values()) !!},
        },
        
      ],
        chart: {
        type: 'bar',
        height: 350
      },
      plotOptions: {
        bar: {
          horizontal: false,
          columnWidth: '55%',
          endingShape: 'rounded'
        },
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        show: true,
        width: 2,
        colors: ['transparent']
      },
      xaxis: {
        categories: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
      },
      yaxis: {
        title: {
          text: 'Pemasukan'
        }
      },
      fill: {
        opacity: 1
      },
      tooltip: {
        y: {
          formatter: function (val) {
            return "Rp. " + new Intl.NumberFormat('id-ID').format(val) + " "
          }
        }
      },
      colors:['#3ca832', '#a89532', '#ff0303']

      };

      var chart = new ApexCharts(document.querySelector("#chart"), options);
      chart.render();
      var options = {
        series: [{
        name: 'Pending',
        data: {!! json_encode($reportsTotalPending->values()) !!},
      },
        {
            name: 'Berhasil',
            data: {!! json_encode($reportsTotalSuccess->values()) !!},
        },
        {
            name: 'Gagal',
            data: {!! json_encode($reportsTotalFailed->values()) !!},
        }
    ],
        chart: {
        type: 'bar',
        height: 350
      },
      plotOptions: {
        bar: {
          horizontal: false,
          columnWidth: '55%',
          endingShape: 'rounded'
        },
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        show: true,
        width: 2,
        colors: ['transparent']
      },
      xaxis: {
        categories: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
      },
      yaxis: {
        title: {
          text: 'Pemasukan'
        }
      },
      fill: {
        opacity: 1
      },
      tooltip: {
        y: {
          formatter: function (val) {
            return new Intl.NumberFormat('id-ID').format(val) + " "
          }
        }
      },
      colors:['#3ca832', '#a89532', '#ff0303']

      };

      var chart = new ApexCharts(document.querySelector("#chart-order"), options);
      chart.render();
  </script>
@endsection
