@extends('web.layout.app')
@section('title', 'Topup E-Wallet')
@section('breadcrumb')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">
                Halaman Top up Dompet Digital - {{Str::ucfirst($brand)}}
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
                <h5><i class="{{$icon}}"></i>
                  Topup Dompet Digital - {{Str::ucfirst($brand)}}
                </h5>
            </div>
            <div class="card-body">
                {{-- input --}}
                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="number" class="form-label">
                            <span class="circle-icon">1</span>
                            Masukan nomor telepon 
                        </label>
                        <div class="input-group">
                            <input type="text" class="form-control form-control-lg keyboard-virtual"  autofocus
                            placeholder="Contoh: 08xxxx"
                            id="number" name="number">
                            <button class="btn btn-danger" type="button" id="btnCheck" 
                            enabled>
                                <i class="fa fa-search"></i>
                                Cek
                            </button>
                           
                        </div>
                        <small class="text-muted" id="small_customer_name">
                            Silahkan masukan nomor telepon pelanggan dan klik tombol cek
                        </small>
                    
                    </div>
                
                    <div class="col-12">
                        <label for="products" class="form-label">
                            <span class="circle-icon">2</span>
                            Pilih Produk
                        </label>
                        <div class="row" id="products">
                            
                            <div class="col-6 col-lg-4 mb-lg-0 mb-3 loading-skeleton">
                                <a class="card" href="">
                                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                        <h2>Produk tidak tersedia</h2>
                                        <b>
                                            Bayar : Rp 10.000 </b>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6 col-lg-4 mb-lg-0 mb-3 loading-skeleton">
                                <a class="card" href="">
                                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                        <h2>Produk tidak tersedia</h2>
                                        <b>
                                            Bayar : Rp 10.000 </b>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6 col-lg-4 mb-lg-0 mb-3 loading-skeleton">
                                <a class="card" href="">
                                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                        <h2>Produk tidak tersedia</h2>
                                        <b>
                                            Bayar : Rp 10.000 </b>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6 col-lg-4 mb-lg-0 mb-3 loading-skeleton">
                                <a class="card" href="">
                                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                        <h2>Produk tidak tersedia</h2>
                                        <b>
                                            Bayar : Rp 10.000 </b>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6 col-lg-4 mb-lg-0 mb-3 loading-skeleton">
                                <a class="card" href="">
                                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                        <h2>Produk tidak tersedia</h2>
                                        <b>
                                            Bayar : Rp 10.000 </b>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6 col-lg-4 mb-lg-0 mb-3 loading-skeleton">
                                <a class="card" href="">
                                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                        <h2>Produk tidak tersedia</h2>
                                        <b>
                                            Bayar : Rp 10.000 </b>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6 col-lg-4 mb-lg-0 mb-3 loading-skeleton">
                                <a class="card" href="">
                                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                        <h2>Produk tidak tersedia</h2>
                                        <b>
                                            Bayar : Rp 10.000 </b>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6 col-lg-4 mb-lg-0 mb-3 loading-skeleton">
                                <a class="card" href="">
                                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                        <h2>Produk tidak tersedia</h2>
                                        <b>
                                            Bayar : Rp 10.000 </b>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6 col-lg-4 mb-lg-0 mb-3 loading-skeleton">
                                <a class="card" href="">
                                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                        <h2>Produk tidak tersedia</h2>
                                        <b>
                                            Bayar : Rp 10.000 </b>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6 col-lg-4 mb-lg-0 mb-3 loading-skeleton">
                                <a class="card" href="">
                                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                        <h2>Produk tidak tersedia</h2>
                                        <b>
                                            Bayar : Rp 10.000 </b>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6 col-lg-4 mb-lg-0 mb-3 loading-skeleton">
                                <a class="card" href="">
                                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                        <h2>Produk tidak tersedia</h2>
                                        <b>
                                            Bayar : Rp 10.000 </b>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6 col-lg-4 mb-lg-0 mb-3 loading-skeleton">
                                <a class="card" href="">
                                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                        <h2>Produk tidak tersedia</h2>
                                        <b>
                                            Bayar : Rp 10.000 </b>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6 col-lg-4 mb-lg-0 mb-3 loading-skeleton">
                                <a class="card" href="">
                                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                        <h2>Produk tidak tersedia</h2>
                                        <b>
                                            Bayar : Rp 10.000 </b>
                                    </div>
                                </a>
                            </div>
                                    
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end card -->
    </div> <!-- end col -->
