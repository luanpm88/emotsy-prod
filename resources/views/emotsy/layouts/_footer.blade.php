<div class="footer text-muted mt-5 py-3 small">
    <div class="row w-100" >
        <div class="col-md-6">
            <p class="mb-0">{!! trans('messages.brand.copyright', ['app_name' => get_app_name()] ) !!}</p>
        </div>
        <div class="col-md-6 text-end">
            <div class="dropdown d-inline-block">
                <a class=" toolbar-item dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ Acelle\Model\Language::where('code', language_code())->first()->name }}
                </a>

                <ul class="dropdown-menu">
                    @foreach (Acelle\Model\Language::active()->get() as $language)
                        @if ($language->code != language_code()) 
                            <li><a class="dropdown-item" href="{{ action('AccountController@setLanguage', [
                                'code' => $language->code,
                            ]) }}">{{ $language->name }}</a></li>
                        @endif
                    @endforeach
                </ul>
            </div> 
        </div>
    </div>
</div>