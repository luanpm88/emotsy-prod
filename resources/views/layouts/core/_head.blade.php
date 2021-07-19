<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="{{ \Acelle\Model\Setting::get("site_description") }}">
<meta name="keywords" content="{{ \Acelle\Model\Setting::get("site_keyword") }}" />
<meta name="php-version" content="{{ phpversion() }}" />

<title>@yield('title') - {{ \Acelle\Model\Setting::get("site_name") }}</title>

@include('layouts.core._favicon')

<!-- Font -->
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i&amp;subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese" rel="stylesheet">

<!-- jQuery -->
<script type="text/javascript" src="{{ URL::asset('core/js/jquery-3.6.0.min.js') }}"></script>

<!-- Bootstrap -->
<link rel="stylesheet" type="text/css" href="{{ URL::asset('core/bootstrap/css/bootstrap.min.css') }}">
<script type="text/javascript" src="{{ URL::asset('core/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Select2 -->
<link rel="stylesheet" type="text/css" href="{{ URL::asset('core/select2/css/select2.min.css') }}">
<script type="text/javascript" src="{{ URL::asset('core/select2/js/select2.min.js') }}"></script>

<!-- Validate -->
<script type="text/javascript" src="{{ URL::asset('core/validate/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('core/js/validate.js') }}"></script>

<!-- Numeric -->
<script type="text/javascript" src="{{ URL::asset('core/numeric/jquery.numeric.min.js') }}"></script>

<!-- Tooltip -->
<link rel="stylesheet" href="{{ URL::asset('core/tooltipster/css/tooltipster.bundle.min.css') }}">
<link rel="stylesheet" href="{{ URL::asset('core/tooltipster/css/plugins/tooltipster/sideTip/themes/tooltipster-sideTip-light.min.css') }}">
<script type="text/javascript" src="{{ URL::asset('core/tooltipster/js/tooltipster.bundle.min.js') }}"></script>

<!-- Google icon -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined"rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round"rel="stylesheet">

<!-- Autofill -->
<link rel="stylesheet" type="text/css" href="{{ URL::asset('core/css/autofill.css') }}">
<script type="text/javascript" src="{{ URL::asset('core/js/autofill.js') }}"></script>

<!-- AcelleMail -->
<link rel="stylesheet" type="text/css" href="{{ URL::asset('core/css/theme.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('core/css/app.css') }}">

<script type="text/javascript" src="{{ URL::asset('core/js/functions.js') }}"></script>

<script type="text/javascript" src="{{ URL::asset('core/js/link.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('core/js/box.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('core/js/popup.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('core/js/list.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('core/js/anotify.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('core/js/dialog.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('core/js/iframe_modal.js') }}"></script>

<script type="text/javascript" src="{{ URL::asset('core/js/app.js') }}"></script>