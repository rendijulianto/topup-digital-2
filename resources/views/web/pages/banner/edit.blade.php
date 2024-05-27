
<form action="{{route('admin.banners.update',['banner'=> $banner->id])}}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="judul" class="form-label">Nama</label>
        <input type="text" name="judul" id="judul" class="form-control @error('judul') is-invalid @enderror" placeholder="Masukkan judul" value="{{ old('judul') ?? $banner->judul }}">
        <small class="text-danger"></small>
    </div>
    <div class="mb-3">
        <label for="gambar" class="form-label">Logo</label>
        <input type="file" name="gambar" id="gambar" class="form-control @error('gambar') is-invalid @enderror" placeholder="Masukkan gambar" accept="image/*" />
        <small class="text-danger"></small>
    </div>
</form>
    
