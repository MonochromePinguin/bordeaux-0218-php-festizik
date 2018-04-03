<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 * PHP version 7
 */

namespace Controller;

use Model\Admin;
use Model\AdminManager;

/**
 * Class AdminController
 *
 */
class AdminController extends AbstractController
{
    /**
    * display items – no editing for now
    */
    public function list()
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
            'Admin/list.html.twig',
            [
                'artists' => $entries
            ]
        );
    }


}
