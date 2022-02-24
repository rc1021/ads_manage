@if (session()->has('toastr'))
    @php
        $toastr     = session("toastr")->getMessages();
        $toastr_type       = data_get($toastr, 'type.0', 'success');
        $toastr_message    = data_get($toastr, 'message.0', '');
        $toastr_options    = json_encode(data_get($toastr, 'options', []));
    @endphp
    <script>
        window.addEventListener("load", function () {
            toastr.{{ $toastr_type }}('{!!  $toastr_message  !!}', null, {!! $toastr_options !!});
        });
    </script>
@endif
