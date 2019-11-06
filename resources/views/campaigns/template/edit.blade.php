<!doctype html>
<html>
  <head>
    <title>{{ trans('messages.campaign.edit_template') }} - {{ $campaign->name }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    @include('layouts._favicon')
    
    <link href="{{ URL::asset('builder/builder.css') }}" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="{{ URL::asset('builder/builder.js') }}"></script>
    
    <script>
        var CSRF_TOKEN = "{{ csrf_token() }}";
        var editor;
        
        $( document ).ready(function() {
            editor = new Editor({
                url: '{{ action('CampaignController@templateContent', $campaign->uid) }}',
                urlBack: '{{ action('CampaignController@template', $campaign->uid) }}',
                uploadAssetUrl: '{{ action('CampaignController@templateAsset', $campaign->uid) }}',
                uploadAssetMethod: 'POST',
                saveUrl: '{{ action('CampaignController@templateEdit', $campaign->uid) }}',
                saveMethod: 'POST',
                tags: {!! json_encode(Acelle\Model\Template::builderTags((isset($list) ? $list : null))) !!},
                root: '{{ URL::asset('builder') }}/',
            });
          
            editor.init();
        });
    </script>
  </head>
  <body>
  </body>
</html>