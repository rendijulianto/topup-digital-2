
                <form action="{{route('admin.products.store')}}" method="POST">
                    @csrf
                
                    <div class="row">
                        <div class="mb-3 col-12 col-lg-4">
                            <label for="kategori_id" class="form-label">Kategori</label>
                            <select name="kategori_id" id="kategori_id" class="form-control select-2 @error('kategori_id') is-invalid @enderror">
                                <option value="" disabled selected>Pilih kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{$category->id}}" {{old('kategori_id') == $category->id ? 'selected' : ''}}>{{$category->nama}}</option>
                                @endforeach
                            </select>
                            <small class="text-danger"></small>
                        </div>
                        <div class="mb-3 col-12 col-lg-4">
                            <label for="brand_id" class="form-label">Brand</label>
                            <select name="brand_id" id="brand_id" class="form-control select-2 @error('brand_id') is-invalid @enderror">
                                <option value="" disabled selected>Pilih brand</option>
                                @foreach ($brands as $brand)
                                <option value="{{$brand->id}}" {{old('brand_id') == $brand->id ? 'selected' : ''}}>{{$brand->nama}}</option>
                                @endforeach
                            </select>
                            <small class="text-danger"></small>
                        </div>
                        <div class="mb-3 col-12 col-lg-4">
                            <label for="tipe_id" class="form-label">Tipe</label>
                            <select name="tipe_id" id="type" class="form-control select-2 @error('tipe_id') is-invalid @enderror">
                                <option value="" disabled selected>Pilih tipe</option>
                                @foreach ($types as $type)
                                 <option value="{{$type->id}}" {{old('tipe_id') == $type->id ? 'selected' : ''}}>{{$type->nama}}</option>
                                @endforeach
                            </select>
                            <small class="text-danger"></small>
                        </div>
                        <div class="mb-3 col-12 col-lg-6">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" placeholder="Masukkan nama" value="{{old('nama')}}">
                            <small class="text-danger"></small>
                        </div>
                        <div class="mb-3 col-12 col-lg-6">
                            <label for="harga" class="form-label">Harga</label>
                            <input type="number" name="harga" id="harga" class="form-control @error('price') is-invalid @enderror" placeholder="Masukkan price" value="{{old('harga')}}">
                            <small class="text-danger"></small>
                        </div>
                        <div class="mb-3 col-12 col-lg-6">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" placeholder="Masukkan deskripsi">{{old('deskripsi')}}</textarea>
                            <small class="text-danger"></small>
                        </div>
                    </div>
                    
                </form>
   
