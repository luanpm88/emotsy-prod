function initJs(container)
{
    container.find('.select').select2({
        dropdownAutoWidth: true,
        minimumResultsForSearch: 30
    });

    // pick a date
    pickadateMask2(container.find('.pickadate-control'));

    // pick a time
    if (container.find(".pickatime").length) {
        container.find(".pickatime").AnyTime_picker({
            format: "%H:%i"
        });
    }

    // custom validation
    customValidate(container.find(".form-validate-jquery"));

    // link method
    applyLinkListener(container.find('a:not(.list_action, .list_actions .action)'));
}

function applyLinkListener(links, options) {
    links.on('click', function(e) {
        var url = $(this).attr('href');
        var confirm = $(this).attr('link-confirm');
        var method = $(this).attr('link-method');

        var goLink = function() {
            if (typeof(method) == 'undefined' || method.trim().toLowerCase() == 'get') {
                window.location = url;
            } else {
                var newForm = jQuery('<form>', {
                    'action': url,
                    'method': 'POST'
                });
                newForm.append(jQuery('<input>', {
                    'name': '_token',
                    'value': CSRF_TOKEN,
                    'type': 'hidden'
                }));
                newForm.append(jQuery('<input>', {
                    'name': '_method',
                    'value': method,
                    'type': 'hidden'
                }));
                $(document.body).append(newForm);
                newForm.submit();
            }
        };

        if (typeof(confirm) !== 'undefined') {
            e.preventDefault();

            var dialog = new Dialog('confirm', {
                message: confirm,
                ok: function() {
                    goLink();
                }
            })
        } else if (typeof(method) !== 'undefined' && method.trim().toLowerCase() !== 'get') {
            e.preventDefault();

            goLink();
        }
    });
}

function pickadateMask(selector) {
    $(document).on('change', selector, function() {
        updatePickadateDateMask($(this));
    });
    $(selector).each(function() {
        updatePickadateDateMask($(this));
    });
    $(document).on('focusout', selector, function() {
        var value = $(this).parent().find('.date-mask-control').html();
        var date = moment(value, LANG_DATE_FORMAT.toUpperCase()); //Get the current date
        $(this).val(date.format('yyyy-mm-dd'.toUpperCase()));
    });
}

function pickadateMask2(selector) {
    selector.change(function() {
        updatePickadateDateMask($(this));
    });
    selector.each(function() {
        updatePickadateDateMask($(this));
    });
    selector.focusout(function() {
        var value = $(this).parent().find('.date-mask-control').html();
        var date = moment(value, LANG_DATE_FORMAT.toUpperCase()); //Get the current date
        $(this).val(date.format('yyyy-mm-dd'.toUpperCase()));
    });
}

function updatePickadateDateMask(control) {
    control.each(function() {
        var mask = $(this).parent().find('.date-mask-control');
        var value = $(this).val();

        if(value !== '') {
            var date = moment(value); //Get the current date
            mask.html(date.format(LANG_DATE_FORMAT.toUpperCase()));
        }
    });
}

function popupwindow(url, title, w, h) {
    var left = (screen.width/2)-(w/2);
    var top = 0;
    return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+screen.height+', top='+top+', left='+left);
}

function removeMaskLoading() {
    $('.mask-loading-effect').remove();
}

function addMaskLoading(text, callback, options) {
    removeMaskLoading();
    var wait = 400;
    
    if (typeof(text) === 'undefined') {
        text = '';
    }

    if (typeof(options) === 'undefined') {
        options = {};
    }

    if (typeof(options.wait) !== 'undefined') {
        wait = options.wait;
    }
    
    var div = $('<div>').html(`<div class="mask-loading-effect"><div class="content">
        <div class="mask-loading mb-3"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div>
        `+text+`</div><div>`
    );
    
    $('body').append(div);
    
    div.fadeIn(400, function() {
        if (typeof(callback) !== 'undefined') {
            setTimeout(function() { callback(); }, wait);
        }
    });
}

function addButtonMask(button) {
    button.addClass('btn-loading');
}

