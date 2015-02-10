$(function () {
    $('.chosen-single').chosen({disable_search_threshold: 10});
    $('#branch').on('change', $.proxy(LoadBranchSpecialization, null, '#branch_specialization'));
});

function LoadBranchSpecialization (target) {
    $.getJSON('ajax/getBranchSpecialization').done(function(data) {
        
    });
}