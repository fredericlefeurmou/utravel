$(function () {
    $('.chosen-multiple').multiselect({
        nonSelectedText: "Indifférent", 
        buttonText: function (options, select) {
            if (options.length == 0) {
                return 'Indifférent <b class="caret"></b>';
            } else  if (options.length > 1) {
                return options.length + ' sélectionnés <b class="caret"></b>';
            } else {
                return $(options[0]).text() + ' <b class="caret"></b>';
            }
        }
    });
    
    $('.chosen-multiple-search').chosen({display_disabled_options: false, placeholder_text_multiple: "Rechercher un pays", no_results_text: "Aucun résultat"});
    
    $('#form-search').ajaxForm({dataType: 'json', beforeSubmit: beforeSearch, success: searchSuccess, error: searchError});
    $('.branch-select').on('change', branchChanged);
    $('.specialization-div-search').hide();
    $('#form_specialization').data('options', $('#form_specialization optgroup'));
    $('#form-search').trigger('submit');
});

var prevSearch = null;
var usePrevsearch = false;

function branchChanged () {
    var branches = [];
    $(this).find('option:selected').each(function () {
        console.log($(this).val());
        if ($(this).val()) {
            branches.push($(this).text());
        }
    });
    $('#form_specialization').empty().append($('#form_specialization').data('options'));
    if (branches.length > 0) {
        $('#form_specialization optgroup').prop('disabled', true);
        for (var i in branches) {
            $('#form_specialization optgroup[label="'+branches[i]+'"]').prop('disabled', false);
            $('#form_specialization').trigger('chosen:updated');
        }
        $('#form_specialization optgroup:disabled').remove();
        $('.specialization-div-search').show();
    } else {
        $('.specialization-div-search').hide();
    }
    $('#form_specialization').multiselect('rebuild');
}

function beforeSearch (array) {
    if (usePrevsearch) {
        array.length = 0;
        for (var k in prevSearch) {
            array.push(prevSearch[k]);
        }
        usePrevSearch = false;
    } else {
        prevSearch = array;
    }
    //$('#main-search').block({message: null, css: { backgroundColor: '#FFF'} });
    $('#search-status').removeClass('text-danger text-success');
    $('#form-search button[type="submit"]').prop('disabled', true);
    $('#search-status').html('<img src="data:image/gif;base64,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"/> Recherche en cours');
}

function searchSuccess (data) {
    $('#search-status').addClass('text-success');
    $('#form-search button[type="submit"]').prop('disabled', false);
    $('#search-status').html(data.count+' résultats');
    $('#search-result').html('<div class="page-result page-'+data.page+'">'+data.html+'</div>');
    if (data.page > 0) {
        $('.link-prev-page').css('visibility', 'visible');
    } else {
        $('.link-prev-page').css('visibility', 'hidden');
    }
    if (data.offset+data.nbResultPage < data.count) {
        $('.link-next-page').css('visibility', 'visible');
    } else {
        $('.link-next-page').css('visibility', 'hidden');
    }
    var maxPage = Math.ceil(data.count / data.nbResultPage);
    if (maxPage > 0) {
        $('.page-nb').removeClass('text-warning');
        $('.page-nb').text('Page '+(data.page+1)+' / '+maxPage);
    } else {
        $('.page-nb').addClass('text-warning');
        $('.page-nb').text('Aucun résultat');
    }
    $('#main-search').unblock();
}

function searchError () {
    $('#search-status').addClass('text-danger');
    $('#form-search button[type="submit"]').prop('disabled', false);
    $('#search-status').html('Une erreur est survenue, réessayez plus tard');
}

function changePage (inc) {
    $('.curent-page').val(parseInt($('.curent-page').val())+inc);
    usePrevSearch = true;
    $('#form-search').trigger('submit');
}