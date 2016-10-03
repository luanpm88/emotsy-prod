@extends('layouts.builder')

@section('content')

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!--[if !mso]><!-->
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!--<![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/res_email.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/builder.css') }}" />
    <!--[if (gte mso 9)|(IE)]>
    <style type="text/css">
        table {border-collapse: collapse;}
    </style>
    <![endif]-->
    
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        
    <script>
    $( function() {
        $( ".left-box" ).sortable({
            items: '.block',
            connectWith: ".right-box",
            start: function(e, ui) {
                console.log(ui.item);
            },
        });
        $( ".right-box" ).sortable({
            items: '.block',
        });
        
        $( "content" ).height( $( window ).height() );
    });
    $( window ).resize(function() {
        $( "content" ).height( $( window ).height() );
    });
    </script>
    
</head>
<body>
    <leftbar>
        <div class="left-box">
            <div class="block">
                asdasdasdasd
            </div>
            <div class="block">
                asdasdasdasd
            </div>
        </div>
    </leftbar>
    <content>
        <center class="wrapper">
            <div class="webkit">
                <!--[if (gte mso 9)|(IE)]>
                <table width="600" align="center">
                <tr>
                <td>
                <![endif]-->
                <table class="outer right-box" align="center">
                    <tr class="block">
                        <td>
                            <table class="outer" align="center">
                                <tr>
                                    <td class="full-width-image">
                                        <img src="http://akashinfoteck.com/images/Hp_Laptop-service%20center%20in%20dwarka.jpg" width="600" alt="" />
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr class="block">
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
                    <tr class="block">
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
                                                        <img src="http://akashinfoteck.com/images/Hp_Laptop-service%20center%20in%20dwarka.jpg" width="280" alt="" />
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
                                                        <img src="http://akashinfoteck.com/images/Hp_Laptop-service%20center%20in%20dwarka.jpg" width="280" alt="" />
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
                    <tr class="block">
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
                                                        <img src="http://akashinfoteck.com/images/Hp_Laptop-service%20center%20in%20dwarka.jpg" width="180" alt="" />
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
                                                        <img src="http://akashinfoteck.com/images/Hp_Laptop-service%20center%20in%20dwarka.jpg" width="180" alt="" />
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
                                                        <img src="http://akashinfoteck.com/images/Hp_Laptop-service%20center%20in%20dwarka.jpg" width="180" alt="" />
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
                    <tr class="block">
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
                    <tr class="block">
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
                                            <img src="http://icons.iconarchive.com/icons/designbolts/free-multimedia/1024/iMac-icon.png" width="80" alt="" />
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
                    <tr class="block">
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
                                            <img src="http://icons.iconarchive.com/icons/designbolts/free-multimedia/1024/iMac-icon.png" width="80" alt="" />
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
                </table>
                <!--[if (gte mso 9)|(IE)]>
                </td>
                </tr>
                </table>
                <![endif]-->
            </div>
        </center>
    </content>
</body>
</html>
    
@endsection