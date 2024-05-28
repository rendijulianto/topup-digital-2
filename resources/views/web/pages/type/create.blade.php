
                <form action="{{route('admin.types.store')}}" onkeydown="return event.key != 'Enter';" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Masukkan Nama"
                        value="{{old('name')}}">
                        <small class="text-danger"></small>
                    </div>
                
                </form>