<?php
namespace Model;

/**
 *
 */
class SceneManager extends AbstractManager
{
    const TABLE = 'scenes';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE, 'Scene');
    }
}
