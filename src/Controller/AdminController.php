<?php
/**
 * Created by PhpStorm.
 * User: laiah
 * Date: 04/04/18
 * Time: 10:52
 */

namespace Controller;

use Model\AdminManager;
use Model\AdminBenevolManager;

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
        unset($_SESSION['username']);
        //session_destroy();
        header('Location: /login');

    }

     public function adminBenevol()
    {
        if(!empty($_POST)){
            $benevolManager = new AdminBenevolManager();
            $benevol = $benevolManager->benevolContentUpdate($_POST);
        
        }
        return $this->twig->render('Admin/adminBenevol.html.twig');

    }
}

