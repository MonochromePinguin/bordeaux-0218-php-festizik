<?php
namespace Model;

/**
 *
 */
class SceneManager extends AbstractManager
{
    const TABLE = 'Scene';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }
}
