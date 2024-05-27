@extends('web.layout.app')
@section('breadcrumb')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Aktivasi Voucher</h4>
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
                <h6 class="text-uppercase mt-0" title="Customers">Sisa Saldo</h6>
                <h2 class="my-2" id="deposit">-</h2>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4 class="card-title">Aktivasi Voucher</h4>
            </div>
            <div class="card-body">
                <form method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-lg-6 mb-3">
                            <label for="brand_id" class="form-label"><span class="circle-icon">1</span> Brand</label>
                            <select name="brand_id" id="brand_id" class="form-control @error('brand_id') is-invalid @enderror">
                                <option value=""
                                selected disabled
                                >Pilih Brand</option>
                                @foreach ($brands as $brand)
                                    <option value="{{$brand->id}}">{{$brand->nama}}</option>
                                @endforeach
                            </select>
                            @error('brand_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                            @enderror
                        </div>
                        <div class="col-12 col-lg-6 mb-3">
                            <label for="type_id" class="form-label"><span class="circle-icon">2</span> Tipe</label>
                            <select name="type_id" id="type_id" class="form-control @error('type_id') is-invalid @enderror">
                                <option value="">Pilih Tipe</option>
                            </select>
                            @error('type_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                            @enderror
                        </div>
                        <div class="col-6 mb-3">
                            <label for="product_id" class="form-label"><span class="circle-icon">3</span> Produk</label>
                            <select name="product_id" id="product_id" class="form-control @error('product_id') is-invalid @enderror">
                                <option value="">Pilih Voucher</option>
                            </select>
                            @error('product_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                            @enderror
                        </div>
                        <div class="col-6 mb-3">
                            <label for="supplier_id" class="form-label"><span class="circle-icon">4</span> Supplier</label>
                            <select name="supplier_id" id="supplier_id" class="form-control @error('supplier_id') is-invalid @enderror">
                                <option value="">Pilih Supplier</option>
                            </select>
                            @error('supplier_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                            @enderror
                        </div>
                        <div class="col-6 mb-3">
                            <label for="tgl_kadaluwarsa" class="form-label"><span class="circle-icon">5</span> Tgl Kadaluwarsa</label>
                            <input type="date" name="tgl_kadaluwarsa" id="tgl_kadaluwarsa" class="form-control @error('tgl_kadaluwarsa') is-invalid @enderror">
                            @error('tgl_kadaluwarsa')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                            @enderror
                        </div>
                        <div class="col-6 mb-3">
                            <label for="days" class="form-label"><span class="circle-icon">6</span> Expired Hari</label>
                            <input type="number" name="days" id="days" class="form-control   @error('tgl_kadaluwarsa') is-invalid @enderror">
                            @error('days')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                            @enderror
                        </div>
            
                        <div class="col-6">
                            <label for="pin" class="form-label"><span class="circle-icon">7</span> Pin</label>
                            <input type="password" class="form-control" @error('pin') is-invalid @enderror id="pin" name="pin"  maxlength="6" placeholder="Masukkan pin anda">
                           
                        </div>
                        <div class="col-6 mb-3">
                            <label for="price_sell" class="form-label"><span class="circle-icon">8</span> Harga Jual</label>
                            <input type="text" readonly name="price_sell" id="price_sell" class="form-control  @error('tgl_kadaluwarsa') is-invalid @enderror">
                            @error('price_sell')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                            @enderror
                        </div>
                    </div>
                </form>
            </div>
        </div> <!-- end card -->
    </div> <!-- end col -->
    <div class="col-12 col-lg-7">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    Aktivasi Voucher
                </h4>
            </div>
            <div class="card-body">
               <form action="#" method="POST" onsubmit="return false" id="formSeri">
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="mdi mdi-information"></i> <strong>Info!</strong> Silahkan isi form inject voucher dibawah ini.
                        </div>
                    </div>
                    <div class="col-12">
                        <label for="number" class="form-label"><span class="circle-icon">9</span> Nomor Seri</label>
                        <div class="input-group mb-3">
                            <input type="number" name="search" class="form-control no-seri" placeholder="Masukkan no seri voucher" onkeypress="return event.keyCode === 8 || event.charCode >= 48 && event.charCode <= 57">
                            <button class="btn btn-danger" type="submit" id="button-addon2"><i class="fa fa-trash"></i></button>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-primary" id="btnInject"><i class="fa fa-syringe"></i>
                            Inject Voucher</button>
                    </div>
                </div>
               </form>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No Seri</th>
                            <th>Brand</th>
                            <th>Produk</th>
                            <th>Status</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody id="result">
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data</td>
                        </tr>
        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
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

            if(dataTopup.data.status.includes('Sukses')) {
                $('#btnStatus_' + dataTopup.data.id).html(``);
                $.toast({
                    title: 'Topup Berhasil',
                    subtitle: new Date().toLocaleString(),
                    content: 'Berhasil melakukan topup ' + dataTopup.data.id,
                    type: 'success',
                    delay: 5000
                });
             
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
        getDeposit();
    });

    function getDeposit()
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




    // updateProduk
    function updateProduk()
    {
        $.ajax({
            url: "{{route('cron.update-produk')}}",
            type: "GET",
            beforeSend: function() {
                Swal.fire({
                    title: 'Loading...',
                    html: 'Sedang mengupdate produk',
                    allowOutsideClick: false,
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                });
            },
            success: function(res) {
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Produk berhasil di update',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
                // trigger brand 
            },
            error: function(err) {
                Swal.fire({
                    title: 'Perhatian!',
                    text: 'Produk gagal di update',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
            },
            complete: function() {
                $('#brand').trigger('change');
            }
        });
    }


    let max_fields = 1;
    // jika no-seri di enter
    // maka form akan ditambah dengan form baru dengan clone form yang sudah ada
    $('#formSeri').on('keypress', 'input', function(e) {
        if (e.which == 13) {
            e.preventDefault();
            var $this = $(this);
            var $parent = $this.parent();
            var $clone = $parent.clone();
            $clone.find('input').val('');
            $parent.after($clone);
            $parent.next().find('input').focus();
        }
    });
    
    $('#formSeri').on('click', '.btn-danger', function(e) {
        e.preventDefault();

        let count = $('.no-seri').length;
        if (count == 1) {
           Swal.fire({
               title: 'Perhatian!',
               text: 'Form seri tidak boleh kosong',
               icon: 'warning',
               confirmButtonText: 'OK'
           });
        } else {
            if (count == max_fields) {
                Swal.fire({
                    title: 'Perhatian!',
                    text: 'Maksimal inject ' + max_fields + ' voucher',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
            } else {
                var $this = $(this);
                var $parent = $this.parent();
                $parent.remove();
            }
        }
    });

    $('#type_id').on('change', function() {
        $('#product_id').html('<option value="" disabled selected>Pilih Voucher</option>')
        $('#supplier_id').html('<option value="" disabled selected>Pilih Supplier</option>')
    });

    $('#product_id').on('change', function() {
        $('#supplier_id').html('<option value="" disabled selected>Pilih Supplier</option>')
    });

    $('#days').on('keyup', function() {
        let days = $(this).val();
        let tgl_kadaluwarsa = new Date();
        tgl_kadaluwarsa.setDate(tgl_kadaluwarsa.getDate() + parseInt(days));
        let month = tgl_kadaluwarsa.getMonth() + 1;
        let day = tgl_kadaluwarsa.getDate();
        let year = tgl_kadaluwarsa.getFullYear();

        $('#tgl_kadaluwarsa').val(year + '-' + month.toString().padStart(2, '0') + '-' + day.toString().padStart(2, '0'));
    });

    $('#tgl_kadaluwarsa').on('change', function() {
        let tgl_kadaluwarsa = $(this).val();
        let tgl = new Date(tgl_kadaluwarsa);
        let now = new Date();
        let diff = tgl - now;
        let days = diff / (1000 * 60 * 60 * 24);
        // bulatkan ke bawah
        days = Math.floor(days)+1;
        $('#days').val(days);
    });

    // jika brand berubah maka voucher akan berubah
    

     $('#brand_id').on('change', function() {
        let brand_id = $(this).val();
        $.ajax({
            url: "{{route('api.types.category-brand')}}",
            type: "GET",
            data: {
                brand_id: brand_id,
                kategori_id: '{{$category->id}}'
            },
            beforeSend: function() {
                $('#type_id').html('<option value="" disabled selected>Pilih Tipe</option>')
                $('#product_id').html('<option value="" disabled selected>Pilih Voucher</option>')
                $('#supplier_id').html('<option value="" disabled selected>Pilih Supplier</option>')
            },
            success: function(res) {
                let data = res.data;
                if (data.types.length == 0) {
                    $('#type_id').html('<option value="">Tidak ada tipe</option>')
                } else {
                    let html = '';
                    data.types.forEach(element => {
                        html += `<option value="${element.id}">
                            ${element.nama}
                            </option>`
                    });
                    $('#type_id').append(html);
                }
            },
            error: function(err) {
                Swal.fire({
                    title: 'Perhatian!',
                    text: 'Gagal mengambil tipe',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                })
            }
        });

    });

    $('#type_id').on('change', function() {
        let type_id = $(this).val();
        let brand_id = $('#brand_id').val();
        $('#product_id').html('<option value="" disabled selected>Pilih Voucher</option>')
        $.ajax({
            url: "{{route('api.products.aktivasi-voucher')}}",
            type: "GET",
            data: {
                brand_id: brand_id,
                tipe_id: type_id,
            },
            success: function(res) {
                let data = res.data;
                if (data.length == 0) {
                    $('#product_id').html('<option value="">Tidak ada produk</option>')
                } else {
                    let html = '';
                    data.forEach(element => {
                        html += `<option value="${element.id}" data-price="${element.harga}">
                            ${element.nama}
                            </option>`
                    });
                    $('#product_id').append(html);
                }
            },
            error: function(err) {
                Swal.fire({
                    title: 'Perhatian!',
                    text: 'Gagal mengambil produk',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                })
            }
        });
    });

    $('#product_id').on('change', function() {
        let product_id = $(this).val();
        let link = "{{route('api.products.supplier', ':product_id')}}";
        link = link.replace(':product_id', product_id);
        $('input[name="price_sell"]').val(new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0
             }).format($(this).find(':selected').data('price')));
        $('#supplier_id').html('<option value="">Pilih Supplier</option>')
        $.ajax({
            url: link,
            type: "GET",
            success: function(res) {
                let data = res.data;
                console.log(data);
                if (data.length == 0) {
                    $('#supplier_id').html('<option value="">Tidak ada supplier</option>')
                } else {
                    let html = '';
                    console.log(data);
                    data.forEach(element => {
                        html += `<option value="${element.id}" data-price="${element.price}">
                            ${element.name} - ${element.price} | ${element.total_berhasil} / ${element.persentase_berhasil}%
                            </option>`
                    });
                    $('#supplier_id').append(html);
                }
            },
            error: function(err) {
                console.log(err);
            }
        });
    });
    function removeDot(str) {
        return str.replace(/\./g, '');
    }


    $('#supplier_id').on('change', function() {
        let price_jual = $('input[name="price"]').val();
        let price = $('#supplier_id').find(':selected').data('price');
        // price hilangkan titik
        price = price.toString();
        price = removeDot(price);

        console.log(price_jual);
        console.log(price);
        let keuntungan =  parseInt(price_jual) - parseInt(price);
        $('#keuntungan').val(keuntungan);
    });

    $('#price').on('keyup', function() {
        let price = $(this).val();
        let price_modal = $('#supplier_id').find(':selected').data('price');
        let price_modal_string = price_modal.toString();
        price_modal_string = price_modal_string.replace(/\./g, '');
        let keuntungan = parseInt(price) - parseInt(price_modal_string);
        $('#keuntungan').val(keuntungan);
    });

    $('#btnInject').on('click', async function () {
    let brand_id = $('#brand_id').val();
    let voucher_id = $('#voucher_id').val();
    let type_id = $('#type_id').val();
    let product_id = $('#product_id').val();
    let supplier_id = $('#supplier_id').val();
    let pin = $('#pin').val();
    let price = $('#price').val();
    let tgl_kadaluwarsa = $('#tgl_kadaluwarsa').val();
    let seri = [];
    
    $('.no-seri').each(function () {
        let value = $(this).val();
        if (value != '') {
            seri.push(value);
        }
    });

    if (brand_id == '' || voucher_id == '' || type_id == '' || product_id == '' || supplier_id == '' || seri.length == 0 || pin == '' || tgl_kadaluwarsa == '') {
        Swal.fire({
            title: 'Perhatian!',
            text: 'Semua form harus diisi',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
    } else {
        // looping seri
        const result = await Swal.fire({
            title: 'Perhatian!',
            text: 'Apakah anda yakin ingin inject voucher ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, saya yakin',
            cancelButtonText: 'Tidak'
        });

        if (result.isConfirmed) {
            let html = '';
            // munculkan loading
            $('#btnInject').html('<i class="fa fa-spinner fa-spin"></i> Loading...');
            $('#btnInject').attr('disabled', true);
            // seluruh select / input disabled
            $('select,input,button').attr('disabled', true);


            // Use for...of loop for asynchronous iteration
            for (const element of seri) {
                try {
                    const res = await $.ajax({
                        url: "{{route('api.topups.storeVoucher')}}",
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        },
                        data: {
                            brand_id: brand_id,
                            type_id: type_id,
                            produk_id: product_id,
                            supplier_id: supplier_id,
                            nomor: element,
                            pin: pin,
                            tgl_kadaluwarsa: tgl_kadaluwarsa,
                        },
                    });

                    let data = res.data;

                    html += `<tr>
                                <td>${data.nomor}</td>
                                <td>${data.brand}</td>
                                <td>${data.product}</td>
                                <td>${data.status} - ${data.message}</td>
                                <td>${data.tanggal}</td>
                            </tr>`;

                } catch (err) {
                    // Handle errors
                    if (err.status == 400) {
                        let data = err.responseJSON.data;
                        html += `<tr>
                                    <td>${data.nomor}</td>
                                    <td>${data.brand}</td>
                                    <td>${data.product}</td>
                                    <td>${data.status} - ${data.message}</td>
                                    <td>${data.tanggal}</td>
                                </tr>`;
                    }

                    if (err.status == 422) {
                        let errors = err.responseJSON.errors;
                        // Menggunakan Object.keys() untuk mengambil kunci dari objek errors
                        html += `<tr>
                                    <td>${element}</td>
                                    <td colspan="4">
                                        <ul>`;

                        Object.keys(errors).forEach(key => {
                            errors[key].forEach(error => {
                                html += `<li>${error}</li>`;
                            });
                        });

                        html += `</ul>
                                    </td>
                                </tr>`;


                    }
                    if (err.status == 500 || err.status == 401) {
                        Swal.fire({
                            title: 'Perhatian!',
                            text: err.responseJSON.message,
                            icon: 'warning',
                            confirmButtonText: 'OK'
                        });
                    }
                }
            }

            // Append to the table after all requests are completed
            $('#result').html(html);

            // hapus loading
            $('#btnInject').html('<i class="fa fa-syringe"></i> Inject Voucher');
            $('#btnInject').attr('disabled', false);
            $('select,input,button').attr('disabled', false);

            getDeposit();

        } else {
            Swal.fire({
                title: 'Perhatian!',
                text: 'Voucher tidak jadi di inject',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
        }
    }
});


</script>
@endsection
