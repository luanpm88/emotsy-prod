/* ------------------------------------------------------------------------------
*
*  # Styled checkboxes, radios and file input
*
*  Specific JS code additions for form_checkboxes_radios.html page
*
*  Version: 1.0
*  Latest update: Aug 1, 2015
*
* ---------------------------------------------------------------------------- */

// Preview upload image
function readURL(input, img) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            img.attr('src', e.target.result);
            
            // calculate crop part
            var box_width = img.parent().width();
            var box_height = img.parent().height();
            var width = img[0].naturalWidth;
            var height = img[0].naturalHeight;
            var cal_width, cal_height;
            
            if(width/height < box_width/box_height) {
                cal_height = box_height;
                cal_width = box_height*(height/width);
            } else {
                cal_width = box_width;
                cal_height = box_width*(width/height);
            }
            
            img.width(cal_height);
            img.height(cal_width);
            
            var mleft = -Math.abs(cal_width - box_width)/2;
            var mtop = -Math.abs(cal_height - box_height)/2;
            img.css("margin-left", mtop+"px");
            img.css("margin-top", mleft+"px");
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function popupwindow(url, title, w, h) {
  var left = (screen.width/2)-(w/2);
  var top = 0;
  return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+screen.height+', top='+top+', left='+left);
} 

