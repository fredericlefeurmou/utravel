/*******************************************************************************
 *                                                                             * 
 *    Fichier de méthodes JavaScript et jQuery pour la partie administration   *
 *                                                                             *
 *******************************************************************************/

/*
 * Fonction pour l'onglet du sous-menu actif
 */
window.onload = function()
{
    var id_service = document.getElementById('service').value;
    if (id_service >= 0) {
        element_menu_actif = document.getElementsByClassName('mainnav')[0].children[id_service];
        if (id_service == 1 || id_service == 2 || id_service == 4) {
            element_menu_actif.setAttribute('class', 'dropdown active');
        } else {
            element_menu_actif.setAttribute('class', 'active');
        }
    }
}


/*
 *    Fonction d'affichage (partiel) d'un bloc panel :
 *    consulat, attaché scientifique, lycée français, annexe Ubifrance, annexe de chambre du commerce
 */
$(" body ").on('click', 'button.btn-display', function(){
    corps = $(this).parent().parent().children()[1];
    icone = $(this).children()[0];
    if ($(icone).attr("class") == "glyphicon glyphicon-chevron-up")
    {
        $(corps).css("display", "none");
        $(icone).attr("class", "glyphicon glyphicon-chevron-down");
    }
    else
    {
        $(corps).css("display", "");
        $(icone).attr("class", "glyphicon glyphicon-chevron-up");
    }
});

/*
 *    Fonction de suppression d'un bloc consulat :
 *    Rétablit le bouton de suppression au bloc précédent
 */
$(" body ").on('click', 'button.btn-suppr-consulat', function(){
    bloc = $(this).parent().parent();
    $(bloc).remove();

    var bouton_suppr = '<button type="button" class="btn btn-danger btn-suppr-consulat btn-sm btn-top">' +
        '<span class="fa fa-times"></span>' +
    '</button>';

    dernier_consulat = $(" #ambassade ").find(" .consulat ").last();
    $(dernier_consulat).find(" .panel-title ").before(bouton_suppr);
});

/*
 *    Fonction d'ajout d'un consulat :
 *      - seul le dernier consulat ajouté est supprimable, on procède à cette vérification
 *      - on rajoute un consulat si le nombre max de consulats n'est pas atteint (5)
 */
$(" #new_consulat ").on('click', function(){
    consulats = $(" #ambassade ").find(" .consulat ");
    var i = consulats.length;
    if (i < 5) {
        if (i > 0) {
            entete_dernier_consulat = $(consulats).last().children()[0];
            bouton_suppr = $(entete_dernier_consulat).children()[1];
            $(bouton_suppr).remove();
        }

        var div_consulat = '<div class="panel panel-info consulat">' +
            '<div class="panel-heading" style="height:41px;">' +
                '<button type="button" class="btn btn-primary btn-display btn-sm btn-top">' +
                    '<span class="glyphicon glyphicon-chevron-up"></span>' +
                '</button>' +
                '<button type="button" class="btn btn-danger btn-suppr-consulat btn-sm btn-top">' +
                    '<span class="fa fa-times"></span>' +
                '</button>' +
                '<h3 class="panel-title">Consulat #' + (i+1) + '</h3>' +
            '</div>' +
            '<div class="panel-body" id="body">' +
                '<div class="form-group">' +
                    '<div class="col-sm-6">' +
                        '<label for="consul_' + (i+1) + '[adr]">Adresse du consulat : </label>' +
                        '<input name="consul_' + (i+1) + '[adr]" type="text" class="form-control">' +
                    '</div>' +
                    '<div class="col-sm-6">' +
                        '<label for="consul_' + (i+1) + '[cp]">Code postal : </label>' +
                        '<input name="consul_' + (i+1) + '[cp]" type="text" class="form-control">' +
                    '</div>' +
                '</div>' +
                '<div class="form-group">' +
                    '<div class="col-sm-6">' +
                        '<label for="consul_' + (i+1) + '[ville]">Ville : </label>' +
                        '<input name="consul_' + (i+1) + '[ville]" type="text" class="form-control">' +
                    '</div>' +
                    '<div class="col-sm-6">' +
                        '<label for="consul_' + (i+1) + '[tel]">Téléphone : </label>' +
                        '<input name="consul_' + (i+1) + '[tel]" type="text" class="form-control">' +
                    '</div>' +
                '</div>' +
            '</div>' +
        '</div>';

        $(this).before(div_consulat);
    }
});

/*
 *    Fonction de suppression d'un bloc attaché scientifique :
 *    Rétablit le bouton de suppression au bloc précédent
 */
