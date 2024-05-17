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
                    <li class="breadcrumb-item active">
                        Laporan Voucher 
                    </li>
                </ol>
            </div>
            <h4 class="page-title">
                <i class="ri ri-shopping-cart-2-line"></i>
                Laporan Voucher 
            </h4>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                <i class="fa fa-calendar"></i>
                    Laporan Voucher 
                </h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 mb-3">
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
                            <div class="col-lg-2 mb-1">
                                <label class="control-label">Brand</label>
                                <div class="input-group">
                                    <select class="form-control select-2" name="brand">
                                        <option>Semua</option>
                                        @foreach ($brands as $brand)
                                            <option 
                                                {{ request()->brand == $brand->id ? 'selected' : '' }}
                                            value="{{ $brand->id }}">{{ $brand->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 mb-1">
                                <label class="control-label">Tipe</label>
                                <div class="input-group">
                                    <select class="form-control select-2" name="type">
                                        <option>Semua</option>
                                        @foreach ($types as $type)
                                            <option 
                                                {{ request()->tipe == $type->id ? 'selected' : '' }}
                                            value="{{ $type->id }}">{{ $type->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 mb-1">
                                <label class="control-label">Produk</label>
                                <div class="input-group">
                                    <select class="form-control select-2" name="product">
                                        <option value="">Semua</option>
                                        @foreach ($products as $product)
                                            <option
                                            {{ request()->produk == $product->id ? 'selected' : '' }}
                                            value="{{ $product->id }}">{{ $product->nama }}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>
                            <div class="col-lg-2 mb-1">
                                <label class="control-label">Supplier</label>
                                <div class="input-group">
                                    <select class="form-control select-2" name="supplier">
                                        <option>Semua</option>
                                        @foreach ($suppliers as $supplier)
                                            <option
                                                {{ request()->supplier == $supplier->id ? 'selected' : '' }}
                                            value="{{ $supplier->id }}">{{ $supplier->nama }}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>
                            <div class="col-lg-3 mb-1">
                                <label class="control-label">Kasir</label>
                                <div class="input-group">
                                    <select class="form-control select-2" name="cashier">
                                        <option>Semua</option>
                                        @foreach ($users as $user)
                                            <option 
                                                {{ request()->kasir == $user->id ? 'selected' : '' }}
                                            value="{{ $user->id }}">{{ $user->nama }}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>
                            <div class="col-lg-3 mb-1">
                                <label class="control-label">Aktivator</label>
                                <div class="input-group">
                                    <select class="form-control select-2" name="activator">
                                        <option>Semua</option>
                                        @foreach ($users as $user)
                                            <option 
                                                {{ request()->aktivator == $user->id ? 'selected' : '' }}
                                            value="{{ $user->id }}">{{ $user->nama }}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>
                            <div class="col-lg-3 mb-1">
                                <label class="control-label">Status Transaksi</label>
                                {{-- input-group --}}
                                <div class="input-group">
                                    <select class="form-control select-2" name="status">
                                        <option>Semua</option>
                                        <option 
                                            {{ request()->status == 'sukses' ? 'selected' : '' }}
                                        value="sukses">Sukses</option>
                                        <option 
                                            {{ request()->status == 'pending' ? 'selected' : '' }}
                                        value="pending">Pending</option>
                                        <option 
                                            {{ request()->status == 'gagal' ? 'selected' : '' }}
                                        value="gagal">Gagal</option>
                                    </select>

                                </div>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <label class="control-label">Klik untuk mencari</label>
                                {{-- button --}}
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Cari Transaksi" name="search" value="{{ request()->search }}">
                                    <button class="btn btn-primary" type="button">
                                        <i class="ri-search-line"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-lg-12 mb-1">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="card d-flex border border-primary align-items-center justify-content-between p-2
                                         flex-row">
                                            <span>Total :</span> <b>Rp {{ number_format($totalOrder, 0, ',', '.') }}</b>
                                        </div>
                                    </div>        
                                    <div class="col-lg-4">
                                        <div class="card d-flex border border-primary align-items-center justify-content-between p-2
                                         flex-row">
                                            <span>Total Transaksi :</span> <b> {{number_format($totalTransaction, 0, ',', '.')}} Transaksi</b>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="card d-flex border border-primary align-items-center justify-content-between p-2
                                         flex-row">
                                            <span>Total Expired :</span> <b> {{number_format($totalTransaction, 0, ',', '.')}} Transaksi</b>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="card d-flex border border-primary align-items-center justify-content-between p-2
                                         flex-row">
                                            <span>Total Pending :</span> <b>Rp {{ number_format($totalPending, 0, ',', '.') }}
                                            <small>({{number_format($countPending, 0, ',', '.')}} Transaksi)</small>
                                            </b>
                                        </div>
                                    </div>                            
                                    <div class="col-lg-4">
                                        <div class="card d-flex border border-primary align-items-center justify-content-between p-2
                                         flex-row">
                                            <span>Total Sukses :</span> <b>Rp {{ number_format($totalSuccess, 0, ',', '.') }}
                                                <small>({{number_format($countSuccess, 0, ',', '.')}} Transaksi)</small>
                                            </b>
                                        </div>
                                    </div>                            
                                    <div class="col-lg-4">
                                        <div class="card d-flex border border-primary align-items-center justify-content-between p-2
                                         flex-row">
                                            <span>Total Gagal :</span> <b>Rp {{ number_format($totalFailed, 0, ',', '.') }}
                                                <small>({{number_format($countFailed, 0, ',', '.')}} Transaksi)</small>
                                            </b>
                                        </div>
                                    </div>                            
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th width="1%">No</th>
                                        <th width="20%">
                                            Produk</th>
                                        <th width="8%">
                                                Tujuan</th>
                                        <th width="5%">
                                            Harga</th>
                                        <th width="8%">
                                            Tanggal</th>
                                        <th width="12%">
                                        Status</th>
                                        <th width="10%">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($vouchers as $voucher)
                                        <tr>
                                            <td>
                                                #{{ $voucher->id  }}
                                            </td>
                                            <td>
                                                Layanan : {{ Str::replaceFirst('Aktivasi', '', $voucher->product_name) }} <br>
                                                Kategori : {{ $voucher->kategori->nama }} <br />
                                                Brand : {{ $voucher->brand->nama }}
                                            </td>
                                            <td>
                                                {{ $voucher->nomor }}
                                            </td>
                                            <td>
                                                 
                                                <span class="badge bg-primary">Beli Rp {{ number_format($voucher->harga_beli, 0, ',', '.') }}</span>
                                                <br>
                                                <span class="badge bg-success">Jual Rp {{ number_format($voucher->harga_jual, 0, ',', '.') }}</span>
                                            </td>
                                            <td>
                                                {{ $voucher->created_at }}
                                            </td>
                                            <td>
                                                {{-- input-group --}}
                                                <div class="input-group">
                                                    <select class="form-control">
                                                        <option 
                                                            {{ $voucher->status == 'sukses' ? 'selected' : '' }}
                                                        value="sukses">Sukses</option>
                                                        <option 
                                                            {{ $voucher->status == 'pending' ? 'selected' : '' }}
                                                        value="pending">Pending</option>
                                                        <option 
                                                            {{ $voucher->status == 'gagal' ? 'selected' : '' }}
                                                        value="gagal">Gagal</option>
                                                    </select>
                                                    <button class="btn btn-primary" type="button">
                                                        <i class="fa fa-caret-down"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="d-flex justify-content-between">
                                                <button class="btn btn-primary btn-sm" data-toggle="modal"
                                                data-id="{{ $voucher->id }}"
                                                onClick="handleDetail(this)">
                                                    <i class="ri-eye-line"></i> Detail
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">
                                                <h4>Belum ada data</h4>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            {{ $vouchers->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end card -->
    </div> <!-- end col -->
</div>
<!-- end row -->
<div class="modal fade" id="modal-detail" tabindex="-1" role="dialog" aria-labelledby="topupDetail" aria-hidden="true">
    <div class="modal-dialog modal-full-width modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="topupDetail">
                    <i class="ri-information-line"></i>
                    Detail Pesanan
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @include('web.pages.topup.show')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal" aria-label="Close">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
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
  function handleDetail (e) {
        let id = $(e).data('id');
        let url = `{{ route('api.topups.show', ':id') }}`;
        url = url.replace(':id', id);
        $.ajax({
            url: url,
            type: "GET",
            data: {
                topup_id: id
            },
            beforeSend: function () {
                Swal.fire({
                    title: 'Mohon Tunggu',
                    html: 'Sedang memproses...',
                    didOpen: () => {
                        Swal.showLoading()
                    },
                })
            },
            success: function (response) {
                $('#modal-detail').modal('show');
                if(response.data.tipe == "e_wallet") {
                    let html = `<div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <td style="font-weight:bold">Nama Pelanggan</td>
                                    <td style="font-weight:bold">Nomor</td>
                                </tr>
                                <tr>
                                    <td>${response.data.detail.nama_pelanggan}</td>
                                    <td>${response.data.nomor}</td>
                                </tr>
                            </thead>
                        </table>
                    </div>`;
                    $('#detail_number').html(html);
                } else if(response.data.tipe == "token_listrik") {
                    let html = `<div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <td>ID Pelanggan</td>
                                    <td>Nomor Meter</td>
                                    <td>Nama Pelanggan</td>
                                    <td>Segment Power</td>
                                    <td>Nomor</td>
                                </tr>
                                <tr>
                                    <td>${response.data.detail.id_pelanggan}</td>
                                    <td>${response.data.detail.nomor_meter}</td>
                                    <td>${response.data.detail.nama_pelanggan}</td>
                                    <td>${response.data.detail.segment_power}</td>
                                    <td>${response.data.nomor}</td>
                                </tr>
                            </thead>
                    
                        </table>
                    </div>`;
                    $('#detail_number').html(html);
                } else {
                    $('#detail_number').html(`<div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                
                                    <td style="font-weight:bold">Nomor</td>
                                </tr>
                                <tr>
                                  
                                    <td>${response.data.nomor}</td>
                                </tr>
                            </thead>

                        </table>
                    </div>`);
                }
                $('#detail_product').html(response.data.produk);
                $('#detail_price').html(`<span class="badge bg-success">${response.data.harga}</span>`);
                $('#detail_sn').html(response.data.keterangan);
                $('#detail_status').html(response.data.status);
                $('#detail_date').html(response.data.tgl_transaksi);
                $('#detail_whatsapp').html(`<a href="https://wa.me/${response.data.whatsapp}" nomor="_blank" class="btn btn-success btn-sm">
                        <i class="ri-whatsapp-fill"></i>
                        Kirim Pesan
                    </a>`);
                $('#detail_logs').html('');
                $.each(response.data.logs, function (index, value) {
                    $('#detail_logs').append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td colspan="2">${value.supplier}</td>
                            <td>${value.message}</td>
                            <td>${value.date}</td>
                            <td>
                                <div class="input-group">
                                    <select class="form-control" name="status" onchange="handleApiStatus(this, '${value.id}')">
                                        <option 
                                            ${value.status == 'sukses' ? 'selected' : ''}
                                        value="sukses">Sukses</option>
                                        <option 
                                            ${value.status == 'pending' ? 'selected' : ''}
                                        value="pending">Pending</option>
                                        <option 
                                            ${value.status == 'gagal' ? 'selected' : ''}
                                        value="gagal">Gagal</option>
                                    </select>
                                    <button class="btn btn-primary" type="button">
                                        <i class="fa fa-caret-down"></i>
                                    </button>
                                </div>
                                </td>
                            <td>
                                ${value.cashier ? value.cashier : '-'}
                            </td>
                        </tr>
                    `);
                });
            },
            error: function (xhr, ajaxOptions, thrownError) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: xhr.responseJSON.message,
                })
            },
            complete: function () {
                Swal.close();
            }
        });
    }

    function handleStatus (e, id) {
        let status = $(e).val();
        let url = `{{ route('api.topups.updateStatus', ':id') }}`;
        url = url.replace(':id', id);
        $.ajax({
            url: url,
            type: "PUT",
            headers: {
                'X-CSRF-TOKEN': `{{ csrf_token() }}`
            },
            data: {
                status: status
            },
            beforeSend: function () {
                Swal.fire({
                    title: 'Mohon Tunggu',
                    html: 'Sedang memproses...',
                    didOpen: () => {
                        Swal.showLoading()
                    },
                })
            },
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message,
                }).then(() => {
                    window.location.reload();
                });
            },
            error: function (xhr, ajaxOptions, thrownError) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: xhr.responseJSON.message,
                })
            },
            complete: function () {
                // Swal.close();
            }
        });
    }

    function handleApiStatus (e, id) {
        let status = $(e).val();
        let url = `{{ route('api.topups.updateApiStatus', ':id') }}`;
        url = url.replace(':id', id);
        $.ajax({
            url: url,
            type: "PUT",
            headers: {
                'X-CSRF-TOKEN': `{{ csrf_token() }}`
            },
            data: {
                status: status
            },
            beforeSend: function () {
                Swal.fire({
                    title: 'Mohon Tunggu',
                    html: 'Sedang memproses...',
                    didOpen: () => {
                        Swal.showLoading()
                    },
                })
            },
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message,
                }).then(() => {
                    window.location.reload();
                });
            },
            error: function (xhr, ajaxOptions, thrownError) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: xhr.responseJSON.message,
                })
            },
            complete: function () {
                // Swal.close();
            }
        });
    }
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
            const search = document.querySelector('input[type="text"]').value;
            const start = $('#reportrange').data('daterangepicker').startDate.format('YYYY-MM-DD');	
            const end = $('#reportrange').data('daterangepicker').endDate.format('YYYY-MM-DD');
            const status = document.querySelector('select[name="status"]').value;
            const product = document.querySelector('select[name="product"]').value;
            const cashier = document.querySelector('select[name="cashier"]').value;
            const supplier = document.querySelector('select[name="supplier"]').value;
            const brand = document.querySelector('select[name="brand"]').value;
            const type = document.querySelector('select[name="type"]').value;
            const activator = document.querySelector('select[name="activator"]').value;

            window.location.href = `{{ route('admin.vouchers.index') }}?search=${search}&start=${start}&end=${end}&status=${status}&produk=${product}&kasir=${cashier}&supplier=${supplier}&brand=${brand}&tipe=${type}&aktivator=${activator}`;
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
