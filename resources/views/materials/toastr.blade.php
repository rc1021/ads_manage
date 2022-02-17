@if (session()->has('success'))
<script>toastr.success('{{ session("success") }}', null, {timeOut: 1.5 * 1000})</script>
@endif
@if (session()->has('error'))
<script>toastr.error('{{ session("error") }}', null, {timeOut: 5 * 1000})</script>
@endif
@if (session()->has('warning'))
<script>toastr.warning('{{ session("warning") }}', null, {timeOut: 3 * 1000})</script>
@endif
@if (session()->has('info'))
<script>toastr.info('{{ session("info") }}', null, {timeOut: 2 * 1000})</script>
@endif
