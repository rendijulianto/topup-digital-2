@extends('web.layout.app')
@section('title', Str::title(str_replace('-', ' ', $category)))
@section('breadcrumb')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">{{config('app.name')}}</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Halaman</a></li>
                    <li class="breadcrumb-item active">Topup {{Str::title(str_replace('-', ' ', $category))}}</li>
                </ol>
            </div>
            <h4 class="page-title">
                Halaman Topup {{Str::title(str_replace('-', ' ', $category))}}
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
                <h4>
                    <i class="{{$icon}} fa-xl"></i>
                    Topup {{Str::title(str_replace('-', ' ', $category))}}
                </h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="number" class="form-label"><span class="circle-icon">1</span> Masukan nomor telepon pelanggan</label>
                        <div class="input-group">
                            <input type="text" class="form-control form-control-lg keyboard-virtual" placeholder="Contoh: 08xxxxxxxxxx"
                            autofocus
                            id="number" name="number">
                            <button class="btn border" type="button">
                                <img src="{{asset('assets/images/logo-dark.png')}}" id="logo" style="min-width: 20px; max-width: 100px; min-height: 20px; max-height: 20px;" alt="Image">
                            </button>
                            @if(Auth::guard('pengguna')->check() && !request()->has('isGuest'))
                                <button class="btn btn-success" type="button" id="btnPelanggan">
                                    <i class="fa fa-address-book"></i> Pilih Pelanggan
                                </button>
                            @endif
                        </div>
                        
                    </div>     
                    <div class="col-12 mb-3">
                        <label for="type" class="form-label"><span class="circle-icon">2</span>
                             Pilih Tipe</label>
                        <select class="form-select form-select-lg" name="type" id="type">
                            <option disabled selected>Pilih Tipe</option>
                        </select>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="products" class="form-label"><span class="circle-icon">3</span>
                            Pilih Produk</label>
                        <div class="row" id="products">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="fa fa-info-circle"></i>
                                    Masukan nomor telepon pelanggan untuk melihat produk
                                </div>
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
<input type="hidden" name="brand" id="brand" value="">
<div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalDetailLabel"><i class="fa fa-plus-circle"></i> Detail Topup</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="position-relative">
                            <label for="number" class="form-label">Nomor Telepon</label>
                            <div class="d-flex align-items-center">
                                <input type="text" class="form-control form-control-lg"  name="show_number" id="show_number" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="name" class="form-label">Nama Produk</label>
                        <input type="text" class="form-control form-control-lg" name="show_produk" id="show_produk" disabled>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="show_price" class="form-label">Bayar</label>
                        <input type="text" class="form-control form-control-lg" name="show_price" id="show_price" disabled>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="show_description" class="form-label">Deskripsi</label>
                        <textarea class="form-control form-control-lg" name="show_description" id="show_description"
                        rows="3"
                        disabled></textarea>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="whatsapp" class="form-label">Whatsapp
                            <i class="fa fa-question-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Masukan nomor whatsapp untuk mengirimkan bukti transaksi"></i>
                        </label>
                        <input type="text" class="form-control form-control-lg keyboard-virtual"
                         name="whatsapp" id="whatsapp" placeholder="08xxxxxxxxxx" autocomplete="off">
                        <small class="text-muted">*Masukan nomor whatsapp untuk menerima detail transaksi</small>
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


