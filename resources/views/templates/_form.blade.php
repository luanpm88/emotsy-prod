              
              
@include('helpers.form_control', ['required' => true, 'type' => 'text', 'label' => trans('messages.template_name'), 'name' => 'name', 'value' => $template->name, 'rules' => ['name' => 'required']])
@include('helpers.form_control', ['class' => 'template-editor','required' => true, 'type' => 'textarea', 'name' => 'content', 'value' => $template->render(), 'rules' => ['content' => 'required']])

<script>
    var editor;
    $(document).ready(function() {
        editor = tinymce.init({
            selector: '.template-editor',
            height: 500,
            convert_urls: false,
            remove_script_host: false,
            forced_root_block: "",
            plugins: [
            'fullpage table advlist autolink lists link image charmap print preview anchor directionality',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media contextmenu paste code textcolor'
            ],
            toolbar: 'tags insertfile undo redo | fontselect | fontsizeselect | styleselect | bold italic | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | ltr rtl',
            valid_elements : '*[*],meta[*]',
            extended_valid_elements : "meta[*]",
            valid_children : "+body[style],+body[meta],+div[h2|span|meta|object],+object[param|embed]",
            content_css: [
                APP_URL.replace('/index.php','')+'/tinymce/skins/lightgray/content.fixed.css',
            ],
            external_filemanager_path:APP_URL.replace('/index.php','')+"/filemanager/",
            filemanager_title:"Responsive Filemanager" ,
            external_plugins: { "filemanager" : APP_URL.replace('/index.php','')+"/filemanager/plugin.min.js"},
            setup: function(editor) {
                editor.addButton('tags', {
                    type: 'listbox',
                    text: '{{ trans('messages.editor.insert_tag') }}',
                    icon: false,
                    onselect: function(e) {                        
                        editor.insertContent(this.value());
                        $('#mceu_0 .mce-txt').html('{{ trans('messages.editor.insert_tag') }}');
                    },
                    values: [
                        @foreach(Acelle\Model\Template::tags() as $tag)
							{text: '{{ "{".$tag["name"]."}" }}', value: '{{ "{".$tag["name"]."}" }}'},
						@endforeach
                    ],
                    onPostRender: function() {
                        // Select the firts item by default
                        this.value('JustifyLeft');
                    }
                });
            }
        });
    });
</script>
							
							