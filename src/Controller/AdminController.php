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
        session_start();

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
            exit;
        }
    }

    public function admin()
    {
        session_start();

        if (!isset($_SESSION['username'])) {
            header('Location: /login');
            exit;
        } else {
            return $this->twig->render('Admin/logged.html.twig');
        }
    }

    public function logout()
    {
        session_start();

        session_unset();
        session_destroy();
        header('Location: /login');
        exit;
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
}

