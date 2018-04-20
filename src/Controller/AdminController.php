<?php
/**
 * Created by PhpStorm.
 * User: laiah
 * Date: 04/04/18
 * Time: 10:52
 */

namespace Controller;

use Model\AdminManager;
use Model\ConcertManager;

/**
 *  Class AdminController
 */
class AdminController extends AbstractController
{
    /*
     * Display login page
     * @return string
     */
    public function login()
    {
        $errors = [];
        if ($_POST) {

            $username = $_POST['username'];
            $password = $_POST['password'];

            try {
                $login = new AdminManager($username, $password);
                if ($login->isLoginCorrect($username, $password)) {
                    $_SESSION['username'] = $username;
                } else {
                    $errors[] = 'Le nom d\'utilisateur et/ou le mot de passe est incorrect.';
                }
            }
            catch (\Exception $e)
            {
                echo $e->getMessage();
            }
        }
        if (!isset($_SESSION['username'])) {
            return $this->twig->render('Admin/login.html.twig', ['errors' => $errors]);
        } else {
            header('Location: /admin');
        }
    }

    public function admin()
    {
        return $this->twig->render('Admin/logged.html.twig');
    }

    public function logout()
    {
        session_unset();
        session_destroy();

        header('Location: /login');
        exit();
    }


    public function concerts()
    {
 //TODO: ADD SESSION TIMING-OUT AND REFRESHING
        session_start();
        if ( empty($_SESSION['username']))
            return $this->twig->render('Admin/login.html.twig',
                    ['errors' => [ 'La page d\'administration des concerts n\'est pas accessible sans identification'] ] );

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
            $this->storeMsg('TODO : Cette page n\'est pas encore fonctionnelle avec la méthode POST');

        return $this->twig->render( 'Admin/concerts.html.twig',
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

