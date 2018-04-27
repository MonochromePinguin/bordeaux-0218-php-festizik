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
        return $this->twig->render('Admin/adminBenevol.html.twig');

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
                $data['picture'] = '/assets/DBimages/'.$_POST['picture'];
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

        public function adminInfos()
    {
        $infosManager = new ArticleManager();
        $infos = $infosManager->selectAll();

        $title = $infos[1]->getTitle();
        $content = $infos[1]->getContent();

        if ($_POST) {
    $data = ['title' => $_POST['title'],
        'content' => $_POST['content'],
        'picture' => '/assets/DBimages/'.$_POST['picture']];
    $infosManager->update(7, $data);
            }


        return $this->twig->render('Admin/adminInfos.html.twig', ['title'=>$title, 'content'=>$content]);
    }
}

