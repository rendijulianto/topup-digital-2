@extends('web.layout.app')
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection
@section('breadcrumb')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">
                Laporan Transaksi
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
                <i class="fa fa-chart-line"></i>
                Laporan Transaksi
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
                                <label class="control-label">Kategori</label>
                                <div class="input-group">
                                    <select class="form-control select-2" name="category">
                                        <option>Semua</option>
                                        @foreach ($categories as $category)
                                            <option 
                                                {{ request()->category == $category->id ? 'selected' : '' }}
                                            value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
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
                                            value="{{ $brand->id }}">{{ $brand->name }}</option>
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
                                                {{ request()->type == $type->id ? 'selected' : '' }}
                                            value="{{ $type->id }}">{{ $type->name }}</option>
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
                                            value="{{ $product->id }}">{{ $product->name }}</option>
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
                                                {{ request()->cashier == $user->id ? 'selected' : '' }}
                                            value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>
                            <div class="col-lg-3 mb-1">
                                <label class="control-label">Supplier</label>
                                <div class="input-group">
                                    <select class="form-control select-2" name="supplier">
                                        <option>Semua</option>
                                        @foreach ($suppliers as $supplier)
                                            <option
                                                {{ request()->supplier == $supplier->id ? 'selected' : '' }}
                                            value="{{ $supplier->id }}">{{ $supplier->name }}</option>
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
                                            <span>Keuntungan :</span> <b>Rp {{ number_format($totalProfit, 0, ',', '.') }}</b>
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
                                    @forelse ($topups as $topup)
                                        <tr>
                                            <td>
                                                #{{ $topup->id  }}
                                            </td>
                                            <td>
                                                Layanan : {{ Str::replaceFirst('Aktivasi', '', $topup->product->name) }} <br>
                                                Kategori : {{ $topup->category->name }} <br />
                                                Brand : {{ $topup->brand->name }} <br />
                                                Kasir : {{ $topup->cashier ? $topup->cashier->name : '-' }} <br />
                                                Supplier : {{ $topup->topup_api()->count() > 0 ? $topup->topup_api->where('status', 'sukses')->first()->supplier->name : '-' }}
                                            </td>
                                            <td>
                                                {{ $topup->target }}
                                            </td>
                                            <td>
                                                 
                                                <span class="badge bg-primary">Beli Rp {{ number_format($topup->price_buy, 0, ',', '.') }}</span>
                                                <br>
                                                <span class="badge bg-success">Jual Rp {{ number_format($topup->price_sell, 0, ',', '.') }}</span>
                                            </td>
                                            <td>
                                                {{ $topup->transacted_at }}
                                            </td>
                                            <td>
                                                {{-- input-group --}}
                                                <div class="input-group">
                                                    <select class="form-control" name="status" onchange="handleStatus(this, '{{ $topup->id }}')">
                                                        <option 
                                                            {{ $topup->status == 'sukses' ? 'selected' : '' }}
                                                        value="sukses">Sukses</option>
                                                        <option 
                                                            {{ $topup->status == 'pending' ? 'selected' : '' }}
                                                        value="pending">Pending</option>
                                                        <option 
                                                            {{ $topup->status == 'gagal' ? 'selected' : '' }}
                                                        value="gagal">Gagal</option>
                                                    </select>
                                                    <button class="btn btn-primary" type="button">
                                                        <i class="fa fa-caret-down"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="d-flex justify-content-between">
                                                <button class="btn btn-primary btn-sm" data-toggle="modal"
                                                data-id="{{ $topup->id }}"
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
                            {{ $topups->withQueryString()->links() }}
                        </div>
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
                    
                    `;
                if(response.data.tipe == "e_wallet" || response.data.tipe == "token_listrik") {
                html = html + `
                <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="detail_nomor" class="form-label">Nomor</label>
                            <input type="text" class="form-control" id="detail_nomor" readonly value="${response.data.target}" readonly>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="detail_name_pelanggan" class="form-label">Nama Pelanggan</label>
                            <input type="text" class="form-control" id="detail_name_pelanggan" readonly value="${response.data.detail.customer_name}">
                        </div>
                    </div>
                    
                    `;
                } else {
                    html = html + `
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <label for="detail_nomor" class="form-label">Nomor</label>
                            <input type="text" class="form-control" id="detail_nomor" readonly value="${response.data.target}" readonly>
                        </div>
                    </div>
                    `;
                }
                html = html + `
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="detail_product" class="form-label">Produk</label>
                        <input type="text" class="form-control" id="detail_product" readonly value="${response.data.product}">
                    </div>
                </div>
                   
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="detail_harga" class="form-label">Harga</label>
                            <input type="text" class="form-control" id="detail_harga" readonly value="${response.data.price_sell}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="detail_keterangan" class="form-label">Keterangan</label>
                            <input type="text" class="form-control" id="detail_keterangan" readonly value="${response.data.note}">
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
                            <input type="datetime" class="form-control" id="detail_tgl_transaksi" readonly value="${response.data.transacted_at}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="detail_whatsapp" class="form-label">Nomor Whatsapp</label>
                            <input type="number" class="form-control" id="detail_whatsapp" readonly value="${response.data.whatsapp}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="detail_kasir" class="form-label">Kasir</label>
                            <input type="text" class="form-control" id="detail_kasir" readonly value="${response.data.cashier}">
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
                                <th>Pembuat</th>
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
                                    ${value.note}
                                </td>
                                <td>
                                    ${value.created_at}
                                </td>
                                <td>
                                    ${value.status}
                                </td>
                                <td>
                                    ${value.user}
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


    function handleStatus(e, id) {
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
            const category = document.querySelector('select[name="category"]').value;
            const status = document.querySelector('select[name="status"]').value;
            const product = document.querySelector('select[name="product"]').value;
            const cashier = document.querySelector('select[name="cashier"]').value;
            const supplier = document.querySelector('select[name="supplier"]').value;
            const brand = document.querySelector('select[name="brand"]').value;
            const type = document.querySelector('select[name="type"]').value;

            window.location.href = `{{ route('admin.topups.index') }}?search=${search}&start=${start}&end=${end}&category=${category}&status=${status}&produk=${product}&cashier=${cashier}&supplier=${supplier}&brand=${brand}&type=${type}`;
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
