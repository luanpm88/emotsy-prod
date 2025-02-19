@extends('layouts.core.frontend', [
    'menu' => 'campaign',
])

@section('title', trans('messages.campaigns') . " - " . trans('messages.recipients'))

@section('head')
    <script type="text/javascript" src="{{ AppUrl::asset('core/js/group-manager.js') }}"></script>
@endsection

@section('page_header')

	<div class="mt-3">
		<div class="border-bottom mb-4">
			<div class="" style="width: 100%">
                <div class="page-title pt-0">
                    <ul class="breadcrumb breadcrumb-caret position-right">
                        <li class="breadcrumb-item"><a href="{{ action("Admin\HomeController@index") }}">{{ trans('messages.home') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ action("CampaignController@index") }}">{{ trans('messages.campaigns') }}</a></li>
                    </ul>
                </div>
                <h2 class="mb-0 mb-3 d-flex align-items-center">
                    <span class="text-semibold">
                        <img width="25px" class="icon-img d-inline-block me-2" src="{{ url('images/icons/'.$campaign->type.'.svg') }}" />
                        <span>{{ $campaign->name }}</span>
                    </span>
                    <span class="text-center text-muted badge badge-info ms-2">{{ trans('messages.campaign.type.' . $campaign->type) }}</span>
                </h2>
				@include('campaigns._steps', ['current' => 1])
			</div>
		</div>
	</div>

@endsection

@section('content')
    <form action="{{ action('CampaignController@recipients', [
                'uid' => $campaign->uid,
                'version_b' => request()->version_b,
            ]) }}" method="POST" class="form-validate-jqueryz recipients-form">
        {{ csrf_field() }}

        <h1 class="mb-4">{{ trans('messages.recipients') }}</h1>

        <p class="mt-0">
            {{ trans('messages.choose_lists_segments_for_the_campaign') }}
        </p>
<hr>
        @if ($cronjobWarning)
            <div class="alert alert-danger" style="display: flex; flex-direction: row; align-items: center; justify-content: space-between;">
                <div style="display: flex; flex-direction: row; align-items: center;">
                    <div style="margin-right:15px">
                        <i class="lnr lnr-circle-minus"></i>
                    </div>
                    <div style="padding-right: 40px;">
                        <h4 style="padding-bottom:5px">{{ trans('messages.cronjob.warning.title') }}</h4>
                        <p style="padding-bottom:5px">{{ trans('messages.cronjob.warning.description', [
                            'last_executed_time' => $cronjobWarning->toString(),
                            'last_executed_time_readable' => $cronjobWarning->diffForHumans()
                        ]) }}.</p>
                    </div>
                </div>

            </div>
        @endif

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
            <a
                sample-url="{{ action('CampaignController@listSegmentForm', [
                'uid' => $campaign->uid,
                'version_b' => request()->version_b,
            ]) }}"
                href="#add_condition" class="btn btn-default add-form">
                <span class="material-symbols-rounded">add</span> {{ trans('messages.add_list_segment') }}
            </a>
        </div>

        <hr>

        @if (Acelle\Model\Setting::isYes('custom_delivery_statuses'))
            @include('campaigns._delivery_settings')

            <hr>
        @endif

        

        <div class="text-end">
            <button class="btn btn-secondary">{{ trans('messages.save_and_next') }} <span class="material-symbols-rounded">arrow_forward</span> </button>
        </div>
    <form>

    <script>
        var CampaignsReciepientsSegment = {
			manager: null,

            rowToGroup: function(row) {
                return {
                    listSelect: row.find('.list-select'),
                    url: row.find('.list-select').closest('.list_select_box').attr("segments-url"),
                    segmentSelect: row.find('.segments-select-box'),
                    getVal: function() {
                        return row.find('.list-select').val();
                    },
                    index: row.closest('.condition-line').attr('rel')
                }
            },

            addRow: function(row) {
                group = this.rowToGroup(row);
                this.getManager().add(group);

                this.groupAction(group);
            },

            groupAction: function(group) {
                group.check = function() {
                    if(group.getVal() !== '') {
                        $.ajax({
                            method: "GET",
                            url: group.url,
                            data: {
                                list_uid: group.getVal(),
                                index: group.index
                            }
                        })
                        .done(function( res ) {
                            group.segmentSelect.html(res);

                            initJs(group.segmentSelect);
                        });
                    } else {
                        group.segmentSelect.html('');
                    }
                }

                group.listSelect.on('change', function() {
                    group.check();
                });
            },

			getManager: function() {
				if (this.manager == null) {
					this.manager = new GroupManager();

					$('.condition-line').each(function() {
						var row = $(this);

						CampaignsReciepientsSegment.addRow(row);
					});
				}

				return this.manager;
			},

			check: function() {
				this.getManager().groups.forEach(function(group) {
					group.check();
				});
			}
		}

        $(function() {
            CampaignsReciepientsSegment.getManager();


            $('.recipients-form').submit(function(e) {
                if (!$('[radio-group=campaign_list_info_defaulf]:checked').length) {
                    new Dialog('alert', {
                        message: '{{ trans('messages.recipients.select_default_list.warning') }}',
                    });

                    e.preventDefault();
                    return false;
                }
            });

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

                    CampaignsReciepientsSegment.addRow(new_line);
                });
            });

            // radio group check
            $(document).on('change', '[radio-group]', function() {
                var checked = $(this).is(':checked');
                var group = $(this).attr('radio-group');

                if(checked) {
                    $('[radio-group="' + group + '"]').prop('checked', false);
                    $(this).prop('checked', true);
                }
            });
        });

        function loadAutomationEmail(container) {
            var url = container.attr('data-url');

            $.ajax({
                method: "GET",
                url: url
            })
            .done(function( data ) {
                container.html(data);
            });
        }
    </script>
@endsection
