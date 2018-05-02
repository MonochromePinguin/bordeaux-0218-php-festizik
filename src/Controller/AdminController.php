<?php
/**
 * Created by PhpStorm.
 * User: laiah
 * Date: 04/04/18
 * Time: 10:52
 */

namespace Controller;

use Model\AdminManager;
use Model\ArticleManager;
use Model\AdminInfosManager;
use Model\ArtistManager;
use Model\AdminBenevolManager;
use Model\StyleManager;
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

        if (!isset($_SESSION['username'])  || !empty($errors)) {
            return $this->twig->render('Admin/login.html.twig', ['errors' => $errors]);
        } else {
            header('Location: /admin');
        }
    }

    public function admin()
    {
        if (!isset($_SESSION['username'])) {
            header('Location: /login');
        } else {
            return $this->twig->render('Admin/logged.html.twig');
        }
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: /login');
    }


    public function adminBenevol()
    {
        $benevolManager = new ArticleManager();
        $benevol = $benevolManager->selectAll();

        $title = $benevol[0]->getTitle();
        $content = $benevol[0]->getContent();
        $picture = $benevol[0]->getPicture();
        return $this->twig->render('Admin/adminBenevol.html.twig', ['question'=>$title, 'beneContent'=>$content, 'picture'=>$picture]);
    }
    public function adminInfos()
    {
        $infosManager = new ArticleManager();
        $infos = $infosManager->selectAll();
        $title = $infos[1]->getTitle();
        $content = $infos[1]->getContent();
        return $this->twig->render('Admin/adminInfos.html.twig', ['title'=>$title, 'content'=>$content]);

    }
    public function benevolContentUpdated()
    {
        $BenevolManager = new AdminBenevolManager();
        $benevol = $BenevolManager->benevolContentUpdate($_POST);
        return $this->twig->render('Admin/logged.html.twig');
    }

    public function adminArtist()
    {
        $artistManager = new ArtistManager();
        $styleManager = new StyleManager();
        $styles = $styleManager->selectStyle();

        if ($_POST) {
            $data = ['name' => $_POST['name'],
                     'about' => $_POST['about'],
                     'id_style' => $_POST['id_style']];
            if (strlen($_POST['picture']) > 0) {
                $data['picture'] = '/assets/DBimages/' . $_POST['picture'];
            }
            if (isset($_GET['artistSelect'])) {
                $artistManager->update($_GET['artistSelect'], $data);
            } else {
                $artistManager->insert();
            }
        }

        $artists = $artistManager->selectNameId();
        if (isset($_GET['artistSelect'])) {
            $artistId = $artistManager->selectOneById($_GET['artistSelect']);
            return $this->twig->render('Admin/adminArtist.html.twig', ['artists' => $artists, 'artistId' => $artistId, 'styles' => $styles]);
        }
        return $this->twig->render('Admin/adminArtist.html.twig', ['artists' => $artists, 'styles' => $styles]);
    }

    public function concerts()
    {
        //TODO: ADD SESSION TIMING-OUT AND REFRESHING
        session_start();
        if (empty($_SESSION['username'])) {
            return $this->twig->render(
                'Admin/login.html.twig',
                ['errors' => ['La page d\'administration des concerts n\'est pas accessible sans identification']]
            );
        }

        $concertManager = new ConcertManager($this->errorStore);
        $concerts = $concertManager->selectAll();

        $sortBy = null;

        #allow to sort data out of the model, so we save an SQL request
        static $props = null;

       /* if (null === $props) {
            $props = $concertManager::getAvailableSortCriterias();
        }*/

        if (0 !== count($_GET)) {
            ## the goal of a GET method is to sort the available datas
            # into the controller, thus saving some SQL different requests
            if (isset($_GET['sortBy'])) {
                $sortBy = $_GET['sortBy'];

                if (!$concertManager->sortArray($concerts, $sortBy)) {
                    $this->storeMsg(
                        'Le paramètre de tri «' . $sortBy
                        . '» n\'est pas valide'
                    );
                }
            } else {
                $this->storeMsg('Cette page n\'est pas prévue pour être utilisée avec ces paramètres de requête');
            };
        }

        if (0 !== count($_POST)) {
            $this->storeMsg('TODO : Cette page n\'est pas encore fonctionnelle avec la méthode POST');
        }

        return $this->twig->render(
            'Admin/concerts.html.twig',
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
