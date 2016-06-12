function formFilter(table) {
    alert(form.length);
}

function updateCustomOrder(form) {
    var table = form.find("table");
    var per_page = parseInt(table.attr("per-page"));
    var page = parseInt(table.attr("current-page"));
    var sort = [];
    form.find("table tr input.node").each(function(index) {
        var total = index + ((page-1)*per_page);
        var row = [];
        row.push($(this).attr("item-id"));
        row.push(total);
        sort.push(row);
    });
    console.log(JSON.stringify(sort));
    
    // ajax update custom sort
    $.ajax({
        method: "GET",
        url: table.attr("sort-url"),
        data: { sort: JSON.stringify(sort) }
    })
    .done(function( msg ) {
        // Success alert
        swal({
            title: msg,
            text: "",
            confirmButtonColor: "#00695C",
            type: "success",
            allowOutsideClick: true,
            confirmButtonText: LANG_OK,
        });	
    });
}

$(document).ready(function() {
    
    $(".listing-form").each(function() {
        var form = $(this);
        
        // Make list sortable
        form.sortable({
            connectWith: '.row-sortable',
            items: 'tr',
            helper: 'original',
            cursor: 'move',
            handle: '[data-action=move]',
            revert: 100,
            containment: 'tbody',
            forceHelperSize: true,
            placeholder: 'sortable-placeholder',
            forcePlaceholderSize: true,
            tolerance: 'pointer',
            start: function(e, ui){
                ui.placeholder.height(ui.item.outerHeight());
            },
            update: function(e, ui){
                updateCustomOrder(form);
            },
        });
    
    });
});