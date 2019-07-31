<!doctype html>
<html>
  <head>
    <title>{{ trans('messages.edit_template') }} - {{ $template->name }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="image/squirrel_black.png" rel="icon" type="image/x-icon" />
    
    <link href="{{ URL::asset('builder/dist/builder.css') }}" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="{{ URL::asset('builder/dist/builder.js') }}"></script>
    
    <script>
        var CSRF_TOKEN = "{{ csrf_token() }}";
        var ROOT_URL = '{{ URL::asset('builder/src') }}';
        var editor;
        
        $( document ).ready(function() {
            editor = new Editor({
                url: '{{ action('TemplateController@builderEditContent', $template->uid) }}',
                urlBack: '{{ action('TemplateController@index') }}',
                uploadAssetUrl: '{{ action('TemplateController@builderAsset', $template->uid) }}',
                uploadAssetMethod: 'POST',
                saveUrl: '{{ action('TemplateController@builderEdit', $template->uid) }}',
                saveMethod: 'POST',
                tags: {!! json_encode(Acelle\Model\Template::builderTags((isset($list) ? $list : null))) !!},
            });
          
            editor.init();
        });
    </script>
  </head>
  <body>
  </body>
</html>