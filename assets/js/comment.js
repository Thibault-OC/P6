//attend que le DOM soit chargé
$( document ).ready(function(){

    const loadMoreComment = $("#loadMoreCom");
    const loadMoreComDiv = $("#loadMoreComDiv");
    const animGifajax = $("#ajax-loading-com");

    const dataComment = $("#dataComment");// recupere les informations pour le javascript
    const route = dataComment.data("route");// route pour l'appel ajax

    let nbCom = dataComment.data("total");// total de comments enregistré en Bdd
    let nbTotalComment;// initialisation du total de comments

    //Pour afficher le load more ap partir de 6 commentaires
    if(nbCom < 6){
        loadMoreComDiv.remove();
    }

    animGifajax.hide(); // On masque le gif au chargement de la page

    loadMoreComment.click(function(e){
        animGifajax.show();// On montre le gif pour le chargement des tricks
        let nbComment = $(".comment").length; //Nombre de comment avant l'appel ajax

        e.preventDefault();
        $.ajax({
            url: route,
            type: "POST",
            data: { "nbComment": nbComment }

        }).done(function(data){

            //recupere l'emplacement
            const placeToInsert = $(".comments ");

            //insertion avec boucle
            for(let i=0; i<data.length;i++){

                let divCom = document.createElement("div");
                divCom.className = "comment";

                let divUser = document.createElement("div");
                divUser.className = "comment-avatar";
                divUser.innerHTML = "<img src='https://gravatar.com/avatar/412c0b0ec99008245d902e6ed0b264ee?s=80'>";


                let divComContent = document.createElement("div");
                divComContent.className = "comment-box";

                let comText = document.createElement("div");
                comText.className = "comment-text";
                comText.textContent = data[i].content;

                let divAuthor = document.createElement("div");
                divAuthor.className = "comment-footer";
                divAuthor.innerHTML = "<span class='comment-author'>" + data[i].user + "</span>" + "<span class='comment-date'>" + data[i].createdAt + "</span>";


                divCom.append(divUser);

                divComContent.append(comText);
                divComContent.append(divAuthor);

                divCom.append(divComContent);
                placeToInsert.append(divCom);

                //Nombre total de comments affiché
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