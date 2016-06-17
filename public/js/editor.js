$(document).ready(function() {
    tinymce.init({
        selector: 'textarea',
        height: 500,
        plugins: [
          'advlist autolink lists link image charmap print preview anchor',
          'searchreplace visualblocks code fullscreen',
          'insertdatetime media table contextmenu paste code'
        ],
        toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        content_css: [
          APP_URL+'/css/all.css',
          APP_URL+'/css/page.css',
        ]
    });    
});