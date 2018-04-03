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
    /**
    * TEST : display items – no editing for now
    */
    public function articles()
    {
//**TESTING PURPOSE ONLY**
        $entries = [
            [ 'name' => 'nom1', 'style' => 'aaaaa', 'about' => 'blablabla' ],
            [ 'name' => 'nom2', 'style' => 'bbbbbb',
                    'about' => 'lmejfmslfjsemljelfj' ],
            [ 'name' => 'nom3', 'style' => 'cccccc',
                    'about' => 'smelfkjse selmfk eslmjfemf !!!!' ],
            [ 'name' => 'nom4', 'style' => 'dddddd',
                    'about' => 'My Pen is Rich' ]
        ];

        return $this->twig->render(
            'User/articles.html.twig',
            [
                'artists' => $entries
            ]
        );
    }


    public function concerts()
    {
        $concertManager = new ConcertManager($this->errorStore);
        $concerts = $concertManager->selectAll();

        return $this->twig->render(
            'User/concerts.html.twig',
            [
                'concerts' => $concerts,
                'errorList' => $this->errorStore ?
                    $this->errorStore->getAllMsg() : NULL
            ]
        );
    }

}
