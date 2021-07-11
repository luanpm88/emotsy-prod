function initJs(container)
{
    container.find('.select').select2({
        dropdownAutoWidth: true,
        minimumResultsForSearch: 30
    });

    customValidate(container.find(".form-validate-jquery"));
}

function notify(options) {
    new ANotify().add(options);
}