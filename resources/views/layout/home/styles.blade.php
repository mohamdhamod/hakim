@vite(['resources/css/app.css'])
<link rel="stylesheet" href="{{ asset('css/print.css') }}" media="print">
@stack("extra_styles")
<style>
    html[dir=rtl] .content-page , html[dir=ltr] .content-page {
        margin-left: 0;
        margin-right: 0;
        padding:0
    }
</style>
