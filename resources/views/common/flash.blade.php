<script>
    @foreach (['danger', 'warning', 'success', 'info', 'error', 'warning'] as $msg)

        @if(Session::has('alert-' . $msg))
            $(document).ready(function() {
                //// Success alert
                //swal({
                //    title: '{!! preg_replace('/[\r\n]+/', ' ', Session::get('alert-' . $msg)) !!}',
                //    text: "",
                //    confirmButtonColor: "#00695C",
                //    type: "{{ $msg }}",
                //    allowOutsideClick: true,
                //    confirmButtonText: "{{ trans('messages.ok') }}",
                //    customClass: "swl-{{ $msg }}"
                //});

                new PNotify({
                    title: '{{ trans('messages.notify.' . $msg)}}',
                    text: '{!! preg_replace('/[\r\n]+/', ' ', Session::get('alert-' . $msg)) !!}',
                    type: "{{ $msg }}",
                    stack: {
                        "dir1": "up",
                        "dir2": "right",
                        "firstpos1": 10,
                        "firstpos2": 10
                    }
                });

            });

        @endif
    @endforeach

    @if (request()->session()->get('user-activated'))
        $(document).ready(function() {
            // Success alert
            swal({
                title: "{!! request()->session()->get('user-activated') !!}",
                text: "",
                confirmButtonColor: "#00695C",
                type: "success",
                allowOutsideClick: true,
                confirmButtonText: "{{ trans('messages.ok') }}",
                customClass: "swl-success",
                html:true
            });

        });
        <?php request()->session()->forget('user-activated'); ?>
    @endif
</script>
