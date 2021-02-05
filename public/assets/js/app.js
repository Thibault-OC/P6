
jQuery(document).ready(function() {

    var $collectionHolder;

// setup an "add a Video" link
    var $addVideoLink = $('<a href="#" class="add_video_link">Add Video</a>');
    var $newLinkLi = $('<li></li>').append($addVideoLink);


        // Get the ul that holds the collection of Videos
        $collectionHolder = $("ul.videos");

        // add the "add a Video" anchor and li to the Videos ul
        $collectionHolder.append($newLinkLi);

        // count the current form inputs we have (e.g. 2), use that as the new
        // index when inserting a new item (e.g. 2)
        var videos = $("#tricks_videos input").length;

        $collectionHolder.data("index", videos);

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
        var index = $collectionHolder.data("index");

        // Replace '$$name$$' in the prototype's HTML to
        // instead be a number based on how many items we have
        var newForm = prototype.replace(/__filename__/g, index);

        // increase the index with one for the next item
        $collectionHolder.data("index", index + 1);

        // Display the form in the page in an li, before the "Add a Video" link li
        var $newFormLi = $("<li></li>").append(newForm);

        // also add a remove button, just for this example
        $newFormLi.append('<a href="#" class="remove-video">x</a>');

        $newLinkLi.before($newFormLi);

        // handle the removal, just for this example
        $(".remove-video"").click(function(e) {



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
        speed: 300,
        slidesToShow: 1,
        centerMode: true,
        variableWidth: true,
        prevArrow: $(".prev"),
        nextArrow: $(".next"),
    });

    $('.variable-width-edit').slick({
        dots: false,
        infinite: false,
        speed: 300,
        slidesToShow: 3,
        slidesToScroll: 1,
        prevArrow: $('.prev'),
        nextArrow: $('.next'),
    });




    $(document).ready(function () {

        function readURL(input) {

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }


        $("#imgInp").change(function(){
            readURL(this);

        });
    });


});
