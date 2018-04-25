//I do love IIFE
( function() {
    $(document).ready( function() {

        // the button for aborting the deletion is focused on modal open
        $('#confirmDelete').on('shown.bs.modal', function () {
            $('#abortDeleteBtn').trigger('focus');
            console.log("bouton délétion focusé !")
        })

        //bind the "supprimer ..." buttons to this funct° that show the
        // confirmation modal
        $('.askForDeleteBtn').click( function(ev) {

 console.log('Cb appelée !')
            //give the button the value to send throught the POST
            $('#deleterButton').value = this.dataset.idToDelete;
console.log(this);
            //Make the form show infos about the concert to delete
              $('#concertInfos').text(this.dataset.infosConcert);

          //$('#confirmDelete').modal('show');
        } );

    } );

} )();
