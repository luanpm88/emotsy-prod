$(function(){
    initJs($('body'));

    // Toogle menu
    menuActiveCheck();
    
    // Default jQuery Exception
    $( document ).ajaxError(function( event, request, settings ) {
        if(typeof(settings.globalError) != 'undefined' || settings.globalError == false) {
            return;
        }
        
        // abort ajax
        if (request.statusText =='abort') {
            console.log('User abort!');
            return;
        }

        if (request.responseText) {
            alert(request.responseText);
        } else {
            console.log(request);
        }
    });

    // Top quota button
    $(document).on('click', '.top-quota-button', function(e) {
        e.preventDefault();
        var url = $(this).attr("data-url");
        console.log(url);


        var quotaPopup = new Popup({
            url: url
        });
        
        quotaPopup.load();
    });

    $('.leftbar .navbar-main').on( 'DOMMouseScroll mousewheel', function ( event ) {
        if( event.originalEvent.detail > 0 || event.originalEvent.wheelDelta < 0 ) { //alternative options for wheelData: wheelDeltaX & wheelDeltaY
          //scroll down
          console.log('Down');
          console.log(-(-$(this).css('margin-top').replace('px', '') - $(this).height()));
          console.log($(window).height());

          var isBottom = -(-$(this).css('margin-top').replace('px', '') - $(this).height() + 20) <= $(window).height();
          var adjust;
          
          if (!isBottom) {
            adjust = ($(this).css('margin-top').replace('px', '') - 20) + 'px';
          } else {
            adjust = (-$(this).height() + $(window).height()) + 'px';
          }

          $(this).css('margin-top', adjust);
        } else {
            console.log(parseInt($(this).css('margin-top').replace('px', '')));
            console.log((parseInt($(this).css('margin-top').replace('px', '')) + 20) + 'px');
            var isTop = parseInt($(this).css('margin-top').replace('px', '')) < 0;
          
            if (isTop) {
                adjust = (parseInt($(this).css('margin-top').replace('px', '')) + 20) + 'px';
            } else {
                adjust = '0px';
            }
            $(this).css('margin-top', adjust);
        }
        //prevent page fom scrolling
        return false;
    });
});