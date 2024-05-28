
                <form action="{{route('admin.products.supplier.update', $supplierProduct->id)}}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="mb-3 col-12">
                            <label for="name" class="form-label">Nama Produk</label>
                            <input type="text" name="name" id="name" readonly class="form-control @error('name') is-invalid @enderror" placeholder="Masukkan name" value="{{$supplierProduct->product->name}}">
                            <small class="text-danger"></small>
                        </div>
                        <div class="mb-3 col-12 col-lg-6">
                            <label for="supplier_id" class="form-label">Supplier</label>
                            <select name="supplier_id" id="supplier_id" class="form-control select-2 @error('supplier_id') is-invalid @enderror">
                                <option value="">Pilih Supplier</option>
                                @foreach ($suppliers as $supplier)
                                    <option
                                    @if (old('supplier_id') ?? $supplierProduct->supplier_id == $supplier->id)
                                        selected
                                    @endif 
                                    value="{{$supplier->id}}">{{$supplier->name}}</option>
                                @endforeach
                            </select>
                            <small class="text-danger"></small>
                        </div>
                        <div class="mb-3 col-12 col-lg-6">
                            <label for="product_sku_code" class="form-label">
                                <i class="fa fa-info-circle" data-bs-toggle="tooltip" 
                                data-bs-placement="top" title="Produk Sku Kode adalah kode yang ada di produk digiflazz"></i>
                          
                                Produk Sku Kode
                            </label>
                            <input type="text" name="product_sku_code" id="product_sku_code" class="form-control @error('product_sku_code') is-invalid @enderror" placeholder="Masukkan Produk Sku Kode" value="{{old('product_sku_code') ?? $supplierProduct->product_sku_code}}">
                            <small class="text-danger"></small>
                        </div>
                        <div class="mb-3 col-12 col-lg-6">
                            <label for="price" class="form-label">Harga</label>
                            <input type="text" name="price" id="price" class="form-control @error('price') is-invalid @enderror" placeholder="Masukkan price" value="{{old('price') ?? $supplierProduct->price}}">
                            <small class="text-danger"></small>
                        </div>
                        <div class="mb-3 col-12 col-lg-6">
                            <label for="multi" class="form-label">Multi</label>
                            <select name="multi" id="multi" class="form-control @error('multi') is-invalid @enderror">
                                <option value="1" 
                                @if (old('multi') ?? $supplierProduct->multi == 1)
                                    selected
                                @endif
                                >Ya</option>
                                <option value="0"
                                @if (old('multi') ?? $supplierProduct->multi == 0)
                                    selected
                                @endif
                                >Tidak</option>
                            </select>
                            <small class="text-danger"></small>
                        </div>
                        <div class="mb-3 col-12 col-lg-6">
                            <label for="stock" class="form-label">Stok</label>
                            <input type="text" name="stock" id="stock" class="form-control @error('stock') is-invalid @enderror" placeholder="Masukkan stok" value="{{old('stock') ?? $supplierProduct->stock}}">
                            <small class="text-danger"></small>
                        </div>
                        <div class="mb-3 col-12 col-lg-6">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status_" class="form-control @error('status') is-invalid @enderror">
                                <option 
                                @if (old('status') ?? $supplierProduct->status == 1)
                                    selected
                                @endif
                                value="1">Aktif</option>
                                <option 
                                @if (old('status') ?? $supplierProduct->status == 0)
                                    selected
                                @endif
                                value="0">Tidak Aktif</option>
                            </select>
                            <small class="text-danger"></small>
                        </div>
                        <div class="mb-3 col-12 col-lg-6">
                            <label for="start_cut_off" class="form-label">Jam Tutup</label>
                            <input type="time" name="start_cut_off" id="start_cut_off" class="form-control @error('start_cut_off') is-invalid @enderror" placeholder="Masukkan price" value="{{old('start_cut_off')?? $supplierProduct->start_cut_off}}">
                            <small class="text-danger"></small>
                        </div>
                        <div class="mb-3 col-12 col-lg-6">
                            <label for="end_cut_off" class="form-label">Jam Buka</label>
                            <input type="time" name="end_cut_off" id="end_cut_off" class="form-control @error('end_cut_off') is-invalid @enderror" placeholder="Masukkan price" value="{{old('end_cut_off')?? $supplierProduct->end_cut_off}}">
                            <small class="text-danger"></small>
                        </div>
                    </div>
                </form>