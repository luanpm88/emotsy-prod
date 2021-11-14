@extends('layouts.popup.small')

@section('bar-title')
    {{ trans('messages.account.welcome_user', [
        'name' => Auth::user()->displayName(),
    ]) }}
@endsection

@section('content')
    <form id="addTaxForm" class="" action="{{ action('Admin\TaxController@addTax', [
        'country_id' => $country->id,
    ]) }}"
        method="POST">
        {{ csrf_field() }}
        
        <h4 class="mt-0">{{ $country->name }}</h4>

        <div>
            <div class="mt-4 d-flex align-items-center">
                <div class="form-group-mb-0 me-1">
                    @include('helpers.form_control', [
                        'type' => 'number',
                        'class' => 'country_tax_rate',
                        'name' => 'tax[countries]['.$country->code.']',
                        'value' => Acelle\Model\Setting::getTaxByCountry($country),
                        'help_class' => 'tax',
                    ])
                </div>
                <div class="me-3">%</div>
            </div>
        </div>

        <div class="mt-4">
            <button class="btn btn-secondary px-4">{{ trans('messages.save') }}</button>
        </div>
    </form>

    <script>
        var TaxesAddTax = {
            save: function(rate) {
                addMaskLoading();
                
                $.ajax({
                    method: "POST",
                    url: $('#addTaxForm').attr('action'),
                    data: {
                        _token: CSRF_TOKEN,
                        tax: {
                            countries: {
                                {{ $country->code }}: rate
                            }
                        } 
                    }
                })
                .done(function( res ) {
                    removeMaskLoading();

                    notify({
                        type: res.status,
                        message: res.message
                    });

                    TaxesSettings.getAddTaxPopup().hide();
                    TaxesSettings.getCountryTaxesBox().load();
                });
            }
        }

        $(function() {
            $('#addTaxForm').on('submit', function(e) {
                e.preventDefault();

                var rate = $(this).find('.country_tax_rate').val();

                if (rate == '') {
                    new Dialog('alert', {
                        message: '{{ trans('messages.tax.default_rate_required') }}'
                    });
                    return;
                }

                TaxesAddTax.save(rate);
            });
        });
    </script>
@endsection