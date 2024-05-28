
<form onkeydown="return event.key != 'Enter';"
    action="{{route('admin.brands.update',['brand'=>$brand->id])}}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="name" class="form-label">Nama</label>
        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Masukkan nama" value="{{ old('name') ?? $brand->name }}">
        <small class="text-danger"></small>
    </div>
    <div class="mb-3">
        <label for="logo" class="form-label">Logo</label>
        <input type="file" name="logo" id="logo" class="form-control @error('logo') is-invalid @enderror" placeholder="Masukkan logo" accept="image/*" />
        <small class="text-danger"></small>
    </div>
</form>
     
