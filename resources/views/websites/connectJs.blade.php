@include('forms.popupJs')

@foreach(\Acelle\Model\Form::byWebsite($website)->get() as $form)
    document.addEventListener("DOMContentLoaded", function(event) {
        popup = new AFormPopup({
            url: '{{ action('FormController@content', [
                'uid' => $form->uid,
            ]) }}'
        });
        popup.load();
    });
@endforeach