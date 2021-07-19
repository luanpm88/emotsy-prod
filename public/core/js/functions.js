function initJs(container)
{
    // tooltip
    container.find('.xtooltip, [data-popup=tooltip]').tooltipster({
        theme: 'tooltipster-light'
    });

    // select2
    container.find('.select').select2({
        dropdownAutoWidth: true,
        minimumResultsForSearch: 30
    });

    // pick a date
    if (container.find(".pickadate-control").length) {
        pickadateMask2(container.find('.pickadate-control'));
        container.find('.pickadate-control').pickadate({
            format: 'yyyy-mm-dd'
        });
    }

    // datetime picker
    if (container.find(".pickadatetime").length) {
        container.find(".pickadatetime").AnyTime_picker({
            format: LANG_ANY_DATETIME_FORMAT
        });
    }

    // numeric
    container.find(".numeric").numeric();

    // pick a time
    if (container.find(".pickatime").length) {
        container.find(".pickatime").AnyTime_picker({
            format: "%H:%i"
        });
    }

    // custom validation
    customValidate(container.find(".form-validate-jquery"));

    // link method
    applyLinkListener(container.find('a[link-method], a[link-confirm]'));

    // Select2 tags
    container.find('.select-tag').select2({
        minimumResultsForSearch: 20,
        templateResult: formatSelect2TextOption,
        templateSelection: formatSelect2TextSelected,
        placeholder: function(){
            $(this).attr('data-placeholder');
        },
        dropdownAutoWidth : true,
        width: 'auto',
        tags: true,
    });

    // Select2 ajax
    container.find(".select2-ajax").each(function() {
        var url = $(this).attr("data-url");
        var placeholder = $(this).attr("placeholder");
        if(typeof(placeholder) == 'undefined') {
            placeholder = "";
        }
        $(this).select2({
            placeholder: placeholder,
            allowClear: true,
            ajax: {
                url: url,
                dataType: 'json',
                delay: 250,

                data: function (params) {
                  return {
                    q: params.term, // search term
                    page: params.page
                  };
                },
                processResults: function (data, params) {
                    // parse the results into the format expected by Select2
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data, except to indicate that infinite
                    // scrolling can be used
                    params.page = params.page || 1;

                    return {
                        results: data.items,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
            minimumInputLength: 0,
            templateResult: formatSelect2TextOption,
            templateSelection: formatSelect2TextSelected,
        });
    });

    // @legacy: radio box
    // Group radio box
    container.find(".control-radio .radio_box .main-control").on('click', function() {
        var radio_control = $(this).parents('.control-radio');
        var radio_box = $(this).parents('.radio_box');
        var radio = $(this).find('input');

        radio_control.find('.radio_more_box').hide();
        if (radio.is(":checked")) {
            radio_box.find('.radio_more_box').show();
        } else {
            radio_box.find('.radio_more_box').hide();
        }
    });
    container.find(".control-radio .radio_box .main-control input:checked").parents('.main-control').trigger('click');
}

// Preview upload image
function previewImageBrowse(input, img) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            img.attr('src', e.target.result);

            // calculate crop part
            var box_width = img.parent().width();
            var box_height = img.parent().height();
            var width = img[0].naturalWidth;
            var height = img[0].naturalHeight;
            var cal_width, cal_height;

            if(width/height < box_width/box_height) {
                cal_height = box_height;
                cal_width = box_height*(height/width);
            } else {
                cal_width = box_width;
                cal_height = box_width*(width/height);
            }

            img.width(cal_height);
            img.height(cal_width);

            var mleft = -Math.abs(cal_width - box_width)/2;
            var mtop = -Math.abs(cal_height - box_height)/2;
            img.css("margin-left", mtop+"px");
            img.css("margin-top", mleft+"px");
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function htmlDecode(input){
    var e = document.createElement('textarea');
    e.innerHTML = input;
    // handle case of empty input
    return e.childNodes.length === 0 ? "" : e.childNodes[0].nodeValue;
}

function copyToClipboard(text) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val(text).select();
    document.execCommand("copy");
    $temp.remove();
}

function formatSelect2TextSelected(d) {
    var text = d.text;
    var parts = text.split('|||');

    return parts[0];
}

function formatSelect2TextOption(d) {
    var text = d.text;
    var parts = text.split('|||');

    if (parts.length == 1) {
        return parts[0];
    } else {
        return '<div class="select2_title">' + parts[0] + '</div>' + '<div class="select2_sub_line">' + parts[1] + '</div>';
    }
}

function menuActiveCheck() {
    // menu active
    for (i=0; i < 10; i++) {
        $("li[rel"+i+"='"+CONTROLLER+"']").find('.nav-link').eq(0).addClass("active");
        $("li[rel"+i+"='"+CONTROLLER+"']").closest('.nav-link').addClass("active");
    }
    for (i=0; i < 10; i++) {
        $("li[rel"+i+"='"+CONTROLLER+"/"+ACTION+"']").find('.nav-link').eq(0).addClass("active");
        $("li[rel"+i+"='"+CONTROLLER+"/"+ACTION+"']").closest('.nav-link').addClass("active");
    }
}

function applyLinkListener(links, options) {
    links.on('click', function(e) {
        e.preventDefault();
        
        var url = $(this).attr('href');
        var confirm = $(this).attr('link-confirm');
        var method = $(this).attr('link-method');

        new Link({
            type: 'link',
            url: url,
            confirm: confirm,
            method: method
        });
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

function notify(optionsOrType, title, message) {
    var options = optionsOrType;

    if (typeof(message) != 'undefined') {
        options = {
            type: optionsOrType,
            title: title,
            message: message
        };
    }

    new ANotify().add(options);
}

function makeList(options) {

    if (typeof(options.container) == 'undefined') {
        options.container = options.content;
    }

    if (typeof(options.data) == 'undefined') {
        options.data = function() {
            return options.container.find('.filter-box :input, [name=per_page]').serialize();
        };
    }

    var _list = new List({
        url: options.url,
        content: options.content,
        data: options.data,
        per_page: options.per_page,
        method: options.method,
        doAction: function() {

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
            this.content.find('a.list-action-single').off('click').on('click', function(e) {
                e.preventDefault();

                var url = $(this).attr('href');
                var confirm = $(this).attr('link-confirm');
                var confirmUrl = $(this).attr('link-confirm-url');
                var method = $(this).attr('link-method');

                if (typeof(confirm) !== 'undefined') {
                    confirm = confirm.replace(':number', 1);
                }

                new Link({
                    url: url,
                    confirm: confirm,
                    confirmUrl: confirmUrl,
                    method: method,
                    data: {
                        _token: CSRF_TOKEN,
                    },
                    done: function(response) {
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
                    }
                });
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
    options.container.find('.a.list-action-multi, .list_actions a.dropdown-item').off('click').on('click', function(e) {
        e.preventDefault();
        
        var url = $(this).attr('href');
        var confirm = $(this).attr('link-confirm');
        var confirmUrl = $(this).attr('link-confirm-url');
        var method = $(this).attr('link-method');
        var data = _list.content.find('[name="uids[]"]:input').serializeArray();

        if (typeof(confirm) !== 'undefined') {
            confirm = confirm.replace(':number', getCheckCount());
        }

        data.push({name: '_token', value: CSRF_TOKEN});

        new Link({
            url: url,
            confirm: confirm,
            confirmUrl: confirmUrl,
            method: method,
            data: data,
            done: function(response) {
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
            }
        });
    });

    return _list;
}

function openBuilder(url) {
    var div = $('<div class="full-iframe-popup">').html(`
        <div class="loading classic-loader frame-classic-loader"><div class="text-center inner"><div class="box-loading"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div></div></div></div>
        <iframe scrolling="no" class="builder d-none" src="`+url+`"></iframe>
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
        <iframe scrolling="no" class="builder d-none" src="`+url+`"></iframe>
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