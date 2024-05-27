@extends('web.layout.app')
@section('breadcrumb')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Pembelian Voucher Fisik</h4>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="row">
   <div class="col-4">
         <form>
              <div class="input-group mb-3">
                <input type="text" name="no_seri" class="form-control" placeholder="Masukan nomor seri voucher"
                 aria-label="Masukan nomor seri voucher"
                 onkeypress="return (event.keyCode === 8 ||event.keyCode === 13 || (event.charCode >= 48 && event.charCode <= 57));" aria-describedby="button-addon2">
                <button class="btn btn-danger" type="submit" id="btn-cari">
                     <i class="fa fa-search"></i>
                     Cari
                </button>
              </div>
         </form>
   </div>
   <div class="col-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title
                ">
                <i class="fa fa-shopping-cart"></i>
                Detail Voucher</h4>
                <hr class="my-1">
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="detail-voucher">
                        <input type="hidden" name="id_voucher" id="id_voucher">
                        <tr>
                            <th>Produk</th>
                            <td id="produk">
                                -
                            </td>
                        </tr>
                        <tr>
                            <th>Harga</th>
                            <td id="harga">
                                -
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td id="status_voucher">
                                -
                            </td>
                        </tr>

                        <tr>
                            <th>
                                Tanggal Dibuat
                            </th>
                            <td id="tanggal_dibuat">
                                -
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Tanggal Kadaluarsa
                            </th>
                            <td id="tanggal_kadaluarsa">
                                -
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Pin Transaksi
                            </th>
                            <td>
                                <input type="password" class="form-control"
                                maxlength="6"
                                 id="pin" placeholder="Masukan pin transaksi">
                            </td>
                        </tr>
                    </table>
                    <button class="btn btn-danger btn-sm float-end" disabled onclick="handleSell()" id="btn-beli">
                        <i class="fa fa-shopping-cart"></i>
                        Beli Sekarang
                    </button>
                </div>
            </div>
        </div>
   </div>

</div>
<!-- end row -->
@endsection
@section('script')
<script>
    const handleSearch = () => {
        let no_seri = $('input[name=no_seri]').val();
        let url = "{{route('api.topups.sellVoucher',':no_seri')}}";
        url = url.replace(':no_seri', no_seri);
        $.ajax({
            url: url,
            type: "GET",
            success: function(response) {
                if (response.status == true) {   
                    $('#id_voucher').val(response.data.id);
                    $('#produk').html(response.data.nama_produk);
                    $('#harga').html(response.data.harga);
                    $('#status_voucher').html(`
                    <span class="badge bg-${response.data.status == 'Sudah Terjual' ? 'danger' : 'success'}">${response.data.status}</span>
                    `);
                    $('#tanggal_terjual').html(response.data.tanggal_terjual);
                    $('#tanggal_dibuat').html(response.data.tanggal_dibuat);
                    $('#tanggal_kadaluarsa').html(response.data.tanggal_kadaluarsa);
                    if (response.data.status == 'Sudah Terjual') {
                        $('#btn-beli').prop('disabled', true);
                    } else {
                        $('#btn-beli').prop('disabled', false);
            
                    }
                } else {
                    $('#detail-voucher').html('<tr><td>' + response.message + '</td></tr>');
                    $('#btn-beli').prop('disabled', true);
                }
            },
            error: function(response) {
                if (response.status == 404) {
                   Swal.fire({
                        title: 'Voucher tidak ditemukan',
                        text: 'Nomor seri voucher tidak ditemukan, silahkan cek kembali nomor seri voucher anda.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            }
        });
    }

    $(document).ready(function() {
        $('form').submit(function(e) {
            e.preventDefault();
            handleSearch();
        });

        $('input').attr('autocomplete', 'off');
        $('input').focus();

        const handleSell = () => {
            Swal.fire({
                title: 'Beli Voucher',
                text: 'Apakah anda yakin ingin membeli voucher ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Beli Sekarang',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    let id_voucher = $('#id_voucher').val();
                    let url = "{{route('api.topups.sellVoucher',':id_voucher')}}";
                    url = url.replace(':id_voucher', id_voucher);
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: {
                            _token: "{{csrf_token()}}",
                            pin: $('#pin').val()
                        },
                        success: function(response) {
                            if (response.status == true) {
                                Swal.fire({
                                    title: 'Berhasil',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then((result) => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Gagal',
                                    text: response.message,
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        },
                        error: function(response) {
                            if (response.status == 422) {
                                let errors = response.responseJSON.errors;
                                let message = '';
                                for (const key in errors) {
                                    message += errors[key] + '<br>';
                                }
                            } else if (response.status == 401) {
                                Swal.fire({
                                    title: 'Oops!',
                                    text:  response.responseJSON.message,
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            } else {
                                Swal.fire({
                                    title: 'Gagal',
                                    text: 'Terjadi kesalahan saat memproses data, silahkan coba lagi.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        }
                    });
                }
            });
        }      
    });
</script>
@endsection