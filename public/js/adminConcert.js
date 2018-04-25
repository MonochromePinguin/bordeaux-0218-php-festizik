//I do love IIFE
( function() {
    $(document).ready( function() {

        //the image associated to an artist change depending on the
        // <select> element content;
        // imgUrl[] is defined in a script into the <head> element
        $('.selectArtist').change( function(ev) {
                $(this).parent().parent().
                     find('img.downsized').
                     attr('src', imgUrl[$(this).val()] );
        } );
//TODO : ↑ MAKE IT WORK; grey out «apply» button when any data is not correct;
//make the select elements into "create entry" start with "select something";
//reduce the TODO in adminConcert.html.twig.

        // the button for aborting the deletion is focused on modal open
        $('#confirmDelete').on('shown.bs.modal', function () {
            $('#abortDeleteBtn').trigger('focus');
        })

        //bind the "supprimer ..." buttons to this funct° that show the
        // confirmation modal
        $('.askForDeleteBtn').click( function(ev) {

            //Make the form show infos about the concert to delete
            $('#concertInfos').text(this.dataset.infosConcert);

//TODO: use AJAX!

            //give the button the value to send throught the POST
            $('#idConcertToDelete').val(this.dataset.idToDelete);
        } );

    } );

} )();
