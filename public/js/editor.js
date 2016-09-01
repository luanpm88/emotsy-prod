$(document).ready(function() {
    tinymce.init({
        selector: '.full-editor',
        height: 500,
        relative_urls: false,
        remove_script_host: false,
        plugins: [
          'advlist autolink lists link image charmap print preview anchor',
          'searchreplace visualblocks code fullscreen',
          'insertdatetime media table contextmenu paste responsivefilemanager code'
        ],
        toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        content_css: [
          APP_URL+'/css/all.css',
          APP_URL+'/css/page.css',
        ],
        
        external_filemanager_path:"/filemanager/",
        filemanager_title:"Responsive Filemanager" ,
        external_plugins: { "filemanager" : "/filemanager/plugin.min.js"}
    });
    
    tinymce.init({
        selector: '.email-editor',
        height: 500,
        relative_urls: false,
        remove_script_host: false,
        plugins: [
          'advlist autolink lists link image charmap print preview anchor',
          'searchreplace visualblocks code fullscreen',
          'insertdatetime media table contextmenu paste code'
        ],
        toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        content_css: [
          APP_URL+'/css/email.css',
        ]
    });
    
    tinymce.init({
        selector: '.clean-editor',
        height: 500,
        relative_urls: false,
        remove_script_host: false,
        plugins: [
          'advlist autolink lists link image charmap print preview anchor',
          'searchreplace visualblocks code fullscreen',
          'insertdatetime media table contextmenu paste code'
        ],
        toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        valid_elements : '*[*]',
        valid_children : "+body[style]",
        
        external_filemanager_path:"/filemanager/",
        filemanager_title:"Responsive Filemanager" ,
        external_plugins: { "filemanager" : "/filemanager/plugin.min.js"}
    }); 
});