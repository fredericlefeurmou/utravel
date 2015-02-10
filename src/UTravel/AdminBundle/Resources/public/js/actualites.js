/*
 *      Script de fonctionnalités pour la partie Gestion d'actualités
 */


$(document).on("click", " #edit_actu ", function(){
    var is_error = false;
    var names = ['illustration', 'titre', 'description'];

    for (var name in names)
    {
        if ($(" input[name='" + name + "'] ").val() == '')
        {
            if (name != 'illustration' || document.getElementById('id_actu').value <= 0)
            { 
                $(" #" + name + "_div ").attr('class', 'form-group has-error'); 
                is_error = true;
            }
        }
    }

    if (is_error)
    {
        alert('Des champs ne sont pas remplis. Vérifiez les');
    }
    else
    {
        $(" #form_actu ").submit();
    }
});