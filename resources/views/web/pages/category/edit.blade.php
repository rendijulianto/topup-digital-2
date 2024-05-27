
<form action="{{route('admin.categories.update',['category'=>$category->id])}}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="nama" class="form-label">Nama</label>
        <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" placeholder="Masukkan nama" value="{{ old('nama') ?? $category->nama }}">
        <small class="text-danger"></small>
    </div>
</form>