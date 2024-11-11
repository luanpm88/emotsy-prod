@extends('layouts.core.frontend', [
    'menu' => false,
])
@section('title', 'Samplel UI')
@section('page_header')
    <!-- nothing here -->
@endsection

@section('content')
    <div class="row">
        <div class="col-md-10">
            <h1>Notifications</h1>
            <div class="alert alert-info"
                style="display: flex; flex-direction: row; justify-content: space-between; align-items: center;">
                <div style="display: flex; flex-direction: row; align-items: center;">
                    <div style="margin-right:15px">
                        <i class="lnr lnr-bubble"></i>
                    </div>
                    <div style="padding-right: 40px">
                        <h4>Pending verification...</h4>
                        <p>We have sent an email to this sender's email address. Please click on the link included in the
                            email to activate</p>
                    </div>
                </div>
                <button class="btn btn-secondary">Send again!</button>
            </div>

            <div class="alert alert-warning"
                style="display: flex; flex-direction: row; align-items: center; justify-content: space-between;">
                <div style="display: flex; flex-direction: row; align-items: center;">
                    <div style="margin-right:15px">
                        <span class="material-symbols-rounded">error_outline</span>
                    </div>
                    <div style="padding-right: 40px">
                        <h4>Verification required</h4>
                        <p>We have sent an email to this sender's email address. Please click on the link included in the
                            email to activate</p>
                    </div>
                </div>
                <button class="btn btn-secondary">{{ trans('messages.sending_domain.verify') }}</button>
            </div>

            <div class="alert alert-danger"
                style="display: flex; flex-direction: row; align-items: center; justify-content: space-between;">
                <div style="display: flex; flex-direction: row; align-items: center;">
                    <div style="margin-right:15px">
                        <i class="lnr lnr-circle-minus"></i>
                    </div>
                    <div style="padding-right: 40px">
                        <h4>Verification required</h4>
                        <p>We have sent an email to this sender's email address. Please click on the link included in the
                            email to activate</p>
                    </div>
                </div>
                <button class="btn btn-secondary">{{ trans('messages.sending_domain.verify') }}</button>
            </div>

            <div class="alert alert-success"
                style="display: flex; flex-direction: row; align-items: center; justify-content: space-between;">
                <div style="display: flex; flex-direction: row; align-items: center;">
                    <div style="margin-right:15px">
                        <i class="lnr lnr-checkmark-circle"></i>
                    </div>
                    <div style="padding-right: 40px">
                        <h4>Sender verified</h4>
                        <p>We have sent an email to this sender's email address. Please click on the link included in the
                            email to activate</p>
                    </div>
                </div>
            </div>

            <h2>Form Input</h2>
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }} control-text">
                <label>
                    Text Input
                    <span class="text-danger">*</span>
                </label>
                <input id="name" placeholder="" value="" type="text" name="name" class="form-control required" />
    
                @if ($errors->has('name'))
                    <span class="help-block">
                        {{ $errors->first('name') }}
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label class="form-label mb-0">
                    <span class="form-label d-flex align-items-top">
                        <label class="d-block mb-0 me-3">
                            <input type="hidden" name="plan[general][no_payment_required_when_free]" value="0">
                            <input {{ true ? 'checked' : '' }} type="checkbox" class="styled me-2 numeric"
                                name="plan[general][no_payment_required_when_free]" value="1" />
                            <span class="check-symbol"></span>
                        </label>
                        <div>
                            <span class="fw-semibold">{{ trans('messages.signature.make_default') }}</span>
                            <p class="mb-0">{{ trans('messages.signature.make_default.desc') }}</p>
                        </div>
                    </span>
                </label>
            </div>

            <div class="form-group">
                <label class="form-label mb-0">
                    <span class="form-label d-flex align-items-top">
                        <label class="d-block mb-0 me-3">
                            <input type="hidden" name="plan[general][no_payment_required_when_free]" value="0">
                            <input {{ true ? 'checked' : '' }} type="checkbox" class="styled me-2 numeric"
                                name="plan[general][no_payment_required_when_free]" value="1" />
                            <span class="check-symbol"></span>
                        </label>
                        <div>
                            <span class="">{{ trans('messages.signature.make_default') }}</span>
                        </div>
                    </span>
                </label>
            </div>
        </div>
    </div>
@endsection
