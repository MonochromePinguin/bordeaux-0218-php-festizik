<?php
/**
 * Created by PhpStorm.
 * User: sylvain
 * Date: 07/03/18
 * Time: 18:20
 * PHP version 7
 */

namespace Model;

/**
 *
 */
class AdminBenevolManager extends AbstractManager
{
    const TABLE = 'Article';
    private $articles;

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function editBenevol($id, $data)
    {
        
    }
     
        
}
        