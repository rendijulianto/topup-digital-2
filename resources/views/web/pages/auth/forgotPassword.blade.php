
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>
        {{ $websiteData->name }} - Lupa Password
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Aplikasi Top up Digital" name="description" />
    <meta content="Rendi Julianto" name="author" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{$websiteData->logo_website_url}}" />

    <!-- Theme Config Js -->
    <script src="{{asset('assets/js/config.js')}}"></script>

    <!-- App css -->
    <link href="{{asset('assets/css/app.min.css')}}" rel="stylesheet" type="text/css" id="app-style" />

    <!-- Icons css -->
    <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="authentication-bg position-relative">
    <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5 position-relative">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-8 col-lg-10">
                    <div class="card overflow-hidden">
                        <div class="row g-0">
                            <div class="col-lg-6 d-none d-lg-block p-2">
                                <img src="assets/images/auth-img.jpg" alt="" class="img-fluid rounded h-100">
                            </div>
                            <div class="col-lg-6">
                                <div class="d-flex flex-column h-100">
                                  
                                    <div class="p-4 my-auto">
       
                                        <div class="d-flex align-items-center mb-4">
                                            <img src="{{$websiteData->logo_website_url}}" alt="dark logo" height="75">
                                            <div>
                                                <h4 class="ml-5 mb-0 mt-2" style="margin-left: 10px;">
                                                    Lupa Kata Sandi
                                                </h4>
                                             
                                            </div>
                                        </div>
                                        @if (session('error'))
                                      
                                        <script>
                                            Swal.fire({
                                                title: 'Oops!',
                                                text: '{{ session('error') }}',
                                                icon: 'error',
                                                confirmButtonText: 'Close'
                                            });
                                        </script>
                                        @elseif(session('success'))
                                            <script>
                                                Swal.fire({
                                                    title: 'Berhasil!',
                                                    text: '{{ session('success') }}',
                                                    icon: 'success',
                                                    confirmButtonText: 'Close'
                                                });
                                            </script>
                                        @endif
                                        <!-- form -->
                                        <form action="{{route('postForgotPassword')}}" method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email</label>
                                                <input class="form-control" type="email" id="email" name="email"
                                                    placeholder="Masukkan email anda" autofocus="">
                                                @if ($errors->has('email'))
                                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                                @endif
                                            </div>
                                            <div class="mb-3">
                                                <a href="{{route('login')}}" class="text-muted float-end"><small>
                                                    Ingat Kata Sandi ?</small></a>
                                               
                                            </div>
                                            
                                            <div class="mb-0 mt-2 text-start">
                                                <button class="btn btn-danger w-100" type="submit"><span class="fw-bold">Reset</span> </button>
                                            </div>


                                        </form>
                                        <!-- end form-->
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div>
                    </div>
                </div>
                <!-- end row -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->

    <footer class="footer footer-alt fw-medium">
        <span class="text-dark">
            <script>document.write(new Date().getFullYear())</script> © {{ config('app.name') }} - Rendi Julianto
        </span>
    </footer>
    <!-- Vendor js -->
    <script src="{{asset('assets/js/vendor.min.js')}}"></script>

    <!-- App js -->
    <script src="{{asset('assets/js/app.min.js')}}"></script>
</body>

</html>
