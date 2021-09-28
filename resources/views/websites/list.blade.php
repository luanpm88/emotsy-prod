@if ($websites->count() > 0)
	<table class="table table-box pml-table mt-2">
		@foreach ($websites as $key => $website)
            <tr>
                <td width="1%">
                    <div class="text-nowrap">
                        <div class="checkbox inline">
                            <label>
                                <input type="checkbox" class="node styled"
                                    name="uids[]"
                                    value="{{ $website->uid }}"
                                />
                            </label>
                        </div>
                    </div>
                </td>
                <td width="1%">
                    <a href="{{ action('WebsiteController@show', [
                        'uid' => $website->uid,
                    ]) }}" class="">
                        <svg width="50px" height="50px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd" viewBox="0 0 6720 6720">
                            <defs>
                            <style type="text/css">
                            
                            .fil1 {fill:none}
                            .fil0 {fill:black;fill-rule:nonzero}
                            
                            </style>
                            </defs>
                            <g id="Layer_x0020_1">
                            <metadata id="CorelCorpID_0Corel-Layer"></metadata>
                            <path class="fil0" d="M3360 1124c485,0 935,155 1302,419 376,271 665,655 816,1104l0 0c12,37 -7,76 -44,88 -8,3 -16,4 -24,4l-4101 0c-39,0 -70,-31 -70,-70 0,-10 2,-19 6,-28 151,-446 439,-829 814,-1098 367,-264 816,-419 1302,-419zm1220 532c-343,-247 -765,-392 -1220,-392 -456,0 -877,145 -1220,392 -327,235 -583,562 -732,943l3904 0c-148,-381 -405,-708 -732,-943zm895 2422c-151,446 -439,829 -814,1098 -367,264 -816,419 -1302,419 -485,0 -935,-155 -1302,-419 -376,-271 -665,-655 -816,-1104l0 0c-12,-37 7,-76 44,-88 8,-3 16,-4 24,-4l4101 0c39,0 70,31 70,70 0,10 -2,19 -6,28zm-895 985c327,-235 583,-562 732,-943l-3904 0c148,381 405,708 732,943 343,247 765,392 1220,392 456,0 877,-145 1220,-392z"></path>
                            <path class="fil0" d="M5086 4778c-208,254 -471,461 -770,603 -290,138 -614,215 -956,215 -342,0 -666,-77 -956,-215 -301,-143 -565,-352 -774,-607l0 0c-24,-30 -20,-74 10,-98 7,-6 16,-10 24,-13 266,-108 543,-191 828,-247 282,-55 572,-84 868,-84 296,0 587,29 868,84 288,56 567,141 834,250l0 0c36,15 53,55 38,91 -3,9 -8,16 -15,22zm-830 477c253,-120 479,-290 663,-497 -231,-88 -471,-158 -719,-206 -272,-53 -554,-81 -841,-81 -288,0 -569,28 -841,81 -248,49 -488,118 -719,206 185,207 410,376 663,497 272,129 576,201 897,201 321,0 625,-72 897,-201z"></path>
                            <path class="fil0" d="M4920 1962c-185,-207 -410,-376 -663,-497 -272,-129 -576,-201 -897,-201 -321,0 -625,72 -897,201 -253,120 -479,290 -663,497 231,88 471,158 719,206 272,53 554,81 841,81 288,0 569,-28 841,-81 248,-49 488,-118 719,-206zm-604 -623c299,142 562,349 770,603 6,6 11,14 15,22 15,36 -3,76 -38,91l0 0c-268,109 -547,194 -834,250 -282,55 -572,84 -868,84 -296,0 -587,-29 -868,-84 -285,-56 -562,-139 -828,-247 -9,-3 -17,-7 -24,-13 -30,-24 -34,-68 -10,-98l0 0c209,-256 473,-464 774,-607 290,-138 614,-215 956,-215 342,0 666,77 956,215z"></path>
                            <path class="fil0" d="M3360 1124c272,0 520,161 721,434 198,271 350,654 429,1099l0 0c7,38 -19,74 -57,81 -4,1 -9,1 -13,1l-2161 0c-39,0 -70,-31 -70,-70 0,-5 1,-10 2,-15 79,-444 231,-825 428,-1095 200,-273 449,-434 721,-434zm608 517c-174,-237 -384,-377 -608,-377 -224,0 -434,140 -608,377 -174,237 -310,570 -388,959l1993 0c-78,-389 -215,-721 -388,-959zm541 2425c-79,443 -231,825 -428,1095 -200,273 -449,434 -721,434 -272,0 -520,-161 -721,-434 -198,-271 -350,-654 -429,-1099l0 0c-7,-38 19,-74 57,-81 4,-1 9,-1 13,-1l2161 0c39,0 70,31 70,70 0,5 -1,10 -2,15zm-541 1013c174,-237 310,-570 388,-959l-1993 0c78,389 215,721 388,959 174,237 384,377 608,377 224,0 434,-140 608,-377z"></path>
                            <path class="fil0" d="M870 2599l4980 0 70 0 0 70 0 1381 0 70 -70 0 -4980 0 -70 0 0 -70 0 -1381 0 -70 70 0zm4911 140l-4841 0 0 1242 4841 0 0 -1242z"></path>
                            <path class="fil0" d="M1484 3766l-249 -812 142 0 129 469 48 174c2,-9 16,-65 42,-168l129 -476 142 0 122 471 41 155 46 -157 139 -470 134 0 -254 812 -143 0 -129 -487 -32 -139 -164 625 -144 0zm1567 0l-249 -812 142 0 129 469 48 174c2,-9 16,-65 42,-168l129 -476 142 0 122 471 41 155 46 -157 139 -470 134 0 -254 812 -143 0 -129 -487 -32 -139 -164 625 -144 0zm1567 0l-249 -812 142 0 129 469 48 174c2,-9 16,-65 42,-168l129 -476 142 0 122 471 41 155 46 -157 139 -470 134 0 -254 812 -143 0 -129 -487 -32 -139 -164 625 -144 0z"></path>
                            </g>
                            <rect class="fil1" width="6720" height="6720"></rect>
                        </svg>
                    </a>
                </td>
                <td>
                    <a class="kq_search fs-6 d-block mb-1 fw-600" href="{{ action('WebsiteController@show', [
                        'uid' => $website->uid,
                    ]) }}">
                        {{ $website->title }}
                    </a>

                    <span class="text-muted2">
                        {{ $website->url }}
                    </span>
                </td>

                <td>
                    <span class="text-muted2 list-status pull-left">
                        <span class="label label-flat bg-{{ $website->status }}">{{ trans('messages.website.status.' . $website->status) }}</span>
                    </span>
                </td>

                <td class="text-end">
                    <a href="{{ action('WebsiteController@show', $website->uid) }}"
                        role="button" class="btn btn-primary form-popup-preview">
                        <span class="material-icons-outlined me-1">
                            code
                            </span> {{ trans('messages.website.view_code') }}
                    </a>

                    @if (Auth::user()->customer->can('disconnect', $website))
                        <a href="{{ action('WebsiteController@disconnect', $website->uid) }}"
                            link-method="POST"
                            role="button" class="btn btn-secondary list-action-single">
                            <span class="material-icons-outlined me-1">
                                pause_circle
                                </span> {{ trans('messages.website.disconnect') }}
                        </a>
                    @endif
                    
                    @if (Auth::user()->customer->can('connect', $website))
                        <a href="{{ action('WebsiteController@connect', $website->uid) }}"
                            role="button" class="btn btn-secondary connect-site">
                            <span class="material-icons-outlined me-1">
                                pause_circle
                                </span> {{ trans('messages.website.connect') }}
                        </a>
                    @endif

                    @if (Auth::user()->customer->can('update', $website))
                        <a href="{{ action('WebsiteController@check', $website->uid) }}"
                            role="button" class="btn btn-secondary check-site-connect">
                            <span class="material-icons-round me-1">
                                task_alt
                                </span> {{ trans('messages.website.check_connection') }}
                        </a>
                    @endif

                    @if (
                        Auth::user()->customer->can('update', $website)
                    )
                        <div class="btn-group">
                            <button role="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown"></button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if (Auth::user()->customer->can('delete', $website))
                                    <li><a
                                        class="dropdown-item list-action-single"
                                        link-method="POST"
                                        link-confirm="{{ trans('messages.websites.delete.confirm') }}"
                                        href="{{ action('WebsiteController@delete', ["uids" => $website->uid]) }}">
                                        <span class="material-icons-outlined me-2">
