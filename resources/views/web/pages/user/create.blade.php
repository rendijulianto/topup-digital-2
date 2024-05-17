            <form action="{{route('admin.users.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" placeholder="Masukkan nama"
                                value="{{old('nama')}}">
                                <small class="text-danger"></small>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan email"
                                value="{{old('email')}}">
                                <small class="text-danger"></small>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan kata sandi"
                                value="{{old('password')}}">
                                <small class="text-danger"></small>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="jabatan" class="form-label">Level</label>
                                <select name="jabatan" id="jabatan" class="form-control @error('jabatan') is-invalid @enderror">
                                    <option value="">Pilih jabatan</option>
                                    <option value="admin" {{old('jabatan') == 'admin' ? 'selected' : ''}}>Admin</option>
                                    <option value="kasir" {{old('jabatan') == 'kasir' ? 'selected' : ''}}>Kasir</option>
                                </select>
                                <small class="text-danger"></small>
                            </div>
                        </div>
                    </div>
                
                    {{-- level --}}
                  
                    <div class="mb-3 d-none"
                    id="input_pin">
                        <label for="pin" class="form-label">Pin</label>
                        <input type="password" name="pin" id="pin" class="form-control @error('pin') is-invalid @enderror" placeholder="Masukkan pin"
                        value="{{old('pin')}}">
                        <small class="text-danger"></small>
                    </div>
                </form>

<script>
    $(document).ready(function() {
       $('#jabatan').on('change', function() {
           let jabatan = $(this).val();
           if (jabatan == 'kasir') {
               $('#input_pin').removeClass('d-none');
           } else {
                $('#input_pin').addClass('d-none');
                $('#pin').val('');
           }
       });
    });
</script>