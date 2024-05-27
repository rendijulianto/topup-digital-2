@extends('web.layout.app')
@section('title', 'Daftar Transaksi')
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection
@section('breadcrumb')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">
                <i class="ri ri-shopping-cart-2-line"></i>
                Halaman Daftar Transaksi
            </h4>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card widget-flat text-bg-info">
            <div class="card-body">
                <div class="float-end">
                    <i class="ri-exchange-dollar-line
                    widget-icon"></i>
                </div>
                <h6 class="text-uppercase mt-0">Sisa Saldo <i 
                    id="show_hide" class="fa fa-eye"></i></h6>
                <h2 class="my-2" id="deposit">-</h2>
               
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>
                    <i class="ri ri-shopping-cart-2-line"></i>
                    Riwayat Transaksi
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
                                    <div class="col-lg-4">
                                        <button class="btn btn-primary" style="width: 100%"
                                         id="start-transaction" type="button" >
                                            <i class="ri ri-computer-line"></i>
                                            Mulai Transaksi
                                        </button>
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
                                        <th width="1%">ID Transaksi</th>
                                        <th width="15%">
                                            Target
                                        </th>
                                        <th width="10%">Harga</th>
                                        <th width="8%">
                                            Tanggal</th>
                                        <th width="10%">
                                        Status</th>
                                        <th width="15%">
                                            Aksi
                                            <i class="mdi mdi-information-outline" data-toggle="tooltip" data-placement="top" title="Informasi khusus untuk pemesanan yang cancel atau gagal perhari ini bisa dilakukan re topup"></i>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($topups as $topup)
                                        <tr data-id="{{ $topup->id }}">
                                            <td>
                                                #{{$topup->id}}
                                            </td>
                                            <td>
                                                Layanan : {{ $topup->product_name }} <br>
                                                Nomor : {{ $topup->nomor }} <br> 
                                            </td>
                                            <td>
                                                <b>Rp {{ number_format($topup->harga_jual, 0, ',', '.') }}</b>
                                            </td>
                                            <td>
                                                {{ $topup->tgl_transaksi }}
                                            </td>
                                            <td>
                                                {!!$topup->status_html!!}
                                            </td>
                                            <td>
                                                <div id="btnStatus_{{ $topup->id }}">
                                               
                                                @if($topup->status == "gagal" && $topup->created_at->diffInDays(today()) <= 1)
                                                <button type="button" class="btn btn-sm btn-danger mb-2" data-toggle="modal" 
                                                data-topup_number="{{ $topup->nomor }}"
                                                data-topup_product_name="{{ $topup->product_name }}"
                                                data-topup_price="Rp {{ number_format($topup->harga_jual, 0, ',', '.') }}"
                                                data-topup_id="{{ $topup->id }}"
                                                data-topup_customer_name="{{ $topup->customer_name }}"
                                                data-topup_type="{{ $topup->tipe }}"
                                                onclick="handleConfirmTopup(this)">
                                                    <i class="fa fa-redo"></i>
                                                  Kirim Ulang
                                                    
                                                </button>
                                                @elseif ($topup->status == "pending" && ($topup->harga_beli == null OR $topup->tipe_produk->nama == "Custom"))
                                                    <button type="button" class="btn btn-sm btn-success mb-2" data-toggle="modal" 
                                                    data-topup_number="{{ $topup->nomor }}"
                                                    data-topup_product_name="{{ $topup->product_name }}"
                                                    data-topup_price="Rp {{ number_format($topup->harga_jual, 0, ',', '.') }}"
                                                    data-topup_id="{{ $topup->id }}"
                                                    data-topup_type="{{ $topup->tipe }}"
                                                    data-topup_customer_name="{{ $topup->customer_name }}"
                                                    onclick="handleConfirmTopup(this)">
                                                        <i class="fa fa-check"></i>
                                                        Terima
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-danger mb-2" data-toggle="tooltip" data-placement="top" title="Batal" onclick="handleClickCancel(this)" data-id="{{ $topup->id }}">
                                                        <i class="fa fa-times"></i>
                                                        Batal
                                                    </button>
                                                @elseif ($topup->status == "pending")
                                                    <button type="button" class="btn btn-sm btn-success mb-2" onClick="handleCheckStatus('{{ $topup->id }}', '{{ $topup->status }}')">
                                                        <i class="fa fa-refresh"></i>
                                                        Cek Status</button>
                                                @endif
                                                </div>
                                                <button type="button" class="btn btn-sm btn-primary mb-2" 
                                                data-id="{{ $topup->id }}"
                                                onClick="handleDetail(this)"
                                                >
                                                    <i class="fa fa-eye"></i>
                                                    Detail 
                                                </button>
                                                <button type="button" class="btn btn-sm btn-success mb-2" 
                                                data-id="{{ $topup->id }}"
                                                onClick="handleCetak(this)">
                                                    <i class="fa fa-print"></i>
                                                    Cetak 
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
 <!--  Modal content for the Large example -->