$(function() {
    // Default tooltip
    $('[data-popup=tooltip]').tooltip({
		template: '<div class="tooltip"><div class="bg-teal-800"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div></div>'
	});
    
    // Basic select2
    // ------------------------------

    // Default initialization
    $('.select').select2({
        minimumResultsForSearch: Infinity
    });

    // Select with search
    $('.select-search').select2();
    
    
    // Checkboxes/radios (Uniform)
    // ------------------------------

    // Default initialization
    $(".styled, .multiselect-container input").uniform({
        radioClass: 'choice'
    });
    
    
    // Form help text
    // ------------------------------
    
    $(".form-control").focus( function() {
        $(this).parents(".form-group").find(".help").addClass("showed");
    });

    $(".form-control").blur( function() {
        $(this).parents(".form-group").find(".help").removeClass("showed");
    });
    
    // Preview upload image    
    $(".previewable").change(function() {
        var img = $("img[preview-for='" + $(this).attr("name") + "']");
        readURL(this, img);
    });
    $(".remove-profile-image").click(function() {
        var img = $(this).parents(".profile-image").find("img");
        var imput = $(this).parents(".profile-image").find("input[name='_remove_image']");
        img.attr("src", img.attr("empty-src"));
        imput.val("true");
    });
    
    //
    // Switchery
    // ------------------------------

    // Initialize multiple switches
    $('.switchery').each(function() {
        if($(this).attr("data-switchery") != "true") {
            var switchery = new Switchery(this);
        }
    }); 
    
    // Bootstrap switch
    // ------------------------------
    $(".switch").bootstrapSwitch();
    
    
    // Action list event
    // ------------------------------
    $(document).on("click", ".list_actions a", function(e) {
        var form = $(this).parents(".listing-form");
        var vals = form.find("input[name='ids[]']:checked").map(function () {
            return this.value;
        }).get();
        
        $(this).attr("href", $(this).attr("href") + "?uids=" + vals.join(","));
    });
    
    
    // Confirm event
    // ------------------------------
    $(document).on("click", "a[delete-confirm]", function(e) {
        var mgs = $(this).attr("delete-confirm");
        
        $('a[data-target="#delete_confirm_model"]').trigger("click");        
        $("#delete_confirm_model h6").html(mgs);
        $(".delete-confirm-button").attr("href", $(this).attr("href"));
        
        e.stopImmediatePropagation();
        e.preventDefault();
    });
    $(document).on("click", ".delete-confirm-button", function(e) {
        if($('.confirm-delete-form').valid()) {
            
        } else {
            e.preventDefault();
        }        
    });
    // Link confirm
    $(document).on("click", "a[link-confirm]", function(e) {
        var mgs = $(this).attr("link-confirm");
        
        $('a[data-target="#link_confirm_model"]').trigger("click");        
        $("#link_confirm_model h6").html(mgs);
        $(".link-confirm-button").attr("href", $(this).attr("href"));
        
        e.stopImmediatePropagation();
        e.preventDefault();
    });
    
    // List fields
    // ------------------------------
    // Change item per page
    $(document).on("click", ".add-custom-field-button", function(e) {
        var type_name = $(this).attr("type_name");
        var sample = $("."+type_name+"_sample ");
        var sample_url = $(this).attr("sample-url");
        
        // ajax update custom sort
        $.ajax({
            method: "GET",
            url: sample_url,
            data: {
                type: type_name,
            }
        })
        .done(function( msg ) {
            var index = $('.field-list tr').length;
            
            msg = msg.replace(/__index__/g, index);
            msg = msg.replace(/__type__/g, type_name);
            
            $('.field-list').append($('<div>').html(msg).find("table tbody").html());
            
            //
            // Switchery
            // ------------------------------
        
            // Initialize multiple switches
            $('.switchery').each(function() {
                if($(this).attr("data-switchery") != "true") {
                    var switchery = new Switchery(this);
                }
            });                
        });
    });
    $(document).on("click", ".remove-not-saved-field", function(e) {
        $('tr[parent="'+$(this).parents('tr').attr('rel')+'"]').remove();
        $(this).parents('tr').remove();
    });
    $(document).on("click", ".add_label_value_group", function(e) {
        var last_item = $(this).parents("tr").find(".label-value-groups .label-value-group").last();
        var pre = last_item.attr("rel");
        var num = parseInt(pre)+1;
        var clone = $('<div>').append(last_item.clone()).html();

        clone = clone.replace('rel="'+pre+'"', 'rel="'+num+'"');
        clone = clone.replace('[options]['+pre+'][', '[options]['+num+'][');
        clone = clone.replace('[options]['+pre+'][', '[options]['+num+'][');
        clone = clone.replace('[options]['+pre+'][', '[options]['+num+'][');
        clone = clone.replace('[options]['+pre+'][', '[options]['+num+'][');
        clone = clone.replace('[options]['+pre+'][', '[options]['+num+'][');
        clone = clone.replace('[options]['+pre+'][', '[options]['+num+'][');
        clone = clone.replace('[options]['+pre+'][', '[options]['+num+'][');
        
        $(this).parents("tr").find(".label-value-groups").append(clone);
        $(this).parents("tr").find(".label-value-groups .label-value-group").last().find("input").val("");
        $(this).parents("tr").find(".label-value-groups .label-value-group").last().find(".help-block").remove();
        $(this).parents("tr").find(".label-value-groups .label-value-group").last().find(".form-group").removeClass("has-error");
    });
    
    // Validate confirm modal
    jQuery.validator.addMethod("deleteConfirm", function(value, element) {
        return value.toLowerCase() == "delete";
    }, LANG_DELETE_VALIDATE);
    $(".confirm-delete-form").each(function() {
        $(this).validate({
            rules: {
                delete: { deleteConfirm: true }
            }
        });
    });
    
    // Basic options
    $('.pickadate').pickadate({format: LANG_DATE_FORMAT});
    
    // Numberic input
    $(".numeric").numeric();
    
    // add segment condition
    $(document).on("click", ".add-segment-condition", function(e) {
        // ajax update custom sort
        $.ajax({
            method: "GET",
            url: $(this).attr('sample-url'),
        })
        .done(function( msg ) {
            var num = "0";
            
            if($('.segment-conditions-container .condition-line').length) {
                num = parseInt($('.segment-conditions-container .condition-line').last().attr("rel"))+1;
            }
            
            msg = msg.replace(/__index__/g, num);
            
            $('.segment-conditions-container').append(msg);
            
            var new_line = $('.segment-conditions-container .condition-line').last();
            new_line.find('select').select2();
        });
    });
    
    // add segment condition
    $(document).on("change", ".condition-line .operator-col select", function(e) {
        var op = $(this).val();
        
        if(op == 'blank' || op == 'not_blank') {
            $(this).parents(".condition-line").find('.value-col').css("visibility", "hidden");
        } else {
            $(this).parents(".condition-line").find('.value-col').css("visibility", "visible");
        }
    });
    
    // add segment condition
    $(document).on("click", "a.ajax_link", function(e) {
        e.preventDefault();
        $(".modal").modal('hide');
        
        var url = $(this).attr("href");
        var form = $(this).parents(".listing-form");
        
        $.ajax({
            method: "GET",
            url: url,
        })
        .done(function( msg ) {
            tableFilter($(".listing-form"));
            if(msg != '') {
                swal({
                    title: msg,
                    text: "",
                    confirmButtonColor: "#00695C",
                    type: "success",
                    allowOutsideClick: true,
                    confirmButtonText: LANG_OK,
                    customClass: "swl-success"
                });
            }
        });        
    });
    
    // Primary file input
	$(".file-styled-primary").uniform({
		wrapperClass: 'bg-warning',
		fileButtonHtml: '<i class="icon-plus2"></i>'
	});
    
    // Styled file input
    $(".file-styled").uniform({
        wrapperClass: 'bg-teal-400',
        fileButtonHtml: '<i class="icon-googleplus5"></i>'
    });
    
    // page preview action
    $(document).on("click", ".preview-page-button", function(e) {
        var url = $(this).attr('page-url');
        tinyMCE.triggerSave();
        var formData = new FormData($("#update-page")[0]);
        var frame = $('.preview_page_frame');
        var current_action = $("#update-page").attr("action");
        $("#update-page").attr('target', 'preview_page_frame');
        $("#update-page").attr('action', url);
        $("#update-page").submit();
        
        // after submit
        $("#update-page").removeAttr('target');
        $("#update-page").attr('action', current_action);

    });
    
    // Click to insert tag
    $(document).on("click", ".insert_tag_button", function(e) {
        var tag = $(this).attr("data-tag-name");
        
        tinymce.activeEditor.execCommand('mceInsertContent', false, tag);
    });
    
    // Segments select box by list
    $(document).on("change", ".list_select_box select", function(e) {
        var url = $(this).parents('.list_select_box').attr("segments-url");
        var box = $("."+$(this).parents('.list_select_box').attr("target-box"));
        var id = $(this).val();
        
        if(id != '') {
            $.ajax({
                method: "GET",
                url: url,
                data: {
                    list_uid: id
                }
            })
            .done(function( msg ) {
                box.html(msg);
                
                // Select with search
                $('.select-search').select2();
            });
        } else {
            box.html('');
        }
    });
});