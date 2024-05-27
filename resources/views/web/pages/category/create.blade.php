
<form onkeydown="return event.key != 'Enter';" action="{{route('admin.categories.store')}}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="nama" class="form-label">Nama</label>
        <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" placeholder="Masukkan nama"
        value="{{old('nama')}}">
        <small class="text-danger"></small>
    </div>
</form>