function removeButtonMask(button) {
    button.removeClass('btn-loading');
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
            checkPageChange();;

            this.content.find('[name=per_page]').on('change', function() {
                _list.load();
                checkPageChange();
            });

            this.content.find('pagination a').on('click', function(e) {
                e.preventDefault();

                _list.url = $(this).attr('href');
                _list.load();
            });

            // each row input clicked
            this.content.find('[name="uids[]"]').on('change', function() {
                checkPageChange();
            });

            // actions
            this.content.find('a.list_action').on('click', function(e) {
                e.preventDefault();

                var url = $(this).attr('href');
                var confirm = $(this).attr('link-confirm');
                var method = $(this).attr('link-method');

                if (typeof(method) == 'undefined') {
                    method = 'GET';
                }

                var goLink = function() {
                    // copy
                    $.ajax({
                        url: url,
                        method: method,
                        data: {
                            _token: CSRF_TOKEN
                        },
                        globalError: false
                    }).done(function(response) {
                        if (typeof(response) == 'string') {
                            response = {
                                status: 'success',
                                message: response
                            };
                        }
                        notify({
                            type: response.status,
                            message: response.message,
                        });

                        _list.load();
                    }).fail(function(res){
                        notify({
                            type: 'danger',
                            message: res.responseText,
                        });
                    });
                };

                if (typeof(confirm) !== 'undefined') {
                    var dialog = new Dialog('confirm', {
                        message: confirm,
                        ok: function() {
                            goLink();
                        }
                    })
                } else {
                    goLink();
                }
            });
        }
    });

    var getCheckCount = function() {
        return options.container.find('[name="uids[]"]:checked').length;
    }

    // check if page checked half or all
    var checkPageChange = function() {
        var checkCount = getCheckCount();
        var notCheckCount = options.container.find('[name="uids[]"]').length;

        if (checkCount == notCheckCount && checkCount > 0) {
            options.container.find('input[name=page_checked]').prop('checked', true);
            options.container.find('input[name=page_checked]').removeClass('half_checked');
        } else if (checkCount == 0) {
            options.container.find('input[name=page_checked]').prop('checked', false);
            options.container.find('input[name=page_checked]').removeClass('half_checked');
        } else {
            options.container.find('input[name=page_checked]').prop('checked', false);
            options.container.find('input[name=page_checked]').addClass('half_checked');
        }

        // show list actions
        if (checkCount > 0) {
            options.container.find('.list_actions').fadeIn();
        } else {
            options.container.find('.list_actions').hide();
        }

        // update count number
        options.container.find('.list_actions .number').html(checkCount);
    };

    // check all|none items in page
    var checkPage = function() {
        var checked = options.container.find('input[name=page_checked]').is(':checked');
        if (checked) {
            options.container.find('[name="uids[]"]').prop('checked', true);
        } else {
            options.container.find('[name="uids[]"]').prop('checked', false);
        }
    };

    // all filter in box change
    options.container.find('.filter-box select').on('change', function() {
        _list.load();
    });

    // keyword input change
    options.container.find('.filter-box input[type=text]').on('keyup', function() {
        _list.load();
    });

    // sort direction click
    options.container.find('.filter-box .sort-direction').on('click', function() {
        var direction = $('[name=sort_direction]').val();

        if (direction == 'desc') {
            $('[name=sort_direction]').val('asc');
        } else {
            $('[name=sort_direction]').val('desc');
        }

        _list.load();
    });

    // check page input clicked
    options.container.find('input[name=page_checked]').on('change', function() {
        checkPage();
        checkPageChange();
    });
    
    // list actions
    options.container.find('.list_actions .action').on('click', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var confirm = $(this).attr('link-confirm');
        var method = $(this).attr('link-method');

        if (typeof(method) == 'undefined') {
            method = 'GET';
        }

        var goLink = function() {
            addMaskLoading();

            var data = _list.content.find('[name="uids[]"]:input').serializeArray();
            console.log(data);
            data.push({name: '_token', value: CSRF_TOKEN});

            // copy
            $.ajax({
                url: url,
                method: method,
                data: data,
                globalError: false
            }).done(function(response) {
                if (typeof(response) == 'string') {
                    response = {
                        status: 'success',
                        message: response
                    };
                }
                notify({
                    type: response.status,
                    message: response.message,
                });

                _list.load();
            }).fail(function(res){
                new Dialog('alert', {
                    title: LANG_ERROR,
                    message: res.responseText
                });
            }).always(function() {
                removeMaskLoading();
            });
        };

        if (typeof(confirm) !== 'undefined') {
            var dialog = new Dialog('confirm', {
                message: confirm.replace(':number', getCheckCount()),
                ok: function() {
                    goLink();
                }
            })
        } else {
            goLink();
        }
    });

    return _list;
}

function openBuilder(url) {
    var div = $('<div class="full-iframe-popup">').html(`
        <div class="loading classic-loader frame-classic-loader"><div class="text-center inner"><div class="box-loading"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div></div></div>
        <iframe scrolling="yes" class="builder d-none" src="`+url+`"></iframe>
    `);
    
    $(".full-iframe-popup").remove();
    $('body').append(div);

    // open builder effects
    addMaskLoading("{{ trans('messages.automation.template.opening_builder') }}");
    $('.builder').on("load", function() {
        removeMaskLoading();

        $(this).removeClass("d-none");

        $('.full-iframe-popup .frame-classic-loader').remove();
    });
    $('body').addClass('overflow-hidden');
}

function openBuilderClassic(url) {
    var div = $('<div class="full-iframe-popup">').html(`
        <div class="loading classic-loader frame-classic-loader"><div class="text-center inner"><div class="box-loading"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div></div></div>
        <iframe scrolling="yes" class="builder d-none" src="`+url+`"></iframe>
    `);
    
    $(".full-iframe-popup").remove();
    $('body').append(div);

    // open builder effects
    addMaskLoading("{{ trans('messages.automation.template.opening_builder') }}");
    $('.builder').on("load", function() {
        removeMaskLoading();

        $(this).removeClass("d-none");

        $('.full-iframe-popup .frame-classic-loader').remove();
    });
    $('body').addClass('overflow-hidden');
}