$(" body ").on('click', 'button.btn-suppr-attache', function(){
    bloc = $(this).parent().parent();
    $(bloc).remove();

    var bouton_suppr = '<button type="button" class="btn btn-danger btn-suppr-attache btn-sm btn-top">' +
        '<span class="fa fa-times"></span>' +
    '</button>';

    dernier_attache = $(" #education ").find(" .attache ").last();
    $(dernier_attache).find(" .panel-title ").before(bouton_suppr);
});

/*
 *    Fonction d'ajout d'un attaché scientifique :
 *      - seul le dernier attaché scientifique ajouté est supprimable, on procède à cette vérification
 *      - on rajoute un attaché scientifique si le nombre max d'attachés scientifiques n'est pas atteint (5)
 */
$(" #new_attache ").on('click', function(){
    attaches = $(" #education ").find(" .attache ");
    var i = attaches.length;
    if (i < 5) {
        if (i > 0) {
            entete_dernier_attache = $(attaches).last().children()[0];
            bouton_suppr = $(entete_dernier_attache).children()[1];
            $(bouton_suppr).remove();
        }

        var div_attache = '<div class="panel panel-info attache">' +
            '<div class="panel-heading" style="height:41px;">' +
                '<button type="button" class="btn btn-primary btn-display btn-sm btn-top">' +
                    '<span class="glyphicon glyphicon-chevron-up"></span>' +
                '</button>' +
                '<button type="button" class="btn btn-danger btn-suppr-attache btn-sm btn-top">' +
                    '<span class="fa fa-times"></span>' +
                '</button>' +
                '<h3 class="panel-title">Attaché scientifique #' + (i+1) + '</h3>' +
            '</div>' +
            '<div class="panel-body">' +
                '<div class="form-group">' +
                    '<div class="col-sm-6">' +
                        '<label for="attache_' + (i+1) + '[nom]">Prénom / Nom : </label>' +
                        '<input name="attache_' + (i+1) + '[nom]" type="text" class="form-control">' +
                    '</div>' +
                    '<div class="col-sm-6">' +
                        '<label for="attache_' + (i+1) + '[ville]">Ville : </label>' +
                        '<input name="attache_' + (i+1) + '[ville]" type="text" class="form-control">' +
                    '</div>' +
                '</div>' +
                '<div class="form-group">' +
                    '<div class="col-sm-6">' +
                        '<label for="attache_' + (i+1) + '[email]">Email de contact : </label>' +
                        '<input name="attache_' + (i+1) + '[email]" type="text" class="form-control">' +
                    '</div>' +
                '</div>' +
            '</div>' +
        '</div>';

        $(this).before(div_attache);
    }
});

/*
 *    Fonction de suppression d'un bloc lycée français :
 *    Rétablit le bouton de suppression au bloc précédent
 */
$(" body ").on('click', 'button.btn-suppr-lycee', function(){
    bloc = $(this).parent().parent();
    $(bloc).remove();

    var bouton_suppr = '<button type="button" class="btn btn-danger btn-suppr-lycee btn-sm btn-top">' +
        '<span class="fa fa-times"></span>' +
    '</button>'

    dernier_lycee = $(" #education ").find(" .lycee ").last();
    $(dernier_lycee).find(" .panel-title ").before(bouton_suppr);
});

/*
 *    Fonction d'ajout d'un lycée français :
 *      - seul le dernier lycée français ajouté est supprimable, on procède à cette vérification
 *      - on rajoute un lycée français si le nombre max de lycées français n'est pas atteint (5)
 */
