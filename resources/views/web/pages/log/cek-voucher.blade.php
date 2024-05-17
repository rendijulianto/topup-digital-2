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
                    <li class="breadcrumb-item active">Cek Nama </li>
                </ol>
            </div>
            <h4 class="page-title">
                <i class="ri-check-line"></i>
                Halaman Cek Nama
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
                <i class="ri-check-line"></i>
                Cek Nama
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
                            <div class="col-lg-4 mb-1">
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
                            <div class="col-lg-4 mb-3">
                                <label class="control-label">Klik untuk mencari</label>
                                {{-- button --}}
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Cari kata kunci" name="search" value="{{ request()->search }}">
                                    <button class="btn btn-primary" type="button">
                                        <i class="ri-search-line"></i>
                                    </button>
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
                                        <th>Brand</th>
                                        <th>Ref ID</th>
                                        <th>Nomor</th>
                                        <th>Atas Nama</th>
                                        <th>Status</th>
                                        <th>Waktu</th>
                                        <th width="10%">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($logs as $log)
                                       <tr>
                                            <td>{{$logs->firstItem() + $loop->index}}</td>
                                            <td>{{$log->brand->nama}}</td>
                                            <td>{{$log->ref_id}}</td>
                                            <td>{{$log->nomor}}</td>
                                            <td>{{$log->keterangan}}</td>
                                            <td>{!!$log->status_html!!}</td>
                                            <td>{{$log->created_at->format('d-m-Y H:i')}}</td>
                                            <td>
                                                <button onclick="handleDelete({{$log->id}})" class="btn btn-sm btn-danger">
                                                    <i class="fa fa-trash"></i>
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
                            {{ $logs->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end card -->
    </div> <!-- end col -->
</div>
<!-- end row -->
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

    function handleDelete(id) {
       Swal.fire({
           title: 'Apakah anda yakin?',
           text: "Data yang dihapus tidak bisa dikembalikan!",
           icon: 'warning',
           showCancelButton: true,
           confirmButtonColor: '#3085d6',
           cancelButtonColor: '#d33',
           confirmButtonText: 'Ya, Hapus!'
       }).then((result) => {
           if (result.isConfirmed) {
            // let url = logs.cek-nama.destroy
                let url = `{{ route('admin.logs.cek-nama.destroy', ':id') }}`;
                url = url.replace(':id', id);

               $.ajax({
                   url: url,
                   type: 'DELETE',
                   data: {
                       _token: '{{ csrf_token() }}'
                   },
                   success: function(response) {
                       if (response.status) {
                           Swal.fire(
                               'Berhasil!',
                               response.message,
                               'success'
                           );
                           setTimeout(() => {
                               window.location.reload();
                           }, 1000);
                       } else {
                           Swal.fire(
                               'Gagal!',
                               response.message,
                               'error'
                           );
                       }
                   },
                     error: function(err) {
                          Swal.fire(
                            'Gagal!',
                            'Terjadi kesalahan saat menghapus data',
                            'error'
                          );
                     }
               });
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
            const brand = document.querySelector('select[name="brand"]').value;

            window.location.href = `{{ route('admin.logs.cek-nama') }}?search=${search}&start=${start}&end=${end}&brand=${brand}`;
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
