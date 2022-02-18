{{-- success alter --}}
@if (session()->has('success'))
<script>
    window.addEventListener('load', function () {
        toastr.success('{{ session("success") }}', null, {timeOut: 1.5 * 1000});
    });
</script>
@endif
{{-- warning alter --}}
@if (session()->has('warning'))
<script>
    window.addEventListener('load', function () {
        toastr.warning('{{ session("warning") }}', null, {timeOut: 3 * 1000});
    });
</script>
@endif
{{-- info alter --}}
@if (session()->has('info'))
<script>
    window.addEventListener('load', function () {
        toastr.info('{{ session("info") }}', null, {timeOut: 2 * 1000});
    });
</script>
@endif
{{-- error alter --}}
@if (session()->has('error'))
<script>
    window.addEventListener('load', function () {
        toastr.error('{{ session("error") }}', null, {timeOut: 5 * 1000});
    });
</script>
@elseif ($errors->any())
<script>
    window.addEventListener('load', function () {
        @foreach ($errors->all() as $error)
        toastr.error('{{ $error }}', null, {timeOut: 2 * 1000});
        @endforeach
    });
</script>
@endif
