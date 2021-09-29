@include('forms.frontend.popupJs')

@foreach($website->connectedForms()->published()->get() as $form)
    document.addEventListener("DOMContentLoaded", function(event) {
        popup = new AFormPopup({
            url: '{{ action('FormController@frontendContent', [
                'uid' => $form->uid,
            ]) }}'
        });
        popup.load();
    });
@endforeach