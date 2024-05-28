
    <form action="{{route('admin.users.update', $user->id)}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-lg-6">
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Masukkan nama"
                    value="{{old('name', $user->name)}}">
                    <small class="text-danger"></small>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan email"
                    value="{{old('email', $user->email)}}">
                    <small class="text-danger"></small>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="kata_sandi" id="kata_sandi" class="form-control @error('kata_sandi') is-invalid @enderror" placeholder="Masukkan kata sandi"
                    value="{{old('kata_sandi')}}">
                    <small class="text-danger"></small>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mb-3">
                    <label for="role" class="form-label">Level</label>
                    <select name="role" id="role" class="form-control @error('role') is-invalid @enderror">
                        <option value="">Pilih role</option>
                        <option value="admin" {{old('role', $user->role) == 'admin' ? 'selected' : ''}}>Admin</option>
                        <option value="kasir" {{old('role', $user->role) == 'kasir' ? 'selected' : ''}}>Kasir</option>
                    
                    </select>
                    <small class="text-danger"></small>
                </div>
            </div>
        </div>
       
        {{-- pin --}}
        <div class="mb-3 @if($user->role == 'admin') d-none @endif" id="input_pin">
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