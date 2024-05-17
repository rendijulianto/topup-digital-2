
                <form action="{{route('admin.suppliers.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" name="nama" id="nama" class="form-control" placeholder="Nama" value="{{old('nama')}}">
                        <small class="text-danger"></small>
                    </div>
          
                </form>
