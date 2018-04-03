<?php
namespace Model;

/**
 *
 */
class ItemManager extends AbstractManager
{
    const TABLE = 'styles';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE, 'Item');
    }
}
