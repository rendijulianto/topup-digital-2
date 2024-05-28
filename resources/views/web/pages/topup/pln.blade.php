@extends('web.layout.app')
@section('title', 'Token Listrik')

@section('breadcrumb')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">
               Halaman 
               Topup Token Listrik
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
                <h5><i class="ri-wireless-charging-line"></i>
                Token Listrik </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12  mb-3">
                        <label for="number" class="form-label">
                            <span class="circle-icon">1</span>
                            Nomor Meter / ID Pelanggan </label>
                        <div class="input-group">
                            <input type="text" class="form-control 
                            form-control-lg keyboard-virtual"
                              placeholder="Contoh: 45xxxxxxxxx / 53xxxxxxxxx"
                            id="number" name="number">
                            <button class="btn btn-danger" type="button" id="btnValidate">
                                <i class="fa fa-search"></i>
                                Cek
                            </button>
                          
                        </div>
                        <small class="text-muted">*Masukan nomor meter / ID pelanggan untuk melihat produk</small>
                    </div>
                    <div class="col-12" id="customer" style="display: none;">
                        <table class="table table-btopuped table-striped table-responsive">
                            <thead>
                                <tr>
                                    <th colspan="4">Detail Pelanggan</th>
                                </tr>
                            </thead>
                            <tr>
                                <td 
                                width="20%"
                                style="vertical-align: middle; font-weight: bold;"
                                >Nomor Meter</td>
                                <td id="customer_meter_no"></td>
                                <td
                                width="20%"
                                style="vertical-align: middle; font-weight: bold;"
                                >Segment Power</td>
                                <td id="customer_segment_power"></td>
                            </tr>
                            <tr>
                                <td
                                width="20%"
                                style="vertical-align: middle; font-weight: bold;"
                                >Nama</td>
                                <td id="customer_name"></td>
                                <td
                                width="20%"
                                style="vertical-align: middle; font-weight: bold;"
                                >ID Pelanggan</td>
                                <td id="customer_subscriber_id"></td>
            
                            </tr>
                        
                        </table>
                    </div>
                    <div class="col-12">
                        <label for="products" class="form-label"><span class="circle-icon">2</span>
                            Pilih Produk</label>
                        <div class="row" id="products">
                            <div class="col-6 col-lg-3 mb-lg-0 mb-3 loading-skeleton">
                                <a class="card" href="">
                                    <div class="card-body d-flex flex-column justify-content-center align-items-center">

                                        <p>PLN 700.000</p>                                        </p>
                                        <b>
                                            Rp 405.000,00
                                        </b>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6 col-lg-3 mb-lg-0 mb-3 loading-skeleton">
                                <a class="card" href="">
                                    <div class="card-body d-flex flex-column justify-content-center align-items-center">

                                        <p>PLN 700.000</p>                                        </p>
                                        <b>
                                            Rp 405.000,00
                                        </b>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6 col-lg-3 mb-lg-0 mb-3 loading-skeleton">
                                <a class="card" href="">
                                    <div class="card-body d-flex flex-column justify-content-center align-items-center">

                                        <p>PLN 700.000</p>                                        </p>
                                        <b>
                                            Rp 405.000,00
                                        </b>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6 col-lg-3 mb-lg-0 mb-3 loading-skeleton">
                                <a class="card" href="">
                                    <div class="card-body d-flex flex-column justify-content-center align-items-center">

                                        <p>PLN 700.000</p>                                        </p>
                                        <b>
                                            Rp 405.000,00
                                        </b>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6 col-lg-3 mb-lg-0 mb-3 loading-skeleton">
                                <a class="card" href="">
                                    <div class="card-body d-flex flex-column justify-content-center align-items-center">

                                        <p>PLN 700.000</p>                                        </p>
                                        <b>
                                            Rp 405.000,00
                                        </b>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6 col-lg-3 mb-lg-0 mb-3 loading-skeleton">
                                <a class="card" href="">
                                    <div class="card-body d-flex flex-column justify-content-center align-items-center">

                                        <p>PLN 700.000</p>                                        </p>
                                        <b>
                                            Rp 405.000,00
                                        </b>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6 col-lg-3 mb-lg-0 mb-3 loading-skeleton">
                                <a class="card" href="">
                                    <div class="card-body d-flex flex-column justify-content-center align-items-center">

                                        <p>PLN 700.000</p>                                        </p>
                                        <b>
                                            Rp 405.000,00
                                        </b>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6 col-lg-3 mb-lg-0 mb-3 loading-skeleton">
                                <a class="card" href="">
                                    <div class="card-body d-flex flex-column justify-content-center align-items-center">

                                        <p>PLN 700.000</p>                                        </p>
                                        <b>
                                            Rp 405.000,00
                                        </b>
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
                <h4 class="modal-title" id="modalDetailLabel">Detail Topup <i class="fa fa-plus-circle"></i></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6 mb-3">
                        <label for="show_number" class="form-label">Nomor Meter</label>
                        <input type="text" class="form-control form-control-lg" name="show_number" id="show_number" disabled>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="show_subscriber_id" class="form-label">ID Pelanggan</label>
                        <input type="text" class="form-control form-control-lg" name="show_subscriber_id" id="show_subscriber_id" disabled>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="show_name" class="form-label">Nama Pelanggan</label>
                        <input type="text" class="form-control form-control-lg" name="show_name" id="show_name" disabled>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="show_segment_power" class="form-label">Segment Power</label>
                        <input type="text" class="form-control form-control-lg" name="show_segment_power" id="show_segment_power" disabled>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="name" class="form-label">Nama Produk</label>
                        <input type="text" class="form-control form-control-lg" name="show_product_name" id="show_product_name" disabled>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="selling_price" class="form-label">Harga</label>
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
                <button type="button" class="btn btn-success btn-lg" id="btnCreateOrder">
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
    const getProducts = () => {
        $.ajax({
                url: "{{route('api.products.pln')}}",
                type: "GET",
                success: function(response){
                    if(response.status == true){
                        $('#products').html('');
                        let topup_count = response.data[0].topup_count;
                        let product_terlaris = response.data.reduce((prev, current) => (prev.topup_count > current.topup_count) ? prev : current);

                        $.each(response.data, function(index, product){
                            $('#products').append(`
                                <div class="col-6 col-lg-3 mb-lg-0 mb-3">
                                <div class="card">
                                    ${product_terlaris.id == product.id ? '<span class="badge badge-info position-absolute" style="top: 0; right: 0; color: white !important; background-color: red !important; font-size: 12px; font-weight: bold;">Terlaris</span>' : ''}
                                    <div class="card-body card-product disabled opacity-50 ${product.status == false ? 'bg-danger text-white gangguan' : ''}"
                                        data-id="${product.id}"
                                        data-name="${product.name}"
                                        data-selling_price="${product.price}"
                                        data-description="${product.description}"
                                        data-status="${product.status}">
                                        <h2>${product.name}</h2>
                                        
                                                    
                                        ${product.status == false ? '<b>Produk sedang gangguan</b>' : `<b>
                                            Bayar: ${new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR',
                                            minimumFractionDigits: 0
                                        
                                        }).format(product.price)}
                                        </b> <br/>`}
                                    </div>
                                </div>
                            </div>
                                `);
                        });

                        $('.card-product ').on('click', function(){
                            if ($(this).hasClass('disabled')) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Silahkan isi nomor meter terlebih dahulu!',
                                })

                                return false;
                            }
                            $('.card-product').removeClass('active');
                            $(this).addClass('active');
                            $('#product_id').val($(this).data('id'));
                            $('#show_number').val($('#number').val());
                            $('#show_product_name').val($(this).data('name'));
                            $('#show_product_selling_price').val($(this).data('selling_price'));
                            $('#show_product_description').val($(this).data('description'));
                            $('#modalDetail').modal('show');
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
                    })
                },
                complete: function(){
                  
                }
            })
    }

    $('#number').keypress(function(e){
        if(e.which == 13){
            $('#btnValidate').click();
        }
        $('#customer').hide();
        $('.card-product').addClass('disabled opacity-50');
    });


    $('#btnValidate').click(function(){
        let number = $('#number').val();
        if(number == ''){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Nomor meter / ID pelanggan tidak boleh kosong!',
            })
        }else{
            $.ajax({
                url: "{{route('api.check.pln')}}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    target: number
                },
                beforeSend: function(){
                    // progress loading
                    $('#customer_meter_no').html('');
                    $('#customer_subscriber_id').html('');
                    $('#customer_segment_power').html('');
                    $('#customer_name').html('');
                    // button disabled
                    $('#btnValidate').attr('disabled', true);
                    $('#btnValidate').html(`
                        <span class="spinner-btopup spinner-btopup-sm" role="status" aria-hidden="true"></span>
                        Memuat...
                    `);
                },
                success: function(response){
                    if(response.status == true){
                       
                        $('#customer_meter_no').html(response.data.meter_no);
                        $('#customer_subscriber_id').html(response.data.subscriber_id);
                        $('#customer_segment_power').html(response.data.segment_power);
                        $('#customer_name').html(response.data.name);
                        $('#show_number').val(response.data.meter_no);
                        $('#show_subscriber_id').val(response.data.subscriber_id);
                        $('#show_segment_power').val(response.data.segment_power);
                        $('#show_name').val(response.data.name);
                        
                        $('#customer').show();
                        // disabled hapus yang tidak memiliki kelas bg-danger text-white sebelumnya
                        $('.card-product').removeClass('disabled opacity-50');
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
                    $('#btnValidate').attr('disabled', false);
                    $('#btnValidate').html(`
                        <i class="fa fa-search"></i>
                        Cek
                    `);
                    // customer
                    // scroll ke customer
                    // $('html, body').animate({
                    //     scrollTop: $('#customer').offset().top - 100
                    // }, 1000);
                }
            })
        }
    });

    $('#btnCreateOrder').click(function(){
            
                  
                    $.ajax({
                        url: "{{route('api.topups.store')}}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            product_id: $('.card-product.active').data('id'),
                            target: $('#number').val(),
                            whatsapp: $('#whatsapp').val(),
                            customer_name: $('#customer_name').html(),
                            subscriber_id: $('#customer_subscriber_id').html(),
                            meter_no: $('#customer_meter_no').html(),
                            segment_power: $('#customer_segment_power').html(),
                        },
                        beforeSend: function(){
                            // progress loading
                            $('#btnCreateOrder').attr('disabled', true);
                            $('#btnCreateOrder').html(`
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
                             
                                        id = response.data.id;
                                        url = "{{route('topup.detail', ['id' => ':id'])}}?isCustomer=true";
                                        url = url.replace(':id', id);
                                        window.location.href = url;
                                   
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
                            $('#btnCreateOrder').attr('disabled', false);
                            $('#btnCreateOrder').html(`
                                <i class="fa fa-shopping-cart"></i>
                                Pesan Sekarang
                            `);
                        }
                    })
                
        });


    $(document).ready(function(){
        getProducts();
        $('input').attr('autocomplete', 'off');
    });
    
</script>
@endsection
