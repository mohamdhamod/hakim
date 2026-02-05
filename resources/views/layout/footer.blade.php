<!-- Footer -->
@php
    // Determine if current locale is RTL. Adjust the list if you support additional RTL locales.
    $rtlLocales = ['ar', 'he', 'fa', 'ur'];
    $isRtl = in_array(App::getLocale(), $rtlLocales);
@endphp
<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            {{-- Left column: on md+ it should appear visually on the left in LTR, and on the right in RTL. --}}
            <div class="col-md-6 text-sm-center text-md-start  {{ $isRtl ? 'order-md-2' : 'order-md-1' }}">
                {{ __('translation.layout.home.footer.rights', ['year' => date('Y')]) }}
            </div>

            {{-- Right column: mirror ordering for RTL --}}
            <div class="col-md-6 {{ $isRtl ? 'order-md-1' : 'order-md-2' }}">
                <div class="text-sm-center text-md-end  d-none d-md-block">
                    {!! __('translation.layout.footer.storage_info_html') !!}
                </div>
            </div>
        </div>
    </div>
</footer>
