<?php
  $icons = [
      'warning' =>  'fa-exclamation-circle',
      'info' =>  'fa-info-circle'
  ]
?>
<div title="Click to suppress notification messages" class="alert alert-{{ $level }}" style="display: flex; flex-direction: row; align-items: center; cursor: pointer">
    <div style="margin-right:15px">
        <i class="fal {{ $icons[$level] }}"></i>
    </div>
    <div>
        <h4>{{ $n->title }}</h4>
        <p>{{$n->message}}</p>
    </div>
</div>