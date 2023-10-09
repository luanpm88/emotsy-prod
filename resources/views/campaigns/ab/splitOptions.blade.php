@extends('layouts.core.frontend', [
    'menu' => 'campaign',
])

@section('title', trans('messages.campaigns') . " - " . trans('messages.recipients'))

@section('head')
    <script type="text/javascript" src="{{ URL::asset('core/js/group-manager.js') }}"></script>
@endsection

@section('page_header')

	<div class="topfix-header bg-light">
		<div class="topfix-container border-bottom d-flex align-items-center shadow-sm py-3 mb-4">
			<div class="topfix-search text-center" style="width: 100%">
                <div class="page-title pt-0">
                    <ul class="breadcrumb breadcrumb-caret position-right">
                        <li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ action("CampaignController@index") }}">{{ trans('messages.campaigns') }}</a></li>
                    </ul>
                </div>
                <h3 class="mb-0 mb-3 d-flex align-items-center justify-content-center">
                    <span class="text-semibold">
                        <img width="25px" class="icon-img d-inline-block me-2" src="{{ url('images/icons/'.$campaign->type.'.svg') }}" />
                        <span>{{ $campaign->name }}</span>
                    </span>
                    <span class="text-center text-muted badge badge-info ms-2">{{ trans('messages.campaign.type.' . $campaign->type) }}</span>
                </h3>
				@include('campaigns._steps', ['current' => 'ab'])
			</div>
		</div>
	</div>

@endsection

