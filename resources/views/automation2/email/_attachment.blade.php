<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/js/plugins/dropzone/css/dropzone.css') }}" />

  <div class="image_upload_div">
    <form action="{{url('/')}}/filemanager/upload_attachment.php" class="dropzone">
       <input name="path" value="../email_attachment/{{$email->uid}}/" type="hidden">
       <input name="path_thumb" value="../thumbs/{{$email->uid}}/" type="hidden">
    </form>
  </div>
  <div class="attachments_pnl">
    <?php
      $path_email=public_path().'/email_attachment'.'/'.$email->uid;
      if (is_dir($path_email)) {
          echo "<h3 class='mt-40'>" . trans('messages.email.attached_files') . " <br/></h3>";
          $ffs = scandir($path_email);

          unset($ffs[array_search('.', $ffs, true)]);
          unset($ffs[array_search('..', $ffs, true)]);

          // prevent empty ordered elements
          if (count($ffs) < 1) {
              return;
          }

          echo '<ul>';
          foreach ($ffs as $k=>$ff) {
                $fileszie = formatSizeUnits(filesize( $path_email . '/' . $ff));
              echo '<li id="delete_'.$k.'"><i class="lnr lnr-file-empty icon-file"></i><span><name>'.$ff.'</name><size>'.trans('messages.email.attachment.file_size_is', ['size' => $fileszie]).'</size></span>'; ?> 
            <form action="{{url('/')}}/filemanager/force_download_attachment.php" method="post" class="download-form" id="{{$k}}">
              <input name="path" value="{{$email->uid}}{{'/'}}" type="hidden">
              <input class="name_download" name="name" value="{{$ff}}" type="hidden">
<span>
              <a title="" class="tip-right" href="javascript:void('')" onclick="$('#{{$k}}').submit();" data-original-title="Download">{{ trans('messages.download') }}</a>
|
             <a
                file-delete-confirm="{{url('/')}}/filemanager/execute_attachment.php?_token={{ csrf_token() }}&action=delete_file&file={{$ff}}&path={{$email->uid}}{{'/'}}"
                href="{{url('/')}}/filemanager/execute_attachment.php?_token={{ csrf_token() }}&action=delete_file&file={{$ff}}&path={{$email->uid}}{{'/'}}"
                title="Delete"
                class=""
                data-del="delete_{{$k}}"
            >
                 {{ trans('messages.remove') }}
              </a>
</span>
            </form>
            <?php
                  if (is_dir($path_email.'/'.$ff)) {
                      listFolderFiles($path_email.'/'.$ff);
                  }
              echo '</li>';
          }
          echo '</ol>';
      }
    ?>

  </div>
