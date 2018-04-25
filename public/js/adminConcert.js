//I do love IIFE
( function() {
    $(document).ready( function() {

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