@section('content')
    <h1 class="mb-4">{{ trans('messages.campaign.ab.split_options') }}</h1>

    <form action="{{ action('CampaignController@abSplitOptions', $campaign->uid) }}" method="POST" class="form-validate-jqueryz recipients-form">
        {{ csrf_field() }}

        <input type="hidden" name="ab_winning_percent" value="{{ $campaign->ab_winning_percent }}">

        <div class="row">
            <div class="col-md-8">
                <h3 class="">{{ trans('messages.campaign.ab.split.divide_groups') }}</h3>

                <p class="mt-0">
                    {{ trans('messages.campaign.ab.split.divide_groups.intro') }}
                </p>
            </div>
        </div>

        <div class="mb-5">
            @if ($reciepientsCount < 5000)
                <div class="d-flex justify-content-center mb-4">
                    <div class="alert alert-info bg-white alert-center pe-4 border text-dark">
                        <p>{{ trans('messages.campaign.ab.recommendded_recipients') }}</p>
                    </div>
                </div>
            @endif

            <div split-control="container" class="position-relative">
                <div class="d-flex border" style="width:100%;">
                    <div split-control="a-box" style="width:25%;" class="border-end bg-gradient bg-info bg-opacity-10">
                        <div class="text-center p-3">
                            <div class="text-truncate">
                                <strong>{{ trans('messages.campaign.ab.version_a') }}</strong>
                            </div>
                            <div class="py-3 text-truncate">
                                <strong class="fw-semibold fs-6"><span split-control="value-a">25</span>%</strong>
                            </div>
                            <div class="text-truncate">
                                (~<span split-control="aprox-b">{{ number_format(round($reciepientsCount/4)) }}</span> {{ trans('messages.recipients') }})
                            </div>
                        </div>
                    </div>
                    <div split-control="b-box" style="width:25%;" class="border-end bg-gradient bg-info bg-opacity-10">
                        <div class="text-center p-3">
                            <div class="text-truncate">
                                <strong>{{ trans('messages.campaign.ab.version_b') }}</strong>
                            </div>
                            <div class="py-3 text-truncate">
                                <strong class="fw-semibold fs-6"><span split-control="value-b">25</span>%</strong>
                            </div>
                            <div class="text-truncate">
                                (~<span split-control="aprox-b">{{ number_format(round($reciepientsCount/4)) }}</span> {{ trans('messages.recipients') }})
                            </div>
                        </div>
                    </div>
                    <div split-control="anchor" style="position: absolute;left:calc(50% - 16px);top:calc(50% - 15px);">
                        <svg style="width:30px;" xmlns="http://www.w3.org/2000/svg" id="Layer_2" viewBox="0 0 223.5 223.5"><g id="Layer_1-2"><circle cx="111.75" cy="111.75" r="111.75" style="fill:#231f20; stroke-width:0px;"/><rect x="86.17" y="105.75" width="51.16" height="12" rx="6" ry="6" style="fill:#fff; stroke-width:0px;"/><path d="m91.15,144.46l-51.13-29.53c-2.45-1.42-2.45-4.96,0-6.37l51.13-29.53c2.45-1.42,5.52.35,5.52,3.19v59.05c0,2.83-3.07,4.6-5.52,3.19Z" style="fill:#fff; stroke-width:0px;"/><path d="m132.35,144.46l51.13-29.53c2.45-1.42,2.45-4.96,0-6.37l-51.13-29.53c-2.45-1.42-5.52.35-5.52,3.19v59.05c0,2.83,3.07,4.6,5.52,3.19Z" style="fill:#fff; stroke-width:0px;"/></g></svg>
                    </div>
                    <div split-control="winning-box" style="width:50%;" class="bg-gradient bg-success bg-opacity-10">
                        <div class="text-center p-3">
                            <div class="text-truncate">
                                <strong>{{ trans('messages.campaign.ab.version_winning') }}</strong>
                            </div>
                            <div class="py-3 text-truncate">
                                <strong class="fw-semibold fs-6"><span split-control="value-winning">50</span>%</strong>
                            </div>
                            <div class="text-truncate">
                                (~<span split-control="aprox-b">{{ number_format(round($reciepientsCount/2)) }}</span> {{ trans('messages.recipients') }})
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div ab-control="winning-none" class="my-4">
                <p class="mt-3 alert alert-info bg-light text-dark rounded">{{ trans('messages.campaign.ab.winning_version.none.intro') }}</p>
            </div>
        </div>
        
        <div ab-control="winning-option">
            <div class="row mb-5">
                <div class="col-md-8">
                    <h4 class="">{{ trans('messages.campaign.ab.split.how_winning_chosen') }}</h4>

                    <p class="mt-0 mb-3">
                        {{ trans('messages.campaign.ab.split.metric_for_winning_version') }}
                    </p>

                    <div>
                        <label for="ab_winning_metric_open" class="mb-2">
                            <input {{ $campaign->ab_winning_metric == 'open' ? 'checked' : '' }} id="ab_winning_metric_open" type="radio" name="ab_winning_metric" value="open" class="styled">
                            <span class="check-symbol me-2"></span>
                            {!! trans('messages.campaign.ab.split.winning_metric.open.intro') !!}
                        </label>
                        <label for="ab_winning_metric_click">
                            <input {{ $campaign->ab_winning_metric == 'click' ? 'checked' : '' }} id="ab_winning_metric_click" type="radio" name="ab_winning_metric" value="click" class="styled">
                            <span class="check-symbol me-2"></span>
                            {!! trans('messages.campaign.ab.split.winning_metric.click.intro') !!}
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="row mb-5">
                <div class="col-md-8">
                    <h4 class="">{{ trans('messages.campaign.ab.split.how_long_run') }}</h4>

                    <p class="mt-0 mb-3">
                        {{ trans('messages.campaign.ab.split.how_long_run.intro') }}
                    </p>

                    <div class="d-flex align-items-center">
                        <div class="form-group mb-0 text-center me-2" style="width:60px">
                            <select name="ab_test_duration_value" id="" class="select">
                                @for($i=1;$i<100;$i++)
                                    <option {{ $campaign->ab_test_duration_value == $i ? 'selected' : '' }} value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group mb-0 text-center me-1"  style="width:90px">
                            <select name="ab_test_duration_unit" id="" class="select">
                                @foreach([
                                    ['value' => 'hour', 'text' => trans('messages.hour')],
                                    ['value' => 'day', 'text' => trans('messages.day')],
                                    ['value' => 'week', 'text' => trans('messages.week')],
                                    ['value' => 'month', 'text' => trans('messages.month')],
                                    ['value' => 'year', 'text' => trans('messages.year')],
                                ] as $option)
                                    <option {{ $campaign->ab_test_duration_unit == $option['value'] ? 'selected' : '' }} value="{{ $option['value'] }}">{{ $option['text'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <span>(s)</span>
                    </div>
                </div>
            </div>  
        </div>

        <hr>

        <div class="text-center">
            <button class="btn btn-secondary">{{ trans('messages.save_and_next') }} <span class="material-symbols-rounded">arrow_forward</span> </button>
        </div>
    <form>

    <script>
        var abSplitManager;
        var abSplitOptions

        $(function() {
            // split manager
            abSplitManager = new ABSplitManager({
                container: document.querySelector('[split-control="container"]'),
                totalRecipients: {{ $reciepientsCount }},
                currentWinningPercent: {{ $campaign->ab_winning_percent }},

                winningBoxOption: document.querySelector('[ab-control="winning-option"]'),
                winningBoxNone: document.querySelector('[ab-control="winning-none"]'),
            });
        });
            
        var ABSplitManager = class {
            constructor(options) {
                this.container = options.container;
                this.dragging = false;
                this.winningPercentValue = options.currentWinningPercent;

                console.log(this.winningPercentValue);
                console.log(this.getWinningPercent());

                // approx
                this.totalRecipients = options.totalRecipients;

                // anchor
                this.startDraggingLeft;
                // this.currentMarginLeft = '0px';

                // box size
                this.currentWinningPercent = options.currentWinningPercent;
                this.currentWinningBoxWidth = this.getWinningBox().clientWidth;

                // option boxes
                this.winningBoxOption = options.winningBoxOption;
                this.winningBoxNone = options.winningBoxNone;

                // init value
                this.updateByPercents(this.winningPercentValue, (100 - this.winningPercentValue)/2, (100 - this.winningPercentValue)/2);

                // event
                this.getAnchor().addEventListener('mousedown', (e) => {
                    e.preventDefault();

                    //
                    this.startDragging(e.clientX);
                });

                document.body.addEventListener('mousemove', (e) => {
                    // when dragging
                    if (this.isDragging()) {
                        e.preventDefault();

                        var move = this.getMoveFromClientX(e.clientX);

                        if (this.isOutOfRange(move)) {
                            this.resizeBoxes(move);
                        } else if (this.atRightSide(move)) {
                            this.moveAllToRight();
                        }
                    }
                });

                document.body.addEventListener('mouseup', (e) => {
                    //
                    if (this.isDragging()) {
                        this.endDragging();
                    }
                });
            }

            setWinningPercent(percent) {
                this.winningPercentValue = percent;

                // update input
                document.querySelector('[name="ab_winning_percent"]').value = this.winningPercentValue;
            }

            getWinningPercent() {
                return this.winningPercentValue;
            }

            getMoveFromClientX(clientX) {
                return clientX - this.startDraggingLeft;
            }

            calcWinningPercentByMove(move) {
                var percentChange = parseFloat((move/this.getBoxesWidth())*100);
                var winningPercent = parseFloat(this.currentWinningPercent) - percentChange;

                return winningPercent;
            }

            isOutOfRange(move) {
                var winningPercent = this.calcWinningPercentByMove(move);

                return winningPercent <= 90 && winningPercent >= 3;
            }

            atRightSide(move) {
                var winningPercent = this.calcWinningPercentByMove(move);

                return winningPercent < 3;
            }

            resizeBoxes(move) {
                var percentChange = parseFloat((move/this.getBoxesWidth())*100);

                var winningPercent = parseFloat(this.currentWinningPercent) - percentChange;
                var aPercent = parseFloat(100 - this.currentWinningPercent)/2 + percentChange/2;
                var bPercent = parseFloat(100 - this.currentWinningPercent)/2 + percentChange/2;

                // update by sizes
                this.updateByPercents(winningPercent, aPercent, bPercent);
            }

            updateByPercents(winningPercent, aPercent, bPercent) {
                // update sizes
                this.getWinningBox().style.width = winningPercent + '%';
                this.getABox().style.width = aPercent + '%';
                this.getBBox().style.width = bPercent + '%';

                // update label
                this.getValueALabel().innerHTML = (100 - parseInt(winningPercent))/2;
                this.getValueBLabel().innerHTML = (100 - parseInt(winningPercent))/2;
                this.getValueWinningLabel().innerHTML = parseInt(winningPercent);

                // update aprox
                this.updateAprox(this.getABox(), aPercent);
                this.updateAprox(this.getBBox(), bPercent);
                this.updateAprox(this.getWinningBox(), winningPercent);

                // move anchor
                this.getAnchor().style.left = 'calc('+(aPercent+bPercent)+'% - 16px)';

                // set winning percent
                this.setWinningPercent(winningPercent);

                // show/hide options
                if (this.getWinningPercent() == 0) {
                    console.log(this.getWinningPercent());
                    this.winningBoxOption.style.display = 'none';
                    this.winningBoxNone.style.display = 'block';
                } else {
                    this.winningBoxOption.style.display = 'block';
                    this.winningBoxNone.style.display = 'none';
                }
            }

            moveAllToRight()
            {
                this.updateByPercents(0, 50, 50);
            }

            updateAprox(box, percent) {
                var value = parseInt(percent/100 * this.totalRecipients)
                box.querySelector('[split-control="aprox-b"]').innerHTML = value.toLocaleString();
            }

            getAnchorLeft() {
                return this.getAnchor().getBoundingClientRect().left;
            }

            startDragging(left) {
                this.dragging = true;
                this.startDraggingLeft = left;
                this.currentWinningPercent = this.getWinningBox().style.width.replace('%','');
            }

            endDragging() {
                this.dragging = false;
                // this.currentMarginLeft = parseInt(this.getAnchor().style.marginLeft.replace('px', ''));
                this.currentWinningPercent = this.getWinningBox().style.width.replace('%','');
            }

            isDragging() {
                return this.dragging == true;
            }

            getValueALabel() {
                return this.container.querySelector('[split-control="value-a"]');
            }

            getValueBLabel() {
                return this.container.querySelector('[split-control="value-b"]');
            }

            getValueWinningLabel() {
                return this.container.querySelector('[split-control="value-winning"]');
            }

            getABox() {
                return this.container.querySelector('[split-control="a-box"]');
            }

            getBBox() {
                return this.container.querySelector('[split-control="b-box"]');
            }

            getWinningBox() {
                return this.container.querySelector('[split-control="winning-box"]');
            }

            getBoxesWidth() {
                return this.getABox().clientWidth + this.getWinningBox().clientWidth + this.getBBox().clientWidth;
            }

            getAnchor() {
                return this.container.querySelector('[split-control="anchor"]');
            }
        }

    </script>
@endsection
