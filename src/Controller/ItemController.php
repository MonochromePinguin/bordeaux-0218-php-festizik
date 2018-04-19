<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 * PHP version 7
 */

namespace Controller;

use Model\Item;
use Model\ItemManager;
use Model\Benevol;
use Model\BenevolManager;

/**
 * Class ItemController
 *
 */
class ItemController extends AbstractController
{

    /**
     * Display item listing
     *
     * @return string
     */
    public function index()
    {
        return $this->twig->render('Item/index.html.twig');
    }

    /**
     * Display item informations specified by $id
     *
     * @param  int $id
     *
     * @return string
     */
    public function show(int $id)
    {
        $itemManager = new ItemManager();
        $item = $itemManager->selectOneById($id);

        return $this->twig->render('Item/show.html.twig', ['item' => $item]);
    }

    /**
     * Display item edition page specified by $id
     *
     * @param  int $id
     *
     * @return string
     */
    public function edit(int $id)
    {
        // TODO : edit item with id $id
        return $this->twig->render('Item/edit.html.twig', ['item', $id]);
    }



    public function benevol()
    {
        // TODO : add a new benevol
        return $this->twig->render('Item/benevol.html.twig');


    }   
    

    public function billetterie()
    {
        // TODO : add a new benevol
        return $this->twig->render('Item/billetterie.html.twig');


    }   




    /**
     * Display item delete page
     *
     * @param  int $id
     *
     * @return string
     */
    public function delete(int $id)
    {
        // TODO : delete the item with id $id
        return $this->twig->render('Item/index.html.twig');
    }

    public function insertedBenevol()
    {
        $BenevolManager = new BenevolManager();
        $benevol = $BenevolManager->insertVolunteer($_POST);
        return $this->twig->render('Item/insertedBenevol.html.twig');
    }

    public function infos()
    {
        // TODO : add a new item
        return $this->twig->render('Item/infos.html.twig');
    }
}
