/*
 *   Pour le service carrousel
 */

$("document").ready(function(){
    refresh_rep();
});

function refresh_rep(){
    $.ajax({
        type : 'GET',
        url: './carrousel/refresh'
    }).done(function(data){
        $(" #liste_images ").html(data);
    });
};

$(" #video ").click(function(){
    $(" #option_1 ").css("display", "");
    $(" #option_2 ").css("display", "none");
});
$(" #carrousel ").click(function(){
    $(" #option_1 ").css("display", "none");
    $(" #option_2 ").css("display", "");
});

$("body").on('click', 'button.suppr-img', function(){
    var filename = $(this).attr('name');
    alert(filename);
    $.ajax({
        type : 'POST',
        url: './carrousel/delete',
        data: {
            file: filename
        }
    }).done(function(data){
        $(" #liste_images ").html(data);
    });
});