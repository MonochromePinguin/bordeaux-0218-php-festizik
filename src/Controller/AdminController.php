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
use Model\DayManager;
use Model\SceneManager;
use Model\ArtistManager;

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
//TODO : won't it be better to start the session below after authentication?
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
            } catch (\Exception $e) {
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
        session_start();
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
        if (empty($_SESSION['username'])) {
            return $this->twig->render(
                'Admin/login.html.twig',
                ['errors' => [ 'La page d\'administration des concerts n\'est pas accessible sans identification'] ]
            );
        }

        $concertManager = new ConcertManager($this->errorStore);
        $concerts = $concertManager->selectAll();

        $artistManager = new ArtistManager($this->errorStore);
        $artists = $artistManager->selectAll('name');

        $sceneManager = new SceneManager($this->errorStore);
        $scenes = $sceneManager->selectAll('name');

        $dayManager = new DayManager($this->errorStore);
        $days = $dayManager->selectAll('date');


        $sortBy = null;

        #allow to sort data out of the model, so we save an SQL request
        static $props = null;

        if (null === $props) {
            $props = $concertManager::getAvailableSortCriterias();
        }

        if (0 !== count($_GET)) {
            ## the goal of a GET method is to sort the available datas
            # into the controller, thus saving some SQL different requests
            if (isset($_GET['sortBy'])) {
                $sortBy = $_GET['sortBy'];

                if (! $concertManager->sortArray($concerts, $sortBy)) {
                    $this->storeMsg(
                        'Le paramètre de tri «' . $sortBy
                        . '» n\'est pas valide'
                    );
                }
            } else {
                $this->storeMsg('Cette page n\'est pas prévue pour être utilisée avec ces paramètres de requête');
            };
        }

        ## STORING DATA ##
        #
        if (0 !== count($_POST)) {
            #list of allowed actions and associated functions
            $actionKeys = [ 'addOneNewConcert', 'deleteOneConcert', 'modifyOneConcert' ];
            $actionFunctions = [ 'addOneConcert', 'deleteOneConcert', 'modifyOneConcert' ];

//TODO : Should validate all parameters before doing anything
#and use $this->storeMsg('Cette page n\'accepte pas l\'action «' . $key . '» par la méthode POST');
            $l = count($actionFunctions);
            foreach ($_POST as $key => $value) {
                for ($i = 0; $i < $l; ++$i) {

                    if ( $key == $actionKeys[$i] ) {
                        #So ... ¿ We cannot use directly $this->$actionFunctions[$i] ?
                        $func = $actionFunctions[$i];
                        $this->$func($concertManager, $artists, $scenes, $days, $_POST);
                    }
                }
            }
        }


        #these two lists are parameters for twig
        $artistNameList = [];
        foreach ($artists as $artist) {
            $artistNameList[] = $artist->getName();
        }

        $sceneNameList = [];
        foreach ( $scenes as $scene) {
            $sceneNameList[] = $scene->getName();
        }

        try {
            return $this->twig->render(
                'Admin/concerts.html.twig',
                [
                    'sortableProperties' => $props,
                    'concerts' => $concerts,
                    #these two are used by the template to generate options in select elements
                    'sceneNames' => $sceneNameList,
                    'artistNames' => $artistNameList,

                    'actualSort' => $sortBy,        #sort criteria actually used, or null if none specified
                    'errorList' => $this->errorStore ?
                        $this->errorStore->formatAllMsg() : null
                ]
            );
        } catch (\Exception $e) {
            return generateEmergencyPage('Erreur de génération de la page', [ $e->getMessage() ]);
        }
    }

    ## adminConcert Page functions ##
    #


    /**
     * make sanity checks and then records the new concert entry
     * @param array $values all needed fields for a new row should be in this
     *              associative array
     */
    private function addOneConcert( ConcertManager $concertManager,
                                    array $artists,
                                    array $scenes,
                                    array $days,
                                    array $values)
    {
        #the values looked up by $manager->insert() are:
        # id_day, hour, id_scene, id_artist, cancelled
        # and the one found into $_POST[] are:
        # DateLocale, hour, scene, artist, cancelled
        $keyList = [ 'DateLocale', 'hour', 'scene', 'artist', 'cancelledConcert'];
        foreach ( $keyList as $key) {
            if (empty($values[$key])) {
                $this->storeMsg("Propriété «{$key}» introuvable dans la requête. Enregistrement abandonné.");
                return;
            }
        }

        #only these two are directly usable
        $sentValues = [
            'hour' => $values['hour'],
            'cancelled' => $values['cancelledConcert']
        ];

        #existance checks
        if (
            !$this->checkValid( $values['artist'], $artists, 'getName',
                         $sentValues['id_artist'], 'Artiste' )
            || !$this->checkValid( $values['scene'], $scenes, 'getName',
                            $sentValues['id_scene'], 'Nom de Scène')
            || !$this->checkValid( $values['DateLocale'], $days, 'getDateAsRaw',
                            $sentValues['id_day'], 'Jour de concert')
        ) {
            return;
        }

        try {
            $concertManager->insert($sentValues);
        } catch (\Exception $e) {
            $this->storeMsg( 'Impossible d\'enregistrer la nouvelle entrée : <br>'
                                . $e->getMessage() );
        }
    }

    /**
     * Basic existance check for differents objects into our lists.
     * We test if an object into $into has its ->getName() returns $lookedFor,
     * and returns an appropriate boolean ;
     * we eventually store an error message into $this->errorStore
     * @param string $lookedFor
     * @param array $into   array of examinated objects
     * @param string $getterName    name of the getter to use to compare data
     * @param &$toVar       variable where the result should be set
     * @param string $errName      name of the object for error message
     * @return bool
     */
    private function checkValid(string $lookedFor, array $into,
                                string $getterName, &$toKey,
                                string $errName): bool
    {
        $found = false;
        foreach ($into as $item) {
$this->storeMsg("cmp {$lookedFor}, {$item->$getterName()}<br>");
            if ($lookedFor == $item->$getterName()) {
                $found = true;
                $sentValues[$toKey] = $item->getId();
                break;
            }
        }

        if (!$found) {
            $this->storeMsg(
                "{$errName} Inconnu «{$lookedFor}» passé en paramètre. Pas d'enregistrement."
            );
            return false;
        }

        return true;
    }

    private function deleteOneConcert(int $id)
    {
        $this->storeMsg(__FUNCTION__);
    }

    private function modifyOneConcert(int $id)
    {
        $this->storeMsg(__FUNCTION__);
    }
}
