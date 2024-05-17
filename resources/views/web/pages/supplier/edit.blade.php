
                <form action="{{route('admin.suppliers.update',$supplier->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" name="nama" id="nama" class="form-control" placeholder="Nama" value="{{old('nama') ?? $supplier->nama}}">
                        <small class="text-danger"></small>
                    </div>
    
                </form>
       