$(" #new_lycee ").on('click', function(){
    lycees = $(" #education ").find(" .lycee ");
    var i = lycees.length;
    if (i < 5) {
        if (i > 0) {
            entete_dernier_lycee = $(lycees).last().children()[0];
            bouton_suppr = $(entete_dernier_lycee).children()[1];
            $(bouton_suppr).remove();
        }

        var div_lycee = '<div class="panel panel-info lycee">' +
            '<div class="panel-heading" style="height:41px;">' +
                '<button type="button" class="btn btn-primary btn-display btn-sm btn-top">' +
                    '<span class="glyphicon glyphicon-chevron-up"></span>' +
                '</button>' +
                '<button type="button" class="btn btn-danger btn-suppr-lycee btn-sm btn-top">' +
                    '<span class="fa fa-times"></span>' +
                '</button>' +
                '<h3 class="panel-title">Lycée français #' + (i+1) + '</h3>' +
            '</div>' +
            '<div class="panel-body">' +
                '<div class="form-group">' +
                    '<div class="col-sm-6">' +
                        '<label for="lycee_' + (i+1) + '[nom]">Nom du lycée : </label>' +
                        '<input name="lycee_' + (i+1) + '[nom]" type="text" class="form-control">' +
                    '</div>' +
                    '<div class="col-sm-6">' +
                        '<label for="lycee_' + (i+1) + '[proviseur]">Prénom / Nom du proviseur : </label>' +
                        '<input name="lycee_' + (i+1) + '[proviseur]" type="text" class="form-control">' +
                    '</div>' +
                '</div>' +
                '<div class="form-group">' +
                    '<div class="col-sm-6">' +
                        '<label for="lycee_' + (i+1) + '[ville]">Ville : </label>' +
                        '<input name="lycee_' + (i+1) + '[ville]" type="text" class="form-control">' +
                    '</div>' +
                    '<div class="col-sm-6">' +
                        '<label for="lycee_' + (i+1) + '[adr]">Adresse : </label>' +
                        '<input name="lycee_' + (i+1) + '[adr]" type="text" class="form-control">' +
                    '</div>' +
                '</div>' +
                '<div class="form-group">' +
                    '<div class="col-sm-6">' +
                        '<label for="lycee_' + (i+1) + '[cp]">Code postal : </label>' +
                        '<input name="lycee_' + (i+1) + '[cp]" type="text" class="form-control">' +
                    '</div>' +
                    '<div class="col-sm-6">' +
                        '<label for="lycee_' + (i+1) + '[tel]">Téléphone : </label>' +
                        '<input name="lycee_' + (i+1) + '[tel]" type="text" class="form-control">' +
                    '</div>' +
                '</div>' +
                '<div class="form-group">' +
                    '<div class="col-sm-6">' +
                        '<label for="lycee_' + (i+1) + '[email]">Email de contact : </label>' +
                        '<input name="lycee_' + (i+1) + '[email]" type="text" class="form-control">' +
                    '</div>' +
                '</div>' +
            '</div>' +
        '</div>';

        $(this).before(div_lycee);
    }
});

/*
 *    Fonction de suppression d'un bloc annexe Ubifrance :
 *    Rétablit le bouton de suppression au bloc précédent
 */
$(" body ").on('click', 'button.btn-suppr-ubifrance', function(){
    bloc = $(this).parent().parent();
    $(bloc).remove();

    var bouton_suppr = '<button type="button" class="btn btn-danger btn-suppr-ubifrance btn-sm btn-top">' +
        '<span class="fa fa-times"></span>' +
    '</button>'

    dernier_ubifrance = $(" #professionnel ").find(" .ubifrance ").last();
    $(dernier_ubifrance).find(" .panel-title ").before(bouton_suppr);
});

/*
 *    Fonction d'ajout d'une annexe Ubifrance :
 *      - seule la dernière annexe Ubifrance ajoutée est supprimable, on procède à cette vérification
 *      - on rajoute une annexe Ubifrance si le nombre max d'annexes Ubifrance n'est pas atteint (5)
 */
$(" #new_ubifrance ").on('click', function(){
    ubifrances = $(" #professionnel ").find(" .ubifrance ");
    var i = ubifrances.length;
    if (i < 5) {
        if (i > 0) {
            entete_dernier_ubifrance = $(ubifrances).last().children()[0];
            bouton_suppr = $(entete_dernier_ubifrance).children()[1];
            $(bouton_suppr).remove();
        }

        var div_ubifrance = '<div class="panel panel-info ubifrance">' +
            '<div class="panel-heading" style="height:41px;">' +
                '<button type="button" class="btn btn-primary btn-display btn-sm btn-top">' +
                    '<span class="glyphicon glyphicon-chevron-up"></span>' +
                '</button>' +
                '<button type="button" class="btn btn-danger btn-suppr-ubifrance btn-sm btn-top">' +
                    '<span class="fa fa-times"></span>' +
                '</button>' +
                '<h3 class="panel-title">Annexe Ubifrance #' + (i+1) + '</h3>' +
            '</div>' +
            '<div class="panel-body">' +
                '<div class="form-group">' +
                    '<div class="col-sm-6">' +
                        '<label for="ubi_' + (i+1) + '[ville]">Ville : </label>' +
                        '<input name="ubi_' + (i+1) + '[ville]" type="text" class="form-control">' +
                    '</div>' +
                    '<div class="col-sm-6">' +
                        '<label for="ubi_' + (i+1) + '[cp]">Code postal : </label>' +
                        '<input name="ubi_' + (i+1) + '[cp]" type="text" class="form-control">' +
                    '</div>' +
                '</div>' +
                '<div class="form-group">' +
                    '<div class="col-sm-6">' +
                        '<label for="ubi_' + (i+1) + '[adr]">Adresse : </label>' +
                        '<input name="ubi_' + (i+1) + '[adr]" type="text" class="form-control">' +
                    '</div>' +
                    '<div class="col-sm-6">' +
                        '<label for="ubi_' + (i+1) + '[tel]">Téléphone : </label>' +
                        '<input name="ubi_' + (i+1) + '[tel]" type="text" class="form-control">' +
                    '</div>' +
                '</div>' +
                '<div class="form-group">' +
                    '<div class="col-sm-6">' +
                        '<label for="ubi_' + (i+1) + '[email]">Email de contact : </label>' +
                        '<input name="ubi_' + (i+1) + '[email]" type="text" class="form-control">' +
                    '</div>' +
                '</div>' +
            '</div>' +
        '</div>';

        $(this).before(div_ubifrance);
    }
});

