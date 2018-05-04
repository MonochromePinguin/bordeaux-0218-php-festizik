//I do love IIFE
( function() {
    $(document).ready( function() {

//TODO: INSERT CODE FOR THE ONE-BLOCK-ONLY VIEW HERE:
// « these two callbacks are used to show / hide the different view blocks »

        //the image associated to an artist change depending on the
        // <select> element content;
        // URLimgs[] is defined in a script into the <head> element
        $('.selectArtist').change( function(ev) {
                artiste = $(this).val();
                img = $(this).closest('form').find('img.downsized');

                img.attr('src', URLimgs[artiste] );
                img.attr('alt', 'image pour ' + artiste + ' absente');
        } );


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
