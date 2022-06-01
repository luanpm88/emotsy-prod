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

    // leftbar scroll behaviour
    $('.leftbar .navbar-main').on( 'DOMMouseScroll mousewheel', function ( event ) {
        if ($('.leftbar').hasClass('leftbar-closed')) {
            console.log('dsss');
            if ($(window).width() < 1200) {
                return;
            }
            if ($(this).parent('.dropdown-menu').length) {
                console.log('dddd');
                return;
            }

            if( event.originalEvent.detail > 0 || event.originalEvent.wheelDelta < 0 ) { //alternative options for wheelData: wheelDeltaX & wheelDeltaY
            var isBottom = -(-$(this).css('margin-top').replace('px', '') - $(this).height()) - 10 < $(window).height();
            var adjust;
            
            if (!isBottom) {
                adjust = ($(this).css('margin-top').replace('px', '') - 10) + 'px';
            } else {
                adjust = (-$(this).height() + $(window).height()) + 'px';
            }

            $(this).css('margin-top', adjust);
            } else {
                var isTop = parseInt($(this).css('margin-top').replace('px', '')) + 10 < 0;
            
                if (isTop) {
                    adjust = (parseInt($(this).css('margin-top').replace('px', '')) + 10) + 'px';
                } else {
                    adjust = '0px';
                }
                $(this).css('margin-top', adjust);
            }
            //prevent page fom scrolling
            return false;
        }
    });

    $('.leftbar-hide-menu').on('click', function() {
        $('.leftbar .navbar-main').css('margin-top', '0px');
    });

    $('.leftbar:not(.leftbar-closed)  .navbar-main:not(.navbar-right) .nav-link.active').on('hide.bs.dropdown', function (e) {
        if ($(this).hasClass('active') && e.clickEvent && e.clickEvent.target.className!="nav-link") {
          e.preventDefault();
        } else {
            // $(this).removeClass('active');
        }
       
    });

    $( window ).on('resize', function() {
        $('.leftbar .navbar-main').css('margin-top', '0px');

        if ($(window).width() > 1300 && $(window).width() < 1600) {
            $('.leftbar').removeClass('leftbar-open');
            $('.leftbar').addClass('leftbar-closed');
        }
    });
});