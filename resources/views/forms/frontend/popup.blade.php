document.addEventListener("DOMContentLoaded", function(event) {
    popup = new AFormPopup({
        url: '{{ action('FormController@frontendContent', [
            'uid' => $form->uid,
        ]) }}'
    });
    @if ($form->getMetadata('display') == 'immediately')
        popup.load();
    @elseif ($form->getMetadata('display') == 'click')
        document.getElementById('{{ $form->getMetadata('element_id') }}').addEventListener("click", function(event) {
            popup.load();
        });
    @elseif ($form->getMetadata('display') == 'wait')
        setTimeout(function() {
            popup.load();
        }, {{ $form->getMetadata('wait_time')*1000 }});
    @endif
});