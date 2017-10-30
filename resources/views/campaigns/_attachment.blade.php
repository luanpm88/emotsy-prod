<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/js/plugins/dropzone/css/dropzone.css') }}" />
<div class="container">
  <div class="image_upload_div">
    <form action="{{url('/')}}/filemanager/upload_attachment.php" class="dropzone">
       <input name="path" value="../campaign_attachment/{{$campaign->uid}}/" type="hidden">
       <input name="path_thumb" value="../thumbs/{{$campaign->uid}}/" type="hidden">
    </form>
  </div>
  <div>
    <?php
      $path_campaign=public_path().'/campaign_attachment'.'/'.$campaign->uid;
      if (is_dir($path_campaign)) {
          echo "Below are attachment list:- <br/>";
          $ffs = scandir($path_campaign);

          unset($ffs[array_search('.', $ffs, true)]);
          unset($ffs[array_search('..', $ffs, true)]);

          // prevent empty ordered elements
          if (count($ffs) < 1) {
              return;
          }

          echo '<ul>';
          foreach ($ffs as $k=>$ff) {
              echo '<li id="delete_'.$k.'">'.$ff; ?>
            <form action="{{url('/')}}/filemanager/force_download_attachment.php" method="post" class="download-form" id="{{$k}}">
              <input name="path" value="{{$campaign->uid}}{{'/'}}" type="hidden">
              <input class="name_download" name="name" value="{{$ff}}" type="hidden">
              <a title="" class="tip-right" href="javascript:void('')" onclick="$('#{{$k}}').submit();" data-original-title="Download"><i class="icon-download"></i>Download</a>

             <a file-delete-confirm="{{url('/')}}/filemanager/execute_attachment.php?action=delete_file&file={{$ff}}&path={{$campaign->uid}}{{'/'}}" href="{{url('/')}}/filemanager/execute_attachment.php?action=delete_file&file={{$ff}}&path={{$campaign->uid}}{{'/'}}" title="Delete" class="" data-del="delete_{{$k}}">
                 <i class="icon-trash"></i> Delete
              </a>
            </form>
            <?php
                  if (is_dir($path_campaign.'/'.$ff)) {
                      listFolderFiles($path_campaign.'/'.$ff);
                  }
              echo '</li>';
          }
          echo '</ol>';
      }
    ?>

  </div>
 </div>
