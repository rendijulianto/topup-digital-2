

                <form action="{{route('admin.prefixes.update',$prefix->id)}}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="brand_id" class="form-label">Brand</label>
                        <select name="brand_id" id="brand_id" class="form-select @error('brand_id') is-invalid @enderror">
                            <option value="" disabled selected>Pilih brand</option>
                            @foreach ($brands as $brand)
                            <option value="{{$brand->id}}" {{old('brand_id')?? $prefix->brand_id == $brand->id ? 'selected' : ''}}>{{$brand->nama}}</option>
                            @endforeach
                        </select>
                        <small class="text-danger"></small>
                    </div>
                    <div class="mb-3">
                        <label for="number" class="form-label">Nomor </label>
                        <input type="text" name="nomor" id="nomor" class="form-control @error('nomor') is-invalid @enderror" placeholder="Nomor ex: 0812" value="{{old('nomor') ?? $prefix->nomor}}">
                        <small class="text-danger"></small>
                    </div>
                </form>
          
