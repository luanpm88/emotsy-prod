@extends('layouts.core.frontend')

@section('title', trans('messages.campaigns') . " - " . trans('messages.recipients'))

@section('page_script')
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
@endsection

@section('page_header')

    <div class="page-title">
        <ul class="breadcrumb breadcrumb-caret position-right">
            <li class="breadcrumb-item"><a href="{{ action("HomeController@index") }}">{{ trans('messages.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ action("CampaignController@index") }}">{{ trans('messages.campaigns') }}</a></li>
        </ul>
        <h1>
            <span class="text-semibold"><span class="material-icons-outlined me-2">
forward_to_inbox
</span> {{ $campaign->name }}</span>
        </h1>

        @include('campaigns._steps', ['current' => 1])
    </div>

@endsection

@section('content')
    <form action="{{ action('CampaignController@recipients', $campaign->uid) }}" method="POST" class="form-validate-jqueryz recipients-form">
        {{ csrf_field() }}

        <h4 class="mb-20 mt-0">
            {{ trans('messages.choose_lists_segments_for_the_campaign') }}
        </h4>

        <div class="addable-multiple-form">
            <div class="addable-multiple-container campaign-list-segments">
                <?php $num = 0 ?>
                @foreach ($campaign->getListsSegmentsGroups() as $index =>  $lists_segment_group)
                    @include('campaigns._list_segment_form', [
                        'lists_segment_group' => $lists_segment_group,
                        'index' => $num,
                    ])
                    <?php $num++ ?>
                @endforeach
            </div>
            <br />
            <a
                sample-url="{{ action('CampaignController@listSegmentForm', $campaign->uid) }}"
                href="#add_condition" class="btn btn-secondary add-form">
                <i class="icon-plus2"></i> {{ trans('messages.add_list_segment') }}
            </a>
        </div>

        <hr>

        <div class="text-end">
            <button class="btn btn-secondary">{{ trans('messages.save_and_next') }} <span class="material-icons-outlined">
arrow_forward
</span> </button>
        </div>
    <form>

    <script>
        // addable multiple form
        $(document).on("click", ".addable-multiple-form .add-form", function(e) {
            var form = $(this).parents('.addable-multiple-form');
            var container = form.find('.addable-multiple-container');
            var status = $(this).attr('automation-status');

            if(status == 'active') {
                //show disable automation confirm
                $('#disable_automation_confirm').modal('show');
                return;
            }

            // ajax update custom sort
            $.ajax({
                method: "GET",
                url: $(this).attr('sample-url'),
            })
            .done(function( msg ) {
                var num = "0";

                if(container.find('.condition-line').length) {
                    num = parseInt(container.find('.condition-line').last().attr("rel"))+1;
                }

                msg = msg.replace(/__index__/g, num);

                container.append(msg);

                var new_line = container.find('.condition-line').last();

                if(new_line.find('.event-campaigns-container').length) {
                    loadAutomationEmail(new_line.find('.event-campaigns-container'));
                }

                initJs(new_line);
            });
        });

        $('.recipients-form').submit(function(e) {
            if (!$('[radio-group=campaign_list_info_defaulf]:checked').length) {
                new Dialog('alert', {
                    message: '{{ trans('messages.recipients.select_default_list.warning') }}',
                });

                e.preventDefault();
                return false;
            }
        });
    </script>
@endsection
