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

    public function testList()
    {
        $concertManager = new ConcertManager($this->errorStore);
        $concerts = $concertManager->selectAll();

        return $this->twig->render(
            'User/testList.html.twig',
            [
                'concerts' => $concerts,
                'errorList' => $this->errorStore ?
                    $this->errorStore->getAllMsg() : null
            ]
        );
    }

    /**
    * TEST : display items – no editing for now
    */
    public function concerts()
    {
        $concertManager = new ConcertManager($this->errorStore);
        $concerts = $concertManager->selectAll();

        return $this->twig->render(
            'User/concerts.html.twig',
            [
                'concerts' => $concerts,
                'errorList' => $this->errorStore ?
                    $this->errorStore->getAllMsg() : null
            ]
        );
    }
}
