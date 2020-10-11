
jQuery(document).ready(function() {

    var $collectionHolder;

// setup an "add a Video" link
    var $addVideoLink = $('<a href="#" class="add_video_link">Add a tag</a>');
    var $newLinkLi = $('<li></li>').append($addVideoLink);


        // Get the ul that holds the collection of Videos
        $collectionHolder = $('ul.videos');

        // add the "add a Video" anchor and li to the Videos ul
        $collectionHolder.append($newLinkLi);

        // count the current form inputs we have (e.g. 2), use that as the new
        // index when inserting a new item (e.g. 2)
        $collectionHolder.data('index', $collectionHolder.find(':input').length);

        $addVideoLink.on('click', function(e) {
            // prevent the link from creating a "#" on the URL
            e.preventDefault();

            // add a new Video form (see next code block)
            addVideoForm($collectionHolder, $newLinkLi);
        });



    function addVideoForm($collectionHolder, $newLinkLi) {
        // Get the data-prototype explained earlier
        var prototype = $collectionHolder.data('prototype');

        // get the new index
        var index = $collectionHolder.data('index');

        // Replace '$$name$$' in the prototype's HTML to
        // instead be a number based on how many items we have
        var newForm = prototype.replace(/__filename__/g, index);

        // increase the index with one for the next item
        $collectionHolder.data('index', index + 1);

        // Display the form in the page in an li, before the "Add a Video" link li
        var $newFormLi = $('<li></li>').append(newForm);

        // also add a remove button, just for this example
        $newFormLi.append('<a href="#" class="remove-video">x</a>');

        $newLinkLi.before($newFormLi);

        // handle the removal, just for this example
        $('.remove-video').click(function(e) {



            e.preventDefault();

            $(this).parent().remove();

            return false;
        });
    }






    });


jQuery(document).ready(function($){

    $(function() {
        /**
         * Smooth scrolling to page anchor on click
         **/
        $("a[href*='#']:not([href='#'])").click(function() {
            if (
                location.hostname == this.hostname
                && this.pathname.replace(/^\//,"") == location.pathname.replace(/^\//,"")
            ) {
                var anchor = $(this.hash);
                anchor = anchor.length ? anchor : $("[name=" + this.hash.slice(1) +"]");
                if ( anchor.length ) {
                    $("html, body").animate( { scrollTop: anchor.offset().top }, 1500);
                }
            }
        });
    });


    $('.variable-width').slick({
        dots: false,
        infinite: true,
        speed: 300,
        slidesToShow: 1,
        centerMode: true,
        variableWidth: true
    });


    

});