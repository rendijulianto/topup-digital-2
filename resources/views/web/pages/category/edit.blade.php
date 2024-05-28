
<form action="{{route('admin.categories.update',['category'=>$category->id])}}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="name" class="form-label">Nama</label>
        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Masukkan nama" value="{{ old('name') ?? $category->name }}">
        <small class="text-danger"></small>
    </div>
</form>