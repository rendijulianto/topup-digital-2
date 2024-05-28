
                <form onkeydown="return event.key != 'Enter';" action="{{route('admin.types.update', $type->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Masukkan nama" value="{{ old('name') ?? $type->name }}">
                        <small class="text-danger"></small>
                    </div>
                </form>
         