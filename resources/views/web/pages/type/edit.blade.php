
                <form onkeydown="return event.key != 'Enter';" action="{{route('admin.types.update', $type->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" placeholder="Masukkan nama" value="{{ old('nama') ?? $type->nama }}">
                        <small class="text-danger"></small>
                    </div>
                </form>
         