</div>
<!-- end row -->
{{-- modal --}}
<div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalDetailLabel"><i class="fa fa-plus-circle"></i> Detail Topup</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6 mb-3">
                        <label for="name" class="form-label">Nomor</label>
                        <input type="text" class="form-control form-control-lg" name="show_number" id="show_number" disabled>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="name" class="form-label">Nama Pelanggan</label>
                        <input type="text" class="form-control form-control-lg" name="show_name" id="show_name" disabled>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="name" class="form-label">Nama Produk</label>
                        <input type="text" class="form-control form-control-lg" name="show_product_name" id="show_product_name" disabled>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="selling_price" class="form-label">Bayar</label>
                        <input type="text" class="form-control form-control-lg" name="show_product_selling_price" id="show_product_selling_price" disabled>
                    </div>
                    <div class="col-12">
                        <label for="whatsapp" class="form-label">Whatsapp
                            <i class="fa fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Masukan nomor whatsapp untuk mengirimkan bukti transaksi"></i>
                        </label>
                        <input type="text" class="form-control form-control-lg keyboard-virtual" name="whatsapp" id="whatsapp" placeholder="08xxxxxxxxxx">
                        <small class="text-muted">*Masukan nomor whatsapp untuk mengirimkan bukti transaksi</small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-lg" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success btn-lg" id="btnCreateTopup">
                    <i class="fa fa-check"></i>
                    Konfirmasi Topup
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalCustom" tabindex="-1" aria-labelledby="modalCustomLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalCustomLabel"><i class="fa fa-plus-circle"></i>Detail Topup </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6 mb-3">
                        <label for="name" class="form-label">Nomor</label>
                        <input type="text" class="form-control form-control-lg" name="show_custom_number" id="show_custom_number" disabled>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="name" class="form-label">Nama Pelanggan</label>
                        <input type="text" class="form-control form-control-lg" name="show_custom_name" id="show_custom_name" disabled>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="name" class="form-label">Nama Produk</label>
                        <input type="text" class="form-control form-control-lg" name="show_custom_product_name" id="show_custom_product_name" disabled>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="name" class="form-label">Nominal Topup <i class="fa fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Masukan nominal topup yang diinginkan maksimal 1.000.000"></i></label>
                        <input type="text" class="form-control form-control-lg keyboard-virtual" name="amount" id="amount"  placeholder="Masukan nominal topup">
                    </div>
                    <div class="col-12 mb-3">
                        <label for="selling_price" class="form-label">Bayar</label>
                        <input type="text" class="form-control form-control-lg" name="show_custom_product_selling_price" id="show_custom_product_selling_price" value="Rp 0" disabled>
                    </div>
                    <div class="col-12">
                        <label for="whatsapp" class="form-label">Whatsapp
                            <i class="fa fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Masukan nomor whatsapp untuk mengirimkan bukti transaksi"></i>
                        </label>
                        <input type="text" class="form-control form-control-lg keyboard-virtual" name="whatsapp" id="whatsapp" placeholder="08xxxxxxxxxx">
                        <small class="text-muted">*Masukan nomor whatsapp untuk mengirimkan bukti transaksi</small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-lg" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success btn-lg" id="btnCreateTopupCustom">
                    <i class="fa fa-check"></i>
                    Konfirmasi Topup
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
@include('web.pages.topup.script')
<script>
    $(document).ready(function(){
        $('#amount').mask('000.000.000', {reverse: true});
    });

    

    $('#amount').on('keyup', debounce(function () {
        let amount = $(this).val();
        amount = amount.replace(/\./g, '');
        let product_id = $('.card-product-custom.active').data('id');
        let url = `{{route('api.products.selling_price', ['product' => ':product'])}}`;
        url = url.replace(':product', product_id);
        $.ajax({
            url: url,
            type: "GET",
            data: {
                amount: amount
            },
            beforeSend: function(){
                $('#show_custom_product_selling_price').val('Rp 0');
            },
            success: function(response){
                if(response.status == true){
                    $('#show_custom_product_selling_price').val(formatAngka(response.data.harga_bulat, 'rupiah'));
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message,
                    })
                }
            },
            error: function(response){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan pada server',
                })
            },
            complete: function(){
                // button disabled

            }
        })
    }, 1000));
    // keypress
    $('#number').keypress(function(e){
        $('.card-product').addClass('disabled opacity-50');
        $('.card-product-custom').addClass('disabled opacity-50');
        if(e.which == 13 || e.keyCode == 13 || e.key == 'Enter' && $('#btnCheck').attr('disabled') == false){
            $('#btnCheck').click();
        }
    });

    let brand_id = '';

    const getProducts = () => {
        let brand = `{{$brand}}`;
        $.ajax({
                url: "{{route('api.products.e-wallet')}}",
                type: "GET",
                data: {
                    brand: brand
                },
                beforeSend: function(){
                  
                },
                success: function(response){
                    if(response.status == true){
                        $('#products').html('');
                        let topup_count = response.data[0].topup_count;
                        let product_terlaris = response.data.reduce((prev, current) => (prev.topup_count > current.topup_count) ? prev : current);
                        let custom = ``;
                        $.each(response.data, function(index, product){
                           
                                $('#products').append(`
                                    <div class="col-6 col-lg-4 mb-lg-0 mb-3">
                                    <div class="card">
                                        ${product_terlaris.id == product.id ? '<span class="badge badge-info position-absolute" style="top: 0; right: 0; color: white !important; background-color: red !important; font-size: 12px; font-weight: bold;">Terlaris</span>' : ''}
                                        <div class="card-body card-product disabled opacity-50 ${product.status == false ? 'bg-danger text-white gangguan' : ''}"
                                            data-id="${product.id}"
                                            data-name="${product.nama}"
                                            data-selling_price="${product.harga}"
                                            data-description="${product.deskripsi}"
                                            data-status="${product.status}">
                                        
                                            <h2>${product.nama}</h2>
                                            ${product.status == false ? '<b>Produk sedang gangguan</b>' : `<b>
                                                Bayar: ${new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR',
                                                minimumFractionDigits: 0
                                            }).format(product.harga)}
                                            </b> <br/>`}
                                        </div>
                                    </div>
                                </div>
                            
                                `);
                                brand_id = product.brand_id;
                        });

                        $('#products').append(custom);

                        $('.card-product').on('click', function(){
                            if ($(this).hasClass('disabled')) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Silahkan isi nomor telepon terlebih dahulu!',
                                })

                                return false;
                            }
                            $('.card-product').removeClass('active');
                            $(this).addClass('active');
                            $('#product_id').val($(this).data('id'));
                            $('#show_number').val($('#number').val());
                            $('#show_product_name').val($(this).data('name'));
                            $('#show_product_selling_price').val(formatAngka($(this).data('selling_price'), 'rupiah'));
                            $('#show_product_description').val($(this).data('description'));
                            $('#modalDetail').modal('show');
                        });
                        
                        $('.card-product-custom').on('click', function(){
                            if ($(this).hasClass('disabled')) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Silahkan isi nomor telepon terlebih dahulu!',
                                })

                                return false;
                            }
                            $('.card-product-custom').removeClass('active');
                            $(this).addClass('active');
                            $('#product_custom_id').val($(this).data('id'));
                            $('#show_custom_number').val($('#number').val());
                            $('#show_custom_product_name').val($(this).data('name'));
                            $('#show_product_selling_price').val(formatAngka($(this).data('selling_price'), 'rupiah'));
                            // fokus
                            
                            $('#modalCustom').modal('show');
                            $('[name="amount"]').attr('autofocus', true);
                        });
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: response.message,
                        })
                    }
                },
                error: function(response){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan pada server',
                    }).then((result) => {
                        window.location.href = "{{route('login')}}";
                    })
                },
                complete: function(){
                  
                }
            })
    }

    $('#btnCheck').click(function(){
        let number = $('#number').val();
        if(number == ''){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Masukan nomor e-wallet',
            })
        }else{
            $.ajax({
                url: "{{route('api.check.e-wallet')}}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    nomor: number,
                    brand_id:brand_id,
                    brand: `{{$brand}}`
                },
                beforeSend: function(){
                    // progress loading
                    $('#small_customer_name').html('');
                    $('#show_number').val('');
                    $('#show_name').val('');
                    $('#btnCheck').attr('disabled', true);
                    $('#btnCheck').html(`
                        <i class="fa fa-spinner fa-spin"></i> Memeriksa...
                    `);
                },
                success: function(response){
                    if(response.status == true){
                        $('#small_customer_name').html(`<b>${response.data.nama}</b>`);
                        $('#show_number').val(response.data.number);
                        $('#show_name').val(response.data.nama);
                        $('#show_custom_number').val(response.data.number);
                        $('#show_custom_name').val(response.data.nama);
                        $('.card-product').removeClass('disabled opacity-50');
                        $('.card-product-custom').removeClass('disabled opacity-50');
                        // $('html, body').animate({
                        //             scrollTop: $('#products').offset().top
                        //             - 200
                                    
                        //         }, 1000);
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: response.message,
                        })
                    }
                },
                error: function(response){
                //    422
                    if(response.status == 422){
                        let error = response.responseJSON.errors;
                        let message = '';
                        $.each(error, function(key, value){
                            message += value + '<br>';
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            html: message,
                        })
                        // 404
                    }else if(response.status == 404){
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: response.responseJSON.message,
                        })
                    }else{
                        // swal konfirmasi server error
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            message: 'Terjadi kesalahan pada server',
                            button: 'OK'
                        })
                    }
                },
                complete: function(){
                    // button disabled
                    $('#btnCheck').attr('disabled', false);
                    $('#btnCheck').html(`
                        <i class="fa fa-search"></i>
                        Cek
                    `);
                   
                }
            })
        }
    });

    $('#btnCreateTopup').click(function(){
            

                    let product_id = $('.card-product.active').data('id');
                    let number = $('#number').val();
                    let whatsapp = $('#whatsapp').val();
                    let nama_pelanggan = $('#show_name').val();
                    $.ajax({
                        url: "{{route('api.topups.store')}}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            produk_id: product_id,
                            nomor: number,
                            whatsapp: whatsapp,
                            nama_pelanggan: nama_pelanggan
                        },
                        beforeSend: function(){
                            // progress loading
                            $('#btnCreateTopup').attr('disabled', true);
                            $('#btnCreateTopup').html(`
                              <span class="spinner-btopup spinner-btopup-sm" role="status" aria-hidden="true"></span>
                                Memuat...
                            `);
                        },
                        success: function(response){
                            if(response.status == true){
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message,
                                    showDenyButton: false,
                                    showCancelButton: false,
                                    confirmButtonText: `OK`,
                                    timer: 2000,
                                }).then((result) => {
                                    @if(request()->isGuest == "true")
                                        id = response.data.id;
                                        url = "{{route('topup.detail', ['id' => ':id'])}}?isGuest=true";
                                        url = url.replace(':id', id);
                                        window.location.href = url;
                                        @else
                                        window.location.href = "{{route('cashier.topup')}}";
                                    @endif
                                })
                                $('#modalDetail').modal('hide');
                                $('#whatsapp').val('');
                            }else{
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: response.message,
                                })
                            }
                        },
                        error: function(response){
                        //    422
                            if(response.status == 422){
                                let error = response.responseJSON.errors;
                                let message = '';
                                $.each(error, function(key, value){
                                    message += value + '<br>';
                                });
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    html: message,
                                })
                                // 404
                            }else if(response.status == 404){
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: response.responseJSON.message,
                                })
                            }else{
                                // swal konfirmasi server error
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    message: 'Terjadi kesalahan pada server',
                                    button: 'OK'
                                })
                            }
                        },
                        complete: function(){
                            // button disabled
                            $('#btnCreateTopup').attr('disabled', false);
                            $('#btnCreateTopup').html(`
                                <i class="fa fa-shopping-cart"></i>
                                Pesan Sekarang
                            `);
                        }
                    })
       
        });


        $('#btnCreateTopupCustom').click(function(){
            

            let product_id = $('.card-product-custom.active').data('id');
            let number = $('#number').val();
            let whatsapp = $('#whatsapp').val();
            let nama_pelanggan = $('#show_name').val();
            let amount = $('#amount').val();
            amount = amount.replace(/\./g, '');
            $.ajax({
                url: "{{route('api.topups.store')}}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    produk_id: product_id,
                    nomor: number,
                    whatsapp: whatsapp,
                    nama_pelanggan: nama_pelanggan,
                    nominal: amount
                },
                beforeSend: function(){
                    // progress loading
                    $('#btnCreateTopupCustom').attr('disabled', true);
                    $('#btnCreateTopupCustom').html(`
                      <span class="spinner-btopup spinner-btopup-sm" role="status" aria-hidden="true"></span>
                        Memuat...
                    `);
                },
                success: function(response){
                    if(response.status == true){
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            showDenyButton: false,
                            showCancelButton: false,
                            confirmButtonText: `OK`,
                            // timer: 2000,
                        }).then((result) => {
                            @if(request()->isGuest == "true")
                                id = response.data.id;
                                url = "{{route('topup.detail', ['id' => ':id'])}}?isGuest=true";
                                url = url.replace(':id', id);
                                window.location.href = url;
                                @else
                                window.location.href = "{{route('cashier.topup')}}";
                            @endif
                        })
                        $('#modalCustom').modal('hide');
                        $('#whatsapp').val('');
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: response.message,
                        })
                    }
                },
                error: function(response){
                //    422
                    if(response.status == 422){
                        let error = response.responseJSON.errors;
                        let message = '';
                        $.each(error, function(key, value){
                            message += value + '<br>';
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            html: message,
                        })
                        // 404
                    }else if(response.status == 404){
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: response.responseJSON.message,
                        })
                    }else{
                        // swal konfirmasi server error
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            message: 'Terjadi kesalahan pada server',
                            button: 'OK'
                        })
                    }
                },
                complete: function(){
                    // button disabled
                    $('#btnCreateTopupCustom').attr('disabled', false);
                    $('#btnCreateTopupCustom').html(`
                        <i class="fa fa-shopping-cart"></i>
                        Pesan Sekarang
                    `);
                }
            })

});
    



    $(document).ready(function(){
        getProducts();

        $('input').attr('autocomplete', 'off');
        // setTimeout(function(){
        //     window.location.href = "{{route('dashboard')}}";
        // }, 600000);

    });

</script>
@endsection
