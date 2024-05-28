
                <form action="{{route('admin.suppliers.update',$supplier->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Nama" value="{{old('name') ?? $supplier->name}}">
                        <small class="text-danger"></small>
                    </div>
    
                </form>
       