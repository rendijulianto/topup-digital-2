
                <form action="{{route('admin.products.supplier.update', $supplierProduct->id)}}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="mb-3 col-12">
                            <label for="nama" class="form-label">Nama Produk</label>
                            <input type="text" name="nama" id="nama" readonly class="form-control @error('nama') is-invalid @enderror" placeholder="Masukkan nama" value="{{$supplierProduct->produk->nama}}">
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
                                    value="{{$supplier->id}}">{{$supplier->nama}}</option>
                                @endforeach
                            </select>
                            <small class="text-danger"></small>
                        </div>
                        <div class="mb-3 col-12 col-lg-6">
                            <label for="produk_sku_code" class="form-label">
                                <i class="fa fa-info-circle" data-bs-toggle="tooltip" 
                                data-bs-placement="top" title="Produk Sku Kode adalah kode yang ada di produk digiflazz"></i>
                          
                                Produk Sku Kode
                            </label>
                            <input type="text" name="produk_sku_code" id="produk_sku_code" class="form-control @error('produk_sku_code') is-invalid @enderror" placeholder="Masukkan Produk Sku Kode" value="{{old('produk_sku_code') ?? $supplierProduct->produk_sku_code}}">
                            <small class="text-danger"></small>
                        </div>
                        <div class="mb-3 col-12 col-lg-6">
                            <label for="harga" class="form-label">Harga</label>
                            <input type="text" name="harga" id="harga" class="form-control @error('price') is-invalid @enderror" placeholder="Masukkan price" value="{{old('harga') ?? $supplierProduct->harga}}">
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
                            <label for="stok" class="form-label">Stok</label>
                            <input type="text" name="stok" id="stok" class="form-control @error('stok') is-invalid @enderror" placeholder="Masukkan price" value="{{old('stok') ?? $supplierProduct->stok}}">
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
                            <label for="jam_tutup" class="form-label">Jam Tutup</label>
                            <input type="time" name="jam_tutup" id="jam_tutup" class="form-control @error('jam_tutup') is-invalid @enderror" placeholder="Masukkan price" value="{{old('jam_tutup')?? $supplierProduct->jam_tutup}}">
                            <small class="text-danger"></small>
                        </div>
                        <div class="mb-3 col-12 col-lg-6">
                            <label for="jam_buka" class="form-label">Jam Buka</label>
                            <input type="time" name="jam_buka" id="jam_buka" class="form-control @error('jam_buka') is-invalid @enderror" placeholder="Masukkan price" value="{{old('jam_buka')?? $supplierProduct->jam_buka}}">
                            <small class="text-danger"></small>
                        </div>
                    </div>
                </form>