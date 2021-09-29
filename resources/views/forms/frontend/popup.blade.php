@include('forms.frontend.popupJs')

document.addEventListener("DOMContentLoaded", function(event) {
    popup = new AFormPopup({
        url: '{{ action('FormController@frontendContent', [
            'uid' => $form->uid,
        ]) }}'
    });
    popup.load();
});