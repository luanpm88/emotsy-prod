@extends('layouts.popup.small')

@section('content')
	<div class="row">
        <div class="col-md-12">
            <div class="d-flex align-items-center">
                <h3 class="mr-auto mb-4">{{ trans('messages.automation.contact.profile') }}</h3>
                <div class="">
                    <div class="btn-group btn-group-sm" role="group" aria-label="Button group with nested dropdown">
                        <div class="btn-group btn-group-sm" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              Action
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btnGroupDrop1">
                                <a class="dropdown-item profile-remove-contact"
                                    href="{{ action('Automation2Controller@removeContact', [
                                        'uid' => $automation->uid,
                                        'contact_uid' => $contact->uid,
                                    ]) }}"
                                    data-confirm="{{ trans('messages.automation.profile.remove_contact.confirm', ['name' => $contact->getFullName()]) }}"
                                >
                                    {{ trans('messages.automation.profile.remove_contact') }}
                                </a>
                                <a class="dropdown-item profile-tag-contact"
                                    href="{{ action('Automation2Controller@tagContact', [
                                        'uid' => $automation->uid,
                                        'contact_uid' => $contact->uid,
                                    ]) }}"
                                >{{ trans('messages.automation.profile.tag_contact') }}</a>
                                <a class="dropdown-item" href="{{ action('SubscriberController@edit', [
                                    'list_uid' => $contact->mailList->uid,
                                    'uid' => $contact->uid,
                                ]) }}">{{ trans('messages.automation.profile.go_to_pofile') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="profile d-flex">
                <div class="media mr-4">
                    <!--<i class="lnr lnr-user"></i>-->
                    <img src="{{ action('SubscriberController@avatar',  $contact->uid) }}" />
                </div>
                <div class="account">
                    <h5 class="mb-0">{{ $contact->getFullName() }}</h5>
                    <p class="small mb-2">{{ $contact->email }}</p>
                    <div class="tags">
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <button type="button" class="btn btn-light btn-tag">Hight Profile</button>
                            <button type="button" class="btn btn-light btn-tag">
                                <i class="lnr lnr-cross"></i>
                            </button>
                        </div>
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <button type="button" class="btn btn-light btn-tag">VIP Customer</button>
                            <button type="button" class="btn btn-light btn-tag">
                                <i class="lnr lnr-cross"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            
            <div class="d-flex align-items-center mt-5">
                <h3 class="mr-auto">Activity Feed</h3>
                <div class="">
                    <div class="btn-group btn-group-sm" role="group" aria-label="Button group with nested dropdown">
                        <div class="btn-group btn-group-sm" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              All activities
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btnGroupDrop1">
                                <a class="dropdown-item" href="#">Open</a>
                                <a class="dropdown-item" href="#">Click</a>
                                <a class="dropdown-item" href="#">Subscribe</a>
                                <a class="dropdown-item" href="#">Unsubscribe</a>
                                <a class="dropdown-item" href="#">Updated</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                
            <div class="activity-feed mt-3">
                <label class="date small font-weight-semibold mb-0 divider">{{ trans('messages.automation.profile_timeline_head') }}</label>
                
                @foreach($automation->timelinesBy($contact)->get() as $timeline)
                    <div class="activity d-flex py-3 px-4">
                        <div class="activity-media pr-4 text-center">
                            <time class="d-block text-center mini mb-2">{{ $timeline->created_at->diffForHumans() }}</time>
                            <i class="material-icons d-inline-block">
                                done_all
                            </i>
                        </div>
                        <div class="small">
                            <action class="d-block font-weight-semibold mb-1">
                                {{ $timeline->activity }}
                            </action>
                            <desc class="d-block">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris id dapibus odio.
                            </desc>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        $('.profile-remove-contact').click(function(e) {
            e.preventDefault();

            var confirm = $(this).attr('data-confirm');
            var url = $(this).attr('href');

            var dialog = new Dialog('confirm', {
                message: confirm,
                ok: function(dialog) {                    
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: {
                            _token: CSRF_TOKEN,
                        },
                        statusCode: {
                            // validate error
                            400: function (res) {
                                alert('Something went wrong!');
                            }
                        },
                        success: function (response) {
                            // notify
                            notify('success', '{{ trans('messages.notify.success') }}', response.message);

                            popup.hide();
                        }
                    });
                },
            });
        });

        var tagContact = new Popup(undefined, undefined, {
			onclose: function() {
				sidebar.load();
			}
		});
        $('.profile-tag-contact').click(function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            tagContact.load(url, function() {
				console.log('Confirm action type popup loaded!');				
			});
        });
    </script>
@endsection