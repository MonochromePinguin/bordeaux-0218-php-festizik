<?php

namespace Controller;

use Model\AdminBenevolManager;
use Model\AdminManager;
use Model\ArticleManager;
use Model\AdminInfosManager;
use Model\ArtistManager;
use Model\ConcertManager;
use Model\Concert;
use Model\DayManager;
use Model\SceneManager;
use Model\StyleManager;
use Model\BenevolManager;

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
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }

        if (!isset($_SESSION['username'])  || !empty($errors)) {
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
        //TODO: ADD SESSION TIMING-OUT AND REFRESHING
        session_start();
        if (empty($_SESSION['username'])) {
            return $this->twig->render('Admin/login.html.twig', ['errors' => ['Cette page d\'administration n\'est pas accessible sans identification']]);
        }
        $contentManager = new ArticleManager();
        $benevolManager = new BenevolManager();

        if (isset($_GET['delete'])) {
            $delete = $benevolManager->deleteBenevol($_GET['delete']);
        }
        $benevol = $benevolManager->selectNameId();
        if ($_POST) {
            $data = ['title' => $_POST['title'], 'content' => $_POST['content']];
            if (strlen($_POST['picture']) > 0) {
                $data['picture'] = '/assets/DBimages/' . $_POST['picture'];
            }
            $contentManager->update(6, $data);
        }

        $content = $contentManager->selectOneById(6);
        return $this->twig->render('Admin/adminBenevol.html.twig', ['content' => $content, 'benevol' => $benevol]);
    }
  
  
    public function adminInfos()
    {
        session_start();
        if (empty($_SESSION['username'])) {
            return $this->twig->render('Admin/login.html.twig', ['errors' => ['Cette page d\'administration n\'est pas accessible sans identification']]);
        }
        $infosManager = new ArticleManager();


        if ($_POST) {
            $data = ['title' => $_POST['title'],
                'content' => $_POST['content']];
            $infosManager->update(7, $data);
        }


        $infos = $infosManager->selectAll();
        $title = $infos[1]->getTitle();
        $content = $infos[1]->getContent();
        return $this->twig->render('Admin/adminInfos.html.twig', ['title' => $title, 'content' => $content]);

    }


    public function adminArtist()
    {
        session_start();
        if (empty($_SESSION['username'])) {
            return $this->twig->render('Admin/login.html.twig', ['errors' => ['Cette page d\'administration n\'est pas accessible sans identification']]);
        }

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

    public function benevolContentUpdated()
    {
        session_start();
        if (empty($_SESSION['username'])) {
            return $this->twig->render('Admin/login.html.twig', ['errors' => ['Cette page d\'administration n\'est pas accessible sans identification']]);
        }

        $BenevolManager = new AdminBenevolManager();
        $benevol = $BenevolManager->benevolContentUpdate($_POST);
        return $this->twig->render('Admin/logged.html.twig');
    }

    public function concerts()
    {
        //TODO: ADD SESSION TIMING-OUT AND REFRESHING
        session_start();
        if (empty($_SESSION['username'])) {
            return $this->twig->render('Admin/login.html.twig', ['errors' => ['Cette page d\'administration n\'est pas accessible sans identification']]);
        }

        $sceneManager = new SceneManager($this->errorStore);
        $scenes = $sceneManager->selectAll('name');

        $artistManager = new ArtistManager($this->errorStore);
        $artists = $artistManager->selectAll('name');

        $dayManager = new DayManager($this->errorStore);
        $days = $dayManager->selectAll('date');

        $concertManager = new ConcertManager($this->errorStore);
        $concerts = $concertManager->selectAll();

        //sort criterias sent in the URL
        $sortBy = null;
        $sortInverted = false;

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
            $actionKeys = ['addOneNewConcert', 'deleteOneConcert', 'modifyOneConcert'];
            $actionFunctions = ['addOneConcert', 'deleteOneConcert', 'updateOneConcert'];

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
                        if ($this->$func($concertManager, $dayManager, $concerts, $artists, $scenes, $days, $_POST)) {
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

                #reload the static arrays, because some of them could have changed
                Concert::initStatics();
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

                if (!$concertManager->sortArray($concerts, $sortBy)) {
                    $this->storeMsg('Le paramètre de tri «' . $sortBy . '» n\'est pas valide');
                }
            }

            if (isset($_GET['sortInverted'])) {
                $sortInverted = 'checked';
                $concerts = array_reverse($concerts);
            } else {
                $sortInverted = '';
            }
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
            return $this->twig->render('Admin/concerts.html.twig', ['sortableProperties' => $props,

                'concerts' => $concerts, #these two are used by the template to generate options in select elements
                'sceneNames' => $sceneNameList, 'artistNames' => $artistNameList, 'URLimgs' => json_encode($artistImgList, JSON_UNESCAPED_SLASHES),

                'actualSort' => $sortBy,        #sort criteria actually used, or null if none specified
                'sortInvertedState' => $sortInverted,    # 'checked' or ''

                'errorList' => $this->errorStore ? $this->errorStore->formatAllMsg() : null]);
        } catch (\Exception $e) {
            return generateEmergencyPage('Erreur de génération de la page', [$e->getMessage()]);
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
     *                          concertDate, hour, scene, artist, cancelled
     * @return bool
     */
    private function addOneConcert(ConcertManager $concertManager, DayManager $dayManager, array $concerts, array $artists, array $scenes, array $days, array $values)
    {
        #the values looked up by $concertManager->insert() into $values are:
        #       id_day, hour, id_scene, id_artist, cancelled
        # and the one found into $_POST[] are:
        #       concertDate, hour, scene, artist, cancelled
        $keyList = ['concertDate', 'hour', 'scene', 'artist'];
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

        #check if a day entry exist for this date, create one if not,
        # and get back the id into $usedValues['id_day']
        $usedValues['id_day'] = null;
        if (!$this->getIdForDay($values['concertDate'], $dayManager, $days, $usedValues['id_day'])) {
            $this->storeMsg('Impossible de créer la nouvelle entrée : erreur au sein de AdminController::getIdForDay()');
            return false;
        }

        try {
            #format the hour to the SQL format
            $h = $values['hour'];
            $h[2] = ':';
            $usedValues['hour'] = $h . ':00';

            #Validity checks
            if (!$this->checkValid($values['artist'], $artists, 'getName', $usedValues['id_artist'], 'Artiste') || !$this->checkValid($values['scene'], $scenes, 'getName', $usedValues['id_scene'], 'Nom de Scène')) {
                $this->storeMsg('requête invalide : propriété absente');
                return false;
            }

            return $concertManager->insert($usedValues);
        } catch (\Exception $e) {
            $this->storeMsg('Impossible d\'enregistrer la nouvelle entrée : <br>' . $e->getMessage());
            return false;
        }
    }


    private function updateOneConcert(ConcertManager $concertManager, DayManager $dayManager, array $concerts, array $artists, array $scenes, array $days, array $values)
    {
        #the values looked up by $concertManager->update() into $values[] are:
        #       id_day, hour, id_scene, id_artist, cancelled, idConcertToUpdate
        # and the one found into $_POST[] are:
        #       concertDate, hour, scene, artist, cancelled, idConcertToUpdate
        $keyList = ['concertDate', 'hour', 'scene', 'artist', 'idConcertToUpdate'];
        # REMEMBER : "cancelled" isn't sent if unchecked. So we don't test for its presence

        $idConcertToUpdate = null;
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

        #check if a day entry exist for this date, create one if not,
        # and get back the id into $usedValues['id_day']
        $usedValues['id_day'] = null;
        if (!$this->getIdForDay($values['concertDate'], $dayManager, $days, $usedValues['id_day'])) {
            $this->storeMsg('Impossible de créer la nouvelle entrée : erreur au sein de AdminController::getIdForDay()');
            return false;
        }

        #format the hour to the SQL format
        $h = $values['hour'];
        $h[2] = ':';
        $usedValues['hour'] = $h . ':00';


        #Validity checks
        if (!$this->checkValid($values['artist'], $artists, 'getName', $usedValues['id_artist'], 'Artiste') || !$this->checkValid($values['scene'], $scenes, 'getName', $usedValues['id_scene'], 'Nom de Scène') || !$this->checkValid($values['idConcertToUpdate'], $concerts, 'getId', $idConcertToUpdate, 'id de concert')) {
            $this->storeMsg('requête invalide : propriété absente');
            return false;
        }

        try {
            return $concertManager->update($idConcertToUpdate, $usedValues);
        } catch (\Exception $e) {
            $this->storeMsg('Impossible de modifier entrée : <br>' . $e->getMessage());
            return false;
        }
    }


    /**
     * check if a day entry exist for the date given into $dateToTest,
     * create one if not, and get back the id of the corresponding Day object
     * intointo &toId
     * @param string $dateToTest the date as a string in the SQL format YYYY-MM-DD
     * @param DayManager $dayManager
     * @param array $days the array to parse
     * @param $toId
     * @return bool                     true if is a concert id was send back, false otherwise
     */
    private function getIdForDay(string $dateToTest, DayManager $dayManager, array $days, &$toId): bool
    {
        if ($this->checkValid($dateToTest, $days, 'getDateAsRaw', $toId, null)) {
            return true;
        }

        try {
            $res = $dayManager->insert(['date' => $dateToTest]);
        } catch (\Exception $e) {
            $this->storeMsg('Impossible de créer une entrée «jour» pour enregistrer la nouvelle entrée : <br>' . $e->getMessage());
            return false;
        }

        if (!$res) {
            $this->storeMsg('Impossible de créer une entrée «jour» pour enregistrer la nouvelle entrée ...<br>');
            return false;
        }

        #creation succeded – we still must get back the id and store it
        $toId = $dayManager->getIdOfDate($dateToTest);
        return true;
    }


    /**
     * Basic existence check for differents objects into our lists.
     * We test if one of the objects into $into has its ->getName() returning
     * $lookedFor, and returns an appropriate boolean ;
     * we eventually store an error message into $this->errorStore
     * @param string $lookedFor
     * @param array $into array of examinated objects
     * @param string $getterName name of the getter to use to compare data
     * @param &$toVar       variable where the id of the corresponding object should be set
     * @param string|null $errName name of the object for error message,
     *                               or null if nothing is to be shown
     * @return bool
     */
    private function checkValid(string $lookedFor, array $into, string $getterName, &$toVar, $errName): bool
    {

        foreach ($into as $item) {
            if ($lookedFor == $item->$getterName()) {
                $toVar = $item->getId();
                return true;
            }
        }

        if (null !== $errName) {
            $this->storeMsg("{$errName} Inconnu «{$lookedFor}» passé en paramètre. Pas d'enregistrement.");
        }
        return false;
    }


    /**
     * DeleteOneConcert by Id.
     * @param ConcertManager $concertManager
     * @param DayManager $dayManager
     * @param array $concerts
     * @param array $artists unused
     * @param array $scenes unused
     * @param array $days unused
     * @param array $values unused
     */
    private function deleteOneConcert(ConcertManager $concertManager, DayManager $dayManager, array $concerts, array $artists, array $scenes, array $days, array $values)
    {
        $id = '';
        if (!$this->checkValid($values['idConcertToDelete'], $concerts, 'getId', $id, 'Concert')) {
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
}
