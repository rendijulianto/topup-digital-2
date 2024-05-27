
<!DOCTYPE html>
<html lang="id">
    @include('web.layout.head')
    <body>
        <!-- Begin page -->
        <div class="wrapper">

            <!-- ============================================================== -->
            <!-- Start Header -->
            <!-- ============================================================== -->
            @include('web.layout.header')
            <!-- ============================================================== -->
            <!-- End Header -->
            <!-- ============================================================== -->

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->

            <div class="content-page">
                <div class="content">

                    <!-- Start Content-->
                    <div class="container-fluid px-xl-5">

                        <!-- start breadcrumb -->
                        @yield('breadcrumb')
                        <!-- end breadcrumb -->
                        <!-- start content  -->
                        @yield('content')
                        <!-- end content -->

                    </div> <!-- container -->

                </div> <!-- content -->

                <!-- Footer Start -->
                @include('web.layout.footer')
                <!-- end Footer -->

            </div>

            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->

        </div>
        <!-- END wrapper -->



        <!-- Scripts -->
        @include('web.layout.script')
        <script>
        $(document).ready(function () {

            // cek apakah ada class keyboard-virtual
            if ($('.keyboard-virtual').length) {
              
        var regex = /[^\d-.]/g; // Definisi regex
    
   
    $('.keyboard-virtual').keyboard({
    layout: 'custom',
    customLayout: {
        'normal': [
            '1 2 3 {b}',
            '4 5 6 {clear}',
            '7 8 9 {a}',
            '{empty} 0 {empty}  {empty} '
        ]
    },
    autoAccept: true, // Mengaktifkan autoAccept
    restrictInput: true,
    autoAcceptSuggestion: false,
    usePreview : false,

    change: function(e, keyboard, el) {
        var change,
            val = keyboard.$preview.val().replace(regex, ''),
            c = $.keyboard.caret(keyboard.$preview),
            start = c.start,
            end = c.end,
            restrict = val.match(regex);
        if (restrict) {
            restrict = restrict.slice(1).join('');
        } else {
            restrict = val;
        }
        keyboard.$preview.val(restrict);
        change = restrict.length - val.length;
        start += change;
        end += change;
        $.keyboard.caret(keyboard.$preview, start, end);
        keyboard.checkDecimal();
    },
    // set value 
}).addTyping({
    showTyping: true,
    delay: 250
}).on('accepted', function(e, keyboard, el) {
    // Ketika input diterima, trigger event keyup pada elemen dengan ID 'number'
    if ($(el).attr('id') === 'number') {
        $('#number').trigger('keyup');
    } else if($(el).attr('id') === 'amount') {
        $('#amount').trigger('keyup');
    }
});


$('.keyboard-virtual').on('click', function () {
    var keyboard = $('.ui-keyboard');
    var input = $(this);
    var inputTop = input.offset().top;
    var inputLeft = input.offset().left;
    var inputHeight = input.outerHeight();
    var keyboardHeight = keyboard.outerHeight();
    var modalHeight = input.closest('.modal').height();
    var modalScrollTop = input.closest('.modal').scrollTop();
    var top = inputTop - modalScrollTop + inputHeight + 10;
    var left = inputLeft;
    
    // Periksa apakah keyboard akan melewati bagian bawah modal
    if (top + keyboardHeight > modalHeight) {
        // Jika iya, posisikan keyboard di atas input
        top = inputTop - modalScrollTop - keyboardHeight - 10;
    }

    keyboard.css({
        top: top,
        left: left
    });
});
            }
            
        });
        var idleTime = 0;
        $(document).ready(function () {
            //Increment the idle time counter every minute.
            var idleInterval = setInterval(timerIncrement, 60000); // 1 minute

            //Zero the idle timer on mouse movement.
            $(this).mousemove(function (e) {
                idleTime = 0;
            });
            $(this).keypress(function (e) {
                idleTime = 0;
            });

            function timerIncrement() {
                idleTime = idleTime + 1;
                if (idleTime > 1) { // 20 minutes
                    @if(request()->isGuest == 'true')
                        window.location.href = "{{route('dashboard')}}?isGuest=true";
                    @endif
                }
            }
        });

      </script>
    </body>
</html>
