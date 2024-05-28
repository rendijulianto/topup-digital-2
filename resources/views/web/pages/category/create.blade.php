
<form onkeydown="return event.key != 'Enter';" action="{{route('admin.categories.store')}}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="name" class="form-label">Nama</label>
        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Masukkan nama"
        value="{{old('name')}}">
        <small class="text-danger"></small>
    </div>
</form>