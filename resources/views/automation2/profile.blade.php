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
                              <a class="dropdown-item" href="#">Remove contact</a>
                              <a class="dropdown-item" href="#">Deactivate</a>
                              <a class="dropdown-item" href="#">Go to profile page</a>
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
                    <h5 class="mb-0">Neko Gal</h5>
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
                <label class="date small font-weight-semibold pl-4 mb-0">Yesterday</label>
                <div class="activity d-flex py-3 px-4">
                    <div class="activity-media pr-4 ">
                        <time class="d-block text-center small font-weight-semibold">1:12pm</time>
                        <i class="lnr lnr-envelope mt-2"></i>
                    </div>
                    <div class="small">
                        <action class="d-block font-weight-semibold mb-1">Subscribes to mail list `Welcome to Soriento` via subscription built-in form</action>
                        <desc class="d-block">
                           Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris id dapibus odio.
                        </desc>
                    </div>
                </div>
                <div class="activity d-flex py-3 px-4">
                    <div class="activity-media pr-4 ">
                        <time class="d-block text-center small font-weight-semibold">10:24am</time>
                        <i class="lnr lnr-exit-up mt-2"></i>
                    </div>
                    <div class="small">
                        <action class="d-block font-weight-semibold mb-1">Subscribes to mail list `Welcome to Soriento` via subscription built-in form</action>
                        <desc class="d-block">
                           Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris id dapibus odio.
                        </desc>
                    </div>
                </div>
                <div class="activity d-flex py-3 px-4">
                    <div class="activity-media pr-4 ">
                        <time class="d-block text-center small font-weight-semibold">3:54am</time>
                        <i class="lnr lnr-bubble mt-2"></i>
                    </div>
                    <div class="small">
                        <action class="d-block font-weight-semibold mb-1">Subscribes to mail list `Welcome to Soriento` via subscription built-in form</action>
                        <desc class="d-block">
                           Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris id dapibus odio.
                        </desc>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection