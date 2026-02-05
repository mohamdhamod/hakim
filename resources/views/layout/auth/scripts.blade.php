<script>
    var BASE_URL = '{{url('')}}' + '/';
    var _token = '{{csrf_token()}}';
</script>

<!-- App js -->
<script src="{{asset("js/sweetalert.min.js")}}"></script>
@vite(['resources/js/app.js'])

