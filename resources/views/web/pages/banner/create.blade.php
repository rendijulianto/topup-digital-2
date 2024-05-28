
<form action="{{route('admin.banners.store')}}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="title" class="form-label">Judul</label>
        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" placeholder="Masukkan judul"
        value="{{old('title')}}">
        <small class="text-danger"></small>
    </div>
    <div class="mb-3">
        <label for="image" class="form-label">Logo</label>
        <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" placeholder="Masukkan gambar" accept="image/*" />
        <small class="text-danger"></small>
    </div>
</form>
