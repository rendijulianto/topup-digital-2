
                <form action="{{route('admin.products.update', $product->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="mb-3 col-12 col-lg-4">
                            <label for="category_id" class="form-label">Kategori</label>
                            <select name="category_id" id="category_id" class="form-control select-2 @error('category_id') is-invalid @enderror">
                                <option value="" disabled selected>Pilih kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{$category->id}}" {{old('category_id') == $category->id ? 'selected' : ''}} {{$product->category_id == $category->id ? 'selected' : ''}}>{{$category->name}}</option>
                                @endforeach
                            </select>
                            <small class="text-danger"></small>
                        </div>
                        <div class="mb-3 col-12 col-lg-4">
                            <label for="brand_id" class="form-label">Brand</label>
                            <select name="brand_id" id="brand_id" class="form-control select-2 @error('brand_id') is-invalid @enderror">
                                <option value="" disabled selected>Pilih brand</option>
                                @foreach ($brands as $brand)
                                <option value="{{$brand->id}}" {{old('brand_id') == $brand->id ? 'selected' : ''}} {{$product->brand_id == $brand->id ? 'selected' : ''}}>{{$brand->name}}</option>
                                @endforeach
                            </select>
                            <small class="text-danger"></small>
                        </div>
                        <div class="mb-3 col-12 col-lg-4">
                            <label for="type_id" class="form-label">Tipe</label>
                            <select name="type_id" id="type" class="form-control select-2 @error('type_id') is-invalid @enderror">
                                <option value="" disabled selected>Pilih tipe</option>
                                @foreach ($types as $type)
                                <option value="{{$type->id}}" {{old('type_id') == $type->id ? 'selected' : ''}} {{$product->type_id == $type->id ? 'selected' : ''}}>{{$type->name}}</option>
                                @endforeach
                            </select>
                            <small class="text-danger"></small>
                        </div>
                        <div class="mb-3 col-12 col-lg-6">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Masukkan nama" value="{{ old('name') ?? $product->name }}">
                            <small class="text-danger"></small>
                        </div>
                        <div class="mb-3 col-12 col-lg-6">
                            <label for="price" class="form-label">Harga</label>
                            <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" placeholder="Masukkan price" value="{{ old('price') ?? $product->price }}">
                            <small class="text-danger"></small>
                        </div>
                        <div class="mb-3 col-12 col-lg-6">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" placeholder="Masukkan description">{{ old('description') ?? $product->description }}</textarea>
                            <small class="text-danger"></small>
                        </div>
                    </div>
                    
                </form>