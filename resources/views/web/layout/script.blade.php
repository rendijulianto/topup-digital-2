
<!-- Vendor js -->
<script src="{{asset('assets/js/vendor.min.js')}}"></script>
<script src="{{asset('assets/js/jquery.mask.min.js')}}"></script>
<script src="https://mottie.github.io/Keyboard/js/jquery.keyboard.js"></script>
<!-- https://mottie.github.io/Keyboard/js/jquery.mousewheel.js -->
<script src="https://mottie.github.io/Keyboard/js/jquery.mousewheel.js"></script>
<!-- https://mottie.github.io/Keyboard/js/jquery.keyboard.extension-typing.js -->
<script src="https://mottie.github.io/Keyboard/js/jquery.keyboard.extension-typing.js"></script>
<!-- App js -->
<script src="{{asset('assets/js/app.min.js')}}"></script>
<script src="{{asset('assets/js/custom.js')}}"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
    // start-transaction
    $(document).on('click', '#start-transaction', function() {
        // buat browser baru 
        window.open("/?isCustomer=true","_blank", "toolbar=yes,top=500,left=500,width=400,height=400");
    });
</script>

@yield('script')
