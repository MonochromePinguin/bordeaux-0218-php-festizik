<?php
/**
 * Created by PhpStorm.
 * User: laiah
 * Date: 04/04/18
 * Time: 11:00
 */

namespace Model;

/**
 *
 */
class AdminManager extends AbstractManager
{
    const TABLE = 'administration';

    /**
     *  Initializes this class
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }
}