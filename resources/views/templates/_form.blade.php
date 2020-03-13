              
              
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
            skin: "oxide-dark",
            forced_root_block: "",
            plugins: [
            'fullpage table advlist autolink lists link image charmap print preview anchor directionality',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media contextmenu paste code textcolor'
            ],
            toolbar: 'acelletags insertfile undo redo | fontselect | fontsizeselect | styleselect | bold italic | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | ltr rtl',
            valid_elements : '*[*],meta[*]',
            extended_valid_elements : "meta[*]",
            valid_children : "+body[style],+body[meta],+div[h2|span|meta|object],+object[param|embed]",
            content_css: [
                APP_URL.replace('/index.php','')+'/tinymce/skins/lightgray/content.fixed.css',
            ],
            external_filemanager_path:APP_URL.replace('/index.php','')+"/filemanager2/",
            filemanager_title:"Responsive Filemanager" ,
            external_plugins: { "filemanager" : APP_URL.replace('/index.php','')+"/filemanager2/plugin.min.js"},
            setup: function (editor) {
                
                /* Menu button that has a simple "insert date" menu item, and a submenu containing other formats. */
                /* Clicking the first menu item or one of the submenu items inserts the date in the selected format. */
                editor.ui.registry.addMenuButton('acelletags', {
                    text: '{{ trans('messages.editor.insert_tag') }}',
                    fetch: function (callback) {
                    var items = [];

                    @foreach(Acelle\Model\Template::tags() as $tag)
                        items.push({
                            type: 'menuitem',
                            text: '{{ "{".$tag["name"]."}" }}',
                            onAction: function (_) {
                                editor.insertContent('{{ "{".$tag["name"]."}" }}');
                            }
                        });
                    @endforeach

                    callback(items);
                    }
                });
            }
        });
    });
</script>
							
							