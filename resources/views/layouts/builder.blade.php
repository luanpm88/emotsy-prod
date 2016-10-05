<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!--[if !mso]><!-->
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!--<![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - {{ \Acelle\Model\Setting::get("site_name") }}</title>
    <link href="{{ URL::asset('assets/css/icons/icomoon/styles.css') }}" rel="stylesheet" type="text/css">    
    
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/builder.css') }}" />
    <!--[if (gte mso 9)|(IE)]>
    <style type="text/css">
        table {border-collapse: collapse;}
    </style>
    <![endif]-->
    
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    
    <script type="text/javascript" src="{{ URL::asset('tinymce/tinymce.min.js') }}"></script>
        
    <script type="text/javascript" src="{{ URL::asset('js/editor.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/validation/validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/validate.js') }}"></script>
    
    
    
    @include('layouts._script_vars')
    
    <script>
    var current_content;
    var current_block;
    
    function updateTextarea() {
        var css = '';
        $('textarea[name=content]').val(css+'<content>' + $('content').html() + '</content>');
    }
    
    function insertElement(name) {
        console.log($('.'+name));
        $("content table.outer").append($('.'+name).clone());
        $('content .'+name).removeClass(name);
        
        updateContent();
    }
    
    function updateConentCol() {
        if (typeof(current_content) != 'undefined') {
            content = tinyMCE.activeEditor.getContent({format : 'html'});
            current_content.html(content);
        }
    }
    
    function ajustLayout() {
        $( "content" ).height( $( window ).height() - 55 );
        $( "editor" ).height( $( window ).height() - 75 );
        $( "leftbar" ).height( $( window ).height() - 55 );
    }
    
    function updateToolbox() {
        $('.toolbox-container').html($('toolbox').html());
        // Sortable
        sortable();
    }
    
    function updateContent() {
        $('content .tool-item').each(function() {
            var template = $(this).attr('template');
            $(this).after($('.'+template).clone());
            $('content .'+template).removeClass(template);
            $(this).remove();
        });
        
        // Find content col
        $('content td').each(function() {
            if(!$(this).find('table').length) {
                $(this).addClass('content-col');
            }
        });
        
        // add action button
        $("tr.block").on("mouseover", function () {
            $(".remove_block").show();
            $(".remove_block").css('top', $(this).offset().top);
            $(".remove_block").css('left', $(this).offset().left + $(this).width() - $(".remove_block").width());
            
            current_block = $(this);
        });
        $(".remove_block").on("mouseover", function () {
            $(".remove_block").show();
        });
        // add action button
        $("tr.block").on("mouseout", function () {
            $(".remove_block").hide();
        });
        // remove block
        $(".remove_block").on("click", function () {
            current_block.remove();
            $(".remove_block").hide();
        });
    }
    
    function sortable() {
        $( ".left-box" ).sortable({
            items: '.block',
            connectWith: ".right-box",
            start: function(e, ui) {
                console.log(ui.item);
            },
            stop: function(event,ui){
                // Update content
                updateContent();
                // Update toolbox
                updateToolbox();
            }
        });
        // Content sortable
        $( ".right-box" ).sortable({
            items: '.block',
        });
    }
    
    $( function() {
        ajustLayout();
        
        // Update content
        updateContent();
        // Update toolbox
        updateToolbox();
        // Sortable
        sortable();
        
        // Click on content col
        $(document).on("click", ".content-col", function() {
            current_content = $(this);
            
            $(".content-col").removeClass('current');
            current_content.addClass('current');
            
            // Set content
            tinyMCE.activeEditor.setContent(current_content.html(), {format : 'html'});
            
            // Show editor
            $('editor').fadeIn();
        });
        
        // Click on content col
        $(document).on("click", ".close-editor", function() {
            // Show editor
            $('editor').fadeOut();
        });
        
        // Auto update content
        setInterval("updateConentCol()", 500);
        setInterval("updateTextarea()", 500);
        
    });
    $( window ).resize(function() {
        ajustLayout()
    });
    </script>
    
</head>
<body>
    <topbar>
        @yield('content')        
    </topbar>
    
    <div title="{{ trans('messages.remove_row') }}" class="remove_block">
        <i class="icon-cross2"></i>
    </div>
    
    <table class="hide">
        <tr class="block full_width_banner">
            <td>
                <table class="" align="center">
                    <tr>
                        <td class="full-width-image">
                            <img src="{{ url("images/icons/image_placeholder_wide.png") }}" width="600" alt="" />
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr class="block single_text_row">
            <td class="one-column">
                <table width="100%">
                    <tr>
                        <td class="inner contents">
                            <p class="h1">Lorem ipsum dolor sit amet</p>
                            <p>Maecenas sed ante pellentesque, posuere leo id, eleifend dolor. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Praesent laoreet malesuada cursus. Maecenas scelerisque congue eros eu posuere. Praesent in felis ut velit pretium lobortis rhoncus ut erat.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr class="block two_image_text_columns">
            <td class="two-column">
                <!--[if (gte mso 9)|(IE)]>
                <table width="100%">
                <tr>
                <td width="50%" valign="top">
                <![endif]-->
                <div class="column">
                    <table width="100%">
                        <tr>
                            <td class="inner">
                                <table class="contents">
                                    <tr>
                                        <td>
                                            <img src="{{ url("images/icons/image_placeholder_medium.png") }}" width="280" alt="" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text">
                                            Maecenas sed ante pellentesque, posuere leo id, eleifend dolor. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. 
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
                <!--[if (gte mso 9)|(IE)]>
                </td><td width="50%" valign="top">
                <![endif]-->
                <div class="column">
                    <table width="100%">
                        <tr>
                            <td class="inner">
                                <table class="contents">
                                    <tr>
                                        <td>
                                            <img src="{{ url("images/icons/image_placeholder_medium.png") }}" width="280" alt="" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text">
                                            Maecenas sed ante pellentesque, posuere leo id, eleifend dolor. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. 
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
                <!--[if (gte mso 9)|(IE)]>
                </td>
                </tr>
                </table>
                <![endif]-->
            </td>
        </tr>
        <tr class="block three_image_text_columns">
            <td class="three-column">
                <!--[if (gte mso 9)|(IE)]>
                <table width="100%">
                <tr>
                <td width="200" valign="top">
                <![endif]-->
                <div class="column">
                    <table width="100%">
                        <tr>
                            <td class="inner">
                                <table class="contents">
                                    <tr>
                                        <td>
                                            <img src="{{ url("images/icons/image_placeholder_square.png") }}" width="180" alt="" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text">
                                            Scelerisque congue eros eu posuere. Praesent in felis ut velit pretium lobortis rhoncus ut erat.
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
                <!--[if (gte mso 9)|(IE)]>
                </td><td width="200" valign="top">
                <![endif]-->
                <div class="column">
                    <table width="100%">
                        <tr>
                            <td class="inner">
                                <table class="contents">
                                    <tr>
                                        <td>
                                            <img src="{{ url("images/icons/image_placeholder_square.png") }}" width="180" alt="" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text">
                                            Scelerisque congue eros eu posuere. Praesent in felis ut velit pretium lobortis rhoncus ut erat.
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
                <!--[if (gte mso 9)|(IE)]>
                </td><td width="200" valign="top">
                <![endif]-->
                <div class="column">
                    <table width="100%">
                        <tr>
                            <td class="inner">
                                <table class="contents">
                                    <tr>
                                        <td>
                                            <img src="{{ url("images/icons/image_placeholder_square.png") }}" width="180" alt="" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text">
                                            Scelerisque congue eros eu posuere. Praesent in felis ut velit pretium lobortis rhoncus ut erat.
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
                <!--[if (gte mso 9)|(IE)]>
                </td>
                </tr>
                </table>
                <![endif]-->
            </td>
        </tr>
        <tr class="block list_image_left">
            <td class="left-sidebar">
                <!--[if (gte mso 9)|(IE)]>
                <table width="100%">
                <tr>
                <td width="100">
                <![endif]-->
                <div class="column left">
                    <table width="100%">
                        <tr>
                            <td class="inner">
                                <img src="{{ url("images/icons/image_placeholder_square.png") }}" width="80" alt="" />
                            </td>
                        </tr>
                    </table>
                </div>
                <!--[if (gte mso 9)|(IE)]>
                </td><td width="500">
                <![endif]-->
                <div class="column right">
                    <table width="100%">
                        <tr>
                            <td class="inner contents">
                                Praesent laoreet malesuada cursus. Maecenas scelerisque congue eros eu posuere. Praesent in felis ut velit pretium lobortis rhoncus ut erat. <a href="#">Read&nbsp;on</a>
                            </td>
                        </tr>
                    </table>
                </div>
                <!--[if (gte mso 9)|(IE)]>
                </td>
                </tr>
                </table>
                <![endif]-->
            </td>
        </tr>
        <tr class="block list_image_right">
            <td class="right-sidebar" dir="rtl">
                <!--[if (gte mso 9)|(IE)]>
                <table width="100%" dir="rtl">
                <tr>
                <td width="100">
                <![endif]-->
                <div class="column left" dir="ltr">
                    <table width="100%">
                        <tr>
                            <td class="inner contents">
                                <img src="{{ url("images/icons/image_placeholder_square.png") }}" width="80" alt="" />
                            </td>
                        </tr>
                    </table>
                </div>
                <!--[if (gte mso 9)|(IE)]>
                </td><td width="500">
                <![endif]-->
                <div class="column right" dir="ltr">
                    <table width="100%">
                        <tr>
                            <td class="inner contents">
                                Maecenas sed ante pellentesque, posuere leo id, eleifend dolor. Class aptent taciti sociosqu ad litora torquent per conubia nostra. <a href="#">Per&nbsp;inceptos</a>
                            </td>
                        </tr>
                    </table>
                </div>
                <!--[if (gte mso 9)|(IE)]>
                </td>
                </tr>
                </table>
                <![endif]-->
            </td>
        </tr>
        <tr class="block three_heading_columns">
            <td class="three-column">
                <!--[if (gte mso 9)|(IE)]>
                <table width="100%">
                <tr>
                <td width="200" valign="top">
                <![endif]-->
                <div class="column">
                    <table width="100%">
                        <tr>
                            <td class="inner contents">
                                <p class="h2">Heading</p>
                                <p>Class eleifend aptent taciti sociosqu ad litora torquent conubia</p>
                                <p><a href="#">Read more</a></p>
                            </td>
                        </tr>
                    </table>
                </div>
                <!--[if (gte mso 9)|(IE)]>
                </td><td width="200" valign="top">
                <![endif]-->
                <div class="column">
                    <table width="100%">
                        <tr>
                            <td class="inner contents">
                                <p class="h2">Heading</p>
                                <p>Class eleifend aptent taciti sociosqu ad litora torquent conubia</p>
                                <p><a href="#">Read more</a></p>
                            </td>
                        </tr>
                    </table>
                </div>
                <!--[if (gte mso 9)|(IE)]>
                </td><td width="200" valign="top">
                <![endif]-->
                <div class="column">
                    <table width="100%">
                        <tr>
                            <td class="inner contents">
                                <p class="h2">Heading</p>
                                <p>Class eleifend aptent taciti sociosqu ad litora torquent conubia</p>
                                <p><a href="#">Read more</a></p>
                            </td>
                        </tr>
                    </table>
                </div>
                <!--[if (gte mso 9)|(IE)]>
                </td>
                </tr>                
                </table>
                <![endif]-->
            </td>
        </tr>
        <tr class="block footer">
            <td class="one-column">                
                <table width="100%">
                    <tr>
                        <td class="inner contents">
                            <hr>
                            <p>
                                <em>Copyright &copy; {CONTACT_NAME}, All rights reserved.</em><br />
                                <br />
                                <strong>Our mailing address is:</strong><br />
                                <a href="mailto:{CONTACT_EMAIL}">{CONTACT_EMAIL}</a><br />
                                <br />
                                Want to change how you receive these emails?<br />
                                You can <a href="{UPDATE_PROFILE_URL}">update your preferences</a> or <a href="{UNSUBSCRIBE_URL}">unsubscribe from this list</a>
                            </p>
                            <hr>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr class="block image_right">
            <td class="right-image" dir="rtl">
                <!--[if (gte mso 9)|(IE)]>
                <table width="100%" dir="rtl">
                <tr>
                <td width="245">
                <![endif]-->
                <div class="column left" dir="ltr">
                    <table width="100%">
                        <tr>
                            <td class="inner contents">
                                <img src="{{ url("images/icons/image_placeholder_medium.png") }}" width="220" alt="" />
                            </td>
                        </tr>
                    </table>
                </div>
                <!--[if (gte mso 9)|(IE)]>
                </td><td width="350">
                <![endif]-->
                <div class="column right" dir="ltr">
                    <table width="100%">
                        <tr>
                            <td class="inner contents">
                                <p class="h2">Heading</p>
                                <p>Maecenas sed ante pellentesque, posuere leo id, eleifend dolor. Class aptent taciti sociosqu ad litora torquent per conubia nostra. <a href="#">Per&nbsp;inceptos</a></p>
                            </td>
                        </tr>
                    </table>
                </div>
                <!--[if (gte mso 9)|(IE)]>
                </td>
                </tr>
                </table>
                <![endif]-->
            </td>
        </tr>
        <tr class="block image_left">
            <td class="left-image">
                <!--[if (gte mso 9)|(IE)]>
                <table width="100%">
                <tr>
                <td width="245">
                <![endif]-->
                <div class="column left">
                    <table width="100%">
                        <tr>
                            <td class="inner contents">
                                <img src="{{ url("images/icons/image_placeholder_medium.png") }}" width="220" alt="" />
                            </td>
                        </tr>
                    </table>
                </div>
                <!--[if (gte mso 9)|(IE)]>
                </td><td width="350">
                <![endif]-->
                <div class="column right">
                    <table width="100%">
                        <tr>
                            <td class="inner contents">
                                <p class="h2">Heading</p>
                                <p>Maecenas sed ante pellentesque, posuere leo id, eleifend dolor. Class aptent taciti sociosqu ad litora torquent per conubia nostra. <a href="#">Per&nbsp;inceptos</a></p>
                            </td>
                        </tr>
                    </table>
                </div>
                <!--[if (gte mso 9)|(IE)]>
                </td>
                </tr>
                </table>
                <![endif]-->
            </td>
        </tr>
    </table>
    
    <toolbox>
        <div class="left-box">
            <div class="block tool-item" template="full_width_banner">
                <i class="icon-file-picture"></i>
                <label>{{ trans('messages.full_width_banner') }}</label>
            </div>
            <div class="block tool-item" template="single_text_row">
                <i class="icon-paragraph-left2"></i>
                <label>{{ trans('messages.single_text_row') }}</label>
            </div>
            <div class="block tool-item" template="two_image_text_columns">
                <i class="icon-newspaper2"></i>
                <label>{{ trans('messages.two_image_text_columns') }}</label>
            </div>
            <div class="block tool-item" template="three_image_text_columns">
                <i class="icon-stack-picture"></i>
                <label>{{ trans('messages.three_image_text_columns') }}</label>
            </div>
            <div class="block tool-item" template="list_image_left">
                <i class="icon-list"></i>
                <label>{{ trans('messages.list_image_left') }}</label>
            </div>
            <div class="block tool-item" template="list_image_right">
                <i class="icon-list" style="
                    -ms-transform: rotate(180deg); /* IE 9 */
                    -webkit-transform: rotate(180deg); /* Chrome, Safari, Opera */
                    transform: rotate(180deg);"></i>
                <label>{{ trans('messages.list_image_right') }}</label>
            </div>
            <div class="block tool-item" template="three_heading_columns">
                <i class="icon-stack-text"></i>
                <label>{{ trans('messages.three_heading_columns') }}</label>
            </div>
            <div class="block tool-item" template="footer">
                <i class="icon-ipad"></i>
                <label>{{ trans('messages.footer') }}</label>
            </div>
            <div class="block tool-item" template="image_right">
                <i class="icon-newspaper" style="
                    -ms-transform: rotate(180deg); /* IE 9 */
                    -webkit-transform: rotate(180deg); /* Chrome, Safari, Opera */
                    transform: rotate(180deg);"></i>
                <label>{{ trans('messages.image_right') }}</label>
            </div>
            <div class="block tool-item" template="image_left">
                <i class="icon-newspaper"></i>
                <label>{{ trans('messages.image_left') }}</label>
            </div>
        </div>
    </toolbox>
    
    <editor>
        <h1>{{ trans('messages.editor') }}</h1>
        <textarea class="builder-editor pull-right"></textarea>
        <br />
        <div style="text-align: right">
            <!--<button class="btn btn-primary save-editor">{{ trans('messages.save_and_close') }}</button>-->
            <button class="btn bg-slate close-editor">{{ trans('messages.close') }}</button>            
        </div>
    </editor>
    
    <leftbar>
        <div class="container">
            <h1>{{ trans('messages.elements') }}</h1>
            <div class="toolbox-container">
                
            </div>
        </div>
    </leftbar>
    
    @yield('template_content')
    
</body>
</html>