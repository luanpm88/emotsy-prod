function initJs(container)
{
    container.find('.select').select2({
        dropdownAutoWidth: true,
        minimumResultsForSearch: 30
    });
}

function notify(options) {
    new ANotify().add(options);
}