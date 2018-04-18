<?php
namespace Controller;

use Model\Concert;
use Model\ConcertManager;

/**
 * Class UserController
 *
 */
class UserController extends AbstractController
{
    public function concerts()
    {
        $concertManager = new ConcertManager($this->errorStore);
        $concerts = $concertManager->selectAll();

        $sortBy = null;

        #allow to sort data out of the model, so we save an SQL request
        static $props = null;


        if ( null === $props )
            $props = $concertManager::getAvailableSortCriterias();

        if ( 0 !== count($_GET) ) {

            ## the goal of a GET method is to sort the available datas
            # into the controller, thus saving some SQL different requests
            if ( isset( $_GET['sortBy'] ) )
            {
               $sortBy = $_GET['sortBy'];

               if ( ! $concertManager->sortArray($concerts, $sortBy) )
                    $this->storeMsg(
                        'Le paramètre de tri «' . $sortBy
                        . '» n\'est pas valide' );
            }
            else
                $this->storeMsg('Cette page n\'est pas prévue pour être utilisée avec ces paramètres de requête');;
        }


        if ( 0 !== count($_POST) )
            $this->storeMsg('Cette page n\'est pas prévue pour être utilisée avec la méthode POST');


        return $this->twig->render(
            'User/concerts.html.twig',
            [
                'sortableProperties' => $props,
                'concerts' => $concerts,
                'actualSort' => $sortBy,        #sort criteria actually used, or null if none specified
                'errorList' => $this->errorStore ?
                        $this->errorStore->formatAllMsg() : null
            ]

        );
    }
}