<div class="modal fade" id="modal-confirm" tabindex="-1" role="dialog" aria-labelledby="topupConfirm" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="topupConfirm">
                    <i class="ri-information-line"></i> Konfimasi Topup
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="confirm_form">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" id="btnCreateTopup">
                    <i class="fa fa-check"></i>
                    Konfirmasi Topup
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- /.modal -->
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
<script src="https://cdn.socket.io/4.5.0/socket.io.min.js" integrity="sha384-7EyYLQZgWBi67fBtVxw60/OWl1kjsfrPFcaU0pp0nAh+i8FD068QogUvg85Ewy1k" crossorigin="anonymous">
</script> 

<script>             
    $(function(){      

    let socket = io('https://socket.kebutuhansosialmedia.com');     
    socket.on('connect', function(){
        console.log('connected');
    });              
    socket.on('update-topup', function(data){
        let dataTopup = data.data;
        console.log(dataTopup);
        let topup = $(`tr[data-id="${dataTopup.data.id}"]`);
        console.log(topup.length);
        if(topup.length > 0) {
            console.log('Stauts updated ' + dataTopup.data.id);
            // jika status mengandung berhasil
            if(dataTopup.data.status.includes('Sukses')) {
                $('#btnStatus_' + dataTopup.data.id).html(``);
                $.toast({
                    title: 'Topup Berhasil',
                    subtitle: new Date().toLocaleString(),
                    content: 'Berhasil melakukan topup ' + dataTopup.data.id,
                    type: 'success',
                    delay: 5000
                });
                // https://dm0qx8t0i9gc9.cloudfront.net/previews/audio/BsTwCwBHBjzwub4i4/audioblocks-correct-answer-hint-accept-5_HFD0bIADI_NWM.mp3
                let audio = new Audio('https://dm0qx8t0i9gc9.cloudfront.net/previews/audio/BsTwCwBHBjzwub4i4/audioblocks-correct-answer-hint-accept-5_HFD0bIADI_NWM.mp3');
                audio.play();
            } else if(dataTopup.data.status.includes('Gagal')) {
                $('#btnStatus_' + dataTopup.data.id).html(` <button type="button" class="btn btn-sm btn-danger mb-2" data-toggle="modal" 
                                                data-topup_number="${dataTopup.data.nomor}"
                                                data-topup_product_name="${dataTopup.data.produk}"
                                                data-topup_price="Rp ${dataTopup.data.harga}"
                                                data-topup_id="${dataTopup.data.id}"
                                                data-topup_customer_name="${dataTopup.data.nama_pelanggan}"
                                                data-topup_type="${dataTopup.data.tipe}"
                                                onclick="handleConfirmTopup(this)">
                                                    <i class="fa fa-redo"></i>
                                                  Kirim Ulang
                                                    
                                                </button>`);
                $.toast({
                    title: 'Topup Gagal',
                    subtitle: new Date().toLocaleString(),
                    content: 'Gagal melakukan topup ' + dataTopup.data.id,
                    type: 'error',
                    delay: 5000
                });
            }
            topup.find('td:eq(4)').html(dataTopup.data.status);
        }
    });         
});
</script> 
<script>
    $(document).ready(function() {
        $('.select-2').select2();
    
    });
