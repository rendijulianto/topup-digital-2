@extends('web.layout.app')
@section('title', 'Daftar Voucher')
@section('breadcrumb')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Voucher Fisik - {{Str::ucfirst($brand)}}</h4>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="row">
   <div class="col-lg-5">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Cek Status Voucher</h4>
        </div>
        <div class="card-body">
            <form action="" method="post" onsubmit="handleValidation(event)">
                @csrf
                {{-- button group append --}}
                <div class="input-group">
                    <input type="text" class="form-control form-control-lg keyboard-virtual" name="kode" placeholder="Contoh: 1234567890"
                    autocomplete="false"
                    required>
                    <button class="btn btn-danger" type="submit">
                        <i class="fa fa-search"></i>
                        Cek
                    </button>
                </div>
                <small class="text-danger">*Masukkan kode voucher yang ingin di cek</small>
                <hr>
                <p>
                    <strong>Produk:</strong>
                    <span class="badge bg-primary" id="product_name">-</span>
                </p>
                <table class="table table-striped">
                  <thead>
                    <tr>
                        <th>No</th>
                        <th>Keterangan</th>
                        <th>Waktu</th>
                  </thead>
                    <tbody id="histories">
        
                    </tbody>
                </table>
            </form>
        </div>
    </div>
   </div>
   <div class="col-lg-7">
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h4 class="card-title">Daftar Produk</h4>
        </div>
        <div class="card-body">
            <div class="responsive-table-plugin">
                <div class="table-rep-plugin">
                    <div class="table-responsive" data-pattern="priority-columns">
                        <table id="tech-companies-1" class="table table-striped">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Harga</th>

                            </tr>
                            </thead>
                            <tbody id="voucher-list">
                            
                            
                            </tbody>
                        </table>
                    </div> <!-- end .table-responsive -->
                </div> <!-- end .table-rep-plugin-->
            </div> <!-- end .responsive-table-plugin-->
        </div>
    </div> <!-- end card -->
</div> <!-- end col -->
</div>
@endsection
@section('script')
<script>
    function handleValidation(event) {
         event.preventDefault();
        const kode = event.target.kode.value;
       $.ajax({
           url: `{{route('api.check.voucher')}}`,
           method: 'POST',
           dataType: 'json',
           headers: {
               'X-CSRF-TOKEN': `{{csrf_token()}}`
           },
           beforeSend: function() {
               $('#product_name').text('Memeriksa...');
               $('#histories').empty();
               Swal.fire({
                   title: 'Memeriksa...',
                   html: 'Sedang memeriksa kode voucher...',
                   didOpen: () => {
                       Swal.showLoading();
                   },
                   allowOutsideClick: () => !Swal.isLoading(),
                   allowEscapeKey: () => !Swal.isLoading(),
                   allowEnterKey: () => !Swal.isLoading()
               });
               $('button[type=submit]').attr('disabled', true)
               $('button[type=submit]').html(`<i class="fa fa-spinner fa-spin"></i> Memeriksa...`)
           },
           data: {
               target: kode,
               brand: `{{$brand}}`,
                brand_id: brand_id
           },
           success: function(response) {
                if(response.status === true){
                    $('#product_name').text(response.data.voucher.product);
                    $('#histories').empty();
                    response.data.histories.forEach((history, index) => {
                        $('#histories').append(`
                            <tr>
                                <td>${index + 1}</td>
                                <td>${history.note}</td>
                                <td>${history.created_at}</td>
                            </tr>
                        `);
                    });
                } else {
                    $('#product_name').text('-');
                    $('#histories').empty();
                    $('#histories').append(`
                        <tr>
                            <td colspan="3" class="text-center">${response.message}</td>
                        </tr>
                    `);	
                }
                // Swal  stop
                Swal.close();
           },
           error: function(error) {
                //    jika error status kode 422
                $('#product_name').text('-');
                if(error.status === 404){
                    $('#histories').empty();
                    $('#histories').append(`
                        <tr>
                            <td colspan="3" class="text-center">Voucher tidak ditemukan</td>
                        </tr>
                    `);
                    Swal.fire({
                        title: 'Voucher tidak ditemukan',
                        icon: 'error'
                    });
                } else if (error.status === 422) {
                //    pesan error validate laravel 
                Swal.fire({
                    title: 'Terjadi kesalahan',
                    icon: 'error',
                    html: error.responseJSON.message
                });
                } else {
                    Swal.fire({
                        title: 'Terjadi kesalahan',
                        icon: 'error'
                    });
                }
           },
           complete: function() {
        
                $('button[type=submit]').attr('disabled', false)
                $('button[type=submit]').html(`<i class="fa fa-search"></i> Cek`)
           }
       });
    }
    let brand_id = '';
    function getVouchers(event) {
        $.ajax({
            url: `{{route('api.products.voucher-fisik')}}?brand={{$brand}}`,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                $('#voucher-list').empty();
                response.data.forEach((voucher, index) => {
                    $('#voucher-list').append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td>${voucher.name}</td>
                            <td>${new Intl.NumberFormat('id-ID', {style: 'currency', currency: 'IDR', minimumFractionDigits: 0}).format(voucher.price)}</td>
                        </tr>
                    `);
                    brand_id = voucher.brand_id;
                });
            },
            error: function(error) {
                console.log(error);
            }
        });
    }

    $(document).ready(function() {
        getVouchers();
    });
  

    
</script>
@endsection
