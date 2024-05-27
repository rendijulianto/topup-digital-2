@extends('web.layout.app')
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection
@section('breadcrumb')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">
               Daftar Aktivasi Voucher Fisik
            </h4>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4 class="card-title">Daftar Voucher</h4>
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
                            <div class="col-lg-6">
                                <label class="control-label">Filter Tanggal Berdasarkan</label>
                                <select class="form-control select-2" name="filter_date">
                                    <option value="">Semua</option>
                                    <option 
                                        {{request()->filter_date == 'tgl_transaksi' ? 'selected' : ''}}
                                    value="tgl_transaksi">Tgl Transaksi</option>
                                    <option 
                                        {{request()->filter_date == 'tgl_kadaluwarsa' ? 'selected' : ''}}
                                    value="tgl_kadaluwarsa">Kadaluwarsa</option>
                                    <option 
                                        {{request()->filter_date == 'created_at' ? 'selected' : ''}}
                                    value="created_at">Dibuat</option>
                                </select>
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
                                    <th>ID</th>
                                    <th>Nomor Seri</th>
                                    <th>Produk</th>
                                    <th>
                                        Harga
                                    </th>
                                    <th>
                                        Status
                                    </th>
                                    <th>
                                        Tgl Aktivasi
                                    </th>
                                    <th>
                                        Aksi
                                    </th>
                            
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($vouchers as $voucher)
                                <tr>
                                    <th>{{ $voucher->id }}</th>
                                    <td>{{$voucher->nomor}}</td>
                                    <td>{{$voucher->produk->nama}}</td>
                                    <td>
                                        Rp. {{number_format($voucher->harga_beli,0,',','.')}}
                                    </td>
                                    <td>
                                        {{Str::ucfirst($voucher->status)}}
                                    </td>
                                    <td>
                                        {{$voucher->updated_at}}
                                    </td>
                                    <td>
                                            <button type="button" class="btn btn-sm btn-primary mb-2" 
                                                data-id="{{ $voucher->id }}"
                                                onClick="handleDetail(this)"
                                                >
                                                    <i class="fa fa-eye"></i>
                                                    Detail 
                                                </button>
                                    </td>
                                
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center">Tidak ada data</td>
                                </tr>
                                @endforelse

                                </tbody>
                            </table>
                        </div> <!-- end .table-responsive -->

                        {{$vouchers->withQueryString()->links()}}

                    </div> <!-- end .table-rep-plugin-->
                </div> <!-- end .responsive-table-plugin-->
                    </div>
                </div>
            </div>
        </div> <!-- end card -->
    </div> <!-- end col -->
</div>
<!-- end row -->
<div class="modal fade" id="modal-detail" tabindex="-1" role="dialog" aria-labelledby="topupDetail" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="topupDetail">
                    <i class="ri-information-line"></i>
                    Detail Transaksi
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detail_form">
         
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal" aria-label="Close">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
{{-- detail modal --}}
@endsection
@section('script')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select-2').select2();
    
    });
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
        const filter_date = document.querySelector('select[name="filter_date"]').value;
        window.location.href = `{{ route('injector.vouchers.index') }}?search=${search}&start=${start}&end=${end}&status=${status}&produk=${product}&filter_date=${filter_date}`;
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
<script>
     function cekStatus()
    {
        $.ajax({
            url: "{{route('cron.update-topup')}}",
            type: "GET",
            beforeSend: function() {
                Swal.fire({
                    title: 'Loading...',
                    html: 'Sedang memproses...',
                    allowOutsideClick: false,
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                });
            },
            success: function(res) {
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Pesanan berhasil di update',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    location.reload();
                });
            },
            error: function(err) {
                Swal.fire({
                    title: 'Perhatian!',
                    text: 'Gagal update pesanan',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
            },
            
        });
    }
     function checkStatus (id, status) {
        let url = `{{ route('cashier.topups.check.status', ':id') }}`;
        url = url.replace(':id', id);
        $.ajax({
            url: url,
            type: "GET",
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
                if (response.data.status.toLowerCase() != status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.data.message,
                    }).then((result) => {
                        location.reload();
                    })
                } else {
                    Swal.fire({
                        icon: 'info',
                        title: 'Info',
                        text: response.data.message,
                    })
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: xhr.responseJSON.message,
                })
            }
        });
    }

    function handleDetail (e) {
        let id = $(e).data('id');
        let url = `{{ route('api.topups.show', ':id') }}`;
        url = url.replace(':id', id);
        $.ajax({
            url: url,
            type: "GET",
            data: {
                topup_id: id,
                _token: "{{ csrf_token() }}",
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
                let html = `
                <div class="row">
                    
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <label for="detail_nomor" class="form-label">Nomor</label>
                            <input type="text" class="form-control" id="detail_nomor" readonly value="${response.data.nomor}" readonly>
                        </div>
                    </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="detail_produk" class="form-label">Produk</label>
                        <input type="text" class="form-control" id="detail_produk" readonly value="${response.data.produk}">
                    </div>
                </div>
                   
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="detail_harga" class="form-label">Harga</label>
                            <input type="text" class="form-control" id="detail_harga" readonly value="${response.data.harga_beli}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="detail_keterangan" class="form-label">Keterangan</label>
                            <input type="text" class="form-control" id="detail_keterangan" readonly value="${response.data.keterangan}">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="detail_status" class="form-label">Status</label>
                            <input type="text" class="form-control" id="detail_status" readonly value="${response.data.status}">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="detail_tgl_transaksi" class="form-label">Tgl Transaksi</label>
                            <input type="datetime" class="form-control" id="detail_tgl_transaksi" readonly value="${response.data.tgl_transaksi}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="detail_kasir" class="form-label">Kasir</label>
                            <input type="text" class="form-control" id="detail_kasir" readonly value="${response.data.kasir}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="detail_tgl_expired" class="form-label">TGL EXPIRED</label>
                            <input type="text" class="form-control" id="detail_tgl_expired" readonly value="${response.data.detail.tgl_kadaluwarsa}">
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <span>
                            <i class="fa fa-list"></i>
                            Daftar Aksi Topup
                        </span>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th width="10%">No</th>
                                <th>Supplier</th>
                                <th>Keterangan</th>
                                <th>Waktu</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="detail_logs">
                        `
                        $.each(response.data.logs, function (index, value) {
                            html = html + `
                            <tr>
                                <td>
                                    ${index + 1}
                                <td>
                                    ${value.supplier}
                                </td>
                                <td>
                                    ${value.keterangan}
                                </td>
                                <td>
                                    ${value.waktu}
                                </td>
                                <td>
                                    ${value.status}
                                </td>
                            </tr>
                            `;
                        });
                        html = html + `
                        </tbody>
                    </table>
                    </div>
                    </div>
                </div>`;
                
                $('#detail_form').html(html);
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

</script>
@endsection