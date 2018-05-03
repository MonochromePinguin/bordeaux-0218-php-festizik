<?php
namespace Controller;

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

        $concertManager = new ConcertManager();
        $concertsD1S1 = $concertManager->selectAllByDay(1, 1);
        $concertsD1S2 = $concertManager->selectAllByDay(1, 2);
        $concertsD1S3 = $concertManager->selectAllByDay(1, 3);
        $concertsD2S1 = $concertManager->selectAllByDay(2, 1);
        $concertsD2S2 = $concertManager->selectAllByDay(2, 2);
        $concertsD2S3 = $concertManager->selectAllByDay(2, 3);
        $concertsD3S1 = $concertManager->selectAllByDay(3, 1);
        $concertsD3S2 = $concertManager->selectAllByDay(3, 2);
        $concertsD3S3 = $concertManager->selectAllByDay(3, 3);
        return $this->twig->render('User/concerts.html.twig', ['concerts' => $concertsD1S1,
                                                                     'concerts2' => $concertsD1S2,
                                                                     'concerts3' => $concertsD1S3,
                                                                     'concerts4' => $concertsD2S1,
                                                                     'concerts5' => $concertsD2S2,
                                                                     'concerts6' => $concertsD2S3,
                                                                     'concerts7' => $concertsD3S1,
                                                                     'concerts8' => $concertsD3S2,
                                                                     'concerts9' => $concertsD3S3]);
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
        $infosManager = new ArticleManager();
        $infos = $infosManager->selectAll();

        $title = $infos[1]->getTitle();
        $content = $infos[1]->getContent();
        return $this->twig->render('User/infos.html.twig', ['date'=>$title, 'content'=>$content]);
    }
}
