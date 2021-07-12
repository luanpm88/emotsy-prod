$(function(){
    initJs($('body'));
    
    $( document ).ajaxError(function( event, request, settings ) {
        if(typeof(settings.globalError) != 'undefined' || settings.globalError == false) {
            return;
        }
        
        // abort ajax
        if (request.statusText =='abort') {
            console.log('User abort!');
            return;
        }

        alert(request.responseText);
    });

    // legacy link
    $( document ).on('click', 'a', function(e) {
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
});