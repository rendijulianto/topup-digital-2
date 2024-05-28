            <form action="{{route('admin.users.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Masukkan name"
                                value="{{old('name')}}">
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
                                <label for="role" class="form-label">Level</label>
                                <select name="role" id="role" class="form-control @error('role') is-invalid @enderror">
                                    <option value="">Pilih role</option>
                                    <option value="admin" {{old('role') == 'admin' ? 'selected' : ''}}>Admin</option>
                                    <option value="kasir" {{old('role') == 'kasir' ? 'selected' : ''}}>Kasir</option>
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
       $('#role').on('change', function() {
           let role = $(this).val();
           if (role == 'kasir') {
               $('#input_pin').removeClass('d-none');
           } else {
                $('#input_pin').addClass('d-none');
                $('#pin').val('');
           }
       });
    });
</script>