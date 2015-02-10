$(function () {
    $('.chosen-single').chosen({disable_search: true, display_disabled_options: false});
    $('.chosen-single-search').chosen({display_disabled_options: false});
    $('#country').on('change', function () {
        $('.country-filtered option').prop('disabled', true);
        $('.country-filtered option[data-country='+$(this).val()+']').prop('disabled', false);
    });
    $('.input-num').on('keyup', function(evt) {
        if (evt.keyCode !== 37 && evt.keyCode !== 39) {
            var txt = $(this).val();
            $(this).val(txt.replace(/[^0-9]/g, ''));
        }
    });
    $('.input-note').on('keyup', function() {
       var num = parseInt($(this).val().replace(/[^0-9]/g, ''));
       $(this).parent().removeClass('form-ok form-error');
       if (num <= 10) {
           $(this).parent().addClass('form-ok');
       } else {
           $(this).parent().addClass('form-error');
       }
    });
    $('.input-float').on('keyup', function(evt) {
        if (evt.keyCode !== 37 && evt.keyCode !== 39) {
            var txt = $(this).val();
            $(this).val(txt.replace(/,/g, '.').replace(/[^0-9.]/g, ''));
        }
    });
    
    var curNbCourse = $('.course-div').size()-1;
    $('#add-course').on('click', function() {
        var el = $('.course-model').clone().removeClass('course-model').insertBefore($(this));
        el.html(el.html().replace(/__name__/g, curNbCourse));
        bindFileUpload(el);
        ++curNbCourse;
    });
    $('.input-note').attr('maxlength', 2);
    
    $('#form_branch').on('change', branchChanged);
    $('#form_branch').trigger('change');
    $('#opinion-form').ajaxForm({dataType: 'json', beforeSerialize: beforeSaveOpinion, beforeSubmit: beforeSubmitOpinion, success: saveOpinionSuccess, error: saveOpinionError});
    if(window.location.hash) {
        var a = $('.tabbable a[href="#tab-'+window.location.hash.substr(1)+'"]');
        if (a.size() > 0) {
            a.trigger('click');
        }
    } 
    $('.tabbable a').on('click', function () {
        window.location.hash = $(this).attr('href').substr(5);
    });
    $('.optional-section button').on('click', function () {
       $(this).toggleClass('active');
       $(this).prevAll('input[type=checkbox]').prop('checked', !$(this).hasClass('active'));
       if ($(this).hasClass('active')) {
           $(this).closest('.can-disabled').find('input, textarea, select').prop('disabled', true).trigger("chosen:updated");
           $(this).closest('.can-disabled').find('label, h3').addClass('disabled');
       } else {
           $(this).closest('.can-disabled').find('input, textarea, select').prop('disabled', false).trigger("chosen:updated");
           $(this).closest('.can-disabled').find('label, h3').removeClass('disabled');
           $(this).closest('.can-disabled').find('.optional-section button').removeClass('active');
       }
    });
    $('.optional-section button').each(function () {
        if ($(this).prevAll('input[type=checkbox]').prop('checked') === false) {
            $(this).trigger('click');
        }
    });
    $('#opinion-form button[type=submit]').on('click', function () { 
        btnClicked = $(this).attr('name');
    });
});

function bindFileUpload (elt) {
    if (typeof elt === "undefined") elt = 'html';
    $(elt).find('.btn-upload').each(function () {
        console.log('bind');
        $(this).on('click', function() {  console.log('click'); $(this).prev('.file-with-btn').click(); });
    });
    $(elt).find('.file-with-btn').on('change', function() { 
        if ($(this).val().trim() !== "") {
            $(this).next('.btn-upload').addClass('btn-success');
        } else {
            $(this).next('.btn-upload').removeClass('btn-success');
        }
    });
}

var tmpCoursesModel;
var btnClicked;

function branchChanged () {
    var branch = $(this).find('option:selected').text();
    $('#form_specialization optgroup').prop('disabled', true);
    $('#form_specialization optgroup[label="'+branch+'"]').prop('disabled', false);
    $('#form_specialization option[value=0]').prop('disabled', false);
    $('#form_specialization').val();
    $('#form_specialization').trigger('chosen:updated');
}

function beforeSaveOpinion () {
    tmpCoursesModel = $('.course-model').remove();
    $('#edit-btn-ctn button').prop('disabled', true);
    $('#opinion-save-status').html('<img src="data:image/gif;base64,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"/>');
    $('.form-error-msg, #global-errors').hide();
    $('#opinion-form .nav-tabs .badge-error').remove();
}

function beforeSubmitOpinion (arr) {
    arr.push({name: btnClicked, value: "1", type: "submit", required: false});
}

function saveOpinionSuccess (data) {
    console.log(data);
    $('#edit-btn-ctn button').prop('disabled', false);
    $(tmpCoursesModel).insertAfter('#add-course');
    if (data.status === "ok") {
        var d = new Date();
        var h = d.getHours();
        var m = d.getMinutes();
        var str = ((h<10) ? '0'+h : ''+h) + ':' + ((m<10) ? '0'+m : ''+m);
        if (data.action === 'save') {
            $('#opinion-save-status').html("Brouillon enregistré à "+str);
        } else {
            $('#opinion-save-status').html("Publié à "+str);
        }
    } else {
        $('#opinion-save-status').html('Le formulaire comporte des erreurs');
        var tabErrors = { general : 0, life: 0, studying: 0, company: 0, housing: 0, transport: 0, final: 0};
        for (var k in data.errors) {
            var error = data.errors[k];
            $("#err-"+k).html(error).show();
            console.log(k, $("#err-"+k).closest('.tab-pane'));
            var tab = $("#err-"+k).closest('.tab-pane').attr('id').substr(4);
            tabErrors[tab] += 1;
            console.log(k, error);
        }
        var eTabs = [];
        for (var k in tabErrors) {
            if (tabErrors[k] === 0) continue;
            eTabs.push($('#opinion-form .nav-tabs a[href="#tab-'+k+'"]').text());
            $('#opinion-form .nav-tabs a[href="#tab-'+k+'"]').append('<span class="badge badge-error">'+tabErrors[k]+'</span>');
        }
        $('#global-errors').html('Le formulaire comportent des erreurs, avant de pouvoir publier l\'avis, vous devez les corriger.<br />'+
                'Il y a des erreurs dans les onglets suivants : '+eTabs.join(', ')+'.').show();
    }
}

function saveOpinionError () {
    $('#edit-btn-ctn button').prop('disabled', false);
    $(tmpCoursesModel).insertAfter('#add-course');
}