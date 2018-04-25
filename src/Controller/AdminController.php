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

        $sceneManager = new SceneManager($this->errorStore);
        $scenes = $sceneManager->selectAll('name');

        $artistManager = new ArtistManager($this->errorStore);
        $artists = $artistManager->selectAll('name');

        $dayManager = new DayManager($this->errorStore);
        $days = $dayManager->selectAll('date');

        $concertManager = new ConcertManager($this->errorStore);
        $concerts = $concertManager->selectAll();

        //sort criteria sent in the URL
        $sortBy = null;

        #allow to sort data out of the model, so we save an SQL request
        static $props = null;
        if (null === $props) {
            $props = $concertManager::getAvailableSortCriterias();
        }

        ## STORING DATA ##
        #
        if (0 !== count($_POST)) {
            #list of allowed actions and associated functions ; these one are
            # activated by a classical POST page-reload
            $actionKeys = [ 'addOneNewConcert', 'deleteOneConcert', 'modifyOneConcert' ];
            $actionFunctions = [ 'addOneConcert', 'deleteOneConcert', 'modifyOneConcert' ];

//TODO : Should validate ALL parameters before doing anything,
// instead of validation at the time of action
// – and use $this->storeMsg('Cette page n\'accepte pas l\'action «' . $key . '» par la méthode POST');

            #did at least an action succefully run and changed something?
            # → we need to reload the datas
            $actionFlag = false;

//TODO : for now we are open to multiple actions in a same POST request, so we do that in a loop
            $l = count($actionFunctions);
            foreach ($_POST as $key => $value) {
                for ($i = 0; $i < $l; ++$i) {
                    if ($key == $actionKeys[$i]) {
                        #So ... ¿ We cannot use directly $this->$actionFunctions[$i] ?
                        $func = $actionFunctions[$i];
                        if ($this->$func($concertManager, $artists, $scenes, $days, $_POST)) {
                            $actionFlag = true;
                        }
                    }
                }
            }

//TODO : We need to load datas for validation, ok, but can we do this better
//than by reloading the whole set ?
            if ($actionFlag) {
                $scenes = $sceneManager->selectAll('name');
                #no need to reload the artists, we don't modify them
                $days = $dayManager->selectAll('date');
                $concerts = $concertManager->selectAll();
            }
        }


        ## SORTING DATA ##
        #
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


        #these two lists are parameters for twig
        $artistNameList = [];
        foreach ($artists as $artist) {
            $artistNameList[] = $artist->getName();
        }

        $sceneNameList = [];
        foreach ($scenes as $scene) {
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


//TODO : THROW an exception in case of problem ? Probably betten than returning nothing ...
    /**
     * make sanity checks and if all is OK then records the new concert entry.
     *  in case of error, store a message in the errorStore and return without value ...
     * @param array $values all needed fields for a new row should be in this
     *              associative array
     */
    private function addOneConcert(
        ConcertManager $concertManager,
        array $artists,
        array $scenes,
        array $days,
        array $values
    ) {
        #the values looked up by $manager->insert() are:
            # id_day, hour, id_scene, id_artist, cancelled
        # and the one found into $_POST[] are:
            # DateLocale, hour, scene, artist, cancelled
        $keyList = [ 'DateLocale', 'hour', 'scene', 'artist'];
        # REMEMBER : "cancelled" isn't sent if unchecked. So we don't test for its presence

        $sentValues = [];


        #Test for the presence of needed parameters
        foreach ($keyList as $key) {
            if (empty($values[$key])) {
                $this->storeMsg("Propriété «{$key}» introuvable dans la requête. Enregistrement abandonné.");
                return false;
            }
        }

        #Translate the checkbox to a value usable by SQL
        if (isset($values['cancelledConcert'])) {
            $v = $values['cancelledConcert'];
            if ($v != 'on') {
                $this->storeMsg('Valeur invalide fournie pour la propriété «cancelledConcert». Pas d\'enregistrement');
                return false;
            }
            $sentValues['cancelled'] = '1';
        } else {
            $sentValues['cancelled'] = '0';
        }

        #only this one is directly usable
        $sentValues['hour'] = $values['hour'];

        #Validity checks
//TODO : as an upper ↑ TODO said, should'nt we return a value ?
        if (!$this->checkValid(
            $values['artist'],
            $artists,
            'getName',
            $sentValues['id_artist'],
            'Artiste'
        )
            || !$this->checkValid(
                $values['scene'],
                $scenes,
                'getName',
                $sentValues['id_scene'],
                'Nom de Scène'
            )
            || !$this->checkValid(
                $values['DateLocale'],
                $days,
                'getDateAsRaw',
                $sentValues['id_day'],
                'Jour de concert'
            )
        ) {
            return false;
        }

//TODO : CREATE A DAY ENTRY IN CASE OF NON-EXISTENCE
        try {
            $concertManager->insert($sentValues);
            return true;
        } catch (\Exception $e) {
            $this->storeMsg('Impossible d\'enregistrer la nouvelle entrée : <br>'
                                . $e->getMessage());
            return false;
        }
    }


    /**
     * Basic existence check for differents objects into our lists.
     * We test if one of the objects into $into has its ->getName() returning
     * $lookedFor, and returns an appropriate boolean ;
     * we eventually store an error message into $this->errorStore
     * @param string $lookedFor
     * @param array $into   array of examinated objects
     * @param string $getterName    name of the getter to use to compare data
     * @param &$toVar       variable where the result should be set
     * @param string $errName      name of the object for error message
     * @return bool
     */
    private function checkValid(
        string $lookedFor,
        array $into,
        string $getterName,
        &$toVar,
        string $errName
    ): bool {
        $found = false;
        foreach ($into as $item) {
            if ($lookedFor == $item->$getterName()) {
                $found = true;
                $toVar = $item->getId();
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
        $this->storeMsg(__FUNCTION__ . ' a été appelé !');
    }


    private function modifyOneConcert(int $id)
    {
        $this->storeMsg(__FUNCTION__);
    }
}