</script>
<script type="text/javascript">
 
    
    function handleGetDeposit()
    {
        $.ajax({
            url: "{{route('api.digiflazz.profile')}}",
            type: "GET",
            success: function(res) {
                let data = res.data;
                $('#deposit').html(new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0
             }).format(data.deposit));
            },
            error: function(err) {
                alert('Gagal mengambil deposit');
            }
        });
    }

    function handleConfirmTopup (e) {
        let number = $(e).data('topup_number');
        let product_name = $(e).data('topup_product_name');
        let price = $(e).data('topup_price');
        let id = $(e).data('topup_id');
        let customer_name = $(e).data('topup_customer_name');
        let type = $(e).data('topup_type');
        let url = `{{ route('api.topups.supplier', ':id') }}`;
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
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    allowEscapeKey: false,
                    allowEnterKey: false
                })
            },
            success: function (response) {
                $('#confirm_form').html(`      
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="confirm_nomor" class="form-label">Nomor</label>
                            <input type="text" class="form-control" id="confirm_nomor" readonly value="${number}">
                        </div>
                    </div>
                    <div class="col-lg-6 ${!customer_name ? 'd-none' : ''}">
                        <div class="mb-3">
                            <label for="confirm_customer_name" class="form-label">Nama Pelanggan</label>
                            <input type="text" class="form-control" id="confirm_customer_name" readonly value="${customer_name}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="confirm_product_name" class="form-label">Produk</label>
                            <input type="text" class="form-control" id="confirm_product_name" readonly value="${product_name}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="confirm_price" class="form-label">Harga</label>
                            <input type="text" class="form-control" id="confirm_price" readonly value="${price}">
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <label for="supplier" class="form-label">Supplier</label>
                        <select class="form-select" id="supplier" name="supplier"></select>
                        
                    </div>
                    <div class="col-lg-12">
                        <label for="pin" class="form-label">Pin Transaksi <i class="fa fa-info-circle" data-toggle="tooltip"
                            data-placement="top" title="Pin transaksi adalah pin yang digunakan untuk melakukan transaksi"></i></label>
                            
                        <div class="mb-3 input">
                            <input type="password" class="form-control" id="pin" name="pin"
                         
                            maxlength="6"
                            placeholder="Masukkan pin anda">
                        </div>
                    </div>
                </div>`);
                $('#btnCreateTopup').data('id', id);
               
                $('#modal-confirm').modal('show');
                $('#supplier').html('<option value="" selected disabled>Pilih Supplier</option>');
                if (response.data.length > 0) {
                    $.each(response.data, function (index, value) {
                        $('#supplier').append(`<option value="${value.id}">${value.name} - Rp ${value.price} 
                            | ${value.persentase_berhasil}% / ${value.total_topup}
                            </option>`);
                    });
                    $('#supplier option:eq(1)').prop('selected', true);
                } else {
                    $('#supplier').append(`<option value="" selected disabled>Supplier tidak tersedia</option>`);
                }
                Swal.close();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: xhr.responseJSON.message,
                })
            },
            
        });
    }

    function handleCetak(e) {
        let id = $(e).data('id');
        let url = `{{ route('cashier.topup.print', ':id') }}`;
        url = url.replace(':id', id);
        $.ajax({
            url: url,
            method: "GET",
            success: function(data) {
                var popup = window.open('', '_blank', 'width=600,height=400');    
                popup.document.open();
                popup.document.write(data); 
                popup.document.close(); 
                popup.onload = function() {
                    popup.print();
                    popup.onafterprint = function() {
                        popup.close();
                    }
                };
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }


    function handleClickCancel (e) {
        let id = $(e).data('id');
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Transaksi akan dibatalkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#28a745',
                confirmButtonText: 'Ya, Batalkan!',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    let  url = `{{ route('api.topups.cancel', ':id') }}`;
                    url = url.replace(':id', id);
                    $.ajax({
                        url: url,
                        type: "GET",
                        success: function (response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                            }).then((result) => {
                                location.reload();
                            })

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
            })
    }

    // handleDetail
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
                            <input type="text" class="form-control" id="detail_nomor" readonly value="${response.data.nomor}" readonly>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="detail_nama_pelanggan" class="form-label">Nama Pelanggan</label>
                            <input type="text" class="form-control" id="detail_nama_pelanggan" readonly value="${response.data.detail.nama_pelanggan}">
                        </div>
                    </div>
                    
                    `;
                } else {
                    html = html + `
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <label for="detail_nomor" class="form-label">Nomor</label>
                            <input type="text" class="form-control" id="detail_nomor" readonly value="${response.data.nomor}" readonly>
                        </div>
                    </div>
                    `;
                }
                html = html + `
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="detail_produk" class="form-label">Produk</label>
                        <input type="text" class="form-control" id="detail_produk" readonly value="${response.data.produk}">
                    </div>
                </div>
                   
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="detail_harga" class="form-label">Harga</label>
                            <input type="text" class="form-control" id="detail_harga" readonly value="${response.data.harga}">
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
                            <label for="detail_whatsapp" class="form-label">Nomor Whatsapp</label>
                            <input type="number" class="form-control" id="detail_whatsapp" readonly value="${response.data.whatsapp}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="detail_kasir" class="form-label">Kasir</label>
                            <input type="text" class="form-control" id="detail_kasir" readonly value="Rendi Julianto">
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

    // handleCheckStatus
    function handleCheckStatus (id, status) {
        let url = `{{ route('api.topups.check.status', ':id') }}`;
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



    $('#btnCreateTopup').click(function (e) {
        let id = $('#btnCreateTopup').data('id');
        let supplier = $('#supplier').val();
        let pin = $('#pin').val();
        if (!supplier) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Supplier tidak boleh kosong',
            })
            return;
        }
        if (!pin) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Pin tidak boleh kosong',
            })
            return;
        }
        let url = `{{ route('api.topups.storeTopup', ':id') }}`;
        url = url.replace(':id', id);
        $.ajax({
            url: url,
            type: "POST",
            data: {
                supplier_produk_id: supplier,
                _token: "{{ csrf_token() }}",
                pin: pin
            },
            beforeSend: function () {
                Swal.fire({
                    title: 'Mohon Tunggu',
                    html: 'Sedang memproses...',
                    didOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    allowEscapeKey: false,
                    allowEnterKey: false
                })
            },
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message,
                }).then(function(isConfirm) {
  if (isConfirm) {
    location.reload();
  } else {
    //if no clicked => do something else
  }
});
        
            },
            error: function (xhr, ajaxOptions, thrownError) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: xhr.responseJSON.message,
                })
            },
        });
    });

    $('#show_hide').click(function (e) {
        let deposit = $('#deposit').html();
        if ($('#deposit').html() == 'Rp xxx.xxx.xxx') {
            handleGetDeposit();
        } else {
            $('#deposit').html('Rp xxx.xxx.xxx');
        }
        // icon change
        $(this).toggleClass('fa-eye fa-eye-slash');

    });
    handleGetDeposit();

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
        window.location.href = `{{ route('cashier.topup') }}?search=${search}&start=${start}&end=${end}&status=${status}&produk=${product}`;
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