@endsection
@section('script')
@include('web.pages.topup.script')
<script>
    $(document).ready(function () {
        // number selesai render click langsung number
        $('#number').focus();
        $('input').attr('autocomplete', 'off');
        $('#number').on('keyup', debounce(function () {
            let number = $(this).val();
            $('#logo').attr('src', '-');
            $('#type').html('<option disabled selected>Pilih Tipe</option>');
            if (number.length >= 4) {
                $.ajax({
                    url: "{{route('api.types.category-prefix')}}",
                    type: "GET",
                    data: {
                        prefix: number,
                        kategori: '{{$category}}'
                    },
                    dataType: "json",
                    beforeSend: function () {
                        $('#logo').attr('src', `{{asset('assets/images/logo-dark.png')}}`);
                        $('#type').html('<option disabled selected>Loading...</option>');
                    },
                    success: function (response) {
                        if (response.status == true) {
                            $('#type').html('<option disabled selected>Pilih Tipe</option>');
                            if (response.data.types.length == 0) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Tipe atau produk tidak ditemukan',
                                });
                                return false;
                            }

                            $('#logo').attr('src', response.data.brand.logo);
                            $('#brand').val(response.data.brand.id);
                            response.data.types.forEach(function (type) {
                                $('#type').append(`
                                    <option value="${type.id}">${type.nama}</option>
                                `);
                            });
                            if (response.data.types.find(type => type.nama == 'Umum')) {
                                $('#type').val(response.data.types.find(type => type.nama == 'Umum').id);
                                $('#type').trigger('change');
                            } else {
                                $('#type').val(response.data.types[0].id);
                                $('#type').trigger('change');
                            }
                            $('#type').prop('disabled', false);

                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: response.message,
                            });
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: xhr.responseJSON.message,
                        });
                    },

                });
            } else {
                $('#logo').attr('src', `{{asset('assets/images/logo-dark.png')}}`);
                $('#type').html('<option disabled selected>Pilih Tipe</option>');
                $('#products').html('<div class="col-12"><div class="alert alert-info"><i class="fa fa-info-circle"></i> Masukan nomor handphone untuk melihat produk</div></div>');
            }
        }, 2000));

        $('#number').on('keyup', function(){
            let number = $(this).val();
            if (number.length >= 4) {
                $('#type').prop('disabled', false);
            } else {
                $('#type').prop('disabled', true);
            }
            $('#type').html('<option disabled selected>Pilih Tipe</option>');
            $('#products').html('<div class="col-12"><div class="alert alert-info"><i class="fa fa-info-circle"></i> Masukan nomor telepon pelanggan untuk melihat produk</div></div>');
        });

        $('#type').on('change', function(){
            let brand = $('#brand').val();
            let type = $(this).val();
            $.ajax({
                url: "{{route('api.products.seluler')}}",
                type: "GET",
                data: {
                    brand_id: brand,
                    tipe_id: type,
                    kategori: '{{$category}}'
                },
                beforeSend: function(){
                    $('#products').html('<div class="col-12"><div class="alert alert-info"><i class="fa fa-info-circle"></i> Loading...</div></div>');
                },
                success: function(response){
                    $('#products').html('');
                    if(response.status == true){
                        // cek product.topup_count tertinggi
                        let topup_count = response.data[0].topup_count;
                        let product_terlaris = response.data.reduce((prev, current) => (prev.topup_count > current.topup_count) ? prev : current);

                        $.each(response.data, function(index, product){

                            $('#products').append(`
                                <div class="col-6 col-lg-4 mb-lg-0 mb-3">
                                <div class="card">
                                  
                                    ${product_terlaris.id == product.id ? '<span class="badge badge-info position-absolute" style="top: 0; right: 0; color: white !important; background-color: red !important; font-size: 12px; font-weight: bold;">Terlaris</span>' : ''}
                                    <div class="card-body card-product ${product.status == false ? 'bg-danger text-white gangguan' : ''}"
                                        data-id="${product.id}"
                                        data-name="${product.nama}"
                                        data-harga_jual="${product.harga}"
                                        data-deskripsi="${product.deskripsi}"
                                        data-status="${product.status}">
                                    
                                        <h2>${product.nama}</h2>
                                        ${product.status == false ? '<b>Produk sedang gangguan</b>' : `<b>
                                            Bayar: ${new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR',
                                            minimumFractionDigits: 0
                                        
                                        }).format(product.harga)}
                                        </b> <br/>`}
                                        @if($category == 'Data')
                                        <sub>${product.deskripsi}</sub>
                                        @endif                                      
                                    
                                    </div>
                                </div>
                            </div>
                                `);
                        });

                        $('.card-product').on('click', function(){
                            if ($(this).hasClass('disabled')) {
                                return false;
                            }
                            $('.card-product').removeClass('active');
                            $(this).addClass('active');
                            $('#product_id').val($(this).data('id'));
                            $('#show_number').val($('#number').val());
                            $('#show_produk').val($(this).data('name'));
                            $('#show_price').val(formatAngka($(this).data('harga_jual'), 'rupiah'));
                            $('#show_description').val($(this).data('deskripsi'));
                            $('#modalDetail').modal('show');
                            $('#whatsapp').val($('#number').val());
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
                        text: response.responseJSON.message,
                    });
                },
                complete: function(){
                    // $('html, body').animate({
                    //     scrollTop: $('#products').offset().top
                    //     - 100
                    // }, 1000);
                }
            })

        });

        $('#btnCreateTopup').click(function(){
            let product_id = $('.card-product.active').data('id');
            let number = $('#number').val();
            let whatsapp = $('#whatsapp').val();
            $.ajax({
                url: "{{route('api.topups.store')}}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    produk_id: product_id,
                    nomor: number,
                    whatsapp: whatsapp
                },
                beforeSend: function(){
                    // progress loading
                    $('#btnCreateTopup').attr('disabled', true);
                    $('#btnCreateTopup').html(`
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
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
                    }else if(response.status == 404){
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: response.responseJSON.message,
                        })
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            message: 'Terjadi kesalahan pada server',
                            button: 'OK'
                        })
                    }
                },
                complete: function(){
                    $('#btnCreateTopup').attr('disabled', false);
                    $('#btnCreateTopup').html(`
                        <i class="fa fa-check"></i>
                        Konfirmasi Topup
                    `);
                }
            })
        });
    });
</script>
@endsection
