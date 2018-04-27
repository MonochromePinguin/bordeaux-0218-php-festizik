<?php
namespace Controller;


require_once __DIR__ . '/../Misc/functions.php';
use Model\ArtistManager;
use Model\Concert;
use Model\ConcertManager;
use Model\Benevol;
use Model\BenevolManager;
use Model\ArticleManager;

/**
 * Class UserController
 *
 */
class UserController extends AbstractController
{
    public function index()
    {
        return $this->twig->render('User/index.html.twig');
    }

    public function concerts()
    {
        try {
            $concertManager = new ConcertManager($this->errorStore);
            $concerts = $concertManager->selectAll();

        } catch ( \Exception $e ) {
//TODO: TEST IT WORKS ALSO WITH PDO IN PRODUCTION ENV
            #if something went wrong, show the user some apologies
            echo emergencyPage( 'Désolé ! Une erreur critique est survenue',
                                $e->getMessage() );
            exit;
        }

        $sortBy = null;

        #allow to sort data out of the model, so we save an SQL request
        static $props = null;


        if (null === $props)
            $props = $concertManager::getAvailableSortCriterias();

        if (0 !== count($_GET)) {

            ## the goal of a GET method is to sort the available datas
            # into the controller, thus saving some SQL different requests
            if (isset($_GET['sortBy'])) {
                if (!$concertManager->sortArray($concerts, $_GET['sortBy']))
                    $this->storeMsg(
                        'Le paramètre de tri «' . $_GET['sortBy']
                        . '» n\'est pas valide');
            } else
                $this->storeMsg('Cette page n\'est pas prévue pour être utilisée avec ces paramètres de requête');;
        }


        if (0 !== count($_POST))
            $this->storeMsg('Cette page n\'est pas prévue pour être utilisée avec la méthode POST');


        # retrieve a list of actually used artist names...
        $artistNames = [];
        foreach ($concerts as $concert) {
            $artistNames[] = $concert->getArtist()->getName();
        }
        $artistNames = array_unique($artistNames);
        sort($artistNames);

        return $this->twig->render(
            'User/concerts.html.twig',
            [
                'artistNames' => $artistNames,
                'sortableProperties' => $props,
                'concerts' => $concerts,
                'actualSort' => $sortBy,        #sort criteria actually used, or null if none specified
                'errorList' => $this->errorStore ?
                    $this->errorStore->formatAllMsg() : null
            ]

        );
    }

    public function artists()
    {
        $artistManager = new ArtistManager();
        $artists = $artistManager->selectAll();
        return $this->twig->render('User/artist.html.twig', ['artists' => $artists]);

    }

        public function benevol()
    {
        $benevolManager = new ArticleManager();
        $benevol = $benevolManager->selectAll();

        $title = $benevol[0]->getTitle();
        $content = $benevol[0]->getContent();
        $picture = $benevol[0]->getPicture();
        return $this->twig->render('User/benevol.html.twig', ['question'=>$title, 'beneContent'=>$content, 'picture'=>$picture]);
    }

    public function billetterie()
    {
        return $this->twig->render('User/billetterie.html.twig');
    }

    public function insertedBenevol()
    {
        $BenevolManager = new BenevolManager();
        $benevol = $BenevolManager->insertBenevol($_POST);
        return $this->twig->render('User/insertedBenevol.html.twig');
    }

    public function infos()
    {
        return $this->twig->render('User/infos.html.twig');
    }

}
