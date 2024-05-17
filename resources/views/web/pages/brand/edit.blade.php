
                <form
                onkeydown="return event.key != 'Enter';"
                action="{{route('admin.brands.update',['brand'=>$brand->id])}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    @method('PUT')
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" placeholder="Masukkan nama" value="{{ old('nama') ?? $brand->nama }}">
                        <small class="text-danger"></small>
                    </div>
                    <div class="mb-3">
                        <label for="logo" class="form-label">Logo</label>
                        <input type="file" name="logo" id="logo" class="form-control @error('logo') is-invalid @enderror" placeholder="Masukkan logo" accept="image/*" />
                        <small class="text-danger"></small>
                    </div>
                  
                </form>
     
