function addButtonMask(button) {
    button.addClass('btn-loading');
}

function removeButtonMask(button) {
    button.removeClass('btn-loading');
}

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

function makeList(options) {
    var _list = new List({
        url: options.url,
        content: options.content,
        data: function() {
            return options.container.find('.filter-box :input, [name=per_page]').serialize();
        },
        loaded: function() {
            this.content.find('[name=per_page]').on('change', function() {
                _list.load();
            });

            this.content.find('pagination a').on('click', function(e) {
                e.preventDefault();

                _list.url = $(this).attr('href');
                _list.load();
            });
        }
    });

    _list.load();

    options.container.find('.filter-box select').on('change', function() {
        _list.load();
    });

    options.container.find('.filter-box input[type=text]').on('keyup', function() {
        _list.load();
    });

    options.container.find('.filter-box .sort-direction').on('click', function() {
        var direction = $('[name=sort_direction]').val();

        if (direction == 'desc') {
            $('[name=sort_direction]').val('asc');
        } else {
            $('[name=sort_direction]').val('desc');
        }

        _list.load();
    });

    return _list;
}