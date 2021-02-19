//attend que le DOM soit chargé
$( document ).ready(function(){


    const loadMoreComment = $("#loadMoreCom");
    const loadMoreComDiv = $("#loadMoreComDiv");
    const animGifajax = $("#ajax-loading-com");

    const dataComment = $("#dataComment");// recupere les informations pour le javascript
    const route = dataComment.data("route");// route pour l'appel ajax



    let nbCom = dataComment.data("total");// total de comments enregistré en Bdd
    let nbTotalComment;// initialisation du total de comments

    //Pour afficher le load more ap partir de 10 ticks
    if(nbCom < 10){
        loadMoreComDiv.remove();
    }

    animGifajax.hide(); // On masque le gif au chargement de la page



    loadMoreComment.click(function(e){

        animGifajax.show();// On montre le gif pour le chargement des tricks
        let nbComment = $(".col-md-55 ").length; //Nombre de ticks avant l'appel ajax

        e.preventDefault();
        $.ajax({
            url: route,
            type: "POST",
            data: { "nbTricks": nbComment }

        }).done(function(data){

            //recupere l'emplacement
            const placeToInsert = $(".container-tricks .row");





            //insertion avec boucle
            for(let i=0; i<data.length;i++){

                let divCol = document.createElement("div");
                divCol.className = "col-md-55 mb-5 col-lg-4 col-md-4 col-sm-6 card_"+data[i].id+"";

                let divCard = document.createElement("div");
                divCard.className = "card";

                let divHref = document.createElement("a");
                divHref.setAttribute("href", "/P6/public/index.php/"+data[i].slug+"");

                let divBody = document.createElement("div");
                divBody.className = "card-body";

                let divTitle = document.createElement("h5");
                divTitle.className = "card-title";
                divTitle.textContent = data[i].name;

                let divImage = document.createElement("img");
                divImage.className = "card-img-top";
                divImage.setAttribute("src",  "/P6/public/uploads/images/"+data[i].image+"");


             //si l'utilisateur est connecté
                if ( data[i].user !== null){


                    let divEdit = document.createElement("div");
                    divEdit.className = "footer_card";

                    let divBtn = document.createElement("a");
                    divBtn.className = "edit_tricks btn";
                    divBtn.setAttribute("href", "http://p6.sxnt8135.odns.fr/P6/public/index.php/"+data[i].slug+"/edit/");

                    let divPencil = document.createElement("i");
                    divPencil.className = "fas fa-pencil-alt";

                    let divBtnSupr = document.createElement("button");
                    divBtnSupr.setAttribute("type", "button");
                    divBtnSupr.setAttribute("onclick", "confirmationSuppression("+data[i].id+")");

                    let divIconSupr = document.createElement("i");
                    divIconSupr.className = "fas fa-trash-alt";

                    divBtn.append(divPencil);

                    divEdit.append(divBtn);

                    divBtnSupr.append(divIconSupr);

                    divEdit.append(divBtnSupr);

                    divCard.append(divEdit);
                }


                divBody.append(divTitle);

                divHref.append(divImage);

                divHref.append(divBody);

                divCard.append(divHref);

                divCol.append(divCard);

                placeToInsert.append(divCol);

                //Nombre total de ticks affiché
                nbTotalComment = nbComment + data.length;

                //****Verifie le total pour supprimer le bouton load more
                if (nbTotalComment === nbCom){
                    loadMoreComDiv.remove();
                }
            }
            animGifajax.hide(); // On masque le gif
        });

    });

});
