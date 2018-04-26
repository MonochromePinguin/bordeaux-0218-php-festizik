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
                        if ($this->$func($concertManager,
                                         $dayManager,
                                         $concerts,
                                         $artists,
                                         $scenes,
                                         $days,
                                         $_POST)) {
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


        #these 3 lists are parameters for twig
        $artistNameList = [];
        $artistImgList = [];
        $sceneNameList = [];
        foreach ($artists as $artist) {
            $name = $artist->getName();
            $artistNameList[] = $name;
            $artistImgList[$name] = $artist->getImageURL();
        }
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
                    'URLimgs' => json_encode($artistImgList,JSON_UNESCAPED_SLASHES ),

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
    # THESE FUNCTIONS NEED TO FOLLOW THE SAME PROTOTYPE.
    # See the "if (0 !== count($_POST))" block in the above function.
    #


//TODO : THROW an exception in case of problem ?
    /**
     * Add a new concert entry after some sanity checks.
     *  in case of error, store a message in the errorStore and return without value ...
     * @param ConcertManager $concertManager
     * @param DayManager $dayManager unused
     * @param array $concerts unused
     * @param array $artists
     * @param array $scenes
     * @param array $days
     * @param array $values all needed fields for a new row should be in this
     *                      associative array.
     *                      AWAITED KEYS:
     *                          DateLocale, hour, scene, artist, cancelled
     * @return bool
     */
    private function addOneConcert(
        ConcertManager $concertManager,
        DayManager $dayManager,
        array $concerts,
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

        $usedValues = [];


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
            $usedValues['cancelled'] = '1';
        } else {
            $usedValues['cancelled'] = '0';
        }

        #only this one is directly usable
        $usedValues['hour'] = $values['hour'];

        #Validity checks
        if (!$this->checkValid(
            $values['artist'],
            $artists,
            'getName',
            $usedValues['id_artist'],
            'Artiste'
            )
            || !$this->checkValid(
                $values['scene'],
                $scenes,
                'getName',
                $usedValues['id_scene'],
                'Nom de Scène'
            )
         ) {
            $this->storeMsg('requête invalide : propriété absente');
            return false;
        }

        //check if a day entry exist for this date
        if (!$this->checkValid(
                $values['DateLocale'],
                $days,
                'getDateAsRaw',
                $usedValues['id_day'],
                null)
        ) {
            #NO? we must create it
            try {
                $res = $dayManager->insert( [ 'date' => $values['DateLocale'] ]);
            } catch (\Exception $e) {
                $this->storeMsg('Impossible de créer une entrée «jour» pour enregistrer la nouvelle entrée : <br>'
                                . $e->getMessage());
                return false;
            }

            if (!$res) {
                $this->storeMsg('Impossible de créer une entrée «jour» pour enregistrer la nouvelle entrée ...<br>' );
                return false;
            }

            #creation succeded – we still must get back the id
            $usedValues['id_day'] = $dayManager->getIdOfDate($values['DateLocale']);
        }


        try {
            return $concertManager->insert($usedValues);
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
     * @param string $getterName     name of the getter to use to compare data
     * @param &$toVar       variable where the result should be set
     * @param string|null $errName   name of the object for error message,
     *                               or null if nothing is to be shown
     * @return bool
     */
    private function checkValid(
        string $lookedFor,
        array $into,
        string $getterName,
        &$toVar,
        $errName
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
            if (null !== $errName) {
                $this->storeMsg("{$errName} Inconnu «{$lookedFor}» passé en paramètre. Pas d'enregistrement.");
            }
            return false;
        }

        return true;
    }


    /**
     * DeleteOneConcert by Id.
     * @param ConcertManager $concertManager
     * @param DayManager $dayManager
     * @param array $concerts
     * @param array $artists    unused
     * @param array $scenes     unused
     * @param array $days       unused
     * @param array $values     unused
     */
    private function deleteOneConcert(ConcertManager $concertManager,
                                      DayManager $dayManager,
                                      array $concerts,
                                      array $artists,
                                      array $scenes,
                                      array $days,
                                      array $values)
    {
        $id = '';
        if (!$this->checkValid($values['idConcertToDelete'],
                                $concerts,
                    'getId',
                        $id,
                      'Concert')) {
            $this->storeMsg('requête invalide : propriété absente');
            return false;
        };

 //TODO: DELETE THE DAY ENTRY IF NO MORE REFERENCES
        try {
            $concertManager->delete($id);
            return true;
        } catch (\Exception $e) {
            $this->storeMsg('Impossible de supprimer l\'entrée : <br>' . $e->getMessage());
            return false;
        }
    }


    private function modifyOneConcert(int $id)
    {
        $this->storeMsg(__FUNCTION__);
    }
}
