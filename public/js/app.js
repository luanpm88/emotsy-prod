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

$(function() {
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
    
});