/*
 *    Fonction de suppression d'un bloc annexe de chambre du commerce :
 *    Rétablit le bouton de suppression au bloc précédent
 */
$(" body ").on('click', 'button.btn-suppr-commerce', function(){
    bloc = $(this).parent().parent();
    $(bloc).remove();

    var bouton_suppr = '<button type="button" class="btn btn-danger btn-suppr-commerce btn-sm btn-top">' +
        '<span class="fa fa-times"></span>' +
    '</button>'

    dernier_commerce = $(" #professionnel ").find(" .commerce ").last();
    $(dernier_commerce).find(" .panel-title ").before(bouton_suppr);
});

/*
 *    Fonction d'ajout d'un attaché annexe de chambre du commerce :
 *      - seule la dernière annexe ajoutée est supprimable, on procède à cette vérification
 *      - on rajoute une annexe de chambre du commerce si le nombre max d'annexes n'est pas atteint (5)
 */
$(" #new_commerce ").on('click', function(){
    commerces = $(" #professionnel ").find(" .commerce ");
    var i = commerces.length;
    if (i < 5) {
        if (i > 0) {
            entete_dernier_commerce = $(commerces).last().children()[0];
            bouton_suppr = $(entete_dernier_commerce).children()[1];
            $(bouton_suppr).remove();
        }

        var div_commerce = '<div class="panel panel-info commerce">' +
            '<div class="panel-heading" style="height:41px;">' +
                '<button type="button" class="btn btn-primary btn-display btn-sm btn-top">' +
                    '<span class="glyphicon glyphicon-chevron-up"></span>' +
                '</button>' +
                '<button type="button" class="btn btn-danger btn-suppr-commerce btn-sm btn-top">' +
                    '<span class="fa fa-times"></span>' +
                '</button>' +
                '<h3 class="panel-title">Annexe Chambre de commerce #' + (i+1) + '</h3>' +
            '</div>' +
            '<div class="panel-body" id="body">' +
                '<div class="form-group">' +
                    '<div class="col-sm-6">' +
                        '<label for="chcom_ann_' + (i+1) + '[ville]">Ville : </label>' +
                        '<input name="chcom_ann_' + (i+1) + '[ville]" type="text" class="form-control">' +
                    '</div>' +
                    '<div class="col-sm-6">' +
                        '<label for="chcom_ann_' + (i+1) + '[cp]">Code postal : </label>' +
                        '<input name="chcom_ann_' + (i+1) + '[cp]" type="text" class="form-control">' +
                    '</div>' +
                '</div>' +
                '<div class="form-group">' +
                    '<div class="col-sm-6">' +
                        '<label for="chcom_ann_' + (i+1) + '[adr]">Adresse : </label>' +
                        '<input name="chcom_ann_' + (i+1) + '[adr]" type="text" class="form-control">' +
                    '</div>' +
                    '<div class="col-sm-6">' +
                        '<label for="chcom_ann_' + (i+1) + '[tel]">Téléphone : </label>' +
                        '<input name="chcom_ann_' + (i+1) + '[tel]" type="text" class="form-control">' +
                    '</div>' +
                '</div>' +
                '<div class="form-group">' +
                    '<div class="col-sm-6">' +
                        '<label for="chcom_ann_' + (i+1) + '[email]">Email de contact : </label>' +
                        '<input name="chcom_ann_' + (i+1) + '[email]" type="text" class="form-control">' +
                    '</div>' +
                '</div>' +
            '</div>' +
        '</div>';

        $(this).before(div_commerce);
    }
});