delete_outline
</span> {{ trans("messages.delete") }}</a></li>
                                @endif
                            </ul>
                        </div>
                    @endif
                </td>
            </tr>
        @endforeach
	</table>
	@include('elements/_per_page_select', ["items" => $websites])

    <script>
        var WebsitesList = {
            check: function(url) {
                addMaskLoading();
                $.ajax({
                    url : url,
                    type: "GET",
                    globalError: false
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    new Dialog('alert', {
                        title: '{{ trans('messages.notify.error') }}',
                        message: JSON.parse(jqXHR.responseText).error
                    });

                    removeMaskLoading();
                }).done(function(res) {
                    new Dialog('alert', {
                        title: '{{ trans('messages.notify.success') }}',
                        message: res.message
                    });

                    removeMaskLoading();
                });
            },

            connect: function(url) {
                addMaskLoading();
                $.ajax({
                    url : url,
                    type: "POST",
                    data: {
                        _token: CSRF_TOKEN,
                    },
                    globalError: false
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    new Dialog('alert', {
                        title: '{{ trans('messages.notify.error') }}',
                        message: JSON.parse(jqXHR.responseText).error
                    });

                    removeMaskLoading();
                }).done(function(res) {
                    new notify({
                        message: res.message
                    });

                    removeMaskLoading();

                    WebsitesIndex.getList().load();
                });
            }
        }
        $(function() {
            $('.check-site-connect').on('click', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');

                WebsitesList.check(url);
            });

            $('.connect-site').on('click', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');

                WebsitesList.connect(url);
            });
        });
    </script>
@elseif (!empty(request()->keyword))
	<div class="empty-list">
		<span class="material-icons-outlined">
			auto_awesome
			</span>
		<span class="line-1">
			{{ trans('messages.no_search_result') }}
		</span>
	</div>
@else
	<div class="empty-list">
		<span class="material-icons-outlined">
			auto_awesome
			</span>
		<span class="line-1">
			{{ trans('messages.form.empty_list') }}
		</span>
	</div>
@endif
