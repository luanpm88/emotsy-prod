@include('forms.popupJs')

document.addEventListener("DOMContentLoaded", function(event) {
    popup = new AFormPopup({
        url: '{{ action('FormController@content', [
            'uid' => $form->uid,
        ]) }}'
    });
    popup.load();
});