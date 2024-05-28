
<form action="{{route('admin.banners.update',['banner'=> $banner->id])}}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="title" class="form-label">Judul</label>
        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" placeholder="Masukkan judul" value="{{ old('title') ?? $banner->title }}">
        <small class="text-danger"></small>
    </div>
    <div class="mb-3">
        <label for="image" class="form-label">Gambar</label>
        <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" placeholder="Masukkan gambar" accept="image/*" />
        <small class="text-danger"></small>
    </div>
</form>
    
